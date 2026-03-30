<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $loans = DB::table('loans')->pluck('id')->toArray();
        if (empty($loans)) $loans = [1, 2, 3, 4, 5];

        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[0] : null;

        $types = ['national_id', 'payslip', 'business_reg', 'agreement', 'national_id'];
        $files = ['អត្តសញ្ញាណប័ណ្ណ.jpg', 'លិខិតបញ្ជាក់ប្រាក់ខែ.pdf', 'ប៉ាតង់អាជីវកម្ម.pdf', 'កិច្ចសន្យាខ្ចីប្រាក់.pdf', 'សៀវភៅគ្រួសារ.pdf'];

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'loan_id' => $loans[array_rand($loans)],
                'document_type' => $types[$i],
                'file_name' => $files[$i],
                'storage_path' => 'loans/docs/' . rand(1000, 9999) . '_' . $files[$i],
                'mime_type' => str_ends_with($files[$i], 'pdf') ? 'application/pdf' : 'image/jpeg',
                'file_size_bytes' => rand(102400, 2048000),
                'uploaded_by' => $userId,
                'uploaded_at' => now(),
            ];
        }

        DB::table('loan_documents')->insert($data);
    }
}
