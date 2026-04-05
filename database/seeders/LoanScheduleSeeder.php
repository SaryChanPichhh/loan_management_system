<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->get();
        if ($loans->isEmpty()) return;

        foreach ($loans as $loan) {
            $startDate = \Carbon\Carbon::parse($loan->first_payment_date);
            
            for ($j = 1; $j <= $loan->duration_months; $j++) {
                $dueDate = $startDate->copy()->addMonths($j - 1);
                $isToday = $dueDate->isToday();
                $isPast = $dueDate->isPast() && !$isToday;

                // Simple math for schedule
                $principalDue = round($loan->principal_amount / $loan->duration_months, 2);
                $interestDue = round(($loan->principal_amount * ($loan->interest_rate / 100)) / 12, 2);
                $amountDue = $principalDue + $interestDue;

                // Logic for payment status
                if ($isPast) {
                    // 80% chance it's paid if it's in the past
                    $isPaid = rand(1, 10) <= 8;
                    $status = $isPaid ? 'paid' : 'overdue';
                    $paidAmount = $isPaid ? $amountDue : 0;
                    $paidDate = $isPaid ? $dueDate->copy()->subDays(rand(0, 5)) : null;
                } elseif ($isToday) {
                    // For demo, some are pending, one partial
                    $status = ($j % 3 == 0) ? 'partial' : 'pending';
                    $paidAmount = ($status === 'partial') ? round($amountDue / 2, 2) : 0;
                    $paidDate = ($status === 'partial') ? $dueDate : null;
                } else {
                    $status = 'pending';
                    $paidAmount = 0;
                    $paidDate = null;
                }

                DB::table('loan_schedules')->updateOrInsert(
                    [
                        'loan_id' => $loan->id,
                        'installment_number' => $j,
                    ],
                    [
                        'due_date' => $dueDate->format('Y-m-d'),
                        'amount_due' => $amountDue,
                        'principal_due' => $principalDue,
                        'interest_due' => $interestDue,
                        'penalty_due' => 0,
                        'late_fee_due' => 0,
                        'amount_paid' => $paidAmount,
                        'paid_date' => $paidDate ? $paidDate->format('Y-m-d') : null,
                        'is_grace_period' => false,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
