<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CactualizacionCostos extends REST_Controller
{
	public $RutaPdf;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('finanzas/Mconceptooperacion');
		$this->load->model('finanzas/Mporcentajeoperacion');
		$this->load->model('Mconfigservicio');
		$this->load->model('Mtiposervicio');
		$this->load->library('UploadFile');
		$this->load->library('FinanzasActualizacion');

		setTimeZone($this->verification, $this->input);
	}


	public function getData_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];


		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$Tipo = $this->get('Tipo');
		$TipoBusqueda = $this->get('TipoBusqueda');

		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, $Tipo, $TipoBusqueda, $IdEmpresa);

		$data['detalle'] = $row;


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

		$v = new Valitron\Validator([
			'Tipo' => $this->post('Tipo'),
			'Mes' => $this->post('Mes'),
			'Anio' => $this->post('Anio')
		]);

		$v->rule('required', [
			'Tipo',
			'Mes',
			'Anio'
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {
			$Tipo = $this->post('Tipo');
			$Mes = $this->post('Mes');
			$Anio = $this->post('Anio');
			$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

			$Detalle = $this->post('Detalle');
			$Detalle2 = $this->post('Detalle2');

			if ($Tipo == 1) {
				$IdName = 'IdCostoGA';
				$Tabla = 'actualcostoga';
			} else if ($Tipo == 2) {
				$IdName = 'IdGasto';
				$Tabla = 'actualventas';
			} else if ($Tipo == 3) {
				$IdName = 'IdCostoDeptoVenta';
				$Tabla = 'actualoperaciones';
			} else if ($Tipo == 4) {
				$IdName = 'IdCostoVehOpe';
				$Tabla = 'actualcostove';
			} else if ($Tipo == 5) {
				$IdName = 'IdCostoFinanciero';
				$Tabla = 'actualcostof';
			}

			foreach ($Detalle as $element) {
				$oMactualFinanzas = new MactualFinanzas();
				$oMactualFinanzas->Tabla = $Tabla;
				$oMactualFinanzas->NameId = $IdName;
				$oMactualFinanzas->Id = $element[$IdName];
				$oMactualFinanzas->Anio = $Anio;
				$oMactualFinanzas->Mes = $Mes;
				$oMactualFinanzas->IdSucursal = $IdSucursal;
				$dataexit = $oMactualFinanzas->getListTwo();

				$MontoCuenta = $dataexit['data']->MontoCuenta;
				if ($dataexit['data']->MontoCuenta == '') {
					$MontoCuenta = 0;
				}
				$SemanaUno = $element['SemanaUno'];
				if ($element['SemanaUno'] == '') {
					$SemanaUno = 0;
				}
				$SemanaDos = $element['SemanaDos'];
				if ($element['SemanaDos'] == '') {
					$SemanaDos = 0;
				}
				$SemanaTres = $element['SemanaTres'];
				if ($element['SemanaTres'] == '') {
					$SemanaTres = 0;
				}
				$SemanaCuatro = $element['SemanaCuatro'];
				if ($element['SemanaCuatro'] == '') {
					$SemanaCuatro = 0;
				}

				$totalRowWeeks =
					$SemanaUno +
					$SemanaDos +
					$SemanaTres +
					$SemanaCuatro;

				if ($totalRowWeeks == 0 && $MontoCuenta == 0) {
					$MontoMes = 0;
				} else {
					$MontoMes = $MontoCuenta + $totalRowWeeks;
				}

				//Datos a insertar o modificar
				$oMactualFinanzas = new MactualFinanzas();
				$oMactualFinanzas->Tabla = $Tabla;
				$oMactualFinanzas->NameId = $IdName;
				$oMactualFinanzas->Id = $element[$IdName];
				$oMactualFinanzas->Anio = $Anio;
				$oMactualFinanzas->Mes = $Mes;
				$oMactualFinanzas->IdSucursal = $IdSucursal;
				$oMactualFinanzas->FechaCompleta = date('Y-m-d H:i:s');
				$oMactualFinanzas->MontoMes = $MontoMes;
				$oMactualFinanzas->MontoCuenta = $MontoCuenta;
				$oMactualFinanzas->SemanaUno = $SemanaUno;
				$oMactualFinanzas->SemanaDos = $SemanaDos;
				$oMactualFinanzas->SemanaTres = $SemanaTres;
				$oMactualFinanzas->SemanaCuatro = $SemanaCuatro;


				if ($dataexit['status']) {
					$msg = 'update';
					$oMactualFinanzas->update();
				} else {
					$msg = 'insert';
					$oMactualFinanzas->insert();
				}
			}

			foreach ($Detalle2 as $element) {
				$oMactualFinanzas = new MactualFinanzas();
				$oMactualFinanzas->Tabla = $Tabla;
				$oMactualFinanzas->NameId = $IdName;
				$oMactualFinanzas->Id = $element[$IdName];
				$oMactualFinanzas->Anio = $Anio;
				$oMactualFinanzas->Mes = $Mes;
				$oMactualFinanzas->IdSucursal = $IdSucursal;
				$dataexit = $oMactualFinanzas->getListTwo();

				$MontoCuenta = $dataexit['data']->MontoCuenta;
				if ($dataexit['data']->MontoCuenta == '') {
					$MontoCuenta = 0;
				}
				$SemanaUno = $element['SemanaUno'];
				if ($element['SemanaUno'] == '') {
					$SemanaUno = 0;
				}
				$SemanaDos = $element['SemanaDos'];
				if ($element['SemanaDos'] == '') {
					$SemanaDos = 0;
				}
				$SemanaTres = $element['SemanaTres'];
				if ($element['SemanaTres'] == '') {
					$SemanaTres = 0;
				}
				$SemanaCuatro = $element['SemanaCuatro'];
				if ($element['SemanaCuatro'] == '') {
					$SemanaCuatro = 0;
				}

				$totalRowWeeks =
					$SemanaUno +
					$SemanaDos +
					$SemanaTres +
					$SemanaCuatro;

				if ($totalRowWeeks == 0 && $MontoCuenta == 0) {
					$MontoMes = 0;
				} else {
					$MontoMes = $MontoCuenta + $totalRowWeeks;
				}

				//Datos a insertar o modificar
				$oMactualFinanzas = new MactualFinanzas();
				$oMactualFinanzas->Tabla = $Tabla;
				$oMactualFinanzas->NameId = $IdName;
				$oMactualFinanzas->Id = $element[$IdName];
				$oMactualFinanzas->Anio = $Anio;
				$oMactualFinanzas->Mes = $Mes;
				$oMactualFinanzas->IdSucursal = $IdSucursal;
				$oMactualFinanzas->FechaCompleta = date('Y-m-d H:i:s');
				$oMactualFinanzas->MontoMes = $MontoMes;
				$oMactualFinanzas->MontoCuenta = $MontoCuenta;
				$oMactualFinanzas->SemanaUno = $SemanaUno;
				$oMactualFinanzas->SemanaDos = $SemanaDos;
				$oMactualFinanzas->SemanaTres = $SemanaTres;
				$oMactualFinanzas->SemanaCuatro = $SemanaCuatro;


				if ($dataexit['status']) {
					$msg = 'update';
					$oMactualFinanzas->update();
				} else {
					$msg = 'insert';
					$oMactualFinanzas->insert();
				}
			}

			return $this->set_response([
				'status' => true,
				'data' => $msg,
				'message' => 'Se ha agregado correctamente.',
			], REST_Controller::HTTP_CREATED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
