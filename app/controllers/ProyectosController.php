<?php
	
class ProyectosController extends BaseController{
	//Vista principal de las plantillas. Envía datos de las tablas: Plans, Plantillas y Professors.
	public function index(){
		return View::make('proyectos.index')
				->with('proyectos',Proyecto::all());
	}
	//Crea un nuevo proyecto.
	public function crearProyecto(){
		$data = Input::all();
		$ciclo = $data['cicle'];
		($ciclo == 1) ? $ciclo = 'Agosto-Enero' : $ciclo = 'Enero-Julio';
		//Valida los datos ingresados.
		$notificaciones = [
			'year.required'=>'¡Escriba un año!',
			'year.numeric'=>'¡El año debe ser numérico!'
		];
		$validation = Validator::make($data,[
			'year' => 'required|numeric'
		], $notificaciones);

		if($validation->passes()){
			//Se revisa si había registros antes
			$temp = Proyecto::select('ciclo', 'anio')
					->where('ciclo', $ciclo)
					->where('anio', $data['year'])
					->get();
			//Si aún no había registros, se añaden las horas disponbiles por aula y día.
			if(sizeof($temp)<1){
				$idProyecto = Proyecto::insertGetId(
					['ciclo'=>$ciclo, 'anio'=>$data['year']]
				);
				$aulas = Aula::count();
				$horas = Hora::select('*')->where('id','>', '0')->get();;
				for($i = 2; $i<=$aulas; $i++){
					for($k=0; $k<5; $k++){
						foreach($horas as $hora){
							$temp = new Disponible();
							$temp->id_aula 		= $i;
							$temp->hora 		= $hora['id'];
							$temp->dia 			= $k;
							$temp->id_proyecto 	= $idProyecto;
							$temp->save();
						}
					}
				}
			}
			return Redirect::to('/proyectos/');
		}
		else{
			Input::flash();
			return Redirect::to('/proyectos/editar-proyecto')->withInput()->withErrors($validation);
		}
	}
	//Habilita la edición de un proyecto existente.
	public function editarProyecto(){
		$idProyecto = Input::get('p'); //Obtiene el id del proyecto a trabajar.
		$ciclo = Proyecto::select('ciclo')
						->where('id', $idProyecto)
						->get();
		$semestres = [];
		if(sizeof($ciclo)>0){
			if($ciclo[0]->ciclo == "Enero-Julio"){
				$semestres = [2,4,6,8];
			}
			else{
				$semestres = [1,3,5,7,9];
			}
			return View::make('proyectos.plantilla')
						->with('planes',Plan::all())
						->with('semestres', $semestres)
						->with('id', $idProyecto)
						->with('plantillas', Plantilla::select('plantillas.*', 'p.nombre as plan', 's.semestre')
						->join('subjects as s', 's.id', '=', 'plantillas.id_subject')
						->join('plans as p', 'p.id', '=', 's.id_plan')
						->where('id_proyecto', $idProyecto)
						->groupBy('s.semestre')
						->groupBy('plantillas.grupo')
						->get()
			);
		}
		else{
			return Redirect::to('proyectos');
		}
	}
	//Elimina un proyecto existente.
	public function eliminarProyecto(){
		$proyecto = Proyecto::find(Input::get('p'));
		$proyecto->delete();
		return Redirect::to('/proyectos');
	}
	//Procesa el agregado de grupos
	public function agregarGrupos(){
		$data = Input::all();
		//Se revisa si había registros antes
		$temp = Plantilla::select('id_plan', 'grupo')
				->where('id_plan', $data['plan'])
				->where('grupo', $data['group'])
				->get();
		//Se obtienen los ID de las materias del semestre y plan seleccionados
		$materias = Subject::select('id')
					->where('semestre','=', Input::get('semester'))
					->where('id_plan', '=', Input::get('plan'))
					->get();
		//Se eliminan las comas en caso de haber múltiples grupos
		$data['group'] = str_replace(" ", "", $data['group']);
		$data['group'] = strtoupper($data['group']);
		$grupos = explode(',',$data['group']);
		//Se añade cada uno de los grupos con sus respectivas materias a la base de datos
		foreach ($grupos as $grupo) {
			foreach ($materias as $materia) {
				$plantilla = new Plantilla();
				$plantilla->grupo 		= $grupo;
				$plantilla->id_plan		= $data['plan'];
				$plantilla->id_subject 	= $materia['id'];
				$plantilla->id_proyecto	= $data['idProyecto'];
				$plantilla->save();
			}
		}
		return Redirect::to('proyectos/editar-proyecto?p='.$data['idProyecto']);
	}
	//Vista de edición de grupo. Envía datos de las tablas: Professors, Plantillas y Aulas.
	public function editGroup(){
		$data = Input::all();
		return View::make('proyectos.editarGrupo')->with('profesores', Professor::all())->with('data',
			Plantilla::select('plantillas.id', 'plantillas.grupo', 's.nombre as materia','plantillas.id_profesor',
							  'p.nombre as plan', 'pr.id as idProyecto', 'pr.ciclo', 'pr.anio')
					->join('subjects as s', 's.id', '=', 'plantillas.id_subject')
					->join('plans as p', 'p.id', '=', 's.id_plan')
					->join('proyectos as pr', 'plantillas.id_proyecto','=', 'pr.id')
					->where('plantillas.grupo', '=', $data['g'])
					->where('s.semestre', '=', $data['s'])
					->where('p.nombre', '=', $data['pl'])
					->where('plantillas.id_proyecto', $data['pid'])
					->get())
			->with('aulas', Aula::all())
			->with('grupos', Plantilla::select('*')->groupBy('grupo')->get());
	}
	//Elimina el grupo seleccionado
	public function deleteGroup(){
		$data = Input::all();
		//Se obtienen los datos del grupo a eliminar.
		$toDelete = Plantilla::select('plantillas.id')
				->join('subjects as s', 'plantillas.id_subject', '=', 's.id')
				->join('plans as p', 'p.id', '=', 's.id_plan')
				->where('plantillas.grupo', '=', $data['g'])
				->where('s.semestre', '=', $data['s'])
				->where('p.nombre', '=', $data['pl'])
				->get();
		//Se eliminan todos sus registros
		foreach ($toDelete as $dato) {
			$grupo = Plantilla::find($dato->id);
			$grupo->delete();
		}
		return Redirect::to('proyectos/editar-proyecto?p='.$data['pid']);
	}
}