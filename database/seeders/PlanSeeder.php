<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = ['basic', 'purpleTeam'];

        foreach ($plans as $plan) {
            Plan::create([
                'name' => $plan
            ]);
        }
    }
}
