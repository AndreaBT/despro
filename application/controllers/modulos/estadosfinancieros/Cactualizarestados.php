<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cactualizarestados extends REST_Controller
{
    public $RutaPdf;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('estadosf/Mplanfactura');
        $this->load->model('estadosf/Mserviciosf');
        $this->load->model('estadosf/Mestadosfinancieros');
        $this->load->model('estadosf/Mdetalleestado');
        $this->load->model('estadosf/Mcostoga');
        $this->load->model('estadosf/Mactualcostoga');
        $this->load->model('estadosf/Mgastosdirectos');
        $this->load->model('estadosf/Mactualventas');
        $this->load->model('estadosf/Mcostodeptoventas');
        $this->load->model('estadosf/Mactualoperaciones');
        $this->load->model('estadosf/Mestadofupdate');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mcostofinanciero');
        $this->load->model('estadosf/Mactualcostof');
        $this->load->model('estadosf/Mporcentajeoperacion');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mactualcostove');

        setTimeZone($this->verification,$this->input);
    }

    public function getDataTodosFac_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $arraydatos = array();
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        //$this->response($IdSucursal);
        $Anio = $this->get('Anio');
        $AnioActual = $this->get('Anio');
        $Mes = $this->get('Mes');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        //Insertar el plan
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

        $arrayplanfact = array("Facturacion", "Materiales", "Equipos", "Mano de Obra", "Vehiculos", "Contratistas", "Viaticos", "Burden");

        if (count($rowPlanFactura) == 0) {
            foreach ($arrayplanfact as $Descripcion) {
                $oplanfactura = new Mplanfactura();
                $oplanfactura->IdSucursal = $IdSucursal;
                $oplanfactura->Descripcion = $Descripcion;
                $oplanfactura->set_insert_planfactura();
            }
        }

        //aqui no
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

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

        $MesActual = date("n");

        //Traer total de servicios de enero hasta el mes actual vehiculo mano de obra y burden toal.
        //Aqui se debe hacer un bettwen 
        $BurdenTotalAnioActual = 0;
        $ManoObraTAnioActual = 0;
        $CostoVAnioActual = 0;

        $EquiposDAno = 0;
        $MaterialesDAnio = 0;
        $ViaticosDAnio = 0;
        $ContratistasDAnio = 0;

        for ($i = 1; $i < 6; $i++)
        {
            $oservicio = new Mserviciosf();
            $oservicio->Fecha_F = $Anio . '-' . $Mes . '-31';
            $oservicio->Fecha_I = $Anio . '-' . '01-' . '01';
            $oservicio->RegEstatus = 'A';
            $oservicio->IdSucursal = $IdSucursal;

            if($IdClienteS > 0) {
                $oservicio->IdClienteS = $IdClienteS;
            }
            if($IdCliente > 0) {
                $oservicio->IdCliente = $IdCliente;
            }
            if($IdContrato > 0) {
                $oservicio->IdContrato = $IdContrato;
            }

            $oservicio->Tipo_Serv = $i;
            $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

            foreach($rowmesserv as $elementfin)
            {
                if($elementfin->BurdenTotal != '')
                {
                    $BurdenTotalAnioActual += $elementfin->BurdenTotal;
                    $ManoObraTAnioActual += $elementfin->ManoObraT;
                    $CostoVAnioActual += $elementfin->CostoV;
                    $EquiposDAno += $elementfin->EquiposD;
                    $MaterialesDAnio += $elementfin->MaterialesD;
                    $ViaticosDAnio += $elementfin->ViaticosD;
                    $ContratistasDAnio += $elementfin->ContratistasD;
                }
            }
        }

        //buscamos el monte de facturacion de enero hasta el mes actual o seleccionado
        $TotalFact = 0;
        for ($i = 1; $i < 6; $i++)
        {
            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = '01';
            $oestadofinanciero->Mes2 = $Mes;
            $rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();

            foreach ($rowfacturaanio as $element) {
                $TotalFact += $element->Facturacion;
            }
        }

        $TotalFact = $TotalFact;

        //****BUSQUEDA DE ESTADOFINANCIERO EN LA BASE DE DATOS
        $MontoFactActual = 0;
        $FactPasAcom = 0;
        $MaterialPasAcom = 0;
        $EquiposPasAcom = 0;
        $ManoOPasAcom = 0;
        $VehiculoPasAcom = 0;
        $ContratistaPasAcom = 0;
        $ViaticosPasAcom = 0;
        $BurdenPasAcom = 0;

        for ($i = 1; $i < 6; $i++)
        {
            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = $Mes;
            $oestadofinanciero->Mes2 = $Mes;
            $oestadofinanciero->IdTipoServ = "";
            $rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();

            foreach($rowmontoestado as $elemento) {
                $MontoFactActual += $elemento->Facturacion;
            }

            $countfac = 0;
            foreach($rowPlanFactura as $element)
            {
                $odetalleestadofinanciero = new Mdetalleestado();
                $odetalleestadofinanciero->IdEstadoF = $oestadofinanciero->IdEstadoF;
                $odetalleestadofinanciero->IdPlanFactura = $element->IdPlanFactura;
                $odetalleestadofinanciero->get_recobery_detalleestadofinanciero();

                if ($countfac == 0) {
                    $FactPasAcom += $odetalleestadofinanciero->Pasado;
                }
                $countfac++;
                if ($element->Descripcion == 'Mano de Obra') {
                    $ManoOPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Vehiculos') {
                    $VehiculoPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Burden') {
                    $BurdenPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Materiales') {
                    $MaterialPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Equipos') {
                    $EquiposPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Contratistas') {
                    $ContratistaPasAcom += $odetalleestadofinanciero->Pasado;
                }
                if ($element->Descripcion == 'Viaticos') {
                    $ViaticosPasAcom += $odetalleestadofinanciero->Pasado;
                }
            }
        }

        if ($oestadofinanciero->IdEstadoF > 0) {
        }

        //**Para el detalleestadofinanciero de la base de datos anios pasados
        //**SACAR PORCENTAJES 
        //Total mesual de vehiculo mano de obra y burden toal.

        $BurdenTotal = 0;
        $ManoObraT = 0;
        $CostoV = 0;
        $EquiposD = 0;
        $MaterialesD = 0;
        $ViaticosD = 0;
        $ContratistasD = 0;
        $TotalMontFact = 0;

        for ($i = 1; $i < 6; $i++)
        {
            $oservicio = new Mserviciosf();
            $oservicio->Fecha_F = $Anio . '-' . $Mes;
            $oservicio->RegEstatus = 'A';
            $oservicio->IdSucursal = $IdSucursal;
            $oservicio->Tipo_Serv = $i;

            if ($IdClienteS > 0) {
                $oservicio->IdClienteS = $IdClienteS;
            }
            if ($IdCliente > 0) {
                $oservicio->IdCliente = $IdCliente;
            }
            if ($IdContrato > 0) {
                $oservicio->IdContrato = $IdContrato;
            }
            
            $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

            foreach ($rowmesserv as $elementfin)
            {
                if ($elementfin->BurdenTotal != '')
                {

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

        //aqui recorremos los update
        for ($i = 1; $i < 6; $i++)
        {
            //Este es nuevo EstadoUpdateClass
            $oestadofupdate = new Mestadofupdate();
            $oestadofupdate->IdSucursal = $IdSucursal;
            $oestadofupdate->Mes = intval('01');
            $oestadofupdate->Mes2 = intval($Mes);
            $oestadofupdate->Anio = $Anio;
            $oestadofupdate->IdConfigServ = $i;
            $oestadofupdate->IdTipoServ = "";
            $rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

            foreach($rowEstadoUpdate as $element) {
                if($element->Descripcion == 'Facturacion') {
                    $TotalFact += $element->Monto;
                }
                if($element->Descripcion == 'Burden') {
                    $BurdenTotalAnioActual += $element->Monto;
                }
                if($element->Descripcion == 'Mano de Obra') {
                    $ManoObraTAnioActual += $element->Monto;
                }
                if($element->Descripcion == 'Vehiculos') {
                    $CostoVAnioActual += $element->Monto;
                }
                if($element->Descripcion == 'Equipos') {
                    $EquiposDAno += $element->Monto;
                }
                if($element->Descripcion == 'Materiales') {
                    $MaterialesDAnio += $element->Monto;
                }
                if($element->Descripcion == 'Viaticos') {
                    $ViaticosDAnio += $element->Monto;
                }
                if($element->Descripcion == 'Contratistas') {
                    $ContratistasDAnio += $element->Monto;
                }
            }
            //FIN ESTADO UPDATE CLASS
        }

        if (count($rowPlanFactura)) 
        {
            $con = 0;
            $arraydatos = array();
            $countfac = 0;
            $PlanAnualGral = 1;
            $PlanMesGral = 1;
            $PorcenAnio = 0;
            $PorcenMes = 0;
            
            foreach ($rowPlanFactura as $element)
            {
                $PrimerTT = 0;
                $SegundoTT = 0;
                $TercerTT = 0;
                $CuartoTT = 0;
                $AnoAnteriorMont = 0;
            
                //$TotalAnual=$oporcentajeoperacion->PrimerT+$oporcentajeoperacion->SegundoT+$oporcentajeoperacion->TercerT+$oporcentajeoperacion->CuartoT;
                //****Este es el procetaje para anual mesual y actual del porcetjae de operacion;
                //****Find e Porcentaje Plan
            
                $Plan = 0;
                $PlanAnual = 0;
                $TotalAnual = 0;

                $oporcentajeoperacion = new Mporcentajeoperacion();
                $oporcentajeoperacion->IdSucursal = $IdSucursal;
                $oporcentajeoperacion->Descripcion = $element->Descripcion;
                $oporcentajeoperacion->Anio = $Anio;
                $oporcentajeoperacion->IdSubtipoServ = 0;
                $porcentdata = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinanciero2();

                $PrimerTT = $porcentdata['data']->PrimerT;
                $SegundoTT = $porcentdata['data']->SegundoT;
                $TercerTT = $porcentdata['data']->TercerT;
                $CuartoTT = $porcentdata['data']->CuartoT;
                $AnoAnteriorMont = $porcentdata['data']->AnioAnterior;

                //aqui abajo se divide entre tres por que esta por trimestre
                //Plan Mensual actual
                $Plan = 0;

                if($Trimestre == 1) {
                    $Plan = $PrimerTT / 3;
                }
                if($Trimestre == 2) {
                    $Plan = $SegundoTT / 3;
                }
                if($Trimestre == 3) {
                    $Plan = $TercerTT / 3;
                }
                if($Trimestre == 4) {
                    $Plan = $CuartoTT / 3;
                }

                //para el año actual Plan
                $PlanAnual = 0;
                $valorprim = $PrimerTT / 3;
                $valorseug = $SegundoTT / 3;
                $valorter = $TercerTT / 3;
                $valorcuatro = $CuartoTT / 3;
                $count = 1;

                for ($i = 1; $i <= 12; $i++)
                {
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
                $ColocarPasado = 0;
                $ColocarAnioActual = 0;
                $readonlyactual = '';
                $clasmonto = 'features-input-dis ';
                $readonlmonto = 'readonly="true"';
                $AnoAnteriorPorcent = 0;

                if ($countfac == 0)
                {
                    //colocamos la facturacion almacenada en la base de datos
                    $Colocar = $MontoFactActual;
                    $ColocarAnioActual = $TotalFact;
                    //$readonlmonto='';
                    $clasmonto = 'features-input-dis';
                    $ColocarPasado = $FactPasAcom;

                    if ($PlanAnual == 0) {
                    } else {
                        $PlanAnualGral = $PlanAnual;
                    }
                    if ($Plan == 0) {
                    } else {
                        $PlanMesGral = $Plan;
                    }

                    $PorcenAnio = 100;
                    $PorcenMes = 100;
                }
                $countfac++;

                if($element->Descripcion == 'Mano de Obra')
                {
                    $Colocar = $ManoObraT; //paa el mes actual
                    $ColocarAnioActual = $ManoObraTAnioActual; //para el mes 1 al mes actual
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $ManoOPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion == utf8_encode('Vehiculos'))
                {
                    $Colocar = $CostoV;
                    $ColocarAnioActual = $CostoVAnioActual;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $VehiculoPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion == 'Burden')
                {
                    $Colocar = $BurdenTotal;
                    $ColocarAnioActual = $BurdenTotalAnioActual;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $BurdenPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion == 'Materiales')
                {
                    $Colocar = $MaterialesD;
                    $ColocarAnioActual = $MaterialesDAnio;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $MaterialPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion == 'Equipos')
                {
                    $Colocar = $EquiposD;
                    $ColocarAnioActual = $EquiposDAno;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $EquiposPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion == 'Contratistas')
                {
                    $Colocar = $ContratistasD;
                    $ColocarAnioActual = $ContratistasDAnio;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $ContratistaPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }
                if($element->Descripcion ==  utf8_encode('Viaticos'))
                {
                    $Colocar = $ViaticosD;
                    $ColocarAnioActual = $ViaticosDAnio;
                    $readonlyactual = 'readonly="true"';
                    $ColocarPasado = $ViaticosPasAcom;
                    $PorcenAnio = ($PlanAnual * 100) / $PlanAnualGral;
                    $PorcenMes = ($Plan * 100) / $PlanMesGral;
                }

                #Aqui dentro va los datos
                $array = array(
                    'IdPlanFactura'         => $element->IdPlanFactura,
                    'IdPorcentajeOperacion' => $oporcentajeoperacion->IdPorcentajeOperacion,
                    'Descripcion'           => $element->Descripcion,
                    'AnoAnteriorMont'       => number_format($AnoAnteriorMont,0,'.',''),
                    'PorcentajeAnterior'    => number_format(0,1,'.',''),
                    'PlanAnual'             => number_format($PlanAnual,0,'.',''),
                    'PorcenAnio'            => $PorcenAnio,
                    'ColocarAnioActual'     => number_format($ColocarAnioActual,0,'.',''),
                    'PorcentajeAnio'        => round(0),
                    'PlanMesActual'         => number_format($Plan,0,'.',''),
                    'PorcentajePlanMesActual'=> $PorcenMes,
                    'ActualMes'             => number_format($Colocar,0,'.',''),
                    'PorcentajeMesACtual'   => 0,
                );

                array_push($arraydatos,$array);
            }
        }

        #Mensaje
        return $this->set_response([
            'status' => true,
            'data' => $arraydatos,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
?>