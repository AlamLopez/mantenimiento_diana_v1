<?php

use Illuminate\Database\Seeder;

use App\Rol;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'nombre' => 'ADMINISTRADOR',
            'descripcion' => 'Administrador del sistema'
        ]);

        Rol::create([
            'nombre' => 'MANTENIMIENTO',
            'descripcion' => 'Mantenimiento preventivo y rendimiento por region'
        ]);
    }
}
