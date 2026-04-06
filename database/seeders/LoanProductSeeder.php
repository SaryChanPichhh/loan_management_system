<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $products = [
            [
                'product_code' => 'LP-PERSONAL',
                'name' => 'Personal Loan',
                'description' => 'Personal needs',
                'min_amount' => 100,
                'max_amount' => 5000,
                'interest_rate' => 1.5,
                'interest_type' => 'REDUCING_BALANCE',
                'max_term_months' => 24,
                'grace_period_days' => 5,
                'late_fee_rate' => 2.0,
                'requires_guarantor_above' => 500,
                'guarantor_income_multiplier' => 1.5,
                'requires_collateral_above' => 5000,
                'penalty_rate' => 1.0,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_code' => 'LP-BUSINESS',
                'name' => 'Business Loan',
                'description' => 'Business capital',
                'min_amount' => 500,
                'max_amount' => 20000,
                'interest_rate' => 1.2,
                'interest_type' => 'REDUCING_BALANCE',
                'max_term_months' => 36,
                'grace_period_days' => 5,
                'late_fee_rate' => 2.0,
                'requires_guarantor_above' => 1000,
                'guarantor_income_multiplier' => 2.0,
                'requires_collateral_above' => 5000,
                'penalty_rate' => 1.0,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_code' => 'LP-EMERGENCY',
                'name' => 'Emergency Loan',
                'description' => 'Urgent needs',
                'min_amount' => 50,
                'max_amount' => 1000,
                'interest_rate' => 1.8,
                'interest_type' => 'FLAT',
                'max_term_months' => 6,
                'grace_period_days' => 2,
                'late_fee_rate' => 2.5,
                'requires_guarantor_above' => 300,
                'guarantor_income_multiplier' => 0,
                'requires_collateral_above' => 2000,
                'penalty_rate' => 1.5,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_code' => 'LP-SALARY',
                'name' => 'Salary Advance',
                'description' => 'Employees only',
                'min_amount' => 50,
                'max_amount' => 2000,
                'interest_rate' => 1.0,
                'interest_type' => 'FLAT',
                'max_term_months' => 12,
                'grace_period_days' => 3,
                'late_fee_rate' => 1.5,
                'requires_guarantor_above' => 0,
                'guarantor_income_multiplier' => 1.5,
                'requires_collateral_above' => 0,
                'penalty_rate' => 0.5,
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        foreach ($products as $product) {
            \App\Models\LoanProduct::updateOrCreate(
                ['product_code' => $product['product_code']],
                $product
            );
        }
    }
}
