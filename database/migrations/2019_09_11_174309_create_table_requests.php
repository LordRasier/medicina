<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("user_id");
            $table->bigInteger("licence_id");
            $table->bigInteger("periodo_id");
            $table->text("description");
            $table->string("file");
            $table->integer("autorizacion2")->default(1);
            $table->text("observacion2")->nullable();
            $table->integer("autorizacion3")->default(1);
            $table->text("observacion3")->nullable();
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
        Schema::dropIfExists('requests');
    }
}
