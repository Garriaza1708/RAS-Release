@extends('layout')

@section('css')
  <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
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
	Usuarios
@stop

@section('description')
	Gestion de Usuarios
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
                  <h3 class="box-title">Lista de Usuarios</h3>
                </div>
               <div class="box-body">
                  <table id="tblUsuarios" class="table table-bordered table-hover" width="100%">
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
                    <h3 class="box-title">Usuario</h3>
                  </div>
                  <form role="form" method="POST" id="FormUsuario">
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
                      <div class="form-group">
                        <label for="Password">Contraseña</label>
                        <input type="password" class="form-control" id="Password" name="Password" placeholder="Ingrese Contraseña" required>
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

<!--Modal para subir foto-->
     <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-lg">
          <div class="modal-content">
            @include('partials.subirfoto')
          </div>
       </div>
    </div> 

@stop

@section('scripts')
<script src="{{ asset('js/gestion/user.js') }}"></script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script>
  $('#FormUsuario').bootstrapValidator({
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
                                message: 'El Nombre no debe ser mayor de 191 caracteres'
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
                    },
                    Password: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'No ha ingresado la Contraseña'
                          },
                          stringLength: {
                                min: 7,
                                message: 'La Contraseña debe tener al menos 7 caracteres'
                          }
                      }
                    }
                }
            })
            // Enable the password/confirm password validators if the password is not empty
            .on('keyup', '[name="Password"]', function() {
                var isEmpty = $(this).val() == '';
                $('#FormUsuario')
                        .bootstrapValidator('enableFieldValidators', 'Password', !isEmpty);

                // Revalidate the field when user start typing in the password field
                if ($(this).val().length == 1) {
                    $('#FormUsuario').bootstrapValidator('validateField', 'Password');
                }
            })
            .on('success.form.bv', function(e) {
                // Prevent form submission
                e.preventDefault();
                insertar();
                $("#FormUsuario").data('bootstrapValidator').resetForm();
    });
  configurarTabla();
  //$(".select2").select2();
  Dropzone.autoDiscover = false;
    $("#mydropzone").dropzone({
        //paramName: "file",
        url: "usuario/foto",
        addRemoveLinks : true,
        acceptedFiles: 'image/*',
        maxFilesize: 4.5,
        maxFiles:1,
        dictDefaultMessage: '<div class="dz-message needsclick">Arrastre su foto aqui o haga clic para subirlo</div>',
        dictResponseError: 'Error al subir foto!',
        success:function(file,data){
          if(data.Estado == "Subido"){
          mensajePersonalizado('Subir','Imagen Subida Correctamente','success',3000);
          }
          else
          mensajePersonalizado('Subir','Ocurrio un Error','error',3000);

          Dropzone.forElement("#mydropzone").removeAllFiles(true);
        }
    });

    viewData();
    eliminar();
    subirFoto();
</script>
@stop


