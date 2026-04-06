<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $users = DB::table('users')->pluck('id')->toArray();
        $userId = !empty($users) ? $users[array_rand($users)] : null;

        $khmerFirstNames = ['សុខ', 'ចាន់', 'លី', 'នី', 'ហេង', 'ស្រី', 'ជា', 'ម៉ៅ', 'ប៉ុន', 'ឡេង', 'ជា', 'ឃឹម', 'ទូច', 'ណេង'];
        $khmerLastNames = ['ដារ៉ា', 'ស្រីពៅ', 'មករា', 'ស្រីល័ក្ខ', 'វុទ្ធី', 'នាង', 'វិសាល', 'ស្រីនិត', 'សុខា', 'ស្រីមុំ', 'រតនា', 'វណ្ណា', 'សំណាង'];
        $occupations = ['Farmer', 'Teacher', 'Merchant', 'Engineer', 'Doctor', 'Driver', 'Tailor', 'Accountant', 'Retailer', 'Factory Worker'];
        $provinces = ['Phnom Penh', 'Kandal', 'Siem Reap', 'Battambang', 'Takeo', 'Kampot', 'Kampong Cham', 'Prey Veng', 'Banteay Meanchey', 'Kampong Speu'];
        $types = ['individual', 'business'];

        $rows = [];
        $total = 200;

        for ($i = 1; $i <= $total; $i++) {
            $gender = $faker->randomElement(['Male', 'Female', 'Other']);
            $monthlyIncome = $faker->randomFloat(2, 150, 8500);
            $createdAt = $faker->dateTimeBetween('-2 years', 'now');

            $rows[] = [
                'code'              => '01-CUS-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'name'              => $faker->boolean(55)
                    ? $faker->randomElement($khmerFirstNames) . ' ' . $faker->randomElement($khmerLastNames)
                    : $faker->name($gender === 'Other' ? null : strtolower($gender)),
                'gender'            => $gender,
                'phone'             => '0' . $faker->numerify('########'),
                'email'             => $faker->boolean(75) ? $faker->unique()->safeEmail() : null,
                'address'           => $faker->streetAddress() . ', ' . $faker->randomElement($provinces),
                'national_id'       => 'KH-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT) . '-' . $faker->numerify('######'),
                'date_of_birth'     => $faker->dateTimeBetween('-65 years', '-20 years')->format('Y-m-d'),
                'age_verified'      => 1,
                'occupation'        => $faker->randomElement($occupations),
                'monthly_income'    => $monthlyIncome,
                'has_existing_loan' => $faker->boolean(40),
                'credit_score'      => $faker->numberBetween(350, 900),
                'type'              => $faker->randomElement($types),
                'status'            => $faker->boolean(92),
                'document_path'     => $faker->boolean(35) ? '/documents/customers/cust_' . str_pad((string) $i, 5, '0', STR_PAD_LEFT) . '.pdf' : null,
                'created_by'        => $userId,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ];
        }

        DB::table('customers')->insert($rows);
    }
}
