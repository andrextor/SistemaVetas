<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable= ['nombre','tipo_documento','numero_documento','direccion','telefono','email'];

    public function proveedor(){
        return $this->hasOne('App\Proveedor');
    }

    public function user(){
        return $this->hasOne('App\user');
    }
}
