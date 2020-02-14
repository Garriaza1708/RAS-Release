$(document).ready(function(){

	$('#ListaCursos span').each(function() {
		var $span = $(this);
			if($span.data('id') == localStorage.getItem('idSeleccionado')){
				$span.css('border', '2px solid red');
			}
	});

	$("#ContenedorClases").on('click','.btn-add-lesson',function(e){
		var id = $(this).data('id');
		var semana = $(this).data('semana');

		document.getElementById('SemanaClase').value = semana;
		document.getElementById('UnidadClase').value = id;
	});

	$(".btn-update-lesson").on('click', function(event) {
		$.post('clase/actualizar',$("#FormClaseUpdate").serializeObject(),function(rpta){
			if(rpta.Estado == "Actualizado")
			{
				mensajePersonalizado("Clase","Clase Actualizada Correctamente","success",3000);	
				getLessonsByWeek(rpta.Listado,$("#UnidadClaseActualizado").val(),$("#SemanaClaseActualizado").val().replace(/\s+/g, ''));
				setTimeout(function(){$(".btn-close-update").trigger('click')},1000);	
			}
			else
				mensajePersonalizado("Clase","Ocurrio un error","error",3000);

			limpiarForm('FormClaseUpdate');
		});
	});

	$(".a-set-course").on('click', function(e) {

		var clase = "tab-pane active" , div = "";

		$(".seleccionado").css('border', '');

		$(this).parent().css('border', '2px solid red');
		
		var id = $(this).data('id');

		localStorage.setItem('idSeleccionado', id);

		$.post('clase/set/curso/'+id,function(rpta){


			$("#ContenedorClases").empty();
			loading('ContenedorClases');
			setTimeout(function(){
				 for(var i = 0; i < rpta.Unidades.length; i++)
				 {
				 	if(i != 0)
				 		clase = "tab-pane";

				 	div += '<div class="'+clase+'" id="tab_'+(i+1)+'">';
				 		for (var j = 1; j <= rpta.Unidades[i].duracion; j++) {
				 			div += '<div class="box-group" id="accordion' + rpta.Unidades[i].id + '' + j + ' " style="margin-bottom: 16px; ">'+
				 			'<div class="panel box box-success"><div class="box-header with-border">'+
				 			' <h4 class="box-title">'+
                                  '<a data-toggle="collapse" data-parent="#accordion' + rpta.Unidades[i].id + '' + j + '" href="#collapse' + rpta.Unidades[i].id + '' + j + '">'+
                                    '<i class="fa fa-dot-circle-o"></i> <span> Semana '+j+'</span>'+
                                  '</a>'+
                               '</h4>'+
                               '<div id="collapse' + rpta.Unidades[i].id + '' + j + '" class="panel-collapse collapse">'+
                                '<div class="box-body">'+
                                '<button type="button" data-toggle="modal"'+ 
                                  'data-target="#modalAddLesson" '+
                                  'data-id="' + rpta.Unidades[i].id + '" '+
                                  'data-semana="Semana '+j+'" class="btn btn-primary btn-add-lesson" '+
                                  'aria-label="Left Align">'+
                                      '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>'+
                                  '</button><hr>'+
                                  '<div id="container' + rpta.Unidades[i].id + 'Semana'+j+'">';
                                  	for (var k = 0; k < rpta.Lessons.length; k++) {
                                  		var semaux = "Semana " + j; 
	                          			if (rpta.Unidades[i].id == rpta.Lessons[k].period_range_id && 
	                          				semaux == rpta.Lessons[k].semana) {
	                          				div += '<div class="panel panel-default" id="Panel'+rpta.Lessons[k].id+'">'+
			                                        '<div class="panel-heading">'+
				                                          '<span style="font-size:17px !important;color:#8B0000">'+
				                                          ''+ rpta.Lessons[k].nombre + ' - ' + convertDate(rpta.Lessons[k].fecha) +'' +
				                                          '</span>'+
			                                        '</div>'+
			                                        '<div class="panel-body" id="ContenedorBotones">'+
			                                          '<a class="btn btn-app btn-attendance" data-toggle="modal" data-target="#modalAttendance" onclick="takeAttendance('+rpta.Lessons[k].id+')"><i class="fa fa-users"></i> Asistencia</a>'+
			                                          '<a class="btn btn-app btn-edit-lesson" data-toggle="modal" data-target="#modalUpdateLesson" onclick="updateLesson('+rpta.Lessons[k].id+')"><i class="fa fa-edit"></i>'+ 
			                                          'Editar</a>'+
			                                          '<a class="btn btn-app btn-delete-lesson" onclick="deleteLesson('+rpta.Lessons[k].id+')"><i class="fa fa-trash"></i> Eliminar</a>'+
			                                        '</div>'+
	                                    		'</div>';
	                          			}
                                  	}
                                  div += '</div>'; 

				 			div += '</div></div></div></div></div>';
				 		}
				 	div += '</div>';
				 }
				 $("#ContenedorClases").html(div);
                      
			},2000);

			getStudentsByCourse(rpta.Alumnos);
		});
	});

	
	
});

