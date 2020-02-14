@extends('layout')

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@stop

@section('title')
	Rangos de Periódos
@stop

@section('description')
	Gestión de Rangos
@stop

@section('container')
<div class="row">
	<div class="col-md-12">
		<!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" data-toggle="tab">Listado</a></li>
              <li><a href="#tab_2" data-toggle="tab">Nuevo</a></li>
              <!--<li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>-->

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Lista de Rangos</h3>
                </div>
               <div class="box-body">
                  <table id="tblRangos" class="table table-bordered table-hover" width="100%">
                   <thead>
                     <tr> 
                       <th></th>
                       <th></th>
                       <th>Nombre</th>
                       <th>Duración(semanas)</th>
                       <th>Fecha de Inicio</th>
                       <th>Fecha de Fin</th>
                       <th>Periódo</th>
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
                    <h3 class="box-title">Rangos</h3><br>
                  </div>
                  <form role="form" method="POST" id="FormRango">
                    <input type="hidden" name="Accion" id="Accion" value="Registrar">
                     
                    <div class="box-body">
                      <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese Nombre" required>
                      </div>
                       <div class="form-group">
                        <label for="Duracion">Duración (en semanas)</label>
                        <input type="text" class="form-control" id="Duracion" name="Duracion" placeholder="Ingrese Duración en semanas" required>
                      </div>
                      <div class="form-group">
                        <label for="FechaInicio">Fecha de Inicio</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control" id="FechaInicio" name="FechaInicio" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="FechaFin">Fecha Fin</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control" id="FechaFin" name="FechaFin" required>
                        </div>
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
  <script src="{{ asset('js/gestion/period_range.js') }}"></script>
  <!-- bootstrap datepicker -->
  <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
  
  <script>
      $('#FormRango').bootstrapValidator({
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
                    Duracion: {
                        validators: {
                            notEmpty: {
                              message: 'No ha ingresado la Duración'
                            },
                            digits: {
                              message: 'La Duracion solo puede ser numeros'
                            },
                            lessThan: {
                              value: 52,
                              inclusive: true,
                              message: 'La Duración tiene que ser menor o igual a 52'
                            },
                            greaterThan: {
                              value: 0,
                              inclusive: false,
                              message: 'La Duración tiene que ser mayor a 0'
                            }
                        }
                    },
                    FechaInicio:{
                        validators: {
                            notEmpty: {
                                message: 'No ha seleccionado la Fecha de Inicio'
                            }
                        }
                    },
                    FechaFin:{
                      validators: {
                          notEmpty: {
                              message: 'No ha seleccionado la Fecha de Fin'
                          }/*,
                          date: {
                              format: 'DD/MM/YYYY',
                              message: 'La Fecha de Fin no es valida'
                          }*/
                      }
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertar();
                $("#FormRango").data('bootstrapValidator').resetForm();
    });

    $('#FechaInicio').datepicker({
      format:'dd/mm/yyyy',
      autoclose: true
    });

    $('#FechaFin').datepicker({
      format:'dd/mm/yyyy',
      autoclose: true
    });

    configurarTabla();
    viewData();
    eliminar();
  </script>
@stop


