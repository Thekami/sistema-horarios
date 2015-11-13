@extends('layouts.iniMaster')
@section('title')
Log In
@stop
@section('content')



<!-- comienza el modal -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModal" hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <!-- comienza el header del modal -->
        <h3 class="modal-title" id="myModalLabel">Inico de Sesion</h3>
      </div>
      <div class="modal-body"> <!-- comienza el body del modal -->
  		{{Form::open(array('method' => 'POST', 'class' => 'form-signin'))}} <!-- comienza mi formulario de login -->
			{{ Form::text('username','',  array('placeholder'=>'Username', 'class' => 'form-control', 'id'=>'username', 'autofocus')) }}
			<br>
			
			{{ Form::password('password', array('placeholder' => 'Password', "class" => 'form-control', 'id'=>'password')) }}
			<br>
			
			{{Form::checkbox('','')}}
			<br>
			{{Form::submit('Go', array('class' => 'btn btn-default2','id'=>'loginButton' ))}}
		{{Form::close()}} <!-- termina formulario de login y el body del modal -->
      </div>
      <div class="modal-footer" id="loginMsg" style="display:none;">
      	<div class="alert alert-danger">
	    	<button type="button" class="close" onclick="$('#loginMsg').slideUp(400);">&times;</button>
	    	<h3>Error!!</h3>
	    	<p id="msgText"></p>
		</div>
      </div>
    </div>
  </div>
</div>

@stop

@section('scripts')
<script src="{{URL::asset('js/funcionesCrud.js')}}"></script>
@stop






