<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEspecialidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("description");
            $table->timestamps();
        });

        DB::table("specialties")->insert(["description" => "Cirugia General"]);
        DB::table("specialties")->insert(["description" => "Pediatria"]);
        DB::table("specialties")->insert(["description" => "Obstetricia"]);
        DB::table("specialties")->insert(["description" => "Traumatologia"]);
        DB::table("specialties")->insert(["description" => "Clinica medica"]);
        DB::table("specialties")->insert(["description" => "Cardiologia"]);
        DB::table("specialties")->insert(["description" => "Laboratorio"]);
        DB::table("specialties")->insert(["description" => "Urologia"]);
        DB::table("specialties")->insert(["description" => "O.R.L"]);
        DB::table("specialties")->insert(["description" => "Oftalmologia"]);
        DB::table("specialties")->insert(["description" => "Ginecologia"]);
        DB::table("specialties")->insert(["description" => "Psiquiatria"]);
        DB::table("specialties")->insert(["description" => "Diagnostico por imagenes"]);
        DB::table("specialties")->insert(["description" => "Radioterapia"]);
        DB::table("specialties")->insert(["description" => "Neurologia"]);
        DB::table("specialties")->insert(["description" => "Cirujia cardiovascular"]);
        DB::table("specialties")->insert(["description" => "Terapia intensiva"]);
        DB::table("specialties")->insert(["description" => "Cirugia pediatrica"]);
        DB::table("specialties")->insert(["description" => "Dermatologia"]);
        DB::table("specialties")->insert(["description" => "Reumatologia"]);
        DB::table("specialties")->insert(["description" => "Oncologia"]);
        DB::table("specialties")->insert(["description" => "Infectologia"]);
        DB::table("specialties")->insert(["description" => "Hematologia"]);
        DB::table("specialties")->insert(["description" => "Neumonologia"]);
        DB::table("specialties")->insert(["description" => "Medicina del dolor"]);
        DB::table("specialties")->insert(["description" => "Patologia"]);
        DB::table("specialties")->insert(["description" => "Endocrinologia"]);
        DB::table("specialties")->insert(["description" => "Diabetes"]);
        DB::table("specialties")->insert(["description" => "Flebologia"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialties');
    }
}
