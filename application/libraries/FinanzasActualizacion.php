<?php
//defined("BASEPATH") or die("El acceso al script no est� permitido");
defined('BASEPATH') or exit('No direct script access allowed');

class FinanzasActualizacion
{
	//Individual
	public $AnioPasado = 0;
	public $PlanAnio = 0;
	public $ActualAnio = 0;
	public $PlanMes = 0;
	public $ActualMes = 0;

	//Totales
	public $AnioPasadoT = 0;
	public $PlanAnioT = 0;
	public $ActualAnioT = 0;
	public $PlanMesT = 0;
	public $ActualMesT = 0;

	public function __construct()
	{
		$CI = &get_instance();

		$CI->load->model('finanzas/Mcostoga');
		$CI->load->model('finanzas/Mgastosdirectos');
		$CI->load->model('finanzas/Mcostodeptoventa');
		$CI->load->model('finanzas/Mcostovehope');
		$CI->load->model('finanzas/Mcostofinanciero');

		$CI->load->model('finanzas/MactualFinanzas');
	}

	function InsertValues($Tipo = 0, $Anio, $IdSucursal, $IdEmpresa, $TipoBusqueda)
	{
		$rows = null;

		if ($Tipo == 1) {

			$arreg = array(
				"Salarios, Bonos & Comisiones", "Viajes &  Entretenimiento - G&A",
				"Gastos Vehiculos - G&A",
				"Seguros", "Sistemas  IT",
				"Recursos Humanos",
				"Servicios Profesionales",
				"Depresiacion y Equipo de Oficinas",
				"Materiales de Oficina",
				"Gastos Telefonicos",
				"Facturas Incobrables",
				"Licencias e Impuestos Especiales",
				"Miscelaneos - G&A",
				"Depresiacion o Renta de Oficinas G&A", "Gastos de Servicios G&A",
				"",
				""
			);
			foreach ($arreg as $element) {

				$oMcostoga = new Mcostoga();
				$oMcostoga->IdSucursal = $IdSucursal;
				$oMcostoga->Anio = $Anio;
				$oMcostoga->Descripcion = $element;
				$oMcostoga->NumeroCuenta = '';
				$oMcostoga->AnioAnterior = '0';
				$oMcostoga->PrimerT = '0';
				$oMcostoga->SegundoT = '0';
				$oMcostoga->TercerT = '0';
				$oMcostoga->CuartoT = '0';
				$oMcostoga->insert();
			}

			$oMcostoga = new Mcostoga();
			$oMcostoga->Anio = $Anio;
			$oMcostoga->IdSucursal = $IdSucursal;
			// Paginaci�n
			$rows =  $oMcostoga->get_list();
		} else  if ($Tipo == 2) {
			if ($TipoBusqueda == 1) {
				$arreg = array(
					"Salario Representantes de Ventas",
					"Comisiones",
					"Viajes y Entretenimiento",
					"Gastos de Vehiculo",
					"Gastos Varios de Ventas",
					"",
					""
				);
				foreach ($arreg as $element) {
					$oMgastosdirectos = new Mgastosdirectos();
					$oMgastosdirectos->IdSucursal = $IdSucursal;
					$oMgastosdirectos->IdEmpresa = $IdEmpresa;
					$oMgastosdirectos->Gasto = $element;
					$oMgastosdirectos->NumCuenta = '';
					$oMgastosdirectos->FechaAnterior = date('Y-m-d');
					$oMgastosdirectos->MontoAnterior = '0';
					$oMgastosdirectos->MontoAnual = '0';
					$oMgastosdirectos->UnoT = '0';
					$oMgastosdirectos->DosT = '0';
					$oMgastosdirectos->TresT = '0';
					$oMgastosdirectos->CuatroT = '0';
					$oMgastosdirectos->FechaAct = date('Y-m-d');
					$oMgastosdirectos->Tipo = "1";
					$oMgastosdirectos->Anio = $Anio;
					$oMgastosdirectos->insert();
				}
			}
			if ($TipoBusqueda == 2) {
				$arreg = array(
					"Publicidad",
					"Miselaneos",
					"Gastos Oficina de Ventas",
					"Servicio Oficina de Ventas",
					"",
					""
				);
				foreach ($arreg as $element) {
					$oMgastosdirectos = new Mgastosdirectos();
					$oMgastosdirectos->IdSucursal = $IdSucursal;
					$oMgastosdirectos->IdEmpresa = $IdEmpresa;
					$oMgastosdirectos->Gasto = $element;
					$oMgastosdirectos->NumCuenta = '';
					$oMgastosdirectos->FechaAnterior = date('Y-m-d');
					$oMgastosdirectos->MontoAnterior = '0';
					$oMgastosdirectos->MontoAnual = '0';
					$oMgastosdirectos->UnoT = '0';
					$oMgastosdirectos->DosT = '0';
					$oMgastosdirectos->TresT = '0';
					$oMgastosdirectos->CuatroT = '0';
					$oMgastosdirectos->FechaAct = date('Y-m-d');
					$oMgastosdirectos->Tipo = "2";
					$oMgastosdirectos->Anio = $Anio;
					$oMgastosdirectos->insert();
				}
			}

			$oMgastosdirectos = new Mgastosdirectos();
			$oMgastosdirectos->Anio = $Anio;
			$oMgastosdirectos->Tipo = $TipoBusqueda;
			$oMgastosdirectos->IdSucursal = $IdSucursal;
			// Paginaci�n
			$rows =  $oMgastosdirectos->get_list();
		} else  if ($Tipo == 3) {
			$arreg = array(
				"Salario Gerencia Operativa",
				"Viajes y Entretenimiento",
				"Gastos de Vehículos",
				"Bono de Gerencia",
				"Salarios Tiempos No Productivos",
				"Gastos de Entrenamiento",
				"Impuesteos Especiales",
				"Seguros", "Herramientas",
				"Uniformes Y Seguridad",
				"Materiales Menores",
				"Comunicaciones",
				"Miscelaneos",
				"Depresiación del Edificio", "Servicios del Edificio",
				"Mantenimiento del Edificio"
			);

			foreach ($arreg as $element) {
				$oMcostodeptoventa = new Mcostodeptoventa();
				$oMcostodeptoventa->IdSucursal = $IdSucursal;
				$oMcostodeptoventa->Anio = $Anio;
				$oMcostodeptoventa->Descripcion = $element;
				$oMcostodeptoventa->NumeroCuenta = '';
				$oMcostodeptoventa->AnioAnterior = '0';
				$oMcostodeptoventa->PrimerT = '0';
				$oMcostodeptoventa->SegundoT = '0';
				$oMcostodeptoventa->TercerT = '0';
				$oMcostodeptoventa->CuartoT = '0';
				$oMcostodeptoventa->insert();
			}

			$oMcostodeptoventa = new Mcostodeptoventa();
			$oMcostodeptoventa->Anio = $Anio;
			$oMcostodeptoventa->IdSucursal = $IdSucursal;
			// Paginaci�n
			$rows =  $oMcostodeptoventa->get_list();
		} else if ($Tipo == 4) {
			$arreg = array(
				"Depresiación o Leasing",
				"Combustible",
				"Mantenimiento",
				"Impuestos o Licencias",
				"Conductor",
				"Varios",
				"",
				""
			);

			foreach ($arreg as $element) {
				$oMcostovehope = new Mcostovehope();
				$oMcostovehope->IdSucursal = $IdSucursal;
				$oMcostovehope->Anio = $Anio;
				$oMcostovehope->Descripcion = $element;
				$oMcostovehope->NumeroCuenta = '';
				$oMcostovehope->AnioAnterior = '0';
				$oMcostovehope->PrimerT = '0';
				$oMcostovehope->SegundoT = '0';
				$oMcostovehope->TercerT = '0';
				$oMcostovehope->CuartoT = '0';
				$oMcostovehope->insert();
			}

			$oMcostovehope = new Mcostovehope();
			$oMcostovehope->Anio = $Anio;
			$oMcostovehope->IdSucursal = $IdSucursal;
			// Paginaci�n
			$rows =  $oMcostovehope->get_list();
		} else if ($Tipo == 5) {
			$arreg = array(
				"Ingresos por Intereses Inversiones",
				"Otros Ingresos",
				""
			);
			$arreg2 = array(
				"Amortización Inversión",
				"Almacén", 
				"Pago de Intereses Bancarios",
				"Pagos Corporativos",
				"Pagos de Licencias Corporativas",
				""
			);

			$Type = "TOTAL INTERESES Y GASTOS";
			if ($TipoBusqueda == 2) {
				$Type = "TOTAL OTROS INGRESOS/GASTOS";
			}

			if ($TipoBusqueda == 1) {
				foreach ($arreg as $element) {
					$oMcostofinanciero = new Mcostofinanciero();
					$oMcostofinanciero->IdSucursal = $IdSucursal;
					$oMcostofinanciero->Anio = $Anio;
					$oMcostofinanciero->Descripcion = $element;
					$oMcostofinanciero->NumeroCuenta = '';
					$oMcostofinanciero->Tipo = $Type;
					$oMcostofinanciero->AnioAnterior = '0';
					$oMcostofinanciero->PrimerT = '0';
					$oMcostofinanciero->SegundoT = '0';
					$oMcostofinanciero->TercerT = '0';
					$oMcostofinanciero->CuartoT = '0';
					$oMcostofinanciero->insert();
				}
			} else {
				foreach ($arreg2 as $element) {
					$oMcostofinanciero = new Mcostofinanciero();
					$oMcostofinanciero->IdSucursal = $IdSucursal;
					$oMcostofinanciero->Anio = $Anio;
					$oMcostofinanciero->Descripcion = $element;
					$oMcostofinanciero->NumeroCuenta = '';
					$oMcostofinanciero->Tipo = $Type;
					$oMcostofinanciero->AnioAnterior = '0';
					$oMcostofinanciero->PrimerT = '0';
					$oMcostofinanciero->SegundoT = '0';
					$oMcostofinanciero->TercerT = '0';
					$oMcostofinanciero->CuartoT = '0';
					$oMcostofinanciero->insert();
				}
			}

			$oMcostofinanciero = new Mcostofinanciero();
			$oMcostofinanciero->Anio = $Anio;
			$oMcostofinanciero->IdSucursal = $IdSucursal;
			$oMcostofinanciero->Tipo = $Type;
			// Paginaci�n
			$rows =  $oMcostofinanciero->get_list();
		}

		return $rows;
	}

