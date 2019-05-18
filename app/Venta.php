<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'impuesto',
        'total',
        'estado'
    ];
}
