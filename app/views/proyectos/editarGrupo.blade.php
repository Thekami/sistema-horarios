@extends('layouts.master')

@section('title')

Editando grupo {{Input::get('g')}}...

@stop

@section('styles')

<link rel="stylesheet" href="{{URL::asset('packages/chosen/chosen.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('css/bipo/edit-group.css')}}">

@stop


@section('content')
<input type="hidden" id="actual" value="">
<input type="hidden" id="chkd" value="0">
<input type="hidden" id="grupo" value="{{$data[0]['grupo']}}">
<input type="hidden" id="ocupadas" value="">
<input type="hidden" id="idProyecto" value="{{$data[0]['idProyecto']}}">
<div id="information">
	{{$data[0]['ciclo']."\t".$data[0]['anio']."\t".$data[0]['grupo']."\t".$data[0]['plan']}}
	<span class="btn" unselectable="on" id="guardar" onclick="Guardar();">Guardar</span>
	<span class="btn" unselectable="on" id="salir" onclick="Salir();" data-val="0">Salir</span>
</div>

	<div id="materias" class="col-md-6">
		<table class="table-responsive table-hover ">
			<tr>
				<td></td>
				<td>Materia</td>
				<td>Profesor</td>
			</tr>
		@foreach($data as $materia)
			<tr class="group-data">
				<td><input type="radio" value="{{$materia['id']}}" name="seleccion[]" onchange="CambioRegistro(this.value, $(this));" class="radios"></td>
				<td>{{$materia['materia']}}</td>
				<td style="text-align: center;">
					<select data-placeholder="Seleccione un profesor..." style="width:80%;" class="chosen-select prof-select" id="prof{{$materia['id']}}" onchange="if($('#chkd').val() == 1){CheckProf(this.value, 1);}else{$('.alert').addClass('alert-error').html('¡Ocurrió un error!<br> Debe seleccionar una materia primero.').slideDown(300).delay(2500).slideUp(200);}">
					<option value="0"></option>
						<?php $i=1; ?>
						@foreach($profesores as $profesor)
							<?php $nombre = $profesor['ap_pat']." ".$profesor['ap_mat']." ".$profesor['nombre']." ".$profesor['seg_nombre'];?>
							@if($materia['id_profesor'] == $i)
								<option selected="selected" value="{{$profesor['id']}}">{{$nombre}}</option>
							@else
								<option value="{{$profesor['id']}}">{{$nombre}}</option>
							@endif
							<?php $i++; ?>
						@endforeach
					</select>
				</td>
			</tr>
		@endforeach
		</table>
	</div>


	<div id="comparacion" class="col-md-6">
		<div id="agregar">
			<select data-placeholder="Seleccione un profesor..." style="width:80%;" class="chosen-select comparar" id="prof">
				<option value="0"></option>
				@foreach($profesores as $profesor)
					<?php $nombre = $profesor['ap_pat']." ".$profesor['ap_mat']." ".$profesor['nombre']." ".$profesor['seg_nombre'];?>
					<option value="{{$profesor['id']}}">{{$nombre}}</option>
				@endforeach
			</select>
			<span unselectable="on" class="addButton" onclick="AgregarComparacion($('#prof').val(), 1);">+</span>
			<select data-placeholder="Grupo" style="width:75px;" class="chosen-select comparar" id="gp">
				<option value="0"></option>
				@foreach($grupos as $grupo)
					<option value="{{$grupo['grupo']}}">{{$grupo['grupo']}}</option>
				@endforeach
			</select>
			<span unselectable="on" class="addButton" onclick="AgregarComparacion($('#gp').val(), 2);">+</span>
			<select data-placeholder="Aula" style="width:140px;" class="chosen-select comparar" id="aula">
				<option value="0"></option>
				@foreach($aulas as $aula)
					<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
				@endforeach
			</select>
			<span unselectable="on" class="addButton" onclick="AgregarComparacion($('#aula').val(), 3);">+</span>
		</div>
		<div id="info-variada">
		</div>
	</div>



	<div id="prof-seleccionado" style="margin-right:4.8%;">
		<div>
			<h3>Información del profesor seleccionado</h3>
			<p id="prof-actual">
				Profesor: N/A <br>
				Grupos asignados: N/A<br>
				Horas totales asignadas: N/A
			</p>
		</div>
	</div>



