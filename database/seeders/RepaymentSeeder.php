<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repayment;

class RepaymentSeeder extends Seeder
{
    public function run(): void
    {
        $methods = ['Cash', 'ABA Bank', 'Wing', 'Acleda'];
        $statuses = ['Paid', 'Pending', 'Failed'];
        
        for ($i = 1; $i <= 20; $i++) {
            Repayment::create([
                'loan_reference' => 'LN-' . str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT),
                'customer_name' => fake()->name(),
                'amount' => fake()->randomFloat(2, 50, 1500),
                'payment_date' => fake()->dateTimeBetween('-2 months', 'now'),
                'payment_method' => $methods[array_rand($methods)],
                'reference_number' => 'REF-' . strtoupper(fake()->lexify('????')) . '-' . fake()->numerify('####'),
                'points' => fake()->numberBetween(0, 50),
                'status' => $statuses[array_rand($statuses)],
                'notes' => fake()->optional()->sentence()
            ]);
        }
    }
}
