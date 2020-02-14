@extends('layout')

@section('title')
	Notas
@stop

@section('description')
	Configuración
@stop

@section('container')
<div class="row">

	<div class="col-md-12">
		<!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" data-toggle="tab">Listado</a></li>
              <li><a href="#tab_2" data-toggle="tab">Nuevo</a></li>
              <li><a href="#tab_3" data-toggle="tab" id="Tab3" style="display: none;">Estructura</a></li>
              <!--<li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>-->

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Lista de Notas</h3>
                </div>
               <div class="box-body">
                  <table id="tblNotas" class="table table-bordered table-hover" width="100%">
                   <thead>
                     <tr>
                       <th></th>
                       <th></th>
                       <th>Nombre</th>
                       <th>Descripción</th>
                       <th>Curso</th>
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
                    <h3 class="box-title">Curso</h3>
                  </div>
                  <form role="form" method="POST" id="FormNota">
                    <input type="hidden" name="Accion" id="Accion" value="Registrar">

                    <div class="box-body">
                      <div class="form-group">
                        <label for="Curso">Curso</label>
                        <select class="form-control" style="width:100%;" id="Curso" name="Curso" required>
                        @if(count($cursos)>0)
                            <option value="">Seleccione curso</option>
                          @foreach($cursos as $cur)
                            <option value="{{ $cur->id }}">{{ $cur->nombre }}</option>
                          @endforeach
                        @else
                          <option value="">No hay cursos registrados</option>
                        @endif
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese Nombre" required>
                      </div>

                      <div class="form-group">
                        <label for="Descripcion">Descripción</label>
                        <textarea class="form-control" name="Descripcion" id="Descripcion" rows="3" placeholder="Ingrese Descripción"></textarea>
                      </div>

                    </div>
                    <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" id="Enviar" class="btn btn-primary pull-right">Enviar</button>
                  </div>
            </form>
                  </div>

              </div>
              <div class="tab-pane" id="tab_3" >
                <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Agregar Sub Notas a <span id="NombreNota"></span></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" method="POST" id="FormSubNota">
                          <input type="hidden" name="AccionSubNota" id="AccionSubNota" value="RegistrarSubNota">
                          <div class="form-group">
                            <label for="NombreSubNota">Nombre</label>
                            <input type="text" class="form-control" id="NombreSubNota" name="NombreSubNota" placeholder="Ingrese Nombre de la Sub Nota" required autofocus>
                          </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                          <button type="submit" id="EnviarForm" class="btn btn-primary pull-right">Enviar</button>
                        </div>
                      </form>
                    </div>
                  </div>

                  <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Lista de Sub Notas</h3>
                    </div>
                    <div class="box-body">
                      <table id="tblSubNotas" class="table table-bordered table-hover" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Nombre</th>
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
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
	</div>
</div>
@stop

@section('scripts')
<script src="{{ asset('js/gestion/grade.js') }}"></script>
<script src="{{ asset('js/gestion/grade_structure.js') }}"></script>
<script>
    $('#FormNota').bootstrapValidator({
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
                    Curso: {
                        validators: {
                            notEmpty: {
                                message: 'No ha seleccionado un Curso'
                            }
                        }
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertar();
                $("#FormNota").data('bootstrapValidator').resetForm();
    });

    $('#FormSubNota').bootstrapValidator({
                message: 'Este valor no es valido',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    NombreSubNota: {
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
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertarSubNota();
                $("#FormSubNota").data('bootstrapValidator').resetForm();
    });

    configurarTabla();
    viewData();
    eliminar();

    mostrarFormSubNota();
    viewDataSubNota();
    eliminarSubNota();

</script>
@stop
