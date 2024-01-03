<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CestadosfinancierosinfoPDF extends REST_Controller
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
        $this->load->model('finanzas/Mporcentajeoperacion');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mactualcostove');
        $this->load->model('dashboard/uno/Mtiposervicio');
        $this->load->model('estadosf/Mconfigporcensubtipof');

        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('Mclientes');
        $this->load->model('Mclientesucursal');


        $this->load->model('Mempresa');
        $this->load->model('Mtiposervicio');
        $this->load->model('Mconfigservicio');
        $this->load->model('Mclientes');
        $this->load->model('Mclientesucursal');
        $this->load->library('reports/financiero/RptCostoca');
        $this->load->library('FinanzasActualizacion');
        $this->load->library('EstadoFinanciero');

        $this->load->model('Msucursal');
        setTimeZone($this->verification,$this->input);
    }


    public function getDataTodosInfo_get()
    {
        $this->load->library('reports/financiero/RptEstadofinanciero');
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $arraydatos = array();
        $arraydatospdf= array();

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //$this->response($IdSucursal);

        $IdServicio = $this->get('IdConfigS');
        $IdTipoServ = $this->get('IdTipoServ');
        $Anio = $this->get('Anio');
        $AnioActual = $this->get('Anio');
        $Mes = $this->get('Mes');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $IdConfigS = $this->post('IdConfigS');

        //Insertar el plan 

        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

        $arrayplanfact = array("Facturacion", "Materiales", "Equipos", "Mano de Obra", "Vehiculos", "Contratistas", "Viaticos", "Burden");

        // SINO EXISTE UN PLAN DE FACTURA LO INSERTAMOS
        /*if (count($rowPlanFactura) == 0)
        {
            foreach ($arrayplanfact as $Descripcion)
            {
                $oplanfactura = new Mplanfactura();
                $oplanfactura->IdSucursal = $IdSucursal;
                $oplanfactura->Descripcion = $Descripcion;
                //$oplanfactura->set_insert_planfactura();
            }
        }*/

        // VOLVEMOS A TOMAR EL PLAN PERO AHORA YA EXISTENTE
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

        // TRAEMOS DATOS DEL TIPO DE SERVICIO
        /*$otiposervicio = new Mtiposervicio();
        $otiposervicio->IdConfigS = $IdServicio;
        $otiposervicio->IdSucursal = $IdSucursal;
        $rowgm = $otiposervicio->get_list_tiposervicio();
        $contad = 0;
        $GM = 0;

        foreach($rowgm as $element)
        {
            $GM+= $element->GrossM;
            $contad++;
        }

        if($contad==0){
            $contad=1;
        }
        $GrossM = $GM /$contad;*/
        
        //print_r($rowPlanFactura);
        //************CALCULOS***********
        //************CALCULOS*******
        //************CALCULOS************

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
        $SucursalGet='';
        $ClienteGet='';

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
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {
                            $respFac = $oestadofupdate->get_recobery_estadofupdate();
                            $dataFac = $respFac['data'];
                                 
                            if ($dataFac->Monto>0)
                            {
                                $MontoUpdate = $dataFac->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto1 = $oestadofupdate->get_list_estadofupdate();
                                 
                            foreach($rowmonto1 as $element1)
                            {
                                $MontoUpdate+= $element1->Monto;
                            }
                        }
                    }

                    // colocamos la facturacion almacenada en la base de datos
                    // Colocar = Monto Actual en Mes Actual
                    if($MontoMensualFactura==0)
                    {
                        if($MontoUpdate>0)
                        {
                            $Colocar = $MontoUpdate;
                        }
                        else
                        {
                            $Colocar = 0;  
                        }
                    }
                    else
                    {
                        $colop = $MontoMensualFactura+$MontoUpdate;
                        $Colocar = number_format($colop,0,'.','');
                    }

                    if ($TotalFact==0)
                    {
                        $ColocarAnioActual = 0;
                    }
                    else
                    {
                        $ColocarAnioActual = number_format($TotalFact,0,'.','');
                    }

                    if($IdTipoServ !='')
                    { 
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Facturacion');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPF = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPF = $respPF['data'];
                            
                        $AnoAnteriorMont = $dataPF->AnioAnterior;
                        //$AnoAnteriorMont = 16666;
                        $AnoAnteriorPorcent = $dataPF->PorcentajeAnterior;

                        $oMclientes= new Mclientes();
                        $oMclientes->IdCliente=$IdCliente;
                        $data3 = $oMclientes->get_clientes();
                       
                        if ($data3['status'])
                        {
                            $Clientes =$data3['data']->Nombre;
                            $ClienteGet=$Clientes;
                            if (!empty($IdClienteS))
                            {
                                $oMclientesucursal = new Mclientesucursal();
                                $oMclientesucursal->IdClienteS=$IdClienteS;
                                $data4 =$oMclientesucursal->get_cliente();
                                if ($data4['status'])
                                {
                                    $Sucursal=$data4['data']->Nombre;
                                    $SucursalGet=$Sucursal;
                                }
                            }
                        }
                    }

                    if(intval($Mes)==$MesActual && $Anio==$AnioActual) 
                    {
                        $readonlmonto='';
                        $clasmonto='features-input';
                    }
                    
                    $MesAnterior = $MesAnterior = $MesActual-1;
                    if($MesAnterior > 0)
                    {
                        if (intval($Mes)==$MesAnterior && $Anio==$AnioActual) 
                        {
                            $readonlmonto='';
                            $clasmonto='features-input';
                        }
                    }

                    $AnioVal = $AnioActual -1;

                    if (intval($Mes)==12 && $AnioVal ==$Anio)
                    {
                        $readonlmonto='';
                        $clasmonto='features-input';
                    }
                }

                if($element->Descripcion=='Mano de Obra')
                {
                    $oestadofupdate->Descripcion = 'Mano de Obra';

                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {  
                            $respMan = $oestadofupdate->get_recobery_estadofupdate();
                            $dataMan = $respMan['data'];
                             
                            if ($dataMan->Monto>0)
                            {
                                $MontoUpdate = $dataMan->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto2 = $oestadofupdate->get_list_estadofupdate();
                             
                            foreach ($rowmonto2 as $element2)
                            {
                                $MontoUpdate+= $element2->Monto;
                            }
                        }
                    }
                    
                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $ManoObraT+$MontoUpdate;
                    $ColocarAnioActual = $ManoObraTAnioActual;//para el mes 1 al mes actual
                    $readonlyactual='readonly="true"';
                     
                    if($IdTipoServ !='')
                    {
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = 'Mano de Obra';
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPM = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPM = $respPM['data'];
                        
                        $AnoAnteriorMont = $dataPM->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPM->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion== ('Vehiculos'))
                {
                    $oestadofupdate->Descripcion = 'Vehiculos';
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if ($IdTipoServ !='')
                        {
                            $respVeh = $oestadofupdate->get_recobery_estadofupdate();
                            $dataVeh = $respVeh['data'];
                             
                            if ($dataVeh->Monto>0)
                            {
                                $MontoUpdate = $dataVeh->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto3 = $oestadofupdate->get_list_estadofupdate();
                             
                            foreach ($rowmonto3 as $element3)
                            {
                                $MontoUpdate+= $element3->Monto;
                            }
                        }
                    }
                
                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $CostoV+$MontoUpdate;
                    $ColocarAnioActual = $CostoVAnioActual ;
                    $readonlyactual='readonly="true"';
                
                    if($IdTipoServ !='')
                    {
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Vehiculos');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPV = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPV = $respPV['data'];
                            
                        $AnoAnteriorMont = $dataPV->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPV->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion=='Burden')
                {
                    $oestadofupdate->Descripcion = 'Burden';
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {
                            $respBur = $oestadofupdate->get_recobery_estadofupdate();
                            $dataBur = $respBur['data'];
                             
                            if ($dataBur->Monto>0)
                            {
                                $MontoUpdate = $dataBur->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto4 = $oestadofupdate->get_list_estadofupdate();
                             
                            foreach ($rowmonto4 as $element4)
                            {
                                $MontoUpdate+= $element4->Monto;
                            }
                        }
                    }

                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $BurdenTotal+$MontoUpdate;
                    $ColocarAnioActual = $BurdenTotalAnioActual;
                    $readonlyactual='readonly="true"';
                  
               
                    if($IdTipoServ !='')
                    { 
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Burden');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPB = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPB = $respPB['data'];
                
                        $AnoAnteriorMont = $dataPB->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPB->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion=='Materiales')
                {
                    $oestadofupdate->Descripcion='Materiales';
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        { 
                            $respMat = $oestadofupdate->get_recobery_estadofupdate();
                            $dataMat = $respMat['data'];
                            
                            if ($dataMat->Monto>0)
                            {
                                $MontoUpdate = $dataMat->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto5 = $oestadofupdate->get_list_estadofupdate();
                             
                            foreach ($rowmonto5 as $element5)
                            {
                                $MontoUpdate+= $element5->Monto;
                            }
                        }
                    }

                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $MaterialesD+$MontoUpdate;
                    $ColocarAnioActual = $MaterialesDAnio;
                    $readonlyactual='readonly="true"';               
                 
                    if($IdTipoServ !='')
                    { 
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Materiales');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPMt = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPMt = $respPMt['data'];
                
                        $AnoAnteriorMont = $dataPMt->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPMt->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion=='Equipos')
                {
                    $oestadofupdate->Descripcion = 'Equipos';
                 
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {
                            $respEq = $oestadofupdate->get_recobery_estadofupdate();
                            $dataEq = $respEq['data'];
                            
                            if ($dataEq->Monto>0)
                            {
                                $MontoUpdate = $dataEq->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto6 = $oestadofupdate->get_list_estadofupdate();
                            
                            foreach ($rowmonto6 as $element6)
                            {
                                $MontoUpdate+= $element6->Monto;
                            }
                        }
                    }

                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $EquiposD+$MontoUpdate;
                    $ColocarAnioActual = $EquiposDAno ;
                    $readonlyactual='readonly="true"';
             
                    if($IdTipoServ !='')
                    { 
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Equipos');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPE = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPE = $respPE['data'];
                        $AnoAnteriorMont = $dataPE->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPE->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion=='Contratistas')
                {
                    $oestadofupdate->Descripcion='Contratistas';
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {
                            $respCon = $oestadofupdate->get_recobery_estadofupdate();
                            $dataCon = $respCon['data'];
                            
                            if ($dataCon->Monto>0)
                            {
                                $MontoUpdate = $dataCon->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto7 = $oestadofupdate->get_list_estadofupdate();
                            
                            foreach ($rowmonto7 as $element7)
                            {
                                $MontoUpdate+= $element7->Monto;
                            }
                        }
                    }

                    // Colocar = Monto Actual en Mes Actual
                    $Colocar =$ContratistasD+$MontoUpdate;
                    $ColocarAnioActual = $ContratistasDAnio;
                    $readonlyactual='readonly="true"';
      
                    if($IdTipoServ !='')
                    { 
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Contratistas');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPC = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPC = $respPC['data'];
                
                        $AnoAnteriorMont = $dataPC->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPC->PorcentajeAnterior;
                    }
                }

                if($element->Descripcion=='Viaticos')
                {
                    $oestadofupdate->Descripcion='Viaticos';
                    if($IdClienteS==0 && $IdCliente==0)
                    {
                        if($IdTipoServ !='')
                        {
                            $respVia = $oestadofupdate->get_recobery_estadofupdate();
                            $dataVia = $respVia['data'];
                             
                            if($dataVia->Monto>0)
                            {
                                $MontoUpdate = $dataVia->Monto;
                            }
                        }
                        else
                        {
                            $rowmonto8 = $oestadofupdate->get_list_estadofupdate();
                             
                            foreach ($rowmonto8 as $element8)
                            {
                                $MontoUpdate+= $element8->Monto;
                            }
                        }
                    }

                    // Colocar = Monto Actual en Mes Actual
                    $Colocar = $ViaticosD+$MontoUpdate;
                    $ColocarAnioActual = $ViaticosDAnio ;
                    $readonlyactual='readonly="true"';
                  
                    if($IdTipoServ !='')
                    {
                        $oporcentajeoperacion = new Mporcentajeoperacion();
                        $oporcentajeoperacion->Descripcion = ('Viaticos');
                        $oporcentajeoperacion->IdSucursal = $IdSucursal;
                        $oporcentajeoperacion->IdTipoSer = $IdServicio;
                        $oporcentajeoperacion->IdSubtipoServ = $IdTipoServ;
                        $oporcentajeoperacion->Anio = $Anio;
                        $respPVi = $oporcentajeoperacion->get_recobery_porcentajeoperacion();
                        $dataPVi = $respPVi['data'];
                        
                        $AnoAnteriorMont = $dataPVi->AnioAnterior;
                        $AnoAnteriorPorcent = $dataPVi->PorcentajeAnterior;
                    }
                }

                //fin  
                //PASADO BASE DE DATOS DETALLEESTADO FINANCIERO
                ////////
                //html//
                ////////
				
				//print_r($element);
                $array = array(
                    'IdPlanFactura'         => $element->IdPlanFactura,
                    'IdPorcentajeOperacion' => '',//$oporcentajeoperacion->IdPorcentajeOperacion,
                    'Descripcion'           => $element->Descripcion,
                    'AnioAnteriorMonto'    => number_format($AnoAnteriorMont,0,'.',''),
                    'AnioAnteriorPorcen'    => number_format($AnoAnteriorPorcent,1,'.',''),

                    'AnioActualPlan'        => number_format($PlanAnual,0,'.',''),
                    'AnioActualPlanPorcent' => $PorcentajePlan,
                    'AnioActualMonto'       => number_format($ColocarAnioActual,0,'.',''),
                    'AnioActualPorcen'      => round(0),

                    'MesActualPlan'         => number_format($Plan,0,'.',''),
                    'MesActualPlanPorcen'   => number_format($PorcentajeMesPlan,1,'.',''),
                    'MesActualMonto'        => number_format($Colocar,0,'.',''),
                    'MesActualPorcen'       => 0,


                   
                );

                array_push($arraydatos,$array);
                
                $con++;
            }

            ////////
            //html//
            ////////

            $arraydatos2 = array(
                'TotalCostoOper'        => '',
                'COAnioAnteriorMonto'   => number_format(0,2,'.',''),
                'COAnioAnteriorPorcen'  => 0,
                'COAnioActualPlan'      => number_format(0,2,'.',''),
                'COAnioActualPlanPorcen'=> number_format(0,2,'.',''),
                'COAnioActualMonto'     => number_format(0,2,'.',''),
                'COAnioActualPorcen'    => number_format(0,2,'.',''),
                'COMesActualPlan'       => number_format(0,2,'.',''),
                'COMesActualPlanPorcen' => 0,
                'COMesActualMonto'      => number_format(0,2,'.',''),
                'COMesActualPorcen'     => 0,
            
                'GROSSPROFIT'           => '',
                'GPAnioAnteriorMonto'   => 0,
                'GPAnioAnteriorPorcen'  => 0,
                'GPAnioActualPlan'      => 0,
                'GPAnioActualPlanPorcen'=> 0,
                'GPAnioActualMonto'     => 0,
                'GPAnioActualPorcen'    => 0,
                'GPMesActualPlan'       => 0,
                'GPMesActualPlanPorcen' => 0,
                'GPMesActualMonto'      => 0,
                'GPMesActualPorcen'     => 0,
            );     
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

                $MesActualPlanPorcen=0;

                $FacturacionPlan=0;
                $Material=0;
                $Equipo=0;
                $ManoObra=0;
                $Vehiculo=0;
                $Contratista=0;
                $Viatico=0;
                $Burden=0;


                
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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
                        $oMestadofinanciero->IdConfigS = $IdConfigS;
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

                
                $array2 = array(
                    'IdPlanFactura'         => $element->IdPlanFactura,
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
                    'PorcentajeFinanzas'    =>$MesActualPlanPorcen,
                    'Cliente'               =>$ClienteGet,
                    'Sucursal'              =>$SucursalGet
                    
                    
                );

                array_push($arraydatospdf,$array2);
                
                $con++;
            }

            
            $arraydatos3 = array(
                'COAnioActualPorcen'    => number_format(0,2,'.',''),
                'GPAnioActualPlanPorcen'=> 0,
                'COMesPlan'             => 0,
                'GPMesActualMonto'      => 0,
            );     

        }
        

        $value = $this->Calculos_EstaosF($arraydatos,$arraydatos2);

        $data['row'] =  $value['row'];
        $data['rowconfig'] =  $value['config'];

        $value2 = $this->Calcular_PorcentajesNUEVO($arraydatospdf,$arraydatos3);

        $data2['row'] =  $value2['row2'];
        $data2['rowconfig'] =  $value2['config2'];



        $dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Estado financiero';
        $dataResp['Folio']='';
        $dataResp['Cliente']=$ClienteGet;
        $dataResp['ClienteSucursal']=$SucursalGet;
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        //$dataResp['TipoServicio']=$Servicio;
        $dataResp['Lista']=$data;
        $dataResp['Lista2']=$data2;

        // var_dump($dataResp);
    
        
        $pdf=new RptEstadofinanciero("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        $pdf->Output(); 

        
        // return $this->set_response([
        //     'status' => true,
        //     'data' => $data,
        //     'data2' => $data2,
        //     'message' => 'Success',
        // ], REST_Controller::HTTP_OK);
    }


    function Calcular_PorcentajesNUEVO($arraydatospdf,$arraydatos3){


        $varAnioActualPorcen = 0;
        
        $arraydatospdf[0]['AnioActualPorcen'] = 100; 
        $MaterialesCantidad=$arraydatospdf[1]['Materiales'];
        $EquiposCantidad=$arraydatospdf[2]['Equipos'];
        $ManoDeObraCantidad=$arraydatospdf[3]['ManoDeObra'];
        $VehiculosCantidad=$arraydatospdf[4]['Vehiculos'];
        $ContratistasCantidad=$arraydatospdf[5]['Contratistas'];
        $ViaticosCantidad=$arraydatospdf[6]['Viaticos'];
        $BurdenCantidad=$arraydatospdf[7]['Burden'];
        $FacturacionCantidad=$arraydatospdf[0]['FacturacionPlan'];

        $sumaTotal=0;
        $SumaRestaTotal=0;

        $contador = 0;
        foreach($arraydatospdf as $element)
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
               

                $arraydatospdf[$contador]['AnioActualPorcen'] = number_format($element['AnioActualPorcen'], 1, '.', '');
                
                  
            }
        
            $contador ++;
        }

        $sumaTotal=$MaterialesCantidad+$EquiposCantidad+$ManoDeObraCantidad+$VehiculosCantidad+$ContratistasCantidad+$ViaticosCantidad+$BurdenCantidad;
        // var_dump($sumaTotal);
        $SumaRestaTotal=$FacturacionCantidad-$sumaTotal;
        $arraydatos3['COMesPlan'] = number_format($sumaTotal,1,'.','');
        $arraydatos3['GPMesActualMonto'] = number_format($SumaRestaTotal,1,'.','');
        $GPAnioActualPlanPorcen = (100 - $varAnioActualPorcen);
        if ($GPAnioActualPlanPorcen >= 100) {
            $GPAnioActualPlanPorcen = 100;
        }
        $arraydatos3['COAnioActualPorcen'] = number_format($varAnioActualPorcen,1,'.','');
        $arraydatos3['GPAnioActualPlanPorcen'] = number_format($GPAnioActualPlanPorcen, 1, '.', '');

       

        $dataCalculos['row2'] = $arraydatospdf;
        $dataCalculos['config2'] = $arraydatos3;

        return $dataCalculos;
    }
    

    //!fin nuevo andrea 


    function Calculos_EstaosF($arraydatos,$arraydatos2)
    {
        $AnioAnteriorMontoTot = $arraydatos[0]['AnioAnteriorMonto'];
        $AnioActualPlanTot = $arraydatos[0]['AnioActualPlan'];
        $AnioActualMontoTot = $arraydatos[0]['AnioActualMonto'];

        $MesActualMontoTot = $arraydatos[0]['MesActualMonto'];
        $MesActualMontoTot2 = $arraydatos[0]['MesActualMonto'];
        $MesActualMonto = $arraydatos[0]['MesActualMonto'];

        
        //si es 0 que se divida entre 1
        if ($MesActualMontoTot == '0' || $MesActualMontoTot == '') {
            $MesActualMontoTot = 1;
        }
    
        if ($AnioAnteriorMontoTot == '0' || $AnioAnteriorMontoTot == '') {
            $AnioAnteriorMontoTot = '';
        }
    
        $varAnioAnteriorMonto = 0;
        $varAnioAnteriorPorcen = 0;

        $varAnioActualPlan = 0;
        $varAnioActualPlanPorcen = 0;
        $varAnioActualMonto = 0;
        $varAnioActualPorcen = 0;

        $varMesActualPlan = 0;
        $varMesActualPlanPorcen = 0;
        $varMesActualMonto = 0;
        $varMesActualPorcen = 0;
    
        $arraydatos[0]['AnioAnteriorPorcen'] = 100;
        $arraydatos[0]['AnioActualPlanPorcent'] = 100;
        $arraydatos[0]['AnioActualPorcen'] = 100;   
        $arraydatos[0]['MesActualPorcen'] = 100;

        $valorAnioActualPlan = $arraydatos[0]['AnioActualPlan'];
    
        //****calculos***
        $contador = 0;
        foreach($arraydatos as $element)
        {
            if($contador > 0)
            {
                // HACEMOS CALCULOS PARA OBTENER LOS PORCENTAJES, LOS CUALES SE HACEN DEL MONTO DE ITERACION*100 ENTRE EL MONTO DEL PRIMER CONCEPTO EL CUAL ES FACTURACION
                if($element['MesActualMonto'] == 0 || is_numeric(($element['MesActualMonto'] * 100)/$MesActualMontoTot) == false)
                {
                    $evalua1 = 0;
                }else{
                    $evalua1 = (($element['MesActualMonto'] * 100) / $MesActualMontoTot);
                }
                

                //POCENTAJE DEL AÑO PASADO
                if($element['AnioAnteriorMonto'] == 0 || is_numeric(($element['AnioAnteriorMonto'] * 100)/$AnioAnteriorMontoTot) == false)
                {
                    $evalua2 = 0;
                }else{
                    $evalua2 = (($element['AnioAnteriorMonto'] * 100) / $AnioAnteriorMontoTot);
                }


                //if($element['AnioActualPorcen'] > 0){
                /* if($element['AnioActualMonto'] > 0){
                    $element['AnioActualPorcen'] = (($element['AnioActualMonto'] * 100) / $AnioActualMontoTot);
                } */
                
                // PORCENTAJE DEL AÑO ACTUAL MONTO
                if($AnioActualMontoTot == 0 || $element['AnioActualMonto'] == 0 || is_numeric(($element['AnioActualMonto'] * 100)/$AnioActualMontoTot) == false)
                {
                    $evalua3 = 0;
                }else{
                    $evalua3 = (($element['AnioActualMonto'] * 100) / $AnioActualMontoTot);
                }

                
                //PORCENTAJE DEL AÑO ACTUAL PLAN
				if($element['AnioActualPlan'] > 0)
				{
					$ValorPorcenActual = number_format((($element['AnioActualPlan'] * 100) / $valorAnioActualPlan),1,'.','');
				}else{
					$ValorPorcenActual = 0;
				}
                
        
                if (is_numeric($ValorPorcenActual) == false) {
                    $evalua4 = 0;
                } else {
                    $evalua4 = $ValorPorcenActual;
                }

                $element['AnioAnteriorPorcen'] = number_format($evalua2,1,'.','');
                $element['AnioActualPlanPorcent'] = number_format($evalua4,1,'.','');
                $element['AnioActualPorcen'] = number_format($evalua3,1,'.','');
                $element['MesActualPorcen'] = number_format($evalua1,1,'.','');

            
                $varAnioAnteriorMonto+= number_format($element['AnioAnteriorMonto'], 2, '.', '');
                $varAnioAnteriorPorcen+= number_format($element['AnioAnteriorPorcen'], 1, '.', '');
                $varAnioActualPlan+= number_format($element['AnioActualPlan'], 2, '.', '');
                $varAnioActualPlanPorcen+= number_format($element['AnioActualPlanPorcent'], 1, '.', '');
                $varAnioActualMonto+= number_format($element['AnioActualMonto'], 2, '.', '');
                $varAnioActualPorcen+= number_format($element['AnioActualPorcen'], 1, '.', '');
                $varMesActualPlan+= number_format($element['MesActualPlan'], 2, '.', '');
                $varMesActualPlanPorcen+= number_format($element['MesActualPlanPorcen'], 1, '.', '');
                $varMesActualMonto+= number_format($element['MesActualMonto'], 2, '.', '');
                $varMesActualPorcen+= number_format($element['MesActualPorcen'], 1, '.', '');

                $arraydatos[$contador]['AnioAnteriorPorcen'] = number_format($element['AnioAnteriorPorcen'], 1, '.', '');
                $arraydatos[$contador]['AnioActualPorcen'] = number_format($element['AnioActualPorcen'], 1, '.', '');
                $arraydatos[$contador]['AnioActualPlanPorcent'] = number_format($element['AnioActualPlanPorcent'], 1, '.', '');
                
                if($MesActualMontoTot2 > 0){
                    $arraydatos[$contador]['MesActualPorcen'] = number_format($element['MesActualPorcen'], 1, '.', '');
                }else{
                    $arraydatos[$contador]['MesActualPorcen'] = 0;
                }       
            }
        
            $contador ++;
        }
        // FIN FOREACH CONCEPTOS


		if($AnioAnteriorMontoTot > 0 || $varAnioAnteriorMonto > 0){
			$GPAnioAnteriorMonto = $AnioAnteriorMontoTot - $varAnioAnteriorMonto;
		}else{
			$GPAnioAnteriorMonto = 0;
		}

        $GPAnioAnteriorPorcen = 100 - $varAnioAnteriorPorcen;
        
        
        if(is_numeric($varAnioAnteriorMonto) == false || $varAnioAnteriorMonto == 0) {
            $evalua5 = 0;
        }else {
            $evalua5 = $varAnioAnteriorMonto;
        }
      
    
        if(is_numeric($GPAnioAnteriorMonto) == false || $GPAnioAnteriorMonto == 0) {
            $evalua6 = 0;
        }else {
            $evalua6 = $GPAnioAnteriorMonto;
        }    
    
        $GPAnioAnteriorPorcen = $GPAnioAnteriorPorcen;
        if ($GPAnioAnteriorPorcen >= 100) {
            $GPAnioAnteriorPorcen = 100;
        }
    
        // AÑO ANTERIOR CO Y GP
        $arraydatos2['COAnioAnteriorMonto'] = number_format($evalua5, 2, '.', '');
        $arraydatos2['GPAnioAnteriorMonto'] = number_format($evalua6, 2, '.', '');
        $arraydatos2['COAnioAnteriorPorcen'] = number_format($varAnioAnteriorPorcen, 1, '.', '');
        $arraydatos2['GPAnioAnteriorPorcen'] = number_format($GPAnioAnteriorPorcen, 1, '.', '');
    
        $evalua7 = $varAnioActualPlan;
        if (is_numeric($varAnioActualPlan) == false) {
            $evalua7 = 0;
        }
    
        // AÑO ACTUAL PLAN Y MONTO
        $arraydatos2['COAnioActualPlan'] = number_format($evalua7,2,'.','');
        $arraydatos2['COAnioActualPlanPorcen'] = number_format($varAnioActualPlanPorcen,1,'.','');
        $arraydatos2['COAnioActualMonto'] = number_format($varAnioActualMonto,2,'.','');
        $arraydatos2['COAnioActualPorcen'] = number_format($varAnioActualPorcen,1,'.','');

        // MES ACTUAL PLAN Y MONTO
        $arraydatos2['COMesActualPlan'] = number_format($varMesActualPlan,2,'.','');
    
        $COMesActualPlanPorcen = $varMesActualPlanPorcen;
    
        if ($COMesActualPlanPorcen >= 100) {
            $COMesActualPlanPorcen = 100;
        }
        //echo $varMesActualMonto.'//';
        $arraydatos2['COMesActualPlanPorcen'] =  number_format($COMesActualPlanPorcen,1,'.','');
        $arraydatos2['COMesActualMonto'] =  number_format($varMesActualMonto,2,'.','');
    
        $COMesActualPorcen = $varMesActualPorcen;
        if ($COMesActualPorcen >= 100) {
            $COMesActualPorcen = 100;
        }
        $arraydatos2['COMesActualPorcen'] =  number_format($COMesActualPorcen,1,'.','');

        /*
        'GROSSPROFIT'           => '',
        'GPAnioAnteriorMonto'   => 'CostoGrossProfittAnterior',
        'GPAnioAnteriorPorcen'  => 'PorcentajeGrossProfittAnterior',
        'GPAnioActualPlan'      => 'GrosPPlanAnio',
        'GPAnioActualPlanPorcen'=> 'GrosPPlanAnioPorcen',
        'GPAnioActualMonto'     => 'GrosPActualAnio',
        'GPAnioActualPorcen'    => 'GrosPActualAnioPorcen',
        'GPMesActualPlan'       => 'GrosprofitPlanMes',
        'GPMesActualPlanPorcen' => 'GrosprofitFacturacionPlanMes',
        'GPMesActualMonto'      => 'GrosprofitActualMes',
        'GPMesActualPorcen'     => 'GrosprofitPorcentajeAnualMes', */

        // AÑO ACTUAL PLAN ===
        $evalua8 = ($AnioActualPlanTot - $varAnioActualPlan);
    
        $GPAnioActualPlanPorcen = (100 - $varAnioActualPlanPorcen);
        if ($GPAnioActualPlanPorcen >= 100) {
            $GPAnioActualPlanPorcen = 100;
        }
    
        $arraydatos2['GPAnioActualPlan'] = number_format($evalua8, 2, '.', '');
        $arraydatos2['GPAnioActualPlanPorcen'] = number_format($GPAnioActualPlanPorcen, 1, '.', '');
    
        // AÑO ACTUAL MONTO ===
        $GPAnioActualMonto = ($AnioActualMontoTot - $varAnioActualMonto);
        if (is_numeric($GPAnioActualMonto) == false) {
            $GPAnioActualMonto = 0;
        }

        $GPAnioActualPorcen = (100 - $varAnioActualPorcen);
    
        if ($GPAnioActualPorcen < 0) {
            $GPAnioActualPorcen = 0;
        }
        $arraydatos2['GPAnioActualMonto'] = number_format($GPAnioActualMonto, 2, '.', '');
        $arraydatos2['GPAnioActualPorcen'] = number_format($GPAnioActualPorcen, 1, '.', '');


        // MES ACTUAL PLAN ===
        $evalua9 = ($arraydatos[0]['MesActualPlan'] - $varMesActualPlan);
        $evalua10 = (100 - $varMesActualPlanPorcen);
        $arraydatos2['GPMesActualPlan'] = number_format($evalua9, 2, '.', '');
        $arraydatos2['GPMesActualPlanPorcen'] = number_format($evalua10, 1, '.', '');

        // MES ACTUAL MONTO ===
        if (is_numeric(($MesActualMonto - $varMesActualMonto)) == false) {
            $GPMesActualMonto = 0;
        }
        else
        {
            $GPMesActualMonto = ($MesActualMonto - $varMesActualMonto);
        }

        $arraydatos2['GPMesActualMonto'] = number_format($GPMesActualMonto,2, '.', '');
    
        $GrossPPAM = (100 - $varMesActualPorcen);
        if ($GrossPPAM >= 100) {
            $GrossPPAM = 100;
        }
    
        if ($GrossPPAM < 0) {
            $evalua11 = 0;
        } else {
            $evalua11 = $GrossPPAM;
        }

        $arraydatos2['GPMesActualPorcen'] = number_format($evalua11,1, '.', '');
    

        $dataCalculos['row'] = $arraydatos;
        $dataCalculos['config'] = $arraydatos2;

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

        //gross profit - costo g&a - costo depto venta-costo operacion - varianza vehiculo + gastos e ingresos
        //Costo vehiculo operacion no se resta

        /* $np1 = $gp1 - $ga1 - $cv1 - $co1 - $vv1 + $ie1;
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
        $arraydatos2['np10'] = $cc5; */

    return $arraydatos2;
    }    
}
?>