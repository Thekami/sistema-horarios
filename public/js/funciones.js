//Se genera una matriz que servirá para el desplegado de horarios
var matriz = new Array(17);
$(document).ready(function() {
	//Se llena la matriz de datos vacíos
	for(i=0; i<17; i++){
		matriz[i]= new Array(6);
		for(k=0; k<6; k++){
			matriz[i][k]='';
		}
	}
	//Se añaden las cabeceras
	matriz[0][0]='Hora';
	matriz[0][1]='Lunes';
	matriz[0][2]='Martes';
	matriz[0][3]='Miércoles';
	matriz[0][4]='Jueves';
	matriz[0][5]='Viernes';
	//Se obtienen las horas de la base de datos y se añaden a la matriz
	$.ajax({
		url:'../ajax/dias-horas',
		type:'get',
		dataType:'json',
		success: function(data){
			var horario;
			for(i=1; i<17; i++){
				matriz[i][0]= data[i].id + " ("+data[i].hora+")";
			}
			CheckHorario($('#grupo').val(),1);
		}
	});
});

//Acciones

//Añade información sobre profesor, aula o grupo a la ventana de comparación.
function AgregarComparacion(valor, tipo){
	//Se guardan los datos de la ventana para concatenar la nueva información.
	var html = $('#info-variada').html();
	if(tipo == 2 || tipo == 3){ //Si es aula o grupo, se limpia la matriz.
		for(i=1; i<17; i++){
			for(k=1; k<6; k++){
				matriz[i][k]='';
			}
		}
	}
	switch(tipo){
		case 1: //Profesor
		//Se obtiene la información del profesor seleccionado en el presente año y ciclo escolar
			$.ajax({
				url:'../ajax/comparar-profesor',
				type:'get',
				dataType:'json',
				data:{idProf:valor, idProyecto:$('#idProyecto').val()},
				success: function(data){
					//Estructura básica de la información
					html +='<div class="comp-prof"><span class="close" onclick="$(this).parent().remove();">X</span>'+
					'<div>'+
					'<h3>Información del profesor seleccionado</h3>'+
					'<p>'+'Profesor: ';
					html += data[0] +'<br>'+
								"Grupos asignados: ";
					if(data[1].length < 1)
						html+='<br>';
					else{
						for(i=0; i<data[1].length; i++){
							html += data[1][i];
							//Si ya no hay grupos por agregar, se añade un salto de línea
							(i == (data[1].length-1)) ? html+='<br>' : html+=", ";
						}
					}
					html+= "Horas totales asignadas: " + data[2]+
						'</p>'+
					'</div>';
					$('#info-variada').html(html); //Se muestra la información en la ventana de comparación
				},
				error: function(){
					ShowAlert(2, "¡Ocurrió un error!<br> Intente de nuevo más tarde.");
				}
			});
		break;

		case 2: //Grupo
			CheckHorario(valor, 2);
		break;

		case 3: //Aula
		//Obtiene las horas libres del aula seleccionada en el presente año y ciclo escolar
			$.ajax({
				url:'../ajax/comparar-aula',
				type:'get',
				dataType:'json',
				data:{id:valor,anio:$('#anio').val(), ciclo:$('#ciclo').val()},
				success: function(data){
					html+= '<div class="comp-aula"><span class="close" onclick="$(this).parent().remove();">X</span>';
					html+= '<h3>Horario de '+ data[1].nombre+'</h3><table class="table table-bordered table-striped table-hover">';
					for(i=0; i<data[0].length; i++){
						if(data[0][i].hora_inicio == data[0][i].hora_fin){
							//Recorre la matriz y va añadiendo el grupo que ocupa la hora
							matriz[data[0][i].hora_fin][data[0][i].dia+1] = 'Grupo '+ data[0][i].grupo;
						}
						else{
							//En caso de ser más de 1 hora seguida de la misma materia, en el mismo lugar
							var diferencia = parseInt(data[0][i].hora_fin) - parseInt(data[0][i].hora_inicio);
							for(k=0; k<=diferencia; k++){
								//Se muestra el grupo que ocupa esas horas
								matriz[data[0][i].hora_inicio+k][data[0][i].dia+1] = 'Grupo '+ data[0][i].grupo;
							}
						}
					}
					//Se concatena la tabla del horario del aula.
					for(i=0; i<17; i++){
						html+='<tr>';
						for(k=0; k<6; k++){
							html+='<td>'+matriz[i][k]+'</td>';
						}
						html+='</tr>';
					}
					html+='</table></div>';
					$('#info-variada').html(html); //Se muestra la información en la ventana de comparación
				},
				error: function(){
					ShowAlert(2, "¡Ocurrió un error!<br> Intente de nuevo más tarde.");
				}
			});
		break;
	}
}
//Realiza el cambio de los datos de acuerdo al profesor seleccionado.
function CambioRegistro(idRegistro, radio){
	$('#chkd').val(1); //Al hacer click en un radio button, se añade el valor global chkd que indica que se ha seleccionado
	$('.aulaHoras').show(400);
	var prof= '#prof' + idRegistro;
	var row = radio.parent().parent(); //Se obtiene la fila del profesor seleccionado.
	var idProf = $(prof).val();
	$('#actual').val(idRegistro);

	if(row.hasClass('group-data')){
		//Se añade la clase selected a la fila seleccionada
		row.removeClass('group-data').addClass('selected');
		//Se mueve el horario unos pixeles hacia arriba
		$('.group-data').hide(500, function(){$('#horario-actual').css('top', '600px');});
		$('#salir').html('Atrás');
		$('#salir').data('val', 1); //Ahora el botón salir se convierte en "Atrás"
		$('#guardar').show(0);
	}
	else {
		row.removeClass('selected').addClass('group-data');
	}
	 if(idProf != 0){
		CheckProf(idProf);
		CheckHorasProfesor(idProf, $('#grupo').val(), $('#idProyecto').val());
	}
	else{
		$('#prof-actual').html("Profesor: N/A <br> Grupos asignados: N/A <br> Horas totales asignadas: N/A");
	}
}

