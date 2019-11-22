<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("icon");
            $table->timestamps();
        });

        DB::table("menus")->insert(["id" => "1","name" => "Herramientas", "icon" => "far fa-cogs"]);
        DB::table("menus")->insert(["id" => "2","name" => "Configuraciones", "icon" => "far fa-cog"]);
        DB::table("menus")->insert(["id" => "3","name" => "Espacios", "icon" => "far fa-cog"]);
        //DB::table("menus")->insert(["id" => "4","name" => "Facturas", "icon" => "far fa-cog"]);
        DB::table("menus")->insert(["id" => "5","name" => "Honorarios", "icon" => "far fa-cog"]);
        DB::table("menus")->insert(["id" => "6","name" => "Dispensas", "icon" => "far fa-cog"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
