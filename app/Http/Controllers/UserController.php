<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Persona;
use App\Rol;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
      
    public function index(Request $request)
    {
        if(!request()->ajax()) return redirect('/');

        $buscar  = $request->buscar;
        $criterio  = $request->criterio;

        if($buscar == ''){

            $usuario = User::join('personas','users.persona_id','=','personas.id')
                            ->join('roles','users.rol_id','=', 'roles.id')
                            ->select('personas.id','personas.nombre','personas.tipo_documento',
                                    'personas.numero_documento','personas.direccion',
                                    'personas.telefono','personas.email',
                                    'roles.nombre as rol',
                                    'users.usuario','users.rol_id','users.password','users.condicion')
                            ->orderBy('personas.id', 'DESC')->paginate(3);

        }else{
            $usuario = User::join('personas','users.persona_id','=','personas.id')
                                ->join('roles','users.rol_id','=', 'roles.id')
                                ->select('personas.id','personas.nombre','personas.tipo_documento',
                                        'personas.numero_documento','personas.direccion',
                                        'personas.telefono','personas.email',
                                        'roles.nombre as rol',
                                        'users.persona_id','users.rol_id','users.password','users.condicion')
                                ->where('personas.'.$criterio, 'like', '%'. $buscar .'%')
                                ->orderBy('personas.id', 'DESC')->paginate(3);
        }

        return 
        [
            'pagination' =>[
            "total" => $usuario->total(),
            "current_page" =>  $usuario->currentPage(),
            "per_page" =>  $usuario->perPage(),
            "last_page" =>  $usuario->lastPage(),
            "from"  =>  $usuario->firstItem(),
            "to" =>  $usuario->lastItem(),
        ],

         'usuarios' => $usuario
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

            $user = new User();
            $user->persona_id = $persona->id;
            $user->rol_id = $request->rol_id;
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = 1;
        
            $user->save();

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
        
            $user =  User::where('persona_id',$request->id)->first();
            
            $user->persona_id = $persona->id;
            $user->rol_id = $request->rol_id;
            $user->usuario = $request->usuario;
            $user->condicion = 1;
            $user->password = bcrypt($request->password);
            $user->save();

            DB::commit();
                 
         
        } catch ( Exception $e){
            DB::rollBack();
        }   
   
        
        
    }

    public function status(Request $request)
    {
        if(!request()->ajax()) return redirect('/');
        $user =  User::where('persona_id',(int)$request->id)->first();
        $persona =  Persona::findOrFail((int)$request->id);
        $condicion = $user->condicion; 
        if($condicion == 1){
            $condicion = 0;
        }else{
            $condicion = 1;
        }
        $user->condicion =   $condicion ;
        $user->save();
        return ['user' => $user,
                'persona' => $persona];
    }

    public function selectRol(Request $request){
        
        if(!request()->ajax()) return redirect('/');
        $roles = Rol::where('condicion','1')
                                ->select('id','nombre')
                                ->orderBy('nombre','ASC')->get();

        return ['roles' => $roles];
    }

    
}
