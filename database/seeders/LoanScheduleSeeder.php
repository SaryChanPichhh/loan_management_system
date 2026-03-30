<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];
        
        $loans = array_slice($loans, 0, 5);
        $data = [];

        foreach ($loans as $loanId) {
            for ($j = 1; $j <= 5; $j++) {
                $status = $j <= 2 ? 'paid' : ($j == 3 ? 'partial' : 'pending');
                $principal = 100;
                $interest = 15;
                $amount_due = $principal + $interest;
                $paid_amount = $status === 'paid' ? $amount_due : ($status === 'partial' ? 50 : 0);

                $data[] = [
                    'loan_id' => $loanId,
                    'installment_number' => $j,
                    'due_date' => now()->addMonths($j)->format('Y-m-d'),
                    'amount_due' => $amount_due,
                    'principal_due' => $principal,
                    'interest_due' => $interest,
                    'penalty_due' => 0,
                    'late_fee_due' => 0,
                    'amount_paid' => $paid_amount,
                    'paid_date' => $status === 'paid' ? now()->addMonths($j)->subDays(1) : null,
                    'is_grace_period' => false,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($data)) {
            DB::table('loan_schedules')->insert($data);
        }
    }
}
