function saveAttendance()
{
	$("#btnSaveAttendance").on('click',function(){
		loading3('ImgLoad');
		$.post('asistencia/insertar',$("#FormAsistencias").serializeObject(),function(rpta){
			if(rpta.Estado == "Guardado")
				$("#MsjOK").show();
			else
				$("#MsjError").show();

			$("#ImgLoad").html('');
		});
	});
}


function takeAttendance(id) 
{
	$("#MsjOK").hide();
	$("select").prop('selectedIndex', 0);

	if(document.getElementById('Clase'))//si encuentra el elemento entonces se asigna el id
		document.getElementById('Clase').value = id;

	$.get('asistencia/get/'+id,function(rpta){
		if(rpta.length)
		{
			for (var i = 0; i < rpta.length; i++) {
				document.getElementsByClassName('al'+rpta[i].student_id)[0].value = rpta[i].estado;
			}
		}
	});
}


