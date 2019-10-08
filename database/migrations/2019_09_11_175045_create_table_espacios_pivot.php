<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEspaciosPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('espacios_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id");
            $table->bigInteger("espacio_id");
            $table->bigInteger("horario");
            $table->bigInteger("detalle");
            $table->bigInteger("respuesta");
            $table->bigInteger("fecha");
            $table->bigInteger("autorizado")->default(0);
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
        Schema::dropIfExists('espacios_pivot');
    }
}
