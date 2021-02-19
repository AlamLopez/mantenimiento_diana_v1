<?php

use Illuminate\Database\Seeder;

use App\Distribuidora;

class DistribuidorasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Distribuidora::create([
            'nombre' => 'XELAJU',
            'pais' => 'GUATEMALA'
        ]);

        Distribuidora::create([
            'nombre' => 'RETALHULEU',
            'pais' => 'GUATEMALA'
        ]);

        Distribuidora::create([
            'nombre' => 'MITA',
            'pais' => 'GUATEMALA'
        ]);

        Distribuidora::create([
            'nombre' => 'DISMANA',
            'pais' => 'NICARAGUA'
        ]);

        Distribuidora::create([
            'nombre' => 'ESTELI',
            'pais' => 'NICARAGUA'
        ]);

        Distribuidora::create([
            'nombre' => 'DISTEGU',
            'pais' => 'HONDURAS'
        ]);

        Distribuidora::create([
            'nombre' => 'DISANPE',
            'pais' => 'HONDURAS'
        ]);
    }
}
