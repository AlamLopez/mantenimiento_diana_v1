<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRendimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendimientos', function (Blueprint $table) {
            
            $table->id();

            $table->date('fecha');
            $table->foreignId('id_vehiculo')->references('id')->on('vehiculos');
            $table->string('id_data');
            $table->foreignId('id_combustible')->references('id')->on('combustibles');
            $table->integer('kilometraje');
            $table->double('cantidad_galones');
            $table->double('valor');
            $table->double('recorrido');
            $table->double('rendimiento');
            $table->string('status');

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
        Schema::dropIfExists('rendimientos');
    }
}
