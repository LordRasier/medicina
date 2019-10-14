<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("code");
            $table->string('name');
            $table->string("doc")->unique();
            $table->string("phone")->default("");
            $table->string('email')->unique();
            $table->string("profile")->default("default.png");
            $table->integer("horas")->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->date("ingreso")->default("2019-06-01");
            $table->string('password');
            $table->integer("specialty");
            $table->integer("type");
            $table->integer("dedication");
            $table->integer("active")->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table("users")->insert(["code" => "9999", "name" => "SMART", "doc" => "00000000", "email" => "smart@smart.com","specialty" => 1, "type" => 1, "dedication" => 1, "password" => '$2y$10$CW9lzuoect4nc/VGUgpB1ewMuugO3VswmrcoE3.wowqa4KWUl/Ovi']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
