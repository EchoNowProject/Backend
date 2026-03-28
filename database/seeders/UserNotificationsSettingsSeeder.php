<?php

namespace Database\Seeders;

use App\Models\UserNotificationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserNotificationsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserNotificationSetting::create([
            'user_id' => 100,
        ]);
    }
}