function Guardar(){
	var profCmb = "#prof" + $('#actual').val();
	var idProf = $(profCmb).val();
	var idReg = $('#actual').val();
	var ocupadas = $('#ocupadas').val();	//Obtiene las horas ocupadas por el registro a editar.

	//Carga un JSON con los días de la semana.
	var horasDia ={
		lun:[0],
		mar:[1],
		mier:[2],
		jue:[3],
		vie:[4]
	};
	//Por cada día se añade el aula y horas de inicio y fin seleccionadas.
	horasDia.lun.push($('#lunAula').val());
	horasDia.lun.push($('#lunInicio').val());
	horasDia.lun.push($('#lunFin').val());
	horasDia.mar.push($('#marAula').val());
	horasDia.mar.push($('#marInicio').val());
	horasDia.mar.push($('#marFin').val());
	horasDia.mier.push($('#mierAula').val());
	horasDia.mier.push($('#mierInicio').val());
	horasDia.mier.push($('#mierFin').val());
	horasDia.jue.push($('#jueAula').val());
	horasDia.jue.push($('#jueInicio').val());
	horasDia.jue.push($('#jueFin').val());
	horasDia.vie.push($('#vieAula').val());
	horasDia.vie.push($('#vieInicio').val());
	horasDia.vie.push($('#vieFin').val());

	console.log(horasDia.lun[2] + " "+ horasDia.lun[3]);
	if((horasDia.lun[2] > horasDia.lun[3]) || (horasDia.mar[2] > horasDia.mar[3]) || (horasDia.mier[2] > horasDia.mier[3]) ||
		(horasDia.jue[2] > horasDia.jue[3]) || (horasDia.vie[2] > horasDia.vie[3])){
		alert("Error");
		return;
	}
	//Al guardar, se remueven las clases 'edited' de todos los objetos que fueron modificados
	else{
		$.ajax({
			url:'../ajax/guardar-cambios',
			type:'get',
			data:{idProf:idProf, idReg:idReg, horasDia:horasDia, ocupadas:ocupadas},
			success: function(){
				$('.edited').removeClass('edited');
				CheckProf(idProf); //Actualiza los datos del profesor seleccionado
				CheckHorario($('#grupo').val(), 1); //Actualiza el horario actual del grupo
				ShowAlert(1, "¡Guardado!");
			},
			error: function(){
				ShowAlert(2, "¡Ocurrió un error!<br> Intente de nuevo más tarde.");
			}
		});
	}
}

