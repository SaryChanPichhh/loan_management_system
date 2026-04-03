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
            ['name' => 'View Loans', 'slug' => 'loan_view', 'module' => 'Loan Management'],
            ['name' => 'Create Loan', 'slug' => 'loan_create', 'module' => 'Loan Management'],
            ['name' => 'Approve Loan', 'slug' => 'loan_approve', 'module' => 'Loan Management'],

            ['name' => 'View Users', 'slug' => 'user_view', 'module' => 'User Management'],
            ['name' => 'Create User', 'slug' => 'user_create', 'module' => 'User Management'],
            ['name' => 'Delete User', 'slug' => 'user_delete', 'module' => 'User Management'],

            ['name' => 'Login Index', 'slug' => 'login.index', 'module' => 'Login', 'description' => 'Allow access to login.index'],
            ['name' => 'Login Forgot Password', 'slug' => 'login.forgot_password', 'module' => 'Login', 'description' => 'Allow access to login.forgot_password'],
            ['name' => 'Dashboard Index', 'slug' => 'dashboard.index', 'module' => 'Dashboard', 'description' => 'Allow access to dashboard.index'],

            ['name' => 'Customer Index', 'slug' => 'customer.index', 'module' => 'Customer', 'description' => 'Allow access to customer.index'],
            ['name' => 'Customer Create', 'slug' => 'customer.create', 'module' => 'Customer', 'description' => 'Allow access to customer.create'],
            ['name' => 'Customer Store', 'slug' => 'customer.store', 'module' => 'Customer', 'description' => 'Allow access to customer.store'],
            ['name' => 'Customer Show', 'slug' => 'customer.show', 'module' => 'Customer', 'description' => 'Allow access to customer.show'],
            ['name' => 'Customer Edit', 'slug' => 'customer.edit', 'module' => 'Customer', 'description' => 'Allow access to customer.edit'],
            ['name' => 'Customer Update', 'slug' => 'customer.update', 'module' => 'Customer', 'description' => 'Allow access to customer.update'],
            ['name' => 'Customer Destroy', 'slug' => 'customer.destroy', 'module' => 'Customer', 'description' => 'Allow access to customer.destroy'],

            ['name' => 'Loans Index', 'slug' => 'loans.index', 'module' => 'Loans', 'description' => 'Allow access to loans.index'],
            ['name' => 'Loans Create', 'slug' => 'loans.create', 'module' => 'Loans', 'description' => 'Allow access to loans.create'],
            ['name' => 'Loans Defaulted', 'slug' => 'loans.defaulted', 'module' => 'Loans', 'description' => 'Allow access to loans.defaulted'],
            ['name' => 'Loans Show', 'slug' => 'loans.show', 'module' => 'Loans', 'description' => 'Allow access to loans.show'],
            ['name' => 'Loans Edit', 'slug' => 'loans.edit', 'module' => 'Loans', 'description' => 'Allow access to loans.edit'],
            ['name' => 'Loans Review', 'slug' => 'loans.review', 'module' => 'Loans', 'description' => 'Allow access to loans.review'],
            ['name' => 'Loans Payments', 'slug' => 'loans.payments', 'module' => 'Loans', 'description' => 'Allow access to loans.payments'],

            ['name' => 'Repayments Index', 'slug' => 'repayments.index', 'module' => 'Repayments', 'description' => 'Allow access to repayments.index'],
            ['name' => 'Repayments Store', 'slug' => 'repayments.store', 'module' => 'Repayments', 'description' => 'Allow access to repayments.store'],
            ['name' => 'Repayments Overdue', 'slug' => 'repayments.overdue', 'module' => 'Repayments', 'description' => 'Allow access to repayments.overdue'],
            ['name' => 'Repayments Show', 'slug' => 'repayments.show', 'module' => 'Repayments', 'description' => 'Allow access to repayments.show'],
            ['name' => 'Repayments Create', 'slug' => 'repayments.create', 'module' => 'Repayments', 'description' => 'Allow access to repayments.create'],
            ['name' => 'Repayments Edit', 'slug' => 'repayments.edit', 'module' => 'Repayments', 'description' => 'Allow access to repayments.edit'],

            ['name' => 'Report Index', 'slug' => 'report.index', 'module' => 'Report', 'description' => 'Allow access to report.index'],
            ['name' => 'Notification Index', 'slug' => 'notification.index', 'module' => 'Notification', 'description' => 'Allow access to notification.index'],

            ['name' => 'Role Index', 'slug' => 'role.index', 'module' => 'Role', 'description' => 'Allow access to role.index'],
            ['name' => 'Role Permissions Update', 'slug' => 'role.permissions.update', 'module' => 'Role', 'description' => 'Allow access to role.permissions.update'],

            ['name' => 'User Permissions Edit', 'slug' => 'user.permissions.edit', 'module' => 'User', 'description' => 'Allow access to user.permissions.edit'],
            ['name' => 'User Permissions Update', 'slug' => 'user.permissions.update', 'module' => 'User', 'description' => 'Allow access to user.permissions.update'],

            ['name' => 'Activity Log Index', 'slug' => 'activity_log.index', 'module' => 'Activity Log', 'description' => 'Allow access to activity_log.index'],

            ['name' => 'Settings Company Profile', 'slug' => 'settings.company_profile', 'module' => 'Settings', 'description' => 'Allow access to settings.company_profile'],
            ['name' => 'Settings Exchange Rate', 'slug' => 'settings.exchange_rate', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate'],
            ['name' => 'Settings Exchange Rate Insert', 'slug' => 'settings.exchange_rate.insert', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate.insert'],
            ['name' => 'Settings Exchange Rate Store', 'slug' => 'settings.exchange_rate.store', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate.store'],
            ['name' => 'Settings Exchange Rate Edit', 'slug' => 'settings.exchange_rate.edit', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate.edit'],
            ['name' => 'Settings Exchange Rate Update', 'slug' => 'settings.exchange_rate.update', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate.update'],
            ['name' => 'Settings Exchange Rate Delete', 'slug' => 'settings.exchange_rate.delete', 'module' => 'Settings', 'description' => 'Allow access to settings.exchange_rate.delete'],

            ['name' => 'Storage Local', 'slug' => 'storage.local', 'module' => 'Storage', 'description' => 'Allow access to storage.local'],
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
