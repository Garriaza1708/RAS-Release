<?php

namespace App\Http\Controllers;

use App\Student;
use App\Course;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
class StudentController extends Controller
{
    public function showPage()
    {
        $courses = Course::select('id','nombre')->orderBy('nombre','ASC')
                ->where('period_id',\Session::get('idPeriodo'))
                ->get();
                
        $students = Student::select('id','nombre','apellidos')
            ->orderBy('apellidos','ASC')->get();
            
        return view('menus.alumnos',compact('courses','students'));
    }
    
    public function listAll()
    {
        return Datatables::of(Student::select('id','nombre','apellidos',
            'direccion','email')->get())->make(true);
    }

    
    public function setSession(Request $request)
    {
         $request->session()->put('idAlumno',$request->id);
         return response()->json(["Sesion"=>"Asignado"]);
    }


    public function save(Request $request)
    {
        if ($request->ajax()) {
            if ($request->Accion == "Registrar") {
                $alu            = new Student;
                $alu->nombre    = $request->Nombre;
                $alu->apellidos = $request->Apellidos;
                $alu->direccion = $request->Direccion;
                $alu->email     = $request->Email;
                if($alu->save())
                    return response()->json(["Estado"=>"Registrado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            }else if($request->Accion == "Editar"){
                $alu            = Student::find($request->session()->get('idAlumno'));
                $alu->nombre    = $request->Nombre;
                $alu->apellidos = $request->Apellidos;
                $alu->direccion = $request->Direccion;
                $alu->email     = $request->Email;
                if($alu->save())
                    return response()->json(["Estado"=>"Editado"]);
                else
                    return response()->json(["Estado"=>"Error"]);    
            } 
            $request->session()->forget('idAlumno');
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
        $alu = Student::find($request->id);
        if (!is_null($alu)) {
            $alu->delete();
            return response()->json(["Estado"=>"Eliminado"]);
        }
            return response()->json(["Estado"=>"Error"]);
    }

    public function asignarCursos(Request $request)
    {
        $stu = Student::find($request->Estudiante);
        if ($request->ajax()) {
            if(is_array($request->Cursos)){
                foreach ($request->Cursos as $curso){
                    if($curso != null)
                        if(!$stu->courses()->where('course_id', $curso)->exists())
                            $stu->courses()->attach($curso);
                }
                return response()->json(["Estado"=>"Registrado"]);
            }else{
                if($request->Cursos != null)
                    if(!$stu->courses()->where('course_id', $request->Cursos)->exists())
                        $stu->courses()->attach($request->Cursos);
                return response()->json(["Estado"=>"Registrado"]);
            }
        }
        return response()->json(["Estado"=>"Error"]);
    }

    public function getAlumnos()
    {
        $students = Student::select('id','nombre','apellidos')
            ->orderBy('apellidos','ASC')->get();
        
        return $students;
    }
}
