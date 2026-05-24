<?php

namespace Database\Seeders;

use App\Models\StatusUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusNames = ['En línea', 'Ausente', 'Desconectado', 'Invisible', 'No molestar', 'En llamada'];

        foreach ($statusNames as $index => $statusName) {
            StatusUser::create([
                'id' => $index + 1,
                'name' => $statusName,
            ]);
        }
    }
}
