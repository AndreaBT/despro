<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cestadosfinancieros extends REST_Controller
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


    public function getDataTodos_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $arraydatos = array();

        
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        

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
        
        //print_r($rowPlanFactura);
        //************CALCULOS***********
        //************CALCULOS*******
        //************CALCULOS************

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



        //Total de enero hasta el mes actual del anio


        //Este es el mes seleccionado del combo
        $Mes = $Mes + 1;

        if ($Mes < 10) {
            $Mes = '0' . $Mes;
        }

        //Traer total de servicios de enero hasta el mes actual vehiculo mano de obra y burden toal.

        //Aqui se debe hacer un bettwen 

        $BurdenTotalAnioActual = 0;
        $ManoObraTAnioActual = 0;
        $CostoVAnioActual = 0;

        $EquiposDAno = 0;
        $MaterialesDAnio = 0;
        $ViaticosDAnio = 0;
        $ContratistasDAnio = 0;

        for ($i = 1; $i < 6; $i++) {

            $oservicio = new Mserviciosf();
            $oservicio->Fecha_F = $Anio . '-' . $Mes . '-31';
            $oservicio->Fecha_I = $Anio . '-' . '01-' . '01';
            $oservicio->RegEstatus = 'A';
            $oservicio->IdSucursal = $IdSucursal;

            if ($IdClienteS > 0) {
                $oservicio->IdClienteS = $IdClienteS;
            }

            if ($IdCliente > 0) {
                $oservicio->IdCliente = $IdCliente;
            }

            if ($IdContrato > 0) {
                $oservicio->IdContrato = $IdContrato;
            }

            $oservicio->Tipo_Serv = $i;
            
            $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();
       
            foreach ($rowmesserv as $elementfin) {
                
                if ($elementfin->BurdenTotal != '') {

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

        //echo 'Pasa';
        for ($i = 1; $i < 6; $i++) {
            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = '01';
            $oestadofinanciero->Mes2 = $Mes;
            $oestadofinanciero->IdCliente = $IdCliente;
            $oestadofinanciero->IdClienteS = $IdClienteS;
            $oestadofinanciero->IdContrato = $IdContrato;
            $rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();
            // echo '<pre>';
            // print_r($rowfacturaanio);
            // echo '</pre>';
                 //print_r($rowmesserv);
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

        for ($i = 1; $i < 6; $i++) {

            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = $Mes;
            $oestadofinanciero->Mes2 = $Mes;
            $oestadofinanciero->IdTipoServ = "";
            $oestadofinanciero->IdCliente = $IdCliente;
            $oestadofinanciero->IdClienteS = $IdClienteS;
            $oestadofinanciero->IdContrato = $IdContrato;
            $rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();
            // echo '<pre>';
            //      print_r('hola');
            //    echo '</pre>';

            // echo '<pre>';
            // print_r($rowmontoestado);
            // echo '</pre>';
            //Este de aca no trae nada
            foreach ($rowmontoestado as $elemento) {
                $MontoFactActual += $elemento->Facturacion;
            }

            


            $countfac = 0;
            foreach ($rowPlanFactura as $element) {

                $odetalleestadofinanciero = new Mdetalleestado();
                $odetalleestadofinanciero->IdEstadoF = $oestadofinanciero->IdEstadoF;
                $odetalleestadofinanciero->IdPlanFactura = $element->IdPlanFactura;
                $recoverydetallef = $odetalleestadofinanciero->get_recobery_detalleestadofinanciero();

                //print_r($recoverydetallef);

                if ($countfac == 0) {
                    $FactPasAcom += $recoverydetallef['data']->Pasado;
                }
                $countfac++;

                if ($element->Descripcion == 'Mano de Obra') {
                    $ManoOPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Vehiculos') {
                    $VehiculoPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Burden') {
                    $BurdenPasAcom += $recoverydetallef['data']->Pasado;
                }

                if ($element->Descripcion == 'Materiales') {
                    $MaterialPasAcom += $recoverydetallef['data']->Pasado;
                }

                if ($element->Descripcion == 'Equipos') {
                    $EquiposPasAcom += $recoverydetallef['data']->Pasado;
                }

                if ($element->Descripcion == 'Contratistas') {
                    $ContratistaPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Viaticos') {
                    $ViaticosPasAcom += $recoverydetallef['data']->Pasado;
                }
            }
        }



        //$op = 'Guardar';

        if ($oestadofinanciero->IdEstadoF > 0) {
            //$op = 'Modificar';
        }

        //****COSTOS G&A

        $TotalAnioPasadoGA = 0;
        $TotalPlanAcomGA = 0;
        $TotalActualAcomGA = 0;
        $TotalPlanMesGA = 0;
        $TotalActualMesGA = 0;

        $ocostoga = new Mcostoga();
        $ocostoga->Anio = $Anio;
        $ocostoga->IdSucursal = $IdSucursal;
        $rowcostodepto = $ocostoga->get_list_costoga();

        if (count($rowcostodepto) > 0) {

            foreach ($rowcostodepto as $element) {

                $Plan = 0;

                //para el a�o actual
                $PlanAnual = 0;
                $valorprim = $element->PrimerT / 3;
                $valorseug = $element->SegundoT / 3;
                $valorter = $element->TercerT / 3;
                $valorcuatro = $element->CuartoT / 3;


                $count = 1;

                for ($i = 1; $i <= 12; $i++) {

                    if ($Trimestre == 1 && $i < 4) {
                        $Plan += $valorprim;
                        break;
                    }

                    if ($Trimestre == 2 && $i > 3 && $i < 7) {
                        $Plan += $valorseug;
                        break;
                    }
                    if ($Trimestre == 3 && $i > 6 && $i < 10) {
                        $Plan += $valorter;
                        break;
                    }
                    if ($Trimestre == 4 && $i > 9) {
                        $Plan += $valorcuatro;
                        break;
                    }

                    $count++;
                }


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

                $MontoAnualActual = 0;
                for ($z = 0; $z < $Mes; $z++) {
                    $oactualcostoga = new Mactualcostoga();
                    $oactualcostoga->IdCostoGA = $element->IdCostoGA;
                    $oactualcostoga->Mes = $z + 1;
                    $oactualcostoga->IdSucursal = $IdSucursal;
                    $oactualcostoga->Anio = $Anio;
                    $recoveryoactualcostoga1 = $oactualcostoga->get_recobery_actualcostoga();
                    //print_r($recoveryoactualcostoga);
                    $MontoAnualActual += $recoveryoactualcostoga1['data']->MontoMes;
                    
                }

                //print_r($MontoAnualActual);
                $oactualcostoga = new Mactualcostoga();
                $oactualcostoga->IdCostoGA = $element->IdCostoGA;
                
                $oactualcostoga->Mes = $Mes;
                $oactualcostoga->IdSucursal = $IdSucursal;
                $oactualcostoga->Anio = $Anio;
                $recoveryoactualcostoga2 = $oactualcostoga->get_recobery_actualcostoga();

                // echo '<pre>';
                //print_r($recoveryoactualcostoga2['data']);
                // echo '</pre>';
                $valorN = $recoveryoactualcostoga2['data']->MontoMes;
                if (round($valorN) == 0) {
                    $montmes = '';
                } else {
                    $montmes = round($valorN);
                }
                if ($element->AnioAnterior != '') {
                    $TotalAnioPasadoGA += round($element->AnioAnterior);
                }
                // echo '<pre>';
                // print_r($montmes);
                // echo '</pre>';
                $TotalPlanAcomGA += round($PlanAnual);
                $TotalActualAcomGA += round($MontoAnualActual);
                $TotalPlanMesGA += round($Plan);
                $TotalActualMesGA += round($montmes);
            }
        }

        //FIN COSTOS GA
        $ogastosdirectos = new Mgastosdirectos();
        $ogastosdirectos->IdSucursal = $IdSucursal;
        $ogastosdirectos->Tipo = "1";
        $ogastosdirectos->Anio = $Anio;
        $row = $ogastosdirectos->get_list_gastosdirectos();

        $ogastosdirectos = new Mgastosdirectos();
        $ogastosdirectos->IdSucursal = $IdSucursal;
        $ogastosdirectos->Tipo = "2";
        $ogastosdirectos->Anio = $Anio;
        $rowind = $ogastosdirectos->get_list_gastosdirectos();


        $VentasPasado = 0;
        $VentasPlanAnioA = 0;
        $VentasActualAnioA = 0;
        $VentasPlanMes = 0;
        $VentasActualMes = 0;
        //directos 

        foreach ($row as $element) {
            //para el mes actual
            $Plan = 0;

            //para el a�o actual
            $PlanAnual = 0;
            $valorprim = $element->UnoT / 3;
            $valorseug = $element->DosT / 3;
            $valorter = $element->TresT / 3;
            $valorcuatro = $element->CuatroT / 3;
            $count = 1;

            for ($i = 1; $i <= 12; $i++) {

                if ($Trimestre == 1 && $i < 4) {

                    $Plan += $valorprim;

                    break;
                }

                if ($Trimestre == 2 && $i > 3 && $i < 7) {

                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }



                $count++;
            }
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



            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++) {
                $oactualventas = new Mactualventas();
                $oactualventas->IdGasto = $element->IdGasto;
                $oactualventas->Mes = $z + 1;
                $oactualventas->Anio = $Anio;
                $oactualventas->IdSucursal = $IdSucursal;
                $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
                $MontoAnualActual += $recoveryoactualventas['data']->MontoMes;
            }

            $oactualventas = new Mactualventas();
            $oactualventas->IdGasto = $element->IdGasto;
            $oactualventas->Mes = $Mes;
            $oactualventas->Anio = $Anio;
            $oactualventas->IdSucursal = $IdSucursal;
            $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
            $montval = round($recoveryoactualventas['data']->MontoMes);

            if (round($recoveryoactualventas['data']->MontoMes) == 0) {
                $montval = '';
            }

            if (round($element->MontoAnterior) != '' || round($element->MontoAnterior) != '0') {
                $VentasPasado += round($element->MontoAnterior);
            }

            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {

                $VentasPlanAnioA += round($PlanAnual);
                // echo $VentasPlanAnioA.'Directos1';

            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VentasActualAnioA += round($MontoAnualActual);
            }

            if (round($Plan) != '' || round($Plan) != '0') {
                $VentasPlanMes += round($Plan);
            }

            if (round($montval) != '' || round($montval) != '0') {
                $VentasActualMes += round($montval);
            }
        }

        //GASTOS INDIRECTOS ****

        foreach ($rowind as $element) {

            $Plan = 0;

            $PlanAnual = 0;
            $valorprim = $element->UnoT / 3;
            $valorseug = $element->DosT / 3;
            $valorter = $element->TresT / 3;
            $valorcuatro = $element->CuatroT / 3;


            $count = 1;

            for ($i = 1; $i <= 12; $i++) {

                if ($Trimestre == 1 && $i < 4) {

                    $Plan += $valorprim;

                    break;
                }

                if ($Trimestre == 2 && $i > 3 && $i < 7) {

                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }



                $count++;
            }

            $count = 1;
            $PlanAnual = 0;
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


            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++) {

                $oactualventas = new Mactualventas();
                $oactualventas->IdGasto = $element->IdGasto;
                $oactualventas->Mes = $z + 1;
                $oactualventas->Anio = $Anio;
                $oactualventas->IdSucursal = $IdSucursal;
                $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
                $MontoAnualActual += $recoveryoactualventas['data']->MontoMes;
            }

            $oactualventas = new Mactualventas();
            $oactualventas->IdGasto = $element->IdGasto;
            $oactualventas->Mes = $Mes;
            $oactualventas->Anio = $Anio;
            $oactualventas->IdSucursal = $IdSucursal;
            $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
            $montval = round($recoveryoactualventas['data']->MontoMes);

            if (round($recoveryoactualventas['data']->MontoMes) == 0) {
                $montval = '';
            }

            if (round($element->MontoAnterior) != '' || round($element->MontoAnterior) != '0') {
                $VentasPasado += round($element->MontoAnterior);
            }

            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $VentasPlanAnioA += round($PlanAnual);
                // echo $PlanAnual;
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VentasActualAnioA += round($MontoAnualActual);
            }

            if (round($Plan) != '' || round($Plan) != '0') {
                $VentasPlanMes += round($Plan);
            }

            if (round($montval) != '' || round($montval) != '0') {
                $VentasActualMes += round($montval);
            }
        }


        //****Costos


        $OpPasado = 0;
        $OpPlanAnio = 0;
        $OpActualAnio = 0;
        $OpPlanMes = 0;
        $OpActualMes = 0;

        //** COSTO DEPTO OPERACIONES

        $ocostodeptoventa = new Mcostodeptoventas();
        $ocostodeptoventa->Anio = $Anio;
        $ocostodeptoventa->IdSucursal = $IdSucursal;
        $rowcostodepto = $ocostodeptoventa->get_list_costodeptoventa();
        foreach ($rowcostodepto as $element) {
            $Plan = 0;

            //para el a?o actual
            $PlanAnual = 0;
            $valorprim = $element->PrimerT / 3;
            $valorseug = $element->SegundoT / 3;
            $valorter = $element->TercerT / 3;
            $valorcuatro = $element->CuartoT / 3;

            $count = 1;

            for ($i = 1; $i <= 12; $i++) {

                if ($Trimestre == 1 && $i < 4) {

                    $Plan += $valorprim;

                    break;
                }

                if ($Trimestre == 2 && $i > 3 && $i < 7) {

                    $Plan += $valorseug;

                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

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

            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++) {

                $oactualoperaciones = new Mactualoperaciones();
                $oactualoperaciones->IdCostoDeptoVenta = $element->IdCostoDeptoVenta;
                $oactualoperaciones->Mes = $z + 1;
                $oactualoperaciones->Anio = $Anio;
                $recoveryoactualoperaciones = $oactualoperaciones->get_recobery_actualoperaciones();
                $MontoAnualActual += $recoveryoactualoperaciones['data']->MontoMes;
            }

            $oactualoperaciones = new Mactualoperaciones();
            $oactualoperaciones->IdCostoDeptoVenta = $element->IdCostoDeptoVenta;
            $oactualoperaciones->Mes = $Mes;
            $oactualoperaciones->Anio = $Anio;
            $recoveryoactualoperaciones = $oactualoperaciones->get_recobery_actualoperaciones();

            $montac = round($recoveryoactualoperaciones['data']->MontoMes);
            if (round($recoveryoactualoperaciones['data']->MontoMes) == 0) {
                $montac = '';
            }


            if (round($element->AnioAnterior) != '' || round($element->AnioAnterior) != '0') {
                $OpPasado += round($element->AnioAnterior);
            }

            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $OpPlanAnio += round($PlanAnual);
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $OpActualAnio += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $OpPlanMes += round($Plan);
            }
            if (round($montac) != '' || round($montac) != '0') {
                $OpActualMes += round($montac);
            }
        }



        //**fIN COSTO OPERACIONES


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

        for ($i = 1; $i < 6; $i++) {

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

        //**
        //aqui recorremos los uodate
        if ($IdCliente == 0 && $IdClienteS == 0) {

            for ($i = 1; $i < 6; $i++) {
                //Este es nuevo EstadoUpdateClass
                $oestadofupdate = new Mestadofupdate();
                $oestadofupdate->IdSucursal = $IdSucursal;
                $oestadofupdate->Mes = intval($Mes);
                $oestadofupdate->Mes2 = intval($Mes);
                $oestadofupdate->Anio = $Anio;
                $oestadofupdate->IdConfigServ = $i;
                $rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

                

                foreach ($rowEstadoUpdate as $element) {
                    if ($element->Descripcion == 'Facturacion') {
                        //$TotalFact +=$element->Monto;
                        $MontoFactActual += $element->Monto;
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
            }

            //aqui recorremos los uodate de enero hasta el mes actual;


            for ($i = 1; $i < 6; $i++) {
                //Este es nuevo EstadoUpdateClass
                $oestadofupdate = new Mestadofupdate();
                $oestadofupdate->IdSucursal = $IdSucursal;
                $oestadofupdate->Mes = intval('01');
                $oestadofupdate->Mes2 = intval($Mes);
                $oestadofupdate->Anio = $Anio;
                $oestadofupdate->IdConfigServ = $i;
                $rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

                //print_r($rowEstadoUpdate);

                foreach ($rowEstadoUpdate as $element) {
                    if ($element->Descripcion == 'Facturacion') {
                        $TotalFact += $element->Monto;
                    }
                    if ($element->Descripcion == 'Burden') {

                        $BurdenTotalAnioActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Mano de Obra') {

                        $ManoObraTAnioActual += $element->Monto;
                    }

                    if ($element->Descripcion == 'Vehiculos') {

                        $CostoVAnioActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Equipos') {

                        $EquiposDAno += $element->Monto;
                    }
                    if ($element->Descripcion == 'Materiales') {

                        $MaterialesDAnio += $element->Monto;
                    }
                    if ($element->Descripcion == 'Viaticos') {

                        $ViaticosDAnio += $element->Monto;
                    }
                    if ($element->Descripcion == 'Contratistas') {
                        $ContratistasDAnio += $element->Monto;
                    }
                }

                //FIN ESTADO UPDATE CLASS
            }
        }

        //Varianza burden 

        $vb1 = 0;
        $vb2 = 0;
        $vb3 = 0;
        $vb4 = 0;
        $vb5 = 0;
        $vb6 = 0;
        $vb7 = 0;
        $vb8 = 0;
        $vb9 = 0;
        $vb10 = 0;


        //Varianza Vehiculo 

        $vv1 = 0;
        $vv2 = 0;
        $vv3 = 0;
        $vv4 = 0;
        $vv5 = 0;
        $vv6 = 0;
        $vv7 = 0;
        $vv8 = 0;
        $vv9 = 0;
        $vv10 = 0;

        $VpPasado = 0;
        $VpPlanAnio = 0;
        $VpActualAnio = 0;
        $VpPlanMes = 0;
        $VpActualMes = 0;
        //** COSTO DEPTO OPERACIONES

        $ocostovehope = new Mcostovehope();
        $ocostovehope->Anio = $Anio;
        $ocostovehope->IdSucursal = $IdSucursal;
        $rowcostove = $ocostovehope->get_list_costovehope();

        //print_r($rowcostove);
        foreach ($rowcostove as $element) {
            $Plan = 0;



            //para el a?o actual
            $PlanAnual = 0;
            $valorprim = $element->PrimerT / 3;
            $valorseug = $element->SegundoT / 3;
            $valorter = $element->TercerT / 3;
            $valorcuatro = $element->CuartoT / 3;

            $count = 1;

            for ($i = 1; $i <= 12; $i++) {

                if ($Trimestre == 1 && $i < 4) {

                    $Plan += $valorprim;

                    break;
                }

                if ($Trimestre == 2 && $i > 3 && $i < 7) {

                    $Plan += $valorseug;

                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

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

            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++) {

                $oactualcostove = new Mactualcostove();
                $oactualcostove->IdCostoVehOpe = $element->IdCostoVehOpe;
                $oactualcostove->Mes = $z + 1;
                $oactualcostove->Anio = $Anio;
                $oactualcostove->IdSucursal = $IdSucursal;
                $recoveryoactualcostove = $oactualcostove->get_recobery_actualcostove();
                
                $MontoAnualActual += $recoveryoactualcostove['data']->MontoMes;
                // echo '<pre>';
                // print_r($MontoAnualActual);
                // echo '</pre>';
            }

            $oactualcostove = new Mactualcostove();
            $oactualcostove->IdCostoVehOpe = $element->IdCostoVehOpe;
            $oactualcostove->Mes = $Mes;
            $oactualcostove->Anio = $Anio;
            $oactualcostove->IdSucursal = $IdSucursal;
            $recoveryoactualcostove = $oactualcostove->get_recobery_actualcostove();
            $montac = round($recoveryoactualcostove['data']->MontoMes);
            // echo '<pre>';
            // print_r($montac);
            // echo '</pre>';

            if (round($recoveryoactualcostove['data']->MontoMes) == 0) {
                $montac = '';
            }


            if (round($element->AnioAnterior) != '' || round($element->AnioAnterior) != '0') {
                $VpPasado += round($element->AnioAnterior);
            }

            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $VpPlanAnio += round($PlanAnual);
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VpActualAnio += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $VpPlanMes += round($Plan);
            }
            if (round($montac) != '' || round($montac) != '0') {
                $VpActualMes += round($montac);
            }
        }

        ///////////////////////////////////////



        //Pasados Acomulados


        //*********************** COSTOS FINANCIEROS ************************///
        $CostoFinAnioAnt = 0;
        $CostoFinTrim1 = 0;
        $CostoFinTrim2 = 0;
        $CostoFinTrim3 = 0;
        $CostoFinTrim4 = 0;

        $ocostofinanciero = new Mcostofinanciero();
        $ocostofinanciero->Anio = $AnioActual;
        $ocostofinanciero->IdSucursal = $IdSucursal;
        $ocostofinanciero->Tipo = 'TOTAL INTERESES Y GASTOS';
        $recoveryocostofinanciero = $ocostofinanciero->get_recobery_costofinanciero_plangral();

        if ($recoveryocostofinanciero['data']->AnioAnterior != '') {
            $CostoFinAnioAnt += $recoveryocostofinanciero['data']->AnioAnterior;
        }
        if ($recoveryocostofinanciero['data']->PrimerT != '') {

            $CostoFinTrim1 += $recoveryocostofinanciero['data']->PrimerT;
        }

        if ($recoveryocostofinanciero['data']->SegundoT != '') {

            $CostoFinTrim2 += $recoveryocostofinanciero['data']->SegundoT;
        }
        if ($recoveryocostofinanciero['data']->TercerT != '') {
            $CostoFinTrim3 += $recoveryocostofinanciero['data']->TercerT;
        }
        if ($recoveryocostofinanciero['data']->CuartoT != '') {
            $CostoFinTrim4 += $recoveryocostofinanciero['data']->CuartoT;
        }



        $ocostofinanciero = new Mcostofinanciero();
        $ocostofinanciero->Anio = $AnioActual;
        $ocostofinanciero->IdSucursal = $IdSucursal;
        $ocostofinanciero->Tipo = 'TOTAL OTROS INGRESOS/GASTOS';
        $recoveryocostofinanciero = $ocostofinanciero->get_recobery_costofinanciero_plangral();

        if ($recoveryocostofinanciero['data']->AnioAnterior != '') {

            $CostoFinAnioAnt -= $recoveryocostofinanciero['data']->AnioAnterior;
        }
        if ($recoveryocostofinanciero['data']->PrimerT != '') {
            $CostoFinTrim1 -= $recoveryocostofinanciero['data']->PrimerT;
        }

        if ($recoveryocostofinanciero['data']->SegundoT != '') {
            $CostoFinTrim2 -= $recoveryocostofinanciero['data']->SegundoT;
        }
        if ($recoveryocostofinanciero['data']->TercerT != '') {
            $CostoFinTrim3 -= $recoveryocostofinanciero['data']->TercerT;
        }
        if ($recoveryocostofinanciero['data']->CuartoT != '') {
            $CostoFinTrim4 -= $recoveryocostofinanciero['data']->CuartoT;
        }






        $valorprimcf = $CostoFinTrim1 / 3;
        $valorseugcf = $CostoFinTrim2 / 3;
        $valortercf = $CostoFinTrim3 / 3;
        $valorcuatrocf = $CostoFinTrim4 / 3;


        $AnioPasadoCF = $CostoFinAnioAnt;
        $PlanAnioCF = 0;
        // $ActualAnioCF=0;
        $PlanMesCF = $CostoFinTrim1 + $CostoFinTrim2 + $CostoFinTrim3 + $CostoFinTrim4;
        //$ActualMesCF=0;



        $count = 1;
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 4) {
                $PlanAnioCF += $valorprimcf;
                $PlanMesCF = $valorprimcf;
            } else if ($i < 7) {
                $PlanAnioCF += $valorseugcf;
                $PlanMesCF = $valorseugcf;
            } else if ($i < 10) {
                $PlanAnioCF += $valortercf;
                $PlanMesCF = $valortercf;
            } else {
                $PlanAnioCF += $valorcuatrocf;
                $PlanMesCF = $valorcuatrocf;
            }

            if ($count == $Mes) {
                break;
            }
            $count++;
        }
        //********Costos financieros actual
        $MontoMesCF = 0;
        $MontoAnioCF = 0;
        $oactualcostof = new Mactualcostof();
        $oactualcostof->IdSucursal = $IdSucursal;
        $oactualcostof->Anio = $AnioActual;
        $oactualcostof->Mes = $Mes;
        $oactualcostof->Tipo = 1;
        $oactualcostof->Type = 'TOTAL INTERESES Y GASTOS';
        $recoveryoactualcostof = $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoMesCF = $recoveryoactualcostof['data']->MontoMes;
        }
        $oactualcostof->Tipo = 1;
        $oactualcostof->Type = 'TOTAL OTROS INGRESOS/GASTOS';
        $recoveryoactualcostof = $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoMesCF -= $recoveryoactualcostof['data']->MontoMes;
        }

        $oactualcostof = new Mactualcostof();
        $oactualcostof->IdSucursal = $IdSucursal;
        $oactualcostof->Anio = $AnioActual;
        $oactualcostof->Mes = $Mes;
        $oactualcostof->Tipo = 2;
        $oactualcostof->Type = 'TOTAL INTERESES Y GASTOS';
        $recoveryoactualcostof =  $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {

            $MontoAnioCF = $recoveryoactualcostof['data']->MontoMes;
        }

        $oactualcostof->Type = 'TOTAL OTROS INGRESOS/GASTOS';
        $oactualcostof->Tipo = 2;
        $recoveryoactualcostof =$oactualcostof->get_recobery_actualcostofrptgral2();

        //print_r($recoveryoactualcostof);

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoAnioCF -= $recoveryoactualcostof['data']->MontoMes;
        }

        if(count($rowPlanFactura))
        {
        
            $con=0;
            
            $countfac=0;
            $PlanAnualGral=1;
            $PlanMesGral=1;
            $PorcenAnio=0;
            $PorcenMes=0;
            
            foreach($rowPlanFactura as $element)
            {    
                $PrimerTT=0;
                $SegundoTT=0;
                $TercerTT=0;
                $CuartoTT=0;
                $AnoAnteriorMont=0;
        
        
                $oporcentajeoperacion=new Mporcentajeoperacion();
                $oporcentajeoperacion->IdSucursal=$IdSucursal;
                //echo $element->Descripcion;
                $oporcentajeoperacion->Descripcion=$element->Descripcion;
                $oporcentajeoperacion->Anio=$Anio;
                $oporcentajeoperacion->IdSubtipoServ=0;
                $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinanciero2();
                
                $PrimerTT =$recoveryoporcentajeoperacion['data']->PrimerT;
                $SegundoTT =$recoveryoporcentajeoperacion['data']->SegundoT;
                $TercerTT =$recoveryoporcentajeoperacion['data']->TercerT;
                $CuartoTT =$recoveryoporcentajeoperacion['data']->CuartoT;
                //print_r($ejemplo1);
                $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;
            
                //aqui abajo se divide entre tres por que esta por trimestre
                //Plan Mensual actual
                $Plan=0;
                
                if($Trimestre==1)
                {
                    $Plan=$PrimerTT/3;
                }
                if($Trimestre==2){
                    $Plan=$SegundoTT/3;
                }
                if ($Trimestre==3){
                    $Plan=$TercerTT/3;   
                }
                if($Trimestre==4){
                    $Plan=$CuartoTT/3;
                }
                    
                //para el a�o actual Plan
                $PlanAnual=0;
                $valorprim= $PrimerTT/3;
                $valorseug= $SegundoTT/3;
                $valorter= $TercerTT/3;
                $valorcuatro=$CuartoTT/3;
                $count=1;
                    
                for ($i=1; $i <=12 ;$i++ )
                {
                    if ($i <4){
                        $PlanAnual +=$valorprim;
                    }  
                    else if($i <7){
                        $PlanAnual +=$valorseug;     
                    }
                    else if($i <10 ){
                        $PlanAnual +=$valorter; 
                    }
                    else
                    {
                        $PlanAnual +=$valorcuatro;
                    }
                    
                    if($count==$Mes){
                        break;
                    }
                    $count++;
                }

                //Dependiendo de el nombre se pinta colocar en la columna  para mano de obra costo vehiculo y burden total
                $ColocarPorcenMes=0;
                $Colocar=0;
                $ColocarPasado=0;
                $ColocarAnioActual=0;
                $readonlyactual='';
                $clasmonto='features-input-dis ';
                $readonlmonto ='readonly="true"';
                $AnoAnteriorPorcent=0;
            
                if($countfac==0)
                {
                    //colocamos la facturacion almacenada en la base de datos
                    $Colocar = $MontoFactActual;
                    //print_r($MontoFactActual);
                    $ColocarAnioActual=$TotalFact;
                    //$readonlmonto='';
                    $clasmonto='features-input-dis';
                    $ColocarPasado= $FactPasAcom;
                    
                    if ($PlanAnual==0){
                    }
                    else{
                        $PlanAnualGral=$PlanAnual;
                    }

                    if($Plan==0)
                    {
                    }
                    else
                    {
                        $PlanMesGral=$Plan;
                    }

                    $PorcenAnio=100;
                    $PorcenMes=100;
                    //$oporcentajeoperacion->get_recobery_porcentajeoperacion();
                    //$AnoAnteriorMont = 16666;
                }

                $countfac ++;
                if($element->Descripcion=='Mano de Obra')
                {
                    $Colocar = $ManoObraT;//paa el mes actual
                    $ColocarAnioActual=$ManoObraTAnioActual;//para el mes 1 al mes actual
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $ManoOPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                    
                    //$oporcentajeoperacion->get_recobery_porcentajeoperacion(); 
                    //Varianza burden  
                }

                if($element->Descripcion==utf8_encode('Vehiculos'))
                {
                    $Colocar =$CostoV;
                    $ColocarAnioActual=$CostoVAnioActual;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $VehiculoPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
    
                    $vv1=$VpPasado-$AnoAnteriorMont;
                    $vv2=0;
                    $vv3=$VpPlanAnio-$PlanAnual;
                    $vv4=0;
                    $vv5=$VpActualAnio-$ColocarAnioActual;
                    $vv6=0;
                    $vv7=$VpPlanMes-$Plan;
                    $vv8=0;
                    $vv9=$VpActualMes- $Colocar;
                    $vv10=0;
                }

                if($element->Descripcion=='Burden')
                {
                    $Colocar =$BurdenTotal;
                    $ColocarAnioActual=$BurdenTotalAnioActual;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $BurdenPasAcom ;

                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                
                    //Varianza burden 
                    $vb1=$OpPasado-$AnoAnteriorMont;
                    $vb2=0;
                    $vb3=$OpPlanAnio-$PlanAnual;
                    $vb4=0;
                    $vb5=$OpActualAnio-$ColocarAnioActual;
                    $vb6=0;
                    $vb7=$OpPlanMes-$Plan;
                    $vb8=0;
                    $vb9=$OpActualMes- $Colocar;
                    $vb10=0;
                }
                
                if($element->Descripcion=='Materiales')
                {
                    $Colocar =$MaterialesD;
                    $ColocarAnioActual=$MaterialesDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $MaterialPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;   
                }
                
                if($element->Descripcion=='Equipos')
                {
                    $Colocar =$EquiposD;
                    $ColocarAnioActual=$EquiposDAno;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $EquiposPasAcom;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                
                if($element->Descripcion=='Contratistas')
                {  
                    $Colocar =$ContratistasD;
                    $ColocarAnioActual=$ContratistasDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $ContratistaPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }

                if($element->Descripcion==utf8_encode('Viaticos'))
                { 
                    $Colocar =$ViaticosD;
                    $ColocarAnioActual=$ViaticosDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $ViaticosPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                //fin  
                //PASADO BASE DE DATOS DETALLEESTADO FINANCIERO 
                
                // $obje = array(

                // 'MontoAnioAnterior' => $AnoAnteriorMont
                // // 'PorcentajeAnioAnterior' => 0,
                // // 'PlanAnual' => $PlanAnual,
                // // 'PorcenAnio' => $PorcenAnio,
                // // 'MontoAnioAnterior' => $AnoAnteriorMont,
                // // 'MontoAnioAnterior' => $AnoAnteriorMont,

                // );

                // array_push($arraydatos,$obje);

                    //PorcentajeAnioPlan
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



            $arraydatos2 = array(
                'TotalCostoOper' => '',
                'CostoOperacionAnterior' => number_format(0,2,'.',''),
                'PorcentajeOperacionAnterior' => 0,
                'sumprimer' => number_format(0,2,'.',''),
                'SumSeg' => number_format(0,2,'.',''),
                'SumTer' => number_format(0,2,'.',''),
                'SumCua' => number_format(0,2,'.',''),
                'SumTotal' => number_format(0,2,'.',''),
                'PorcentajeOperacionAnual' => 0,
                'SumTotalMes' => number_format(0,2,'.',''),
                'PorcentajeOperacionMes' => 0,
                'GROSSPROFIT' => '',
                'CostoGrossProfittAnterior' => 0,
                'PorcentajeGrossProfittAnterior' => 0,
                'GrosPPlanAnio' => 0,
                'GrosPPlanAnioPorcen' => 0,
                'GrosPActualAnio' => 0,
                'GrosPActualAnioPorcen' => 0,
                'GrosprofitPlanMes' => 0,
                'GrosprofitFacturacionPlanMes' => 0,
                'GrosprofitActualMes' => 0,
                'GrosprofitPorcentajeAnualMes' => 0,
                /*COSTOS G & A */
                'CostosG&A' => '',
                'ga1' => $TotalAnioPasadoGA,
                'ga2' => 0,
                'ga3' => $TotalPlanAcomGA,
                'ga4' => 0,
                'ga5' => $TotalActualAcomGA,
                'ga6' => 0,
                'ga7' => $TotalPlanMesGA,
                'ga8' => 0,
                'ga9' => $TotalActualMesGA,
                'ga10' => 0,

                /* COSTOS Depto ventas directas e indirectas*/
                'CostosDeptoVenta' => 0,
                'cv1' => $VentasPasado,
                'cv2' => 0,
                'cv3' => $VentasPlanAnioA,
                'cv4' => 0,
                'cv5' => $VentasActualAnioA,
                'cv6' => 0,
                'cv7' => $VentasPlanMes,
                'cv8' => 0,
                'cv9' => $VentasActualMes,
                'cv10' => 0,

                /*COSTOS Varianza burden */
                'VarianzaBurden' => '',
                'vb1' => number_format($vb1,0,'.',''),
                'vb2' => number_format($vb2,0,'.',''),
                'vb3' => number_format($vb3,0,'.',''),
                'vb4' => 0,
                'vb5' => number_format($vb5,0,'.',''),
                'vb6' => 0,
                'vb7' => number_format($vb7,0,'.',''),
                'vb8' => number_format($vb8,0,'.',''),
                'vb9' => number_format($vb9,0,'.',''),
                'vb10' => number_format($vb10,0,'.',''),

                /*COSTOS Varianza Vehiculo */
                'VarianzaVehiculo' => '',
                'vv1' => number_format($vv1,0,'.',''),
                'vv2' => number_format($vv2,0,'.',''),
                'vv3' => number_format($vv3,0,'.',''),
                'vv4' => 0,
                'vv5' => number_format($vv5,0,'.',''),
                'vv6' => 0,
                'vv7' => number_format($vv7,0,'.',''),
                'vv8' => number_format($vv8,0,'.',''),
                'vv9' => number_format($vv9,0,'.',''),
                'vv10' => number_format($vv10,0,'.',''),

                /* Costo financiero*/
                'IngresosyEgresos' => '',
                'ie1' => number_format($AnioPasadoCF,0,'.',''),
                'ie2' => number_format(0,0,'.',''),
                'ie3' => number_format($PlanAnioCF,0,'.',''),
                'ie4' => 0,
                'ie5' => number_format($MontoAnioCF,0,'.',''),
                'ie6' => 0,
                'ie7' => number_format($PlanMesCF,0,'.',''),
                'ie8' => number_format(0,0,'.',''),
                'ie9' => number_format($MontoMesCF,0,'.',''),
                'ie10' => number_format(0,0,'.',''),

                /* Net Profit */

                'NETPROFIT' => '',
                'np1' =>  0,
                'np2' =>  0,
                'np3' =>  0,
                'np4' =>  0,
                'np5' =>  0,
                'np6' =>  0,
                'np7' =>  0,
                'np8' =>  0,
                'np9' =>  0,
                'np10' =>  0,
            );
        }
        

        

        $value = $this->Calculos_EstaosF($arraydatos,$arraydatos2);


        $data['row'] =  $value['row'];
        $data['rowconfig'] =  $value['config'];
        // echo '<pre>';
        // print_r($value);
        // echo '</pre>';
        
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    function Calculos_EstaosF($arraydatos,$arraydatos2) {

        //var ActualMes = document.getElementsByName('ActualMes[]');
        //var PorcentajeMesACtual = document.getElementsByName('PorcentajeMesACtual[]');
        //var Anterios = document.getElementsByName('Anterios[]');
        //var PorcentajeAnterior = document.getElementsByName('PorcentajeAnterior[]');
        //var PlaAnio = document.getElementsByName('PlaAnio[]');
        //var PorcentajeAnioPlan = document.getElementsByName('PorcentajeAnioPlan[]');
        //var ActualAnio = document.getElementsByName('ActualAnio[]');
        //var PorcentajeAnio = document.getElementsByName('PorcentajeAnio[]');
        //var PlanMesActual = document.getElementsByName('PlanMesActual[]');
        //var PorcentajePlanMesActual = document.getElementsByName('PorcentajePlanMesActual[]');
    
        //var IdTipoSer = document.getElementById('IdTipoSer').value;
    
    
        $TotalFacturacionActual = $arraydatos[0]['ActualMes'];//$arraydatos[0]['ActualMes']; // $actualMesn;
        #Plan del mes de factura
        $TotalFacturacionActual2 = $arraydatos[0]['ActualMes'];//$arraydatos[0]['ActualMes']; // $actualMesn;
        $TotalAnteriosActual = $arraydatos[0]['AnoAnteriorMont'];//$arraydatos[0]['AnoAnteriorMont']; //Anterios[0].value;

        $actualAnion = $arraydatos[0]['ColocarAnioActual'];//$arraydatos[0]['ColocarAnioActual'];
        $planAnion = $arraydatos[0]['PlanAnual'];//$arraydatos[0]['PlanAnual'];
        $actualMesn = $arraydatos[0]['ActualMes'];//$arraydatos[0]['ActualMes'];

        
    
        //si es 0 que se divida entre 1
        if ($TotalFacturacionActual == '0' || $TotalFacturacionActual == '') {
            $TotalFacturacionActual = 1;
        }
    
        if ($TotalAnteriosActual == '0' || $TotalAnteriosActual == '') {
            $TotalAnteriosActual = '';
        }
    
        $anterior = 0;
        $porcentajeant = 0;
        $PlaAnioTot = 0;
        $PorcentajeAnioPlanTot = 0;
        $ActualAnioTot = 0;
        $PorcentajeAnioTot = 0;
        $PlanMesActualTot = 0;
        $PorcentajePlanMesActualTot = 0;
        $ActualMesTot = 0;
        $PorcentajeMesACtualTot = 0;
    
    
        $arraydatos[0]['PorcentajeAnterior'] = 100; //;PorcentajeAnterior[0].value = 100;
        $arraydatos[0]['PorcenAnio'] = 100; //PorcentajeAnioPlan[0].value = 100;
        $arraydatos[0]['PorcentajeAnio'] = 100; //PorcentajeAnio[0].value = 100;
    
        $VarloFactPlan = $arraydatos[0]['PlanAnual'];//$arraydatos[0]['PlanAnual'];//$planAnion;
        $arraydatos[0]['PorcentajeMesACtual'] = 100; //PorcentajeMesACtual[0].value = 100;
        
        //print_r($arraydatos);
    
        //****calculos***
        $contador = 0;
        foreach($arraydatos as $element){

            if($contador > 0){


                $element['PorcentajeMesACtual'] = (($element['ActualMes'] * 100) / $TotalFacturacionActual);

                
                //POCENTAJE DEL AÑO PASADO
                if(is_numeric(($element['AnoAnteriorMont'] * 100)/$TotalAnteriosActual) == false)
                {
                    $element['PorcentajeAnterior'] = 0;
                }else{
                    $element['PorcentajeAnterior'] = (($element['AnoAnteriorMont'] * 100) / $TotalAnteriosActual);
                }

                $element['PorcentajeAnterior'] = number_format($element['PorcentajeAnterior'],1,'.','');


                //PORCENTAJE DEL AÑO ACTUAL
                $element['PorcentajeAnio'] = (($element['ColocarAnioActual'] * 100) / $actualAnion);
                
                if(is_numeric(($element['ColocarAnioActual'] * 100)/$actualAnion) == false)
                {
                    $element['PorcentajeAnio'] = 0;
                }

                $element['PorcentajeAnio'] = number_format($element['PorcentajeAnio'],1,'.','');


                
                //PORCENTAJE ACTUAL
                $ValorPorcenActual = (($element['PlanAnual'] * 100) / $VarloFactPlan);
        
                if (is_numeric($ValorPorcenActual) == false) {
                    $element['PorcentajeAnioPlan'] = 0;
                } else {
                    $element['PorcentajeAnioPlan'] = $ValorPorcenActual;
                }

                // echo '<pre>';
                // print_r($element['PlanAnual']);
                // echo '-';
                // print_r($VarloFactPlan);
                // echo '-';
                // print_r($element['PorcentajeAnioPlan']);
                // echo '__';
                // echo '</pre>';

            
                $anterior += $element['AnoAnteriorMont'];//number_format($element['AnoAnteriorMont'], 2, '.', '');
                $porcentajeant += number_format($element['PorcentajeAnterior'], 0, '.', '');
                $PlaAnioTot += number_format($element['PlanAnual'], 1, '.', '');
                $PorcentajeAnioPlanTot += number_format($element['PorcentajeAnioPlan'], 0, '.', '');
                $ActualAnioTot += number_format($element['ColocarAnioActual'], 0, '.', '');
                $PorcentajeAnioTot += number_format($element['PorcentajeAnio'], 1, '.', '');
                $PlanMesActualTot += number_format($element['PlanMesActual'], 0, '.', '');
                
                $PorcentajePlanMesActualTot += number_format($element['PorcentajePlanMesActual'], 0, '.', '');
                $ActualMesTot += number_format($element['ActualMes'], 0, '.', '');
                $PorcentajeMesACtualTot += number_format($element['PorcentajeMesACtual'], 0, '.', '');


                $arraydatos[$contador]['PorcentajeAnterior'] = $element['PorcentajeAnterior'];
                $arraydatos[$contador]['PorcentajeAnio'] = $element['PorcentajeAnio'];
                $arraydatos[$contador]['PorcentajeAnioPlan'] = $element['PorcentajeAnioPlan'];
                
                if($TotalFacturacionActual2 > 0){
                    $arraydatos[$contador]['PorcentajeMesACtual'] = $element['PorcentajeMesACtual'];
                }else{
                    $arraydatos[$contador]['PorcentajeMesACtual'] = 0;
                }
                
            }

            
           

            $contador ++;

        }

        //print_r($arraydatos);


        $CostoGrossProfittAnterior = number_format($TotalAnteriosActual, 0, '.', '') - number_format($anterior, 0, '.', '');
        $PorcentajeGrossProfittAnterior = 100 - number_format($porcentajeant, 0, '.', '');
        
        
        if (is_numeric($anterior) == false || $anterior == 0) {
           // echo 'Pasa1';
            $arraydatos2['CostoOperacionAnterior'] = 0;
        } else {
           // echo 'Pasa2';
            $arraydatos2['CostoOperacionAnterior'] = $anterior;
        }
      
    
        if (is_numeric($CostoGrossProfittAnterior) == false || $CostoGrossProfittAnterior == 0) {
           // echo 'Pasa3';
            $arraydatos2['CostoOperacionAnterior'] = 0;
        } else {
            $arraydatos2['CostoGrossProfittAnterior'] = $CostoGrossProfittAnterior;
        }
    
        //echo $anterior.'---';
        //echo $arraydatos2['CostoOperacionAnterior'];
    
        $arraydatos2['PorcentajeOperacionAnterior'] = $porcentajeant;
    
    
        $PorcentajeGrossProfittAnterior = $PorcentajeGrossProfittAnterior;
        if ($PorcentajeGrossProfittAnterior >= 100) {
            $PorcentajeGrossProfittAnterior = 100;
        }
    
        $arraydatos2['PorcentajeGrossProfittAnterior'] = $PorcentajeGrossProfittAnterior;
    
        $arraydatos2['sumprimer'] = $PlaAnioTot;
        if (is_numeric($PlaAnioTot) == false) {
            $arraydatos2['sumprimer'] = 0;
        }
    
        $arraydatos2['SumSeg'] = $PorcentajeAnioPlanTot;
        $arraydatos2['SumTer'] = $ActualAnioTot;
        $arraydatos2['SumCua'] = $PorcentajeAnioTot;
        $arraydatos2['SumTotal'] = $PlanMesActualTot;
    
        $PorcentajeOperacionAnual = $PorcentajePlanMesActualTot;
    
        if ($PorcentajeOperacionAnual >= 100) {
            $PorcentajeOperacionAnual = 100;
        }
        $arraydatos2['PorcentajeOperacionAnual'] = $PorcentajeOperacionAnual;
    
        $arraydatos2['SumTotalMes'] = $ActualMesTot;
    
        $PorcentajeOperacionMes = $PorcentajeMesACtualTot;
        if ($PorcentajeOperacionMes >= 100) {
            $PorcentajeOperacionMes = 100;
        }
        $arraydatos2['PorcentajeOperacionMes'] = $PorcentajeOperacionMes;
        //sacar los gros profit anio actual y mes
        $arraydatos2['GrosPPlanAnio'] = number_format($planAnion, 0, '.', '') - number_format($PlaAnioTot, 0, '.', '');
    
        $GrosPPlanAnioPorcen = (100 - $PorcentajeAnioPlanTot);
        if ($GrosPPlanAnioPorcen >= 100) {
            $GrosPPlanAnioPorcen = 100;
        }
    
    
    
        $arraydatos2['GrosPPlanAnioPorcen'] = $GrosPPlanAnioPorcen;
    
        $opGrosPActualAnio = number_format($actualAnion, 0, '.', '') - number_format($ActualAnioTot, 0, '.', '');
        if (is_numeric($opGrosPActualAnio) == false) {
            $opGrosPActualAnio = 0;
        }
        $arraydatos2['GrosPActualAnio'] = $opGrosPActualAnio;
    
        $GrosPActualAnioPorcen = (100 - $PorcentajeAnioTot);
    
        if ($GrosPActualAnioPorcen < 0) {
            $GrosPActualAnioPorcen = 0;
        }
        $arraydatos2['GrosPActualAnioPorcen'] = $GrosPActualAnioPorcen;
        $arraydatos2['GrosprofitPlanMes'] = number_format($arraydatos[0]['PlanMesActual'], 0, '.', '') - $PlanMesActualTot;
    
    
        $arraydatos2['GrosprofitFacturacionPlanMes'] = (100 - $PorcentajePlanMesActualTot);
        $GrosprofitActualMes = number_format($actualMesn, 0, '.', '') - $ActualMesTot;
        $arraydatos2['GrosprofitActualMes'] = $GrosprofitActualMes;
        $valorgam = number_format($actualMesn, 0, '.', '') - $ActualMesTot;
        if (is_numeric($valorgam) == false) {
            $arraydatos2['GrosprofitActualMes'] = 0;
        }
    
        $GrossPPAM = (100 - $PorcentajeMesACtualTot);
        if ($GrossPPAM >= 100) {
            $GrossPPAM = 100;
        }
    
    
        if ($GrossPPAM < 0) {
            $arraydatos2['GrosprofitPorcentajeAnualMes'] = 0;
        } else {
            $arraydatos2['GrosprofitPorcentajeAnualMes'] = $GrossPPAM;
        }


        #Calcular procentaje de G&A

        $arraydatos2['ga2'] = (($arraydatos2['ga1']*100)/$arraydatos[0]['AnoAnteriorMont']);
        $arraydatos2['ga4'] = (($arraydatos2['ga3']*100)/$arraydatos[0]['PlanAnual']);
        $arraydatos2['ga6'] = (($arraydatos2['ga5']*100)/$arraydatos[0]['ColocarAnioActual']);
        $arraydatos2['ga8'] = (($arraydatos2['ga7']*100)/$arraydatos[0]['PlanMesActual']);

        #Calcular procentaje de Costos Depto. Vent
        $arraydatos2['cv2'] = (($arraydatos2['cv1']*100)/$arraydatos[0]['AnoAnteriorMont']);
        $arraydatos2['cv4'] = (($arraydatos2['cv3']*100)/$arraydatos[0]['PlanAnual']);
        $arraydatos2['cv6'] = (($arraydatos2['cv5']*100)/$arraydatos[0]['ColocarAnioActual']);
        $arraydatos2['cv8'] = (($arraydatos2['cv7']*100)/$arraydatos[0]['PlanMesActual']);
        //$arraydatos2['cv10'] = (($arraydatos2['cv9']*100)/$arraydatos[0]['ActualMes']);

        #Calcular procentaje de Costos Depto. Vent
        $arraydatos2['ie2'] = (($arraydatos2['ie1']*100)/$arraydatos[0]['AnoAnteriorMont']);
        $arraydatos2['ie4'] = (($arraydatos2['ie3']*100)/$arraydatos[0]['PlanAnual']);
        $arraydatos2['ie6'] = (($arraydatos2['ie5']*100)/$arraydatos[0]['ColocarAnioActual']);
        $arraydatos2['ie8'] = (($arraydatos2['ie7']*100)/$arraydatos[0]['PlanMesActual']);
        //$arraydatos2['ie10'] = (($arraydatos2['ie9']*100)/$arraydatos[0]['ActualMes']);

        #Calcular procentaje de Costos Depto. Vent
        $arraydatos2['vb2'] = (($arraydatos2['vb1']*100)/$arraydatos[0]['AnoAnteriorMont']);
        $arraydatos2['vb4'] = (($arraydatos2['vb3']*100)/$arraydatos[0]['PlanAnual']);
        $arraydatos2['vb6'] = (($arraydatos2['vb5']*100)/$arraydatos[0]['ColocarAnioActual']);
        $arraydatos2['vb8'] = (($arraydatos2['vb7']*100)/$arraydatos[0]['PlanMesActual']);
        
        
        #Calcular procentaje de Costos Depto. Vent
        $arraydatos2['vv2'] = (($arraydatos2['vv1']*100)/$arraydatos[0]['AnoAnteriorMont']);
        $arraydatos2['vv4'] = (($arraydatos2['vv3']*100)/$arraydatos[0]['PlanAnual']);
        $arraydatos2['vv6'] = (($arraydatos2['vv5']*100)/$arraydatos[0]['ColocarAnioActual']);
        $arraydatos2['vv8'] = (($arraydatos2['vv7']*100)/$arraydatos[0]['PlanMesActual']);


        if($arraydatos[0]['ActualMes'] > 0)
        {
            $arraydatos2['ga10'] =   (($arraydatos2['ga9']*100)/$arraydatos[0]['ActualMes']);
            $arraydatos2['cv10'] =   (($arraydatos2['cv9']*100)/$arraydatos[0]['ActualMes']);
            $arraydatos2['ie10'] =   (($arraydatos2['ie9']*100)/$arraydatos[0]['ActualMes']);
            $arraydatos2['vb10'] =   (($arraydatos2['vb7']*100)/$arraydatos[0]['ActualMes']);
            $arraydatos2['vv10'] =   (($arraydatos2['vv7']*100)/$arraydatos[0]['ActualMes']);
        }else{
            $arraydatos2['ga10'] =  0;
            $arraydatos2['cv10'] =  0;
            $arraydatos2['ie10'] =  0;
            $arraydatos2['vb10'] =  0;
            $arraydatos2['vv10'] =  0;
        }

        

        // Costos  G&A

        // ga1

        // Departamento de ventas

        // cv1
        // Varianza Burden

        // vb1
        // Varianza Vehiculo

        // vv1
        // Net Profit

        // np1

        

        



        // var u = (parseFloat(np1t[i].value) * 100) / parseFloat(unot[0].value);//AnoAnteriorMont
        // var d = (parseFloat(np3t[i].value) * 100) / parseFloat(trest[0].value);//PlanAnual
        // var t = (parseFloat(np5t[i].value) * 100) / parseFloat(cincot[0].value);//ColocarAnioActual
        // var c = (parseFloat(np7t[i].value) * 100) / parseFloat(sietet[0].value);//PlanMesActual
        // var cc = (parseFloat(np9t[i].value) * 100) / parseFloat(nuevet[0].value);//ActualMes



    
        $value = $this->NetProfit($arraydatos,$arraydatos2);

        $dataCalculos['row'] = $arraydatos;
        $dataCalculos['config'] = $value;

        return $dataCalculos;
    
    
    }

    public function NetProfit($arraydatos,$arraydatos2){

        $gp1 = $arraydatos2['CostoGrossProfittAnterior'];
        $gp2 = $arraydatos2['PorcentajeGrossProfittAnterior'];
        $gp3 = $arraydatos2['GrosPPlanAnio'];
        $gp4 = $arraydatos2['GrosPPlanAnioPorcen'];
        $gp5 = $arraydatos2['GrosPActualAnio'];
        $gp6 = $arraydatos2['GrosPActualAnioPorcen'];
        $gp7 = $arraydatos2['GrosprofitPlanMes'];
        $gp8 = $arraydatos2['GrosprofitFacturacionPlanMes'];
        $gp9 = $arraydatos2['GrosprofitActualMes'];
        $gp10 = $arraydatos2['GrosprofitPorcentajeAnualMes'];

        //costos ga
        $ga1 = $arraydatos2['ga1'];
        $ga2 = $arraydatos2['ga2'];
        $ga3 = $arraydatos2['ga3'];
        $ga4 = $arraydatos2['ga4'];
        $ga5 = $arraydatos2['ga5'];
        $ga6 = $arraydatos2['ga6'];
        $ga7 = $arraydatos2['ga7'];
        $ga8 = $arraydatos2['ga8'];
        $ga9 = $arraydatos2['ga9'];
        $ga10 = $arraydatos2['ga10'];
        //costos  ventas
        $cv1 = $arraydatos2['cv1'];
        $cv2 = $arraydatos2['cv2'];
        $cv3 = $arraydatos2['cv3'];
        $cv4 = $arraydatos2['cv4'];
        $cv5 = $arraydatos2['cv5'];
        $cv6 = $arraydatos2['cv6'];
        $cv7 = $arraydatos2['cv7'];
        $cv8 = $arraydatos2['cv8'];
        $cv9 = $arraydatos2['cv9'];
        $cv10 = $arraydatos2['cv10'];
        //Varianza burden

        $co1 = $arraydatos2['vb1'];
        $co2 = $arraydatos2['vb2'];
        $co3 = $arraydatos2['vb3'];
        $co4 = $arraydatos2['vb4'];
        $co5 = $arraydatos2['vb5'];
        $co6 = $arraydatos2['vb6'];
        $co7 = $arraydatos2['vb7'];
        $co8 = $arraydatos2['vb8'];
        $co9 = $arraydatos2['vb9'];
        $co10 = $arraydatos2['vb10'];

        //Varianza Vehiculo

        $vv1 = $arraydatos2['vv1'];
        $vv2 = $arraydatos2['vv2'];
        $vv3 = $arraydatos2['vv3'];
        $vv4 = $arraydatos2['vv4'];
        $vv5 = $arraydatos2['vv5'];
        $vv6 = $arraydatos2['vv6'];
        $vv7 = $arraydatos2['vv7'];
        $vv8 = $arraydatos2['vv8'];
        $vv9 = $arraydatos2['vv9'];
        $vv10 = $arraydatos2['vv10'];

        //Ingresos y egresos
        $vv1 = $arraydatos2['vv1'];
        $vv2 = $arraydatos2['vv2'];
        $vv3 = $arraydatos2['vv3'];
        $vv4 = $arraydatos2['vv4'];
        $vv5 = $arraydatos2['vv5'];
        $vv6 = $arraydatos2['vv6'];
        $vv7 = $arraydatos2['vv7'];
        $vv8 = $arraydatos2['vv8'];
        $vv9 = $arraydatos2['vv9'];
        $vv10 = $arraydatos2['vv10'];

        $ie1 = $arraydatos2['ie1'];
        $ie3 = $arraydatos2['ie3'];
        $ie5 = $arraydatos2['ie5'];
        $ie7 = $arraydatos2['ie7'];
        $ie9 = $arraydatos2['ie9'];

        // echo ($gp1 . " -" . $ga1 . " -" . $cv1 . "-" . $co1 . " -" . $vv1 . " -" . $ie1);
        // echo '////////';
        // echo ($gp3 . " -" . $ga3 . " -" . $cv3 . "-" . $co3 . " -" . $vv3 . " -" . $ie3);
        // echo '////////';
        // echo ($gp7 . " -" . $ga7 . " -" . $cv7 . "-" . $co7 . " -" . $vv7 . " -" . $ie7);
        // echo '////////';
        // echo ($gp5 . " -" . $ga5 . " -" . $cv5 . "-" . $co5 . " -" . $vv5 . " -" . $ie5);
        // echo '////////';
        // echo ($gp9 . " -" . $ga9 . " -" . $cv9 . "-" . $co9 . " -" . $vv9 . " -" . $ie9);


    //

    //gross profit - costo g&a - costo depto venta-costo operacion - varianza vehiculo + gastos e ingresos
    //Costo vehiculo operacion no se resta

    $np1 = $gp1 - $ga1 - $cv1 - $co1 - $vv1 + $ie1;
    $np2 = 0;
    $np3 = $gp3 - $ga3 - $cv3 - $co3 - $vv3 + $ie3;
    $np4 = 0;
    $np5 = $gp5 - $ga5 - $cv5 - $co5 - $vv5 + $ie5;
    $np6 = 0;
    $np7 = $gp7 - $ga7 - $cv7 - $co7 - $vv7 + $ie7;
    $np8 = 0;
    $np9 = $gp9 - $ga9 - $cv9 - $co9 - $vv9 + $ie9;
    $np10 = 0;

    if (is_numeric($np1) == false) {
        $np1 = 0;
    } else {
        $np1 = $np1;
    }
    if (is_numeric($np2) == false){
        $np2 = 0;
    } else {
        $np2 = $np2;
    }

    if (is_numeric($np3) == false) {

        $np3 = 0;
    } else {
        $np3 = $np3;
    }
    if (is_numeric($np4) == false) {
        $np4 = 0;
    } else {
        $np4 = $np4;
    }
    if (is_numeric($np5) == false) {
        $np5 = 0;
    } else {
        $np5 = $np5;
    }
    if (is_numeric($np6) == false) {
        $np6 = 0;
    } else {
        $np6 = $np6;
    }
    if (is_numeric($np7) == false) {
        $np7 = 0;
    } else {
        $np7 = $np7;
    }
    if (is_numeric($np8) == false) {
        $np8 = 0;
    } else {
        $np8 = $np8;
    }
    if (is_numeric($np9) == false) {
        $np9 = 0;
    } else {
        $np9 = $np9;
    }
    if (is_numeric($np10) == false) {
        $np10 = 0;
    } else {
        $np10 = $np10;
    }



    $arraydatos2['np1'] = $np1;
    $arraydatos2['np2'] = $np2;
    $arraydatos2['np3'] = $np3;
    $arraydatos2['np4'] = $np4;
    $arraydatos2['np5'] = $np5;
    $arraydatos2['np6'] = $np6;
    $arraydatos2['np7'] = $np7;
    $arraydatos2['np8'] = $np8;
    $arraydatos2['np9'] = $np9;
    $arraydatos2['np10'] = $np10;

    
        //Porcentajes Finales 
        
        
        
        $u5 = 0;
        $d5 = 0;
        $t5 = 0;
        $c5 = 0;
        $cc5 = 0;
    
        if($arraydatos[0]['AnoAnteriorMont'] != 0 ){
            $u5 = (($arraydatos2['np1'] * 100) / $arraydatos[0]['AnoAnteriorMont']);
        }

        if($arraydatos[0]['PlanAnual']!= 0){
            $d5 = (($arraydatos2['np3'] * 100) / $arraydatos[0]['PlanAnual']);
        }

        if($arraydatos[0]['ColocarAnioActual']!= 0){
            $t5 = (($arraydatos2['np5'] * 100) / $arraydatos[0]['ColocarAnioActual']);
        }

        if($arraydatos[0]['PlanMesActual']!= 0){
            
            $c5 = (($arraydatos2['np7'] * 100) / $arraydatos[0]['PlanMesActual']);
        }

        if($arraydatos[0]['ActualMes']!= 0){
       
            $cc5 = (($arraydatos2['np9'] * 100) / $arraydatos[0]['ActualMes']);
        }

        $arraydatos2['np2'] = $u5;
        $arraydatos2['np4'] = $d5;
        $arraydatos2['np6'] = $t5;
        $arraydatos2['np8'] = $c5;
        $arraydatos2['np10'] = $cc5;



        // $u1 = (($arraydatos2['ga1'] * 100) / $arraydatos[0]['AnoAnteriorMont']);//AnoAnteriorMont
        // $u2 = (($arraydatos2['cv1'] * 100) / $arraydatos[0]['AnoAnteriorMont']);//AnoAnteriorMont
        // $u3 = (($arraydatos2['vb1'] * 100) / $arraydatos[0]['AnoAnteriorMont']);//AnoAnteriorMont
        // $u4 = (($arraydatos2['vv1'] * 100) / $arraydatos[0]['AnoAnteriorMont']);//AnoAnteriorMont
        

        //$arraydatos2['np2'] = ($u1+$u2+$u3+$u4+$u5);
        //print_r($u1.'-'.$u2.'-'.$u3.'-'.$u4.'-'.$u5);

        

        //$d1 = (($arraydatos2['ga3'] * 100) / $arraydatos[0]['PlanAnual']);//PlanAnual
        //$d2 = (($arraydatos2['cv3'] * 100) / $arraydatos[0]['PlanAnual']);//PlanAnual
        //$d3 = (($arraydatos2['vb3'] * 100) / $arraydatos[0]['PlanAnual']);//PlanAnual
        //$d4 = (($arraydatos2['vv3'] * 100) / $arraydatos[0]['PlanAnual']);//PlanAnual
        

        //print_r($d5);

        //$t1 = (($arraydatos2['ga5'] * 100) / $arraydatos[0]['ColocarAnioActual']);//ColocarAnioActual
        //$t2 = (($arraydatos2['cv5'] * 100) / $arraydatos[0]['ColocarAnioActual']);//ColocarAnioActual
        //$t3 = (($arraydatos2['vb5'] * 100) / $arraydatos[0]['ColocarAnioActual']);//ColocarAnioActual
        //$t4 = (($arraydatos2['vv5'] * 100) / $arraydatos[0]['ColocarAnioActual']);//ColocarAnioActual
        //$t5 = (($arraydatos2['np5'] * 100) / $arraydatos[0]['ColocarAnioActual']);//ColocarAnioActual
        
        //$c1 = (($arraydatos2['ga7'] * 100) / $arraydatos[0]['PlanMesActual']);//PlanMesActual
        //$c2 = (($arraydatos2['cv7'] * 100) / $arraydatos[0]['PlanMesActual']);//PlanMesActual
        //$c3 = (($arraydatos2['vb7'] * 100) / $arraydatos[0]['PlanMesActual']);//PlanMesActual
        //$c4 = (($arraydatos2['vv7'] * 100) / $arraydatos[0]['PlanMesActual']);//PlanMesActual
        //$c5 = (($arraydatos2['np7'] * 100) / $arraydatos[0]['PlanMesActual']);//PlanMesActual

        //$cc1 = (($arraydatos2['ga9'] * 100) / $arraydatos[0]['ActualMes']);//ActualMes
        //$cc2 = (($arraydatos2['cv9'] * 100) / $arraydatos[0]['ActualMes']);//ActualMes
        //$cc3 = (($arraydatos2['vb9'] * 100) / $arraydatos[0]['ActualMes']);//ActualMes
        //$cc4 = (($arraydatos2['vv9'] * 100) / $arraydatos[0]['ActualMes']);//ActualMes
        //$cc5 = (($arraydatos2['np9'] * 100) / $arraydatos[0]['ActualMes']);//ActualMes



    //$this->NetProfit($arraydatos,$arraydatos2);

    return $arraydatos2;


    }

    
}
?>