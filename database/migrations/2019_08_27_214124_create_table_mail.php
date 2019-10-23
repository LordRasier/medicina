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

        DB::statement("create view inboxes as select `a`.`id` AS `oid`,`a`.`name` AS `oname`,`a`.`email` AS `oemail`,`a`.`profile` AS `oprofile`,`b`.`id` AS `id`,`b`.`asunto` AS `asunto`,`b`.`body` AS `body`,`b`.`created_at` AS `created_at`,`c`.`id` AS `did`,`c`.`name` AS `dname`,`c`.`email` AS `demail`,`c`.`profile` AS `dprofile` from ((`medicina`.`users` `a` join `medicina`.`mails` `b` on((`a`.`id` = `b`.`origin`))) join `medicina`.`users` `c` on((`b`.`destiny` = `c`.`id`)))");


    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mails');
    }
}
