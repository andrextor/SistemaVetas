<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //

    // protected $table = 'articulos';
    protected $fillable = [ 'categoria_id',
                            'codigo',
                            'nombre',
                            'precio_venta',
                            'stock',
                            'descripcion',
                            'condicion'];
    
    //Relation Many to one / muchos a uno

    public function categorias(){
        return $this->belongsTo('App\Categoria', 'categoria_id');
    }
}
