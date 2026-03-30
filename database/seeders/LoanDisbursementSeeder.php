<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanDisbursementSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];

        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[0] : null;

        $methods = ['BANK_TRANSFER', 'CASH', 'MOBILE_MONEY', 'CHEQUE', 'BANK_TRANSFER'];
        $banks = ['ABA', null, 'Wing', 'ACLEDA', 'Sathapana'];
        $notes = ['បើកប្រាក់ចូលកុងធនាគារ ABA', 'បើកប្រាក់សុទ្ធជូនអតិថិជន', 'វេរតាមវីងម៉ាន់នី', 'ចេញសែក', 'វេរចូលកុងត្រាជារៀល'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $method = $methods[$i];
            $data[] = [
                'loan_id' => $loans[array_rand($loans)],
                'amount' => rand(1000, 5000),
                'method' => $method,
                'reference_number' => $method !== 'CASH' ? 'REF' . rand(100000, 999999) : null,
                'bank_name' => $banks[$i],
                'account_number' => $method !== 'CASH' ? 'ACC' . rand(10000000, 99999999) : null,
                'notes' => $notes[$i],
                'disbursed_at' => now(),
                'disbursed_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('loan_disbursements')->insert($data);
    }
}
