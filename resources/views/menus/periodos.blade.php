@extends('layout')

@section('title')
	Periódos
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
              <!--<li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>-->

            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Lista de Periódos</h3>
                </div>
               <div class="box-body">
                  <table id="tblPeriodos" class="table table-bordered table-hover" width="100%">
                   <thead>
                     <tr> 
                       <th></th>
                       <th>Nombre</th>
                       <th>Duración</th>
                       <th>Año</th>
                       <th>Descripción</th>
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
                    <h3 class="box-title">Periódo</h3>
                  </div>
                  <form role="form" method="POST" id="FormPeriodo">
                    <input type="hidden" name="Accion" id="Accion" value="Registrar">
                    
                    <div class="box-body">
                      <div class="form-group">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese Nombre">
                      </div>
                       <div class="form-group">
                        <label for="Duracion">Duración (en semanas)</label>
                        <input type="text" class="form-control" id="Duracion" name="Duracion" placeholder="Ingrese Duración en semanas">
                      </div>
                      <div class="form-group">
                        <label for="Anio">Año</label>
                        <select class="form-control" style="width:100%;" id="Anio" name="Anio">
                            <option value="">Seleccione Año</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" name="Descripcion" 
                        id="Descripcion" rows="3" placeholder="Ingrese Descripción"></textarea>
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
  <script src="{{ asset('js/gestion/period.js') }}"></script>
  <script>
  $('#FormPeriodo').bootstrapValidator({
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
                            }/*,
                            regexp: {
                                regexp: /^[a-zA-Z0-9_\.]+$/,
                                message: 'The username can only consist of alphabetical, number, dot and underscore'
                            }*/
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
                              value: 1,
                              inclusive: false,
                              message: 'La Duración tiene que ser mayor a 1'
                            }
                        }
                    },
                    Anio: {
                        validators: {
                            notEmpty: {
                                message: 'No ha seleccionado un Año'
                            }
                        }
                    }
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertar();
                $("#FormPeriodo").data('bootstrapValidator').resetForm();
    });
   

    //$(".select2").select2();
    configurarTabla();
    //insertar();
    viewData();
    eliminar();
  </script>
@stop


