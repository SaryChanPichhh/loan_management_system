<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GuarantorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $customers = DB::table('customers')->pluck('id')->toArray();

        if (empty($customers)) {
            return;
        }

        $relations = ['parent', 'sibling', 'spouse', 'friend', 'business_partner', 'relative', 'guardian'];
        $statuses = ['active', 'released', 'defaulted'];
        $khmerFirstNames = ['សុខ', 'ចាន់', 'លី', 'នី', 'ហេង', 'ជា', 'ម៉ៅ', 'ប៉ុន', 'ឡេង', 'ឃឹម', 'ទូច', 'ណេង'];
        $khmerLastNames = ['សាន្ត', 'ធូ', 'ស្រីមុំ', 'រតនា', 'ដាវី', 'វិចិត្រ', 'វណ្ណា', 'សុភ័ក្រ'];

        $data = [];

        foreach ($customers as $customerId) {
            if (!$faker->boolean(75)) {
                continue;
            }

            $guarantorCount = $faker->numberBetween(1, 2);
            for ($i = 0; $i < $guarantorCount; $i++) {
                $createdAt = $faker->dateTimeBetween('-2 years', 'now');
                $fileRef = $faker->unique()->numerify('#####');

                $data[] = [
                    'customer_id' => $customerId,
                    'full_name' => $faker->boolean(50)
                        ? $faker->randomElement($khmerFirstNames) . ' ' . $faker->randomElement($khmerLastNames)
                        : $faker->name(),
                    'national_id' => 'G-' . $faker->unique()->numerify('########'),
                    'phone' => '0' . $faker->numerify('########'),
                    'address' => $faker->address(),
                    'income' => $faker->randomFloat(2, 120, 6500),
                    'relationship' => $faker->randomElement($relations),
                    'document_path' => 'guarantors/docs/' . $fileRef . '_id.pdf',
                    'guarantor_profile' => 'guarantors/profile/' . $fileRef . '.jpg',
                    'guarantor_document' => 'guarantors/document/' . $fileRef . '.pdf',
                    'status' => $faker->randomElement($statuses),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }
        }

        if (!empty($data)) {
            DB::table('guarantors')->insert($data);
        }
    }
}
