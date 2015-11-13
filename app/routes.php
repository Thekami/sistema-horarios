<?php
Route::controller('ajax', 'AjaxController'); //Controlador que maneja las llamadas AJAX
Route::get('/', function(){
	return Redirect::to('/inicio');
});

//Proyectos ---------------------------------------------------------------------------------------------
Route::group(['prefix'=>'proyectos', 'before'=>'auth'], function(){
	Route::get('/', 'ProyectosController@index');
	Route::get('/editar-proyecto', 'ProyectosController@editarProyecto');
	Route::any('/crear-proyecto', 'ProyectosController@crearProyecto');
	Route::any('/eliminar-proyecto', 'ProyectosController@eliminarProyecto');
	Route::any('/editar-grupo', 'ProyectosController@editGroup');
	Route::any('/agregar-grupo', 'ProyectosController@agregarGrupos');
	Route::any('/eliminar-grupo', 'ProyectosController@deleteGroup');
});

//Ruta para login ----------------------------------------------------------------------------------------
Route::get('/inicio', function()
{
    if (Auth::check()){
        return Redirect::to('/hidden');
    }
    else{
    	return View::make('inicio');
    }
});

//Pagina principal donde está el formulario de identificación

//Página oculta donde sólo puede ingresar un usuario identificado
Route::get('/hidden', ['before' => 'auth', function(){
    return View::make('hidden');
}]);
//Procesa el formulario e identifica al usuario
//Route::post('/login', ['uses' => 'AuthController@doLogin', 'before' => 'guest']);
//Desconecta al usuario
Route::get('/logout', ['uses' => 'AuthController@doLogout', 'before' => 'auth']);

//Rutas para CRUD (vistas y funcinalidades) ---------------------------------------------------------------------------

//A continuacion declaro un grupo de rutas (con 'prefix' determino el nombre del grupo) 
//(con 'before' => 'auth' estoy agregando el filtro deseado, en esete caso NO SE PUEDE ACCEDER A ESTAS RUTAS A MENOS QUE ESTES LOGUEADO)
Route::group(array('prefix' => 'crud', 'before' => 'auth'), function(){ 

	Route::controller('/catalogo','CrudController', array('as'=>'catalogo'));		//Muestra la ventana principal de el catalogo
	Route::get('/listas','CrudController@redirectList', array('as'=>'listas'));		//Cambia entre la tabla que se desea ver (Maestros, Materias, Aulas o Planes)
	Route::get('/showForm','CrudController@showForm', array('as'=>'showForm'));		//Lanza el formulario para agregar un registro
	Route::get('/delete','CrudController@delete', array('as'=>'delete'));			//Eliminacion de registros y redireccion a la tabla
	Route::get('/edit', 'CrudController@edit', array('as'=>'edit'));				//Lanza un formulario con los datos del registro que se modificara 
	Route::any('/update', 'Crudcontroller@update', array('as'=>'update'));			//Hace la actualizacion de datos de alguna de las tablas
	Route::any('/add', 'CrudController@add', array('as'=>'add'));					// hace la consulta y la insercion del nuevo registro
	Route::any('registro', 'Crudcontroller@getRegistro', array('as'=>'registro'));	//Muestra el formulario pa registrar nuevos usuarios
	Route::any('crearUser', 'Crudcontroller@crearUser', array('as'=>'crearUser'));	//Hace la consulta a y la insercion del nuevo usuario y su password
});

