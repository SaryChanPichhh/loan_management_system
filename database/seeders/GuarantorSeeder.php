<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuarantorSeeder extends Seeder
{
    public function run(): void
    {
        $customers = DB::table('customers')->pluck('id')->toArray();
        if (empty($customers)) {
            $customers = [1, 2, 3, 4, 5];
        }

        $names = ['សុខ សាន្ត', 'ចាន់ ធូ', 'មាស ស្រីមុំ', 'កែវ រតនា', 'ជា ដាវី'];
        $relations = ['បងប្អូន', 'សាច់ញាតិ', 'មិត្តភក្តិ', 'ប្តី/ប្រពន្ធ', 'ឪពុកម្តាយ'];
        $addresses = ['ភ្នំពេញ', 'កណ្តាល', 'សៀមរាប', 'បាត់ដំបង', 'កំពង់ចាម'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'customer_id' => $customers[array_rand($customers)],
                'full_name' => $names[$i],
                'national_id' => '01' . rand(10000000, 99999999),
                'phone' => '0' . rand(10000000, 99999999),
                'address' => 'ផ្ទះលេខ ' . rand(1, 100) . ', ខេត្ត/ក្រុង ' . $addresses[$i],
                'relationship' => $relations[$i],
                'document_path' => 'guarantors/docs/id_' . rand(100, 999) . '.pdf',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('guarantors')->insert($data);
    }
}
