<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $status = ['online', 'absent', 'busy', 'invisible'];

        foreach ($status as $item) {
            Status::create([
                'name' => $item
            ]);
        }
    }
}
