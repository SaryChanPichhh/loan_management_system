<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $actions = ['Login', 'Logout', 'Created Record', 'Updated Record', 'Deleted Record'];
        $users = ['Admin', 'Sary', 'Pich', 'System'];
        $modules = ['Exchange Rate', 'Customer', 'Settings', 'Profile'];
        
        for ($i = 0; $i < 20; $i++) {
            $action = $actions[array_rand($actions)];
            $user = $users[array_rand($users)];
            $module = $modules[array_rand($modules)];
            
            ActivityLog::create([
                'user_name' => $user,
                'action' => $action,
                'description' => "User {$user} performed {$action} on {$module} module.",
                'ip_address' => rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255),
                'created_at' => now()->subHours(rand(1, 100)),
                'updated_at' => now()->subHours(rand(1, 100))
            ]);
        }
    }
}
