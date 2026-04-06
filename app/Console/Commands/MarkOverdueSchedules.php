<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanSchedule;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarkOverdueSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily job to mark loan schedules as overdue and update accounts accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $this->info("Starting overdue marking job at {$today->toDateString()}");

        // Find schedules where due_date < today AND status is pending/partial AND loan is active
        $overdueSchedules = LoanSchedule::whereIn('status', ['pending', 'partial'])
            ->whereDate('due_date', '<', $today)
            ->whereHas('loan', function ($q) {
                $q->where('status', 'active');
            })
            ->get();

        if ($overdueSchedules->isEmpty()) {
            $this->info("No overdue schedules found.");
            return 0;
        }

        $loansAffectedIds = [];
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($overdueSchedules as $schedule) {
                // Update schedule status to overdue
                $schedule->update([
                    'status' => 'overdue'
                ]);
                $updatedCount++;
                
                if (!in_array($schedule->loan_id, $loansAffectedIds)) {
                    $loansAffectedIds[] = $schedule->loan_id;
                }
            }

            $notificationsCreated = 0;
            
            // For every affected loan, update the account aggregates and process notifications
            foreach ($loansAffectedIds as $loanId) {
                $loan = Loan::with(['account'])->find($loanId);
                
                if (!$loan || !$loan->account) continue;

                // 1. Calculate days_past_due: today minus earliest unpaid due_date
                $earliestUnpaid = LoanSchedule::where('loan_id', $loanId)
                    ->whereIn('status', ['pending', 'partial', 'overdue'])
                    ->orderBy('due_date')
                    ->first();

                $daysPastDue = 0;
                if ($earliestUnpaid) {
                    $due = Carbon::parse($earliestUnpaid->due_date);
                    $daysPastDue = max(0, (int) $due->diffInDays($today, false));
                }

                // 2. Sum of unpaid amount_due from all overdue schedules
                // unpaid amount = amount_due - amount_paid
                $overdueSchedulesForLoan = LoanSchedule::where('loan_id', $loanId)
                    ->where('status', 'overdue')
                    ->get();
                    
                $overdueAmount = $overdueSchedulesForLoan->sum(function ($s) {
                    return (float) $s->amount_due - (float) $s->amount_paid;
                });

                // Update the LoanAccount record
                $loan->account->update([
                    'days_past_due' => $daysPastDue,
                    'overdue_amount' => round($overdueAmount, 2),
                ]);

                // 3. Notifications logic
                // If days_past_due > 30 on any loan, create a notification warning staff to contact guarantor
                if ($daysPastDue > 30) {
                    // Prevent duplicate notifications in the last 7 days
                    $recentGuarantorNotif = Notification::where('customer_id', $loan->customer_id)
                        ->where('type', 'WARNING_GUARANTOR')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->exists();

                    if (!$recentGuarantorNotif) {
                        Notification::create([
                            'customer_id' => $loan->customer_id,
                            'title' => 'Warning: Loan Overdue > 30 Days',
                            'message' => "កម្ចី {$loan->loan_code} ហួសកំណត់ {$daysPastDue} ថ្ងៃ។ សូមទាក់ទងអ្នកធានា (Guarantor)។",
                            'type' => 'WARNING_GUARANTOR',
                            'is_read' => 0,
                            'target_user' => 'admin',
                        ]);
                        $notificationsCreated++;
                    }
                }

                // If days_past_due > 60 and loan has collateral, create notifications record to begin collateral claim
                if ($daysPastDue > 60 && $loan->collateral_required) {
                    $recentCollateralNotif = Notification::where('customer_id', $loan->customer_id)
                        ->where('type', 'WARNING_COLLATERAL')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->exists();

                    if (!$recentCollateralNotif) {
                        Notification::create([
                            'customer_id' => $loan->customer_id,
                            'title' => 'Critical: Loan Overdue > 60 Days (Collateral Claim)',
                            'message' => "កម្ចី {$loan->loan_code} ហួសកំណត់ {$daysPastDue} ថ្ងៃ។ សូមចាប់ផ្តើមនីតិវិធីរឹបអូសទ្រព្យបញ្ចាំ (Collateral Claim)។",
                            'type' => 'WARNING_COLLATERAL',
                            'is_read' => 0,
                            'target_user' => 'admin',
                        ]);
                        $notificationsCreated++;
                    }
                }
            }

            // Record summary to activity_logs
            ActivityLog::create([
                'user_name' => 'System (Cron)',
                'action' => 'MARK_OVERDUE_SCHEDULES',
                'description' => "Marked {$updatedCount} schedules as overdue across " . count($loansAffectedIds) . " loans. Processed {$notificationsCreated} new alerts.",
                'ip_address' => '127.0.0.1'
            ]);

            DB::commit();
            $this->info("Job completed successfully. {$updatedCount} schedules marked overdue. {$notificationsCreated} notifications sent.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error marking overdue schedules: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
