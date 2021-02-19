<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostorutinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costorutinas', function (Blueprint $table) {
            $table->id();
            $table->string('modelo');
            $table->string('rutina');
            $table->double('costo')->default(0.00);
            $table->foreignId('id_user')->references('id')->on('users');
            $table->foreignId('id_distribuidora')->references('id')->on('distribuidoras');
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
        Schema::dropIfExists('costorutinas');
    }
}
