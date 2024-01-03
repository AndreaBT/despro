<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccostofinanciero extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('finanzas/Mcostofinanciero');

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
			'Anio' => $this->get('Anio'),
			'Tipo' => $this->get('Tipo')
		]);

		$v->rule('required', [
			'Anio',
			'Tipo'

		])->message('El campo {field} es requerido.');


		if ($v->validate()) {
			$arreg = array("Ingresos por Intereses Inversiones", "Otros Ingresos", "");
			$arreg2 = array("Amortizaci�n Inversi�n", "Royalties", "Pago de Intereses Bancarios", "Pagos Corporativos", "Pagos de Licencias Corporativas", "");

			$Type = "TOTAL INTERESES Y GASTOS";
			if ($this->get('Tipo') == 2) {
				$Type = "TOTAL OTROS INGRESOS/GASTOS";
			}


			$oMcostofinanciero = new Mcostofinanciero();
			$oMcostofinanciero->Anio = $this->get('Anio');
			$oMcostofinanciero->IdSucursal = $IdSucursal;
			$oMcostofinanciero->Tipo = $Type;
			// Paginaci�n
			$rows =  $oMcostofinanciero->get_list();

			if (count($rows) == 0) {
				//si es igual a 0 se inserta para que busque
				$oFinanzasActualizacion = new FinanzasActualizacion();
				$rows =  $oFinanzasActualizacion->InsertValues(5, $this->get('Anio'), $IdSucursal, $IdEmpresa, $this->get('Tipo'));
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

		$oMcostofinanciero = new Mcostofinanciero();
		$oMcostofinanciero->Anio = $this->get('Anio');

		$oMcostofinanciero->IdSucursal = $IdSucursal;


		$data['listacostoctpp'] = $oMcostofinanciero->get_list();

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}


	public  function Add_post()
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

			$Detalle = $this->post('Detalle');
			$Detalle2 = $this->post('Detalle2');

			foreach ($Detalle as $element) {
				$AnioAnterior = $element['AnioAnterior'];
				$UnoT = $element['PrimerT'];
				$DosT = $element['SegundoT'];
				$TresT = $element['TercerT'];
				$CuatroT = $element['CuartoT'];

				if ($AnioAnterior == '') {
					$AnioAnterior = 0;
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

				$oMcostofinanciero = new Mcostofinanciero();
				$oMcostofinanciero->IdCostoFinanciero = $element['IdCostoFinanciero'];
				$oMcostofinanciero->Tipo = $element['Tipo'];
				$oMcostofinanciero->Descripcion = $element['Descripcion'];
				$oMcostofinanciero->NumeroCuenta = $element['NumeroCuenta'];
				$oMcostofinanciero->AnioAnterior = $AnioAnterior;
				$oMcostofinanciero->PrimerT = $UnoT;
				$oMcostofinanciero->SegundoT = $DosT;
				$oMcostofinanciero->TercerT = $TresT;
				$oMcostofinanciero->CuartoT = $CuatroT;
				$oMcostofinanciero->update();
			}

			foreach ($Detalle2 as $element) {
				$AnioAnterior = $element['AnioAnterior'];
				$UnoT = $element['PrimerT'];
				$DosT = $element['SegundoT'];
				$TresT = $element['TercerT'];
				$CuatroT = $element['CuartoT'];

				if ($AnioAnterior == '') {
					$AnioAnterior = 0;
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

				$oMcostofinanciero = new Mcostofinanciero();
				$oMcostofinanciero->IdCostoFinanciero = $element['IdCostoFinanciero'];
				$oMcostofinanciero->Tipo = $element['Tipo'];
				$oMcostofinanciero->Descripcion = $element['Descripcion'];
				$oMcostofinanciero->NumeroCuenta = $element['NumeroCuenta'];
				$oMcostofinanciero->AnioAnterior = $AnioAnterior;
				$oMcostofinanciero->PrimerT = $UnoT;
				$oMcostofinanciero->SegundoT = $DosT;
				$oMcostofinanciero->TercerT = $TresT;
				$oMcostofinanciero->CuartoT = $CuatroT;
				$oMcostofinanciero->update();
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
