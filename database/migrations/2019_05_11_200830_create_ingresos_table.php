<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('proveedor_id');
            $table->bigInteger('usuario_id');
            $table->string('tipo_comprobante',20);
            $table->string('serie_comprobante',20);
            $table->string('num_comprobante',10)->nullable();
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto',4,2);
            $table->decimal('total',11,2);
            $table->string('estado',20);
            $table->timestamps();
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}
