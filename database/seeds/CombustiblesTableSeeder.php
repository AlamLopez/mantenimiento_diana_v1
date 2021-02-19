<?php

use Illuminate\Database\Seeder;

use App\Combustible;

class CombustiblesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Combustible::create([
            'nombre' => 'DIESEL'
        ]);

        Combustible::create([
            'nombre' => 'DIESEL CON DYNAMAX'
        ]);

        Combustible::create([
            'nombre' => 'REGULAR'
        ]);

        Combustible::create([
            'nombre' => 'REGULAR CON DYNAMAX'
        ]);

        Combustible::create([
            'nombre' => 'SUPER'
        ]);

        Combustible::create([
            'nombre' => 'SUPER CON DYNAMAX'
        ]);
    }
}
