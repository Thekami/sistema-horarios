@extends('layouts.master')
@section('title')
Proyectos
@stop

@section('styles')
<link rel="stylesheet" href="{{URL::asset('css/misc.css')}}">
@stop

@section('content')
{{Form::button('Nuevo Proyecto', ['id'=>'btnMostrar','class'=>'btn btn-success pull-right', 'onclick'=>'mostrarForm();'])}}
{{Form::open(['url'=>'/proyectos/crear-proyecto', 'method'=>'post', 'class'=>'form-inline', 'id'=>'formCrear'])}}
{{Form::label('cicle','Ciclo Escolar: ','', ['class'=>'control-label'])}}
<select name="cicle" id="cicle" class="form-control">
	<option value="">- - -</option>
	<option value="1">Agosto-Enero</option>
	<option value="2">Enero-Julio</option>
</select>
{{Form::label('year','Año: ','')}}
{{Form::text('year','',['placeholder'=>'Ej. 2014', 'pattern' => '[0-9]*$', 'required', 'title' => '¡Debe ser un número!', 'class' =>'form-control'])}}
{{Form::submit('Agregar', ['class'=>'btn btn-primary form-control'])}}
{{Form::close()}}
<br><br><br>
<table id="tablaProyectos" class="table table-bordered table-striped table-responsive table-hover">
	<tr>
		<td>Ciclo</td>
		<td>Año</td>
		<td>Opciones</td>
	</tr>
	@foreach($proyectos as $proyecto)
		<tr>
			<td>{{$proyecto['ciclo']}}</td>
			<td>{{$proyecto['anio']}}</td>
			<td>
				<div class="btn-group">
					<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a>
						<ul class="dropdown-menu">
							<li><a href="proyectos/editar-proyecto?p={{$proyecto['id']}}">Editar</a>
							</li>
							<li><a href="proyectos/eliminar-proyecto?p={{$proyecto['id']}}">Eliminar</a>
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
<div class="alert"></div>
@stop

@section('scripts')
<script src="{{URL::asset('js/global.js')}}"></script>
<script src="{{URL::asset('js/proyectos.js')}}"></script>
@stop