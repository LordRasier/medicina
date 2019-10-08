<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubMenuUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menu_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sub_menu_id');
            $table->bigInteger('user_id');
        });

        DB::table("sub_menu_user")->insert(["sub_menu_id" => 2, "user_id" => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_menu_user');
    }
}
