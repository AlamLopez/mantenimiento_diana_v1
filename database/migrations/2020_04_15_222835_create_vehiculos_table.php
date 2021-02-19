<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            
            $table->id();

            $table->string('codigo_comb')->unique();
            $table->string('numero_vehiculo')->nullable();
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('anio');
            $table->foreignId('id_distribuidora')->references('id')->on('distribuidoras');
            $table->string('conductor');
            $table->date('ulto_km')->nullable();
            $table->double('km')->nullable();
            $table->string('odometroSw')->nullable();
            $table->boolean('condicion')->default(true);
            $table->foreignId('id_taller')->references('id')->on('talleres');
            $table->double('periodo_mtto_kms');
            $table->double('periodo_mtto_meses');
            $table->string('ruta');
            $table->string('propietario');
            $table->double('rendimiento_meta_2018')->default(0);
            $table->boolean('edit_usuario_meta_2018')->default(false);
            $table->double('rendimiento_meta_2019')->default(0);
            $table->boolean('edit_usuario_meta_2019')->default(false);
            $table->double('rendimiento_meta_2020')->default(0);
            $table->boolean('edit_usuario_meta_2020')->default(false);
            $table->double('rendimiento_meta_2021')->default(0);
            $table->boolean('edit_usuario_meta_2021')->default(false);
            $table->double('rendimiento_meta_2022')->default(0);
            $table->boolean('edit_usuario_meta_2022')->default(false);
            $table->double('rendimiento_meta_2023')->default(0);
            $table->boolean('edit_usuario_meta_2023')->default(false);
            $table->double('rendimiento_meta_2024')->default(0);
            $table->boolean('edit_usuario_meta_2024')->default(false);
            $table->double('rendimiento_meta_2025')->default(0);
            $table->boolean('edit_usuario_meta_2025')->default(false);
            $table->double('rendimiento_meta_2026')->default(0);
            $table->boolean('edit_usuario_meta_2026')->default(false);
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
}
