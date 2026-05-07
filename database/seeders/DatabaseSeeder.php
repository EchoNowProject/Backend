<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // factories
        User::factory(50)->create();

        // Seeders
        $this->call([

            // Users
            StatusUserSeeder::class,
            UserSeeder::class,
            UserSettingsSeeder::class,
            UserNotificationsSettingsSeeder::class,
            UserPrivacySettingSeedeer::class,

            // Types of messages
            TypeMessageSeeder::class,
        ]);

        /* Examples */
        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
