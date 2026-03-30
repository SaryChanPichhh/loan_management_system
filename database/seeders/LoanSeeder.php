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
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $data[] = [
                'loan_code' => 'L-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'application_id' => $faker->numberBetween(1, 5),
                'customer_id' => $faker->numberBetween(1, 5),
                'product_id' => $faker->numberBetween(1, 5),
                'principal_amount' => $faker->randomFloat(2, 1000, 50000),
                'disbursed_amount' => $faker->randomFloat(2, 1000, 50000),
                'interest_rate' => $faker->randomFloat(2, 1, 15),
                'duration_months' => $faker->numberBetween(6, 60),
                'status' => $faker->randomElement(['approved', 'pending', 'rejected', 'active']),
                'purpose' => $faker->sentence(),
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->date('Y-m-d', '+1 year'),
                'note' => $faker->sentence(),
                'first_payment_date' => $faker->date('Y-m-d', '+1 month'),
                'grace_period_end_date' => $faker->date('Y-m-d'),
                'collateral_required' => $faker->boolean(),
                'guarantor_required' => $faker->boolean(),
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
