<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        $approvedApplications = DB::table('loan_applications')
            ->where('status', 'approved')
            ->get(['id', 'customer_id', 'product_id']);
        $users = DB::table('users')->pluck('id')->toArray();

        if ($approvedApplications->isEmpty()) {
            return;
        }

        $statuses = ['approved', 'active', 'completed', 'defaulted', 'written_off', 'rejected'];
        $data = [];
        $applicationIdsForLink = [];
        $sequence = 1;

        foreach ($approvedApplications as $application) {
            $duration = $faker->numberBetween(6, 72);
            $principal = $faker->randomFloat(2, 1000, 100000);
            $disbursed = round($principal * $faker->randomFloat(4, 0.82, 1), 2);
            $status = $faker->randomElement($statuses);
            $startDate = $faker->dateTimeBetween('-2 years', '-2 months');
            $firstPayment = (clone $startDate)->modify('+1 month');
            $endDate = (clone $startDate)->modify('+' . $duration . ' months');
            $isRejected = $status === 'rejected';
            $isApprovedState = in_array($status, ['approved', 'active', 'completed', 'defaulted', 'written_off'], true);
            $reviewer = !empty($users) ? $users[array_rand($users)] : null;

            $data[] = [
                'loan_code' => 'L-' . str_pad((string) $sequence, 6, '0', STR_PAD_LEFT),
                'application_id' => $application->id,
                'customer_id' => $application->customer_id,
                'product_id' => $application->product_id,
                'principal_amount' => $principal,
                'disbursed_amount' => $disbursed,
                'interest_rate' => $faker->randomFloat(2, 1, 15),
                'duration_months' => $duration,
                'status' => $status,
                'purpose' => $faker->sentence(),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'note' => $faker->sentence(),
                'first_payment_date' => $firstPayment->format('Y-m-d'),
                'grace_days' => $faker->numberBetween(3, 7),
                'collateral_required' => $faker->boolean(),
                'guarantor_required' => $faker->boolean(),
                'early_settlement_date' => null,
                'approved_by' => $isApprovedState ? $reviewer : null,
                'rejected_by' => $isRejected ? $reviewer : null,
                'rejected_reason' => $isRejected ? $faker->sentence(8) : null,
                'created_by' => $reviewer,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $applicationIdsForLink[] = $application->id;
            $sequence++;
        }

        DB::table('loans')->insert($data);

        // Connect approved applications to the created loan records.
        DB::table('loan_applications')
            ->whereIn('id', $applicationIdsForLink)
            ->update([
                'loan_id' => DB::raw('(SELECT id FROM loans WHERE loans.application_id = loan_applications.id LIMIT 1)'),
                'updated_at' => now(),
            ]);
    }
}
