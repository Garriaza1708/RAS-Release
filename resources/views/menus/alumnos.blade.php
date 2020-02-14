@extends('layout')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
  <style>
    .dropzone {
      border: 2px dashed #0087F7;
      border-radius: 5px;
      background: white;
    }
    .dropzone .dz-message {
      font-weight: 400;
      font-size: 25px;
    }

    .dropzone .dz-message {
        text-align: center;
        margin: 2em 0;
    }
  </style>
@stop

@section('title')
	Alumnos
@stop

@section('description')
	Gestion de Alumno
@stop

@section('container')
<div class="row">
	<div class="col-md-12">
		<!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" data-toggle="tab">Listado</a></li>
              <li><a href="#tab_2" data-toggle="tab">Nuevo</a></li>
              <li><a href="#tab_3" data-toggle="tab">Asignación de Estudiantes</a></li>

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Lista de Alumnos</h3>
                    <div class="box-tools pull-right">
                      <a href="{{ route('app.excel.download') }}" 
                      class="btn btn-primary" id="btnDescargar" 
                      style="padding:4px 10px;">Descargar Plantilla</a> 
                    </div>
                </div>
               <div class="box-body">
                  <table id="tblAlumnos" class="table table-bordered table-hover" width="100%">
                   <thead>
                     <tr> 
                       <th></th>
                       <th>Nombre</th>
                       <th>Apellidos</th>
                       <th>Dirección</th>
                       <th>Email</th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                   </tbody>
                  </table>
               </div>
              </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Alumno</h3>
                  </div>
                  <form role="form" method="POST" id="FormAlumno">
                    <input type="hidden" name="Accion" id="Accion" value="Registrar">
                     
                    <div class="box-body">
                      <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese Nombre" required>
                      </div>
                      <div class="form-group">
                        <label for="Apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="Apellidos" name="Apellidos" placeholder="Ingrese Apellidos" required>
                      </div>
                      <div class="form-group">
                        <label for="Direccion">Dirección</label>
                        <input type="text" class="form-control" id="Direccion" name="Direccion" placeholder="Ingrese Dirección" required>
                      </div>
                      <div class="form-group">
                        <label for="Email">Email</label>
                        <input type="email" class="form-control" id="Email" name="Email" placeholder="Ingrese Email" required> 
                      </div>
                    </div>
                    <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" id="Enviar" class="btn btn-primary pull-right">Enviar</button>
                  </div>
            </form>
                  </div>  
                
              </div>

              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                 <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Asignar estudiantes a cursos</h3>
                </div>
               <div class="box-body">
                <form role="form" method="POST" enctype="multipart/form-data" 
                id="FormAlumnoCursos">
                  <input type="hidden" name="Opcion" id="Opcion">
                  <div class="form-group">
                  <div style="float:right;" id="LoadingExcel"></div>
                  <label>Seleccione Tipo Carga</label><br>
                        <input type="radio" name="r1" value="Excel" class="minimal">
                        Excel
                        <input type="radio" name="r1" value="Individual" class="minimal">
                        Individual
                  </div>
                  <div class="form-group" id="AsignacionIndividual" style="display: none;">
                      <label>Seleccione Estudiante</label>
                      <select class="form-control select2" style="width: 100%;" name="Estudiante" id="Estudiante">
                        @if(count($students))
                          @foreach ($students as $student)
                              <option value="{{ $student->id }}">{{ $student->nombre }} {{ $student->apellidos }}</option>
                          @endforeach
                        @else
                          <option value="">No existen alumnos registrados</option>
                        @endif
                      </select>
                  </div>
                  <div class="form-group" id="AsignacionExcel" style="display: none;">
                    <input type="file" name="excel" id="Excel">
                  </div>

                  <div class="form-group">
                    <label>Seleccione los cursos</label>
                    <select class="form-control select2 js-example-programmatic-multi" multiple="multiple" data-placeholder="Seleccione cursos" style="width: 100%;" name="Cursos" id="Cursos">
                      @if(count($courses))
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->nombre }}</option>
                        @endforeach
                      @else
                        <option value="">No existen cursos registrados</option>
                      @endif
                    </select>
                </div>

                <!--<form enctype="multipart/form-data" id="UploadFile" method="POST">
                      
                      <input type="hidden" name="idCurso" id="idCurso">-->
                      
                <!--</form>-->

                <div class="box-footer">
                    <button type="button" id="EnviarMultiple" class="btn btn-primary pull-right">Enviar</button>
                  </div>
              </form>
               </div>
              </div>
              </div>
              <!-- /.tab-pane -->
             
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
	</div>
</div>

@stop

@section('scripts')
  <script src="{{ asset('js/gestion/student.js') }}"></script>
  <script src="{{ asset('js/dropzone.js') }}"></script>
  <script src="{{ asset('js/gestion/excel.js') }}"></script>
  <!-- iCheck 1.0.1 -->
  <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>

  <script>
    $('#FormAlumno').bootstrapValidator({
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
                                message: 'El Nombre no debe ser mayor de 191 caracteres'
                            }
                        }
                    },
                    Apellidos: {
                        message: 'Los apellidos no son validos',
                        validators: {
                            notEmpty: {
                                message: 'No ha ingresado los Apellidos'
                            },
                            stringLength: {
                                max: 191,
                                message: 'Los apellidos no deben ser mayor de 191 caracteres'
                            }
                        }
                    },
                    Direccion: {
                        message: 'La Dirección no es valida',
                        validators: {
                            notEmpty: {
                                message: 'No ha ingresado la Dirección'
                            },
                            stringLength: {
                                max: 191,
                                message: 'La Dirección no debe ser mayor de 191 caracteres'
                            }
                        }
                    },
                    Email: {
                        message: 'El Email no es valido',
                        validators: {
                            notEmpty: {
                              message: 'No ha ingresado el Email'
                            },
                            emailAddress: {
                              message: 'No es un Email valido'
                            }
                        }
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertar();
                $("#FormAlumno").data('bootstrapValidator').resetForm();
    });

    $(".select2").select2();
    configurarTabla();

    viewData();
    eliminar();
    subirFoto();
    mostrarConfiguracion();
    guardarRelacion();
     
  </script>
@stop


