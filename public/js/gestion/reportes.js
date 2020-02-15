$(document).ready(function(){

	$(".a-set-course").on('click',function(){
		$('.seleccionadoc').css('border','');

		var id = $(this).data('id');

		//este id se usara para generar el reporte en PDF
		document.getElementById('idCurso').value = id;

		$(this).parent().css('border','2px solid red');

		$("#DetalleReporte").html('');

		loading('DetalleReporte');

		if(localStorage.getItem('tipoReporte') == 'rpprom')
			promedioGenerales(id);
		else if(localStorage.getItem('tipoReporte') == 'rpdet')
			detalleNotas(id);
		else if(localStorage.getItem('tipoReporte') == 'rpasi')
			asistenciasAlumnos(id);
		else if(localStorage.getItem('tipoReporte') == 'rpalu')
			estadosNotasAlumnos(id);
		else
			alert('Reporte no disponible');

	});

	$("#DetalleReporte").on('click','.get-data',function(){
		var alu = $(this).data('id-student');
		var course = $(this).data('id-course');
		var tipo = $(this).data('tipo');
		var html = '';
		$("#DetalleAsistencias").html('');
		loading('DetalleAsistencias');
		$.post('/appnotas/public/reportes/asis/detalle',{Alumno:alu,Curso:course,Tipo:tipo},function(rpta){
			//console.log(rpta.asistencias);

			if(rpta.asistencias.length)
				{
					html += '<div class="panel panel-primary">'+
							'<table class="table" id="Table-notas">'+
								'<thead>'+
									'<tr>'+
										'<th>Periódo</th>'+
										'<th>Semana</th>'+
										'<th>Nombre de la Clase</th>'+
										'<th>Fecha</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>';
					for (var i = 0 ; i < rpta.asistencias.length; i++) {
						html += '<tr>'+
									'<td>'+rpta.asistencias[i].periodo+'</td>'+
									'<td>'+rpta.asistencias[i].semana+'</td>'+
									'<td>'+rpta.asistencias[i].lesson+'</td>'+
									'<td>'+rpta.asistencias[i].fecha+'</td>'+
								'</tr>'
					}

					html +=			'</tbody>'+
								'</table>'+
							'</div>';


				}
			else
				html = '<div class="callout callout-info">'+
	                		'<h4>Información!</h4>'+
	                		'<p>No se han encontrado registros.</p>'+
              			'</div>';
		$("#DetalleAsistencias").html(html);
		});
	});

});

function promedioGenerales (curso) {
	$.get('/appnotas/public/reportes/prom/'+curso,function(rpta){
		$("#DetalleReporte").html(rpta);
	});
}

function detalleNotas (curso) {
	$.get('/appnotas/public/reportes/detallenotas/'+curso,function(rpta){
		$("#DetalleReporte").html(rpta);
	});
}

function estadosNotasAlumnos (curso) {
	$.get('/appnotas/public/reportes/alaprodes/'+curso,function(rpta){
		$("#DetalleReporte").html(rpta);
	});
}

function asistenciasAlumnos (curso) {
	$.get('/appnotas/public/reportes/alasis/'+curso,function(rpta){
		$("#DetalleReporte").html(rpta);
	});
}

function nombreReporte () {
	const tipo = localStorage.getItem('tipoReporte');
	var nombre;

	if(tipo == "rpdet") nombre = "Detalles Notas";
	else if(tipo == "rpalu") nombre = "Alumnos Aprobados y Desaprobados";
	else if(tipo == "rpasi") nombre = "Asistencias por alumno";
	else if(tipo == "rpprom") nombre = "Promedios Generales";
	else nombre = "Reporte";

	return nombre;
}

function exportarExcel(){
	$("#btnExcel").click(function(){
		if($("#DetalleReporte").children().length == 0){
			alert('No ha generado reporte');
		}else{
			$(".tabla-reporte").btechco_excelexport({
	                containerid: "tabla-reporte",
	                datatype: $datatype.Table,
	                filename: nombreReporte()
			});
		}
	})
}

function exportarPDF(){
	const tipo = localStorage.getItem('tipoReporte');
	$("#btnPDF").click(function(){
		if($("#DetalleReporte").children().length == 0){
			alert('No ha generado reporte');
		}else{
			location.href = "/generarpdf/"+$("#idCurso").val()+"/"+tipo;
		}
	});
}
