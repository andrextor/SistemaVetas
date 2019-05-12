<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{

    protected $table = 'ingresos';
    protected $fillable = ['proveedor_id',
                            'usuario_id',
                            'tipo_comprobante',
                            'serie_comprobante',
                            'num_comprobante',
                            'fecha_hora',
                            'impuesto',
                            'total',
                            'estado',
                        ];

    public function usuario(){
        return $this->belongsTo('App\User');
    }
    public function proveedor(){
        return $this->belongsTo('App\Proveedor');
    }
}
