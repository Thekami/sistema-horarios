<!doctype html>
<html lang="es-mx">
<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}">
	<title> @yield('title') </title>
	<link rel="stylesheet" href="{{URL::asset('css/bootstrap.css')}}">
	@yield('styles')
</head>
<body>
	<div class="container">
		@section('navbar')

		<nav class="navbar navbar-default navbar-fixed-top navbar-inverse" id="navbar">
		  <div class="container">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="{{URL::to('/inicio')}}">Horarios Telematica</a>
		    </div>

		    <div class="collapse navbar-collapse">
		      <ul class="nav navbar-nav navbar-right">
		      	<li><a href="{{URL::to('/crud/registro')}}">Registro</a></li>
		      	<li><a href="{{URL::to('/crud/catalogo')}}">Catalogos</a></li>
		      	<li><a href="{{URL::to('/proyectos')}}">Proyectos</a></li>
		        
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{Auth::user()->username}}<span class="caret"></span></a>
		          <ul class="dropdown-menu" role="menu">
		            <li><a href="#">Reportes</a></li>
		            <li><a href="#">Cambiar Informacion</a></li>
		            <li class="divider"></li>
		            <li><a href="{{ URL::to('/logout') }}">Cerrar Sesion</a></li>
		          </ul>
		        </li>

		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>

		@show
		<div class="row-fluid">
			
				@yield('content')
			
		</div>
		
		<!--<footer style="position:absolute;">
			<hr>
			<p>&copy {{/*date('Y')*/""}}</p>
		</footer>-->
	</div>
	<script src="{{URL::asset('js/jquery-1.11.1.min.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
	@yield('scripts')
</body>
</html>