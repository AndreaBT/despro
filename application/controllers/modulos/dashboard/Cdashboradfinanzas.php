<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cdashboradfinanzas extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ctaporpagar/Mctaporpagar');
		$this->load->model('ctaporcobrar/Mctaporcobrar');
		$this->load->model('dashboard/uno/Mtrabajador');
		$this->load->model('dashboard/uno/Mservicio');
		$this->load->model('dashboard/uno/Mtiposervicio');
		$this->load->model('dashboard/uno/Mvehiculo');
		$this->load->model('dashboard/uno/Mconfigservicio');
		$this->load->model('dashboard/uno/Mfinventas');
		$this->load->model('dashboard/uno/Mserviciosvalores');
		$this->load->model('dashboard/uno/Mestadofinanciero');
		$this->load->model('dashboard/uno/Mestadoupdate');
		$this->load->model('estadosf/Mplanfactura');
		$this->load->model('estadosf/Mestadofupdate');
		$this->load->model('estadosf/Mcostoga');
		$this->load->model('estadosf/Mactualcostoga');
		$this->load->model('estadosf/Mgastosdirectos');
		$this->load->model('estadosf/Mactualventas');
		$this->load->model('estadosf/Mporcentajeoperacion');


		$this->load->model('Mrol');
		setTimeZone($this->verification, $this->input);
	}

	public function Calcular_Minutos($hora1, $hora2)
	{

		$separar[1] = explode(':', $hora1);
		$separar[2] = explode(':', $hora2);

		$total_minutos_trasncurridos[1] = ($separar[1][0] * 60) + $separar[1][1];
		$total_minutos_trasncurridos[2] = ($separar[2][0] * 60) + $separar[2][1];
		$total_minutos_trasncurridos = $total_minutos_trasncurridos[1] - $total_minutos_trasncurridos[2];
		if ($total_minutos_trasncurridos <= 59) return ($total_minutos_trasncurridos . '');
		elseif ($total_minutos_trasncurridos > 59) {
			$HORA_TRANSCURRIDA = round($total_minutos_trasncurridos / 60);
			if ($HORA_TRANSCURRIDA <= 9) $HORA_TRANSCURRIDA = '0' . $HORA_TRANSCURRIDA;
			$MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos % 60;
			if ($MINUITOS_TRANSCURRIDOS <= 9) $MINUITOS_TRANSCURRIDOS = '0' . $MINUITOS_TRANSCURRIDOS;
			return ($HORA_TRANSCURRIDA . ':' . $MINUITOS_TRANSCURRIDOS . ' Horas');
		}
	}

	public function findAll_get()
	{

		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		//datos grafica
		$anio = $this->get('Anio');


		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oplanfactura = new Mplanfactura();
		$oplanfactura->IdSucursal = $IdSucursal;
		$rowPlanFactura = $oplanfactura->get_list_planfactura();

		//print_r($rowPlanFactura);
		$IdServicio = $this->get('IdServicio');

		$Colores = array("#f07173", "#8edce7", "#8adfb9");
		$contcol = 0;

		$meses = array(
			'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
			'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
		);

		//Plan calculo automatico
		$oconfigservicio = new Mconfigservicio();
		$oconfigservicio->IdConfigS = $IdServicio;
		$oconfigservicio->RegEstatus = 'A';
		$valor = $oconfigservicio->get_recobery_configservicio();

		$PorcenajeUno = $valor['data']->Porcentaje / 100;
		$PorcentajeDos = $valor['data']->Porcentaje2 / 100;
		$NombreConSer = $valor['data']->Nombre;

		$ofinventas = new Mfinventas();
		$ofinventas->IdSucursal = $IdSucursal;
		$ofinventas->Anio = $anio;
		$ofinventas->IdTipoServ = $IdServicio;
		$valoresofin = $ofinventas->get_recovery_finventasporcentajeope();

		$TotalUno = $valoresofin['data']->TotalUno;
		$TotalDos = $valoresofin['data']->TotalDos;
		$TotalTres = $valoresofin['data']->TotalTres;
		$TotalCuatro = $valoresofin['data']->TotalCuatro;

		$oserviciosvalores = new Mserviciosvalores();
		$oserviciosvalores->IdSucursal = $IdSucursal;
		$oserviciosvalores->IdConfigS = $IdServicio;
		$valoresoserv = $oserviciosvalores->get_recobery_serviciosvalores();

		$ComisionA = 0;
		$baseactual = 0;

		$arraydatos = array();
		$arraydatosactual = array();
		$arrayApexchart = array();
		$arrayApexchartActual = array();


		if ($valoresoserv['data']->ComisionA != '') {
			$baseactual = $valoresoserv['data']->BaseActual;
			$ComisionA = $valoresoserv['data']->ComisionA;
		}

		if ($NombreConSer == 'Mantenimiento') {
			$UnoT = round(($TotalUno * $PorcenajeUno * $PorcentajeDos) + ($baseactual * $PorcentajeDos));

			$DosT = ($TotalUno * $PorcentajeDos) + ($TotalDos * $PorcenajeUno * $PorcentajeDos) + ($baseactual * $PorcentajeDos);
			$TresT = ($TotalUno * $PorcentajeDos) + ($TotalDos * $PorcentajeDos) + ($TotalTres * $PorcenajeUno * $PorcentajeDos) + ($baseactual * $PorcentajeDos);
			$CuatroT = ($TotalUno * $PorcentajeDos) + ($TotalDos * $PorcentajeDos) + ($TotalTres * $PorcentajeDos) + ($TotalCuatro * $PorcenajeUno * $PorcentajeDos) + ($baseactual * $PorcentajeDos);
		} else if ($NombreConSer == 'Servicio') {
			$UnoT = $TotalUno * $PorcenajeUno;
			$DosT = ($TotalUno * $PorcentajeDos) + ($TotalDos * $PorcenajeUno);
			$TresT = ($TotalDos * $PorcentajeDos) + ($TotalTres * $PorcenajeUno);
			$CuatroT = ($TotalTres * $PorcentajeDos) + ($TotalCuatro * $PorcenajeUno);
		} else if ($NombreConSer == 'Proyecto') {
			$UnoT = ($TotalUno * $PorcenajeUno) + $baseactual;
			$DosT = ($TotalUno * $PorcentajeDos) + ($TotalDos * $PorcenajeUno);
			$TresT = ($TotalDos * $PorcentajeDos) + ($TotalTres * $PorcenajeUno);
			$CuatroT = ($TotalTres * $PorcentajeDos) + ($TotalCuatro * $PorcenajeUno);
		}

		foreach ($rowPlanFactura as $element) {

			$Mes = 1;
			$data = array();
			$dataactual = array();

			foreach ($meses as $listmeses) {

				$Colocar = 0;
				$oestadofinanciero = new Mestadofinanciero();
				$oestadofinanciero->IdConfigS = $IdServicio;
				$oestadofinanciero->IdTipoServ = "";
				$oestadofinanciero->IdSucursal = $IdSucursal;
				$oestadofinanciero->Anio = $anio;
				if ($Mes < 10) {
					$oestadofinanciero->Mes = '0' . $Mes;
					$oestadofinanciero->Mes2 = '0' . $Mes;
				} else {
					$oestadofinanciero->Mes = $Mes;
					$oestadofinanciero->Mes2 = $Mes;
				}
				$rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();

				foreach ($rowmontoestado as $elemento) {
					$Colocar += $elemento->Facturacion;
				}

				//Este es nuevo EstadoUpdateClass
				$oestadofupdate = new Mestadoupdate();
				$oestadofupdate->IdSucursal = $IdSucursal;
				if ($Mes < 10) {
					$oestadofupdate->Mes = intval($Mes);
					$oestadofupdate->Mes2 = intval($Mes);
				} else {
					$oestadofupdate->Mes = intval($Mes);
					$oestadofupdate->Mes2 = intval($Mes);
				}
				$oestadofupdate->Anio = $anio;
				$oestadofupdate->IdConfigServ = $IdServicio;
				$oestadofupdate->IdTipoServ = "";

				$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

				foreach ($rowEstadoUpdate as $element) {
					if ($element->Descripcion == 'Facturacion') {
						$Colocar += $element->Monto;
					}
				}


				$Trimestre = 0;

				if ($Mes < 4) {
					$Trimestre = 1;
					$Plan = $UnoT / 3;
				} else if ($Mes < 7) {
					$Trimestre = 2;
					$Plan = $DosT / 3;
				} else if ($Mes < 10) {
					$Trimestre = 3;
					$Plan = $TresT / 3;
				} else {
					$Trimestre = 4;
					$Plan = $CuatroT / 3;
				}

				$PlanAnual = round($Plan);

				//echo $PlanAnual;

				$nommes = substr($listmeses, 0, 3);

				if ($data == 0) {

					//print_r($PlanAnual);
					$data = array(
						'value' => $PlanAnual
					);
					array_push($arraydatos, $data);
					array_push($arrayApexchart, $PlanAnual);
				} else {
					$data = array(
						'value' => $PlanAnual
					);
					array_push($arraydatos, $data);
					array_push($arrayApexchart, $PlanAnual);
				}

				if ($dataactual == '') {

					$dataactual = array(
						'value' => $Colocar
					);
					array_push($arraydatosactual, $dataactual);
					array_push($arrayApexchartActual, $Colocar);
				} else {
					$dataactual = array(
						'value' => $Colocar
					);
					array_push($arraydatosactual, $dataactual);
					array_push($arrayApexchartActual, $Colocar);
				}

				$Mes++;
			}

			break;
		}

		return $this->set_response([
			'status' => true,
			'data' => $arraydatos,
			'arraydatosactual' => $arraydatosactual,
			'dataapex' => $arrayApexchart,
			'dataapexactual' => $arrayApexchartActual,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	#Grafica de Facturacion Actual
	public function PlanFactura_get()
	{
		//datos grafica
		$anio = $this->get('Anio');
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


		$otrabajador = new Mtrabajador();
		$otrabajador->IdSucursal = $IdSucursal;
		$otrabajador->RegEstatus = 'A';
		$otrabajador->Rol = 'Usuario App';
		$rowsuc = $otrabajador->get_list_trabajador();

		$numUsuer = count($rowsuc);

		$messelect = $this->get('Mes');

		$messelect2 = '-12';
		if ($messelect < 10) {

			$messelect = '-0' . $messelect;
			$messelect2 = $messelect;
		} else {

			if ($messelect == 13) {

				$messelect = '-01';
				$messelect2 = '-12';
			} else {

				$messelect = '-' . $messelect;
				$messelect2 = $messelect;
			}
		}

		$oservicio = new Mservicio();
		$oservicio->IdSucursal = $IdSucursal;
		$oservicio->Fecha_I = $anio . $messelect . '-01';
		$oservicio->Fecha_F = $anio . $messelect2 . '-31';
		$rowServicios = $oservicio->get_list_finanzashorasfacturables();


		$minutos_inicialesFaturables = 0;
		$minutos_inicialesFaturablesTotal = 0;

		$minutos_inicialesNoFaturables = 0;
		$minutos_inicialesNoFaturablesTotal = 0;

		foreach ($rowServicios as $element) {

			$partirhi = explode(':', $element->HoraInicio);
			$partirhf = explode(':', $element->HoraFin);

			$horaI = $partirhi[0] . ':' . $partirhi[1];
			$horaF = $partirhf[0] . ':' . $partirhf[1];



			if ($element->Ingresos == 's') { //Facturables
				if ($element->EstadoServicio == 'CERRADA') {
					$minutos_inicialesFaturables += Calcular_Minutos($horaI, $horaF);
				}
				$minutos_inicialesFaturablesTotal += Calcular_Minutos($horaI, $horaF);
			} else {
				if ($element->EstadoServicio == 'CERRADA') {
					$minutos_inicialesNoFaturables += Calcular_Minutos($horaI, $horaF);
				}
				$minutos_inicialesNoFaturablesTotal += Calcular_Minutos($horaI, $horaF);
			}
		}



		//Facturables
		$minutos_inicialesFaturables = $minutos_inicialesFaturables * -1;
		$minutofacturable = $minutos_inicialesFaturables % 60;
		$horafacturable = ($minutos_inicialesFaturables - ($minutos_inicialesFaturables % 60)) / 60;

		if ($horafacturable == 0 && $minutofacturable == 0) {
			$HorasFacturables = 0;
		} else {
			$HorasFacturables = $horafacturable . '.' . $minutofacturable;
		}



		//Total Facturables
		$minutos_inicialesFaturablesTotal = $minutos_inicialesFaturablesTotal * -1;
		$minutofacturabletotal = $minutos_inicialesFaturablesTotal % 60;
		$horafacturabletotal = ($minutos_inicialesFaturablesTotal - ($minutos_inicialesFaturablesTotal % 60)) / 60;

		if ($horafacturabletotal == 0 && $minutofacturabletotal == 0) {
			$horafacturabletotal = 0;
		} else {
			$horafacturabletotal = $horafacturabletotal . '.' . $minutofacturabletotal;
		}

		if ($horafacturabletotal > 0) {
			$porcentajefacturable = ($HorasFacturables * 100) / $horafacturabletotal;
		} else {
			$porcentajefacturable = '';
		}
		//No facturables

		$minutos_inicialesNoFaturables = $minutos_inicialesNoFaturables * -1;

		$minutonofacturable = $minutos_inicialesNoFaturables % 60;
		$horanofacturable = ($minutos_inicialesNoFaturables - ($minutos_inicialesNoFaturables % 60)) / 60;

		if ($horanofacturable == 0 && $minutonofacturable == 0) {
			$HorasNoFacturables = 0;
		} else {
			$HorasNoFacturables = $horanofacturable . '.' . $minutonofacturable;
		}

		//Total NO Facturables
		$minutos_inicialesNoFaturablesTotal = $minutos_inicialesNoFaturablesTotal * -1;
		$minutoNofacturabletotal = $minutos_inicialesNoFaturablesTotal % 60;
		$horaNofacturabletotal = ($minutos_inicialesNoFaturablesTotal - ($minutos_inicialesNoFaturablesTotal % 60)) / 60;

		if ($horaNofacturabletotal == 0 && $minutoNofacturabletotal == 0) {
			$horaNofacturabletotal = 0;
		} else {
			$horaNofacturabletotal = $horaNofacturabletotal . '.' . $minutoNofacturabletotal;
		}
		if ($horaNofacturabletotal > 0) {
			$porcentajeNofacturable = ($HorasNoFacturables * 100) / $horaNofacturabletotal;
		} else {
			$porcentajeNofacturable = 0;
		}

		$HorasFacXPersona = 0;
		if ($numUsuer > 0) {
			$HorasFacXPersona = $horafacturabletotal / $numUsuer;
		} else {
			$HorasFacXPersona = 0;
		}

		$datan['Facturadas'] = $horafacturabletotal;
		$datan['NoFacturadas'] = $horaNofacturabletotal;
		$datan['FacXPersona'] = $HorasFacXPersona;

		return $this->set_response([
			'status' => true,
			'data' => $datan,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}


	#Grafica de FACTURACION
	public function Factura_get()
	{
		//datos grafica
		$anio   = $this->get('Anio');
		$Mes    = $this->get('Mes');

		if ($Mes < 10) {
			$Mes = '0' . $Mes;
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oplanfactura = new Mplanfactura();
		$oplanfactura->IdSucursal = $IdSucursal;
		$rowPlanFactura = $oplanfactura->get_list_planfactura();
		$TotalActual = 0;

		//$IdServicio = $_POST['IdServicio'];
		$data = '';
		$Colores = array("#f07173", "#8edce7", "#8adfb9");
		$contcol = 0;

		$meses = array(
			'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
			'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
		);

		//Plan calculo automatico
		$arrayset = array();
		$arrayApexchart  = array();




		for ($i = 1; $i < 4; $i++) {

			$oconfigservicio = new Mconfigservicio();
			$oconfigservicio->IdConfigS = $i;
			$oconfigservicio->RegEstatus = 'A';
			$valoreconfigserv = $oconfigservicio->get_recobery_configservicio();

			$PorcenajeUno   = $valoreconfigserv['data']->Porcentaje / 100;
			$PorcentajeDos  = $valoreconfigserv['data']->Porcentaje2 / 100;
			$NombreConSer   = $valoreconfigserv['data']->Nombre;


			$ofinventas = new Mfinventas();
			$ofinventas->IdSucursal = $IdSucursal;
			$ofinventas->Anio       = $anio;
			$ofinventas->IdTipoServ = $i;
			$valoresfin             = $ofinventas->get_recovery_finventasporcentajeope();

			$TotalUno       = $valoresfin['data']->TotalUno;
			$TotalDos       = $valoresfin['data']->TotalDos;
			$TotalTres      = $valoresfin['data']->TotalTres;
			$TotalCuatro    = $valoresfin['data']->TotalCuatro;

			$oserviciosvalores = new Mserviciosvalores();
			$oserviciosvalores->IdSucursal = $IdSucursal;
			$oserviciosvalores->IdConfigS = $i;
			$oserviciosvalores->get_recobery_serviciosvalores();

			$ComisionA = 0;
			$baseactual = 0;
			if ($oserviciosvalores->ComisionA != '') {
				$baseactual = $oserviciosvalores->BaseActual;
				$ComisionA  = $oserviciosvalores->ComisionA;
			}


			/* $oestadofupdate = new Mestadofupdate();
            $oestadofupdate->IdSucursal = $IdSucursal;
            $oestadofupdate->Mes = intval($Mes);
            $oestadofupdate->Mes2 = intval($Mes);
            $oestadofupdate->Anio = $anio;
            $oestadofupdate->IdConfigServ = $i;
            $oestadofupdate->IdTipoServ = "";
            $rowEstadoUpdate = $oestadofupdate->get_recobery_estadofupdate();

            $NombreFact=$rowEstadoUpdate['data']->Descripcion;*/



			//if($NombreFact == 'Facturacion'){
			foreach ($rowPlanFactura as $element) {
				$Mesbuscar      = 1;
				$TotalActual    = 0;
				$Colocar        = 0;


				if ($this->get('Mes') != 13) {

					$oestadofinanciero = new Mestadofinanciero();
					$oestadofinanciero->IdConfigS   = $i;
					$oestadofinanciero->IdSucursal  = $IdSucursal;
					$oestadofinanciero->Anio        = $anio;
					$oestadofinanciero->Mes         = $Mes;
					$oestadofinanciero->Mes2        = $Mes;
					$oestadofinanciero->IdTipoServ  = "";
					$rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();

					foreach ($rowmontoestado as $elemento) {

						$Colocar += ($elemento->Facturacion);
						$TotalActual += ($elemento->Facturacion);
					}

					$oestadofupdate = new Mestadofupdate();
					$oestadofupdate->IdSucursal     = $IdSucursal;
					$oestadofupdate->Mes            = intval($Mes);
					$oestadofupdate->Mes2           = intval($Mes);
					$oestadofupdate->Anio           = $anio;
					$oestadofupdate->IdConfigServ   = $i;
					$oestadofupdate->IdTipoServ     = "";
					$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();


					foreach ($rowEstadoUpdate as $element) {
						if ($element->Descripcion == 'Facturacion') {

							$Colocar += round($element->Monto);
							$TotalActual += round($element->Monto);
						}
					}
				} else {
					/**
					 * Funcion Anual
					 * Ubicar y calcular la data que se tenga acumulada ene todo el año
					 * 
					 */
					foreach ($meses as $listmeses) {

						$oestadofinanciero = new Mestadofinanciero();
						$oestadofinanciero->IdConfigS   = $i;
						$oestadofinanciero->IdSucursal  = $IdSucursal;
						$oestadofinanciero->Anio        = $anio;

						if ($Mesbuscar < 10) {
							$oestadofinanciero->Mes     = '0' . $Mesbuscar;
							$oestadofinanciero->Mes2    = '0' . $Mesbuscar;
						} else {
							$oestadofinanciero->Mes     = $Mesbuscar;
							$oestadofinanciero->Mes2    = $Mesbuscar;
						}

						$oestadofinanciero->IdTipoServ = "";
						$rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();

						foreach ($rowmontoestado as $elemento) {

							$Colocar += $elemento->Facturacion;
						}

						$oestadofupdate = new Mestadofupdate();
						$oestadofupdate->IdSucursal     = $IdSucursal;
						$oestadofupdate->Mes            = intval($Mesbuscar);
						$oestadofupdate->Mes2           = intval($Mesbuscar);
						$oestadofupdate->Anio           = $anio;
						$oestadofupdate->IdConfigServ   = $i;
						$oestadofupdate->IdTipoServ     = "";
						$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();



						foreach ($rowEstadoUpdate as $element) {
							if ($element->Descripcion == 'Facturacion') {
								$Colocar += round($element->Monto);
							}
						}

						//OCULATADO
						/* $Trimestre = 0;
    
                            if ($Mesbuscar < 4) {
                                $Trimestre = 1;
                                $Plan = $UnoT / 3;
                               
                            } else if ($Mesbuscar < 7) {
                                $Trimestre = 2;
                                $Plan = $DosT / 3;
                            } else if ($Mesbuscar < 10) {
                                $Trimestre = 3;
                                $Plan = $TresT / 3;
                            } else {
                                $Trimestre = 4;
                                $Plan = $CuatroT / 3;
                            }

                           
                
                            $PlanAnual += round($Plan);*/
						$TotalActual = $Colocar;



						if ($Mesbuscar == $Mes) {

							break;
						}

						$Mesbuscar++;
					}
				}
				break;
			}
			// }
			/*else if ($NombreConSer == 'Mantenimiento') {
               
            } else if ($NombreConSer == 'Servicio') {
               
            } else if ($NombreConSer == 'Proyecto') {
                
            }*/


			if ($data == '') {

				// $data .= '{
				//     "label": "' . $NombreConSer . '",
				//     "value": "' . $TotalActual . '",
				//     "color":"' . $Colores[$contcol] . '"
				// }';

				$data = array(
					'label' => $NombreConSer,
					'value' => $TotalActual
				);

				array_push($arrayset, $data);

				array_push($arrayApexchart, $TotalActual);
			} else {

				$data = array(
					'label' => $NombreConSer,
					'value' => $TotalActual
				);

				array_push($arrayset, $data);

				array_push($arrayApexchart, $TotalActual);
			}
			$contcol++;
			//echo $PlanAnual.'-';
			//echo $TotalActual.'-';

		}

		/////////////////////// nuevo
		$TotalFaturacion = array_sum($arrayApexchart);

		$data2 = array(
			'label' => 'Total',
			'value' => $TotalFaturacion
		);



		array_push($arrayset, $data2);
		array_push($arrayApexchart, $TotalFaturacion);


		#Mensaje
		return $this->set_response([
			'status' => true,
			'data' => $arrayset,
			'dataapex' => $arrayApexchart,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	#Grafica de Servicios
	public function Servicios_get()
	{
		//datos grafica
		$anio   = $this->get('Anio');
		$Mes    = $this->get('Mes');


		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oconfigservicio = new Mconfigservicio();
		$oconfigservicio->RegEstatus = 'A';
		$oconfigservicio->Facturable = 'S';
		$rowTipoSer = $oconfigservicio->get_list_configservicio();
		$arrayset = array();
		$data = '';
		$TotalGERAL = 0;
		foreach ($rowTipoSer as $elementser) {
			//buscamos el monte de facturacion de enero hasta el mes actual o seleccionado
			$oestadofinanciero = new Mestadofinanciero();
			$oestadofinanciero->IdConfigS = $elementser->IdConfigS;
			$oestadofinanciero->IdSucursal = $IdSucursal;
			$oestadofinanciero->Anio = $anio;
			if ($Mes < 10) {
				$oestadofinanciero->Mes = ('01');
				$oestadofinanciero->Mes2 = ('0' . $Mes);
			} else {
				$oestadofinanciero->Mes = ('01');
				$oestadofinanciero->Mes2 = $Mes;
			}

			$oestadofinanciero->IdTipoServ = "";
			$rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();

			$TotalFact = 0;
			foreach ($rowfacturaanio as $element) {
				$TotalFact += $element->Facturacion;
			}

			$oestadofupdate = new Mestadofupdate();
			$oestadofupdate->IdSucursal = $IdSucursal;
			$oestadofupdate->Mes = intval('01');
			$oestadofupdate->Mes2 = intval($Mes);
			$oestadofupdate->Anio = $anio;
			$oestadofupdate->IdConfigServ = $elementser->IdConfigS;
			$oestadofinanciero->IdTipoServ = "";
			$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();


			foreach ($rowEstadoUpdate as $element) {
				if ($element->Descripcion == 'Facturacion') {

					$TotalFact += round($element->Monto);
				}
			}

			$TotalGERAL += $TotalFact;

			if ($data == '') {

				$data = array(
					'label' => $elementser->Nombre,
					'value' => $TotalFact
				);

				array_push($arrayset, $data);
			} else {
				$data = array(
					'label' => $elementser->Nombre,
					'value' => $TotalFact
				);

				array_push($arrayset, $data);
			}
		}
		if ($TotalGERAL == 0) {
			if ($data == '') {


				$data = array(
					'label' => "Sin registro",
					'value' => 100
				);

				array_push($arrayset, $data);
			} else {
				$data = array(
					'label' => "Sin registro",
					'value' => 100
				);

				array_push($arrayset, $data);
			}
		}

		#Mensaje
		return $this->set_response([
			'status' => true,
			'data' => $arrayset,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	#Grafica de Servicios
	public function Porcentaje_get()
	{
		//datos grafica
		$AnioActual   = $this->get('Anio');
		$Mes    = $this->get('Mes');
		$Trimestre = 0;
		$MesActual = date("n");

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		//Estado financiaero
		$oestadofinanciero = new Mestadofinanciero();
		$oestadofinanciero->IdSucursal = $IdSucursal;
		$oestadofinanciero->Anio = $AnioActual;
		$oestadofinanciero->Mes = $Mes;
		$oestadofinanciero->IdTipoServ = '';
		if ($Mes < 10) {
			$oestadofinanciero->Mes2 = '0' . $Mes;
		} else {
			$oestadofinanciero->Mes2 = $Mes;
		}
		$rowestadofinanciero = $oestadofinanciero->get_list_estadofinanciero();


		$TotalFactura = 0;
		foreach ($rowestadofinanciero as $aestadofactura) {
			$TotalFactura += $aestadofactura->Facturacion;
		}


		$oestadofupdate = new Mestadofupdate();
		$oestadofupdate->IdSucursal = $IdSucursal;
		$oestadofupdate->Mes = intval($Mes);
		$oestadofupdate->Mes2 = intval($Mes);
		$oestadofupdate->Anio = $AnioActual;
		//$oestadofupdate->IdConfigServ=$i;
		$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

		foreach ($rowEstadoUpdate as $element) {
			if ($element->Descripcion == 'Facturacion') {
				//$TotalFact +=$element['Monto'];
				$TotalFactura += $element->Monto;
			}
		}

		$Mestotal = $Mes + 1;
		//Total Costos GA

		$MontoAnualActual = 0;
		$ocostoga = new Mcostoga();
		$ocostoga->Anio = $AnioActual;
		$ocostoga->IdSucursal = $IdSucursal;
		$rowcostodepto = $ocostoga->get_list_costoga();

		$TotalCostoGA = 0;
		$TotalActualMesGA = 0;

		if (count($rowcostodepto) > 0) {
			$Fechas = '';
			foreach ($rowcostodepto as $element) {
				$Plan = 0;

				$MontoAnualActual = 0;

				$oactualcostoga = new Mactualcostoga();
				$oactualcostoga->IdCostoGA = $element->IdCostoGA;
				$oactualcostoga->Mes = $Mes;
				$oactualcostoga->Anio = $AnioActual;
				$oactualcostoga->IdSucursal = $IdSucursal;
				$valuecostoga = $oactualcostoga->get_recobery_actualcostoga();

				if (round($valuecostoga['data']->MontoMes) == 0) {
					$montmes = '';
				} else {
					$montmes = round($valuecostoga['data']->MontoMes);
				}
				$TotalActualMesGA += round($montmes);
			}
		}



		if ($TotalFactura <= 0) {
			$PorcentajeGA = 0;
		} else {
			$PorcentajeGA = ($TotalActualMesGA / $TotalFactura) * 100;
		}

		//Depto. Ventas

		$ogastosdirectos = new Mgastosdirectos();
		$ogastosdirectos->IdSucursal = $IdSucursal;
		$ogastosdirectos->Tipo = "1";
		$ogastosdirectos->Anio = $AnioActual;
		$row = $ogastosdirectos->get_list_gastosdirectos();

		$ogastosdirectos = new Mgastosdirectos();
		$ogastosdirectos->IdSucursal = $IdSucursal;
		$ogastosdirectos->Tipo = "2";
		$ogastosdirectos->Anio = $AnioActual;
		$rowind = $ogastosdirectos->get_list_gastosdirectos();

		$VentasActualMes = 0;
		foreach ($row as $element) {

			$oactualventas = new Mactualventas();
			$oactualventas->IdGasto = $element->IdGasto;
			$oactualventas->Mes = $Mes;
			$oactualventas->Anio = $AnioActual;
			$oactualventas->IdSucursal = $IdSucursal;
			$valueventas = $oactualventas->get_recobery_actualventas();

			$montval = round($valueventas['data']->MontoMes);

			if (round($valueventas['data']->MontoMes) == 0) {
				$montval = '';
			}

			if (round($montval) != '' || round($montval) != '0') {
				$VentasActualMes += round($montval);
			}
		}


		//GASTOS INDIRECTOS ****

		foreach ($rowind as $element) {

			$oactualventas = new Mactualventas();
			$oactualventas->IdGasto = $element->IdGasto;
			$oactualventas->Mes = $Mes;
			$oactualventas->Anio = $AnioActual;
			$oactualventas->IdSucursal = $IdSucursal;
			$actualventas2 = $oactualventas->get_recobery_actualventas();



			$montval = round($actualventas2['data']->MontoMes);
			if (round($actualventas2['data']->MontoMes) == 0) {
				$montval = '';
			}

			if (round($montval) != '' || round($montval) != '0') {
				$VentasActualMes += round($montval);
			}
		}
		if ($TotalFactura <= 0) {
			$PorcentajeVenta = 0;
		} else {
			$PorcentajeVenta = ($VentasActualMes / $TotalFactura) * 100;
		}

		//*********** Procentaje de procentaje **********************

		//**TOTALES DE LOS SERVICIOS 

		//Total mesual de vehiculo mano de obra y burden toal.

		$BurdenTotal = 0;
		$ManoObraT = 0;
		$CostoV = 0;
		$EquiposD = 0;
		$MaterialesD = 0;
		$ViaticosD = 0;
		$ContratistasD = 0;
		$TotalMontFact = 0;

		for ($i = 1; $i < 6; $i++) {


			$oservicio = new Mservicio();
			$oservicio->Fecha_F = $AnioActual . '-' . $Mes;
			$oservicio->RegEstatus = 'A';
			$oservicio->IdSucursal = $IdSucursal;
			$oservicio->Tipo_Serv = $i;

			$rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

			foreach ($rowmesserv as $elementfin) {
				if ($elementfin->BurdenTotal != '') {
					$BurdenTotal += $elementfin->BurdenTotal;
					$ManoObraT += $elementfin->ManoObraT;
					$CostoV += $elementfin->CostoV;
					$EquiposD += $elementfin->EquiposD;
					$MaterialesD += $elementfin->MaterialesD;
					$ViaticosD += $elementfin->ViaticosD;
					$ContratistasD += $elementfin->ContratistasD;
				}
			}
		}

		//Fin

		//****AQUI SE OBTIENEN LOS UPDATE************* //

		//**
		//aqui recorremos los uodate

		for ($i = 1; $i < 6; $i++) {
			//Este es nuevo EstadoUpdateClass
			$oestadofupdate = new Mestadofupdate();
			$oestadofupdate->IdSucursal = $IdSucursal;
			$oestadofupdate->Mes = intval($Mes);
			$oestadofupdate->Mes2 = intval($Mes);
			$oestadofupdate->Anio = $AnioActual;
			$oestadofupdate->IdConfigServ = $i;
			$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

			foreach ($rowEstadoUpdate as $element) {
				if ($element->Descripcion == 'Burden') {
					$BurdenTotal += $element->Monto;
				}
				if ($element->Descripcion == 'Mano de Obra') {
					$ManoObraT += $element->Monto;
				}

				if ($element->Descripcion == 'Vehiculos') {
					$CostoV += $element->Monto;
				}
				if ($element->Descripcion == 'Equipos') {
					$EquiposD += $element->Monto;
				}
				if ($element->Descripcion == 'Materiales') {
					$MaterialesD += $element->Monto;
				}
				if ($element->Descripcion == 'Viaticos') {
					$ViaticosD += $element->Monto;
				}
				if ($element->Descripcion == 'Contratistas') {
					$ContratistasD += $element->Monto;
				}
			}

			//FIN ESTADO UPDATE CLASS
		}


		$TotalCostoOperacional = $BurdenTotal + $ManoObraT + $CostoV + $EquiposD + $MaterialesD + $ViaticosD + $ContratistasD;


		if ($TotalFactura <= 0) {
			$PorcentajeCostoOp = 0;
		} else {
			$PorcentajeCostoOp = ($TotalCostoOperacional * 100) / $TotalFactura;
		}

		if ($PorcentajeCostoOp < 0) {
			$PorcentajeCostoOp = 0;
		}

		$PorceGrosProfit = 100 - $PorcentajeCostoOp;

		if ($PorceGrosProfit <= 0) {
			$PorceGrosProfit = 0;
		}

		$data['DiesTGA'] = $PorcentajeGA;
		$data['DiesTDV'] = $PorcentajeVenta;
		$data['GrossP'] = $PorceGrosProfit;


		#Mensaje
		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	#Grafica de GrossProfit
	public function GrossProfit_get()
	{
		//datos grafica
		$anio   = $this->get('Anio');
		$Mes    = $this->get('Mes');

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


		// OBTENER LOS 3 TIPOS PRINCIPALES DE SERVICIOS [MANTENIMIENTO, SERVICIO, PROYECTO]
		$oconfigservicio = new Mconfigservicio();
		$oconfigservicio->RegEstatus = 'A';
		$oconfigservicio->Facturable = 'S';
		$rowSerConfig = $oconfigservicio->get_list_configservicio(); // Mantenimiento, Servicio, Proyecto


		/** 
		 * TOMAR EL PLAN DE FACTURACION - SUB TIPOS DE SERVICIO
		 * ["Facturacion", "Materiales", "Equipos", "Mano de Obra", "Vehiculos", "Contratistas", "Viaticos", "Burden"] 
		 * */
		$oplanfactura = new Mplanfactura();
		$oplanfactura->IdSucursal   = $IdSucursal;
		$rowPlanFactura             = $oplanfactura->get_list_planfactura();




		$contserconf = 0;
		$Colores = array('#f07173', '#659ad2', '#8edce7', '#659ad2', '#8adfb9', '#659ad2');
		$Mes = $Mes + 1;

		if ($Mes < 10) {
			$Mes = '0' . $Mes;
		}

		$dataset = array();

		// BUCLE DE LOS 3 TIPOS PRINCIPALES DE SERVICIO
		foreach ($rowSerConfig as $aserconfig) {
			$acumulado = array();

			$IdServicio = $aserconfig->IdConfigS;

			//************CALCULOS***********
			//************CALCULOS*******
			//************CALCULOS************

			// DETERMINAR TRIMESTRE AL QUE PERTENECE EL MES PROPORCIONADO
			$Trimestre = 0;
			if ($Mes < 3) {
				$Trimestre = 1;
			} else if ($Mes < 6) {
				$Trimestre = 2;
			} else if ($Mes < 9) {
				$Trimestre = 3;
			} else {
				$Trimestre = 4;
			}



			$oservicio = new Mservicio();
			$oservicio->Fecha_F = $anio . '-' . $Mes;
			$oservicio->RegEstatus = 'A';
			$oservicio->IdSucursal = $IdSucursal;
			$oservicio->Tipo_Serv = $IdServicio;
			$rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

			$BurdenTotal = 0;
			$ManoObraT = 0;
			$CostoV = 0;
			$EquiposD = 0;
			$MaterialesD = 0;
			$ViaticosD = 0;
			$ContratistasD = 0;
			foreach ($rowmesserv as $elementfin) {
				if ($elementfin->BurdenTotal != '') {
					$BurdenTotal += $elementfin->BurdenTotal;
					$ManoObraT += $elementfin->ManoObraT;
					$CostoV += $elementfin->CostoV;

					$EquiposD += $elementfin->EquiposD;
					$MaterialesD += $elementfin->MaterialesD;
					$ViaticosD += $elementfin->ViaticosD;
					$ContratistasD += $elementfin->ContratistasD;
				}
			}
			//Fin 
			//Traer total de servicios de enero hasta el mes actual vehiculo mano de obra y burden toal.



			//dib


			//buscamos el monte de facturacion de enero hasta el mes actual o seleccionado
			$oestadofinanciero = new Mestadofinanciero();
			$oestadofinanciero->IdConfigS = $IdServicio;
			$oestadofinanciero->IdTipoServ = "";
			$oestadofinanciero->IdSucursal = $IdSucursal;
			$oestadofinanciero->Anio = $anio;
			$oestadofinanciero->Mes = '01';
			$oestadofinanciero->Mes2 = $Mes;
			$rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();
			$TotalFact = 0;
			foreach ($rowfacturaanio as $element) {
				$TotalFact += $element->Facturacion;
			}


			//****BUSQUEDA DE ESTADOFINANCIERO EN LA BASE DE DATOS


			//Este es nuevo EstadoUpdateClass
			$oestadofupdate = new Mestadofupdate();
			$oestadofupdate->IdSucursal = $IdSucursal;
			$oestadofupdate->Mes = intval($Mes);
			$oestadofupdate->Mes2 = intval($Mes);
			$oestadofupdate->Anio = $anio;
			$oestadofupdate->IdConfigServ = $IdServicio;
			$oestadofupdate->IdTipoServ = "";
			$rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

			foreach ($rowEstadoUpdate as $element) {
				if ($element->Descripcion == 'Facturacion') {
					//$TotalFact +=$element->Monto;
					$TotalFact += $element->Monto;
				}
				if ($element->Descripcion == 'Burden') {
					$BurdenTotal += $element->Monto;
				}
				if ($element->Descripcion == 'Mano de Obra') {
					$ManoObraT += $element->Monto;
				}

				if ($element->Descripcion == 'Vehiculos') {
					$CostoV += $element->Monto;
				}
				if ($element->Descripcion == 'Equipos') {
					$EquiposD += $element->Monto;
				}
				if ($element->Descripcion == 'Materiales') {
					$MaterialesD += $element->Monto;
				}
				if ($element->Descripcion == 'Viaticos') {
					$ViaticosD += $element->Monto;
				}
				if ($element->Descripcion == 'Contratistas') {
					$ContratistasD += $element->Monto;
				}
			}

			//FIN ESTADO UPDATE CLASS




			//Regitro de concepto 
			$conplanfac = 0;
			$porcentajeFactura = 0;
			$totalporcentajeFactura = 0;
			$totalGrossProfit = 0;
			foreach ($rowPlanFactura as $element) {
				$oporcentajeoperacion = new Mporcentajeoperacion();
				$oporcentajeoperacion->IdSucursal = $IdSucursal;
				$oporcentajeoperacion->IdTipoSer = $IdServicio;
				$oporcentajeoperacion->Anio = $anio;
				$oporcentajeoperacion->IdPlanFactura = $element->IdPlanFactura;
				$dataporcenoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinanciero();

				$TotalAnual = $dataporcenoperacion['data']->PrimerT + $dataporcenoperacion['data']->SegundoT + $dataporcenoperacion['data']->TercerT + $dataporcenoperacion['data']->CuartoT;
				//****Este es el procetaje para anual mesual y actual del porcetjae de operacion;
				$PorcentajePlan = $dataporcenoperacion['data']->PorcentajeAnual;
				//****Find e Porcentaje Plan


				//Plan Mensual actual
				$Plan = 0;

				if ($Trimestre == 1) {
					$Plan = $dataporcenoperacion['data']->PrimerT / 3;
				}

				if ($Trimestre == 2) {
					$Plan = $dataporcenoperacion['data']->SegundoT / 3;
				}

				if ($Trimestre == 3) {
					$Plan = $dataporcenoperacion['data']->TercerT / 3;
				}
				if ($Trimestre == 4) {
					$Plan = $dataporcenoperacion['data']->CuartoT / 3;
				}

				//para el año actual Plan
				$PlanAnual = 0;
				$valorprim = $dataporcenoperacion['data']->PrimerT / 3;
				$valorseug = $dataporcenoperacion['data']->SegundoT / 3;
				$valorter = $dataporcenoperacion['data']->TercerT / 3;
				$valorcuatro = $dataporcenoperacion['data']->CuartoT / 3;
				$count = 1;

				for ($i = 1; $i <= 12; $i++) {
					if ($i < 4) {
						$PlanAnual += $valorprim;
					} else if ($i < 7) {
						$PlanAnual += $valorseug;
					} else if ($i < 10) {
						$PlanAnual += $valorter;
					} else {
						$PlanAnual += $valorcuatro;
					}

					if ($count == $Mes) {
						break;
					}
					$count++;
				}



				//Dependiendo de el nombre se pinta colocar en la columna  para mano de obra costo vehiculo y burden total
				$Colocar = 0;
				$ColocarAnioActual = 0;
				$readonlyactual = '';



				if ($element->Descripcion == 'Facturacion') {

					//colocamos la facturacion almacenada en la base de datos
					//$Colocar = $oestadofinanciero->Facturacion;

				}

				if ($element->Descripcion == 'Mano de Obra') {
					$Colocar = $ManoObraT; //paa el mes actual
					$readonlyactual = 'readonly="true"';
				}
				if ($element->Descripcion == 'Vehiculos') {
					$Colocar = $CostoV;
					$readonlyactual = 'readonly="true"';
				}
				if ($element->Descripcion == 'Burden') {
					$Colocar = $BurdenTotal;
					$readonlyactual = 'readonly="true"';
				}

				if ($element->Descripcion == 'Materiales') {
					$Colocar = $MaterialesD;
					$readonlyactual = 'readonly="true"';
				}

				if ($element->Descripcion == 'Equipos') {
					$Colocar = $EquiposD;
					$readonlyactual = 'readonly="true"';
				}

				if ($element->Descripcion == 'Contratistas') {
					$Colocar = $ContratistasD;
					$readonlyactual = 'readonly="true"';
				}
				if ($element->Descripcion == 'Viaticos') {
					$Colocar = $ViaticosD;
					$readonlyactual = 'readonly="true"';
				}


				if ($conplanfac > 0) {
					$Factura = $TotalFact;
					if ($TotalFact <= 0) {
						$Factura = 1;
					}

					$porcentajeFactura = round(($Colocar * 100) / $Factura);
					$totalporcentajeFactura += $porcentajeFactura;
				}


				$conplanfac++;

				//Total de grossprofit

			}

			$totalGrossProfit = 100 - $totalporcentajeFactura;

			if ($totalGrossProfit < 0) {
				$totalGrossProfit = 0;
			}
			$colorN = '#e67e22';

			if ($aserconfig->Nombre === 'Mantenimiento') {
				$colorN = '#e67e22';
			} else if ($aserconfig->Nombre === 'Servicio') {
				$colorN = '#e74c3c';
			} else if ($aserconfig->Nombre === 'Proyecto') {
				$colorN = '#3498db';
			}
			///nuevo
			$data3 = array();
			$data = array(
				'value' => $totalGrossProfit,
				'color' => $colorN,
				'label' => $aserconfig->Nombre,
			);


			$data2 = array(
				'value' => $totalporcentajeFactura,
				'color' => '#2ecc71',
				'label' => '',
			);

			array_push($acumulado, $data);
			array_push($acumulado, $data2);

			$data3[$aserconfig->Nombre] = $acumulado;

			//$data3[$aserconfig->Porcentaje]=$acumulado;

			array_push($dataset, $data3);

			$contserconf++;

			if ($contserconf == count($Colores)) {
				$contserconf = 0;
			}
		}

		#Mensaje
		return $this->set_response([
			'status' => true,
			'data' => $dataset,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function cuentasTotal_get()
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

		$Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
		$ctapagar = array();
		$ctacobrar = array();

		for ($i = 1; $i < count($Meses) + 1; $i++) {

			$Mctaporpagar = new Mctaporpagar();
			$Mctaporpagar->IdSucursal = $IdSucursal;

			if ($i < 10) {
				$Mctaporpagar->Fecha = $Anio . '-0' . $i;
			} else {
				$Mctaporpagar->Fecha = $Anio . '-' . $i;
			}

			$sumPayAaccount = $Mctaporpagar->getSumGraphics();
			foreach ($sumPayAaccount as $element) {
				$pagar = $element->sumAmount;
				if ($pagar == "") {
					$pagar = 0;
				}
			}

			$Mctaporcobrar = new Mctaporcobrar();
			$Mctaporcobrar->IdSucursal = $IdSucursal;

			if ($i < 10) {
				$Mctaporcobrar->Fecha = $Anio . '-0' . $i;
			} else {
				$Mctaporcobrar->Fecha = $Anio . '-' . $i;
			}

			$sumCollectAaccount = $Mctaporcobrar->getSumGraphics();
			foreach ($sumCollectAaccount as $element) {
				$cobrar = $element->sumAmount;
				if ($cobrar == "") {
					$cobrar = 0;
				}
			}

			array_push($ctapagar, $pagar);
			array_push($ctacobrar, $cobrar);
		}

		$data['ctapagar'] = $ctapagar;
		$data['ctacobrar'] = $ctacobrar;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}
}