	public  function  ActualizacionCostos($IdSucursal, $anio, $Mes = 1, $Tipo = 1, $TipoBusqueda = "", $IdEmpresa = 0)
	{

		$rowFin = null;

		$IdName = '';
		$Tabla = '';

		if ($Tipo == 1) {
			$oMcostoga = new Mcostoga();
			$oMcostoga->Anio = $anio;
			$oMcostoga->IdSucursal = $IdSucursal;
			$row = $oMcostoga->get_list();
			$IdName = 'IdCostoGA';
			$Tabla = 'actualcostoga';
		} else if ($Tipo == 2) {

			$oMgastosdirectos = new Mgastosdirectos();
			$oMgastosdirectos->Anio = $anio;
			$oMgastosdirectos->Tipo = $TipoBusqueda;
			$oMgastosdirectos->IdSucursal = $IdSucursal;
			$row = $oMgastosdirectos->get_list2();
			$IdName = 'IdGasto';
			$Tabla = 'actualventas';
		} else if ($Tipo == 3) {
			$oMcostodeptoventa = new Mcostodeptoventa();
			$oMcostodeptoventa->Anio = $anio;
			$oMcostodeptoventa->IdSucursal = $IdSucursal;
			$row = $oMcostodeptoventa->get_list();
			$IdName = 'IdCostoDeptoVenta';
			$Tabla = 'actualoperaciones';
		} else if ($Tipo == 4) {
			$oMcostovehope = new Mcostovehope();
			$oMcostovehope->Anio = $anio;
			$oMcostovehope->IdSucursal = $IdSucursal;
			$row = $oMcostovehope->get_list();
			$IdName = 'IdCostoVehOpe';
			$Tabla = 'actualcostove';
		} else if ($Tipo == 5) {
			$TFiltro = "TOTAL INTERESES Y GASTOS";
			if ($TipoBusqueda == 2) {
				$TFiltro = "TOTAL OTROS INGRESOS/GASTOS";
			}

			$oMcostofinanciero = new Mcostofinanciero();
			$oMcostofinanciero->Anio = $anio;
			$oMcostofinanciero->Tipo = $TFiltro;
			$oMcostofinanciero->IdSucursal = $IdSucursal;
			$row = $oMcostofinanciero->get_list();
			$IdName = 'IdCostoFinanciero';
			$Tabla = 'actualcostof';
		}

		if (count($row) == 0) { //si es igual a 0 se inserta para que busque
			$row =  $this->InsertValues($Tipo, $anio, $IdSucursal, $IdEmpresa, $TipoBusqueda);
		}

		$rowFin = $this->Validacion($Mes, $row, $IdName, $anio, $IdSucursal, $Tabla);

		$data['row'] = $rowFin;
		//Totales
		$data['AnioPasadoT'] = $this->AnioPasadoT;
		$data['PlanAnioT'] = $this->PlanAnioT;
		$data['ActualAnioT'] = $this->ActualAnioT;
		$data['PlanMesT'] = $this->PlanMesT;
		$data['ActualMesT'] = $this->ActualMesT;


		return $data;
	}

