@extends('layouts.master')
@section('title')
Registro
@stop
@section('content')

<style type="text/css">
	body{background-color: #f5f5f5;}
</style>

<div class ="divLogin">


{{Form::open(array('method' => 'POST', 'url' => 'crud/crearUser', 'class' => 'form-signin'))}}
	<h2 class="form-signin-heading">Registro</h2>

	{{ Form::text('username','',  array('placeholder'=>'Username', 'class' => 'form-control')) }}
	<br>
	
	{{ Form::password('password', array('placeholder' => 'Password', "class" => 'form-control')) }}
	<br>
	
	{{Form::submit('Registrarse', array('class' => 'btn btn-default' ))}}
{{Form::close()}}

@if(isset($msg))
<h4>{{$msg}}</h4>
@endif


</div>

@stop