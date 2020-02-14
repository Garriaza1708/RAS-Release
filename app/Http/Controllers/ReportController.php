<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calculos;
use App\Grade;
use App\Course;
use App\Period_Range;
use App\Student;
use App\Grade_Structure;
use App\Attendance;
use DB;
class ReportController extends Controller
{
    public function showPage()
    {
    	return view('menus.reportes');
    }

    public function showDetailPage($type)
    {
        $cursos = Course::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->get();

        return view('menus.detallereporte',compact('type','cursos'));
    }

    public function promedioGenerales($idCurso)
    {
        $cal = new Calculos;
        $tabla = "";
        $periodos = Period_Range::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')->get();

        $alumnos = Student::join('courses_students as cs','students.id','=',
                'cs.student_id')
                ->select('id',
                DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
                ->orderBy('apellidos','ASC')->where('cs.course_id',$idCurso)
                ->get();

        $tabla = "<table id='TablaReporte' width='100%' class='table table-hover table-striped table-condensed table-bordered tabla-reporte'>
                <thead><tr>
                    <th>Alumno</th>";

        foreach ($periodos as $id => $item) {
            $tabla.= "<th>".$item->nombre."</th>";
        }
        $tabla.= "<th>PROM FINAL</th></tr>
                </thead>
                    <tbody>";

        foreach ($alumnos as $id => $item0) {

            $tabla.= "<tr style='font-size:15px;'>
                        <td>".$item0->alumno."</td>";
            $can = 0;
            $acum = 0;
            foreach ($periodos as $id => $item) {
                $promedio = $cal->getPromedioPeriodoRango($item->id,$idCurso,$item0->id);

                $tabla.= "<td style='color:".$cal->getColorNota($promedio)."'>".
                      $promedio."</td>";

                $acum += $promedio;

                $can++;
            }
                $prom = $acum / $can;

                $tabla.= "<td style='color:".$cal->getColorNota($prom)."'>".
                round($prom)."</td>";

            $tabla.= "</tr>";
        }

        $tabla.="</tbody>
                    </table>";
        return $tabla;
    }

    public function detalleNotas($id)
    {
        $cal = new Calculos;
        $tabla = "";
        $promedio = 0;
        $promGeneral = 0;

        $periodos = Period_Range::select('id','nombre')
                ->where('period_id',\Session::get('idPeriodo'))
                ->orderBy('nombre','ASC')->get();

        $alumnos = Student::join('courses_students as cs','students.id','=',
                'cs.student_id')
                ->select('id',
                DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
                ->orderBy('apellidos','ASC')->where('cs.course_id',$id)
                ->get();

        $componentes = Grade::select('id','nombre')
                    ->where('course_id',$id)
                    ->get();
        $tabla.= '<table class="tabla-reporte" width="100%">';
        foreach ($periodos as $index => $item) {
            $tabla .= ' 
                <tr><td>
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne'.$item->id.'">
                        '.$item->nombre.'
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne'.$item->id.'" class="panel-collapse collapse">
                    <div class="box-body">';

            $tabla .= "<table id='TablaReporte".$item->id."' width='100%' class='table table-hover table-striped table-condensed table-bordered'>
                <thead>
                    <tr>
                        <th rowspan='2' style='vertical-align: inherit;'>Alumno</th>";     
            foreach ($componentes as $index1 => $item1) {
                $can = Grade_Structure::where('grade_id',$item1->id)->count();
                $tabla.= "<th colspan='".$can."'>".$item1->nombre."</th>
                <th rowspan='2' style='vertical-align: inherit;'>PROM</th>";
            }
            $tabla.= "<th rowspan='2' style='vertical-align: inherit;width:10%'>PROM FINAL</th>
                </tr>";

            $tabla .= "<tr>";
            foreach ($componentes as $index2 => $item2) {
                $est = Grade_Structure::select('id','nombre')
                    ->where('grade_id',$item2->id)->get();

                foreach ($est as $index3 => $item3) {
                    $tabla.= "<th>".$item3->nombre."</th>";
                }  
            }
            $tabla .= "</tr></thead>";

            foreach ($alumnos as $index0 => $item0){

                $tabla.= "<tr style='font-size:15px;'>
                        <td>".$item0->alumno."</td>";

                $acumPromedios = 0;
                $canPromedios = 0;
                foreach ($componentes as $index4 => $item4) {

                $est = Grade_Structure::select('id','nombre')
                    ->where('grade_id',$item4->id)->get();

                    $acumNotas = 0;
                    $canNotas = 0;

                    foreach ($est as $index5 => $item5) {
                    
                    $nota = $cal->getNotaSubComponente($item->id,$item5->id,
                        $item0->id);
                    
                    $nota = $nota == null ? 0 : $nota->grade;
                    
                    $tabla.= "<td style='color:".$cal->getColorNota($nota)."'>".$nota."</td>";
                        
                    $acumNotas += $nota;
                    $canNotas++;
                    }
                    
                    if($canNotas != 0)
                        $promedio = $acumNotas / $canNotas;

                    $tabla.= "<td style='color:".$cal->getColorNota($promedio)."'>".round($promedio)."</td>";

                    $acumPromedios += $promedio;

                    $canPromedios++;
                }
                if($canPromedios != 0)
                    $promGeneral = $acumPromedios / $canPromedios;

                $tabla.= "<td style='color:".$cal->getColorNota($promGeneral)."'>".round($promGeneral)."</td>";

                $tabla.= "</tr>";
            }

            $tabla.= "</tbody>
                    </table>";      

            $tabla .= '</div>
                  </div>
                </div></td></tr>';
        }
       $tabla.="</table>";

        return $tabla;
    }

    public function estadosNotasAlumnos($id)
    {
        $cal = new Calculos;
        $array = $cal->getPromedioFinal($id);
        $tabla = ' 
            <table class="tabla-reporte" width="100%">
                <tr>
                <td>
            <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-thumbs-o-up"></i>
              <h3 class="box-title">Aprobados</h3>
            </div>
            <div class="box-body">';
        
        $tabla .= "<table id='Aprobados' width='100%' class='table table-hover table-striped table-condensed table-bordered'>
                <thead>
                        <tr>
                            <th style='width: 70%'>Alumno</th>
                            <th style='width: 30%'>Promedio Final</th>
                        </tr>
                </thead>
                <tbody>";
        
            foreach ($array as $index => $item){
                if($item->estado == "Aprobado"){
                    $tabla.= "<tr style='font-size:15px;'>
                        <td >".$item->alumno."</td>
                        <td style='color:".$cal->getColorNota($item->final)."'>".
                        $item->final."</td>";
                }
            }

        $tabla .= "</tbody></table>";

        $tabla .=   '</div>
          </div>';


        $tabla .= '</tr><tr><td><div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-thumbs-o-down"></i>
              <h3 class="box-title">Desaprobados</h3>
            </div>
            <div class="box-body">';
        
        $tabla .= "
                <table id='Desaprobados' width='100%' class='table table-hover table-striped table-condensed table-bordered'>
                <thead>
                        <tr>
                            <th style='width: 70%'>Alumno</th>
                            <th style='width: 30%'>Promedio Final</th>
                        </tr>
                </thead>
                <tbody>";
            foreach ($array as $index => $item){
                if($item->estado == "Desaprobado"){
                    $tabla.= "<tr style='font-size:15px;'>
                        <td>".$item->alumno."</td>
                        <td style='color:".$cal->getColorNota($item->final)."'>".
                        $item->final."</td>";
                }
            }

        $tabla .= "</tbody></table>";

        $tabla .=   '</div>
          </div></td></tr></table>';
       

        return $tabla;
    }

    public function asistenciasAlumnos($id)
    {
        $cal = new Calculos;

        $alumnos = Student::join('courses_students as cs','students.id','=',
                'cs.student_id')
                ->select('id',
                DB::raw("CONCAT(students.apellidos,' ',students.nombre) AS alumno"))
                ->orderBy('apellidos','ASC')->where('cs.course_id',$id)
                ->get();
        
        $tabla = "<table id='Reporte' width='100%' class='table table-hover table-striped table-condensed table-bordered tabla-reporte'>
                <thead>
                    <tr>
                        <th rowspan='2' style='vertical-align: inherit;'>Alumno</th>
                        <th colspan='3' style='text-align: center;'>Cantidad</th>
                        <th rowspan='2' style='vertical-align: inherit;'>Estado</th>
                    </tr>
                    <tr>
                        <th>Asistencias</th>
                        <th>Tardanzas</th>
                        <th>Faltas</th>
                    </tr>
                </thead>
                <tbody>";

            foreach ($alumnos as $index => $item) {

                $color = $cal->asistenciasCursoAlumno($item->id,$id);

                $porcentaje = $color == -1 ? 100 : $color;

                $tabla .= "<tr style='font-size:15px;'>
                    <td>".$item->alumno."</td>
                    <td><a href='#' data-toggle='modal' 
                        class='get-data'
                        data-target='#modalDetailLesson'
                        data-id-student=".$item->id."
                        data-id-course=".$id."
                        data-tipo='Asistencia'>
                        ".$cal->getCantidadAsistencias($item->id,$id)."
                    </a>
                    </td>
                    <td><a href='#' data-toggle='modal' 
                        class='get-data'
                        data-target='#modalDetailLesson'
                        data-id-student=".$item->id."
                        data-id-course=".$id."
                        data-tipo='Tardanza'>
                        ".$cal->getCantidadTardanzas($item->id,$id)."
                    </a>
                    </td>
                    <td><a href='#' data-toggle='modal' 
                        class='get-data'
                        data-target='#modalDetailLesson'
                        data-id-student=".$item->id."
                        data-id-course=".$id."
                        data-tipo='Falta'>
                        ".$cal->getCantidadFaltas($item->id,$id)."
                    </a></td>
                    <td>
                        <div class='progress'>
                          <div class='progress-bar ".$cal->getColorBarra($color)." progress-bar-striped active' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100' style='width:".round($porcentaje,2)."%;color:black;'>
                            ".round($porcentaje,2)."%
                          </div>
                        </div>
                    </td>
                </tr>";
            }

        $tabla .= "</tbody></table>";

        return $tabla;
    }

    public function getDetalleAsistencias(Request $request)
    {
        $query = Attendance::join('lessons as l','l.id','=','attendances.lesson_id')
                ->join('periods_ranges as pr','pr.id','=','l.period_range_id')
                ->select('l.id as idLesson','l.nombre as lesson','fecha','semana','pr.nombre as periodo','pr.id as idPeriodo')
                ->where('student_id',$request->Alumno)
                ->where('l.course_id',$request->Curso)
                ->where('estado',$request->Tipo)
                ->get();

        return response()->json([
                "asistencias" => $query
            ]);
    }

    public function generarPDF($id,$tipo)
    {
        define('_MPDF_TTFONTDATAPATH',sys_get_temp_dir()."/");
        
        if($tipo == "rpdet") {
            $html = $this->detalleNotas($id);
            $nombre = "Detalles Notas";
        }else if($tipo == "rpalu"){
            $html = $this->estadosNotasAlumnos($id);
            $nombre = "Alumnos Aprobados y Desaprobados";  
        } 
        else if($tipo == "rpasi") 
        {
            $html = $this->asistenciasAlumnos($id);
            $nombre = "Asistencias por alumno";
        }
        else {
            $html = $this->promedioGenerales($id);
            $nombre = "Promedios Generales";
        }


        $mpdf = new \mPDF();
        $css  = '';
        //$css .= file_get_contents('css/bootstrap.min.css');
        $css .= file_get_contents('css/AdminLTE.min.css');

        $mpdf->setFooter('{PAGENO}');
        $mpdf->writeHTML($css,1);
        $mpdf->WriteHTML($html,2);
        $mpdf->output($nombre.'.pdf','D');
    }
}
