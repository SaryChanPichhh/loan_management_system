<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::insert([
            [
                'code' => '01-CUS-00001',
                'name' => 'សុខ ដារ៉ា',
                'gender' => 'Male',
                'phone' => '012345678',
                'address' => 'ភ្នំពេញ',
                'type' => 'អតិថិជនទូទៅ',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00002',
                'name' => 'ចាន់ ស្រីពៅ',
                'gender' => 'Female',
                'phone' => '098765432',
                'address' => 'កណ្ដាល',
                'type' => 'VIP',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00003',
                'name' => 'លី មករា',
                'gender' => 'Male',
                'phone' => '011111111',
                'address' => 'សៀមរាប',
                'type' => 'អតិថិជនទូទៅ',
                'status' => 0,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00004',
                'name' => 'នី ស្រីល័ក្ខ',
                'gender' => 'Female',
                'phone' => '022222222',
                'address' => 'បាត់ដំបង',
                'type' => 'VIP',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00005',
                'name' => 'ហេង វុទ្ធី',
                'gender' => 'Male',
                'phone' => '033333333',
                'address' => 'តាកែវ',
                'type' => 'អតិថិជនទូទៅ',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00006',
                'name' => 'ស្រី នាង',
                'gender' => 'Female',
                'phone' => '044444444',
                'address' => 'កំពង់ចាម',
                'type' => 'VIP',
                'status' => 0,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00007',
                'name' => 'ជា វិសាល',
                'gender' => 'Male',
                'phone' => '055555555',
                'address' => 'ព្រៃវែង',
                'type' => 'អតិថិជនទូទៅ',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00008',
                'name' => 'ម៉ៅ ស្រីនិត',
                'gender' => 'Female',
                'phone' => '066666666',
                'address' => 'កំពង់ស្ពឺ',
                'type' => 'VIP',
                'status' => 1,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00009',
                'name' => 'ប៉ុន សុខា',
                'gender' => 'Male',
                'phone' => '077777777',
                'address' => 'កោះកុង',
                'type' => 'អតិថិជនទូទៅ',
                'status' => 0,
                'document' => null,
            ],
            [
                'code' => '01-CUS-00010',
                'name' => 'ឡេង ស្រីមុំ',
                'gender' => 'Female',
                'phone' => '088888888',
                'address' => 'កំពត',
                'type' => 'VIP',
                'status' => 1,
                'document' => null,
            ],
        ]);
    }
}
