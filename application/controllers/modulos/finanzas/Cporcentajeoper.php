<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cporcentajeoper extends REST_Controller
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
		$this->load->library('Finanzas');

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

		$IdConfigS = $this->get('IdConfigS');
		$IdSubtipoServ = $this->get('IdTipoServ');
		$Anio = $this->get('Anio');

		$ofinanzas = new Finanzas();
		$row = $ofinanzas->PorcentajeOperacionCalculo($IdSucursal, $Anio, $IdConfigS, $IdSubtipoServ);

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
			'IdConfigS' => $this->post('IdConfigS'),
			'IdTipoServ' => $this->post('IdTipoServ'),
			'Anio' => $this->post('Anio')

		]);

		$v->rule('required', [
			'IdConfigS',
			'IdTipoServ',
			'Anio'
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {
			$IdConfigS = $this->post('IdConfigS');
			$IdTipoServ = $this->post('IdTipoServ');
			$Anio = $this->post('Anio');
			$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


			$Detalle = $this->post('ListaDetalle');

			foreach ($Detalle as $element) {
				$oMporcentajeoperacion = new Mporcentajeoperacion();
				$oMporcentajeoperacion->IdTipoSer = $IdConfigS;
				$oMporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
				$oMporcentajeoperacion->Anio = $Anio;
				$oMporcentajeoperacion->IdSucursal = $IdSucursal;
				$oMporcentajeoperacion->Descripcion = $element['Nombre'];

				$rowval =   $oMporcentajeoperacion->get_list();

				$AnioAnt = $element['AnioAnterior'];
				$PorceAnt = $element['PorcenAnioAnte'];

				if ($AnioAnt == '') {
					$AnioAnt = 0;
				}
				if ($PorceAnt == '') {
					$PorceAnt = 0;
				}

				$oMporcentajeoperacion = new Mporcentajeoperacion();
				$oMporcentajeoperacion->IdTipoSer = $IdConfigS;
				$oMporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
				$oMporcentajeoperacion->Anio = $Anio;
				$oMporcentajeoperacion->IdSucursal = $IdSucursal;
				$oMporcentajeoperacion->IdPlanFactura = 0;
				$oMporcentajeoperacion->Anio = $Anio;
				$oMporcentajeoperacion->Descripcion = $element['Nombre'];
				$oMporcentajeoperacion->AnioAnterior = $AnioAnt;
				$oMporcentajeoperacion->PorcentajeAnterior = $PorceAnt;
				$oMporcentajeoperacion->PorcentajeAnual = $element['PorcenAnual'];
				$oMporcentajeoperacion->PrimerT = $element['PrimerT'];
				$oMporcentajeoperacion->SegundoT = $element['SegundoT'];
				$oMporcentajeoperacion->TercerT = $element['TercerT'];
				$oMporcentajeoperacion->CuartoT = $element['CuartoT'];
				$oMporcentajeoperacion->IdConceptoOperacion = 0;
				if (count($rowval) > 0) {
					$oMporcentajeoperacion->update();
					$msg = "update";
				} else {
					$oMporcentajeoperacion->insert();
					$msg = "insert";
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
