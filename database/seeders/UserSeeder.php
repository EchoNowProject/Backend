<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.env') === 'local') {
            User::create([
                'id' => 100,
                'username' => 'blazquezz.aj',
                'password' => Hash::make('HolaMundo1234%'),
                'email' => 'antblajim@gmail.com',
                //'display_name' => ,
                //'biography' => $this->faker->text(100),
                'status' => rand(1, 4),
                'telephone_number' => 694420542,
                'prefix_telephone_number' => 34,
                'plan' => rand(1, 2),
                'avatar_img' => 'IMG_0432.jpg'
            ]);
        }
    }
}
