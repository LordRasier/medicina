<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLicences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("description");
            $table->integer("alter")->default(1);
            $table->integer("habilitado")->default(1);
            $table->timestamps();
        });

        DB::table("licences")->insert(["description" =>"Dispensa Comun"]);
        DB::table("licences")->insert(["description" =>"Dispensa Extraordinaria"]);
        DB::table("licences")->insert(["description" =>"Dispensa por enfermedad"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licences');
    }
}