function Salir(){
	var idProyecto = $('#idProyecto').val();
	if($('#salir').data('val') === 0) //Si el botón es "Salir", regresa a la ventana de plantillas.
		window.location='editar-proyecto?p='+idProyecto;
	//Si el botón es "Atrás", revisa si hay cambios sin guardar y pregunta si se desea continuar y perder los cambios.
	else{
		if($('select').hasClass('edited')){
			if(!confirm("Si continúa se perderan los cambios.\n¿Desea continuar?")){
				return;
			}
		}
		location.reload(); //Recarga la página simulando la acción "Atrás".
	}
}

//Funciones globales
//Muestra alertas dependiendo del tipo que sea
function ShowAlert(type, text){
	$('.alert').removeClass('alert-success').removeClass('alert-error');
	switch(type){
		//Info...
		case 0:
		$('.alert').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
		//Success
		case 1:
		$('.alert').addClass('alert-success').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
		//Error
		case 2:
			$('.alert').addClass('alert-error').html(text).slideDown(300).delay(2500).slideUp(200);
			break;
	}
}

function CheckProf(idProf){
	$.ajax({
		url:'../ajax/datos-profesor',
		type:'get',
		dataType:'json',
		data:{idProf:idProf, idRegistro:$('#actual').val(), idProyecto:$('#idProyecto').val()},
		success: function(data){
			var info = "Profesor: " + data[0] +'<br>'+
						"Grupos asignados: ";
			if(data[1].length < 1)
				info+='<br>';
			else{
				for(i=0; i<data[1].length; i++){
					info += data[1][i];
					(i == (data[1].length-1)) ? info+='<br>' : info+=", ";
				}
			}
			info+= "Horas totales asignadas: " + data[2];
			$('#prof-actual').html(info);
		},
		error: function(){
			ShowAlert(2, "¡Ocurrió un error!<br> Intente de nuevo más tarde.");
		}
	});
}
//Obtiene las horas disponibles del aula seleccionada y las muestra en sus respectivos combobox
function CheckHoras(idAula, dia){
	var idDia;
	switch(dia){
		case 0:	idDia='#lun'; break;
		case 1:	idDia='#mar'; break;
		case 2:	idDia='#mier'; break;
		case 3:	idDia='#jue'; break;
		case 4:	idDia='#vie'; break;
	}
	$.ajax({
		url:'../ajax/chk-hrs',
		type:'get',
		dataType:'json',
		data:{id:idAula, dia:dia},
		success: function(data){
			hrs='<option value="0"></option>';
			for(i=0; i<data.length; i++){
				if(data[i].disponible == 1)
					hrs+='<option value="'+data[i].hora+'">'+data[i].hora+'</option>';
			}
			temp = idDia + "Inicio";
			$(temp).html(hrs);
			temp = idDia+"Fin";
			$(temp).html(hrs);
		},
		error: function(){
			ShowAlert(2, "¡Ocurrió un error!<br> Intente de nuevo más tarde.");
		}
	});
}
//Obtiene el horario del grupo seleccionado
function CheckHorario(grupo, tipo){
	//Limpia la matriz
	for(i=1; i<17; i++){
		for(k=1; k<6; k++){
			matriz[i][k]='';
		}
	}
	$.ajax({
		url:'../ajax/cargar-horario',
		type:'get',
		dataType:'json',
		data: {grupo:grupo, idProyecto:$('#idProyecto').val()},
		success: function(data){
			var horario;
			//Por cada hora que el grupo tenga asignada se añaden a la tabla: materia y aula en el día y hora que se asignaron.
			for(i=0; i<data.length; i++){
				if(data[i].hora_inicio == data[i].hora_fin){
					matriz[data[i].hora_fin][data[i].dia+1] = data[i].materia+'<br>'+
					data[i].ap_pat + ' ' + data[i].ap_mat + ' ' + data[i].nombre + ' ' + data[i].seg_nombre +
					'<br>'+data[i].aula;
				}
				else{
					var diferencia = parseInt(data[i].hora_fin) - parseInt(data[i].hora_inicio);
					for(k=0; k<=diferencia; k++){
						//Se añaden los datos a la tabla cuando son 2 o más horas seguidas.
						matriz[data[i].hora_inicio+k][data[i].dia+1] = data[i].materia+'<br>'+
						data[i].ap_pat + ' ' + data[i].ap_mat + ' ' + data[i].nombre + ' ' + data[i].seg_nombre +
						'<br>'+data[i].aula;
					}
					matriz[data[i].hora_fin][data[i].dia+1] = data[i].materia+'<br>'+
					data[i].ap_pat + ' ' + data[i].ap_mat + ' ' + data[i].nombre + ' ' + data[i].seg_nombre +
					'<br>'+data[i].aula;
				}
			}
			//Construcción de la estructura de la tabla
			for(i=0; i<17; i++){
				horario+='<tr>';
				for(k=0; k<6; k++){
					horario+='<td>'+matriz[i][k]+'</td>';
				}
				horario+='</tr>';
			}
			//Si el horario se consulta para el grupo actual se muestra en la ventana correspondiente.
			if(tipo==1){
				$('#horario-actual>table').html(horario);
			}
			else{ //En caso de ser para comparación, se muestra en la ventana de comparación.
				html = $('#info-variada').html();
				html+= '<div class="comp-grupo"><span class="close" onclick="$(this).parent().remove();">X</span>';
				html+= '<h3>Horario del grupo '+ data[0].grupo+'</h3><table  class="table table-bordered table-striped table-hover">';
				html+= horario + '</table>';
				$('#info-variada').html(html);
			}
		}
	});
}

