<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ingreso_id');
            $table->foreign('ingreso_id')->references('id')->on('ingresos')->onDelete('cascade');
            $table->bigInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->integer('cantidad');
            $table->decimal('precio',11,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ingresos');
    }
}
