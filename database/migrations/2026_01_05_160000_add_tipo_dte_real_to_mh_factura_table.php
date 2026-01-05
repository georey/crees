<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoDteRealToMhFacturaTable extends Migration
{
    public function up()
    {
        Schema::table('mh_factura', function (Blueprint $table) {
            if (!Schema::hasColumn('mh_factura', 'tipo_dte')) {
                $table->integer('tipo_dte')->default(1)->after('cliente_id');
            }
        });
    }

    public function down()
    {
        Schema::table('mh_factura', function (Blueprint $table) {
            if (Schema::hasColumn('mh_factura', 'tipo_dte')) {
                $table->dropColumn('tipo_dte');
            }
        });
    }
}
