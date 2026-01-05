<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMhActividadEconomicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mh_actividad_economica', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 10);
            $table->string('descripcion', 255);
            $table->timestamps();
            
            $table->index('codigo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mh_actividad_economica');
    }
}
