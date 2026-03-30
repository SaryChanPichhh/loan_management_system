<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            // Loan Management
            ['name' => 'View Loans', 'slug' => 'loan_view', 'module' => 'Loan Management'],
            ['name' => 'Create Loan', 'slug' => 'loan_create', 'module' => 'Loan Management'],
            ['name' => 'Approve Loan', 'slug' => 'loan_approve', 'module' => 'Loan Management'],
            
            // User Management
            ['name' => 'View Users', 'slug' => 'user_view', 'module' => 'User Management'],
            ['name' => 'Create User', 'slug' => 'user_create', 'module' => 'User Management'],
            ['name' => 'Delete User', 'slug' => 'user_delete', 'module' => 'User Management'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Create Roles
        $adminRole = Role::updateOrCreate(['slug' => 'admin'], [
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full access to the system'
        ]);

        $staffRole = Role::updateOrCreate(['slug' => 'staff'], [
            'name' => 'Staff',
            'slug' => 'staff',
            'description' => 'Limited access to loan management'
        ]);

        // 3. Assign Permissions to Roles
        $adminRole->permissions()->sync(Permission::all());
        $staffRole->permissions()->sync(Permission::where('slug', 'like', 'loan_%')->get());

        // 4. Create Users with specific Usernames
        $adminUser = User::updateOrCreate(['email' => 'admin@loan.com'], [
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@loan.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->roles()->sync([$adminRole->id]);

        $staffUser = User::updateOrCreate(['email' => 'staff@loan.com'], [
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@loan.com',
            'password' => Hash::make('password'),
        ]);
        $staffUser->roles()->sync([$staffRole->id]);
    }
}
