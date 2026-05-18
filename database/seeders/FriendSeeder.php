<?php

namespace Database\Seeders;

use App\Models\Friend;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i <= 15; $i++) {
            Friend::create([
                'first_user_id' => 100,
                'first_user_username' => fake()->userName,
                'second_user_id' => $i + 1,
                'second_user_username' => fake()->userName,
            ]);
        }
    }
}
