<?php

namespace App\Http\Controllers;

use App\Grade_Value;
use App\Course;
use App\Grade;
use App\Grade_Structure;
use App\Student;
use App\Period_Range;
use DB;
use Illuminate\Http\Request;

class GradeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPage()
    {
        $courses = Course::where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')
                ->get();

        $periods = Period_Range::where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')
                ->get();

        return view('menus.subirnotas',compact('courses','periods'));
    }

    public function getGradesByCourse($id)
    {
        $grades = Grade::orderBy('nombre','ASC')
                  ->where('course_id',$id)->get();//notas
        $subgrades = Grade_Structure::orderBy('nombre','ASC')->get();
        return response()->json([
                "grades" => $grades,
                "subgrades" => $subgrades
                ]);
    }

    public function getStudents($id,$idGrade)
    {
        \Session::put('idGradeSelect',$idGrade);
        \Session::put('idCourseSelect',$id);

        $students = Student::join('courses_students as cs','students.id','=',
                    'cs.student_id')
                    ->select('students.id as idAlumno',
                        DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS 
                            alumno"))
                    ->where('cs.course_id',\Session::get('idCourseSelect'))
                    ->orderBy('alumno','ASC')
                    ->get();

        $subgrades = Grade_Structure::where('grade_id',\Session::get('idGradeSelect'))
                    ->orderBy('nombre','ASC')->get();

        $curso = Course::where('id',\Session::get('idCourseSelect'))->first();

        $grade = Grade::where('id',\Session::get('idGradeSelect'))->first();

        return view('menus.listaalumnos',
                compact('students','subgrades','curso','grade'));
    }

    public function existsGradeValue($grade,$student,$period)
    {
        $result = Grade_Value::where('grade_structures_id',$grade)
                    ->where('student_id',$student)
                    ->where('period_ranges_id',$period)
                    ->first();

        return $result;
    }

    public function save(Request $request)
    {
        $array;
        if($request->ajax())
        {
            if(is_array($request->Alumnos) && is_array($request->Notas)){
                $array = array_combine($request->Alumnos, $request->Notas);
                foreach ($array as $key => $value) {
                    if(!is_null($this->existsGradeValue($request->idSubGrade,$key,
                        $request->idPeriodo))){
                        $grade_value = $this->existsGradeValue($request->idSubGrade,
                            $key,$request->idPeriodo);
                        $this->updateGradeValue($value,$request->idPeriodo,
                            $grade_value);
                    }else{
                        $this->newGradeValue($value,$request->idSubGrade,$key,
                            $request->idPeriodo);
                    }
                }
                return response()->json(["Estado" => "Guardado"]);
            }else{
                if(!is_null($this->existsGradeValue($request->idSubGrade,
                    $request->Alumnos,$request->idPeriodo))){
                    $grade_value = $this->existsGradeValue($request->idSubGrade,
                            $request->Alumnos,$request->idPeriodo);
                    $this->updateGradeValue($request->Notas,$request->idPeriodo,
                        $grade_value);
                }else
                    $this->newGradeValue($request->Notas,$request->idSubGrade,
                        $request->Alumnos,$request->idPeriodo);
                    return response()->json(["Estado" => "Guardado"]);
            }
        }
        return response()->json(["Estado" => "Error"]);
    }

    public function newGradeValue($value,$gradeEstructure,$student,$period)
    {
        $grade_value = new Grade_Value;
        $grade_value->grade = $value;
        $grade_value->grade_structures_id = $gradeEstructure;
        $grade_value->student_id = $student;
        $grade_value->period_ranges_id = $period;
        $grade_value->save();
    }

    public function updateGradeValue($grade,$period,Grade_Value $grade_value)
    {
        $grade_value->grade = $grade;
        $grade_value->period_ranges_id = $period;
            if($grade_value->save())
                return response()->json(["Estado" => "Guardado"]);

        return response()->json(["Estado" => "Error"]);   
    }

    public function getGradeStudents($id,$idPeriodo)
    {
        $result = Grade_Value::select('id','grade','student_id')
                ->where('grade_structures_id',$id)
                ->where('period_ranges_id',$idPeriodo)
                ->get();
        return $result;
    }
}
