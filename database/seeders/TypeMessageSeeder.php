<?php

namespace Database\Seeders;

use App\Models\TypesMsg;
use Illuminate\Database\Seeder;

class TypeMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['message', 'file', 'mixed'];

        foreach ($names as $index => $name) {

            TypesMsg::create([
                'id' => $index + 1,
                'name' => $name,
            ]);
        }
    }
}
