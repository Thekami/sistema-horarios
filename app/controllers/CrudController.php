<?php

class CrudController extends BaseController{

	public function getIndex() {
		return View::make('crud.crudMaestros')->with('professors', Professor::all());
	}

	public function redirectList(){
		
		$tabla = Input::get('id');
	
		switch($tabla) {
			case '0':
				return View::make('crud.crudMaestros')->with('professors', Professor::all());
			break;
			case '1':
				return View::make('crud.crudMaterias')->with('subjects', Subject::all());
			break;
			case '2':
				return View::make('crud.crudAulas')->with('aulas', Aula::all());
			break;
			case '3':
				return View::make('crud.crudPlanes')->with('plans', Plan::all());
			break;
			default: 				//aqui me redirecciona a una pagina vacia solo con un mensaje 404
				return View::make('landing');
			break;
		}
	}

	public function showForm(){ 

		$id = Input::get('id');

		switch($id){
			case '0':
				return View::make('crud.crudMaestros')->with('hola', 'si estoy')->with('professors',Professor::all());
			break;
			case '1':
				return View::make('crud.crudMaterias')->with('hola', 'si estoy')->with('subjects',Subject::all());
			break;
			case '2':
				return View::make('crud.crudAulas')->with('hola', 'si estoy')->with('aulas',Aula::all());
			break;
			case '3':
				return View::make('crud.crudPlanes')->with('hola', 'si estoy')->with('plans',Plan::all());
			break;
			default: 			//aqui me redirecciona a una pagina vacia solo con un mensaje 404
				return View::make('landing');
			break;
		}

	}

	public function add(){

		$tabla = Input::get('tabla');
		
		switch($tabla){
			case '0':
				$clave = Input::get('clave');
				$appat = Input::get('ap_pat');
				$apmat = Input::get('ap_mat');
				$nombre = Input::get('nombre');
				$segnombre = Input::get('seg_nombre');
				$tipo = Input::get('tipo');
				$grado = Input::get('grado');
				$tutorias = Input::get('tutorias');
				$gestion = Input::get('gestion');
				$invest = Input::get('invest');
				$depend = Input::get('depend');

				$add = new Professor;
				$add->clave = $clave;
				$add->ap_pat = $appat;
				$add->ap_mat = $apmat;
				$add->nombre = $nombre;
				$add->seg_nombre = $segnombre;
				$add->tipo = $tipo;
				$add->id_grado = $grado;
				$add->tutorias = $tutorias;
				$add->gestion = $gestion;
				$add->investigacion = $invest;
				$add->dependencias = $depend;
				$add->save();
				return View::make('crud.crudMaestros')->with('professors', Professor::all());
			break;
			case '1':
				$nombre = Input::get('nombre');
				$semestre = Input::get('semestre');
				$plan = Input::get('idPlan');

				$add = new Subject;
				$add->nombre = $nombre;
				$add->semestre = $semestre;
				$add->id_plan = $plan;
				$add->save();
				return View::make('crud.crudMaterias')->with('subjects', Subject::all());
			break;
			case '2':
				$nombre = Input::get('nombre');

				$add = new Aula;
				$add->nombre = $nombre;
				$add->save();
				return View::make('crud.crudAulas')->with('aulas', Aula::all());
			break;
			case '3':
				$nombre = Input::get('nombre');

				$add = new Plan;
				$add->nombre = $nombre;
				$add->save();
				return View::make('crud.crudPlanes')->with('plans', Plan::all());
			break;
			default: 			//aqui me redirecciona a una pagina vacia solo con un mensaje 404 
				return View::make('landing');
			break;
		}
	}

