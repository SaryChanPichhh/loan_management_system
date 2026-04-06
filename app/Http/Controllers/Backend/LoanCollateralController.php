<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanCollateral;
use App\Models\LoanCollateralDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LoanCollateralController extends Controller
{
    /**
     * Collateral threshold: must be ≥ 120% of loan principal.
     */
    private const COVERAGE_RATIO = 1.20;

    // ─────────────────────────────────────────────────────────────────
    // INDEX — show all collaterals for a loan
    // ─────────────────────────────────────────────────────────────────

    public function index($loan_id)
    {
        $loan = Loan::with(['customer', 'product'])->findOrFail($loan_id);

        $collaterals = LoanCollateral::with('docs.uploader')
            ->where('loan_id', $loan->id)
            ->latest()
            ->get();

        $principal      = (float) $loan->principal_amount;
        $requiredValue  = round($principal * self::COVERAGE_RATIO, 2);
        $totalValue     = (float) $collaterals->where('status', 'active')->sum('estimated_value');
        $isSufficient   = $totalValue >= $requiredValue;
        $requiresCollateral = $principal > 5000;

        return view('backend.loans.collaterals', compact(
            'loan', 'collaterals',
            'principal', 'requiredValue', 'totalValue',
            'isSufficient', 'requiresCollateral'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — add a new collateral record
    // ─────────────────────────────────────────────────────────────────

    public function store(Request $request, $loan_id)
    {
        $request->validate([
            'collateral_type'  => 'required|string|max:100',
            'description'      => 'required|string|max:1000',
            'estimated_value'  => 'required|numeric|min:0.01',
            'valuation_date'   => 'required|date',
        ]);

        $loan = Loan::findOrFail($loan_id);

        LoanCollateral::create([
            'loan_id'          => $loan->id,
            'collateral_type'  => $request->collateral_type,
            'description'      => $request->description,
            'estimated_value'  => $request->estimated_value,
            'valuation_date'   => $request->valuation_date,
            'status'           => 'active',
        ]);

        // Recheck 120% coverage
        $warning = $this->checkCoverage($loan);

        return redirect()
            ->route('loans.collaterals.index', $loan->id)
            ->with('success', 'Collateral added successfully.')
            ->with('coverage_warning', $warning);
    }

    // ─────────────────────────────────────────────────────────────────
    // UPDATE — edit collateral fields
    // ─────────────────────────────────────────────────────────────────

    public function update(Request $request, $collateral_id)
    {
        $request->validate([
            'collateral_type'  => 'required|string|max:100',
            'description'      => 'required|string|max:1000',
            'estimated_value'  => 'required|numeric|min:0.01',
            'valuation_date'   => 'required|date',
            'status'           => 'required|in:active,released,seized',
        ]);

        $collateral = LoanCollateral::findOrFail($collateral_id);
        $collateral->update($request->only([
            'collateral_type', 'description',
            'estimated_value', 'valuation_date', 'status',
        ]));

        $loan    = $collateral->loan;
        $warning = $this->checkCoverage($loan);

        return redirect()
            ->route('loans.collaterals.index', $loan->id)
            ->with('success', 'Collateral updated successfully.')
            ->with('coverage_warning', $warning);
    }

    // ─────────────────────────────────────────────────────────────────
    // DESTROY — hard delete collateral (and cascade docs via DB)
    // ─────────────────────────────────────────────────────────────────

    public function destroy($collateral_id)
    {
        $collateral = LoanCollateral::with('docs')->findOrFail($collateral_id);
        $loan       = $collateral->loan;

        // Delete physical files first
        foreach ($collateral->docs as $doc) {
            Storage::disk('local')->delete($doc->storage_path);
        }

        $collateral->delete(); // cascades docs via FK

        $warning = $this->checkCoverage($loan);

        return redirect()
            ->route('loans.collaterals.index', $loan->id)
            ->with('success', 'Collateral removed.')
            ->with('coverage_warning', $warning);
    }

    // ─────────────────────────────────────────────────────────────────
    // UPLOAD DOC — store a file for a collateral
    // ─────────────────────────────────────────────────────────────────

    public function uploadDoc(Request $request, $collateral_id)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $collateral = LoanCollateral::findOrFail($collateral_id);

        $file     = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path     = $file->storeAs('collateral_docs', $fileName, 'local');  // local disk → storage/app/private/

        LoanCollateralDoc::create([
            'collateral_id'   => $collateral->id,
            'document_type'   => $request->document_type,
            'file_name'       => $file->getClientOriginalName(),
            'storage_path'    => $path,
            'mime_type'       => $file->getMimeType(),
            'file_size_bytes' => $file->getSize(),
            'uploaded_by'     => Auth::id(),
            'uploaded_at'     => now(),
        ]);

        return redirect()
            ->route('loans.collaterals.index', $collateral->loan_id)
            ->with('success', 'Document uploaded successfully.');
    }

    // ─────────────────────────────────────────────────────────────────
    // DELETE DOC — remove a document file + record
    // ─────────────────────────────────────────────────────────────────

    public function deleteDoc($doc_id)
    {
        $doc        = LoanCollateralDoc::findOrFail($doc_id);
        $loan_id    = $doc->collateral->loan_id;

        Storage::disk('local')->delete($doc->storage_path);
        $doc->delete();

        return redirect()
            ->route('loans.collaterals.index', $loan_id)
            ->with('success', 'Document deleted.');
    }

    // ─────────────────────────────────────────────────────────────────
    // DOWNLOAD DOC — secure file download
    // ─────────────────────────────────────────────────────────────────

    public function downloadDoc($doc_id)
    {
        $doc = LoanCollateralDoc::findOrFail($doc_id);

        if (!Storage::disk('local')->exists($doc->storage_path)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return Storage::disk('local')->download($doc->storage_path, $doc->file_name);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE HELPER — check 120% rule
    // ─────────────────────────────────────────────────────────────────

    /**
     * Returns a warning string if active collateral is < 120% of principal,
     * or null if threshold is met.
     */
    private function checkCoverage(Loan $loan): ?string
    {
        $principal     = (float) $loan->principal_amount;
        $required      = round($principal * self::COVERAGE_RATIO, 2);
        $activeTotal   = (float) LoanCollateral::where('loan_id', $loan->id)
            ->where('status', 'active')
            ->sum('estimated_value');

        if ($principal > 5000 && $activeTotal < $required) {
            $shortage = number_format($required - $activeTotal, 2);
            return "⚠️ Collateral coverage is insufficient. Required: \${$required}, Current: \$"
                . number_format($activeTotal, 2)
                . ". Shortage: \${$shortage}";
        }

        return null;
    }
}
