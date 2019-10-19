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
        DB::table("sub_menus")->insert(["name" => "Dispensas", "url" => "/Dispensas", "menu_id" => "2"]);
        //Espacios
        DB::table("sub_menus")->insert(["name" => "Espacios", "url" => "/espacios/list", "menu_id" => "2"]);
        DB::table("sub_menus")->insert(["name" => "Mis solicitudes", "url" => "/espacios/solicitudes/list", "menu_id" => "3"]);
        DB::table("sub_menus")->insert(["name" => "Autorizar", "url" => "/espacios/solicitudes/autorizar", "menu_id" => "3"]);
        DB::table("sub_menus")->insert(["name" => "Archivo", "url" => "/espacios/solicitudes/historial", "menu_id" => "3"]);
        //Facturas
        DB::table("sub_menus")->insert(["name" => "Mis facturas", "url" => "/misFacturas", "menu_id" => "4"]);
        DB::table("sub_menus")->insert(["name" => "Archivo", "url" => "/facturas/show", "menu_id" => "4"]);
        //Honorarios
        DB::table("sub_menus")->insert(["name" => "Mis honorarios", "url" => "/misHonorarios", "menu_id" => "5"]);
        DB::table("sub_menus")->insert(["name" => "Cargar Registros", "url" => "/honorarios/create", "menu_id" => "5"]);
        DB::table("sub_menus")->insert(["name" => "Archivo", "url" => "/honorarios", "menu_id" => "5"]);
        //Dispensas
        DB::table("sub_menus")->insert(["name" => "Mis Dispensas", "url" => "/misDispensas","menu_id" => "6"]);
        DB::table("sub_menus")->insert(["name" => "Autorizaciones nivel 2", "url" => "/autorizaciones2","menu_id" => "6"]);
        DB::table("sub_menus")->insert(["name" => "Autorizaciones nivel 3", "url" => "/autorizaciones3","menu_id" => "6"]);
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
