<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * @throws \Throwable
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Prevent duplicate user creation
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

                LoanProductSeeder::class,
                GuarantorSeeder::class,
                LoanApplicationSeeder::class,

                LoanSeeder::class,

                LoanAccountSeeder::class,
                LoanScheduleSeeder::class,
                LoanDisbursementSeeder::class,
                LoanCollateralSeeder::class,
                LoanFeeSeeder::class,
                LoanDocumentSeeder::class,

                LoanCollateralDocSeeder::class,
                TransactionSeeder::class,

                RepaymentSeeder::class,
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: log error
            logger()->error($e);

            throw $e; // rethrow so you still see the error
        }
    }
}