	public function Validacion($Mes, $row, $IdName, $anio, $IdSucursal, $Tabla)
	{
		// $MesFalso = 1;

		// if ($Mes == 4) {
		// 	$MesFalso = 1;
		// }
		// if ($Mes == 5) {
		// 	$MesFalso = 2;
		// }
		// if ($Mes == 6) {
		// 	$MesFalso = 3;
		// }
		// if ($Mes == 7) {
		// 	$MesFalso = 1;
		// }
		// if ($Mes == 8) {
		// 	$MesFalso = 2;
		// }
		// if ($Mes == 9) {
		// 	$MesFalso = 3;
		// }
		// if ($Mes == 10) {
		// 	$MesFalso = 1;
		// }
		// if ($Mes == 11) {
		// 	$MesFalso = 2;
		// }
		// if ($Mes == 12) {
		// 	$MesFalso = 3;
		// }

		$contador = 0;
		foreach ($row as $element) {
			$this->AnioPasado = 0;
			$oMactualFinanzas = new MactualFinanzas();
			$oMactualFinanzas->Tabla = $Tabla;
			$oMactualFinanzas->NameId = $IdName;
			$oMactualFinanzas->Id = $element->$IdName;
			$oMactualFinanzas->Anio = $anio;
			$oMactualFinanzas->Mes = $Mes;
			$oMactualFinanzas->IdSucursal = $IdSucursal;
			// $data = $oMactualFinanzas->get_TotalesAct();
			$data = $oMactualFinanzas->getListTwo();

			// $AnioActual = '';
			// $ActualMes = '';
			// if ($data['status']) {
			// 	if ($data['data']->Total != null) {
			// 		$AnioActual = $data['data']->Total;
			// 	}
			// 	if ($data['data']->TotalMes != null) {
			// 		$ActualMes = $data['data']->TotalMes;
			// 	}
			// } agarre de caja chica 600 pesos para alchol

			$MontoMes = $data['data']->MontoMes;
			if ($MontoMes == "") {
				$MontoMes = 0;
			}
			$MontoCuenta =  $data['data']->MontoCuenta;
			if ($MontoCuenta == "") {
				$MontoCuenta = 0;
			}
			$weekOne =  $data['data']->SemanaUno;
			if ($weekOne == "") {
				$weekOne = 0;
			}
			$weekTwo =  $data['data']->SemanaDos;
			if ($weekTwo == "") {
				$weekTwo = 0;
			}
			$weekThree =  $data['data']->SemanaTres;
			if ($weekThree == "") {
				$weekThree = 0;
			}
			$weekFour =  $data['data']->SemanaCuatro;
			if ($weekFour == "") {
				$weekFour = 0;
			}

			$totalRowWeeks = $weekOne + $weekTwo + $weekThree + $weekFour;
			if ($totalRowWeeks == "") {
				$totalRowWeeks = 0;
			}

			// $this->PlanAnio  = 0;
			// $this->AnioPasado += $element->AnioAnterior;

			//De Enero al mes actual 
			if ($Mes <= 3) {

				$this->PlanMes = 0;
				if (isset($element->PrimerT)) {

					if ($element->PrimerT > 0) {
						$this->PlanMes = $element->PrimerT / 3;
					}
				} else {

					if ($element->UnoT > 0) {
						$this->PlanMes = $element->UnoT / 3;
					}
				}
			}

			if ($Mes > 3 && $Mes <= 6) {

				$this->PlanMes = 0;
				if (isset($element->SegundoT)) {

					if ($element->SegundoT > 0) {
						$this->PlanMes = $element->SegundoT / 3;
					}
				} else {

					if ($element->DosT > 0) {
						$this->PlanMes = $element->DosT / 3;
					}
				}
			}

			if ($Mes > 6 && $Mes <= 9) {

				$this->PlanMes = 0;
				if (isset($element->TercerT)) {

					if ($element->TercerT > 0) {
						$this->PlanMes = $element->TercerT / 3;
					}
				} else {

					if ($element->TresT > 0) {
						$this->PlanMes = $element->TresT / 3;
					}
				}
			}

			if ($Mes > 9 && $Mes <= 12) {

				$this->PlanMes = 0;
				if (isset($element->CuartoT)) {

					if ($element->CuartoT > 0) {
						$this->PlanMes = $element->CuartoT / 3;
					}
				} else {

					if ($element->CuatroT > 0) {
						$this->PlanMes = $element->CuatroT / 3;
					}
				}
			}

			// $this->AnioPasadoT += round($this->AnioPasado, 0, PHP_ROUND_HALF_UP);
			// $this->PlanAnioT  += round($this->PlanAnio, 0, PHP_ROUND_HALF_UP);
			// $this->ActualAnioT  += round($AnioActual, 0, PHP_ROUND_HALF_UP);
			// $this->PlanMesT  += round($this->PlanMes, 0, PHP_ROUND_HALF_UP);
			// $this->ActualMesT  += round($ActualMes, 0, PHP_ROUND_HALF_UP);

			// $row[$contador]->AnioPasado = round($this->AnioPasado, 0, PHP_ROUND_HALF_UP);
			// $row[$contador]->PlanAnio = round($this->PlanAnio, 0, PHP_ROUND_HALF_UP);
			// $row[$contador]->ActualAnio = round($AnioActual, 0, PHP_ROUND_HALF_UP);
			// $row[$contador]->PlanMes = round($this->PlanMes, 0, PHP_ROUND_HALF_UP);
			// $row[$contador]->ActualMes = round($ActualMes, 0, PHP_ROUND_HALF_UP);

			$row[$contador]->PlanMes = round($this->PlanMes, 0, PHP_ROUND_HALF_UP);
			$row[$contador]->ActualMes = round($MontoMes, 0, PHP_ROUND_HALF_UP);
			$row[$contador]->SemanaUno = round($weekOne, 0, PHP_ROUND_HALF_UP);
			$row[$contador]->SemanaDos = round($weekTwo, 0, PHP_ROUND_HALF_UP);
			$row[$contador]->SemanaTres = round($weekThree, 0, PHP_ROUND_HALF_UP);
			$row[$contador]->SemanaCuatro = round($weekFour, 0, PHP_ROUND_HALF_UP);

			$contador++;
		}

		return $row;
	}
}
