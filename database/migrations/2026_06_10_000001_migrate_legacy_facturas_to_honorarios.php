<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateLegacyFacturasToHonorarios extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('facturas') || ! Schema::hasTable('honorarios')) {
            return;
        }

        foreach (DB::table('facturas')->get() as $factura) {
            $updated = DB::table('honorarios')
                ->where('user_id', $factura->user_id)
                ->where('fecha', $factura->fecha)
                ->whereNull('factura')
                ->update([
                    'factura' => $factura->file,
                    'updated_at' => now(),
                ]);

            if ($updated > 0) {
                DB::table('facturas')->where('id', $factura->id)->delete();
            }
        }

        if (DB::table('facturas')->count() === 0) {
            Schema::drop('facturas');
        }
    }

    public function down()
    {
        if (Schema::hasTable('facturas')) {
            return;
        }

        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('numero');
            $table->text('description');
            $table->date('fecha');
            $table->string('file');
            $table->timestamps();
        });
    }
}
