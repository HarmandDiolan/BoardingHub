<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Plan::create([
            'name' => 'Basic Plan',
            'price' => 1000,
            'features' => json_encode(['feature1' => 'value1', 'feature2' => 'value2']),
        ]);
    
        \App\Models\Plan::create([
            'name' => 'Premium Plan',
            'price' => 2500,
            'features' => json_encode(['feature1' => 'value1', 'feature2' => 'value2']),
        ]);
    }
    
}
