<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Course;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function showPage(){
        $cursosConfigurables = Course::select('id','nombre')
            ->where('period_id',\Session::get('idPeriodo'))
            ->get();
        $cursos = Course::select('id','nombre')->orderBy('nombre','ASC')
                ->where('period_id',\Session::get('idPeriodo'))
                ->get();
        return view('menus.notas',compact('cursos','cursosConfigurables'));
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        return Datatables::of(Grade::join('courses as co','co.id','=','grades.course_id')
            ->select('grades.id as id','co.id as curso_id','grades.nombre as nombre','grades.descripcion as descripcion','co.nombre as curso')
            ->where('co.period_id',\Session::get('idPeriodo'))
            ->get())->make(true);
    }

    
    public function setSession(Request $request)
    {
         $request->session()->put('idGrade',$request->id);
         return response()->json(["Sesion"=>"Asignado"]);
    }


    public function save(Request $request)
    {
        if ($request->ajax()){
            if ($request->Accion == "Registrar") {
                $not              = new Grade;
                $not->nombre      = $request->Nombre;
                $not->descripcion = $request->Descripcion;
                $curso = Course::find($request->Curso);

                if($curso->grades()->save($not))
                    return response()->json(["Estado"=>"Registrado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            
            }else if($request->Accion == "Editar"){
                $not              = Grade::find($request->session()->get('idGrade'));
                $not->nombre      = $request->Nombre;
                $not->descripcion = $request->Descripcion;
                $curso = Course::find($request->Curso);
                if($curso->grades()->save($not))
                    return response()->json(["Estado"=>"Editado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            } 
            $request->session()->forget('idGrade');
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
        $not = Grade::find($request->id);
        if (!is_null($not)) {
            $not->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }
}
