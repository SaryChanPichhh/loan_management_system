<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoanAccountSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $loans = DB::table('loans')->get(['id', 'principal_amount', 'disbursed_amount', 'status']);
        if ($loans->isEmpty()) {
            return;
        }

        $data = [];
        foreach ($loans as $i => $loan) {
            $paidRatio = match ($loan->status) {
                'completed' => $faker->randomFloat(4, 0.95, 1.00),
                'active', 'approved' => $faker->randomFloat(4, 0.10, 0.75),
                'defaulted', 'written_off' => $faker->randomFloat(4, 0.05, 0.45),
                default => $faker->randomFloat(4, 0.00, 0.30),
            };
            $principalPaid = round($loan->principal_amount * $paidRatio, 2);
            $outstanding = max(0, round($loan->principal_amount - $principalPaid, 2));

            $data[] = [
                'loan_id' => $loan->id,
                'account_number' => 'ACC-' . date('Y') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'outstanding_balance' => $outstanding,
                'total_principal_paid' => $principalPaid,
                'total_interest_paid' => round($principalPaid * $faker->randomFloat(4, 0.03, 0.18), 2),
                'total_penalty_paid' => round($faker->randomFloat(2, 0, 120), 2),
                'total_late_fee_paid' => round($faker->randomFloat(2, 0, 60), 2),
                'overdue_amount' => in_array($loan->status, ['defaulted', 'written_off'], true)
                    ? round($outstanding * $faker->randomFloat(4, 0.20, 0.65), 2)
                    : 0,
                'days_past_due' => in_array($loan->status, ['defaulted', 'written_off'], true)
                    ? $faker->numberBetween(15, 180)
                    : $faker->numberBetween(0, 12),
                'last_payment_at' => now()->subDays($faker->numberBetween(1, 120)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('loan_accounts')->insert($data);
        }
    }
}
