<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cfinanciero extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Mempresa');
		$this->load->model('Mtiposervicio');
		$this->load->model('Mconfigservicio');
		$this->load->model('Mclientes');
		$this->load->model('Mclientesucursal');
		$this->load->library('reports/financiero/RptCostoca');
		$this->load->library('FinanzasActualizacion');
		$this->load->library('EstadoFinanciero');





		$this->load->model('Msucursal');

		setTimeZone($this->verification, $this->input);
	}

	public function Costoca_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$TipoBusqueda = "";

		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 1, $TipoBusqueda, $IdEmpresa);

		/*
        $Mfactura=new Mfactura();
        $Mfactura->IdFactura=$Id;
        $Mfactura->RegEstatus='A';
        $response=$Mfactura->get_factura();*/

		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Plan Costo G & A';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = '';
		$dataResp['ClienteSucursal'] = '';
		$dataResp['Anio'] = $Anio;
		$dataResp['Mes'] = $Mes;
		$dataResp['TipoServicio'] = '';
		$dataResp['Lista'] = $row;


		$pdf = new RptCostoca("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		ob_end_clean();
		$pdf->Output();
		//}
	}

	public function CostoVenta_get()
	{
		$this->load->library('reports/financiero/RptCostoventa');
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$TipoBusqueda = "";

		//directos
		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 2, 1, $IdEmpresa);
		//indirectos
		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row2 = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 2, 2, $IdEmpresa);

		/*
        $Mfactura=new Mfactura();
        $Mfactura->IdFactura=$Id;
        $Mfactura->RegEstatus='A';
        $response=$Mfactura->get_factura();*/

		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Plan Costo Depto. Ventas';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = '';
		$dataResp['ClienteSucursal'] = '';
		$dataResp['Anio'] = $Anio;
		$dataResp['Mes'] = $Mes;
		$dataResp['TipoServicio'] = '';
		$dataResp['Lista'] = $row;
		$dataResp['Lista2'] = $row2;


		$pdf = new RptCostoventa("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		ob_end_clean();
		$pdf->Output();
		//}
	}

	public function CostoOperacion_get()
	{
		$this->load->library('reports/financiero/RptCostooperacion');

		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$TipoBusqueda = "";


		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 3, $TipoBusqueda, $IdEmpresa);

		/*
        $Mfactura=new Mfactura();
        $Mfactura->IdFactura=$Id;
        $Mfactura->RegEstatus='A';
        $response=$Mfactura->get_factura();*/

		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Plan Costo Depto. Operaciones';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = '';
		$dataResp['ClienteSucursal'] = '';
		$dataResp['Anio'] = $Anio;
		$dataResp['Mes'] = $Mes;
		$dataResp['TipoServicio'] = '';
		$dataResp['Lista'] = $row;


		$pdf = new RptCostooperacion("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		ob_end_clean();
		$pdf->Output();
		//}
	}

	public function CostoVehiculo_get()
	{
		$this->load->library('reports/financiero/RptCostovehiculo');

		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$TipoBusqueda = "";


		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 4, "", $IdEmpresa);

		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Costo Vehículo Operaciones';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = '';
		$dataResp['ClienteSucursal'] = '';
		$dataResp['Anio'] = $Anio;
		$dataResp['Mes'] = $Mes;
		$dataResp['TipoServicio'] = '';
		$dataResp['Lista'] = $row;

		$pdf = new RptCostovehiculo("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		ob_end_clean();
		$pdf->Output();
		//}
	}

	public function CostoFinanciero_get()
	{
		$this->load->library('reports/financiero/RptCostofinanciero');

		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$TipoBusqueda = "";

		//directos
		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 5, 1, $IdEmpresa);
		//indirectos
		$oFinanzasActualizacion = new FinanzasActualizacion();
		$row2 = $oFinanzasActualizacion->ActualizacionCostos($IdSucursal, $Anio, $Mes, 5, 2, $IdEmpresa);



		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Costos Financieros';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = '';
		$dataResp['ClienteSucursal'] = '';
		$dataResp['Anio'] = $Anio;
		$dataResp['Mes'] = $Mes;
		$dataResp['TipoServicio'] = '';
		$dataResp['Lista'] = $row;
		$dataResp['Lista2'] = $row2;


		$pdf = new RptCostofinanciero("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		ob_end_clean();
		$pdf->Output();
		//}
	}

	public function EstadoFinanciero_get()
	{
		$this->load->library('reports/financiero/RptEstadofinanciero');

		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$IdConfigS = $this->get('IdConfigS');
		$IdSubtipoServ = $this->get('IdTipoServ');
		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$IdCliente = $this->get('IdCliente');
		$IdClienteS = $this->get('IdClienteS');
		$IdContrato = $this->get('IdContrato');
		$Type = $this->get('Tipo');

		if (empty($Type)) {
			$Type = 1;
		}

		$Servicio = "Todos";
		$Clientes = "";
		$Sucursal = "";
		//obtener tipos de servicios

		if ($Type == 1) {
			$oEstadoFinanciero = new EstadoFinanciero();
			$row = $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal, $Anio, $IdConfigS, $IdSubtipoServ, $Mes, $IdCliente, $IdClienteS, $IdContrato, $Type, $IdEmpresa);
		} else if ($Type == 2) {
			$oEstadoFinanciero = new EstadoFinanciero();
			$row = $oEstadoFinanciero->GetEstadoFinancieroTodos($IdSucursal, $Anio, $Mes, $IdCliente, $IdClienteS, $IdContrato, $Type, $IdEmpresa);
		}

		$oMconfigservicio = new Mconfigservicio();
		$oMconfigservicio->IdConfigS = $IdConfigS;
		$data = $oMconfigservicio->get_configservicio();
		if ($data['status']) {
			$Servicio = $data['data']->Nombre;
			if (!empty($IdSubtipoServ)) {
				$oMtiposervicio = new Mtiposervicio();
				$oMtiposervicio->IdConfigS = $IdConfigS;
				$oMtiposervicio->IdTipoSer = $IdSubtipoServ;
				$data2 = $oMtiposervicio->get_tiposervicio();

				if ($data2['status']) {
					$Servicio = $data['data']->Nombre . ' - ' . $data2['data']->Concepto;
				}
			}
		}

		if (!empty($IdCliente)) {
			$oMclientes = new Mclientes();
			$oMclientes->IdCliente = $IdCliente;
			$data = $oMclientes->get_clientes();

			if ($data['status']) {
				$Clientes = $data['data']->Nombre;
				if (!empty($IdClienteS)) {
					$oMclientesucursal = new Mclientesucursal();
					$oMclientesucursal->IdClienteS = $IdClienteS;
					$data2 = $oMclientesucursal->get_cliente();
					if ($data2['status']) {
						$Sucursal = $data2['data']->Nombre;
					}
				}
			}
		}

		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Estado financiero';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = $Clientes;
		$dataResp['ClienteSucursal'] = $Sucursal;
		$dataResp['Anio'] = $Anio;
		$dataResp['TipoServicio'] = $Servicio;
		$dataResp['Lista'] = $row;


		$pdf = new RptEstadofinanciero("L", 'mm', 'Letter');
		$pdf->setDatos($dataResp);
		$pdf->AddPage();
		//$pdf->HeaderG();
		$pdf->SetMargins(5, 20, 5);
		$pdf->contenido();
		$pdf->Output();
		//}
	}

	public function EstadoFinGen_get()
	{
		$this->load->library('reports/financiero/RptEstadofingen');
		//!PDF ANTIGUO
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$IdConfigS = $this->get('IdConfigS');
		$IdSubtipoServ = $this->get('IdTipoServ');
		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$IdCliente = $this->get('IdCliente');
		$IdClienteS = $this->get('IdClienteS');
		$IdContrato = $this->get('IdContrato');
		$Type = $this->get('Tipo');

		if (empty($Type)) {
			$Type = 1;
		}

		$Servicio = "Todos";
		$Clientes = "";
		$Sucursal = "";
		//obtener tipos de servicios

		$oEstadoFinanciero = new EstadoFinanciero();
		$row = $oEstadoFinanciero->GetEstadoFinancieroTodos($IdSucursal, $Anio, $Mes, $IdCliente, $IdClienteS, $IdContrato, 2, $IdEmpresa);

		$oMconfigservicio = new Mconfigservicio();
		$oMconfigservicio->IdConfigS = $IdConfigS;
		$data = $oMconfigservicio->get_configservicio();
		if ($data['status']) {
			$Servicio = $data['data']->Nombre;
			if (!empty($IdSubtipoServ)) {
				$oMtiposervicio = new Mtiposervicio();
				$oMtiposervicio->IdConfigS = $IdConfigS;
				$oMtiposervicio->IdTipoSer = $IdSubtipoServ;
				$data2 = $oMtiposervicio->get_tiposervicio();
				if ($data2['status']) {
					$Servicio = $data['data']->Nombre . ' - ' . $data2['data']->Concepto;
				}
			}
		}

		if (!empty($IdCliente)) {
			$oMclientes = new Mclientes();
			$oMclientes->IdCliente = $IdCliente;
			$data = $oMclientes->get_clientes();

			if ($data['status']) {
				$Clientes = $data['data']->Nombre;
				if (!empty($IdClienteS)) {
					$oMclientesucursal = new Mclientesucursal();
					$oMclientesucursal->IdClienteS = $IdClienteS;
					$data2 = $oMclientesucursal->get_cliente();
					if ($data2['status']) {
						$Sucursal = $data2['data']->Nombre;
					}
				}
			}
		}


		//if($response['status']){
		#obtener datos del token
		$dataResp['IdEmpresa'] = $IdEmpresa;
		$dataResp['IdSucursal'] = $IdSucursal;
		$dataResp['Titulo'] = 'Estado financiero';
		$dataResp['Folio'] = '';
		$dataResp['Cliente'] = $Clientes;
		$dataResp['ClienteSucursal'] = $Sucursal;
		$dataResp['Anio'] = $Anio;
		$dataResp['TipoServicio'] = $Servicio;
		$dataResp['Lista'] = $row;

		// var_dump($dataResp);

		// $pdf=new RptEstadofingen("L",'mm','Letter');
		// $pdf->setDatos($dataResp);
		// $pdf->AddPage();
		// //$pdf->HeaderG();
		// $pdf->SetMargins(5,20,5);
		// $pdf->contenido();
		// $pdf->Output(); 
		//}
		//!PDF ANTIGUO FIN



	}
}
