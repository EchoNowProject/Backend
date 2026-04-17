<?php

namespace Database\Seeders;

use App\Models\UserPrivacySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPrivacySettingSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPrivacySetting::create([
            'user_id' => 100,
        ]);
    }
}