<div id="agregar-lugar" style="margin-right:4.5%;">
	<table class="table table-bordered table-striped table-responsive table-hover">
		<tr>
			<td>Día</td>
			<td>Aula</td>
			<td>Hora Inicio</td>
			<td>Hora Fin</td>
		</tr>
		<tr>
			<td>Lunes</td>
			<td>
				<select id="lunAula" data-dia="0" style="width:140px;" class="aulaHoras" onchange="CheckHoras(this.value, $(this).data('dia'));">
					@foreach($aulas as $aula)
						<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
					@endforeach
				</select>
			</td>
			<td><select id="lunInicio" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
			<td><select id="lunFin" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
		</tr>
		<tr>
			<td>Martes</td>
			<td>
				<select id="marAula" data-dia="1" style="width:140px;" class="aulaHoras" onchange="CheckHoras(this.value, $(this).data('dia'));">
					@foreach($aulas as $aula)
						<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
					@endforeach
				</select>
			</td>
			<td><select id="marInicio" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
			<td><select id="marFin" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
		</tr>
		<tr>
			<td>Miércoles</td>
			<td>
				<select id="mierAula" data-dia="2" style="width:140px;" class="aulaHoras" onchange="CheckHoras(this.value, $(this).data('dia'));">
					@foreach($aulas as $aula)
						<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
					@endforeach
				</select>
			</td>
			<td><select id="mierInicio" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
			<td><select id="mierFin" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
		</tr>
		<tr>
			<td>Jueves</td>
			<td>
				<select id="jueAula" data-dia="3" style="width:140px;" class="aulaHoras" onchange="CheckHoras(this.value, $(this).data('dia'));">
					@foreach($aulas as $aula)
						<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
					@endforeach
				</select>
			</td>
			<td><select id="jueInicio" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
			<td><select id="jueFin" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
		</tr>
		<tr>
			<td>Viernes</td>
			<td>
				<select id="vieAula" data-dia="4" style="width:140px;" class="aulaHoras" onchange="CheckHoras(this.value, $(this).data('dia'));">
					@foreach($aulas as $aula)
						<option value="{{$aula['id']}}">{{$aula['nombre']}}</option>
					@endforeach
				</select>
			</td>
			<td><select id="vieInicio" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
			<td><select id="vieFin" class="aulaHoras" style="width:100px;">
				<option value="0"></option>
			</select></td>
		</tr>
	</table>
</div>

<!--

	-->


<div id="horario-actual">
	<div><h2>Horario actual del grupo {{Input::get('g')}}</h2></div>
	<table class="table table-bordered table-striped table-responsive table-hover"></table>
</div>
@stop

@section('scripts')
<script src="{{URL::asset('packages/chosen/chosen.jquery.min.js')}}"></script>
<script src="{{URL::asset('js/funciones.js')}}"></script>
<script>$(document).ready(function() {
	$('.chosen-select').chosen({no_results_text: "No se encontró el profesor"});
	$(document).on('change', 'select', function(event) {
		var id = "#" + this.id + "_chosen";
		if($(this).val() == 0){
			$(this).removeClass('edited');
			$(id).removeClass('edited');
		}
		else{
				if(!$(this).hasClass('edited') && !$(this).hasClass('comparar') ){
				$(this).addClass('edited');
				$(id).addClass('edited');
			}
		}
	});
});
</script>

@stop

@section('scripts')
<script src="{{URL::asset('js/global.js')}}"></script>
<script src="{{URL::asset('js/funciones.js')}}"></script>
@stop