<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Period;
use App\Period_Range;
use App\Lesson;
use App\Student;
use DB;
class LessonController extends Controller
{
	public function __construct()
	{
		$curso = Course::take(1)->first();
            if($curso != null)
		      if(!(\Session::has('idCursoFiltro'))) 
                    \Session::put('idCursoFiltro',$curso->id);
	}

    public function showPage()
    {
    	$cursos = Course::where('period_id',\Session::get('idPeriodo'))->get();
    	$periodo = Period::select('duracion')->where('id',\Session::get('idPeriodo'))->first();
    	$unidades = Period_Range::where('period_id',\Session::get('idPeriodo'))
                   ->orderBy('nombre','ASC')
                   ->get();
        $lessons = Lesson::where('course_id',\Session::get('idCursoFiltro'))
                       ->orderBy('id','DESC')
                       ->get();
        $students = Student::join('courses_students as cs','students.id','=',
            'cs.student_id')
                    ->select('students.id as id',
                        DB::raw("CONCAT(students.nombre,' ',students.apellidos) AS alumno"))
                    ->where('course_id',\Session::get('idCursoFiltro'))
                    ->get();               
    	return view('menus.clases',
            compact('cursos','periodo','unidades','lessons','students'));
    }

    public function setCurso($id)
    {
    	\Session::put('idCursoFiltro',$id);
        $lessons = Lesson::where('course_id',\Session::get('idCursoFiltro'))
                       ->orderBy('id','DESC')
                       ->get();
        $unidades = Period_Range::where('period_id',\Session::get('idPeriodo'))
                   ->orderBy('nombre','ASC')
                   ->get();
        $students = Student::join('courses_students as cs','students.id','=','cs.student_id')
                    ->select('students.id as id',
                        DB::raw("CONCAT(students.nombre,' ',students.apellidos) AS alumno"))
                    ->where('course_id',\Session::get('idCursoFiltro'))
                    ->get();
        return response()->json([
                            "Lessons"=>$lessons,
                            "Unidades"=>$unidades,
                            "Alumnos"=>$students]);
    }

    public function save(Request $request)
    {
    	if($request->ajax())
    	{
    		$lesson = new Lesson;
    		$lesson->nombre = $request->Nombre;
    		$lesson->fecha = date('Y-m-d');
    		$lesson->hora_inicio = date('H:i:s');
    		$lesson->semana = $request->SemanaClase;
    		$lesson->period_range_id = $request->UnidadClase;
    		$lesson->course_id = \Session::get('idCursoFiltro');
    		if($lesson->save()){
                $listado = Lesson::where('semana',$request->SemanaClase)
                       ->where('period_range_id',$request->UnidadClase)
                       ->where('course_id',\Session::get('idCursoFiltro'))
                       ->orderBy('id','DESC')
                       ->get();
    			return response()->json([
                                        "Estado"=> "Registrado",
                                        "Listado"=> $listado
                                        ]);
            }
    		
    	}
    	return response()->json(["Estado"=>"Error"]);
    }

    public function getLessonById($id)
    {
    	$lesson = Lesson::find($id);
    	return $lesson;
    }

    public function update(Request $request)
    {
        if($request->ajax())
        {
            $lesson = Lesson::find($request->Actualizado);
            $lesson->nombre = $request->NombreNuevo;
            //$lesson->fecha = date('Y-m-d');//configurable
            //$lesson->hora_inicio = date('H:i:s');//configurable
            if($lesson->save()){
                $listado = Lesson::where('semana',$request->SemanaClaseActualizado)
                       ->where('period_range_id',$request->UnidadClaseActualizado)
                       ->where('course_id',\Session::get('idCursoFiltro'))
                       ->orderBy('id','DESC')
                       ->get();
                return response()->json([
                                    "Estado"=>"Actualizado",
                                    "Listado"=> $listado
                                    ]);
            }
        }
        return response()->json(["Estado"=>"Error"]);
    }

    public function delete($id)
    {
        $lesson = Lesson::find($id);
        if(!(is_null($lesson)))
        {
            $lesson->delete();
                return response()->json(["Estado"=>"Eliminado"]);
        }
        return response()->json(["Estado"=>"Error"]);
    }
}
