<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('loan_products')->pluck('id')->toArray();
        if (empty($products)) $products = [1, 2, 3, 4, 5];

        $customers = DB::table('customers')->pluck('id')->toArray();
        if (empty($customers)) $customers = [1, 2, 3, 4, 5];

        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[0] : null;

        $purposes = ['ទិញម៉ូតូថ្មី', 'ពង្រីកអាជីវកម្ម', 'សាងសង់ជួសជុលផ្ទះ', 'ទិញជីកសិកម្ម', 'បង់ថ្លៃសាលាកូន'];
        $statuses = ['approved', 'under_review', 'pending', 'rejected'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $status = $statuses[$i % count($statuses)];

            $data[] = [
                'application_code' => 'APP-' . date('Ym') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customers[array_rand($customers)],
                'product_id' => $products[array_rand($products)],
                'requested_amount' => rand(1000, 5000),
                'requested_months' => rand(6, 24),
                'purpose' => $purposes[$i % count($purposes)],
                'status' => $status,
                'reviewed_by' => in_array($status, ['approved', 'rejected']) ? $userId : null,
                'reviewed_at' => in_array($status, ['approved', 'rejected']) ? now() : null,
                'rejection_reason' => $status === 'rejected' ? 'មិនគ្រប់លក្ខខណ្ឌ' : null,
                'loan_id' => null,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('loan_applications')->insert($data);
    }
}
