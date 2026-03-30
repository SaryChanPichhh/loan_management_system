<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Prevent duplicate user creation if seeded multiple times
        if (User::where('email', 'test@example.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            CustomerSeeder::class,
            ExchangeRateSeeder::class,
            ActivityLogSeeder::class,
            NotificationSeeder::class,

            // Base dependent tables
            LoanProductSeeder::class,
            GuarantorSeeder::class,
            LoanApplicationSeeder::class,

            // Core loan table
            LoanSeeder::class,

            // Tables dependent on loans
            LoanAccountSeeder::class,
            LoanScheduleSeeder::class,
            LoanDisbursementSeeder::class,
            LoanCollateralSeeder::class,
            LoanFeeSeeder::class,
            LoanDocumentSeeder::class,

            // Further downstream dependent tables
            LoanCollateralDocSeeder::class,
            TransactionSeeder::class,

            // Other existing seeders
            RepaymentSeeder::class,
        ]);
    }
}
