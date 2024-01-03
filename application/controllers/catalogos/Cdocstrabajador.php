<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cdocstrabajador extends REST_Controller
{
	public $RutaQr;
	public $RutaFoto;


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mdosctrabajador');

		setTimeZone($this->verification, $this->input);
		$this->load->library('UploadFile');
		$this->RutaFoto = 'assets/files/archivos_trabajador/';
	}

	public function List_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

		$objeto = new Mdosctrabajador();
		$objeto->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$objeto->IdTrabajador = $this->get('IdTrabajador');
		$objeto->Tipo = $this->get('Tipo');

		// Paginación
		$rows =  $objeto->get_list();

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));
		$objeto->Tamano = $pager->PageSize;
		$objeto->Offset = $pager->Offset;
		$rows = $objeto->get_list();

		$RutaPrincipal = $this->RutaFoto . $IdEmpresa . '/';

		if (!is_dir($RutaPrincipal)) {
			mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
		}

		$route = $RutaPrincipal . $IdSucursal . '/';

		$data['row'] = $rows;
		$data['pagination'] = $pager;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'routefiles' => base_url() . $route,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}


	public function Add_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$v = new Valitron\Validator([
			'Trabajador' => $this->post('IdTrabajador'),
		]);

		$v->rule('required', [
			'Trabajador'
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			// $arra = $this->post('files');
			$IdTrabajador = $this->post('IdTrabajador');
			$Tipo = $this->post('Tipo');

			$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
			$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

			$RutaPrincipal = $this->RutaFoto . $IdEmpresa . '/';

			if (!is_dir($RutaPrincipal)) {
				mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
			}

			$route = $RutaPrincipal . $IdSucursal . '/';

			//$results ES EL ARRAY CON LAS IMÁGENES NUEVAS A UTILIZAR
			$results = array();

			#Archivo
			if (!empty($_FILES['files'])) { # Verfica si se a enviado un archivo nuevo

				#Subida y guardado del nuevo archvio

				$SaveKeyPath = $this->uploadfile->savefile($route, 'files', '', '*', UploadFile::MULTIPLE, true);
				$titulos = $this->post('titulos');
				
				
				//$filesMap DEVUELVE EL NOMBRE DEL ARCHIVO
				$filesMap = array_map(

					function ($item) {
						$itmes = $item['Name'];
						return $itmes; 
					}, 

					$SaveKeyPath
				);

				//$resultado REEMPLAZA TODAS LAS APARICIONES DEL STRING BUSCANDO CON EL STRING DE REEMPLAZO
				$resultado = str_replace($route, '', $filesMap);
				
				foreach ($resultado as $element) {
					array_push($results, $element);
					//$results ES EL ARRAY CON LAS IMÁGENES NUEVAS
				}
			}

			$contador = 0;

			if (!empty($this->post('oldfiles'))) {

				//oldfiles ARRAY CON ARCHIVOS Y TITULOS {file: , titulo: }
				$json = str_replace(array('\t', '\n', '\"'), "", $this->post('oldfiles'));
				$data = json_decode($json);
				//jsonfinalf ARRAY CON ARCHIVOS
				$jsonfinalf = str_replace(array('\t', '\n', '\"'), "", $this->post('finalfiles'));
				$finalfiles = json_decode($jsonfinalf);
				//jsonfinalf ARRAY CON TITULOS
				$jsontitulos = str_replace(array('\t', '\n', '\"'), "", $this->post('finaltitulos'));
				$finaltitulos = json_decode($jsontitulos);

				foreach ($data as $element) {

					//COMPRUEBA SI UN ARCHIVO DE oldfiles EXISTE ES finalfiles
					if (
						!in_array($element->file, $finalfiles) ||
						!in_array($element->titulo, $finaltitulos)	
					) {
						$oArchivos = new Mdosctrabajador();
						$oArchivos->IdTrabajador =  $IdTrabajador;
						$oArchivos->File = $element->file;
						$oArchivos->Tipo = $Tipo;
						$oArchivos->Titulo = $element->titulo;
						$row  = $oArchivos->delete();

						if (file_exists($route . $element->file)) {
							unlink($route . $element->file);
						}

						$contador++;
					}
				}
			}
			
			//REFERENCIA DE POSICIÓN CUANDO EXISTEN ARCHIVOS
			$restantes = count($data) - $contador;

			foreach ($results as $element) {

				$oArchivos = new Mdosctrabajador();
				$oArchivos->IdTrabajador =  $IdTrabajador;
				$oArchivos->File = $element;
				$oArchivos->Tipo = $Tipo;
				$oArchivos->insert();
			}
			//SI EXISTEN ARCHIVOS ENTONCES SE SUMA LA CANTIDAD DE ESTOS AL CONTADOR PARA POSICIONAR BIEN EL ÍNDICE
			$posicion = 0;
			if($restantes > 0){
				$posicion = 0 + $restantes;
			}

			$registros = $oArchivos->get_list();
			
			//SI TIENE REGISTROS ENTONCES LA POSICIÓN SERÁ [1], [2]... DEPENDIEDO DE CUANTOS HAYAN
			for($posicion; $posicion < count($registros); $posicion++)
			
			foreach ($titulos as $titulo) {

				//PARÁMETROS UPDATE
				$oArchivos->IdTrabajador =  $IdTrabajador;
				$oArchivos->File = $registros[$posicion]->File; //LA POSICIÓN DEL TÍTULO SIEMPRE SERÁ IGUAL A LA DEL FILE INSERTADO
				//DATOS A INSERTAR
				$oArchivos->Titulo = $titulo;
				$oArchivos->updateInsert();

				$posicion ++;
			}

			if (count($results) > 0 && $contador == 0) {
				$mensaje = 'Archivos agregados correctamente.';
			}

			if (count($results) > 0 && $contador > 0) {
				$mensaje = 'Se han agregado y eliminado archivos correctamente';
			}

			if (count($results) == 0 && $contador > 0) {
				$mensaje = 'Se han eliminado archivos correctamente.';
			}

			return $this->set_response([
				'status' => true,
				'data' => $results,
				'message' => $mensaje,
			], REST_Controller::HTTP_CREATED);
		} else {

			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
