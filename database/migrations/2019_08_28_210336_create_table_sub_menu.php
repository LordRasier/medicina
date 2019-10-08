<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("url");
            $table->bigInteger("menu_id");
            $table->timestamps();
        });

        DB::table("sub_menus")->insert(["name" => "Mensajeria", "url" => "/mail/inbox", "menu_id" => "1"]);
        DB::table("sub_menus")->insert(["name" => "Usuarios", "url" => "/users", "menu_id" => "2"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_menus');
    }
}
