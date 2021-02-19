<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'nombre' => 'admin',
            'nombre_completo' => 'ADMINISTRADOR',
            'email' => 'administrador@grupodisatel.com',
            'password' => bcrypt('admin123*'),
            'condicion' => true,
            'id_rol' => 1,
            'distribuidoras' => '1,2,3,4,5,6,7'
        ]);

        User::create([
            'nombre' => 'carlos_blandon',
            'nombre_completo' => 'CARLOS BLANDON',
            'email' => '',
            'password' => bcrypt('blandon123*'),
            'condicion' => true,
            'id_rol' => 1,
            'distribuidoras' => '1,2'
        ]);

        User::create([
            'nombre' => 'miguel_lux',
            'nombre_completo' => 'MIGUEL LUX',
            'email' => 'miguel.lux@diana.com.sv',
            'password' => bcrypt('lux123*'),
            'condicion' => true,
            'id_rol' => 1,
            'distribuidoras' => '3'
        ]);

        User::create([
            'nombre' => 'manuel_barcala',
            'nombre_completo' => 'MANUEL BARCALA',
            'email' => 'manuel.martinez@diana.com.sv',
            'password' => bcrypt('barcala123'),
            'condicion' => true,
            'id_rol' => 2,
            'distribuidoras' => '4,5'
        ]);

        User::create([
            'nombre' => 'cinthya_gutierrez',
            'nombre_completo' => 'CINTHYA GUTIERREZ',
            'email' => 'cinthya.gutierrez@diana.com.sv',
            'password' => bcrypt('cinthya123*'),
            'condicion' => true,
            'id_rol' => 2,
            'distribuidoras' => '6,7'
        ]);

        User::create([
            'nombre' => 'gerardo_perez',
            'nombre_completo' => 'GERARDO PEREZ',
            'email' => null,
            'password' => bcrypt('gerardo123*'),
            'condicion' => true,
            'id_rol' => 2,
            'distribuidoras' => '3'
        ]);
    }
}
