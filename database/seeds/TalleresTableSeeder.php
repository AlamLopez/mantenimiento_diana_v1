<?php

use Illuminate\Database\Seeder;

use App\Taller;

class TalleresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Taller::create([
            'nombre' => 'DIANA'
        ]);

        Taller::create([
            'nombre' => 'CANELLA'
        ]);

        Taller::create([
            'nombre' => 'CODACA'
        ]);

        Taller::create([
            'nombre' => 'HINORENT'
        ]);

        Taller::create([
            'nombre' => 'TALLER MITA'
        ]);

        Taller::create([
            'nombre' => 'VITATRAC'
        ]);

        Taller::create([
            'nombre' => 'FLEXIAUTO'
        ]);

        Taller::create([
            'nombre' => 'CASA PELLAS'
        ]);

        Taller::create([
            'nombre' => 'AUTOEXCEL'
        ]);

        Taller::create([
            'nombre' => 'INVERDECA'
        ]);

        Taller::create([
            'nombre' => 'N/A'
        ]);
    }
}
