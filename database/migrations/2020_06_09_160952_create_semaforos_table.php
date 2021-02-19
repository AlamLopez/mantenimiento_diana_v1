<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemaforosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semaforos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vehiculo')->references('id')->on('vehiculos');
            $table->foreignId('id_mantenimiento')->nullable()->references('id')->on('mantenimientos');
            $table->integer('diferencia_kms')->nullable();
            $table->string('diferencia_kms_color')->nullable();
            $table->date('fecha_prox_manto_kms')->nullable();
            $table->integer('diferencia_dias')->nullable();
            $table->string('diferencia_dias_color')->nullable();
            $table->date('fecha_prox_manto_tiempo')->nullable();
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
        Schema::dropIfExists('semaforos');
    }
}
