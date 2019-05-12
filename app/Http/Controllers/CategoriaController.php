<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $categorias = Categoria::orderBy('created_at', 'DESC')->paginate(3);
        }else{
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar .'%')
                                    ->orderBy('created_at', 'DESC')->paginate(3);
        }

        return 
        [
            'pagination' =>[
            "total" => $categorias->total(),
            "current_page" =>  $categorias->currentPage(),
            "per_page" =>  $categorias->perPage(),
            "last_page" =>  $categorias->lastPage(),
            "from"  =>  $categorias->firstItem(),
            "to" =>  $categorias->lastItem(),
        ],

         'categorias' => $categorias
         ];
    }

    public function selectCategoria(Request $request){
        
        if(!request()->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','1')
                                ->select('id','nombre')
                                ->orderBy('nombre','ASC')->get();

        return ['categorias' => $categorias];
    }

   
    public function store(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->save();
    }

 
    public function status(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $categoria =  Categoria::findOrFail((int)$request->id);
        $condicion = $categoria->condicion; 
        if($condicion == 1){
            $condicion = 0;
        }else{
            $condicion = 1;
        }
        $categoria->condicion =   $condicion ;
        $categoria->update();
        return $categoria;
    }
    
  
    public function update(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $categoria =  Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->update();
    }
    // public function destroy($id)
    // {
    //     if(!request()->ajax()) return redirect('/');
    //     $categoria =  Categoria::findOrfail($id);
    //     $categoria->delete();
    //     // // Categoria::destroy($request->id);
        
    //     return ['message' => 'Categoria Eliminada'];
    // }

  
}
