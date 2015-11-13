<?php

class AjaxController extends BaseController {

	public function get_cargar_proyectos(){
		echo Proyecto::all();
	}

	//Guarda los cambios al horario
	public function get_guardar_cambios(){
		$data = Input::all();
		$registro = Horario::select('*')->where('id_plantilla', $data['idReg'])->get();
		//Si aún no hay ningún registro para la materia y grupo seleccionados...

		if(sizeof($registro) < 1){
			foreach($data['horasDia'] as $dia){
				//Se crean los registros
				$registro = new Horario();
				$registro->id_plantilla = $data['idReg'];
/*
 Si el día no está asignado, los valores de hora_inicio, hora_fin y id_aula quedan en null.
 Índice nulo para horas: 0. 
 Índice nulo para aulas: 1.
 */
				if($dia[1] == 1){ //Si no hay aula asignada, se añade el valor nulo
					$registro->id_aula = 1;
				}
				//Se añaden los valores del aula, hora inicio y hora fin al registro.
				else{
					for($i=$dia[2]; $i<=$dia[3]; $i++){
						$row = Disponible::select('id')->where('id_aula', $dia[1])
							->where('dia', $dia[0])
							->where('hora', $i)
							->get();
						$aula = Disponible::find($row[0]->id);
						$aula->disponible = 0;
						$aula->save();
					}
					$registro->id_aula = $dia[1];
					$registro->hora_inicio = $dia[2];
					$registro->hora_fin = $dia[3];
				}
				//Se agrega el valor del día y se guarda el registro.
				$registro->dia = $dia[0];
				$registro->save();
			}
		}
		else{
			//Separa los ID de las horas por comas y las convierte en un array.
			$horas = [];
			if($data['ocupadas'] != ""){ //Se revisa que no tenga horas ocupadas.
				$data['ocupadas'] = str_replace(" ", "", $data['ocupadas']);
				$data['ocupadas'] = strtoupper($data['ocupadas']);
				$horas = explode(',',$data['ocupadas']);
				foreach($horas as $hora){
					//Cambia las horas anteriores a libres.
					$liberarHora = Disponible::find($hora);
					$liberarHora->disponible = 1;
					$liberarHora->save();
				}
			}

			$k=0;
			foreach($data['horasDia'] as $dia){
				//Se crean los registros
				$materia = $registro[$k]['id'];
				$materia = Horario::find($materia);
				$k++;
/*				
 Si el día no está asignado, los valores de hora_inicio, hora_fin y id_aula quedan en null.
 Índice nulo para horas: 0. 
 Índice nulo para aulas: 1.
 */
				if($dia[1] == 1){ //Si no hay aula asignada, se añade el valor nulo
					$materia->id_aula = 1;
				}
				//Se añaden los valores del aula, hora inicio y hora fin al registro.
				else{
					for($i=$dia[2]; $i<=$dia[3]; $i++){
						$row = Disponible::select('id')->where('id_aula', $dia[1])
							->where('dia', $dia[0])
							->where('hora', $i)
							->get();
						$aula = Disponible::find($row[0]->id);
						$aula->disponible = 0;
						$aula->save();
					}
					$materia->id_aula = $dia[1];
					$materia->hora_inicio = $dia[2];
					$materia->hora_fin = $dia[3];
				}
				//Se agrega el valor del día y se guarda el registro.
				$materia->dia = $dia[0];
				$materia->save();
			}
		}
		//Se agrega el profesor asignado a la base de datos.
		$temp = Plantilla::find($data['idReg']);
		$temp->id_profesor = $data['idProf'];
		$temp->save();
	}

	//Obtiene los datos del profesor seleccionado
	public function get_datos_profesor(){
		$id 		= Input::get('idProf');
		$idProyecto = Input::get('idProyecto');
		$idRegistro = Input::get('idRegistro');
		$json = [];
		$grupos = ['N/A'];
		$horas = 0;
		//Obtiene el nombre del profesor seleccionado y lo añade al array que se enviará.
		$data = Professor::find($id);
		$nombre = $data['ap_pat']." ".$data['ap_mat']." ".$data['nombre']." ".$data['seg_nombre'];
		array_push($json, $nombre);

		//Obtiene los grupos donde el profesor da clases, y los agrega al array a enviar.
		$data = Plantilla::select('grupo')
			->where('id_profesor', $id)
			->where('id_proyecto', $idProyecto)
			->groupBy('grupo')
			->get();
		if(sizeof($data) > 0){
			$grupos=[];
			foreach($data as $grupo){
				array_push($grupos, $grupo['grupo']);
			}
			//Se obtiene el equivalente en horas a la semana de cada materia asignada y se agrega al array a enviar.
			$data = Plantilla::select('m.horas')
				->join('subjects as m', 'm.id', '=', 'plantillas.id_subject')
				->where('plantillas.id_profesor', '=', $id)
				->where('id_proyecto',$idProyecto)
				->get();
			foreach($data as $cantidad){
				$horas += $cantidad['horas'];
			}
		}
		/*
		 En caso de no tener asignado algo, se manda un array con el nombre el profesor y 2 "N/A" correspondientes
		 a grupos y horas totales
		*/
		array_push($json, $grupos);
		array_push($json, $horas);
		echo json_encode($json);
	}

