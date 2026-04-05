<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    /**
     * Display a listing of the Chart of Accounts with balances.
     */
    public function index()
    {
        // Get all accounts with their sum of debits and credits
        $accounts = ChartOfAccount::with(['journalItems'])->get()->map(function ($account) {
            $debits = $account->journalItems->where('type', 'Debit')->sum('amount');
            $credits = $account->journalItems->where('type', 'Credit')->sum('amount');
            
            // Simplified balance calculation based on account type
            // Normal Balance for Asset/Expense = Debit - Credit
            // Normal Balance for Liability/Equity/Revenue = Credit - Debit
            if (in_array($account->type, ['Asset', 'Expense'])) {
                $account->balance = $debits - $credits;
            } else {
                $account->balance = $credits - $debits;
            }
            
            return $account;
        });

        return view('backend.accounting.index', compact('accounts'));
    }

    /**
     * Display the General Journal (all transactions).
     */
    public function journal()
    {
        $entries = JournalEntry::with(['items.account', 'creator'])
            ->latest('entry_date')
            ->latest('id')
            ->paginate(15);

        return view('backend.accounting.journal', compact('entries'));
    }

    /**
     * Display a specific account's ledger.
     */
    public function ledger($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $items = JournalItem::where('chart_of_account_id', $id)
            ->with('entry')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('backend.accounting.ledger', compact('account', 'items'));
    }
}
