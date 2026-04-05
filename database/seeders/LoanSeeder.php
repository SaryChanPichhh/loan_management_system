<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $products = DB::table('loan_products')->pluck('id')->toArray();

        // If no records, fallback to defaults
        if (empty($customers)) $customers = [1, 2, 3, 4, 5];
        if (empty($products)) $products = [1, 2, 3, 4, 5];

        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            // Logic for start dates
            if ($i <= 5) {
                // 5 Loans starting 3-4 months ago (Past)
                $startDate = now()->subMonths(4)->subDays($faker->numberBetween(0, 10));
            } elseif ($i <= 10) {
                // 5 Loans starting exactly 1 month ago (So a payment is due TODAY)
                $startDate = now()->subMonths(1);
            } else {
                // 10 Loans starting recently or in the future
                $startDate = now()->subDays($faker->numberBetween(-30, 30));
            }

            $firstPaymentDate = $startDate->copy()->addMonth();
            $durationMonths = $faker->randomElement([6, 12, 18, 24]);
            $principal = $faker->randomFloat(2, 500, 10000);

            $data[] = [
                'loan_code' => 'LOAN-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'application_id' => null, // Simplified for seeder
                'customer_id' => $faker->randomElement($customers),
                'product_id' => $faker->randomElement($products),
                'principal_amount' => $principal,
                'disbursed_amount' => $principal,
                'interest_rate' => $faker->randomFloat(2, 5, 12),
                'duration_months' => $durationMonths,
                'status' => 'active',
                'purpose' => $faker->randomElement(['Personal Loan', 'Business Expansion', 'Medical Bill', 'Home Renovation']),
                'start_date' => $startDate->toDateString(),
                'end_date' => $startDate->copy()->addMonths($durationMonths)->toDateString(),
                'note' => $faker->sentence(),
                'first_payment_date' => $firstPaymentDate->toDateString(),
                'grace_period_end_date' => $startDate->copy()->addDays(3)->toDateString(),
                'collateral_required' => $principal > 5000,
                'guarantor_required' => $principal > 1000,
                'early_settlement_date' => null,
                'approved_by' => 1,
                'rejected_by' => null,
                'rejected_reason' => null,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('loans')->insert($data);
    }
}
