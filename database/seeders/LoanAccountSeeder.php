<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanAccountSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];
        
        // Ensure unique loan IDs and limit to 5
        $loans = array_slice($loans, 0, 5);

        $data = [];
        foreach ($loans as $i => $loanId) {
            $data[] = [
                'loan_id' => $loanId,
                'account_number' => 'ACC-' . date('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'outstanding_balance' => rand(500, 5000),
                'total_principal_paid' => rand(100, 1000),
                'total_interest_paid' => rand(50, 200),
                'total_penalty_paid' => 0,
                'total_late_fee_paid' => 0,
                'overdue_amount' => 0,
                'days_past_due' => rand(0, 5),
                'last_payment_at' => now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('loan_accounts')->insert($data);
        }
    }
}
