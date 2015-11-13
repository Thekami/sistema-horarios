@extends('layouts.master')
@section('title')
Catalogo de Materias
@stop
@section('content')


  <div class="row-flid">
 	<div class="span4">
		<ul id="lista" class="nav nav-tabs">
			<li><a href="listas?id=0">Maestros</a></li>
			<li class="active"><a href="listas?id=1">Materias</a></li>
			<li><a href="listas?id=2">Aulas</a></li>
			<li><a href="listas?id=3">Planes</a></li>
		</ul>
	</div>
</div>

@if(!isset($hola))
<a href="showForm?id=1">Agregar Registro</a>
@endif

@if(isset($hola))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/add'))}}

			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre', '', array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('semestre', 'Semestre')}}
			{{Form::text('semestre','', array('id'=>'semestreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('idPlan', 'IdPlan')}}
			{{Form::text('idPlan','', array('id'=>'idPlanTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::hidden('tabla','1')}}

			{{Form::submit('Agregar', array('class'=>'btn btn-default'))}}
		</div>
	</div>
@endif

@if(isset($txTsubjects))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/update'))}}
			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre', $txTsubjects['nombre'], array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('semestre', 'Semestre')}}
			{{Form::text('semestre', $txTsubjects['semestre'], array('id'=>'semestreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('idPlan', 'IdPlan')}}
			{{Form::text('idPlan', $txTsubjects['id_plan'], array('id'=>'idPlanTxt', 'class' => 'form-control'))}}
			<br>
			{{Form::submit('Editar', array('class'=>'btn btn-default'))}}
			{{Form::hidden('id',$txTsubjects['id'])}}
			{{Form::hidden('tabla','1')}}

		</div>
	</div>
@endif

<table class="table table-bordered table-striped table-responsive table-hover">
	<thead>
		<th>Id</th>
		<th>Nombre</th>
		<th>Semestre</th>
		<th>IdPlan</th>
		<th>Opcciones</th>	
	</thead>
	<tbody>
	@foreach($subjects as $sub)
		<tr>
			<td>{{$sub['id']}}</td>
			<td>{{$sub['nombre']}}</td>
			<td>{{$sub['semestre']}}</td>
			<td>{{$sub['id_plan']}}</td>
			<td>
				<div class="btn-group">
				<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a> <!-- hara que el boton tenga una flechita a lado pa dar antender que es desplegable -->
					<ul class="dropdown-menu">
						<li><a href="delete?id={{$sub['id']}}&tabla=1" class="delete">Eliminar</a></li>
						<li><a href="edit?id={{$sub['id']}}&tabla=1" class="Edit">Editar</a></li>
					</ul>
				</div>
				<!-- <a href="delete?id={{$sub['id']}}&tabla=1" class="delete">Eliminar</a>
				<a href="edit?id={{$sub['id']}}&tabla=1" class="Edit">Editar</a> -->
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