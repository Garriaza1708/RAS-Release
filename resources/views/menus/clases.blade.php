@extends('layout')

@section('css')

<style>
/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body-attendance{
    height: 510px;
    overflow-y: auto;
}
</style>

@stop

@section('title')
	Clases
@stop

@section('description')
	Gestión de Clases
@stop

@section('container')
	<div class="row">
		<div class="col-md-9">
			<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-calendar"></i>
              <h3 class="box-title">Periódos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if (count($unidades))
               <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="myTab">
                  @foreach($unidades as $key=>$unidad)
                    <li @if($key===0) class="active" @endif><a href="#tab_{{ $key+1 }}" data-toggle="tab">{{ $unidad->nombre }}</a></li>
                  @endforeach
                </ul>
                <div class="tab-content" id="ContenedorClases">
                  @foreach ($unidades as $key=>$unidad)
                    <div @if($key===0) class="tab-pane active" @else class="tab-pane" @endif id="tab_{{ $key+1 }}">
                        @for ($i = 1; $i <= $unidad->duracion ; $i++)
                          <div class="box-group" 
                          id="accordion{{ $unidad->id ."". $i }}" 
                          style="margin-bottom: 16px;">
                            <div class="panel box box-success">
                              <div class="box-header with-border">
                                <h4 class="box-title">
                                  <a data-toggle="collapse" data-parent="#accordion{{ $unidad->id ."". $i }}" href="#collapse{{ $unidad->id ."". $i }}">
                                    <i class="fa fa-dot-circle-o"></i> <span> Semana {{ $i }}</span>
                                  </a>
                                </h4>
                              </div>
                              <div id="collapse{{ $unidad->id ."". $i }}" class="panel-collapse collapse">
                                <div class="box-body">
                                  <button type="button" data-toggle="modal" 
                                  data-target="#modalAddLesson" 
                                  data-id="{{ $unidad->id }}" 
                                  data-semana="Semana {{ $i }}" class="btn btn-primary btn-add-lesson" 
                                  aria-label="Left Align">
                                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                  </button><hr>
                                  <div id="container{{ $unidad->id }}Semana{{ $i }}">
                                    {{-- Repetitiva--}}
                                    @foreach ($lessons as $lesson)
                                      @if($unidad->id == $lesson->period_range_id && 
                                      "Semana ".$i == $lesson->semana)
                                      <div class="panel panel-default" id="Panel{{ $lesson->id }}">
                                        <div class="panel-heading">
                                          <span style="font-size:17px !important;color:#8B0000">
                                            {{ $lesson->nombre }} - 
                                            {{ date('d-m-Y', strtotime($lesson->fecha)) }}
                                          </span>
                                        </div>
                                        <div class="panel-body" id="ContenedorBotones">
                                          <a class="btn btn-app btn-attendance" 
                                          data-toggle="modal" data-target="#modalAttendance"
                                          onclick="takeAttendance({{ $lesson->id }})"><i class="fa fa-users"></i> Asistencia</a>
                                          <a class="btn btn-app btn-edit-lesson" 
                                          data-toggle="modal" data-target="#modalUpdateLesson"  onclick="updateLesson({{ $lesson->id }})"><i class="fa fa-edit"></i> 
                                          Editar</a>
                                          <a class="btn btn-app btn-delete-lesson" onclick="deleteLesson({{ $lesson->id }})"><i class="fa fa-trash"></i> Eliminar</a>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    
                                    {{-- Fin Repetitiva--}}
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endfor
                      
                    </div>
                  @endforeach 
                </div>
              </div>
            @else
               <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
                    No existe semanas registradas para el periodo seleccionado
                </div>
            @endif
             
            </div>
            <!-- /.box-body -->
          </div>
		</div>
		<div class="col-md-3 offset-md-3">
			<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-book"></i>

              <h3 class="box-title">Cursos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="ListaCursos">
            @if(count($cursos))
              @foreach($cursos as $curso)
              @if($loop->first)
                <script>
                if (localStorage.getItem('idSeleccionado') == null) 
                    localStorage.setItem('idSeleccionado',{{ $curso->id }});
                </script>
              @endif
              
                <span class="label label-success seleccionado" 
                style="font-size: 15px;text-align:center;display:block;margin-bottom:6px;height: 25px;" data-id="{{ $curso->id }}">
                  <a href="#" class="a-set-course" style="color: white !important;
                  cursor: pointer;" data-id="{{ $curso->id }}">
                      {{ $curso->nombre }}
                  </a>
                </span>
              @endforeach
            @else
              <span class="label label-warning" style="font-size: 13px;">No existen cursos para el periódo seleccionado</span>
            @endif
             
            </div>
            <!-- /.box-body -->
          </div>
		</div>
	</div>

<div id="modalAddLesson" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      @include('partials.add-lesson')
    </div>
  </div>
</div>

<div id="modalUpdateLesson" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      @include('partials.update-lesson')
    </div>
  </div>
</div>

<div id="modalAttendance" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      @include('partials.attendance',["students" => $students])
    </div>
  </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('js/gestion/lesson.js') }}"></script>
<script src="{{ asset('js/gestion/attendance.js') }}"></script>
<script>
  $('#FormClase').bootstrapValidator({
                message: 'Este valor no es valido',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    Nombre: {
                        message: 'El Nombre no es valido',
                        validators: {
                            notEmpty: {
                                message: 'No ha ingresado el Nombre'
                            },
                            stringLength: {
                                max: 191,
                                message: 'El Nombre no debe ser mayor de 100 caracteres'
                            }
                        }
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertarClase();
                $("#FormClase").data('bootstrapValidator').resetForm();
    });
  saveAttendance();
</script>
@stop
