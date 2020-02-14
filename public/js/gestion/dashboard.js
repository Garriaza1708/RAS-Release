$(document).ready(function(){
	var tipoFiltro = "Tardanza";
	$("#EnviarMail").click(function(){
		$.post('dashboard/sendemail',$("#FormMail").serializeObject(),function(rpta){
			//console.log(rpta);
			if(rpta == "Enviado")
				mensajePersonalizado("Email","Mensaje Enviado Correctamente","success",3000);	
			else
				mensajePersonalizado("Email","Ocurrio un error...Intentelo de Nuevo","error",3000);

			$('#Destinatarios').val(null).trigger('change');
			limpiarForm('FormMail')
		});
	});

	$("#ListaCursostbl8").change(function(){
		var id = $(this).val();
		if(id != "")
		{
			$.post('/dashboard/students',{id:id},function(rpta){
				$("#Destinatarios").html('');
				document.getElementById('PanelDestinatarios').style.display = 'block';
				$("#isTodos").prop('checked',false);
				for (var i = 0; i < rpta.length; i++) {
					$("#Destinatarios").append("<option value='"+rpta[i].email+"'>"+rpta[i].alumno+"</option>");
				}
				$(".select2").select2();
			});
		}
	});

	$("#btnTardanzas").click(function(){
		tipoFiltro = "Tardanza";
		$("#clCantidad").html('Cantidad');
		$("#clCantidad").append('(T)');
		getTableroCuatro(tipoFiltro,$("#ListaCursostbl4").val());
	});

	$("#btnFaltas").click(function(){
		tipoFiltro = "Falta";
		$("#clCantidad").html('Cantidad');
		$("#clCantidad").append('(F)');
		getTableroCuatro(tipoFiltro,$("#ListaCursostbl4").val());
	});

	$("#ListaCursostbl4").change(function(){
		var id = $(this).val();
		if(tipoFiltro == "")
			alert('No ha seleccionado filtro');
		else
		{
			getTableroCuatro(tipoFiltro,id);
			//console.log(id);
		}
	});

	$("#ListaPeriodos").change(function(){
		var id = $(this).val();
		getTableroCinco(id,$("#ListaCursos").val());
	});

	$("#ListaCursos").change(function(){
		var id = $(this).val();
		getTableroCinco($("#ListaPeriodos").val(),id);
	});

	$("#isTodos").click(function(){
		if($(this).is(":checked")) $(".select2").hide();
		else $(".select2").show();
	});

	$("#ListaCursos2").change(function(){
		//var id = $(this).val();
		getTableroDos($(this).val());
	});
	//test();
	getTableroDos($("#ListaCursos2").val());
	getTableroTres();
	getTableroCuatro('Tardanza',$("#ListaCursostbl4").val());
	getTableroCinco($("#ListaPeriodos").val(),$("#ListaCursos").val());
	mostrarResultados();
	$(".primero").trigger('click');
	
});


function getTableroTres(){
	var body = $("#TableroTres tbody");

	body.html('');

	$.get('dashboard/tablerotres',function(rpta){
		if(rpta.length){
			for(var i = 0; i < rpta.length; i++){
				var fila = "<tr>";
				fila += "<td>" + rpta[i].curso + "</td>";
				fila += "<td>" + rpta[i].cantidad + "</td>";

				body.append(fila);
			}
		}else{
			body.append('<tr><td style="text-align:center;" colspan="2">No se han encontrado registros</td></tr>');
		}
		
	});
}

