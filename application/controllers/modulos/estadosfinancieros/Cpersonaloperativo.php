<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpersonaloperativo extends REST_Controller
{
	public $RutaPdf;
	public function __construct()
	{
		parent::__construct();

		$this->load->model('estadosf/Mpersonaloperativo');
		$this->load->model('Mempresa');
		$this->load->model('Msucursal');

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

		$obj = new Mpersonaloperativo();
		$obj->Anio = $this->get('Anio');
		$obj->Mes = $this->get('Mes');
		$obj->IdEmpresa = $IdEmpresa;
		$obj->IdSucursal = $IdSucursal;
		$data['empleados'] = $obj->get_list();
		$data['empleadoscuentas'] = $obj->getEmpleadosCuentas();

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function PdfSueldosPersonalOperativo_get(){
		$this->load->library('reports/financiero/RptSueldosPersonalOp');

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$data['HeadConfig'] = array(
			'Anio' 	=> $this->get('Anio'),
			'Mes' 	=> $this->get('Mes')
		);

		$obj = new Mpersonaloperativo();
		$obj->Anio = $this->get('Anio');
		$obj->Mes = $this->get('Mes');
		$obj->IdEmpresa = $IdEmpresa;
		$obj->IdSucursal = $IdSucursal;
		$Lista = $obj->get_list();
		$Lista2= $obj->getEmpleadosCuentas();
		$data['empleados'] = $Lista;
		$data['cuentas'] = $Lista2;
		$data['IdEmpresa']		= $IdEmpresa;
        $data['IdSucursal']		= $IdSucursal;




		$pdf=new RptSueldosPersonalOp("L",'mm','Letter');
		$pdf->setDatos($data);
		$pdf->AddPage();
		$pdf->SetMargins(5,20,5);
		$pdf->contenido();
		$pdf->AddPage();
		$pdf->startPageGroup();
		$pdf->contenido2();
		$pdf->Output();


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
			'Anio' => $this->post('Anio'),
			'Mes' => $this->post('Mes'),
		]);

		$v->rule('required', [
			'Anio',
			'Mes',

		])->message('El campo {field} es requerido.');


		if ($v->validate()) {
			$Anio = $this->post('Anio');
			$Mes = $this->post('Mes');
			$Detalle = $this->post('Detalle');

			$oMestadofupdate = new Mpersonaloperativo();
			$oMestadofupdate->Anio = $Anio;
			$oMestadofupdate->Mes = $Mes;
			$oMestadofupdate->IdEmpresa = $IdEmpresa;
			$oMestadofupdate->IdSucursal = $IdSucursal;
			$oMestadofupdate->delete();

			foreach ($Detalle as $element) {
				$sueldo = 0.00;
				$obliga = 0.00;
				$presta = 0.00;
				$comisi = 0.00;
				$horase = 0.00;
				$descue = 0.00;
				$total  = 0.00;

				if ($element['Sueldo'] != '') {
					$sueldo = $element['Sueldo'];
				}
				if ($element['Obligaciones'] != '') {
					$obliga = $element['Obligaciones'];
				}
				if ($element['Prestaciones'] != '') {
					$presta = $element['Prestaciones'];
				}
				if ($element['ComisionesBonos'] != '') {
					$comisi = $element['ComisionesBonos'];
				}
				if ($element['Extras'] != '') {
					$horase = $element['Extras'];
				}
				if ($element['Descuentos'] != '') {
					$descue = $element['Descuentos'];
				}
				if ($element['Total'] != '') {
					$total = $element['Total'];
				}

				$oMestadofupdate = new Mpersonaloperativo();
				$oMestadofupdate->Anio              = $Anio;
				$oMestadofupdate->Mes               = $Mes;
				$oMestadofupdate->IdEmpresa         = $IdEmpresa;
				$oMestadofupdate->IdSucursal        = $IdSucursal;
				$oMestadofupdate->Nombre            = $element['Nombre'];
				$oMestadofupdate->Sueldo            = $sueldo;
				$oMestadofupdate->Obligaciones      = $obliga;
				$oMestadofupdate->Prestaciones      = $presta;
				$oMestadofupdate->ComisionesBonos   = $comisi;
				$oMestadofupdate->Extras            = $horase;
				$oMestadofupdate->Descuentos        = $descue;
				$oMestadofupdate->Total             = $total;
				$oMestadofupdate->insert();
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
