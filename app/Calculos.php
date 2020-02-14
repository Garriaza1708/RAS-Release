<?php

namespace App;
use DB;
class Calculos
{
    public function getNotaSubComponente($idPeriodo,$idGradeStructure,$idStudent)
    {
        $query = Grade_Value::select('grade')
                ->where('grade_structures_id',$idGradeStructure)
                ->where('student_id',$idStudent)
                ->where('period_ranges_id',$idPeriodo)
                ->first();

        return $query;
    }

    public function getPromedioPeriodoRango($idRango,$idCurso,$idAlumno)
    {
        $periodo = Period_Range::find($idRango);
        $promedioPeriodo = 0;
        $promedioComponente = 0;
        if(!is_null($periodo))
        {
            $componentes = Grade::select('id')->where('course_id',$idCurso)->get();
            $acumPromedios = 0;
            $canPromedios = 0;
            foreach ($componentes as $index1 => $componente) {
                $subComponentes = Grade_Structure::select('id')->where('grade_id',$componente->id)->get();
                $acumNotas = 0;
                $canNotas = 0;
                foreach($subComponentes as $index2 => $subcomponente){
                    $nota = $this->getNotaSubComponente($periodo->id,$subcomponente->id,
                        $idAlumno);
                    
                    $nota = $nota == null ? 0 : $nota->grade;
                                
                    $acumNotas += $nota;
                    $canNotas++;
                }
                if($canNotas != 0)
                    $promedioComponente = $acumNotas / $canNotas;
    
                $acumPromedios += $promedioComponente;
                $canPromedios++;
            }
            if($canPromedios != 0)
                $promedioPeriodo = $acumPromedios / $canPromedios;
        }
        return round($promedioPeriodo);
    }

    public function obtenerEstado($nota)
    {
    	return $nota >= 10.5 ? "Aprobado":"Desaprobado";
    }

