<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Period;
use App\Student;
use App\Course;
use App\Calculos;
use App\Period_Range;
use App\Grade;
use App\Grade_Structure;
use Yajra\Datatables\Datatables;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {	
    	$this->middleware('auth');
        $period = Period::select('id')->orderBy('año','DESC')->take(1)->first();
            if(!is_null($period))
                if(!(\Session::has('idPeriodo'))) 
                    \Session::put('idPeriodo',$period->id);
        
    }

    public function index()
    {
    	$alumnos = $this->alumnosRegistrados();
    	$cursos = $this->cursosRegistrados();
    	$listaCursos = $this->getCursosxPeriodo();
        $listaRangos = $this->getPeriodosRangos();
    	return view('menus.dashboard',compact('alumnos','cursos',
            'listaCursos','listaRangos'));
    }

    public function getAlumnosCurso(Request $request)
    {
        $alumnos = Student::join('courses_students as cs','students.id','=',
            'cs.student_id')
                ->select('students.id as id',DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"),'students.email AS email')
                ->where('course_id',$request->id)
                ->orderBy('apellidos','ASC')
                ->get();

        return $alumnos;
    }

    public function getAlumnosxCurso($id)
    {
        return Datatables::of(Student::join('courses_students as cs','students.id',
            '=','cs.student_id')
            ->select('students.id as id',DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"),'students.email AS email')
            ->where('course_id',$id)
            ->orderBy('apellidos','ASC')
            ->get())->make(true);
    }

    public function getPeriodosRangos()
    {
        $rangos = Period_Range::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->get();

        return $rangos;
    }

	//Alumnos recientemente registrados
    public function getCantidadAlumnos()
    {
    	$can = Student::count();
    	return $can;
    }

    public function getCantidadCursos()
    {
    	$can = Course::count();
    	return $can;
    }

    public function alumnosRegistrados()
    {
    	$alumnos = Student::select('id','nombre','apellidos','email')
    	           ->orderBy('created_at','DESC')
    	           ->take(5)
    	           ->get();

    	return $alumnos;
    }

    //Cursos recientemente registrados
    public function cursosRegistrados()
    {
    	$cursos = Course::select('id','nombre','descripcion','created_at')
    			->orderBy('created_at','DESC')
    			->take(5)
    			->get();

    	return $cursos;
    }

    //Cursos recientemente registrados
    public function getCursosxPeriodo()
    {
    	$cursos = Course::select('id','nombre')
    	        ->where('period_id',\Session::get('idPeriodo'))
    			->orderBy('nombre','ASC')
    			->get();

    	return $cursos;
    }

    public function getTableroTres()
    {
        $cal = new Calculos;
        $resultados = array();
        $array = $cal->getMayorCantidadAlumnos();

        if(!is_null($array))
        {
            foreach ($array as $key => $value) {
                $resultados['cantidad'][$key] = $value['cantidad'];
            }

            array_multisort($resultados['cantidad'],SORT_DESC,$array);
        
            $array = array_slice($array,0,6);
        }
        
        return $array;
    }

    public function getTableroCuatro(Request $request)
    {
        $cal = new Calculos;
        $resultados = array();
        $array = $cal->getRankingAF($request->id,$request->tipo);

        if(!is_null($array))
        {
            foreach ($array as $key => $value) {
                $resultados['cantidad'][$key] = $value['cantidad'];
            }
    
            array_multisort($resultados['cantidad'],SORT_DESC,$array);
            
            $array = array_slice($array,0,10);
        }
        
        return $array;
    }

    public function getTableroCinco(Request $request)
    {
        $cal = new Calculos;
        $resultados = array();
        $array = $cal->getRankingBC($request->rango,$request->id);

        if(!is_null($array))
        {
            foreach ($array as $key => $value) {
                $resultados['promedio'][$key] = $value['promedio'];
            }
    
            array_multisort($resultados['promedio'],SORT_ASC,$array);
            
            $array = array_slice($array,0,10);
        }

        return $array;
    }

    public function getTableroDos(Request $request)
    {
        $cal = new Calculos;
        $resultados = array();
        $array = $cal->getAlumnosDestacados($request->id);

        if(!is_null($array))
        {
            foreach ($array as $key => $value) {
                $resultados['promedio'][$key] = $value['promedio'];
            }

            array_multisort($resultados['promedio'],SORT_DESC,$array);
        
            $array = array_slice($array,0,6);
        }
        

        $periodos = Period_Range::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')->get();

        $tabla = '<table class="table no-margin" id="TableroDos">
                  <thead>
                    <th>Alumno</th>';

            foreach ($periodos as $item) {
                $tabla.= "<th>".$item->nombre."</th>";
            }

         $tabla.= "<th>PROM FINAL</th>
                    </thead>
                        <tbody>";

        if(!is_null($array))
        {
            foreach ($array as $item0)
            {                  
                $alumno = Student::select('id',
                    DB::raw("CONCAT(apellidos,' ',nombre) AS alumno"))
                    ->orderBy('apellidos','ASC')->where('id',$item0['idAlumno'])
                    ->first();
    
                $tabla.= "<tr>
                        <td>".$alumno->alumno."</td>";
    
                foreach ($periodos as $item) {
                    $promedio = $cal->getPromedioPeriodoRango($item->id,$request->id,$alumno->id);
                    $tabla.= "<td style='color:".$cal->getColorNota($promedio)."'>".
                          $promedio."</td>";
                }
    
                $tabla.= "<td style='color:".$cal->getColorNota($item0['promedio'])."'>".round($item0['promedio'])."</td>";
    
                $tabla.= "</tr>";
            }
        }
         
        $tabla .= '</tbody>
                </table>';

        return $tabla;
    }

    public function configurarGrafico(Request $request)
    {
        $cal = new Calculos;
        $notas = null;
        $obj = "";
        $series = Period_Range::select('id','nombre')
                    ->where('period_id',\Session::get('idPeriodo'))
                    ->get();

        $subcomponentes = Grade::join('grades_structures as gs','grades.id','=',
            'gs.grade_id')
            ->select('gs.id as id','gs.nombre as nombre')
            ->where('grades.course_id',$request->idCurso)
            ->pluck('id','nombre');

        foreach ($series as $item) {
            $valores = array();
            foreach ($subcomponentes as $value) {
                $nota = $cal->getNotaSubComponente($item->id,$value,
                        $request->idAlumno);
                $nota = $nota == null ? 0 : $nota->grade;
                $valores[] = $nota;
            }
            $notas[] =  array(
                "name" => $item->nombre,
                "data" => $valores
            );
        }
        return response()->json([ 
                                "categorias" => $subcomponentes,
                                "notas" => $notas
                            ]);
    }

    
}
