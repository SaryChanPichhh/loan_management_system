<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoanApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $products = DB::table('loan_products')->pluck('id')->toArray();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        if (empty($products) || empty($customers)) {
            return;
        }

        $purposes = [
            'Working capital for micro business',
            'Equipment purchase',
            'Agricultural investment',
            'Home improvement',
            'Education expenses',
            'Vehicle purchase',
        ];
        $statuses = ['pending', 'under_review', 'approved', 'rejected'];
        $total = 320;

        $data = [];
        for ($i = 1; $i <= $total; $i++) {
            $status = $faker->randomElement($statuses);
            $requestedMonths = $faker->numberBetween(6, 84);
            $reviewerId = !empty($users) ? $users[array_rand($users)] : null;
            $reviewedAt = in_array($status, ['approved', 'rejected'], true)
                ? $faker->dateTimeBetween('-1 year', 'now')
                : null;
            $createdAt = $faker->dateTimeBetween('-2 years', '-7 days');
            $startDate = $faker->dateTimeBetween('-1 year', '+3 months');
            $endDate = (clone $startDate)->modify('+' . $requestedMonths . ' months');

            $data[] = [
                'application_code' => 'APP-' . date('Ym') . '-' . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customers[array_rand($customers)],
                'product_id' => $products[array_rand($products)],
                'requested_amount' => $faker->randomFloat(2, 500, 100000),
                'requested_months' => $requestedMonths,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'purpose' => $faker->randomElement($purposes),
                'status' => $status,
                'reviewed_by' => $reviewedAt ? $reviewerId : null,
                'reviewed_at' => $reviewedAt,
                'rejection_reason' => $status === 'rejected' ? $faker->sentence(8) : null,
                'loan_id' => null,
                'created_by' => !empty($users) ? $users[array_rand($users)] : null,
                'created_at' => $createdAt,
                'updated_at' => $reviewedAt ?? $createdAt,
            ];
        }

        DB::table('loan_applications')->insert($data);
    }
}
