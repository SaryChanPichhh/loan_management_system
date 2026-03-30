<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanCollateralSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];

        $types = ['land_title', 'vehicle', 'equipment', 'land_title', 'vehicle'];
        $descriptions = ['ប្លង់រឹងដីនិងផ្ទះ១ល្វែង', 'កាតគ្រីម៉ូតូ Honda Dream ២០២៣', 'គ្រឿងចក្រកសិកម្មត្រាក់ទ័រ', 'ប្លង់ដីចម្ការនៅខេត្តកំពង់ចាម', 'កាតគ្រីឡាន Prius ២០០៨'];
        $statuses = ['active', 'active', 'active', 'released', 'seized'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'loan_id' => $loans[array_rand($loans)],
                'collateral_type' => $types[$i],
                'description' => $descriptions[$i],
                'estimated_value' => rand(5000, 50000),
                'valuation_date' => now()->subDays(rand(1, 30)),
                'status' => $statuses[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('loan_collaterals')->insert($data);
    }
}
