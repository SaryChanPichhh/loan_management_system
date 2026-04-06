<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoanScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $loans = DB::table('loans')->get(['id', 'duration_months', 'principal_amount', 'interest_rate', 'first_payment_date', 'status']);
        if ($loans->isEmpty()) {
            return;
        }

        $data = [];

        foreach ($loans as $loan) {
            $installments = max(6, min((int) $loan->duration_months, 36));
            $baseDate = $loan->first_payment_date ? new \DateTime($loan->first_payment_date) : new \DateTime();
            $principalDue = round($loan->principal_amount / $installments, 2);
            $interestDue = round(($loan->principal_amount * ((float) $loan->interest_rate / 100)) / 12, 2);

            for ($j = 1; $j <= $installments; $j++) {
                $status = match ($loan->status) {
                    'completed' => 'paid',
                    'active' => $j <= (int) floor($installments * 0.45) ? 'paid' : ($j === (int) floor($installments * 0.45) + 1 ? 'partial' : 'pending'),
                    'defaulted', 'written_off' => $j <= 2 ? 'paid' : ($j <= 4 ? 'overdue' : 'pending'),
                    default => 'pending',
                };

                $amountDue = round($principalDue + $interestDue, 2);
                $paidAmount = match ($status) {
                    'paid' => $amountDue,
                    'partial' => round($amountDue * $faker->randomFloat(4, 0.2, 0.8), 2),
                    default => 0,
                };

                $dueDate = (clone $baseDate)->modify('+' . ($j - 1) . ' months');

                $data[] = [
                    'loan_id' => $loan->id,
                    'installment_number' => $j,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'amount_due' => $amountDue,
                    'principal_due' => $principalDue,
                    'interest_due' => $interestDue,
                    'penalty_due' => 0,
                    'late_fee_due' => 0,
                    'amount_paid' => $paidAmount,
                    'paid_date' => in_array($status, ['paid', 'partial'], true) ? (clone $dueDate)->modify('-1 day')->format('Y-m-d') : null,
                    'grace_period_end_date' => (clone $dueDate)->modify('+3 days')->format('Y-m-d'),
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
