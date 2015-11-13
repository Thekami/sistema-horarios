@extends('layouts.master')
@section('title')
Catalogo de Planes
@stop
@section('content')

<ul id="lista" class="nav nav-tabs">
	<li><a href="listas?id=0">Maestros</a></li>
	<li><a href="listas?id=1">Materias</a></li>
	<li><a href="listas?id=2">Aulas</a></li>
	<li class="active"><a href="listas?id=3">Planes</a></li>
</ul>

@if(!isset($hola))
<a href="showForm?id=3">Agregar registro</a>
@endif

@if(isset($hola))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/add'))}}
			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre','', array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::submit('Agregar',array('class'=>'btn btn-default'))}}
			{{Form::hidden('tabla','3')}}
		</div>
	</div>
@endif

@if(isset($txTplans))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/update'))}}

			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre', $txTplans['nombre'], array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::submit('Editar', array('class'=>'btn btn-default'))}}
			{{Form::hidden('id',$txTplans['id'])}}
			{{Form::hidden('tabla','3')}}
		</div>
	</div>
@endif

<table class= "table table-bordered table-striped table-responsive">
	<thead>
		<th>Id</th>
		<th>Nombre</th>
		<th>Opcciones</th>	
	</thead>
	<tbody>
	@foreach($plans as $plan)
		<tr>
			<td>{{$plan['id']}}</td>
			<td>{{$plan['nombre']}}</td>
			<td>
				<div class="btn-group">
					<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a> <!-- hara que el boton tenga una flechita a lado pa dar antender que es desplegable -->
						<ul class="dropdown-menu">
							<li><a href="delete?id={{$plan['id']}}&tabla=3" class="delete">Eliminar</a></li>
							<li><a href="edit?id={{$plan['id']}}&tabla=3" class="Edit">Editar</a></li>
						</ul>
				</div>
				<!-- <a href="delete?id={{$plan['id']}}&tabla=3" class="delete">Eliminar</a>
				<a href="edit?id={{$plan['id']}}&tabla=3" class="Edit">Editar</a> -->
			</td>
		</tr>
	@endforeach
	</tbody>
</table>

@stop 

@section('scripts')
<script src="{{URL::asset('js/datatables/datatables.js')}}"></script>
<script>$(document).ready(function() {
	$('.table').dataTable();
});</script>
@stop