	public function delete(){
		$id = Input::get('id'); //Obtiene el valor de "id" que envia el link "eliminar"
		$tabla = Input::get('tabla'); //Obtine el valor de "tabla" que envia el link "eliminar"

		switch($tabla){
			case '0':
				$profesor = Professor::find($id);		//consulta el registro deseado basado en su id
				$profesor->delete();					//elimina el registro
				return Redirect::to('crud/listas?id=0');	//redirecciona a la tabla de la cual se elimino
			break;
			case '1':
				$subject = Subject::find($id);			//consulta el registro deseado basado en su id
				$subject->delete();						//elimina el registro
				return Redirect::to('crud/listas?id=1');	//redirecciona a la tabla de la cual se elimino
			break;
			case '2':
				$aula = Aula::find($id);				//consulta el registro deseado basado en su id
				$aula->delete();						//elimina el registro
				return Redirect::to('crud/listas?id=2');	//redirecciona a la tabla de la cual se elimino
			break;
			case '3':
				$plan = Plan::find($id);				//consulta el registro deseado basado en su id
				$plan->delete();						//elimina el registro
				return Redirect::to('crud/listas?id=3');	//redirecciona a la tabla de la cual se elimino
			break;
			default: 			//aqui me redirecciona a una pagina vacia solo con un mensaje 404
				return View::make('landing');
			break;
		}
	}

	public function edit(){

		$id = Input::get('id');
		$tabla = Input::get('tabla');

		switch ($tabla) {
			case '0':
				return View::make('crud.crudMaestros')->with('txTprofessors', Professor::find($id))->with('professors',Professor::all());
			break;
			case '1':
				return View::make('crud.crudMaterias')->with('txTsubjects', Subject::find($id))->with('subjects',Subject::all());
			break;
			case '2':
				return View::make('crud.crudAulas')->with('txTaulas', Aula::find($id))->with('aulas',Aula::all());
			break;
			case '3':
				return View::make('crud.crudPlanes')->with('txTplans', Plan::find($id))->with('plans',Plan::all());
			break;
			default: 				//aqui me redirecciona a una pagina vacia solo con un mensaje 404
				return View::make('landing');
			break;
		}
	}

	public function update(){

		$id = Input::get('id');
		$tabla = Input::get('tabla');

		switch ($tabla){
			case '0';
				$clave = Input::get('clave');
				$appat = Input::get('ap_pat');
				$apmat = Input::get('ap_mat');
				$nombre = Input::get('nombre');
				$segnombre = Input::get('seg_nombre');
				$tipo = Input::get('tipo');
				$grado = Input::get('grado');
				$tutorias = Input::get('tutorias');
				$gestion = Input::get('gestion');
				$investigacion = Input::get('investigacion');
				$dependencias = Input::get('dependencias');

				$edit = Professor::find($id);
				$edit->clave = $clave;
				$edit->ap_pat=$appat;
				$edit->ap_mat=$apmat;
				$edit->nombre=$nombre;
				$edit->seg_nombre=$segnombre;
				$edit->tipo = $tipo;
				$edit->id_grado = $grado;
				$edit->tutorias = $tutorias;
				$edit->gestion = $gestion;
				$edit->investigacion = $investigacion;
				$edit->dependencias = $dependencias;
				$edit->save();
				return View::make('crud.crudMaestros')->with('professors', Professor::all());
			break;
			case '1';
				$nombre = Input::get('nombre');
				$semestre = Input::get('semestre');
				$plan = Input::get('idPlan');

				$edit = Subject::find($id);
				$edit->nombre=$nombre;
				$edit->semestre = $semestre;
				$edit->id_plan = $plan;
				$edit->save();
				return View::make('crud.crudMaterias')->with('subjects', Subject::all());
			break;
			case '2';
				$nombre = Input::get('nombre');

				$edit = Aula::find($id);
				$edit->nombre = $nombre;
				$edit->save();
				return View::make('crud.crudAulas')->with('aulas', Aula::all());
			break;
			case '3';
				$nombre = Input::get('nombre');

				$edit = Plan::find($id);
				$edit->nombre = $nombre;
				$edit->save();
				return View::make('crud.crudPlanes')->with('plans', Plan::all());
			break;
			default: 			//aqui me redirecciona a una pagina vacia solo con un mensaje 404
				return View::make('landing');
			break;
		}
	}

	public function getRegistro(){
		return View::make('crud.crearUser');
	}

	public function crearUser(){
		$username = Input::get('username');
		$password = Input::get('password');
		$add = new User;
		$add->username = $username;
		$add->password = $password;
		$add->save();
		return View::make('crud.crearUser')->with('msg', 'Has sido registrado exitosamente');

	}

}
?>