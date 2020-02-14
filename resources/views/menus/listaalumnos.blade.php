@extends('layout')

@section('title')
	Notas
@stop

@section('description')
	Ingreso de Notas
@stop

@section('container')
	<div class="row">
		<div class="col-md-9">
			@if(count($students))
			<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Alumnos del Curso 
              	<span style="font-size: 18px;" id="NombreCurso">
              		{{ $curso->nombre }}
              	</span>
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <form role="form" id="FormNotas" method="POST">
            <input type="hidden" name="idSubGrade" id="idSubGrade">
            <input type="hidden" name="idPeriodo" id="idPeriodo">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 5px">#</th>
                  <th>Alumno</th>
                  <th style="width: 10%;">Nota</th>
                </tr>
                @foreach ($students as $key=>$student)
					<tr style="font-size: 15px">
	                  <td>{{ $key + 1 }}</td>
	                  <td>
	                  	<input type="hidden" name="Alumnos" id="Alumnos" 
	                  		value="{{ $student->idAlumno }}">
	                  	{{ $student->alumno }}
	                  </td>
	                  <td>
	                    <select name="Notas" id="Notas" class="form-control nt{{ 
	                    	$student->idAlumno }}">
	                    	<option value="00">00</option>	
	                    	<option value="01">01</option>
	                    	<option value="02">02</option>
	                    	<option value="03">03</option>
	                    	<option value="04">04</option>
	                    	<option value="05">05</option>
	                    	<option value="06">06</option>
	                    	<option value="07">07</option>
	                    	<option value="08">08</option>
	                    	<option value="09">09</option>
	                    	<option value="10">10</option>
	                    	<option value="11">11</option>
	                    	<option value="12">12</option>
	                    	<option value="13">13</option>
	                    	<option value="14">14</option>
	                    	<option value="15">15</option>
	                    	<option value="16">16</option>
	                    	<option value="17">17</option>
	                    	<option value="18">18</option>
	                    	<option value="19">19</option>
	                    	<option value="20">20</option>
	                    </select>	
	                  </td>
               	 	</tr>
				@endforeach
              </table>
				</form>
				<div class="box-footer clearfix">
             		<button class="btn btn-primary pull-right btn-save-grade">
             			Guardar
             		</button>
            	</div>
            </div>

            
            <!-- /.box-body -->

          </div>
          <!-- /.box -->
				
			@else
				<div class="alert alert-warning alert-dismissible">
		            <button type="button" class="close" data-dismiss="alert" 
		                    aria-hidden="true">×</button>
		            <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
		                No existen alumnos registrados para este curso
		        </div>
			@endif
		</div>

		<div class="col-md-3 offset-md-3">
			<div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-book"></i>

              <h3 class="box-title">Sub Notas de 
				<span style="font-size: 18px;" id="NombreNota">
					{{ $grade->nombre }}
				</span>
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="ListaSubNotas">
           	
           	@if(count($subgrades))
	            @foreach($subgrades as $subgrade)
	                <span class="label label-success seleccionado" 
	                style="font-size: 15px;text-align:center;display:block;margin-bottom:6px;height: 25px;" 
	                	data-id="{{ $subgrade->id }}">
	                  <a href="#" class="a-set-subgrade" 
	                  	style="color: white !important;
	                  cursor: pointer;" data-id="{{ $subgrade->id }}">
	                      {{ $subgrade->nombre }}
	                  </a>
	                </span>
	            @endforeach
            @else
              <span class="label label-warning" style="font-size: 13px;">
              	No existen Sub-Notas para la Nota Seleccionada
              </span>
            @endif
             
            </div>
            <!-- /.box-body -->
          </div>
		</div>
	</div>
@stop

@section('scripts')
	<script src="{{ asset('js/gestion/grade_value_save.js') }}"></script>
@stop
