<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ctrabajador extends REST_Controller
{
	public $RutaFoto;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Mempresa');
		$this->load->model('Msucursal');

		$this->load->model('Mtrabajador');
		$this->load->model('Musuarios');
		$this->load->model('Mrol');
		$this->load->model('Mperfil');

		$this->load->model('Msucursal');

		setTimeZone($this->verification, $this->input);
		$this->load->library('UploadFile');
		$this->RutaFoto = 'assets/files/foto_trabajador/';
	}

	public function findAll_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}


		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$Rol = $this->get('Rol');
		$IdPerfil = $this->get('IdPerfil');

		$objTrabajador = new Mtrabajador();
		$objTrabajador->RegEstatus = 'A';

		if ($Rol != '' && $IdPerfil > 0) {
			//IdPerfil=4 es USUARIO APP en rol
			$oMrol = new Mrol();
			$oMrol->Nombre = $Rol;
			$oMrol->IdSucursal = $IdSucursal;
			$orol = $oMrol->get_recovery();

			$objTrabajador->IdRol = $orol['data']->IdRol;
			$objTrabajador->IdPerfil = $IdPerfil;
		}

		$objTrabajador->IdSucursal = $IdSucursal;
		$objTrabajador->IdTrabajador = $this->get('IdTrabajador');
		$objTrabajador->Nombre = $this->get('Nombre');

		// Paginación
		$rows =   $objTrabajador->get_list();

		$Entrada = '';
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$objTrabajador->Limit = $Entrada;

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

		$objTrabajador->Tamano = $pager->PageSize;
		$objTrabajador->Offset = $pager->Offset;
		
		$rows =   $objTrabajador->get_list();

		// Límite de usuarios por paquete
		$Msucursal = new Msucursal();
		$Msucursal->IdSucursal = $IdSucursal;
		$sucursal = $Msucursal->getEmployeeLimit();

		// Cantidad de usuarios
		$objTrabajador->IdSucursal = $IdSucursal;
		$cantidad = $objTrabajador->getEmployeeQuantity();

		$data['trabajador'] = $rows;
		$data['pagination'] = $pager;
		$data['sucursal'] = $sucursal['data'];
		$data['usuarios'] = $cantidad['data'];

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function Delete_delete($Id)
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$objTrabajador = new Mtrabajador();
		$objTrabajador->IdTrabajador = $Id;
		$response = $objTrabajador->get_trabajador();


		if ($response['status']) {

			if ($objTrabajador->delete()) {

				$objUsuarios = new Musuarios();
				$objUsuarios->IdUsuario = $response['data']->IdUsuario;
				$responseU = $objUsuarios->get_usuario();

				if ($responseU['status']) {
					$objUsuarios->delete();
				}

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


	public function findOne_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
		$objTrabajador = new Mtrabajador();
		$Id = (int) $this->get('IdTrabajador');

		if (empty($Id)) {

			return $this->set_response([
				'status' => false,
				'message' => 'Parámetros no recibidos.',
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$objTrabajador->IdTrabajador = $Id;
		}
		$data['UrlFoto'] = base_url() . $this->RutaFoto . $IdEmpresa . '/' . $IdSucursal . '/';

		$response =  $objTrabajador->get_trabajador();

		if ($response['data']->IdPerfil <= 0) {
			$oMrol = new Mrol();
			$oMrol->IdRol = $response['data']->IdRol;
			$datarol = $oMrol->get_recovery();

			$Mpefil = new Mperfil();
			$Mpefil->Busqueda = $datarol['data']->Nombre;
			$dataperfil = $Mpefil->get_recovery();

			$response['data']->IdPerfil = $dataperfil['data']->IdPerfil;
		}

		$response['data']->Foto2 =  $response['data']->Foto2;
		if ($response['status']) {
			$data['trabajador'] = $response['data'];
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

	public function Add_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$oMusuarios = new Musuarios();
		$oMusuarios->Candado = trim($this->post('Correo'));
		$oMusuarios->IdUsuario = $this->post('IdUsuario');
		$oMusuarios->Estatus = 'A';
		$eval = $oMusuarios->exists_username();

		$Correo2 = '';

		if ($eval) {
			$Correo2 = trim($this->post('Correo'));
		}

		$Id = $this->post('IdTrabajador');

		$v = new Valitron\Validator([
			'Nombre' => $this->post('Nombre'),
			'Telefono' => $this->post('Telefono'),
			'Profesion' => $this->post('Profesion'),
			'Categoria' => $this->post('IdCategoria'),
			'Correo' => $this->post('Correo'),
			'Correo2' => $Correo2,
			'Pass' => $this->post('Pass'),
			'Password_Confirmacion' => $this->post('Pass2'),
			'CostoAnual' => $this->post('CostoAnual'),
			'HorasTS' => $this->post('HorasTS'),
			'HorasPS' => $this->post('HorasPS'),
			'CostoHora' => $this->post('CostoHora'),
			'Perfil' => $this->post('IdPerfil')
		]);

		if ($Id > 0) {
			$v->rule('required', [
				'Nombre',
				'Telefono',
				'Profesion',
				'Categoria',
				'Correo',
				'CostoAnual',
				'HorasTS',
				'HorasPS',
				'CostoHora',
				'Perfil'
			])->message('El campo {field} es requerido.');
		} else {
			$v->rule('required', [
				'Nombre',
				'Telefono',
				'Profesion',
				'Categoria',
				'Correo',
				'Pass',
				'Password_Confirmacion',
				'CostoAnual',
				'HorasTS',
				'HorasPS',
				'CostoHora',
				'Perfil'
			])->message('El campo {field} es requerido.');

			$v->rule('equals', 'Password_Confirmacion', 'Pass')->message('La contraseña debe ser igual');
		}

		$v->rule('email', [
			'Correo'
		])->message('El campo {field} no es un correo valido.');
		$v->rule('different', 'Correo', 'Correo2')->message('El usuario ya existe');


		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$RutaPrincipal = $this->RutaFoto . $IdEmpresa . '/';
		if (!is_dir($RutaPrincipal)) {
			mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
		}

		if ($v->validate()) {
			$route = $RutaPrincipal . $IdSucursal . '/';
			$files = $this->uploadfile->savefile($route, 'File', $this->post('NombreFoto'), '*', UploadFile::SINGLE);

			$Mpefil = new Mperfil();
			$Mpefil->IdPerfil = $this->post('IdPerfil');
			$dataperfil = $Mpefil->get_recovery();

			$objTrabajador = new Mtrabajador();
			$objTrabajador->IdTrabajador = $this->post('IdTrabajador');
			$objTrabajador->Nombre = $this->post('Nombre');
			$objTrabajador->Telefono = $this->post('Telefono');
			$objTrabajador->Profesion = $this->post('Profesion');
			$objTrabajador->Categoria = $this->post('Categoria');
			$objTrabajador->CostoHora = $this->post('CostoHora');
			$objTrabajador->CostoAnual = $this->post('CostoAnual');
			$objTrabajador->IdSucursal = $IdSucursal;
			$objTrabajador->Usuario = trim($this->post('Correo'));
			$objTrabajador->Pass = $this->post('Pass');
			$objTrabajador->RegEstatus = "A";
			$objTrabajador->Observaciones = $this->post('Observaciones');
			$objTrabajador->Perfil = $dataperfil['data']->Nombre;
			$objTrabajador->HorasTS = $this->post('HorasTS');
			$objTrabajador->HorasPS = $this->post('HorasPS');
			$objTrabajador->IdCategoria = $this->post('IdCategoria');
			$objTrabajador->IdPerfil = $this->post('IdPerfil');
			$objTrabajador->IdUsuario = $this->post('IdUsuario');
			$objTrabajador->Correo = $this->post('Correo');
			$objTrabajador->Estatus = $this->post('Estatus');
			$objTrabajador->Token = $this->post('Token');
			$objTrabajador->EstadoChat = $this->post('EstadoChat');
			$objTrabajador->IdTipoProceso = $this->post('IdTipoProceso');
			$objTrabajador->UpdateApp = $this->post('UpdateApp');
			$objTrabajador->GastoAsignado = $this->post('GastoAsignado');
			$objTrabajador->IdCajaC = $this->post('IdCajaC');
			$objTrabajador->Inventario = $this->post('Inventario');
			// $objTrabajador->Imagen= $this->post('Imagen');
			$objTrabajador->FechaMod = date('Y-m-d H:i:s');
			$objTrabajador->Foto2 = $files;

			if ($objTrabajador->IdTrabajador == 0) {
				$oMusuarios = new Musuarios();
				$oMusuarios->Nombre = $this->post('Nombre');
				$oMusuarios->Apellido = '';
				$oMusuarios->Candado = $this->post('Correo');
				$oMusuarios->Seguridad = '';
				$oMusuarios->IdEmpresa = $IdEmpresa;
				$oMusuarios->IdSucursal = $IdSucursal;
				$oMusuarios->IdCliente = 0;
				$oMusuarios->Password = Password::hash($this->post('Pass'));
				$oMusuarios->FechaMod = date('Y-m-d H:i:s');
				$oMusuarios->IdPerfil2 = $this->post('IdPerfil');
				$oMusuarios->Foto2 = $files;
				$IdUsuario = $oMusuarios->insert();
				$Id = 0;

				if ($IdUsuario > 0) {
					$objTrabajador->IdUsuario = $IdUsuario;
					$Id =  $objTrabajador->insert();
				}

				if ($Id > 0) {
					$objTrabajador->IdTrabajador = $Id;
					$response = $objTrabajador->get_trabajador();
					$data['trabajador'] = $response['data'];
					$resmail =  $this->sendmailservices($response['data'], $IdEmpresa, $IdSucursal, $this->post('Pass'));
					return $this->set_response([
						'status' => true,
						'data' => $data,
						'resmail' => $resmail,
						'message' => 'Se ha agregado correctamentes.',
					], REST_Controller::HTTP_CREATED);
				} else {
					return $this->set_response([
						'status' => false,
						'message' => 'Error al agregar a la base de datos.',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				if ($objTrabajador->update()) {
					$oMusuarios = new Musuarios();
					$oMusuarios->Nombre = $this->post('Nombre');
					$oMusuarios->Candado = $this->post('Correo');
					$oMusuarios->Password = Password::hash($this->post('Pass'));
					$oMusuarios->FechaMod = date('Y-m-d H:i:s');
					$oMusuarios->IdPerfil2 = $this->post('IdPerfil');
					$oMusuarios->Foto2 = $files;
					$oMusuarios->IdUsuario = $this->post('IdUsuario');
					$oMusuarios->update_usertrab();

					$response =  $objTrabajador->get_trabajador();
					$data['trabajador'] = $response['data'];

					return $this->set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha actualizado correctamente.',
					], REST_Controller::HTTP_ACCEPTED);
				} else {

					return $this->set_response([
						'status' => false,
						'message' => 'Error al actualizar los datos de la base de datos.',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			}
		} else {
			$data['errores'] = $v->errors();
			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	public function ChangePass_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$oMusuarios = new Musuarios();
		$oMusuarios->Candado = trim($this->post('Correo'));
		$oMusuarios->IdUsuario = $this->post('IdUsuario');
		$oMusuarios->Estatus = 'A';
		$eval = $oMusuarios->exists_username();

		$Correo2 = '';
		if ($eval) {
			$Correo2 = trim($this->post('Correo'));
		}

		$Id = $this->post('IdTrabajador');

		$v = new Valitron\Validator([
			'Correo' => $this->post('Correo'),
			'Correo2' => $Correo2,
			'Pass' => $this->post('Pass'),
			'Password_Confirmacion' => $this->post('Pass2')
		]);

		$v->rule('required', [
			'Correo',
			'Pass',
			'Password_Confirmacion',
		])->message('El campo {field} es requerido.');

		$v->rule('equals', 'Password_Confirmacion', 'Pass')->message('La contraseña debe ser igual');
		$v->rule('email', ['Correo'])->message('El campo {field} no es un correo valido.');
		$v->rule('different', 'Correo', 'Correo2')->message('El usuario ya existe');

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		if ($v->validate()) {
			$objTrabajador = new Mtrabajador();
			$objTrabajador->IdTrabajador = $this->post('IdTrabajador');
			$objTrabajador->Usuario = $this->post('Correo');
			$objTrabajador->Correo = $this->post('Correo');
			$objTrabajador->Pass = $this->post('Pass');
			$objTrabajador->FechaMod = date('Y-m-d H:i:s');

			if ($objTrabajador->updateCredencial()) {
				$oMusuarios = new Musuarios();
				$oMusuarios->Candado = $this->post('Correo');
				$oMusuarios->Password = Password::hash($this->post('Pass'));
				$oMusuarios->FechaMod = date('Y-m-d H:i:s');
				$oMusuarios->IdUsuario = $this->post('IdUsuario');
				$oMusuarios->updatePassw_trabajador();

				$response =  $objTrabajador->get_trabajador();
				$data['trabajador'] = $response['data'];

				return $this->set_response([
					'status' => true,
					'data' => $data,
					'message' => 'Se ha actualizado correctamente.',
				], REST_Controller::HTTP_ACCEPTED);
			} else {
				return $this->set_response([
					'status' => false,
					'message' => 'Error al actualizar los datos de la base de datos.',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$data['errores'] = $v->errors();
			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	function sendmailservices($datatrabajador, $IdEmpresa, $IdSucursal, $password)
	{
		$resmail = '';
		//Lista de trabajadores

		$oMempresa = new Mempresa();
		$oMempresa->IdEmpresa = $IdEmpresa;
		$dataempresa = $oMempresa->get_empresa();

		$oMsucursal = new Msucursal();
		$oMsucursal->IdSucursal = $IdSucursal;
		$datasucursal = $oMsucursal->get_sucursal();


		$dataview['empresa'] = $dataempresa;
		$dataview['datos'] = $datatrabajador;
		$dataview['pass'] = $password;
		$dataview['RutaTrab'] = base_url() . 'assets/foto_trabajador/' . $IdEmpresa . '/' . $IdSucursal . '/';
		$dataview['RutaLogo'] = base_url() . 'assets/logo_empresa/';
		$dataview['link'] = returnLink();
		$dataview['Tipo'] = 1;

		$vista = $this->load->view('catalogos/correo/user.php', $dataview, TRUE);

		$oMail = new Mail();
		$oMail->To = $this->post('Correo');
		$oMail->Subject = 'Bienvenido al sistema';
		$oMail->Message = $vista;
		$resmail = $oMail->SendEmail();


		return $resmail;
	}

	public function ListTrabRol_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMrol = new Mrol();
		$oMrol->Nombre = $this->get('Rol'); //'Usuario APP';
		$oMrol->IdSucursal = $IdSucursal;
		$orol = $oMrol->get_recovery();

		$oMtrabajador = new Mtrabajador();
		$oMtrabajador->IdSucursal = $IdSucursal;
		$oMtrabajador->RegEstatus = 'A';
		$oMtrabajador->IdRol = $orol['data']->IdRol;
		$oMtrabajador->IdPerfil = $this->get('IdPerfil');
		$rowtrabajador = $oMtrabajador->get_list();
		// Paginación

		$data['lista'] = $rowtrabajador;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function ListTrabRolQuery_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdIdComp2 = array();
		$IdIdCompJS3 = str_replace(array('\t', '\n', '\"'), "", $this->get('Rol'));
		$IdIdComp2 = json_decode($IdIdCompJS3);

		$oMrol = new Mrol();
		$oMrol->Nombre = $IdIdComp2; //'Usuario APP';
		$oMrol->IdSucursal = $IdSucursal;
		$orol = $oMrol->get_listName();
		$rowtrabajador = array();

		foreach ($orol as $element) {
			$operfil = new Mperfil();
			$operfil->Busqueda = $element->Nombre;
			$obj = $operfil->get_recovery();

			$oMtrabajador = new Mtrabajador();
			$oMtrabajador->IdSucursal = $IdSucursal;
			$oMtrabajador->RegEstatus = 'A';
			$oMtrabajador->IdRol = $element->IdRol;
			$oMtrabajador->IdPerfil = $obj['data']->IdPerfil;
			$row = $oMtrabajador->get_list();

			foreach ($row as $element) {
				array_push($rowtrabajador, $element);
			}
		}

		// Paginación
		$data['lista'] = $rowtrabajador;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}
}
