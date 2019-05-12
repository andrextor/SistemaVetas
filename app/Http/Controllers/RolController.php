<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $roles = Rol::orderBy('created_at', 'DESC')->paginate(3);
        }else{
            $roles = rol::where($criterio, 'like', '%'. $buscar .'%')
                                    ->orderBy('created_at', 'DESC')->paginate(3);
        }

        return 
        [
            'pagination' =>[
            "total" => $roles->total(),
            "current_page" =>  $roles->currentPage(),
            "per_page" =>  $roles->perPage(),
            "last_page" =>  $roles->lastPage(),
            "from"  =>  $roles->firstItem(),
            "to" =>  $roles->lastItem(),
        ],

         'roles' => $roles
         ];
    }

    
    public function store(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $rol = new Rol();
        $rol->descripcion = $request->descripcion;
        $rol->condicion = 1;
        $rol->save();
    }

    public function status(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        
        $rol =  Rol::findOrFail($request->id);
        $condicion = (int)$rol->condicion; 
        if($condicion == 1){
            $condicion = 0;
        }else{
            $condicion = 1;
        }
        $rol->condicion =   $condicion ;
        $rol->update();
        return $rol;
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
