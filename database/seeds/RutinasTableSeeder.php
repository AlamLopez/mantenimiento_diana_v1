<?php

use Illuminate\Database\Seeder;

use App\Rutina;

class RutinasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rutina::create([
            'nombre' => 'A1'
        ]);

        Rutina::create([
            'nombre' => 'A2'
        ]);

        Rutina::create([
            'nombre' => 'A3'
        ]);

        Rutina::create([
            'nombre' => 'B'
        ]);

        Rutina::create([
            'nombre' => 'A4'
        ]);

        Rutina::create([
            'nombre' => 'A5'
        ]);

        Rutina::create([
            'nombre' => 'A6'
        ]);

        Rutina::create([
            'nombre' => 'C'
        ]);
    }
}
