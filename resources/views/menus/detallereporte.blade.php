@extends('layout')

@section('css')
	<style>
	.progress{
		background-color: #E0E0E0;
		font-weight: bold;
	}
	/* Important part */
	.modal-dialog{
	    overflow-y: initial !important
	}
	.modal-body-details{
	    height: 360px;
	    overflow-y: auto;
	}
	</style>
@stop

@section('title')
	Detalle del Reporte
@stop

@section('description')
	@if($type == 'rpdet')	
		Detalle Notas
	@elseif($type == 'rpalu')
		Alumnos Aprobados y Desaprobados
	@elseif($type == 'rpasi')
		Asistencias por Alumno
	@elseif($type == 'rpprom')
		Promedios Generales
	@endif
@stop

@section('container')
	<div class="row">
	<script>
		//localStorage.setItem('idSeleccionado',);
		localStorage.setItem('tipoReporte','{{ $type }}');
	</script>
		<div class="col-md-9">
			<div class="box box-solid">
				<div id="DetalleReporte" class="table-responsive"></div>
			</div>
		</div>
		<div class="col-md-3 offset-md-3">
			<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-book"></i>
              <h3 class="box-title">
              	Cursos
              </h3>
            </div>
            <!-- /.box-header -->
            <!--Cursos-->
            @if($type == 'rpdet' || $type == 'rpalu' || $type == 'rpasi' || 
            	$type == 'rpprom')

            <div class="box-body" id="ListaCursos">
	            @if(count($cursos))
	              @foreach($cursos as $curso)
	                <span class="label label-success seleccionadoc" 
	                style="font-size: 15px;text-align:center;display:block;margin-bottom:6px;height: 25px;" data-id="{{ $curso->id }}">
	                  <a href="#" class="a-set-course" style="color: white !important;
	                  cursor: pointer;" data-id="{{ $curso->id }}">
	                      {{ $curso->nombre }}
	                  </a>
	                </span>
	              @endforeach
	            @else
	              <span class="label label-warning" style="font-size: 13px;">No existen cursos para el periódo actual</span>
	            @endif
            </div>
            <!--Fin Cursos-->
            <div class="box-body" id="Exportar">
            	Exportar como:   
            	<input type="hidden" name="idCurso" id="idCurso">
            	<button type="button" id="btnPDF" class="btn btn-default"> 
            		<i class="fa fa-file-pdf-o" style="color: red"></i> PDF
            	</button>
            	<button type="button" id="btnExcel" class="btn btn-default">
            		<i class="fa fa-fw fa-file-excel-o" style="color:green"></i> Excel
            	</button>
            </div>
            @else

			<div class="box-body">
             <span class="label label-warning" style="font-size: 13px;">No existen filtros para el reporte solicitado</span>
            </div>

            @endif
            <!-- /.box-body -->
          </div>
		</div>

	</div>

<div id="modalDetailLesson" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      @include('partials.detalleasistencias')
    </div>
  </div>
</div>

@stop

@section('scripts')
	<script src="{{ asset('js/gestion/reportes.js') }}"></script>
	<script>
		exportarExcel();
		exportarPDF();
	</script>
@stop
