<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('mails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("origin");
            $table->string("asunto");
            $table->text("body");
            $table->bigInteger("destiny");
            $table->timestamps();
        });


        DB::statement("ALTER TABLE Users ALTER profile SET DEFAULT 'qHr7DTBdBQVB3ZgHVsbCt9yB1ypuNmfbBkokL5Cg.png';");


    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mails');
        Schema::dropIfExists('inboxes');
    }
}
