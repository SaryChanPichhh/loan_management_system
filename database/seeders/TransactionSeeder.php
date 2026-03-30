<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = DB::table('loan_accounts')->pluck('id')->toArray();
        if (empty($accounts)) $accounts = [1, 2, 3, 4, 5];

        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[0] : null;

        $types = ['DISBURSEMENT', 'REPAYMENT_PRINCIPAL', 'REPAYMENT_INTEREST', 'REPAYMENT_PENALTY', 'REPAYMENT_LATE_FEE'];
        $notes = ['បើកប្រាក់ស្ថាពរ', 'បង់ប្រាក់ដើមប្រចាំខែ', 'បង់ការប្រាក់ប្រចាំខែ', 'ការផាកពិន័យ', 'ថ្លៃយឺតយ៉ាវ'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'account_id' => $accounts[array_rand($accounts)],
                'type' => $types[$i],
                'amount' => rand(10, 500),
                'running_balance' => rand(500, 4500),
                'reference' => 'TXN' . rand(1000000, 9999999),
                'notes' => $notes[$i],
                'created_by' => $userId,
                'created_at' => now()->subDays(rand(1, 30)),
            ];
        }

        DB::table('transactions')->insert($data);
    }
}
