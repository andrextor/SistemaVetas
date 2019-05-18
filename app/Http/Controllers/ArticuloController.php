<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;

class ArticuloController extends Controller
{

    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $articulos = Articulo::join('categorias','articulos.categoria_id','=','categorias.id')
                                    ->select('articulos.id','articulos.categoria_id','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                                    ->orderBy('articulos.id', 'DESC')->paginate(3);
        }else{

            $articulos = Articulo::join('categorias','articulos.categoria_id','=','categorias.id')
                                    ->select('articulos.id','articulos.categoria_id','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                                    ->where('articulos.'.$criterio, 'like', '%'. $buscar .'%')
                                    ->orderBy('articulos.id', 'DESC')->paginate(3);
            
        }

        return 
        [
            'pagination' =>[
            "total" => $articulos->total(),
            "current_page" =>  $articulos->currentPage(),
            "per_page" =>  $articulos->perPage(),
            "last_page" =>  $articulos->lastPage(),
            "from"  =>  $articulos->firstItem(),
            "to" =>  $articulos->lastItem(),
        ],

         'articulos' => $articulos
         ];

    }

    public function listarArticulos(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $articulos = Articulo::join('categorias','articulos.categoria_id','=','categorias.id')
                                    ->select('articulos.id','articulos.categoria_id','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                                    ->where('articulos.condicion',1)
                                    ->orderBy('articulos.id', 'DESC')->paginate(10);
        }else{

            $articulos = Articulo::join('categorias','articulos.categoria_id','=','categorias.id')
                                    ->select('articulos.id','articulos.categoria_id','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                                    ->where('articulos.condicion',1)
                                    ->Where('articulos.'.$criterio, 'like', '%'. $buscar .'%')
                                    ->orderBy('articulos.id', 'DESC')->paginate(10);
            
        }

        return [ 'articulos' => $articulos ];

    }

    public function bucarArticulo(Request $request){
        if(!request()->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $articulo = Articulo::where('codigo',$filtro)
                            ->select('id','nombre')->first();

        return ['articulo' => $articulo];
    }

    public function store(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $articulo = new Articulo();
        $articulo->categoria_id = $request->categoria_id;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();
    }

    public function status(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $articulo =  Articulo::findOrFail($request->id);
        $condicion = (int)$articulo->condicion; 
        if($condicion == 1){
            $condicion = 0;
        }else{
            $condicion = 1;
        }
        $articulo->condicion =   $condicion ;
        $articulo->update();
        return $articulo;
    }
    

    public function update(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $articulo =  Articulo::findOrFail($request->id);
        

        $articulo->categoria_id = $request->categoria_id;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->update();
    }

}
