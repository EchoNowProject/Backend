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

        // * For Development
        User::factory(50)->create();

        // if (config('app.env') === 'local') {
        $this->call([
            StatusUserSeeder::class,
            UserSeeder::class,
            UserSettingsSeeder::class,
            UserNotificationsSettingsSeeder::class,
            UserPrivacySettingSeedeer::class,
            //FriendSeeder::class,
        ]);
        // }

        // * For Production
        $this->call([TypeMessageSeeder::class]);

        /* Examples */
        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