function getLessonsByWeek(model,id_cont,sem_cont)
{
	$("#container"+id_cont+sem_cont).empty();
	for (var i = 0; i < model.length; i++) {
		$("#container"+id_cont+sem_cont).append('<div class="panel panel-default" id="Panel'+model[i].id+'"><div class="panel-heading">'+
		' <span style="font-size:17px !important;color:#8B0000">'+model[i].nombre+' - '+ convertDate(model[i].fecha) +'</span>'+
		'</div><div class="panel-body" id="ContenedorBotones"><a class="btn btn-app btn-attendance" data-toggle="modal" data-target="#modalAttendance" onclick="takeAttendance('+model[i].id+')">'+
		'<i class="fa fa-users"></i> Asistencia</a>'+
		'<a class="btn btn-app btn-edit-lesson" data-toggle="modal" data-target="#modalUpdateLesson" onclick="updateLesson('+model[i].id+')"><i class="fa fa-edit"></i> Editar</a>'+
		'<a class="btn btn-app btn-delete-lesson" onclick="deleteLesson('+model[i].id+')"><i class="fa fa-trash">'+
		'</i> Eliminar</a></div></div>');
	}
}

function deleteLesson(id)
{
	var msj = confirm('¿Seguro que desea eliminar, se perderan todos los datos asociados a esta clase?');
	if(msj){
		$.get('clase/eliminar/'+id,function(rpta){
			if(rpta.Estado == "Eliminado")
				$("#Panel"+id).remove();
		});
	}
}

function updateLesson(id)
{
	$.get('clase/get/'+id,function(rpta){
		document.getElementById('NombreNuevo').value = rpta.nombre;
		document.getElementById('Actualizado').value = rpta.id;
		document.getElementById('UnidadClaseActualizado').value = rpta.period_range_id;
		document.getElementById('SemanaClaseActualizado').value = rpta.semana;
	});
}

function getStudentsByCourse(model)
{
	var div = '';
	if(model.length)
	{
		//tabla.html('');
		div +=	'<form role="form" method="POST" id="FormAsistencias">'+
				'<input type="hidden" name="Clase" id="Clase">'+
					'<table class="table table-striped table-bordered table-hover table-condensed">'+
                    '<thead>'+
                      '<tr>'+
                        '<th height="45">NOMBRES Y APELLIDOS</th>'+
                        '<th>ASISTENCIA</th>'+
                      '</tr>'+
                    '</thead>'+
                    '<tbody>';
            for (var i = 0; i < model.length; i++){
            	div +=  '<tr>'+
	                        '<td style="font-size: 16px;">'+
	                        '<input type="hidden" name="Alumnos" id="Alumnos" '+
                        		'value="'+model[i].id+'">'+model[i].alumno+'</td>'+
	                        '<td>'+
	                          '<select class="form-control al'+model[i].id+'" id="Asistencias" '+
                              'name="Asistencias">'+
	                            '<option value="Asistencia">Asistencia</option>'+
	                            '<option value="Tardanza">Tardanza</option>'+
	                            '<option value="Falta">Falta</option>'+
	                          '</select>'+
	                        '</td>'+
                      	'</tr>';
            }     
                div +=   '</tbody></table></form>';

	}
	else
	{
		div += ' <div class="alert alert-warning alert-dismissible">'+
                      '<h4><i class="icon fa fa-warning"></i> Advertencia!</h4>'+
                      'No existe alumnos registrados en el curso'+
                '</div>';

	}
	document.getElementById('ListaAlumnosxCurso').innerHTML = div;
}

function insertarClase () {
	$.post('clase/insertar',$("#FormClase").serializeObject(),function(rpta){
			var sem = document.getElementById('SemanaClase').value.replace(/\s+/g, '');
			var id = document.getElementById('UnidadClase').value
			if(rpta.Estado == "Registrado")
			{
				mensajePersonalizado("Clase","Clase Creada Correctamente","success",3000);	
				getLessonsByWeek(rpta.Listado,id,sem);	
			}
			else
				mensajePersonalizado("Clase","Ocurrio un error","error",3000);

			limpiarForm('FormClase');
	});
}




