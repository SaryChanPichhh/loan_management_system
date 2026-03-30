<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanCollateralDocSeeder extends Seeder
{
    public function run(): void
    {
        $collaterals = DB::table('loan_collaterals')->pluck('id')->toArray();
        if (empty($collaterals)) $collaterals = [1, 2, 3, 4, 5];

        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[0] : null;

        $types = ['land_title', 'vehicle_reg', 'photo', 'land_title', 'photo'];
        $files = ['ប្លង់រឹង.pdf', 'កាតគ្រីមុខក្រោយ.jpg', 'រូបភាពផ្ទះ.png', 'វិញ្ញាបនបត្រដីធ្លី.pdf', 'រូបម៉ូតូ.jpg'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'collateral_id' => $collaterals[array_rand($collaterals)],
                'document_type' => $types[$i],
                'file_name' => $files[$i],
                'storage_path' => 'collaterals/docs/' . rand(1000, 9999) . '_' . $files[$i],
                'mime_type' => str_ends_with($files[$i], 'pdf') ? 'application/pdf' : 'image/jpeg',
                'file_size_bytes' => rand(102400, 2048000), // 100KB to 2MB
                'uploaded_by' => $userId,
                'uploaded_at' => now(),
            ];
        }

        DB::table('loan_collateral_docs')->insert($data);
    }
}
