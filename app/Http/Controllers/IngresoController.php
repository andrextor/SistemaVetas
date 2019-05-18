<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DetalleIngreso;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $ingresos = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
                            ->join('users','ingresos.usuario_id','=', 'users.id')
                            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                                    'ingresos.serie_comprobante','ingresos.num_comprobante',
                                    'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
                                    'personas.nombre','users.usuario')
                            ->orderBy('ingresos.id', 'DESC')->paginate(3);
           

        }else{
            $ingresos = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
                                ->join('users','ingresos.usuario_id','=', 'users.id')
                                ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                                    'ingresos.serie_comprobante','ingresos.num_comprobante',
                                    'ingresos.fecha_hora','ingresos.impuesto','ingresos.total','ingresos.estado',
                                    'personas.nombre','users.usuario')
                                ->where('ingresos.'.$criterio, 'like', '%'. $buscar .'%')
                                ->orderBy('ingreso.id', 'DESC')->paginate(3);
        }

        return 
        [
            'pagination' =>[
            "total" => $ingresos->total(),
            "current_page" =>  $ingresos->currentPage(),
            "per_page" =>  $ingresos->perPage(),
            "last_page" =>  $ingresos->lastPage(),
            "from"  =>  $ingresos->firstItem(),
            "to" =>  $ingresos->lastItem(),
        ],

         'ingresos' => $ingresos
         ];
    }

    public function store(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime = Carbon::now('America/Lima');

            $ingreso = new Ingreso();
            $ingreso->proveedor_id = $request->proveedor_id;
            $ingreso->usuario_id = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = "Registrado";
            $ingreso->save();

            $detalles = $request->data;//arrar detalles
            // recorro todos los elementos

            foreach($detalles as $ep=>$det){

                $detalle = new DetalleIngreso();
                $detalle->ingreso_id = $ingreso->id;
                $detalle->articulo_id =  $det['articulo_id'];
                $detalle->cantidad =  $det['cantidad_articulo'];
                $detalle->precio =  $det['precio_articulo'];
                $detalle->save();
            }
          
            DB::commit();

        } catch ( Exception $e){
            DB::rollBack();
        }   
        
    }

    public function anular(Request $request){
        
        if(!request()->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = "Anulado";
        $ingreso->save();
        
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $datosIngreso = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
        ->join('users','ingresos.usuario_id','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','ingresos.updated_at','personas.nombre','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->first();
         
        return ['datosIngreso' => $datosIngreso];
    }
    public function obtenerDetalle(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $detalleIngreso = DetalleIngreso::join('articulos','detalle_ingresos.articulo_id','=','articulos.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
        ->where('detalle_ingresos.ingreso_id','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();
         
        return ['detalleIngreso' => $detalleIngreso];
    }
}
