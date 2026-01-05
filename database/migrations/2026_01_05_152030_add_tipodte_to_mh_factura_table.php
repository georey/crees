<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoDteToMhFacturaTable extends Migration
{
    public function up()
    {
        Schema::table('mh_factura', function (Blueprint $table) {
            $table->integer('tipo_dte')->default(1)->after('cliente_id');
        });
    }

    public function down()
    {
        Schema::table('mh_factura', function (Blueprint $table) {
            $table->dropColumn('tipo_dte');
        });
    }
}