	//Ídem anterior.
	public function get_comparar_profesor(){
		$id 		= Input::get('idProf');
		$idProyecto = Input::get('idProyecto');
		$idRegistro = Input::get('idRegistro');
		$json = [];
		$grupos = ['N/A'];
		$horas = 0;
		$data = Professor::find($id);
		$nombre = $data['ap_pat']." ".$data['ap_mat']." ".$data['nombre']." ".$data['seg_nombre'];
		array_push($json, $nombre);

		$data = Plantilla::select('grupo')
			->where('id_profesor', '=', $id)
			->where('id_proyecto',$idProyecto)
			->groupBy('grupo')
			->get();

		if(sizeof($data) > 0){
			$grupos=[];
			foreach($data as $grupo){
				array_push($grupos, $grupo['grupo']);
			}
			$data = Plantilla::select('m.horas')
				->join('subjects as m', 'm.id', '=', 'plantillas.id_subject')
				->where('plantillas.id_profesor', '=', $id)
				->where('id_proyecto',$idProyecto)
				->get();
			foreach($data as $cantidad){
				$horas += $cantidad['horas'];
			}
		}
		array_push($json, $grupos);
		array_push($json, $horas);
		echo json_encode($json);
	}

	//Obtiene las horas utilizadas por día en el aula seleccionada.
	public function get_comparar_aula(){
		$data = Input::all();
		$response = [];
		$temp = Horario::select('horarios.*','p.grupo')
				->join('plantillas as p', 'horarios.id_plantilla','=','p.id')
				->where('horarios.id_aula',$data['id'])
				->where('p.ciclo',$data['ciclo'])
				->where('p.anio', $data['anio'])
				->get();
		array_push($response, $temp);
		$temp = Aula::find($data['id']);
		array_push($response, $temp);
		echo json_encode($response);
	}

	//Obtiene las horas disponibles en el día y aula seleccionadas.
	public function get_chk_hrs(){
		$id = Input::get('id');
		$dia = Input::get('dia');
		echo Disponible::select('*')->where('id_aula', $id)
		->where('dia',$dia)->get();
	}

	//Obtiene las horas de la base de datos.
	public function get_dias_horas(){
		echo Hora::all();
	}

	public function get_cargar_horario(){
		$data = Input::all();
		$response = Horario::select('horarios.*','p.grupo','a.nombre as aula','s.nombre as materia', 'pr.*')
				->join('plantillas as p', 'horarios.id_plantilla','=','p.id')
				->join('aulas as a', 'a.id','=','horarios.id_aula')
				->join('subjects as s', 'p.id_subject', '=','s.id')
				->join('professors as pr', 'pr.id', '=', 'p.id_profesor')
				->where('p.grupo', $data['grupo'])
				->where('p.id_proyecto',$data['idProyecto'])
				->where('horarios.hora_inicio','>',0)
				->get();
		echo $response;

	}

	public function get_check_horas_profesor(){
		$data = Input::all();
		$horas = [];
		//Obtiene las horas ocupadas por la materia en cuestión.
		$response = Horario::select('horarios.*', 'hd.disponible', 'hd.id')
				->join('plantillas as p', 'horarios.id_plantilla','=','p.id')
				->join('aulas as a', 'a.id','=','horarios.id_aula')
				->join('subjects as s', 'p.id_subject', '=','s.id')
				->join('professors as pr', 'pr.id', '=', 'p.id_profesor')
				->join('horas_disponibles as hd', function($join){
					$join->on('a.id', '=', 'hd.id_aula');
					$join->on('horarios.dia', '=', 'hd.dia')
						->where('hd.disponible', '=', 0);
				})
				->where('p.grupo', $data['grupo'])
				->where('p.id_proyecto',$data['idProyecto'])
				->where('p.id_profesor', $data['idProf'])
				->get();
		//Guarda los ID de las horas que ocupa la materia, los cuales se relacionan directamente con el aula y el día.
		foreach ($response as $hora) {
			array_push($horas, $hora['id']);
		}

		//Se obtienen las horas disponibles en el aula actual.				
		$aux = Disponible::select('*')
				->where('id_aula', $response[0]->id_aula)
				->get();

		/* Se ponen temporalmente como disponibles las horas que se utilizan para esta materia en el aula seleccionada.
			Esto para asegurar el buen funcionamiento de los combobox para selección de horas */
		for($i=0; $i < sizeof($response); $i++){
			for($k=0; $k < sizeof($aux); $k++){
				if($response[$i]->id == $aux[$k]->id){
					$aux[$k]->disponible = 1;
				}
			}
		}
		//Obtiene el aula y las horas ocupadas por esta materia por día.
		$response = Horario::select('horarios.*', 'hd.disponible', 'hd.id')
				->join('plantillas as p', 'horarios.id_plantilla','=','p.id')
				->join('aulas as a', 'a.id','=','horarios.id_aula')
				->join('subjects as s', 'p.id_subject', '=','s.id')
				->join('professors as pr', 'pr.id', '=', 'p.id_profesor')
				->join('horas_disponibles as hd', function($join){
					$join->on('a.id', '=', 'hd.id_aula');
					$join->on('horarios.dia', '=', 'hd.dia')
						->where('hd.disponible', '=', 0);
				})
				->where('p.grupo', $data['grupo'])
				->where('p.id_proyecto', $data['idProyecto'])
				->where('p.id_profesor', $data['idProf'])
				->groupBy('horarios.id')
				->get();
		$data = [];
		array_push($data, $response);
		array_push($data, $aux);
		array_push($data, $horas);
		//Se envían el array de datos que contiene las horas ocupadas del aula por día y las horas disponibles por día.
		echo json_encode($data);
	}

	public function post_login(){
		$username = Input::get('username');   //obtenemos el username enviado por el form
        $password = Input::get('password');   //obtenemos el password enviado por el form
        $response = false;
        // Realizamos la autenticación
        if (Auth::attempt(['username' => $username, 'password' => $password]))
        {
            //return Redirect::to('/hidden');
            $response = true;
        }

        echo json_encode($response);
	}
}