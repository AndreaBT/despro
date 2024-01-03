<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cplanventas extends REST_Controller
{
	public $RutaPdf;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('finanzas/Mfinventas');
		$this->load->model('Mconfigservicio');
		$this->load->model('Mtiposervicio');
		$this->load->model('finanzas/Mconfigporcensubtipo');
		$this->load->library('UploadFile');
		setTimeZone($this->verification, $this->input);
	}

	public function LisData_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');

		$oMconfigservicio = new Mconfigservicio();
		$oMconfigservicio->Facturable = 'S';
		$serv = $oMconfigservicio->get_list();
		$IdVendedor = $this->get('IdVendedor');

		$count = 0;
		foreach ($serv as $element) {
			$oMfinventas = new Mfinventas();
			$oMfinventas->IdConfigS = $element->IdConfigS;
			$oMfinventas->IdVendedor = $IdVendedor;
			$oMfinventas->Anio = $Anio;
			$rowventas = $oMfinventas->get_finventas();

			$serv[$count]->Data = $rowventas['data'];
			$count++;
		}

		$contpadre = 0;
		foreach ($serv as $element) {
			$oMtiposervicio = new Mtiposervicio();
			$oMtiposervicio->IdConfigS = $element->IdConfigS;
			$oMtiposervicio->RegEstatus = 'A';
			$oMtiposervicio->IdSucursal = $IdSucursal;
			$rowSubtipos = $oMtiposervicio->get_list();
			$contador = 0;
			foreach ($rowSubtipos as $elementsub) {
				$oMconfigporcensubtipo = new Mconfigporcensubtipo();
				$oMconfigporcensubtipo->IdTipoS = $elementsub->IdTipoSer;
				$oMconfigporcensubtipo->IdVendedor = $IdVendedor;
				$oMconfigporcensubtipo->IdConfigS = $element->IdConfigS;
				$oMconfigporcensubtipo->Anio = $Anio;

				$rowconfigporcen = $oMconfigporcensubtipo->get_configprcensubtipo();

				$rowSubtipos[$contador]->Data = $rowconfigporcen['data'];
				$contador++;
			}

			$serv[$contpadre]->ListaSubtipos = $rowSubtipos;
			$contpadre++;
		}

		$data['detalle'] = $serv;


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function Vendedores_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMfinventas = new Mfinventas();
		$oMfinventas->IdSucursal = $IdSucursal;



		$data['Vendedores'] =  $oMfinventas->get_Vendedores();

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
			'Vendedor' => $this->post('IdVendedor'),
			'Anio' => $this->post('Anio')
		]);

		$v->rule('required', [
			'Vendedor',
			'Anio'
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
			$IdVendedor = $this->post('IdVendedor');
			$Anio = $this->post('Anio');
			$DetalleServ = $this->post('Detalle');

			foreach ($DetalleServ as $detalle) {

				$Mfinventas = new Mfinventas();
				$Mfinventas->IdConfigS = $detalle['IdConfigS'];
				$Mfinventas->IdVendedor = $IdVendedor;
				$Mfinventas->Anio = $Anio;
				$objventas = $Mfinventas->get_finventas();

				$Trimestre1 = $detalle['Data']['UnoT'];
				$Trimestre2 = $detalle['Data']['DosT'];
				$Trimestre3 = $detalle['Data']['TresT'];
				$Trimestre4 = $detalle['Data']['CuatroT'];
				$BaseContrato = $detalle['Data']['BaseContrato'];
				$ValorPromedio = $detalle['Data']['ValorPromedio'];
				$PorcentajeBCierre = $detalle['Data']['PorcentajeBCierre'];

				$PorcentajePrimeraR = $detalle['Data']['PorcentajePrimeraR'];
				$PorcentajeLlamadaF = $detalle['Data']['PorcentajeLlamadaF'];

				$UnoP = $detalle['Data']['UnoP'];
				$DosP = $detalle['Data']['DosP'];
				$TresP = $detalle['Data']['TresP'];
				$CuatroP = $detalle['Data']['CuatroP'];
				$TotalAnual = $detalle['Data']['TotalAnual'];

				if ($Trimestre1 == '') {
					$Trimestre1 = 0;
				}
				if ($Trimestre2 == '') {
					$Trimestre2 = 0;
				}
				if ($Trimestre3 == '') {
					$Trimestre3 = 0;
				}
				if ($Trimestre4 == '') {
					$Trimestre4 = 0;
				}
				if ($BaseContrato == '') {
					$BaseContrato = 0;
				}
				if ($ValorPromedio == '') {
					$ValorPromedio = 0;
				}
				if ($PorcentajeBCierre == '') {
					$PorcentajeBCierre = 0;
				}
				if ($PorcentajePrimeraR == '') {
					$PorcentajePrimeraR = 0;
				}
				if ($PorcentajeLlamadaF == '') {
					$PorcentajeLlamadaF = 0;
				}
				if ($UnoP == '') {
					$UnoP = 0;
				}
				if ($DosP == '') {
					$DosP = 0;
				}
				if ($TresP == '') {
					$TresP = 0;
				}
				if ($CuatroP == '') {
					$CuatroP = 0;
				}
				if ($TotalAnual == '') {
					$TotalAnual = 0;
				}

				$Mfinventas = new Mfinventas();
				$Mfinventas->IdConfigS = $detalle['IdConfigS'];
				$Mfinventas->IdVendedor = $IdVendedor;
				$Mfinventas->UnoT = $Trimestre1;
				$Mfinventas->DosT = $Trimestre2;
				$Mfinventas->TresT = $Trimestre3;
				$Mfinventas->CuatroT = $Trimestre4;
				$Mfinventas->BaseContrato = $BaseContrato;
				$Mfinventas->ValorPromedio = $ValorPromedio;
				$Mfinventas->PorcentajeBCierre = $PorcentajeBCierre;
				$Mfinventas->PorcentajePrimeraR = $PorcentajePrimeraR;
				$Mfinventas->PorcentajeLlamadaF = $PorcentajeLlamadaF;
				$Mfinventas->Anio = $Anio;
				$Mfinventas->IdSucursal = $IdSucursal;
				$Mfinventas->UnoP = $UnoP;
				$Mfinventas->DosP = $DosP;
				$Mfinventas->TresP = $TresP;
				$Mfinventas->CuatroP = $CuatroP;
				$Mfinventas->TotalAnual = $TotalAnual;

				if ($objventas['status']) {
					$Mfinventas->update();
				} else {
					$Mfinventas->Insert();
				}

				foreach ($detalle['ListaSubtipos'] as $subtipo) {
					$oMconfigporcensubtipo = new Mconfigporcensubtipo();
					$oMconfigporcensubtipo->IdConfigS = $detalle['IdConfigS'];
					$oMconfigporcensubtipo->IdVendedor = $IdVendedor;
					$oMconfigporcensubtipo->IdTipoS = $subtipo['IdTipoSer'];
					$oMconfigporcensubtipo->Anio = $Anio;
					$subtipoval =  $oMconfigporcensubtipo->get_configprcensubtipo();

					$Porcentaje = $subtipo['Data']['Porcentaje'];

					if ($Porcentaje == '') {
						$Porcentaje = 0;
					}

					$oMconfigporcensubtipo = new Mconfigporcensubtipo();
					$oMconfigporcensubtipo->IdConfigS = $detalle['IdConfigS'];
					$oMconfigporcensubtipo->IdVendedor = $IdVendedor;
					$oMconfigporcensubtipo->IdTipoS = $subtipo['IdTipoSer'];
					$oMconfigporcensubtipo->Anio = $Anio;
					$oMconfigporcensubtipo->Porcentaje = $Porcentaje;

					if ($subtipoval['status']) {
						$oMconfigporcensubtipo->update();
					} else {
						if ($Porcentaje > 0) {
							$oMconfigporcensubtipo->insert();
						}
					}
				}
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Inset',
				'message' => 'Se agreg� Correctamente',
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

	public function sumasT_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');
		$IdVendedor = $this->get('IdVendedor');

		$Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
		$Planes = array();

		$Mfinventas = new Mfinventas();
		$Mfinventas->IdSucursal = $IdSucursal;
		$Mfinventas->IdConfigS = $this->get('IdConfigS');
		$Mfinventas->Anio = $Anio;
		$Mfinventas->IdVendedor = $IdVendedor;
		$Data = $Mfinventas->suma_get();
		$valorprim = 0;
		$valorseug = 0;
		$valorter = 0;
		$valorcuatro = 0;


		if ($Data['data']->UnoT > 0) {
			$valorprim = $Data['data']->UnoT / 3;
		}
		if ($Data['data']->DosT > 0) {
			$valorseug = $Data['data']->DosT / 3;
		}
		if ($Data['data']->TresT > 0) {
			$valorter = $Data['data']->TresT / 3;
		}
		if ($Data['data']->CuatroT > 0) {
			$valorcuatro = $Data['data']->CuatroT / 3;
		}

		$PlanAnualVenta = 0;
		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$PlanAnualVenta = $valorprim;
			} else if ($i < 7) {
				$PlanAnualVenta = $valorseug;
			} else if ($i <= 9) {
				$PlanAnualVenta = $valorter;
			} else if ($i >= 10) {
				$PlanAnualVenta = $valorcuatro;
			}

			$data1['value'] = $PlanAnualVenta;
			array_push($Planes, $PlanAnualVenta);
		}

		/*$T=0;
        foreach($Res as $element){
            if($element->sumas>0){
                $T=$element->sumas;
            }
        }*/
		$data['Plan'] = $Planes;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function sumasAnual_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');
		$IdVendedor = $this->get('IdVendedor');

		$Mfinventas = new Mfinventas();
		$Mfinventas->IdVendedor = $IdVendedor;
		$Mfinventas->Anio = $Anio;

		$Res = $Mfinventas->sumaAnual_get();
		$T = 0;
		foreach ($Res as $element) {
			if ($element->sumas > 0) {
				$T = $element->sumas;
			}
		}

		$data = array();
		$total = array(
			'value' => $T
		);
		array_push($data, $T);

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}
}
