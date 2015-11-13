@extends('layouts.master')
@section('title')
Crud
@stop
@section('content')

<ul id="lista" class="nav nav-tabs">
	<li class="active"><a href="listas?id=0">Maestros</a></li>
	<li><a href="listas?id=1">Materias</a></li>
	<li><a href="listas?id=2">Aulas</a></li>
	<li><a href="listas?id=3">Planes</a></li>
</ul>

@stop 