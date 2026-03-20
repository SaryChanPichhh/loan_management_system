<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;
use Carbon\Carbon;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = ['KHR', 'EUR', 'GBP', 'JPY', 'THB', 'VND', 'AUD', 'CAD', 'SGD', 'CNY'];
        
        for ($i = 0; $i < 20; $i++) {
            $isUsdBase = rand(0, 1) == 1;
            $target = $currencies[array_rand($currencies)];
            
            $baseCurrency = $isUsdBase ? 'USD' : $target;
            $targetCurrency = $isUsdBase ? $target : 'USD';
            
            $rate = match($target) {
                'KHR' => $isUsdBase ? fake()->randomFloat(2, 4000, 4150) : fake()->randomFloat(6, 0.00024, 0.00025),
                'EUR' => $isUsdBase ? fake()->randomFloat(4, 0.85, 0.95) : fake()->randomFloat(4, 1.05, 1.15),
                'GBP' => $isUsdBase ? fake()->randomFloat(4, 0.75, 0.85) : fake()->randomFloat(4, 1.15, 1.30),
                'JPY' => $isUsdBase ? fake()->randomFloat(2, 140, 160) : fake()->randomFloat(6, 0.0060, 0.0075),
                'THB' => $isUsdBase ? fake()->randomFloat(2, 33, 37) : fake()->randomFloat(6, 0.027, 0.030),
                'VND' => $isUsdBase ? fake()->randomFloat(2, 24000, 25500) : fake()->randomFloat(6, 0.000039, 0.000042),
                'AUD' => $isUsdBase ? fake()->randomFloat(4, 1.40, 1.60) : fake()->randomFloat(4, 0.60, 0.70),
                'CAD' => $isUsdBase ? fake()->randomFloat(4, 1.30, 1.40) : fake()->randomFloat(4, 0.70, 0.80),
                'SGD' => $isUsdBase ? fake()->randomFloat(4, 1.30, 1.40) : fake()->randomFloat(4, 0.70, 0.80),
                'CNY' => $isUsdBase ? fake()->randomFloat(4, 7.00, 7.30) : fake()->randomFloat(4, 0.13, 0.15),
                default => 1,
            };

            ExchangeRate::create([
                'base_currency' => $baseCurrency,
                'target_currency' => $targetCurrency,
                'rate' => $rate,
                'exchange_date' => Carbon::now()->subDays(rand(0, 30)),
                'source' => fake()->randomElement(['NBC', 'Market', 'Bank', 'ECB']),
                'created_by' => fake()->randomElement(['Admin', 'Pich', 'System']),
                'status' => fake()->boolean(85), // 85% chance to be active
                'document' => null
            ]);
        }
    }
}
