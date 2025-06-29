<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use App\Models\Sede;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $rolAdmin = Rol::where('nombrerol', 'ADMIN')->first();
        $sedeGeneral = Sede::where('nombre', 'General')->first();

        Usuario::updateOrCreate(
            ['usuario' => 'admin'],
            [
                'nombre' => 'Administrador',
                'correo' => 'admin@instituto.com',
                'clave' => Hash::make('admin'),
                'estado' => 1,
                'idsede' => $sedeGeneral->id ?? 1,
                'idrol' => $rolAdmin->id ?? 1,
            ]
        );
    }
}
