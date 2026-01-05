<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPagoIdToMhFacturaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mh_factura', function(Blueprint $table)
		{
			$table->integer('pago_id')->unsigned()->nullable()->after('cliente_id');
			$table->foreign('pago_id')->references('id')->on('pagos')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mh_factura', function(Blueprint $table)
		{
			$table->dropForeign(['pago_id']);
			$table->dropColumn('pago_id');
		});
	}

}
