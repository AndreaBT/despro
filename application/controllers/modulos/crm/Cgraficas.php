<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cgraficas extends REST_Controller
{
	public $RutaPdf;
	public function __construct()
	{
		parent::__construct();

		//crm
		$this->load->model('finanzas/Mfinventas');
		$this->load->model('crm/Mprocesovendedor');
		$this->load->model('crm/Mseguimientocliente');
		$this->load->model('crm/Mcrmproceso');
		$this->load->model('crm/Moportunidades');

		$this->load->model('crm/Mcrmforecast');



		setTimeZone($this->verification, $this->input);
	}


	public function getplanvsact_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		//datos gráfica

		$Anio = $this->get('Anio');
		$IdVendedor = $this->get('IdVendedor');
		$IdConfigS = $this->get('IdConfigS');
		$Colores = array("#f07173", "#8edce7", "#8adfb9");

		$Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
		$Planes = array();
		$Real = array();
		$Lismes = array();

		$Mfinventas = new Mfinventas();
		$Mfinventas->IdConfigS = $IdConfigS;
		$Mfinventas->IdSucursal = $IdSucursal;
		$Mfinventas->Anio = $Anio;
		$Mfinventas->IdVendedor = $IdVendedor;
		$Data = $Mfinventas->get_finventas();
		$valorprim = 0;
		$valorseug = 0;
		$valorter = 0;
		$valorcuatro = 0;
		$valorter = 0;


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
		$dataplan = '';
		$datavendido = '';

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


			$Mseguimientocliente = new Mseguimientocliente();
			$Mseguimientocliente->IdSucursal = $IdSucursal;
			$Mseguimientocliente->Anio = $Anio;
			$Mseguimientocliente->Estatus = 'Vendido';
			$Mseguimientocliente->Nombre = 'Cierre';
			$Mseguimientocliente->IdTrabajador = $IdVendedor;
			$Mseguimientocliente->IdConfigS = $IdConfigS;


			if ($i < 10) {
				$Mseguimientocliente->Fecha = $Anio . '-0' . $i;
			} else {
				$Mseguimientocliente->Fecha = $Anio . '-' . $i;
			}
			$Resp = $Mseguimientocliente->get_list_grafica1();
			$MontoTotal = 0;
			if ($Resp['status']) {
				if ($Resp['data']->Vendido != '') {
					$MontoTotal = $Resp['data']->Vendido;
				}
			}

			//$data1['value'] = $PlanAnualVenta;
			//$data2['value'] = $MontoTotal;
			$data3['label'] = $Meses[$i - 1];
			array_push($Planes, $PlanAnualVenta);
			array_push($Real,  $MontoTotal);
			array_push($Lismes, $data3);
		}
		$suma = 0;
		if ($PlanAnualVenta > 0) {
			$suma = $valorprim + $valorprim + $valorprim + $valorseug + $valorseug + $valorseug + $valorter + $valorter + $valorter + $valorcuatro + $valorcuatro + $valorcuatro;
		}
		// var_dump($suma);

		$totalfin = array();
		$sumafinal = array(
			'value' => $suma
		);
		array_push($totalfin, $sumafinal);


		$data['Plan'] = $Planes;
		$data['Real'] = $Real;
		$data['Meses'] = $Lismes;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'totalf' => $totalfin,

			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function getventas_get()
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
		$IdVendedor = $this->get('IdVendedor');
		$IdTipoProceso = $this->get('IdTipoProceso');

		if (empty($Mes)) {
			$Mes = date('m');
		}

		$AnioInicio = $Anio . '-' . $Mes . '-01';
		$L = new DateTime($AnioInicio);
		$UltimoDia = $L->format('t');

		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdSucursal = $IdSucursal;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->Estatus = 'Vendido';
		//$Mseguimientocliente->Nombre='Cierre';
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		$Mseguimientocliente->FechaI = $Anio . '-01-01';
		$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;
		//$Mseguimientocliente->IdTipoProceso=$IdTipoProceso;

		$Resp = $Mseguimientocliente->get_list_grafica1();

		$VentaTotal = 0;
		if ($Resp['status']) {
			if ($Resp['data']->Vendido != '') {
				$VentaTotal = $Resp['data']->Vendido;
			}
		}


		$Mseguimientocliente->Nombre = 'Propuestas';
		$Mseguimientocliente->Estatus = '';
		$Resp2 = $Mseguimientocliente->get_list_grafica1();

		$PropuestaT = 0;
		if ($Resp2['status']) {
			if ($Resp2['data']->Vendido != '') {
				$PropuestaT = $Resp2['data']->Vendido;
			}
		}


		//Grafica de Reloj

		$Mfinventas = new Mfinventas();
		$Mfinventas->IdSucursal = $IdSucursal;
		$Mfinventas->Anio = $Anio;
		$Mfinventas->IdVendedor = $IdVendedor;
		$Data = $Mfinventas->get_finventas();
		$valorprim = 0;
		$valorseug = 0;
		$valorter = 0;
		$valorcuatro = 0;
		$valorter = 0;

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
		$count = 1;
		for ($i = 1; $i <= 12; $i++) {
			if ($i < 4) {
				$PlanAnualVenta += $valorprim;
			}
			if ($i > 3) {
				$PlanAnualVenta += $valorseug;
			}
			if ($i > 9) {
				$PlanAnualVenta += $valorter;
			}
			if ($i >= 10) {
				$PlanAnualVenta += $valorcuatro;
			}

			if ($i == $Mes) {
				break;
			}
		}

		$Resta = (.20 * $PlanAnualVenta);
		$Inicio = $PlanAnualVenta - $Resta;

		//////////////////////nuevo-andrea
		//$total=$PlanAnualVenta*.100;

		$guardado = array();
		$ventas = array();
		$Inci = array();
		$metas = array(
			'value' => $Inicio
		);
		$ventasVendidas = array(
			'value' => $VentaTotal
		);
		$Ini = array(
			'value' => $Inicio
		);

		array_push($ventas, $ventasVendidas);
		array_push($guardado, $metas);
		array_push($Inci, $Ini);

		////////////////////termina nuevo-andrea

		$data['Vendido'] = $VentaTotal;
		$data['Propuesta'] = $PropuestaT;

		$data['Inicio'] = $Inicio;
		$data['PlanAnualVenta'] = $PlanAnualVenta;
		$data['Suma'] = $data['PlanAnualVenta'] = $PlanAnualVenta;;



		return $this->set_response([
			'status' => true,
			'Inicio' => $Inci,
			'planmetas' => $guardado,
			'ventas' => $ventas,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	////////////////////NUEVOOOOO
	public function graficametas_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$IdVendedor = $this->get('IdVendedor');
		$IdTipoProceso = $this->get('IdConfigS');

		if (empty($Mes)) {
			$Mes = date('m');
		}

		$AnioInicio = $Anio . '-' . $Mes . '-01';
		$L = new DateTime($AnioInicio);
		$UltimoDia = $L->format('t');

		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		$Mseguimientocliente->IdConfigS = $IdTipoProceso;
		$Mseguimientocliente->Fecha = $Mes;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->FechaI = $Anio . '-01-01';
		$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;


		$Mseguimientocliente->Estatus = "Abierta";

		$Mseguimientocliente->IdSucursal = $IdSucursal;


		$contador = 0;
		$Res = $Mseguimientocliente->get_oportinidades();

		foreach ($Res as $element) {
			if ($element->Estatus == "Abierta") {
				$contador += 1;
			}
		}



		$data = array();
		$resu = array(
			'value' => $contador
		);
		array_push($data, $resu);

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function graficametasVendidas_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$IdVendedor = $this->get('IdVendedor');
		$IdTipoProceso = $this->get('IdConfigS');

		if (empty($Mes)) {
			$Mes = date('m');
		}

		$AnioInicio = $Anio . '-' . $Mes . '-01';
		$L = new DateTime($AnioInicio);
		$UltimoDia = $L->format('t');

		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		$Mseguimientocliente->IdConfigS = $IdTipoProceso;
		$Mseguimientocliente->Fecha = $Mes;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->FechaI = $Anio . '-01-01';
		$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;


		$Mseguimientocliente->Estatus = "Vendido";
		$Mseguimientocliente->Nombre = "Cierre";

		$Mseguimientocliente->IdSucursal = $IdSucursal;


		$contador = 0;
		$Res = $Mseguimientocliente->get_oportinidadesVendidas();
		foreach ($Res as $element) {
			if ($element->Estatus == "Vendido" && $element->Nombre == "Cierre") {
				$contador += 1;
			}
		}
		$suma = 0;
		$Res = $Mseguimientocliente->get_oportinidadesVendidasSuma();
		foreach ($Res as $element) {
			if ($element->suma > 0) {
				$suma = $element->suma;
			}
		}

		$contador2 = 0;
		$UnoP = 0;
		$DosP = 0;
		$TresP = 0;
		$CuatroP = 0;
		$ValorT = array();
		$Res2 = $Mseguimientocliente->get_totalsumacrm();
		foreach ($Res2 as $element) {
			if ($element->Estatus == "Vendido" && $element->Nombre == "Cierre") {
				$contador2 += 1;
				if ($element->Fecha < 4) {
					$UnoP = $element->UnoT / 3;
					$ValorT[] = $UnoP;
					//print_r("hola");
					//var_dump($UnoP);
				}
				if ($element->Fecha < 7) {
					$DosP = $element->DosT / 3;
					$ValorT[] = $DosP;
					//print_r("hola");
					//var_dump($DosP);
				}
				if ($element->Fecha <= 9) {
					$TresP = $element->TresT / 3;
					$ValorT[] = $TresP;
					//print_r("hola");
					//var_dump($DosP);
				}
				if ($element->Fecha >= 10) {
					$CuatroP = $element->CuatroT / 3;
					$ValorT[] = $CuatroP;
					//print_r("hola");
					//var_dump($CuatroP);
				}
			}
		}
		$data = array();
		$resu = array(
			'value' => $contador
		);
		$data2 = array();
		$resu2 = array(
			'value' => $suma
		);
		$data3['value'] = $ValorT;


		array_push($data, $resu);
		array_push($data2, $resu2);


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'data2' => $data2,
			'data3' => $ValorT,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}


	/////////////////////FIN DE NUEVO

	public function getprocesoventa_get()
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
		$IdTipoProceso = $this->get('IdTipoProceso');
		$IdVendedor = $this->get('IdVendedor');

		$Type = $this->get('Tipo');
		if (empty($Type)) {
			$Type = 1;
		}
		if (empty($Mes)) {
			$Mes = date('m');
		}

		$oMcrmproceso = new Mcrmproceso();
		$oMcrmproceso->IdTipoProceso = $IdTipoProceso;
		$Lista = $oMcrmproceso->get_list();

		$AnioInicio = $Anio . '-' . $Mes . '-01';
		$L = new DateTime($AnioInicio);
		$UltimoDia = $L->format('t');

		$Total = 0;
		$Listado = array();
		$dataset = array();
		foreach ($Lista as $element) {

			$Mseguimientocliente = new Mseguimientocliente();
			$Mseguimientocliente->IdSucursal = $IdSucursal;
			$Mseguimientocliente->Anio = $Anio;
			$Mseguimientocliente->Estatus = '';
			$Mseguimientocliente->IdTrabajador = $IdVendedor;
			$Mseguimientocliente->FechaI = $Anio . '-' . $Mes . '-01';
			$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;
			$Mseguimientocliente->IdProceso = $element->IdCrmProceso;

			$Resp = $Mseguimientocliente->get_list_grafica1(2);
			if ($Resp['status']) {
				$Total = $Resp['data']->Total;
			}

			$data2['label'] = $element->Nombre;
			// $data2['value'] =$Total;
			// $data2['color'] =$element->Color;

			$data3['value'] = $Total;
			$data3['color'] = $element->Color;

			array_push($Listado, $data2);
			array_push($dataset, $data3);
		}

		return $this->set_response([
			'status' => true,
			'data' => $Listado,
			'dataset' => $dataset,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function getforecast_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Anio = $this->get('Anio');

		$meses = array(
			'', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
			'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril'
		);

		$MesActual = date("n");
		$MesUno = '';
		$MesDos = '';
		$MesTres = '';
		$MesCuatro = '';
		for ($i = 0; $i < count($meses) + 1; $i++) {
			if ($i >= $MesActual) {
				if ($MesUno == '') {
					$MesUno = $meses[$i];
				} else if ($MesDos == '') {
					$MesDos = $meses[$i];
				} else if ($MesTres == '') {

					$MesTres = $meses[$i];
				} else if ($MesCuatro == '') {
					$MesCuatro = 'No definido';
				}
			}
		}

		$Mcrmforecast = new Mcrmforecast();
		$Mcrmforecast->IdVendedor = $this->get('IdVendedor');
		$Mcrmforecast->IdSucursal = $IdSucursal;
		$Mcrmforecast->Anio = $this->get('Anio');
		$datares =  $Mcrmforecast->get_recovery();


		$data['MesUno'] = $MesUno;
		$data['MesDos'] = $MesDos;
		$data['MesTres'] = $MesTres;
		$data['MesCuatro'] = $MesCuatro;

		$data['Uno'] = $datares['data']->Uno;
		$data['Dos'] = $datares['data']->Dos;
		$data['Tres'] = $datares['data']->Tres;
		$data['Cuatro'] = $datares['data']->Cuatro;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	/////////////////API PARA SOLO EL CONTADOR DE VENTAS-grafica de abajo en el CRM
	public function graficaGross_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


		//datos gráfica 

		$Anio = $this->get('Anio');
		$IdVendedor = $this->get('IdVendedor');
		$IdTipoProceso = $this->get('IdConfigS');


		$Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
		//datos gráfica cierre

		$Planes = array();
		$Real = array();
		$Lismes = array();


		$Mfinventas = new Mfinventas();
		$Mfinventas->IdSucursal = $IdSucursal;
		$Mfinventas->Anio = $Anio;
		$Mfinventas->IdConfigS = $IdTipoProceso;
		$Mfinventas->IdVendedor = $IdVendedor;
		$Data = $Mfinventas->get_finventas();
		$UnoT = 0;
		$DosT = 0;
		$TresT = 0;
		$CuatroT = 0;
		$ValContrato = 0;

		if ($Data['data']->UnoP > 0) {
			$UnoT = $Data['data']->UnoT;
			$ValContrato = $Data['data']->ValorPromedio;
		}
		if ($Data['data']->DosP > 0) {
			$DosT = $Data['data']->DosT;
		}
		if ($Data['data']->TresP > 0) {
			$TresT = $Data['data']->TresT;
		}
		if ($Data['data']->CuatroP > 0) {
			$CuatroT = $Data['data']->CuatroT;
		}

		$Plan = 0;
		$Plan2 = 0;
		$Plan3 = 0;
		$Plan4 = 0;
		$Plan1 = 0;

		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$Plan = ($UnoT / $ValContrato) / 3;
				$Plan1 = $Plan;
			} else if ($i < 7) {
				$Plan = ($DosT / $ValContrato) / 3;
				$Plan2 = $Plan;
			} else if ($i <= 9) {
				$Plan = ($TresT / $ValContrato) / 3;
				$Plan3 = $Plan;
			} else if ($i >= 10) {
				$Plan = ($CuatroT / $ValContrato) / 3;
				$Plan4 = $Plan;
			}

			$Mseguimientocliente = new Mseguimientocliente();
			$Mseguimientocliente->IdSucursal = $IdSucursal;
			$Mseguimientocliente->IdConfigS = $this->get('IdConfigS');
			$Mseguimientocliente->Anio = $Anio;
			$Mseguimientocliente->Estatus = 'Vendido';
			$Mseguimientocliente->Nombre = 'Cierre';
			$Mseguimientocliente->IdTrabajador = $IdVendedor;

			if ($i < 10) {
				$Mseguimientocliente->Fecha = $Anio . '-0' . $i;
			} else {
				$Mseguimientocliente->Fecha = $Anio . '-' . $i;
			}
			$Total = 0;

			$Res = $Mseguimientocliente->get_todo();
			$data1 = array();
			foreach ($Res as $element) {
				if ($element->Estatus == "Vendido" && $element->Nombre == "Cierre") {
					$Total += 1;
				}
			}
			//var_dump($Res);
			$PropCierre = array();
			$PropCierre1 = array();
			$PropCierre2 = array();
			$PropCierre3 = array();

			$data1['value'] = $Plan;
			$data2['value'] = $Total;
			$data3['label'] = $Meses[$i - 1];
			array_push($Planes, $Plan);
			array_push($Real, $Total);
		}

		array_push($PropCierre, $Plan1);
		array_push($PropCierre1, $Plan2);
		array_push($PropCierre2, $Plan3);
		array_push($PropCierre3, $Plan4);
		//datos gráfica cierre fin 



		////////////////////////////////DATOS DE LA TABLA N° de propuestas Anual. 
		$PropAnual = array();
		$PropAnual1 = array();
		$PropAnual2 = array();
		$PropAnual3 = array();
		//$Res= $Mfinventas->grafCRM_get();


		$PropCierreV = 0;

		if ($Data['data']->UnoP > 0) {
			$PropCierreV = $Data['data']->PorcentajeBCierre / 100;
		}


		$Prop1 = 0.0;
		$Prop2 = 0.0;
		$Prop3 = 0.0;
		$Prop4 = 0.0;



		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$Prop1 = ($Plan1 / $PropCierreV);
			} else if ($i < 7) {
				$Prop2 = ($Plan2 / $PropCierreV);
			} else if ($i <= 9) {
				$Prop3 = ($Plan3 / $PropCierreV);
			} else if ($i >= 10) {
				$Prop4 = ($Plan4 / $PropCierreV);
			}

			//$dataProp['value']=$Prop;

		}
		array_push($PropAnual, $Prop1);
		array_push($PropAnual1, $Prop2);
		array_push($PropAnual2, $Prop3);
		array_push($PropAnual3, $Prop4);

		$data['PropuestaAnual'] = $PropAnual;
		$data['PropuestaAnual1'] = $PropAnual1;
		$data['PropuestaAnual2'] = $PropAnual2;
		$data['PropuestaAnual3'] = $PropAnual3;

		///////////////Grfáfica de propuesta Anual $$
		/////plan 
		$DineroPropAn = 0;
		$Res = $Mfinventas->grafCRM_get();
		$ProbAn = 0;
		$DineroPropA = array();



		if ($Data['data']->UnoP > 0) {
			$DineroPropAn = $Data['data']->ValorPromedio;
		}


		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$ProbAn = $Prop1 * $DineroPropAn;
			} else if ($i < 7) {
				$ProbAn = $Prop2 * $DineroPropAn;
			} else if ($i <= 9) {
				$ProbAn = $Prop3 * $DineroPropAn;
			} else if ($i >= 10) {
				$ProbAn = $Prop4 * $DineroPropAn;
			}

			$dataDinero['value'] = $ProbAn;
			array_push($DineroPropA, $ProbAn);
		}
		$planAnual = array();

		for ($i = 1; $i < count($Meses) + 1; $i++) {
			$Mseguimientocliente = new Mseguimientocliente();
			$Mseguimientocliente->IdSucursal = $IdSucursal;
			$Mseguimientocliente->IdConfigS = $IdTipoProceso;
			$Mseguimientocliente->Anio = $Anio;
			$Mseguimientocliente->IdTrabajador = $IdVendedor;

			if ($i < 10) {
				$Mseguimientocliente->Fecha = $Anio . '-0' . $i;
			} else {
				$Mseguimientocliente->Fecha = $Anio . '-' . $i;
			}

			$contadorAnual = 0;
			$contadorAnual2 = 0;

			$Res = $Mseguimientocliente->get_actualPropAnual();
			$data5 = array();
			foreach ($Res as $element) {
				if ($element->sumaProp >= 0) {
					$contadorAnual = $element->sumaProp;
				}
			}
			$data5['value'] = $contadorAnual;
			array_push($planAnual, $contadorAnual);
		}

		////////////////////////////////GRÁFICA REUNIONES 
		$Reuniones = 0;
		$ReunionOperacion = 0;
		$arrayReu = array();

		$reunion1 = 0;
		$reunion2 = 0;
		$reunion3 = 0;
		$reunion4 = 0;

		$arrayReu1 = array();
		$arrayReu2 = array();
		$arrayReu3 = array();
		$arrayReu4 = array();

		if ($Data['data']->UnoP > 0) {
			$Reuniones = $Data['data']->PorcentajePrimeraR / 100;
		}

		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$ReunionOperacion = $Prop1 / $Reuniones;
				$reunion1 = $ReunionOperacion;
			} else if ($i < 7) {
				$ReunionOperacion = $Prop2 / $Reuniones;
				$reunion2 = $ReunionOperacion;
			} else if ($i <= 9) {
				$ReunionOperacion = $Prop3 / $Reuniones;
				$reunion3 = $ReunionOperacion;
			} else if ($i >= 10) {
				$ReunionOperacion = $Prop4 / $Reuniones;
				$reunion4 = $ReunionOperacion;
			}
			$dataReuniones['value'] = $ReunionOperacion;
			array_push($arrayReu, $ReunionOperacion);
		}

		array_push($arrayReu1, $reunion1);
		array_push($arrayReu2, $reunion2);
		array_push($arrayReu3, $reunion3);
		array_push($arrayReu4, $reunion4);

		$data['ReunionMes3'] = $arrayReu1;
		$data['ReunionMes7'] = $arrayReu2;
		$data['ReunionMes9'] = $arrayReu3;
		$data['ReunionMes10'] = $arrayReu4;

		//ACTUAL
		$actualReunion = array();

		for ($i = 1; $i < count($Meses) + 1; $i++) {
			$Mseguimientocliente = new Mseguimientocliente();
			$Mseguimientocliente->IdSucursal = $IdSucursal;
			$Mseguimientocliente->IdConfigS = $IdTipoProceso;
			$Mseguimientocliente->Anio = $Anio;
			$Mseguimientocliente->IdTrabajador = $IdVendedor;

			if ($i < 10) {
				$Mseguimientocliente->Fecha = $Anio . '-0' . $i;
			} else {
				$Mseguimientocliente->Fecha = $Anio . '-' . $i;
			}

			$contadorReunion = 0;

			$ResReunion = $Mseguimientocliente->get_actualReunion();
			$data6 = array();
			foreach ($ResReunion as $element) {
				if ($element->TotalActi >= 0) {
					$contadorReunion = $element->TotalActi;
				}
			}

			$data6['value'] = $contadorReunion;
			array_push($actualReunion, $contadorReunion);
		}
		//FIN ACTUAL
		///////////////////////////7777777TERMINA GRÁFICA REUNIONES 

		///////////////////////////INICIO DE GRÁFICA DE LLAMADAS 
		$llamadas = 0;
		$LlamadasOperacion = 0;
		$arrayllamadas = array();
		$llamadasArray = array();

		if ($Data['data']->UnoP > 0) {
			$llamadas = $Data['data']->PorcentajeLlamadaF / 100;
		}
		for ($i = 1; $i < count($Meses) + 1; $i++) {
			if ($i < 4) {
				$LlamadasOperacion = $reunion1 / $llamadas;
			} else if ($i < 7) {
				$LlamadasOperacion = $reunion2 / $llamadas;
			} else if ($i <= 9) {
				$LlamadasOperacion = $reunion3 / $llamadas;
			} else if ($i >= 10) {
				$LlamadasOperacion = $reunion4 / $llamadas;
			}

			$dataLlamadas['value'] = $LlamadasOperacion;
			array_push($arrayllamadas, $LlamadasOperacion);

			///////ACTUAL 
			$Mseguimillamada = new Mseguimientocliente();
			$Mseguimillamada->IdSucursal = $IdSucursal;
			$Mseguimillamada->IdConfigS = $this->get('IdConfigS');
			$Mseguimillamada->Anio = $Anio;
			$Mseguimillamada->IdTrabajador = $IdVendedor;

			if ($i < 10) {
				$Mseguimillamada->Fecha = $Anio . '-0' . $i;
			} else {
				$Mseguimillamada->Fecha = $Anio . '-' . $i;
			}
			$Totalllamada = 0;

			$Resllamada = $Mseguimillamada->get_llamadas();

			foreach ($Resllamada as $e) {
				if ($e->Nombre == "Llamada en frio") {
					$Totalllamada++;
				}
			}

			$datallama['value'] = $Totalllamada;

			array_push($llamadasArray, $Totalllamada);
			//var_dump($Totalllamada);


		}


		///////////////////////////FIN DE GRÁFICA DE LLAMADAS 

		////actual Propuesta anual 
		$data['PropuestaAnualDinero'] = $DineroPropA;
		$data['PropActualPlanAnual'] = $planAnual;
		$data['Plan'] = $Planes;
		$data['Real'] = $Real;

		$data['Reuniones'] = $arrayReu;
		$data['ActualReuniones'] = $actualReunion;

		$data['llamadasPlan'] = $arrayllamadas;
		$data['llamadasActual'] = $llamadasArray;




		return $this->set_response([
			'status' => true,
			'data' => $data,
			'PropCierre' => $PropCierre,
			'PropCierre1' => $PropCierre1,
			'PropCierre2' => $PropCierre2,
			'PropCierre3' => $PropCierre3,
			/*'plan' => $Planes,

             'monto' => $Real,*/

			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	///////////////////////////////contadores globales 

	public function vendidasGlobales_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		//////////////////OPORTUNIDADES ABIERTAS
		$Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
		$IdVendedor = $this->get('IdVendedor');
		//$IdTipoProceso = $this->get('IdTipoProceso');

		if (empty($Mes)) {
			$Mes = date('m');
		}

		$AnioInicio = $Anio . '-' . $Mes . '-01';
		$L = new DateTime($AnioInicio);
		$UltimoDia = $L->format('t');

		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		//$Mseguimientocliente->IdTipoProceso = $this->get('IdTipoProceso');
		$Mseguimientocliente->Fecha = $Mes;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->FechaI = $Anio . '-01-01';
		$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;
		$Mseguimientocliente->Estatus = "Abierta";
		$Mseguimientocliente->IdSucursal = $IdSucursal;

		$contador = 0;
		$Res = $Mseguimientocliente->get_contadorGlobalAbiertas();

		foreach ($Res as $element) {
			if ($element->Estatus == "Abierta") {
				$contador += 1;
			}
		}

		$data = array();
		$resu = array(
			'value' => $contador
		);
		array_push($data, $resu);
		//////////////////TERMINA OPORTUNIDADES ABIERTAS
		//////////////////OPORTUNIDADES VENDIDAS && CERRADAS
		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		//$Mseguimientocliente->IdTipoProceso = $this->get('IdTipoProceso');
		$isTotal = $this->get('isTotal');
		$IdTipoProceso = $this->get('IdTipoProceso');
		$Mseguimientocliente->Fecha = $Mes;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->FechaI = $Anio . '-01-01';
		$Mseguimientocliente->FechaF = $Anio . '-' . $Mes . '-' . $UltimoDia;
		$Mseguimientocliente->Estatus = "Vendido";
		$Mseguimientocliente->Nombre = "Cierre";

		$Mseguimientocliente->IdSucursal = $IdSucursal;

		if (!empty($isTotal) && intval($IdTipoProceso) == 0) {
			$IdTipoProceso = 0;
		}

		$contador2 = 0;
		$Res = $Mseguimientocliente->get_contadorGlobalCerradas();
		foreach ($Res as $element) {
			if ($element->Estatus == "Vendido" && $element->Nombre == "Cierre") {
				$contador2 += 1;
			}
		}

		$data2 = array();
		$resu2 = array(
			'value' => $contador2
		);

		array_push($data2, $resu2);
		//////////////////TERMINA OPORTUNIDADES VENDIDAS && CERRADAS
		//////////////////sUMA GLOBAL
		$suma = 0;
		$Res = $Mseguimientocliente->get_oportinidadesVendidasSuma();
		foreach ($Res as $element) {
			if ($element->suma > 0) {
				$suma = $element->suma;
			}
		}

		$data3 = array();
		$resu3 = array(
			'value' => $suma
		);
		array_push($data3, $resu3);
		//////////////////SUMA RUEDITA
		$Mfinventas = new Mfinventas();
		$Mfinventas->IdSucursal = $IdSucursal;
		$Mfinventas->Anio = $Anio;
		$Mfinventas->IdVendedor = $IdVendedor;
		$Data = $Mfinventas->sumaAnual_get();

		$T = 0;
		foreach ($Data as $element) {
			if ($element->sumas > 0) {
				$T = $element->sumas;
			}
		}

		$data4 = array();
		$totalG = array(
			'value' => $T
		);
		array_push($data4, $T);


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'data2' => $data2,
			'data3' => $data3,
			'data4' => $data4,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function graficaPipeDrive_get()
	{
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$Anio = $this->get('Anio');
		$IdVendedor = $this->get('IdTrabajador');
		$IdTipoProceso = $this->get('IdTipoProceso');
		$IdOportunidad = $this->get('IdOportunidad');

		//$Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

		//arrays
		$arrayProspectar = array();
		$arrarLlamadas = array();
		$arrayReuniones = array();
		$arrayPropuestas = array();
		$arrayCierre = array();

		$arrayAcumulado = array();

		$Mseguimientocliente = new Mseguimientocliente();
		$Mseguimientocliente->IdSucursal = $IdSucursal;
		$Mseguimientocliente->Anio = $Anio;
		$Mseguimientocliente->IdTrabajador = $IdVendedor;
		$Mseguimientocliente->IdTipoProceso = $IdTipoProceso;
		$Mseguimientocliente->IdOportunidad = $IdOportunidad;

		////////////////////////////////////////////////////AQUÍ EMPIEZA PROSPECTAR
		$ArrayPros = array();
		$TotalProspectar = 0;

		$Prospectar = $Mseguimientocliente->get_prospectar();
		foreach ($Prospectar as $element) {
			if ($element->Nombre == "Prospectar") {
				$TotalProspectar += 1;
			}
		}
		$ArrayPros['value'] = $TotalProspectar;
		array_push($arrayProspectar, $TotalProspectar);
		///////////////////////////////////////////////////AQUI TERMINA PROSPECTAR

		///////////////////////////////////////////////////AQUÍ EMPIEZA PROPUESTA
		$arrayProp = array();
		$TotalPropuesta = 0;
		$Propuesta = $Mseguimientocliente->get_PropuestaPipeDrive();
		foreach ($Propuesta as $element2) {
			if ($element2->Nombre == "Propuestas") {
				$TotalPropuesta += 1;
			}
		}
		$arrayProp['value'] = $TotalPropuesta;
		array_push($arrayPropuestas, $TotalPropuesta);
		//////////////////////////////////////////////////AQUÍ TERMINA PROPUESTA

		//////////////////////////////////////////////////AQUÍ EMPIEZA LLAMADA EN FRIO
		$arrayllamadaFrio = array();
		$TotalLlamada = 0;
		$LlamadaFrio = $Mseguimientocliente->get_llamadasPipeDrive();
		foreach ($LlamadaFrio as $element3) {
			if ($element3->Nombre == "Llamada en frio") {
				$TotalLlamada += 1;
			}
		}
		$arrayllamadaFrio['value'] = $TotalLlamada;
		array_push($arrarLlamadas, $TotalLlamada);
		/////////////////////////////////////////////////AQUÍ TERMINA LLAMADA EN FRIO

		////////////////////////////////////////////////AQUÍ EMPIEZA REUNION DE VENTAS
		$arrayReunion = array();
		$TotalReuniones = 0;
		$ReuninesPipe = $Mseguimientocliente->get_reunionesPipeDrive();
		foreach ($ReuninesPipe as $element4) {
			if ($element4->Nombre == "Reunion de ventas") {
				$TotalReuniones += 1;
			}
		}
		$arrayReunion['value'] = $TotalReuniones;
		array_push($arrayReuniones, $TotalReuniones);
		////////////////////////////////////////////////AQUÍ TERMINA REUNION DE VENTAS

		////////////////////////////////////////////////AQUÍ EMPIEZA CIERRE-VENDIDO
		$arrayCierreVendido = array();
		$TotalCierreVendido = 0;
		$CierresPipe = $Mseguimientocliente->get_cierrePipeDrive();
		foreach ($CierresPipe as $element5) {
			if ($element5->Nombre == "Cierre") {
				$TotalCierreVendido += 1;
			}
		}
		$arrayCierreVendido['value'] = $TotalCierreVendido;
		array_push($arrayCierre, $TotalCierreVendido);
		////////////////////////////////////////////////AQUÍ TERMINA CIERRE-VENDIDO


		/*for($i=1; $i< count($Meses) + 1; $i++){

            if ($i < 10) {
                $Mseguimientocliente->Fecha = $Anio . '-0' . $i;
            } else {
                $Mseguimientocliente->Fecha = $Anio . '-' . $i;
            }
            
            ////////////////////////////////////////////////////AQUÍ EMPIEZA PROSPECTAR
            $ArrayPros=array();
            $TotalProspectar=0;

            $Prospectar =$Mseguimientocliente->get_prospectar();
            foreach($Prospectar as $element){
                if($element->Nombre=="Prospectar"){
                    $TotalProspectar+=1;
                }
            }
            $ArrayPros['value']=$TotalProspectar;
            array_push($arrayProspectar,$TotalProspectar);
            ///////////////////////////////////////////////////AQUI TERMINA PROSPECTAR

            ///////////////////////////////////////////////////AQUÍ EMPIEZA PROPUESTA
            $arrayProp=array();
            $TotalPropuesta=0;
            $Propuesta=$Mseguimientocliente->get_PropuestaPipeDrive();
            foreach($Propuesta as $element2){
                if($element2->Nombre=="Propuestas"){
                    $TotalPropuesta+=1;
                }
            }
            $arrayProp['value']=$TotalPropuesta;
            array_push($arrayPropuestas,$TotalPropuesta);
            //////////////////////////////////////////////////AQUÍ TERMINA PROPUESTA

            //////////////////////////////////////////////////AQUÍ EMPIEZA LLAMADA EN FRIO
            $arrayllamadaFrio=array();
            $TotalLlamada=0;
            $LlamadaFrio=$Mseguimientocliente->get_llamadasPipeDrive();
            foreach($LlamadaFrio as $element3){
                if($element3->Nombre=="Llamada en frio"){
                    $TotalLlamada+=1;
                }
            }
            $arrayllamadaFrio['value']=$TotalLlamada;
            array_push($arrarLlamadas,$TotalLlamada);
            /////////////////////////////////////////////////AQUÍ TERMINA LLAMADA EN FRIO

            ////////////////////////////////////////////////AQUÍ EMPIEZA REUNION DE VENTAS
            $arrayReunion=array();
            $TotalReuniones=0;
            $ReuninesPipe=$Mseguimientocliente->get_reunionesPipeDrive();
            foreach($ReuninesPipe as $element4){
                if($element4->Nombre=="Reunion de ventas"){
                    $TotalReuniones+=1;
                }
            }
            $arrayReunion['value']=$TotalReuniones;
            array_push($arrayReuniones,$TotalReuniones);
            ////////////////////////////////////////////////AQUÍ TERMINA REUNION DE VENTAS

            ////////////////////////////////////////////////AQUÍ EMPIEZA CIERRE-VENDIDO
            $arrayCierreVendido=array();
            $TotalCierreVendido=0;
            $CierresPipe=$Mseguimientocliente->get_cierrePipeDrive();
            foreach($CierresPipe as $element5){
                if($element5->Nombre=="Cierre"){
                    $TotalCierreVendido+=1;
                }
            }
            $arrayCierreVendido['value']=$TotalCierreVendido;
            array_push($arrayCierre,$TotalCierreVendido);
            ////////////////////////////////////////////////AQUÍ TERMINA CIERRE-VENDIDO
        }*/



		//data
		$data['Prospectar'] = $arrayProspectar;
		$data['llamada'] = $arrarLlamadas;
		$data['Reuniones'] = $arrayReuniones;
		$data['Propuesta'] = $arrayPropuestas;
		$data['Cierre'] = $arrayCierre;

		array_push($arrayAcumulado, $TotalProspectar);
		array_push($arrayAcumulado, $TotalLlamada);
		array_push($arrayAcumulado, $TotalReuniones);
		array_push($arrayAcumulado, $TotalPropuesta);
		array_push($arrayAcumulado, $TotalCierreVendido);

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'acumulado' => $arrayAcumulado,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}
}
