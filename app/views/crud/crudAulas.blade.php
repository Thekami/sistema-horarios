@extends('layouts.master')
@section('title')
Catalogo de Aulas
@stop
@section('content')


<ul id="lista" class="nav nav-tabs">
	<li><a href="listas?id=0">Maestros</a></li>
	<li><a href="listas?id=1">Materias</a></li>
	<li class="active"><a href="listas?id=2">Aulas</a></li>
	<li><a href="listas?id=3">Planes</a></li>
</ul>

@if(!isset($hola))
<a href="showForm?id=2" class="btn">Agregar registro</a>
@endif

@if(isset($hola))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/add'))}}
			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre','', array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::hidden('tabla','2')}}
			
			{{Form::submit('Agregar', array('class'=>'btn btn-default'))}}
		</div>
	</div>
@endif

@if(isset($txTaulas))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/update'))}}
			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre', $txTaulas['nombre'], array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::submit('Editar', array('class'=>'btn btn-default'))}}
			{{Form::hidden('id',$txTaulas['id'])}}
			{{Form::hidden('tabla','2')}}

		</div>
	</div>
@endif

<table border="1" class="table table-bordered table-striped table-responsive table-hover">
	<thead>
		<th>Id</th>
		<th>Nombre</th>
		<th>Opcciones</th>	
	</thead>
	<tbody>
	@foreach($aulas as $aula)
		<tr>
			<td>{{$aula['id']}}</td>
			<td>{{$aula['nombre']}}</td>
			<td>
				<div class="btn-group">
					<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a> <!-- hara que el boton tenga una flechita a lado pa dar antender que es desplegable -->
						<ul class="dropdown-menu">
							<li><a href="delete?id={{$aula['id']}}&tabla=2" class="delete">Eliminar</a></li>
							<li><a href="edit?id={{$aula['id']}}&tabla=2" class="Edit">Editar</a></li>
						</ul>
				</div>
			<!-- 	<a href="delete?id={{$aula['id']}}&tabla=2" class="delete">Eliminar</a>
				<a href="edit?id={{$aula['id']}}&tabla=2" class="Edit">Editar</a> -->
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