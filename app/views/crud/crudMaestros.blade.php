@extends('layouts.master')
@section('title')
Catalogo de Maestros
@stop
@section('content')

<ul id="lista" class="nav nav-tabs">
	<li class="active"><a href="listas?id=0">Maestros</a></li>
	<li><a href="listas?id=1">Materias</a></li>
	<li><a href="listas?id=2">Aulas</a></li>
	<li><a href="listas?id=3">Planes</a></li>
</ul>

@if(!isset($hola))
<a href="showForm?id=0">Agregar Registro</a>
@endif

@if(isset($hola))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/add'))}}

			{{Form::label('clave', 'Clave')}}
			{{Form::text('clave','', array('id'=>'claveTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('ap_pat', 'Apellido Paterno')}}
			{{Form::text('ap_pat','', array('id'=>'ap_patTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('ap_mat', 'Apellido Materno')}}
			{{Form::text('ap_mat','', array('id'=>'ap_matTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('nombre', 'Nombre')}}
			{{Form::text('nombre','', array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('seg_nombre', 'Segundo Nombre')}}
			{{Form::text('seg_nombre','', array('id'=>'seg_nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('tipo', 'Tipo')}}
			{{Form::text('tipo','', array('id'=>'tipoTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('grado', 'Grado')}}
			{{Form::text('grado','', array('id'=>'gradoTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('tutorias', 'Tutorias')}}
			{{Form::text('tutorias','', array('id'=>'tutoriasTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('gestion', 'Gestion')}}
			{{Form::text('gestion','', array('id'=>'gestionTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('invest', 'Investigacion')}}
			{{Form::text('invest','', array('id'=>'investTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('depend', 'Dependencias')}}
			{{Form::text('depend','', array('id'=>'dependTxt', 'class' => 'form-control'))}}
		</div>
			<br>
			<br>

			{{Form::hidden('tabla','0')}}

			{{Form::submit('Agregar', array('class'=>'btn btn-default'))}}
		</div>
	</div>
	</fieldset>
@endif

@if(isset($txTprofessors))
	<div class="col-md-12">
		<div class="divCrud col-md-3">
			{{Form::open(array('method' => 'POST', 'url' => 'crud/update'))}}

			{{Form::label('clave', 'Clave')}}
			{{Form::text('clave', $txTprofessors['clave'], array('id'=>'claveTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('ap_pat', 'Apellido Paterno')}}
			{{Form::text('ap_pat', $txTprofessors['ap_pat'], array('id'=>'ap_patTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('ap_mat', 'Apellido Materno')}}
			{{Form::text('ap_mat', $txTprofessors['ap_mat'], array('id'=>'ap_matTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('nombre', 'Primer Nombre')}}
			{{Form::text('nombre', $txTprofessors['nombre'], array('id'=>'nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('seg_nombre', 'Segundo Nombre')}}
			{{Form::text('seg_nombre', $txTprofessors['seg_nombre'], array('id'=>'seg_nombreTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('tipo', 'Tipo')}}
			{{Form::text('tipo', $txTprofessors['tipo'], array('id'=>'tipoTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('grado', 'Grado')}}
			{{Form::text('grado', $txTprofessors['id_grado'], array('id'=>'gradoTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('tutorias', 'Tutorias')}}
			{{Form::text('tutorias', $txTprofessors['tutorias'], array('id'=>'tutoriasTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('gestion', 'Gestion')}}
			{{Form::text('gestion', $txTprofessors['gestion'], array('id'=>'gestionTxt', 'class' => 'form-control'))}}
			<br>
		</div>
		<div class="col-md-3">
			{{Form::label('invest', 'Investigacion')}}
			{{Form::text('invest', $txTprofessors['investigacion'], array('id'=>'investTxt', 'class' => 'form-control'))}}
			<br>

			{{Form::label('depend', 'Dependencias')}}
			{{Form::text('depend', $txTprofessors['dependencias'], array('id'=>'dependTxt', 'class' => 'form-control'))}}
			<br>
		</div>
			{{Form::hidden('id',$txTprofessors['id'])}}
			<br>
			{{Form::hidden('tabla','0')}}
			<br>
			{{Form::submit('Editar', array('class'=>'btn btn-default'))}}
		</div>
	</div>
@endif

<table border="1" class="table table-bordered table-striped table-responsive table-hover">
	<thead>
		<tr>
			<th>Id</th>
			<th>Clave</th>
			<th>Apellido Paterno</th>
			<th>Apellido Materno</th>
			<th>Primer Nombre</th>
			<th>Segundo Nombre</th>
			<th>Tipo</th>
			<th>Grado</th>
			<th>Tutorias</th>
			<th>Gestion</th>
			<th>Invest</th>
			<th>Depend</th>
			<th>Opcciones</th>	
		</tr>
	</thead>
	<tbody>
		@foreach($professors as $prof)
			<tr>
				<td>{{$prof['id']}}</td>
				<td>{{$prof['clave']}}</td>
				<td>{{$prof['ap_pat']}}</td>
				<td>{{$prof['ap_mat']}}</td>
				<td>{{$prof['nombre']}}</td>
				<td>{{$prof['seg_nombre']}}</td>
				<td>{{$prof['tipo']}}</td>
				<td>{{$prof['id_grado']}}</td>
				<td>{{$prof['tutorias']}}</td>
				<td>{{$prof['gestion']}}</td>
				<td>{{$prof['investigacion']}}</td>
				<td>{{$prof['dependencias']}}</td>
				<td>
					<div class="btn-group">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"> Opciones <span class="caret"></span> </a> <!-- hara que el boton tenga una flechita a lado pa dar antender que es desplegable -->
							<ul class="dropdown-menu">
								<li><a href="delete?id={{$prof['id']}}&tabla=0" class="delete">Eliminar</a></li>
								<li><a href="edit?id={{$prof['id']}}&tabla=0" class="Edit">Editar</a></li>
							</ul>
					</div>
					<!-- <a href="delete?id={{$prof['id']}}&tabla=0" class="delete">Eliminar</a>
					<a href="edit?id={{$prof['id']}}&tabla=0" class="Edit">Editar</a> -->
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