/* 
dia: 1
disponible: 0
hora_fin: 2
hora_inicio: 1
id: 737
id_aula: 11
id_plantilla: 6
*/

function CheckHorasProfesor(idProf, grupo, idProyecto){
	var dias = ['lun', 'mar', 'mier', 'jue', 'vie']; //Contiene los prefijos de los combobox.
	var inicio='<option value="0"></option>';		 //Contiene el primer elemento del combobox.
	var fin='<option value="0"></option>';			 //Contiene el primer elemento del combobox
	//Obtiene las horas que están ocupadas por esta materia, en qué aula y los días que le corresponden.
	$.ajax({
		url:'../ajax/check-horas-profesor',
		type:'get',
		dataType:'json',
		data:{grupo:grupo, idProf:idProf, idProyecto:idProyecto},
		success: function(response){
			var data = response[0];	//Datos del aula y horas ocupadas por día.
			var aux = response[1];	//Datos de las horas disponibles en el aula actual.
			var horasOcupadas = response[2];
			$('#ocupadas').val(horasOcupadas);
			//Por cada día...
			for(i in data){
				var dia = data[i].dia;
				$('#'+dias[dia]+'Aula').val(data[i].id_aula);	//Se selecciona el aula ocupada.
				//Se llenan los combobox con las horas disponibles en el aula, por día.
				for(k=0; k<aux.length; k++){
					if(dia == aux[k].dia && aux[k].disponible == 1){	//Se añaden SÓLO LAS HORAS DISPONIBLES.
						inicio += '<option value="'+aux[k].hora+'">'+aux[k].hora+'</option>';
						fin += '<option value="'+aux[k].hora+'">'+aux[k].hora+'</option>';
					}
				}

				$('#'+dias[dia]+'Inicio').html(inicio);
				$('#'+dias[dia]+'Fin').html(fin);
				//Se seleccionan las horas que ocupa la materia a editar en el día y aula correspondiente.
				$('#'+dias[dia]+'Inicio').val(data[i].hora_inicio);
				$('#'+dias[dia]+'Fin').val(data[i].hora_fin);
				inicio='<option value="0"></option>';
				fin='<option value="0"></option>';
			}
		},
		error: function(error){
			console.log("Error!");
			console.log(error.responseText);
		}
	});
}