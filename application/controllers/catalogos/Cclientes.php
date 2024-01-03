<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cclientes extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mclientes');
		$this->load->model('Mclientesucursal');
		setTimeZone($this->verification, $this->input);
	}

	public function findAll_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$objClientes = new Mclientes();
		$objClientes->Nombre = $this->get('Nombre');
		$objClientes->IdSucursal = $IdSucursal;
		$objClientes->RegEstatus = $this->get('RegEstatus');


		// Paginación
		$rows =  $objClientes->get_list();

		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$objClientes->Limit = $Entrada;

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

		$objClientes->Tamano = $pager->PageSize;
		$objClientes->Offset = $pager->Offset;

		$data['clientes'] = $objClientes->get_list();
		$data['clientlist'] = $objClientes->getListCta();
		$data['pagination'] = $pager;

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
		$objClientes = new Mclientes();

		$objClientes->IdCliente = $Id;

		$response = $objClientes->get_clientes();

		if ($response['status']) {

			if ($objClientes->delete()) {

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
		$objClientes = new Mclientes();

		$Id = (int) $this->get('IdCliente');

		if (empty($Id)) {

			return $this->set_response([
				'status' => false,
				'message' => 'Parámetros no recibidos.',
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$objClientes->IdCliente = $Id;
		}
		$response =  $objClientes->get_clientes();
		if ($response['status']) {
			$data['Clientes'] = $response['data'];
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

		$v = new Valitron\Validator([
			'Nombre' => $this->post('Nombre'),
			'Telefono' => $this->post('Telefono'),
			'Direccion' => $this->post('Direccion'),
			'Correo' => $this->post('Correo'),
			'Ciudad' => $this->post('Ciudad'),
			'Contacto' => $this->post('Contacto')

		]);

		$v->rule('required', [
			'Nombre',
			'Telefono',
			'Direccion',
			'Correo',
			'Ciudad',
			'Contacto'

		])->message('El campo {field} es requerido.');

		$v->rule('email', ['Correo'])->message('El campo {field} no es un correo valido.');

		if ($v->validate()) {

			$Id = $this->post('IdTipoSer');

			$objClientes = new Mclientes();
			$objClientes->IdCliente = $this->post('IdCliente');
			$objClientes->Nombre = $this->post('Nombre');
			$objClientes->Telefono = $this->post('Telefono');
			$objClientes->Direccion = $this->post('Direccion');
			$objClientes->Correo = $this->post('Correo');
			$objClientes->Ciudad = $this->post('Ciudad');
			$objClientes->Pais = $this->post('Pais');
			$objClientes->Estado = $this->post('Estado');
			$objClientes->CP = $this->post('CP');
			$objClientes->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
			$objClientes->RegEstatus = "A";
			$objClientes->Contacto = $this->post('Contacto');
			$objClientes->Dfac = $this->post('Dfac');
			$objClientes->FechaMod = date('Y-m-d H:i:s');
			if ($objClientes->IdCliente == 0) {

				$Id =  $objClientes->insert();
				if ($Id > 0) {
					$objClientes->IdCliente = $Id;
					$response =  $objClientes->get_clientes();
					$data['clientes'] = $response['data'];
					return $this->set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha agregado correctamente.',
					], REST_Controller::HTTP_CREATED);
				} else {
					return $this->set_response([
						'status' => false,
						'message' => 'Error al agregar a la base de datos.',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				if ($objClientes->update()) {
					$response =  $objClientes->get_clientes();
					$data['clientes'] = $response['data'];

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

	public function houseAccount_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oClientes = new Mclientes();

		$oClientes->Nombre = "House Account";
		$oClientes->Direccion = "1";
		$oClientes->Contacto = "House Account";
		$oClientes->Correo = "house@gmail.com";
		$oClientes->Ciudad = "1";
		$oClientes->Pais = "1";
		$oClientes->Estado = "1";
		$oClientes->CP = "1";
		$oClientes->IdSucursal = $IdSucursal;
		$oClientes->RegEstatus = "A";
		$oClientes->Telefono = "1";
		$oClientes->Dfac = "1";
		$oClientes->FechaMod = date('Y-m-d H:i:s');

		$Id =  $oClientes->insert();

		$oClientesSucursal = new Mclientesucursal();
		$oClientesSucursal->IdCliente = $Id;
		$oClientesSucursal->Nombre = "House Account";
		$oClientesSucursal->Direccion = "1";
		$oClientesSucursal->Telefono = "1";
		$oClientesSucursal->Correo = "house@gmail.com";
		$oClientesSucursal->Ciudad = "1";
		$oClientesSucursal->IdSucursal = $IdSucursal;
		$oClientesSucursal->RegEstatus = "A";
		$oClientesSucursal->ContactoS = "House Account";
		$oClientesSucursal->Ncontrato = "";
		$oClientesSucursal->CheckCli = "";
		$oClientesSucursal->Tipo = "";
		$oClientesSucursal->IdVendedor = "";
		$oClientesSucursal->IdIconoEmp = "";
		$oClientesSucursal->DistanciaAprox = "1";
		$oClientesSucursal->Comentario = null;
		$oClientesSucursal->Cargo = null;
		$oClientesSucursal->FechaMod = date('Y-m-d H:i:s');
		$oClientesSucursal->Latitud = "0";
		$oClientesSucursal->Longitud = "0";

		$data['clientes'] = $oClientesSucursal->insert();;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function ListMonitoreo_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$objClientes = new Mclientes();
		$objClientes->IdCliente = $array['uniqueid'];
		$objClientes->Nombre = $this->get('Nombre');
		$objClientes->IdSucursal =  $IdSucursal;
		$objClientes->RegEstatus = $this->get('RegEstatus');


		// Paginación
		$rows =  $objClientes->get_list_monitoreo();
		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}
		$objClientes->Limit = $Entrada;
		$pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

		$objClientes->Tamano = $pager->PageSize;
		$objClientes->Offset = $pager->Offset;

		$data['clientes'] = $objClientes->get_list_monitoreo();
		$data['pagination'] = $pager;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}
}
