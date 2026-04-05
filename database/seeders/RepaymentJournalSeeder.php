<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repayment;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;

class RepaymentJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch base accounts
        $coaCash = ChartOfAccount::where('code', '1000')->first();
        $coaPrincipal = ChartOfAccount::where('code', '1100')->first();
        $coaInterest = ChartOfAccount::where('code', '4000')->first();

        if (!$coaCash || !$coaPrincipal || !$coaInterest) {
            $this->command->error('Chart of Accounts (1000, 1100, 4000) not found. Please run ChartOfAccountSeeder first.');
            return;
        }

        // Get all repayments
        $repayments = Repayment::all();

        $count = 0;
        foreach ($repayments as $repayment) {
            // Check if journal entry exists for this repayment
            $exists = JournalEntry::where('reference_type', 'Repayment')
                ->where('reference_id', $repayment->id)
                ->exists();

            if ($exists) continue;

            DB::beginTransaction();
            try {
                $journalEntry = JournalEntry::create([
                    'entry_date' => $repayment->payment_date,
                    'reference_type' => 'Repayment',
                    'reference_id' => $repayment->id,
                    'description' => "Historical Repayment Sync - Loan [" . ($repayment->loan->loan_code ?? 'N/A') . "]",
                    'total_amount' => $repayment->amount,
                    'created_by' => $repayment->received_by ?? 1, // Default to admin if user missing
                ]);

                // Debit Cash (Total)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'chart_of_account_id' => $coaCash->id,
                    'type' => 'Debit',
                    'amount' => $repayment->amount,
                ]);

                // Credit Principal
                if ($repayment->principal_paid > 0) {
                    JournalItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'chart_of_account_id' => $coaPrincipal->id,
                        'type' => 'Credit',
                        'amount' => $repayment->principal_paid,
                    ]);
                }

                // Credit Interest
                if ($repayment->interest_paid > 0) {
                    JournalItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'chart_of_account_id' => $coaInterest->id,
                        'type' => 'Credit',
                        'amount' => $repayment->interest_paid,
                    ]);
                }

                DB::commit();
                $count++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Failed to Sync Repayment ID {$repayment->id}: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully synced {$count} repayments to the General Journal.");
    }
}