function getTableroCuatro(tipo,idCurso) {
	var body = $("#TableroCuatro tbody");
	var loading = "<p><i class='fa fa-circle-o-notch fa-spin'></i> Cargando Datos...</p>";
	if(tipo != "" && idCurso != 0 && idCurso != ""){
	body.html(loading);
		$.post('dashboard/tablerocuatro',{ id:idCurso,tipo:tipo },function(rpta){
			body.html('');
			if(rpta.length){
				for(var i = 0; i < rpta.length; i++){
					var fila = "<tr>";
					fila += "<td>" + (i + 1) + "</td>";
					fila += "<td>" + rpta[i].alumno + "</td>";
					fila += "<td>" + rpta[i].cantidad + "</td>";
					fila += "<td><div class='progress'>";
		            fila += "<div class='progress-bar " + rpta[i].color + " progress-bar-striped active' ";
		            fila += "role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100' ";
		            fila += "style='width:" + rpta[i].porcentaje + "%;color:black;'>" + rpta[i].porcentaje + "% </div></div></td>";

					body.append(fila);
				}
			}else{
				body.append('<tr><td style="text-align:center;" colspan="4">No se han encontrado registros</td></tr>');
			}
			
		});
	}else{
		body.append('<tr><td style="text-align:center;" colspan="4">No se han encontrado registros</td></tr>');
	}
}

function getTableroCinco(rango,idCurso) {
	var body = $("#TableroCinco tbody");
	var loading = "<p><i class='fa fa-circle-o-notch fa-spin'></i> Cargando Datos...</p>";
	if(idCurso != "" && idCurso != 0 && rango != 0 && rango != ""){
		body.html(loading);
		$.post('dashboard/tablerocinco',{ id:idCurso,rango:rango },function(rpta){
			body.html('');
			if (rpta.length){
				for(var i = 0; i < rpta.length; i++){
					var fila = "<tr style='height:42px;'>";
					fila += "<td>" + (i + 1) + "</td>";
					fila += "<td>" + rpta[i].alumno + "</td>";
					fila += "<td>" + rpta[i].promedio + "</td>";

					body.append(fila);
				}
			}else{
				body.append('<tr><td style="text-align:center;" colspan="3">No se han encontrado registros</td></tr>');
			}
		});
	}else{
		body.append('<tr><td style="text-align:center;" colspan="3">No se han encontrado registros</td></tr>');
	}
	
}

function getTableroDos(idCurso){
	if(idCurso != "" && idCurso != 0){
		var loading = "<p><i class='fa fa-circle-o-notch fa-spin'></i> Cargando Datos...</p>";
		document.getElementById('TableroDos').innerHTML = loading;
		$.post('dashboard/tablerodos',{ id:idCurso },function(rpta){
			document.getElementById('TableroDos').innerHTML = rpta;
		});
	}else{
		document.getElementById('TableroDos').innerHTML = '<center><span style="font-size: 17px;">No se han encontrado Registros</span></center>';
	}
}

function listarAlumnos(id,nombre) {
	document.getElementById('idCurso').textContent = id;
	document.getElementById('NombreCurso').textContent = nombre;
	
	$.post('/dashboard/students',{id:id},function(rpta){
		$("#TablaAlumnos").DataTable().draw().clear();
		for(var i = 0; i < rpta.length; i++){
			$('#TablaAlumnos').dataTable().fnAddData([ rpta[i].id ,"<input type='radio' name='seleccion' id='RAlumno'>", 
				rpta[i].alumno ]);
		}
		$("#RAlumno").trigger('click');
	});
	
}

function mostrarResultados(){
	$("#TablaAlumnos").on('click','#RAlumno',function(){
		var idCurso = document.getElementById('idCurso').textContent;
		var data = $("#TablaAlumnos").dataTable().fnGetData($(this).closest('tr'));
		var categorias = [];
		$.post('dashboard/grafico',{ idAlumno:data[0],idCurso:idCurso },function(rpta){
			for(key in rpta.categorias){ categorias.push(key); }
			cargarGrafico(data[2],categorias,rpta.notas);
		});
	});
}

function cargarGrafico(alumno,categories,notas) {
	Highcharts.chart('container', {
	    chart: {
	        type: 'line'
	    },
	    title: {
	        text: "Rendimiento de: " + alumno
	    },/*,
	    subtitle: {
	        text: 'Source: WorldClimate.com'
	    },*/
	    xAxis: {
	        categories
	    },
	    yAxis: {
	        title: {
	            text: 'Notas'
	        }
	    },
	    plotOptions: {
	        line: {
	            dataLabels: {
	                enabled: true
	            },
	            enableMouseTracking: true
	        }
	    },
	    series: notas
	});
}

