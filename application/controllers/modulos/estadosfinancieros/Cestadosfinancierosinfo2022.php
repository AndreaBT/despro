<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cestadosfinancierosinfo2022 extends REST_Controller
{
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
        $this->load->model('finanzas/Mporcentajeoperacion');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mactualcostove');
        $this->load->model('dashboard/uno/Mtiposervicio');
        $this->load->model('estadosf/Mconfigporcensubtipof');

        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('ctaporpagar/Mctaporpagar');


        setTimeZone($this->verification,$this->input);
    }

    public function facturacionServicios_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $arraydatos = array();

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        

        //$this->response($IdSucursal);

        $IdServicio = $this->get('IdConfigS');
        $IdTipoServ = $this->get('IdTipoServ');
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


        // VOLVEMOS A TOMAR EL PLAN PERO AHORA YA EXISTENTE
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

        $Trimestre = 0;
        if ($Mes < 4) {
            $Trimestre = 1;
        } else if ($Mes < 7) {
            $Trimestre = 2;
        } else if ($Mes < 10) {
            $Trimestre = 3;
        } else {
            $Trimestre = 4;
        }

        $MesActual = date("n");

        //Total de enero hasta el mes actual del anio
        //Este es el mes seleccionado del combo
        //$Mes = $Mes + 1;

        if ($Mes < 10) {
            $Mes = '0' . $Mes;
        }   

        // TRAE TODOS LOS SERVICIOS DEL MES, Total mesual de vehiculo, mano de obra y burden total.
        $oservicio = new Mserviciosf();
        $oservicio->Fecha_F = $Anio.'-'.$Mes;
        $oservicio->RegEstatus = 'A';
        $oservicio->IdSucursal = $IdSucursal;
        $oservicio->Tipo_Serv = $IdServicio;
            
        if($IdClienteS>0){
            $oservicio->IdClienteS = $IdClienteS;
        }
        if($IdTipoServ !='')
        {
            $oservicio->IdSubIndice = $IdTipoServ;
                
            $oconfigporcensubtipo = new Mconfigporcensubtipof();
            $oconfigporcensubtipo->IdConfigS = $IdServicio;
            $oconfigporcensubtipo->IdTipoS = $IdTipoServ;
            $oconfigporcensubtipo->Anio = $Anio;
            //$oconfigporcensubtipo->IdSucursal = $IdSucursal;
            $oconfigporcensubtipo->get_recobery_configporcensubtipo();
            
            
            //$PorcenSub = $oconfigporcensubtipo->get_recobery_configporcensubtipo();
            //print_r($PorcenSub);

        }
        if($IdCliente>0){
            $oservicio->IdCliente = $IdCliente;
        }
        if($IdContrato>0){
            $oservicio->IdContrato = $IdContrato;
        }
            
        $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

        $BurdenTotal = 0;
        $ManoObraT = 0;
        $CostoV = 0;
        $EquiposD = 0;
        $MaterialesD = 0;
        $ViaticosD = 0;
        $ContratistasD = 0;

        foreach($rowmesserv as $elementfin)
        {
            
            if($elementfin->BurdenTotal !='')
            {
                $BurdenTotal+= $elementfin->BurdenTotal;
                $ManoObraT+= $elementfin->ManoObraT;
                $CostoV+= $elementfin->CostoV;

                $EquiposD+= $elementfin->EquiposD;
                $MaterialesD+= $elementfin->MaterialesD;
                $ViaticosD+= $elementfin->ViaticosD;
                $ContratistasD+= $elementfin->ContratistasD;
                
            }
        }
        
        //Traer total de servicios de enero hasta el mes actual vehiculo mano de obra y burden toal.
        //Aqui se debe hacer un bettwen 
        // TRAE TODOS LOS SERVICIOS DESDE INICIO DEL AÑO HASTA EL MES ACTUAL
        $oservicio = new Mserviciosf();
        $oservicio->Fecha_F = $Anio.'-'.$Mes.'-31';
        $oservicio->Fecha_I = $Anio.'-'.'01-'.'01';
        $oservicio->RegEstatus = 'A';
        $oservicio->IdSucursal = $IdSucursal;

        if ($IdTipoServ > 0) {
            $oservicio->IdSubIndice = $IdTipoServ;
        }
        if ($IdClienteS > 0) {
            $oservicio->IdClienteS = $IdClienteS;
        }
        if ($IdCliente > 0) {
            $oservicio->IdCliente = $IdCliente;
        }
        if ($IdContrato > 0) {
            $oservicio->IdContrato = $IdContrato;
        }

        $oservicio->Tipo_Serv = $IdServicio;
        $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

        $BurdenTotalAnioActual = 0;
        $ManoObraTAnioActual = 0;
        $CostoVAnioActual = 0;

        $EquiposDAno = 0;
        $MaterialesDAnio = 0;
        $ViaticosDAnio = 0;
        $ContratistasDAnio = 0;
    
        foreach($rowmesserv as $elementfin)
        {    
            if($elementfin->BurdenTotal != '')
            {
                $BurdenTotalAnioActual+= $elementfin->BurdenTotal;
                $ManoObraTAnioActual+= $elementfin->ManoObraT;
                $CostoVAnioActual+= $elementfin->CostoV;
                $EquiposDAno+= $elementfin->EquiposD;
                $MaterialesDAnio+= $elementfin->MaterialesD;
                $ViaticosDAnio+= $elementfin->ViaticosD;
                $ContratistasDAnio+= $elementfin->ContratistasD;
            }
        }

        // Buscamos el monto de facturacion de enero hasta el mes actual o seleccionado
        $TotalFact = 0;

        $oestadofinanciero = new Mestadosfinancieros();
        $oestadofinanciero->IdConfigS = $IdServicio;
        $oestadofinanciero->IdTipoServ = $IdTipoServ;
        $oestadofinanciero->IdSucursal = $IdSucursal;
        $oestadofinanciero->Anio = $Anio;
        $oestadofinanciero->Mes =  '01';
        $oestadofinanciero->Mes2 = $Mes;
        $oestadofinanciero->IdCliente = $IdCliente;
        $oestadofinanciero->IdClienteS = $IdClienteS;
        $oestadofinanciero->IdContrato = $IdContrato;
        $rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();
        
        foreach ($rowfacturaanio as $element) {
            $TotalFact+= $element->Facturacion;
        }

        // (MONTO AÑO ACTUAL)
        $TotalFact = $TotalFact;

        //****BUSQUEDA DE ESTADOFINANCIERO EN LA BASE DE DATOS
        // BUSQUEDA POR EL MES ACTUAL O SELECCIONADO
        $oestadofinanciero = new Mestadosfinancieros();
        $oestadofinanciero->IdConfigS = $IdServicio;
        $oestadofinanciero->IdSucursal = $IdSucursal;
        $oestadofinanciero->IdTipoServ = $IdTipoServ;
        $oestadofinanciero->Anio = $Anio;
        $oestadofinanciero->Mes = $Mes;
        $oestadofinanciero->Mes2 = $Mes;
        $oestadofinanciero->IdCliente = $IdCliente;
        $oestadofinanciero->IdClienteS = $IdClienteS;
        $oestadofinanciero->IdContrato = $IdContrato;
        $rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();
        
        // (MONTO MES ACTUAL)
        $MontoMensualFactura=0;
        
        foreach ($rowmontoestado as $elemento) {
            $MontoMensualFactura+= $elemento->Facturacion;
        }

        ////////////////////////////////////////////////////////////////////////////////
        //AQUI SUMAMOS LA NUEVA FACTURACION DE ESTADOUPDATE
        
        //Si el cliente es igual a 0 buscamos en ESTADOUPDATE
        if($IdClienteS==0 && $IdCliente==0)
        {
            // OBTENEMOS EL ESTADO UPDATE DEL INICIO DEL AÑO HASTA EL MES ACTUAL
            $oestadofupdate = new Mestadofupdate();
            $oestadofupdate->IdSucursal = $IdSucursal;
            $oestadofupdate->Mes = intval('01');
            $oestadofupdate->Mes2 = intval($Mes);
            $oestadofupdate->Anio = $Anio;
            $oestadofupdate->IdConfigServ = $IdServicio;
            $oestadofupdate->IdTipoServ = $IdTipoServ;
            $rowUpdate = $oestadofupdate->get_list_estadofupdate();

            foreach($rowUpdate as $element)
            {
                if($element->Descripcion=='Facturacion'){
                    $TotalFact+= $element->Monto;
                }
                if($element->Descripcion=='Materiales'){
                    $MaterialesDAnio+= $element->Monto;
                }
                if($element->Descripcion=='Equipos'){
                    $EquiposDAno+= $element->Monto;
                }
                if($element->Descripcion=='Mano de Obra'){
                    $ManoObraTAnioActual+= $element->Monto;
                }
                if($element->Descripcion=='Vehiculos'){
                    $CostoVAnioActual+= $element->Monto;
                }
                if($element->Descripcion=='Contratistas'){
                    $ContratistasDAnio+= $element->Monto;
                }
                if($element->Descripcion=='Viaticos'){
                    $ViaticosDAnio+= $element->Monto;
                }
                if($element->Descripcion=='Burden'){
                    $BurdenTotalAnioActual+= $element->Monto;
                }
            }
        }


        if(count($rowPlanFactura))
        {
            $con = 0;
            $TotalFactMes = 0;
			
            foreach($rowPlanFactura as $element)
            {
                $AnoAnteriorMont=0;
                $AnoAnteriorPorcent=0;
                $PorcentajeMesPlan=0;
                $PorcentajePlan = 0;

                $AnoAnteriorMont=0;
                $AnoAnteriorPorcent=0;
                $PorcentajeMesPlan=0;
                $PorcentajePlan = 0;

                $MesActualPlanPorcen=0;

                $FacturacionPlan=0;
                $Material=0;
                $Equipo=0;
                $ManoObra=0;
                $Vehiculo=0;
                $Contratista=0;
                $Viatico=0;
                $Burden=0;

                if($IdTipoServ =='')
                { 
                    $otiposervicio = new Mtiposervicio();
                    $otiposervicio->IdConfigS = $IdServicio;
                    $otiposervicio->RegEstatus = 'A';
                    $otiposervicio->IdSucursal = $IdSucursal;
                    $rowsercicios= $otiposervicio->get_list_tiposervicio();

                    //Si el subtipo no hay entonces recorremos todos los ubtipos
                    $Plan = 0;
                    $PlanAnual = 0;
                    $TotalAnual = 0;

                    foreach($rowsercicios as $subtipo)
                    {
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $subtipo->IdTipoSer;
                        $oporcentajeoperacion->IdPlanFactura = $element->IdPlanFactura;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPorcent = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        //print_r($respPorcent);
                        $porcent = $respPorcent['data'];
                        
                        // MONTO AÑO PASADO 
                        $AnoAnteriorMont+= $porcent->AnioAnterior;
                        
                        // PLAN MES ACTUAL
                        if($Trimestre==1){
                            $Plan+= $porcent->PrimerT/3;
                        }
                        if($Trimestre==2){
                            $Plan+= $porcent->SegundoT/3;
                        }
                        if($Trimestre==3){
                            $Plan+= $porcent->TercerT/3;
                        }
                        if($Trimestre==4){
                            $Plan+= $porcent->CuartoT/3;
                        }

                        $TotalAnual+= $porcent->PrimerT+$porcent->SegundoT+$porcent->TercerT+$porcent->CuartoT;
                        
                        // PLAN AÑO ACTUAL
                        $valorprim = $porcent->PrimerT/3;
                        $valorseug = $porcent->SegundoT/3;
                        $valorter = $porcent->TercerT/3;
                        $valorcuatro = $porcent->CuartoT/3;
                        $count=1;

                        for($i=1; $i<=12; $i++)
                        {
                            if ($i <4)
                            {
                                $PlanAnual+= $valorprim;
                            }
                            else if($i <7 )
                            {
                                $PlanAnual+= $valorseug;
                            }
                            else if($i <10 )
                            {
                                $PlanAnual+= $valorter; 
                            }
                            else
                            {
                                $PlanAnual+= $valorcuatro;
                            }
                            
                            if ($count==$Mes)
                            {
                                break;
                            }
                            $count++;
                        }
                    }

                    // SI EL PRIMER CONTADOR ES EL DE FACTURA, EL PLAN ES EL MONTO Y EL PORCENTAJE ES 100%
                    if($con==0)
                    {
                        $PorcentajeMesPlan = 100;
                        $TotalFactMes = $Plan;
                    }
                    else
                    {
                        if($TotalFactMes>0)
                        {       
                            $PorcentajeMesPlan = ($Plan *100) / $TotalFactMes;
                        }
                    }
                }
                else 
                {
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->IdTipoSer = $IdServicio;
                    $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                    $oporcentajeoperacion->Anio = $Anio;
                    $oporcentajeoperacion->IdPlanFactura = $element->IdPlanFactura;
                    $respPorcent = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinanciero();
                    $porcent2 = $respPorcent['data'];
                    
                    
                    $TotalAnual = ($porcent2->PrimerT+$porcent2->SegundoT+$porcent2->TercerT+$porcent2->CuartoT);
                    //****Este es el procetaje para anual mensual y actual del porcetjae de operacion;
                    $PorcentajePlan = $porcent2->PorcentajeAnual;
                    //****Find e Porcentaje Plan

                    // PLAN MES ACTUAL
                    $Plan = 0;
                    
                    if($Trimestre==1){
                        $Plan = $porcent2->PrimerT/3;
                    }
                    if($Trimestre==2){
                        $Plan = $porcent2->SegundoT/3;
                    }
                    if($Trimestre==3){
                        $Plan = $porcent2->TercerT/3;
                    }
                    if($Trimestre==4){
                        $Plan=$porcent2->CuartoT/3;
                    }

                    // PLAN AÑO ACTUAL
                    $PlanAnual = 0;
                    $valorprim = $porcent2->PrimerT/3;
                    $valorseug = $porcent2->SegundoT/3;
                    $valorter  = $porcent2->TercerT/3;
                    $valorcuatro = $porcent2->CuartoT/3;
                    $count=1;

                    for($i=1; $i<=12; $i++)
                    {
                        if($i <4)
                        {
                            $PlanAnual+= $valorprim;
                        }
                        else if($i <7 )
                        {
                            $PlanAnual+= $valorseug;
                        }
                        else if($i <10 )
                        {
                            $PlanAnual+= $valorter; 
                        }
                        else
                        {
                            $PlanAnual+= $valorcuatro;
                        }
                        
                        if ($count==$Mes)
                        {
                            break;
                        }
                        $count++;
                    }
                    
                    // SI EL PRIMER CONTADOR ES EL DE FACTURA, EL PLAN ES EL MONTO Y EL PORCENTAJE ES 100%
                    if($con==0)
                    {
                        $PorcentajeMesPlan = 100;
                        $TotalFactMes = $Plan;
                    }
                    else
                    { 
                        if($TotalFactMes>0)
                        {       
                            $PorcentajeMesPlan = ($Plan *100) / $TotalFactMes;
                        }
                    }
                }

                // Dependiendo de el nombre se pinta colocar en la columna  para mano de obra costo vehiculo y burden total
                $Colocar = 0;
                $ColocarAnioActual = 0;
                $readonlyactual = '';
                $clasmonto = 'features-input-dis ';
                $readonlmonto ='readonly="true"';

                // Este es nuevo EstadoUpdateClass
                $oestadofupdate = new Mestadofupdate();
                $oestadofupdate->IdSucursal = $IdSucursal;
                $oestadofupdate->Mes = intval($Mes);
                $oestadofupdate->Mes2 = intval($Mes);
                $oestadofupdate->Anio = $Anio;
                $oestadofupdate->IdConfigServ = $IdServicio;
                $oestadofupdate->IdTipoServ = $IdTipoServ;
                $MontoUpdate = 0;
                

                if($element->Descripcion==('Facturacion'))
                {
                    $oestadofupdate->Descripcion='Facturacion';
                    
                    if($IdTipoServ !='')
                    { 
                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        $IdTipoServ=0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $FacturacionPlan = $dataexist['data']->FacturacionPMensual;
                            $IdTipoServ=$dataexist['data']->IdTipoServ;
                            $IdCliente=$dataexist['data']->IdCliente;
                            $IdClienteS=$dataexist['data']->IdClienteS;

                            
                        }
                        
                    }

                }

                if($element->Descripcion=='Mano de Obra')
                {
                    $oestadofupdate->Descripcion = 'Mano de Obra';
                     
                    if($IdTipoServ !='')
                    {
                        
                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $ManoObra = $dataexist['data']->ManoDeObra;
                        }
                        
                    }
                }

                if($element->Descripcion== ('Vehiculos'))
                {
                    $oestadofupdate->Descripcion = 'Vehiculos';
                   
                
                    if($IdTipoServ !='')
                    {
                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Vehiculo = $dataexist['data']->Vehiculos;
                        }
                        
                    }
                }

                if($element->Descripcion=='Burden')
                {
                    $oestadofupdate->Descripcion = 'Burden';
               
                    if($IdTipoServ !='')
                    { 

                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Burden = $dataexist['data']->Burden;
                        }
                        
                        
                    }
                }

                if($element->Descripcion=='Materiales')
                {
                    $oestadofupdate->Descripcion='Materiales';         
                 
                    if($IdTipoServ !='')
                    { 

                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Material = $dataexist['data']->Materiales;
                            
                        }
                    }
                }

                if($element->Descripcion=='Equipos')
                {
                    $oestadofupdate->Descripcion = 'Equipos';
             
                    if($IdTipoServ !='')
                    { 
                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();
                        

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Equipo = $dataexist['data']->Equipos;
                            // var_dump($Equipo);
                        }
                        
                    }
                }

                if($element->Descripcion=='Contratistas')
                {
                    $oestadofupdate->Descripcion='Contratistas';
      
                    if($IdTipoServ !='')
                    { 

                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Contratista = $dataexist['data']->Contratistas;
                        }
                    }
                }

                if($element->Descripcion=='Viaticos')
                {
                    $oestadofupdate->Descripcion='Viaticos';

                    if($IdTipoServ !='')
                    {

                        $oMestadofinanciero= new Mestadofinanciero();
                        $oMestadofinanciero->IdSucursal = $IdSucursal;
                        $oMestadofinanciero->IdConfigS = $IdServicio;
                        $oMestadofinanciero->IdTipoServ=$IdTipoServ;
                        $oMestadofinanciero->Anio = $Anio;
                        $oMestadofinanciero->Mes = $Mes;
                        $oMestadofinanciero->IdCliente = $IdCliente;
                        $oMestadofinanciero->IdClienteS = $IdClienteS;
                        $oMestadofinanciero->IdContrato = $IdContrato;
                        $dataexist = $oMestadofinanciero->get_recovery();

                        $IdEstado = 0;
                        if($IdEstado = $dataexist['data']->IdEstadoF>0){
                            $Viatico = $dataexist['data']->Viaticos;
                        }
                    }
                }

                
                $array = array(
                    'AnioAnteriorMonto'    => number_format($AnoAnteriorMont,0,'.',''),
                    'AnioAnteriorPorcen'    => number_format($AnoAnteriorPorcent,1,'.',''),

                    'AnioActualPlan'        => number_format($PlanAnual,0,'.',''),
                    'AnioActualPlanPorcent' => $PorcentajePlan,
                    'AnioActualMonto'       => number_format($ColocarAnioActual,0,'.',''),
                    'AnioActualPorcen'      => round(0),

                    'MesActualPlan'         => number_format($Plan,0,'.',''),
                    'MesActualPlanPorcen'   => number_format($PorcentajeMesPlan,1,'.',''),
                    'MesActualMonto'        => number_format($Colocar,0,'.',''),
                    'MesActualPorcen'       => number_format(0,2,'.',''),

                    'IdPlanFactura'         => $element->IdPlanFactura,
                    'IdTipoServ'            =>$IdTipoServ,
                    'IdPorcentajeOperacion' => '',//$oporcentajeoperacion->IdPorcentajeOperacion,
                    'Descripcion'           => $element->Descripcion,
                    'AnioActualPorcen'      => round(0),
                    'FacturacionPlan'       =>number_format($FacturacionPlan,0,'.',''),
                    'Materiales'            =>$Material,
                    'Equipos'               =>number_format($Equipo,0,'.',''),
                    'ManoDeObra'            =>number_format($ManoObra,0,'.',''),
                    'Vehiculos'             =>number_format($Vehiculo,0,'.',''),
                    'Contratistas'          =>number_format($Contratista,0,'.',''),
                    'Burden'                =>number_format($Burden,0,'.',''),
                    'Viaticos'              =>number_format($Viatico,0,'.',''),
                    'IdTipoServ'            =>$IdTipoServ,
                    'IdCliente'             =>$IdCliente,
                    'IdClienteS'            =>$IdClienteS,
                    'Anio'                  =>$Anio,
                    'Mes'                   =>$Mes,
                    'PorcentajeFinanzas'    =>$MesActualPlanPorcen,
                    
                    
                );

                array_push($arraydatos,$array);
                
                $con++;
            }

            ////////
            //html//
            ////////

            $arraydatos2 = array(
                'COAnioActualPorcen'    => number_format(0,2,'.',''),
                'GPAnioActualPlanPorcen'=> 0,
            );     

        }

        $value = $this->Calcular_Porcentajes($arraydatos,$arraydatos2);

        $data['row'] =  $value['row'];
        $data['rowconfig'] =  $value['config'];

        
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);



    }

    function Calcular_Porcentajes($arraydatos,$arraydatos2){


        $varAnioActualPorcen = 0;
        
        $arraydatos[0]['AnioActualPorcen'] = 100; 
        $MaterialesCantidad=$arraydatos[1]['Materiales'];
        $EquiposCantidad=$arraydatos[2]['Equipos'];
        $ManoDeObraCantidad=$arraydatos[3]['ManoDeObra'];
        $VehiculosCantidad=$arraydatos[4]['Vehiculos'];
        $ContratistasCantidad=$arraydatos[5]['Contratistas'];
        $ViaticosCantidad=$arraydatos[6]['Viaticos'];
        $BurdenCantidad=$arraydatos[7]['Burden'];
        $FacturacionCantidad=$arraydatos[0]['FacturacionPlan'];

        $contador = 0;
        foreach($arraydatos as $element)
        {
            if($contador > 0)
            {
                if($element['Descripcion'] == 'Materiales' ){
                    if($MaterialesCantidad == 0 || is_numeric(($MaterialesCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($MaterialesCantidad * 100)/$FacturacionCantidad);
                    }
                }

                if($element['Descripcion'] == 'Equipos' ){
                    if($EquiposCantidad == 0 || is_numeric(($EquiposCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($EquiposCantidad * 100)/$FacturacionCantidad);
                    }
                }
                if($element['Descripcion'] == 'Mano de Obra' ){
                    if($ManoDeObraCantidad == 0 || is_numeric(($ManoDeObraCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($ManoDeObraCantidad * 100)/$FacturacionCantidad);
                    }
                }
                if($element['Descripcion'] == 'Vehiculos' ){
                    if($VehiculosCantidad == 0 || is_numeric(($VehiculosCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($VehiculosCantidad * 100)/$FacturacionCantidad);
                    }
                }
                if($element['Descripcion'] == 'Contratistas' ){
                    if($ContratistasCantidad == 0 || is_numeric(($ContratistasCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($ContratistasCantidad * 100)/$FacturacionCantidad);
                    }
                }
                if($element['Descripcion'] == 'Viaticos' ){
                    if($ViaticosCantidad == 0 || is_numeric(($ViaticosCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($ViaticosCantidad * 100)/$FacturacionCantidad);
                    }
                }
                if($element['Descripcion'] == 'Burden' ){
                    if($BurdenCantidad == 0 || is_numeric(($BurdenCantidad * 100)/$FacturacionCantidad) == false)
                    {
                        $factura = 0;
                    }else{
                        $factura = (($BurdenCantidad * 100)/$FacturacionCantidad);
                    }
                }

              
                $element['AnioActualPorcen'] = number_format($factura,1,'.','');
                $element['PorcentajeMateriales'] = $factura;

            
             
                $varAnioActualPorcen+= number_format($element['AnioActualPorcen'], 1, '.', '');
               

                $arraydatos[$contador]['AnioActualPorcen'] = number_format($element['AnioActualPorcen'], 1, '.', '');
                
                  
            }
        
            $contador ++;
        }

        $GPAnioActualPlanPorcen = (100 - $varAnioActualPorcen);
        if ($GPAnioActualPlanPorcen >= 100) {
            $GPAnioActualPlanPorcen = 100;
        }
        $arraydatos2['COAnioActualPorcen'] = number_format($varAnioActualPorcen,1,'.','');
        $arraydatos2['GPAnioActualPlanPorcen'] = number_format($GPAnioActualPlanPorcen, 1, '.', '');

       

        $dataCalculos['row'] = $arraydatos;
        $dataCalculos['config'] = $arraydatos2;

        return $dataCalculos;
    }
    


}

?>
    