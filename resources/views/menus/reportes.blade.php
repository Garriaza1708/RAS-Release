@extends('layout')

@section('title')
	Reportes
@stop

@section('description')
	Lista de Reportes
@stop

@section('container')
<div class="row">
	<div class="col-md-12">
		<!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" data-toggle="tab">Listado</a></li>
              <!--<li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>-->

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Reportes</h3>
                </div>
               <div class="box-body">
                  <table id="tblListaReportes" class="table table-bordered table-hover" width="100%">
                   <thead>
                     <tr> 
                       <th>#</th>
                       <th>Reporte</th>
                       <th>Descripción</th>
                       <th>Detalle</th>
                     </tr>
                   </thead>
                   <tbody>
                    <tr>
                      <td>1</td>
                      <td>Promedios Generales</td>
                      <td>Reporte en el que se listan todas los promedios obtenidos de un curso por periódo</td>
                      <td>
                        <a href="{{ route('app.report.detail.page','rpprom') }}">Ver Detalle</a>
                      </td>
                    </tr>
                   
                    <tr>
                      <td>2</td>
                      <td>Detalle Notas</td>
                      <td>Reporte detallado en el que se listan las notas obtenidas de un alumno por curso</td>
                      <td>
                      <a href="{{ route('app.report.detail.page','rpdet') }}">Ver Detalle</a>
                      </td>
                    </tr>

                    <tr>
                      <td>3</td>
                      <td>Alumnos Aprobados y Desaprobados</td>
                      <td>Reporte en el que se listan los alumnos aprobados y desaprobados</td>
                      <td>
                        <a href="{{ route('app.report.detail.page','rpalu') }}">Ver Detalle</a>
                      </td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>Asistencias por Alumno</td>
                      <td>Reporte en el que se listan las asistencias de un alumno y su estado</td>
                      <td>
                        <a href="{{ route('app.report.detail.page','rpasi') }}">Ver Detalle</a>
                      </td>
                    </tr>
                   </tbody>
                  </table>
               </div>
              </div>
              </div>
              <!-- /.tab-pane -->
              <!--<div class="tab-pane" id="tab_3"></div>-->
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
	</div>
</div>
@stop

@section('scripts')
  <script src="{{ asset('js/gestion/reportes.js') }}"></script>
@stop


