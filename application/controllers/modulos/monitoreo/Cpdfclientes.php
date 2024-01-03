<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpdfclientes extends REST_Controller
{
	public $RutaPdf;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('monitoreo/Mpdfclientes');
		$this->load->library('UploadFile');
		$this->RutaPdf		= 'assets/files/monitoreo_cotizacion/';
		$this->rutaOldPdf	= 'assets/oldsystemfile/archivose/';
		setTimeZone($this->verification,$this->input);
	}

	public function List_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

		$objeto = new Mpdfclientes();
		$objeto->IdCliente =$this->get('IdCliente');
		$objeto->IdClienteS =$this->get('IdClienteS');
		$objeto->Titulo =$this->get('Nombre');
		$objeto->RegEstatus='A';
		$objeto->Tipo=$this->get('Tipo');

		// Paginación
		$rows =  $objeto->get_list();
		$Entrada=10;

		if ($this->get('Entrada')!='')
		{
			$Entrada =$this->get('Entrada');
		}
		$objeto->Limit=$Entrada;

		$pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

		$objeto->Tamano = $pager->PageSize;
		$objeto->Offset = $pager->Offset;
		$rows=$objeto->get_list();

		$RutaPrincipal=$this->RutaPdf.$IdEmpresa.'/'.$IdSucursal.'/';

		return $this->set_response([
			'status' 		=> true,
			'row' 			=> $rows,
			'rutaOld' 		=> base_url().$this->rutaOldPdf,
			'RutaPdf' 		=> base_url().$RutaPrincipal, ## RUTA CAMBIADA //20012022 - Presento fallo de ubicacion de archivos.
			'pagination' 	=> $pager,
			'message' 		=> 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function Recovery_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$obj= new Mpdfclientes();
		$Id = (int) $this->get('IdPdf');

		if (empty($Id)) {

			return $this->set_response([
				'status' => false,
				'message' => 'Parámetros no recibidos.',
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$obj->IdPdf = $Id;
		}
		$response =   $obj->get_recovery();
		if ($response['status']) {
			$data['pdfclientes'] = $response['data'];

			return $this->set_response([
				'status' => true,
				'data' => $data,
				'message' => 'Success',
			], REST_Controller::HTTP_OK);
		} else {

			$this->set_response([
				'status' => false,
				'message' => 'No encontrado.',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function Add_post() {
		// Valid Token

		if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
			return $this -> set_unauthorized_response();
		}
		$Id = (int)$this -> post('IdPdf');

		$Tipo = $this -> post('Tipo');
		$IdCliente=$this -> post('IdCliente');
		$IdClienteS=$this -> post('IdClienteS');

		if($Tipo <=0 ){$Tipo = '';}
		if($IdCliente<=0){$IdCliente='';}
		if($IdClienteS<=0){$IdClienteS='';}

		$File='Correcto';
		if($Id<=0){
			if(empty($_FILES['File'])){
				$File='';
			}}


		$v = new Valitron\Validator([
			'Tipo' => $Tipo,
			'Titulo' => trim($this -> post('Titulo')),
			'IdCliente' => $IdCliente,
			'IdClienteS' => $IdClienteS,
			'Archivo'=>$File,
		]);

		$v -> rule('required', [
			'Tipo','IdCliente','Titulo','IdClienteS','Archivo'
		]) -> message('El campo  es requerido.');

		if ($v -> validate()) {

			$IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
			$IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

			$RutaPrincipal=$this->RutaPdf.$IdEmpresa.'/';
			if (!is_dir($RutaPrincipal)) {
				mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
			}

			$route =$RutaPrincipal.$IdSucursal.'/';
			$files = $this->uploadfile->savefile($route, 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);

			$obj = new Mpdfclientes();
			$obj->IdPdf=$Id;
			$obj->IdCliente=$this->post('IdCliente');
			$obj->IdClienteS=$this->post('IdClienteS');;
			$obj->Titulo = trim($this->post('Titulo'));
			$obj->NombreArchivo = $files;
			$obj->Tipo = $Tipo;
			$obj->RegEstatus = 'A';
			$obj->FechaAlta = date('Y-m-d');
			$obj->FechaMod = date('Y-m-d H:i:s');

			if ($Id==0)
			{
				$Id = $obj->insert();
				if ($Id > 0)
				{
					$obj->IdPdf = $Id;
					$response = $obj-> get_recovery();
					$data['pdfclientes'] = $response['data'];

					return $this -> set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha agregado correctamente.',
					], REST_Controller:: HTTP_CREATED);
				} else {
					return $this -> set_response([
						'status' => false,
						'message' => 'Error al agregar.',
					], REST_Controller:: HTTP_BAD_REQUEST);
				}
			}
			else
			{
				if ($obj-> update()) {
					$response = $obj -> get_recovery();
					$data['pdfclientes'] = $response['data'];
					return $this -> set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha actualizado correctamente.',
					], REST_Controller:: HTTP_ACCEPTED);
				} else {
					return $this -> set_response([
						'status' => false,
						'message' => 'Error al actualizar los datos.',
					], REST_Controller:: HTTP_BAD_REQUEST);
				}
			}
		}else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function Delete_delete($Id)
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$obj = new Mpdfclientes();
		$obj->RegEstatus='B';
		$obj->IdPdf = $Id;
		$response =   $obj->get_recovery();

		if ($response['status']) {
			$obj->FechaMod=date('Y-m-d H:i:s');
			if ($obj->delete()) {

				return $this->set_response([
					'status' => true,
					'message' => 'Se ha eliminado correctamente.',
				], REST_Controller::HTTP_ACCEPTED);
			} else {

				return $this->set_response([
					'status' => false,
					'message' => 'Error al eliminar la información.',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {

			return $this->set_response([
				'status' => false,
				'message' => 'No encontrado.',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
}
