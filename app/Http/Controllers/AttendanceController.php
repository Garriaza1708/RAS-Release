<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Lesson;
use App\Student;
class AttendanceController extends Controller
{
 
    public function existsAttendance($lesson,$student)
    {
        $result = Attendance::where('lesson_id',$lesson)
                    ->where('student_id',$student)
                    ->first();

        return $result;
    }


    public function save(Request $request)
    {
        $array;
        if($request->ajax())
        {
            if(is_array($request->Alumnos) && is_array($request->Asistencias)){
                $array = array_combine($request->Alumnos, $request->Asistencias);
                foreach ($array as $key => $value) {
                    if(!is_null($this->existsAttendance($request->Clase,$key))){
                        $attendance = $this->existsAttendance($request->Clase,$key);
                        $this->updateAttendance($value,$attendance);
                    }else{
                       $this->newAttendance($value,$request->Clase,$key);
                    }
                }
                return response()->json(["Estado" => "Guardado"]);
            }else{
                if(!is_null($this->existsAttendance($request->Clase,$key)))
                   $this->updateAttendance($request->Asistencias,
                        $this->existsAttendance($request->Clase,$key));
                else
                    $this->newAttendance($request->Asistencias,$request->Clase,
                        $request->Alumnos);
                    return response()->json(["Estado" => "Guardado"]);
   
            }
        }
        return response()->json(["Estado" => "Error"]);
    }

    public function newAttendance($estado,$lesson,$student)
    {
        $attendance = new Attendance;
        $attendance->estado = $estado;
        $attendance->lesson_id = $lesson;
        $attendance->student_id = $student;
        $attendance->save();
    }

    public function updateAttendance($estado, Attendance $attendance)
    {
        $attendance->estado = $estado;
            if($attendance->save())
                return response()->json(["Estado" => "Guardado"]);

        return response()->json(["Estado" => "Error"]);   
    }

    public function getAttendanceLesson($id)
    {
        $lesson = Attendance::where('lesson_id',$id)->get();
        return $lesson;
    }

}
