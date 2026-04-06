<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffRole = Role::where('slug', 'staff')->first();
        $adminRole = Role::where('slug', 'admin')->first();

        $users = [
            [
                'name' => 'setec',
                'username' => 'setec',
                'email' => 'setec@loan.com',
                'password' => Hash::make('123456'),
                'role' => $adminRole
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(['email' => $userData['email']], $userData);
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
