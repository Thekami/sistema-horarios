@extends('layouts.master')
@section('title')
Generar Plantilla
@stop
@section('styles')
<link rel="stylesheet" href="{{URL::asset('css/misc.css')}}">
@stop

@section('content')
{{Form::open(['url'=>'proyectos/agregar-grupo', 'method'=>'post', 'class'=>'form-inline'])}}
<input id="idProyecto" name="idProyecto" type="hidden" value="{{$id}}">
{{Form::label('planes','Plan: ','')}}
<select name="plan" id="planes" class="form-control">
	<option value="">- - -</option>
	@foreach ($planes as $plan)
		<option value="{{$plan->id}}">{{$plan->nombre}}</option>
	@endforeach
</select>
{{Form::label('semester','Semestre: ','')}}
<select name="semester" id="semester" class="form-control">
	<option value="">- - -</option>
	@for($i=0; $i<sizeof($semestres); $i++)
		<option value="{{$semestres[$i]}}">{{$semestres[$i]}}</option>
	@endfor
</select>
{{Form::label('group','Grupo: ','')}}
{{Form::text('group','',['placeholder'=> 'Ej. A, B, C, D', 'required', 'title' =>' ¡Sólo se permiten letras!', 'class'=>'form-control'])}}
{{Form::submit('Agregar', ['class'=>'btn btn-primary form-control'])}}
{{Form::close()}}
<br>
<p>Para añadir múltiples grupos, ingréselos separados por coma. <br>
	Ejemplo: A,B,C,D
</p>
<table class="table table-bordered table-striped table-responsive table-hover">
	<tr>
		<td>Plan</td>
		<td>Semestre</td>
		<td>Grupo</td>
		<td>Opciones</td>
	</tr>
	@foreach($plantillas as $plantilla)
		<tr>
			<td>{{$plantilla['plan']}}</td>
			<td>{{$plantilla['semestre']}}</td>
			<td>{{$plantilla['grupo']}}</td>
			<td>
				<div class="btn-group">
					<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a> <!-- hara que el boton tenga una flechita a lado pa dar antender que es desplegable -->
						<ul class="dropdown-menu">
							<li><a href="editar-grupo?g={{$plantilla['grupo']}}&pl={{$plantilla['plan']}}&s={{$plantilla['semestre']}}&pid={{$id}}">Editar</a>
							</li>
							<li><a href="eliminar-grupo?g={{$plantilla['grupo']}}&pl={{$plantilla['plan']}}&s={{$plantilla['semestre']}}&pid={{$id}}">Eliminar</a>
							</li>
						</ul>
				</div>
			</td>
		</tr>
	@endforeach
</table>
<div>
	@if(!null==$errors->all())
		@foreach($errors->all() as $error)
			{{$error}} <br>
		@endforeach
	@endif
</div>
@stop

@section('scripts')
<script src="{{URL::asset('js/funciones.js')}}"></script>
@stop