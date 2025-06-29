<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::updateOrCreate([
            'nombre' => 'General',
            'provincia' => 'Desconocida',
            'ciudad' => 'Desconocida',
            'direccion' => 'Desconocida',
            'email' => 'general@instituto.com',
            'telefono' => '0000000000',
            'estado' => 1,
        ]);
    }
}