    public function getPromedioFinal($idCurso)
    {
        $resultados = null;
        $promedioComponente = 0;
        $promedioPeriodo = 0;
        $promedioFinal = 0;

        $alumnos = Student::join('courses_students as cs','students.id','=',
        'cs.student_id')->select('id',
        DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
        ->orderBy('apellidos','ASC')->where('cs.course_id',$idCurso)
        ->get();

        $periodos = Period_Range::where('period_id',\Session::get('idPeriodo'))->get();

        $componentes = Grade::select('id')->where('course_id',$idCurso)->get();

        $cantidadPeriodos = Period_Range::where('period_id',\Session::get('idPeriodo'))
                ->count();
        
        foreach ($alumnos as $index3 => $alumno) {
            $acumPromedioPeriodo = 0;
            foreach ($periodos as $index => $periodo) {
                $acumPromedios = 0;
                $canPromedios = 0;
                foreach ($componentes as $index1 => $componente) {
                    $subComponentes = Grade_Structure::select('id')->where('grade_id',$componente->id)->get();
                    $acumNotas = 0;
                    $canNotas = 0;
                    foreach($subComponentes as $index2 => $subcomponente){
                        $nota = $this->getNotaSubComponente($periodo->id,$subcomponente->id,
                        $alumno->id);
                    
                        $nota = $nota == null ? 0 : $nota->grade;
                                
                        $acumNotas += $nota;
                        $canNotas++;
                        
                    }
                    if($canNotas != 0)
                        $promedioComponente = $acumNotas / $canNotas;
    
                    $acumPromedios += $promedioComponente;
                    $canPromedios++;
                }
                if($canPromedios != 0)
                        $promedioPeriodo = $acumPromedios / $canPromedios;
    
                $acumPromedioPeriodo += $promedioPeriodo;
            }
            if($cantidadPeriodos != 0)
                $promedioFinal = $acumPromedioPeriodo / $cantidadPeriodos;

            $resultados[] = (object)array(
                "alumno" => $alumno->alumno,
                "final" => round($promedioFinal),
                "estado" => $this->obtenerEstado($promedioFinal)
            );
        }
        return $resultados;
    }

    public function getCantidadAsistencias($idStudent,$idCourse)
    {
        $asistencias = Attendance::join('lessons as le','le.id','=',
            'attendances.lesson_id')
            ->where('student_id',$idStudent)
            ->where('course_id',$idCourse)
            ->where('estado','Asistencia')
            ->count();

        return $asistencias;
    }

    public function getCantidadTardanzas($idStudent,$idCourse)
    {
        $tardanzas = Attendance::join('lessons as le','le.id','=',
            'attendances.lesson_id')
            ->where('student_id',$idStudent)
            ->where('course_id',$idCourse)
            ->where('estado','Tardanza')
            ->count();

        return $tardanzas;
    }

    public function getCantidadFaltas($idStudent,$idCourse)
    {
        $faltas = Attendance::join('lessons as le','le.id','=',
            'attendances.lesson_id')
            ->where('student_id',$idStudent)
            ->where('course_id',$idCourse)
            ->where('estado','Falta')
            ->count();

        return $faltas;
    }

    public function asistenciasCursoAlumno($idStudent,$idCurso)
    {
        $porcentaje = 0;
        $can = Attendance::join('lessons as l','l.id','=','attendances.lesson_id')
                ->where('student_id',$idStudent)
                ->where('l.course_id',$idCurso)
                ->count();

        $faltas = Attendance::join('lessons as l','l.id','=','attendances.lesson_id')
                ->where('student_id',$idStudent)
                ->where('l.course_id',$idCurso)
                ->where('estado','Falta')
                ->count();

        $asistencias = $can - $faltas;
        if($can == 0) $porcentaje = 100;        
        else {
            if($asistencias == 0) $porcentaje = -1;
                
            else $porcentaje = ($asistencias / $can) * 100;
        }
        return $porcentaje;
    }

    public function getColorBarra($porcentaje)
    {
        if($porcentaje >= 60)
            return "progress-bar-success";
        else if($porcentaje <= 30)
            return "progress-bar-danger";
        else
            return "progress-bar-warning";
    }

    public function getColorNota($nota)
    {
        return $nota >= 10.5 ? "blue" : "red";
    }

    //alumnos con mayor cantidad de faltas o asistencias x curso.
    public function getRankingAF($curso,$tipo)
    {
        $resultados = null;
        $alumnos = Student::join('courses_students as cs','students.id','=',
            'cs.student_id')
                ->select('students.id as id',DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
                ->where('course_id',$curso)
                ->orderBy('apellidos','DESC')
                ->get();

        foreach($alumnos as $value){

        $color = $this->asistenciasCursoAlumno($value->id,$curso);

        $porcentaje = $color == -1 ? 100 : $color;

            if($tipo == 'Tardanza')
                $opc = $this->getCantidadTardanzas($value->id,$curso);
            elseif($tipo == 'Falta')
                $opc = $this->getCantidadFaltas($value->id,$curso);

            $resultados[] =  array(
                "id" => $value->id,
                "alumno" => $value->alumno,
                "cantidad" => $opc,
                "porcentaje" => round($porcentaje,2),
                "color" => $this->getColorBarra($color)
            );
        }

        return $resultados;        
    }

    //alumnos con notas mas bajas x bimestre x curso.
    public function getRankingBC($rango,$curso)
    {
        $resultados = null;
       $alumnos = Student::join('courses_students as cs','students.id','=',
            'cs.student_id')
                ->select('students.id as id',DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
                ->where('course_id',$curso)
                ->orderBy('apellidos','DESC')
                ->get();

        foreach($alumnos as $value){
            $promedio = $this->getPromedioPeriodoRango($rango,$curso,$value->id);

            $resultados[] =  array(
                "id" => $value->id,
                "alumno" => $value->alumno,
                "promedio" => $promedio
            );
        }
        return $resultados; 
    }

    //Cursos con Mayor Cantidad de Alumnos. TOTAL=6
    public function getMayorCantidadAlumnos()
    {
        $resultados = null;
        $cursos = Course::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->get();

        foreach ($cursos as $value) {
            $resultados[] =  array(
                "id" => $value->id,
                "curso" => $value->nombre,
                "cantidad" => $value->students->count()
            );
        }
        return $resultados;
    }

    //Alumnos mas destacados. TOTAL=5
    public function getAlumnosDestacados($curso)
    {
        $promedios = null;
        $prom = 0;
        $periodos = Period_Range::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')->get();

        $alumnos = Student::join('courses_students as cs','students.id','=',
                'cs.student_id')
                ->select('id')
                ->orderBy('apellidos','ASC')->where('cs.course_id',$curso)
                ->get();

        foreach ($alumnos as $item0){            
            $can = 0;
            $acum = 0;
            foreach ($periodos as $item) {
                $promedio = $this->getPromedioPeriodoRango($item->id,$curso,$item0->id);
                
                $acum += $promedio;

                $can++;
            }
            
            if($can != 0) $prom = $acum / $can;
            
            $promedios[] = array(
                "idAlumno" => $item0->id,
                "promedio" => $prom
            );    
        } 
        return $promedios;
    }
}


?>
