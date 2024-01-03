<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cnumcontrato extends REST_Controller
{
	public $RutaQr;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mnumcontrato');
		$this->load->model('Mspend_proyecto');

		setTimeZone($this->verification, $this->input);
	}

	public function List_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

		$oMnumcontrato = new Mnumcontrato();
		$oMnumcontrato->Nombre = $this->get('Nombre');
		$oMnumcontrato->IdClienteS = $this->get('IdClienteS');
		$oMnumcontrato->RegEstatus = 'A';

		// Paginaci�n
		$rows =  $oMnumcontrato->get_list();

		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$oMnumcontrato->Limit = $Entrada;

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

		$oMnumcontrato->Tamano = $pager->PageSize;
		$oMnumcontrato->Offset = $pager->Offset;

		$array = array();
		foreach ($rows as $element) {
			$insert = true;
			if ($element->IdProyectoSpend > 0) {
				$oMspend_proyecto = new Mspend_proyecto();
				$oMspend_proyecto->IdProyecto = $element->IdProyectoSpend;
				$oMspend_proyecto->Estatus = 'Abierto';
				$response = $oMspend_proyecto->get_recovery();
				if (!$response['status']) {
					$insert = false;
				}
			}
			if ($insert) {
				$array[] = array(
					'IdContrato' => $element->IdContrato,
					'IdClienteS' => $element->IdClienteS,
					'NumeroC' => $element->NumeroC,
					'RegEstatus' => $element->RegEstatus,
					'Comentario' => $element->Comentario,
					'IdProyectoSpend' => $element->IdProyectoSpend,
				);
			}
		}

		$data['pagination'] = $pager;
		$data['contractlist'] = $oMnumcontrato->getContract();
		$data['row'] = $array;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function Add_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$Id = $this->post('IdCtaPagar');


		$v = new Valitron\Validator([
			'Numero_Contrato' => $this->post('NumeroC')
		]);

		$v->rule('required', [
			'Numero_Contrato'
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			$Id = $this->post('IdContrato');

			$oMnumcontrato = new Mnumcontrato();
			$oMnumcontrato->IdContrato = $Id;
			$oMnumcontrato->NumeroC = $this->post('NumeroC');
			$oMnumcontrato->IdClienteS = $this->post('IdClienteS');
			$oMnumcontrato->Comentario = $this->post('Comentario');
			$oMnumcontrato->RegEstatus = 'A';

			if ($oMnumcontrato->IdContrato == 0) {

				$Id = $oMnumcontrato->insert();

				if ($Id > 0) {
					$oMnumcontrato->IdContrato = $Id;
					$response = $oMnumcontrato->get_recovery();
					$data['ctaporpagar'] = $response['data'];
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
				if ($oMnumcontrato->update()) {
					$response = $oMnumcontrato->get_recovery();
					$data['ctaporpagar'] = $response['data'];

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

	public function Recovery_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$oMnumcontrato = new Mnumcontrato();

		$Id = (int) $this->get('IdContrato');

		if (empty($Id)) {

			return $this->set_response([
				'status' => false,
				'message' => 'Parámetros no recibidos.',
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$oMnumcontrato->IdContrato = $Id;
		}
		$response = $oMnumcontrato->get_recovery();
		if ($response['status']) {
			$data['contrato'] = $response['data'];
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

	public function Delete_delete($Id)
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$oMnumcontrato = new Mnumcontrato();
		$oMnumcontrato->IdContrato = $Id;

		$response = $oMnumcontrato->get_recovery();

		if ($response['status']) {

			if ($oMnumcontrato->delete()) {

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
