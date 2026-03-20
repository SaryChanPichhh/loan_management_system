<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['info', 'success', 'warning', 'error'];
        $titles = ['New Login Detected', 'Exchange Rate Updated', 'Loan Application Approved', 'Customer Passed KYC', 'System Maintenance Scheduled'];
        $users = ['Admin', 'Sary', 'Pich'];
        
        for ($i = 0; $i < 20; $i++) {
            $type = $types[array_rand($types)];
            $title = $titles[array_rand($titles)];
            
            Notification::create([
                'title' => $title,
                'message' => "This is a dummy notification detailing the {$title} event happening recently.",
                'type' => $type,
                'is_read' => fake()->boolean(40), // 40% chance read
                'target_user' => fake()->boolean(70) ? $users[array_rand($users)] : null, // 70% targeted, 30% global
                'created_at' => now()->subHours(rand(1, 150)),
                'updated_at' => now()->subHours(rand(1, 150))
            ]);
        }
    }
}
