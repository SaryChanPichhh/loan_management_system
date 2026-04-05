<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // ASSETS (1xxx)
            ['code' => '1000', 'name' => 'សាច់ប្រាក់ក្នុងដៃ (Cash on Hand)', 'type' => 'Asset', 'description' => 'Physical cash kept in office'],
            ['code' => '1001', 'name' => 'ប្រាក់បញ្ញើធនាគារ (Bank Account)', 'type' => 'Asset', 'description' => 'Cash kept in corporate bank accounts'],
            ['code' => '1100', 'name' => 'ឥណទានត្រូវប្រមូល (Loan Principal Receivable)', 'type' => 'Asset', 'description' => 'Outstanding principal due from customers'],
            
            // LIABILITIES (2xxx)
            ['code' => '2100', 'name' => 'ប្រាក់កក់អតិថិជន (Customer Deposits)', 'type' => 'Liability', 'description' => 'Prepayments or security deposits from customers'],
            
            // REVENUE (4xxx)
            ['code' => '4000', 'name' => 'ចំណូលការប្រាក់ (Interest Income)', 'type' => 'Revenue', 'description' => 'Interest earned from loan repayments'],
            ['code' => '4100', 'name' => 'ចំណូលថ្លៃសេវា (Fee Income)', 'type' => 'Revenue', 'description' => 'Processing fees or late penalties'],
            
            // EXPENSES (5xxx)
            ['code' => '5000', 'name' => 'ការខាតបង់លើឥណទាន (Loan Loss Provisioning)', 'type' => 'Expense', 'description' => 'Expenses from defaulted loans'],
            ['code' => '5100', 'name' => 'ចំណាយរដ្ឋបាល (Administrative Expenses)', 'type' => 'Expense', 'description' => 'General business overhead'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::updateOrCreate(['code' => $account['code']], $account);
        }
    }
}
