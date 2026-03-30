<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'product_code' => 'LP-PERSONAL',
                'name' => 'ប្រាក់កម្ចីផ្ទាល់ខ្លួន (Personal)',
                'description' => 'សម្រាប់តម្រូវការផ្ទាល់ខ្លួន ការសិក្សា ព្យាបាលជំងឺ',
                'min_amount' => 100,
                'max_amount' => 2000,
                'interest_rate' => 1.5,
                'interest_type' => 'REDUCING_BALANCE',
                'max_term_months' => 24,
                'grace_period_days' => 5,
                'late_fee_rate' => 2.0,
                'requires_guarantor_above' => 500,
                'requires_collateral_above' => 5000,
                'penalty_rate' => 1.0,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => 'LP-MOTO',
                'name' => 'ប្រាក់កម្ចីទិញម៉ូតូ (Motorcycle)',
                'description' => 'សម្រាប់គោលបំណងទិញម៉ូតូថ្មី ឬមួយទឹក',
                'min_amount' => 500,
                'max_amount' => 3000,
                'interest_rate' => 1.2,
                'interest_type' => 'FLAT',
                'max_term_months' => 36,
                'grace_period_days' => 3,
                'late_fee_rate' => 1.5,
                'requires_guarantor_above' => 1000,
                'requires_collateral_above' => 3000,
                'penalty_rate' => 0.5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => 'LP-BUSINESS',
                'name' => 'ប្រាក់កម្ចីអាជីវកម្ម (Business)',
                'description' => 'សម្រាប់ពង្រីកអាជីវកម្ម និងទុនបង្វិល',
                'min_amount' => 1000,
                'max_amount' => 50000,
                'interest_rate' => 1.0,
                'interest_type' => 'REDUCING_BALANCE',
                'max_term_months' => 60,
                'grace_period_days' => 7,
                'late_fee_rate' => 2.5,
                'requires_guarantor_above' => 2000,
                'requires_collateral_above' => 5000,
                'penalty_rate' => 1.0,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => 'LP-HOME',
                'name' => 'ប្រាក់កម្ចីលំនៅដ្ឋាន (Home)',
                'description' => 'សម្រាប់ការសាងសង់ ជួសជុល ឬទិញផ្ទះ',
                'min_amount' => 5000,
                'max_amount' => 100000,
                'interest_rate' => 0.8,
                'interest_type' => 'REDUCING_BALANCE',
                'max_term_months' => 120,
                'grace_period_days' => 7,
                'late_fee_rate' => 1.5,
                'requires_guarantor_above' => 5000,
                'requires_collateral_above' => 0,
                'penalty_rate' => 0.5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_code' => 'LP-AGRI',
                'name' => 'ប្រាក់កម្ចីកសិកម្ម (Agriculture)',
                'description' => 'សម្រាប់ទិញជី គ្រាប់ពូជ និងសម្ភារកសិកម្ម',
                'min_amount' => 200,
                'max_amount' => 5000,
                'interest_rate' => 1.3,
                'interest_type' => 'FLAT',
                'max_term_months' => 12,
                'grace_period_days' => 3,
                'late_fee_rate' => 1.5,
                'requires_guarantor_above' => 1000,
                'requires_collateral_above' => 5000,
                'penalty_rate' => 0.5,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('loan_products')->insert($products);
    }
}
