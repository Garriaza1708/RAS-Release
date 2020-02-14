@extends('layout')

@section('css')
    <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <!-- Select2 -->
  <style>
    .dataTables_filter{
      margin-left:-60px;
    }
    #TablaAlumnos_paginate{
      margin-left:-70px;
    }
  </style>
@stop

@section('title')
	Dashboard
@stop

@section('description')
	Vista General
@stop

@section('container')
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Rendimiento de un Alumno por Curso</h3>

              <div class="box-tools pull-right">
               
                <div class="btn-group">
                  <button type="button" class="btn btn-default" id="NombreCurso">Seleccione Curso</button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    @if(count($listaCursos))
                      @foreach ($listaCursos as $curso)
                        @if($loop->first)
                          <?php $clase = 'primero';?>
                        @endif
                        <li onClick="listarAlumnos({{ $curso->id }},'{{ $curso->nombre }}');" class="{{ $clase }}"><a href="#" >{{ $curso->nombre }}</a></li>
                        <?php $clase = '';?>
                      @endforeach
                    @endif  
                  </ul>
                </div>

                 <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
       
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
             
                  <div id="container" style="width: 820px; height: 400px; margin: 0 auto"></div>
                 
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                   <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="TablaAlumnos" width="100%">
                        <thead>
                          <tr>
                            <th></th>
                            <th></th>
                            <th>Alumno</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                  </div>
                  <span style="display: none;" id="idCurso"></span>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Alumnos mas destacados en</h3>
              
              <select name="ListaCursos2" id="ListaCursos2">
                @if(count($listaCursos))
                  @foreach ($listaCursos as $key => $curso)
                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                  @endforeach
                @else
                    <option value="">No ha creado cursos</option>
                @endif  
              </select>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <div class="table-responsive">
                <div id="TableroDos"></div>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


          <!-- TABLE: LATEST ORDERS -->

        </div>

        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Cursos con Mayor Cantidad de Alumnos</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin" id="TableroTres">
                  <thead>
                  <tr>

                    <th>Curso</th>
                    <th>Cantidad</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Alumnos con mayor cantidad de</h3>    
              <button class="btn-primary btn-xs" id="btnTardanzas">TARDANZAS</button>
              <button class="btn-danger btn-xs" id="btnFaltas">FALTAS</button>
                en
               <select name="ListaCursostbl4" id="ListaCursostbl4">
                @if(count($listaCursos))
                  @foreach ($listaCursos as $curso)
                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                  @endforeach
                @else
                    <option value="">No ha creado cursos</option>
                @endif  
              </select>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin" id="TableroCuatro">
                  <thead>
                  <tr>
                    <th>N°</th>
                    <th>Alumno</th>
                    <th id="clCantidad">Cantidad</th>
                    <th>Estado</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        
        <div class="col-md-6" style="height:545px;">
          
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Alumnos con Notas mas Bajas del</h3>  

              <select name="ListaPeriodos" id="ListaPeriodos">
                @if(count($listaRangos))
                  @foreach ($listaRangos as $rango)
                    <option value="{{ $rango->id }}">{{ $rango->nombre }}</option>
                  @endforeach
                @else
                    <option value="">No ha creado rangos</option>
                @endif  
              </select>
              
              en 
              
              <select name="ListaCursos" id="ListaCursos">
                @if(count($listaCursos))
                  @foreach ($listaCursos as $curso)
                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                  @endforeach
                @else
                    <option value="">No ha creado cursos</option>
                @endif  
              </select>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin" id="TableroCinco">
                  <thead>
                  <tr>
                    <th>N°</th>
                    <th>Alumno</th>
                    <th>Promedio</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>

      <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Alumnos Recientemente Registrados</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                @if (count($alumnos))
                  @foreach ($alumnos as $alumno)
                    <li class="item">

                      <div class="product-info">
                        <a href="javascript:void(0)" class="product-title">{{ $alumno->nombre }}
                          <span class="label label-warning pull-right" style="font-size: 12px;">{{ $alumno->email }}</span>
                        </a>
                            <span class="product-description">
                              {{ $alumno->apellidos }}
                            </span>
                      </div>
                    </li>
                  @endforeach

                @else
                  <li class="item">
                    <center><span style="font-size: 17px;">No se han registrado Alumnos</span></center>
                  </li>
                @endif
               
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{ route('app.student.page') }}" class="uppercase">Ver Todos los Alumnos</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
      </div>

      <div class="col-md-6">
        
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Cursos Recientemente Registrados</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                @if(count($cursos))
                  @foreach ($cursos as $key => $curso)
                  <li class="item" style="height: 71px;">
                    <div class="product-img">
                      {{ $key + 1}}
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">{{ $curso->nombre }}
                        <span class="label label-warning pull-right" style="font-size: 12px;">{{ $curso->created_at }}</span></a>
                          <span class="product-description">
                            {{ $curso->descripcion }}
                          </span>
                    </div>
                  </li>
                  @endforeach

                @else
                  <li class="item">
                    <center><span style="font-size: 17px;">No se han registrado Cursos</span></center>
                  </li>
                  
                @endif
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="{{ route('app.course.page') }}" class="uppercase">Ver Todos los Cursos</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
      </div>

        <div class="col-md-12">
           <!-- quick email widget -->
          <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Enviar Email</h3>
              
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <form action="#" method="post" id="FormMail">
                <div class="form-group">
                  <label>Seleccione Curso para listar destinatarios:</label>
                  <select class="form-control" name="ListaCursostbl8" id="ListaCursostbl8">
                  @if(count($listaCursos))
                      <option value="">Seleccione un curso</option>
                    @foreach ($listaCursos as $curso)
                      <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                  @endif
                  </select>
                </div>
                <div class="form-group" style="display: none;" 
                id="PanelDestinatarios">
                  <label for="isTodos">Seleccione si desea enviar a todos los alumnos del curso seleccionado</label>
                  <input type="checkbox" style="margin:5px;" name="isTodos" id="isTodos">
                  <select class="form-control select2" multiple="multiple" data-placeholder="Seleccione Destinatarios" name="Destinatarios" 
                  id="Destinatarios" style="width: 100%;">
                  </select>
              </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="Asunto" placeholder="Asunto">
                </div>
                <div>
                  <textarea class="textarea" name="Mensaje" placeholder="Mensaje" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
                <div class="box-footer clearfix">
                  <button type="button" class="pull-right btn btn-default" id="EnviarMail">Enviar
                    <i class="fa fa-arrow-circle-right"></i></button>
                </div>
              </form>
            </div>
            
          </div>
        </div>
      </div>
      <!-- /.row -->
@stop

@section('scripts')
  <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
  <script src="{{ asset('js/gestion/dashboard.js') }}"></script>

  <script src="{{ asset('plugins/highcharts/highcharts.js') }}"></script>
  <script src="{{ asset('plugins/highcharts/exporting.js') }}"></script>

  <script>
    //cargarGrafico();

    $("#TablaAlumnos").DataTable({
        "aoColumns": [
          {"sWidth": "1%","sClass": "center", "bVisible": false},
          {"sWidth": "1%","sClass": "center", "bSortable": false},
          {"sWidth": "98%","sClass": "center", "bSortable": true},
        ],
        "autoWidth" : true,
        "bLengthChange": false,
        "info":false,
        "pageLength": 5
      });
  </script> 

@stop
