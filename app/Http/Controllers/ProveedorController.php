<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;
use App\Proveedor;

class ProveedorController extends Controller
{
   
    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $proveedores = Proveedor::join('personas','proveedores.persona_id','=','personas.id')
                            ->select('personas.id','personas.nombre','personas.tipo_documento',
                                    'personas.numero_documento','personas.direccion',
                                    'personas.telefono','personas.email',
                                    'proveedores.contacto','proveedores.telefono_contacto')
                            ->orderBy('personas.id', 'DESC')->paginate(3);

        }else{
            $proveedores = Proveedor::join('personas','proveedores.persona_id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
                    'personas.numero_documento','personas.direccion',
                    'personas.telefono','personas.email',
                    'proveedores.contacto','proveedores.telefono_contacto')
            ->where('personas.'.$criterio, 'like', '%'. $buscar .'%')
                                    ->orderBy('personas.id', 'DESC')->paginate(3);
        }

        return 
        [
            'pagination' =>[
            "total" => $proveedores->total(),
            "current_page" =>  $proveedores->currentPage(),
            "per_page" =>  $proveedores->perPage(),
            "last_page" =>  $proveedores->lastPage(),
            "from"  =>  $proveedores->firstItem(),
            "to" =>  $proveedores->lastItem(),
        ],

         'personas' => $proveedores
         ];
    }

    public function store(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->numero_documento = $request->numero_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->persona_id = $persona->id;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();

            DB::commit();

        } catch ( Exception $e){
            DB::rollBack();
        }   
        
    }

    public function update(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        try{
        DB::beginTransaction();

        $persona =  Persona::findOrFail($request->id);
            
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->numero_documento = $request->numero_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->update();
        
            $proveedor =  Proveedor::where('persona_id',$request->id)->first();
            
            $proveedor->persona_id = $request->id;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();
            
            DB::commit();
            
            return $persona;
         
        } catch ( Exception $e){
            DB::rollBack();
        }   
   
        
        
    }
}
