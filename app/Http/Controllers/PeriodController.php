<?php

namespace App\Http\Controllers;

use App\Period;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
class PeriodController extends Controller
{

    public function showPage(){
        return view('menus.periodos');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        return Datatables::of(Period::select('id','nombre','duracion','año','descripcion')
            ->orderBy('año','DESC')
            ->get())->make(true);
    }

    
    public function setSession(Request $request)
    {
         $request->session()->put('idPeriod',$request->id);
         return response()->json(["Sesion"=>"Asignado"]);
    }


    public function save(Request $request)
    {
        if ($request->ajax()) {
            if ($request->Accion == "Registrar") {
                $per              = new Period;
                $per->nombre      = $request->Nombre;
                $per->duracion    = $request->Duracion;
                $per->año         = $request->Anio;
                $per->descripcion = $request->Descripcion;
                if($per->save())
                    return response()->json(["Estado"=>"Registrado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            
            }else if($request->Accion == "Editar"){
                $per              = Period::find($request->session()->get('idPeriod'));
                $per->nombre      = $request->Nombre;
                $per->duracion    = $request->Duracion;
                $per->año         = $request->Anio;
                $per->descripcion = $request->Descripcion;
                if($per->save())
                    return response()->json(["Estado"=>"Editado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            } 
            $request->session()->forget('idPeriod');
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
        $per = Period::find($request->id);
        if (!is_null($per)) {
            $per->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }
}
