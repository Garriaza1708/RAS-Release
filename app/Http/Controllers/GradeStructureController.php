<?php

namespace App\Http\Controllers;

use App\Grade_Structure;
use App\Grade;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
class GradeStructureController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function setSession(Request $request)
    {
         $request->session()->put('idGradeSet',$request->id);
         $nota = Grade::select('nombre')->where('id',$request->session()->get('idGradeSet'))->first();
         return response()->json(["Sesion"=>"Asignado","NombreNota"=>$nota->nombre]);
    }

    public function setSessionStructure(Request $request)
    {
        $request->session()->put('idGradeStructure',$request->id);
        return response()->json(["Sesion"=>"Asignado"]);
    }


    public function listAll(Request $request)
    {
        return Datatables::of(Grade_Structure::select('id','nombre')
            ->where('grade_id',$request->session()->get('idGradeSet'))
            ->get())->make(true);
    }


    public function save(Request $request)
    {
        if ($request->ajax()) {
            if ($request->AccionSubNota == "RegistrarSubNota") {
                $nots              = new Grade_Structure;
                $nots->nombre      = $request->NombreSubNota;
                $nota = Grade::find($request->session()->get('idGradeSet'));
                if($nota->grades_structures()->save($nots))
                    return response()->json(["Estado"=>"Registrado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            
            }else if($request->AccionSubNota == "EditarSubNota"){
                $nots              = Grade_Structure::find($request->session()->get('idGradeStructure'));
                $nots->nombre      = $request->NombreSubNota;
                $nota = Grade::find($request->session()->get('idGradeSet'));
                if($nota->grades_structures()->save($nots))
                    return response()->json(["Estado"=>"Editado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            }
            $request->session()->forget('idGradeStructure');
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
        $nots = Grade_Structure::find($request->id);
        if (!is_null($nots)) {
            $nots->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }
}
