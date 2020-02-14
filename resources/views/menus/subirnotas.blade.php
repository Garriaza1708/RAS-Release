@extends('layout')

@section('title')
	Notas
@stop

@section('description')
	Ingreso de Notas
@stop

@section('container')
      <div class="row">
      @if(count($courses) && count($periods))
      		<div class="col-md-12">
				<div class="box box-solid">
					<div class="box-header with-border">
						<i class="fa fa-book"></i>
						<h3 class="box-title">Cursos</h3>
						<div class="box-tools pull-right">
						<div class="form-group">
							<select name="PeriodosCb" id="PeriodosCb" class="form-control" style="font-size: 17px;">
							<option value="">Seleccione Periódo</option>
								@foreach($periods as $period)
									<option value="{{ $period->id }}">
										{{ $period->nombre }}
									</option>
								@endforeach
							</select>
						</div>
			               
              			</div>
					</div>
					<div class="box-body">
						@foreach($courses as $course)
						<div class="col-md-4">
							<div class="box box-widget widget-user-2">
					            <div class="widget-user-header bg-green">
					            	<a href="#" class="btn-list-grades" 
									data-id="{{ $course->id }}"
									data-name="{{ $course->nombre }}"
					            	data-toggle="modal" data-target="#modalNotas" 
					            	style="font-size: 19px;color:white;">
					            		{{ $course->nombre }}
					            	</a>
					            </div>
		          			</div>
		          		</div>
						@endforeach
					</div>
        		</div>
	        </div>
	    @else
	    <div class="col-md-12">
	    	 <div class="alert alert-warning alert-dismissible">
			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			    <h4><i class="icon fa fa-warning"></i> Advertencia!</h4>
			       No existen cursos registrados para este periódo
			</div>
	    </div>
		   
      @endif
	     
      </div>


<!-- Modal -->
<div class="modal fade" id="modalNotas" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      @include('partials.listanotas');
    </div>
  </div>
</div>

@stop

@section('scripts')
<script src="{{ asset('js/gestion/grade_value.js') }}"></script>
@stop
