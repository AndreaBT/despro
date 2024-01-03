<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cgastosdirectos extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('finanzas/Mgastosdirectos');
		$this->load->library('FinanzasActualizacion');

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
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$v = new Valitron\Validator([
			'Tipo' => $this->get('Tipo'),
			'Anio' => $this->get('Anio')
		]);

		$v->rule('required', [
			'Tipo',
			'Anio'

		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$Anio = $this->get('Anio');
			if ($Anio == "") {
				$Anio = date("Y");
			}

			$oMgastosdirectos = new Mgastosdirectos();
			$oMgastosdirectos->Anio =  $Anio;
			$oMgastosdirectos->Tipo = $this->get('Tipo');
			$oMgastosdirectos->IdSucursal = $IdSucursal;
			// Paginaci�n
			$rows =  $oMgastosdirectos->get_list();

			if (count($rows) == 0) {
				//si es igual a 0 se inserta para que busque
				$oFinanzasActualizacion = new FinanzasActualizacion();
				$rows =  $oFinanzasActualizacion->InsertValues(2, $this->get('Anio'), $IdSucursal, $IdEmpresa, $this->get('Tipo'));
			}

			$data['lista'] = $rows;

			return $this->set_response([
				'status' => true,
				'data' => $data,
				'message' => 'Success',
			], REST_Controller::HTTP_OK);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'data' => 'error',
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function getAll_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMgastosdirectos = new Mgastosdirectos();
		$oMgastosdirectos->Anio = $this->get('Anio');

		$oMgastosdirectos->IdSucursal = $IdSucursal;


		$data['listagastoctpp'] = $oMgastosdirectos->get_list();

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

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$v = new Valitron\Validator([
			'Anio' => $this->post('Anio')
		]);

		$v->rule('required', [
			'Anio'

		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$Detalle = $this->post('Detalle1');
			$Detalle2 = $this->post('Detalle2');

			foreach ($Detalle as $element) {
				$MontoAnterior = $element['MontoAnterior'];
				$MontoAnual = $element['MontoAnual'];
				$UnoT = $element['UnoT'];
				$DosT = $element['DosT'];
				$TresT = $element['TresT'];
				$CuatroT = $element['CuatroT'];

				if ($MontoAnterior == '') {
					$MontoAnterior = 0;
				}
				if ($MontoAnual == '') {
					$MontoAnual = 0;
				}
				if ($UnoT == '') {
					$UnoT = 0;
				}
				if ($DosT == '') {
					$DosT = 0;
				}
				if ($TresT == '') {
					$TresT = 0;
				}
				if ($CuatroT == '') {
					$CuatroT = 0;
				}

				$oMgastosdirectos = new Mgastosdirectos();
				$oMgastosdirectos->IdGasto = $element['IdGasto'];
				$oMgastosdirectos->Gasto = $element['Gasto'];
				$oMgastosdirectos->NumCuenta = $element['NumCuenta'];
				$oMgastosdirectos->MontoAnterior = $MontoAnterior;
				$oMgastosdirectos->MontoAnual = $MontoAnual;
				$oMgastosdirectos->UnoT = $UnoT;
				$oMgastosdirectos->DosT = $DosT;
				$oMgastosdirectos->TresT = $CuatroT;
				$oMgastosdirectos->CuatroT = $TresT;
				$oMgastosdirectos->update();
			}

			foreach ($Detalle2 as $element) {
				$MontoAnterior = $element['MontoAnterior'];
				$MontoAnual = $element['MontoAnual'];
				$UnoT = $element['UnoT'];
				$DosT = $element['DosT'];
				$TresT = $element['TresT'];
				$CuatroT = $element['CuatroT'];

				if ($MontoAnterior == '') {
					$MontoAnterior = 0;
				}
				if ($MontoAnual == '') {
					$MontoAnual = 0;
				}
				if ($UnoT == '') {
					$UnoT = 0;
				}
				if ($DosT == '') {
					$DosT = 0;
				}
				if ($TresT == '') {
					$TresT = 0;
				}
				if ($CuatroT == '') {
					$CuatroT = 0;
				}

				$oMgastosdirectos = new Mgastosdirectos();
				$oMgastosdirectos->IdGasto = $element['IdGasto'];
				$oMgastosdirectos->Gasto = $element['Gasto'];
				$oMgastosdirectos->NumCuenta = $element['NumCuenta'];
				$oMgastosdirectos->MontoAnterior = $MontoAnterior;
				$oMgastosdirectos->MontoAnual = $MontoAnual;
				$oMgastosdirectos->UnoT = $UnoT;
				$oMgastosdirectos->DosT = $DosT;
				$oMgastosdirectos->TresT = $CuatroT;
				$oMgastosdirectos->CuatroT = $TresT;
				$oMgastosdirectos->update();
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Insertado',
				'message' => 'Se ha agregado correctamente.',
			], REST_Controller::HTTP_CREATED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'data' => 'error',
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
