<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 30)->uniqid();
            $table->string('descripcion', 100)->nullable();
            $table->boolean('condicion')->default(1);
            $table->timestamps();
        });
        DB::table('roles')->insert(array('id' => '1',
                                    'nombre' => 'Administrador',
                                    'descripcion' => 'Permiso Total',
                                    'condicion' =>'1',
                                    'created_at' => '2019-05-09 14:03:44',
                                    'updated_at' => '2019-05-09 14:03:44',));
        DB::table('roles')->insert(array('id' => '2',
                                    'nombre' => 'Vendedor',
                                    'descripcion' => 'Ventas',
                                    'condicion' =>'1',
                                    'created_at' => '2019-05-09 14:03:44',
                                    'updated_at' => '2019-05-09 14:03:44',));
        DB::table('roles')->insert(array('id' => '3',
                                    'nombre' => 'Bodeguero',
                                    'descripcion' => 'Encargado de las Bodegas',
                                    'condicion' =>'1',
                                    'created_at' => '2019-05-09 14:03:44',
                                    'updated_at' => '2019-05-09 14:03:44',));
    }           

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
