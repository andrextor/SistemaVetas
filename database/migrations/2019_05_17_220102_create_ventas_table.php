<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cliente_id'); 
            $table->foreign('cliente_id')->references('id')->on('perosnas'); 
            $table->bigInteger('usuario_id'); 
            $table->foreign('usuario_id')->references('id')->on('users'); 
            $table->string('tipo_compobrante',20); 
            $table->string('serie_compobrante',20)->nullable(); 
            $table->string('num_compobrante',20); 
            $table->decimal('impuesto',4,2); 
            $table->decimal('total',11,2); 
            $table->string('estado',20); 
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
        Schema::dropIfExists('ventas');
    }
}
