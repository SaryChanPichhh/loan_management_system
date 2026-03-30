<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class RepaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $data[] = [
                'amount' => $faker->randomFloat(2, 100, 1000),
                'payment_date' => $faker->date('Y-m-d'),
                'payment_method' => $faker->randomElement(['Cash', 'Bank Transfer']),
                'reference_number' => $faker->isbn10(),
                'status' => 'completed',
                'notes' => $faker->sentence(),
                'loan_id' => $faker->numberBetween(1, 5),
                'schedule_id' => $faker->numberBetween(1, 5),
                'principal_paid' => $faker->randomFloat(2, 50, 800),
                'interest_paid' => $faker->randomFloat(2, 10, 200),
                'penalty_paid' => 0,
                'late_fee_paid' => 0,
                'late_fee_applied' => 0,
                'is_early_settlement' => 0,
                'received_by' => 1,
                'waived_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('repayments')->insert($data);
    }
}
