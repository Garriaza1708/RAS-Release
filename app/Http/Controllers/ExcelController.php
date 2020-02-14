<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Student;
class ExcelController extends Controller
{
    public function descargarPlantilla()
    {
    	$pathToFile = storage_path()."/excel/PLANTILLA_ALUMNOS.xlsx";
    	return response()->download($pathToFile);
    }

    public function isValidCantidad($excel)
    {
        $isValid = false;

        $can =  (((Excel::selectSheetsByIndex(0)->load($excel))->get())->first())->count(); 

        if($can == 4)
            $isValid = true;
        
        return $isValid;
    }

    public function isValidOrdenEstructura($excel)
    {
        $isValid = false;

        $cabeceras =  ((((Excel::selectSheetsByIndex(0)->load($excel))->all())->first())->keys())->toArray(); 
        
        if($cabeceras[0] == "nombres" && $cabeceras[1] == "apellidos" &&
            $cabeceras[2] == "direccion" && $cabeceras[3] == "email")
            $isValid = true;

        return $isValid;
    }

    public function subirExcel(Request $request)
    {
        if ($request->hasFile('excel')){
            $ext = $request->file('excel')->getClientOriginalExtension();
            if($ext == 'xlsx' || $ext == 'xls'){//valida si es excel
                if($this->isValidCantidad($request->file('excel')))//cantidad de columnas
                {
                    //orden de las columnas y nombres de columnas son correctas
                    if($this->isValidOrdenEstructura($request->file('excel')))
                    {
                        $data = Excel::load($request->file('excel'), 
                            function($reader){})->get();

                        if(!empty($data) && $data->count()){
                            foreach ($data as $key => $value){
                               $this->nuevoAlumno($value);
                               $this->guardarRelacion($value->apellidos,
                                explode(",",$request->Cursos));
                            }
                            return response()->json(["Estado" => "Subido"]);
                        }
                        else
                            return response()->json(["Estado" => "ErrorDatos"]);
                    }else{
                        return "ERROR EN ESTRUCTURA";
                    }
                }
            }
        }
        return response()->json(["Estado" => "Error"]);
    }

    public function nuevoAlumno($alumno)
    {
        //esta bajo observacion
        $stu = Student::where('apellidos',$alumno->apellidos)->first();
        if(is_null($stu)){
            $student = new Student;
            $student->nombre = $alumno->nombres;
            $student->apellidos = $alumno->apellidos;
            $student->direccion = $alumno->direccion;
            $student->email = $alumno->email;
            $student->save();
        }
        
    }

    public function guardarRelacion($apellidos,$cursos)
    {
        $stu = Student::where('apellidos',$apellidos)->first();
        if(!is_null($stu)){
            foreach ($cursos as $curso){
                if($curso != null)
                    if(!$stu->courses()->where('course_id', $curso)->exists())
                        $stu->courses()->attach($curso);//asigna cursos
            }
        }
    }
}
