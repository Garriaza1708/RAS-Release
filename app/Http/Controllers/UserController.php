<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function showPage()
    {
        return view('menus.usuarios');
    }

    public function listAll()
    {
        return Datatables::of(User::select('id','nombre','apellidos',
        'direccion','email')->get())->make(true);
    }

    
    public function setSession(Request $request)
    {
         $request->session()->put('idUsuario',$request->id);
         return response()->json(["Sesion"=>"Asignado"]);
    }


    public function save(Request $request)
    {
        if ($request->ajax()) {
            if ($request->Accion == "Registrar") {
				$user            = new User;
				$user->nombre    = $request->Nombre;
				$user->apellidos = $request->Apellidos;
				$user->direccion = $request->Direccion;
				$user->email     = $request->Email;
				$user->password  = bcrypt($request->Password);
                $user->foto = 'img/default-user.png';
                if($user->save())
                    return response()->json(["Estado"=>"Registrado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            }else if($request->Accion == "Editar"){
                $user            = User::find($request->session()->get('idUsuario'));
               	$user->nombre    = $request->Nombre;
                $user->apellidos = $request->Apellidos;
				$user->direccion = $request->Direccion;
				$user->email     = $request->Email;
				if($request->Password != "")
				$user->password  = bcrypt($request->Password);

                if($user->save())
                    return response()->json(["Estado"=>"Editado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            } 
            $request->session()->forget('idUsuario');
        }else
            return response()->json(["Estado"=>"Error"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Period  $period
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $user = User::find($request->id);
        if (!is_null($user)) {
            $user->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }

    public function subirFoto(Request $request)
    {
        $user = User::find($request->session()->get('idUsuario'));
        if (!is_null($user)) {
            $request->file('file')->store('public/usuarios/'.$request->session()->get('idUsuario'));
            
            $ruta = $request->file('file')->store('storage/usuarios/'.$request->session()->get('idUsuario'));
            $user->foto = $ruta;
            if ($user->save()){
                return response()->json(["Estado"=>"Subido"]);
            }
        }else{
            return response()->json(["Estado"=>"Error"]);
        }
        $request->session()->forget('idUsuario');    
    }
}
