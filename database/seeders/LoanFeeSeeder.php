<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanFeeSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];

        $types = ['application', 'processing', 'insurance', 'late_fee', 'valuation'];
        $notes = ['ថ្លៃរដ្ឋបាលពាក្យស្នើសុំ', 'ថ្លៃសេវារៀបចំឯកសារ', 'ថ្លៃធានារ៉ាប់រង', 'ថ្លៃពិន័យយឺតយ៉ាវ', 'ថ្លៃវាយតម្លៃទ្រព្យ'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'loan_id' => $loans[array_rand($loans)],
                'fee_type' => $types[$i],
                'amount' => rand(10, 50),
                'is_waived' => false,
                'waived_by' => null,
                'waived_at' => null,
                'notes' => $notes[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('loan_fees')->insert($data);
    }
}
