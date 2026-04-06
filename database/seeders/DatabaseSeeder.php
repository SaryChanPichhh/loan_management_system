<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * @throws \Throwable
     */
    public function run(): void
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach ([
                'loan_collateral_docs',
                'loan_documents',
                'transactions',
                'repayments',
                'loan_fees',
                'loan_collaterals',
                'loan_disbursements',
                'loan_schedules',
                'loan_accounts',
                'guarantors',
                'loans',
                'loan_applications',
                'customers',
                'notifications',
                'activity_logs',
                'exchange_rates',
            ] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Prevent duplicate user creation
            if (User::where('email', 'test@example.com')->doesntExist()) {
                User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ]);
            }

            $this->call([
                // Role & Permission (PK/FK) should seed early so auth IDs exist for other seeders that may rely on them.
                RolePermissionSeeder::class,
                UserSeeder::class,

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

        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Optional: log error
            logger()->error($e);

            throw $e; // rethrow so you still see the error
        }
    }
}
