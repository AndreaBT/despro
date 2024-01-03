<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CestadosFinanTodos extends REST_Controller
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
        $this->load->model('estadosf/Mporcentajeoperacion');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mactualcostove');
         
        setTimeZone($this->verification,$this->input);
    }

    //!Costo G&A
    public function CostoGA_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //?arrays
            $arraydatos=array();
            $arraySumasMes2=array();

        $Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
        $isGA =$this->get('isGA');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostoga = new Mcostoga();
		$Mcostoga->IdSucursal=$IdSucursal;
        $Mcostoga->Mes=$Mes;
        $Mcostoga->Anio=$Anio;
        $Respuesta =$Mcostoga->get_listNuevo();

        $Respuesta2=$Mcostoga->get_SumaMontoMes();

        $SumaMes2=0;
        foreach($Respuesta2 as $res){
            if ($res->Mes<=1&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                    
            }
            if ($res->Mes<=2 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=3 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            } 
            if ($res->Mes<=4&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
      
            }
            if ($res->Mes<=5&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=6&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=7&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=8&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=9&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=10&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }


                
            }
            if ($res->Mes<=11&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=12&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
           
            
            $arrayMesMonto = array(
                'Descripcion'           =>$res->Descripcion,

                'AnioAnteriorGA'        =>$res->AnioAnterior,
                'AnioAnteriorPlanGA'    =>$AnioAnteriorPlanGA,

                'AnioActualPlan'        =>$PlanAnualGA,
                'AnioActualPlanPorcen'  =>$AnioActualPlanPorcen,
                'AnioActualGA'          =>$SumaMes2,
                'AnioActualPorcenGA'    =>$AnioActualProcen,

                'PlanMes'               =>$PlanMes,
                'PlanMesPorcen'         =>$MesPlanPorce,
                'MesActual'             =>$res->MontoMes,
                'MesActualPorce'        =>$MesActualPorcen,
            );


            array_push($arraySumasMes2,$arrayMesMonto);
    
        }

        $data['CostoGA']         =  $arraySumasMes2;


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);

    }

    //!Costo Depto. Venta -listo
    public function getDeptoVenta_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //?arrays
            $arraydatos=array();
            $arraySumasMes2=array();

        $Anio = $this->get('Anio');
        $Mes = $this->get('Mes');
        $isDV =$this->get('isDV');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mgastosdirectos = new Mgastosdirectos();
		$Mgastosdirectos->IdSucursal=$IdSucursal;
        $Mgastosdirectos->Mes=$Mes;
        $Mgastosdirectos->Anio=$Anio;
        $Respuesta =$Mgastosdirectos->get_listDeptoVentaFiltro();
        $Respuesta2=$Mgastosdirectos->get_SumaMontoMes();

        $SumaMes2=0;
        foreach($Respuesta2 as $res){
            if ($res->Mes<=1&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->UnoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=2 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->UnoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=3&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->UnoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=4&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->DosT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
      
            }
            if ($res->Mes<=5&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->DosT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=6&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->DosT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
            }
            if ($res->Mes<=7&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TresT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=8&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TresT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=9&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TresT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=10&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuatroT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=11&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuatroT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=12&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuatroT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->MontoAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->UnoT/3;
                    $valorseugGA= $res->DosT/3;
                    $valorterGA= $res->TresT/3;
                    $valorcuatroGA= $res->CuatroT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            
            $arrayMesMonto = array(
                'Descripcion'           =>$res->Gasto,

                'AnioAnteriorGA'        =>$res->MontoAnterior,
                'AnioAnteriorPlanGA'    =>$AnioAnteriorPlanGA,

                'AnioActualPlan'        =>$PlanAnualGA,
                'AnioActualPlanPorcen'  =>$AnioActualPlanPorcen,
                'AnioActualGA'          =>$SumaMes2,
                'AnioActualPorcenGA'    =>$AnioActualProcen,

                'PlanMes'               =>$PlanMes,
                'PlanMesPorcen'         =>$MesPlanPorce,
                'MesActual'             =>$res->MontoMes,
                'MesActualPorce'        =>$MesActualPorcen,
            );


            array_push($arraySumasMes2,$arrayMesMonto);
    
        }

       
        $data['CostoDV']         =  $arraySumasMes2;


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);

    }


    //!Costo Vehiculo Oper  -listo
    public function getCostosVehiOper_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //?arrays
            $arraydatos=array();
            $arraySumasMes2=array();

        $Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
        $isCVO =$this->get('isCVO');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostovehope = new Mcostovehope();
		$Mcostovehope->IdSucursal=$IdSucursal;
        $Mcostovehope->Mes=$Mes;
        $Mcostovehope->Anio=$Anio;
        $Respuesta =$Mcostovehope->get_listCostoVehiOperFiltro();

        $Respuesta2=$Mcostovehope->get_SumaMontoMes();

        $SumaMes2=0;
        foreach($Respuesta2 as $res){
            if ($res->Mes<=1&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                    
            }
            if ($res->Mes<=2 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=3 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            } 
            if ($res->Mes<=4&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
      
            }
            if ($res->Mes<=5&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=6&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=7&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=8&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=9&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=10&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }


                
            }
            if ($res->Mes<=11&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=12&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
           
            
            $arrayMesMonto = array(
                'Descripcion'           =>$res->Descripcion,

                'AnioAnteriorGA'        =>$res->AnioAnterior,
                'AnioAnteriorPlanGA'    =>$AnioAnteriorPlanGA,

                'AnioActualPlan'        =>$PlanAnualGA,
                'AnioActualPlanPorcen'  =>$AnioActualPlanPorcen,
                'AnioActualGA'          =>$SumaMes2,
                'AnioActualPorcenGA'    =>$AnioActualProcen,

                'PlanMes'               =>$PlanMes,
                'PlanMesPorcen'         =>$MesPlanPorce,
                'MesActual'             =>$res->MontoMes,
                'MesActualPorce'        =>$MesActualPorcen,
            );


            array_push($arraySumasMes2,$arrayMesMonto);
    
        }

        

        $data['CostoV']         =  $arraySumasMes2;



		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
    }

    //!Costo Financiero
    public function CostoFinanciero_get()
    {
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //?arrays
            $arraydatos=array();
            $arraySumasMes2=array();

        $Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
        $isCF =$this->get('isCF');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mactualcostof = new Mactualcostof();
		$Mactualcostof->IdSucursal=$IdSucursal;
        $Mactualcostof->Mes=$Mes;
        $Mactualcostof->Anio=$Anio;
        $Respuesta =$Mactualcostof->get_listCostosFinanFiltro();

        $Respuesta2=$Mactualcostof->get_SumaMontoMes();

        $SumaMes2=0;
        foreach($Respuesta2 as $res){
            if ($res->Mes<=1&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                    
            }
            if ($res->Mes<=2 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=3 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            } 
            if ($res->Mes<=4&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
      
            }
            if ($res->Mes<=5&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=6&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=7&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=8&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=9&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=10&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }


                
            }
            if ($res->Mes<=11&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=12&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
           
            
            $arrayMesMonto = array(
                'Descripcion'           =>$res->Descripcion,

                'AnioAnteriorGA'        =>$res->AnioAnterior,
                'AnioAnteriorPlanGA'    =>$AnioAnteriorPlanGA,

                'AnioActualPlan'        =>$PlanAnualGA,
                'AnioActualPlanPorcen'  =>$AnioActualPlanPorcen,
                'AnioActualGA'          =>$SumaMes2,
                'AnioActualPorcenGA'    =>$AnioActualProcen,

                'PlanMes'               =>$PlanMes,
                'PlanMesPorcen'         =>$MesPlanPorce,
                'MesActual'             =>$res->MontoMes,
                'MesActualPorce'        =>$MesActualPorcen,
            );


            array_push($arraySumasMes2,$arrayMesMonto);
    
        }

        $data['CostoF']         =  $arraySumasMes2;


		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);

    }

    //!Este es el bueno - Costo Depto Oper
    public function getDespVentaNuevo_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        //?arrays
            $arraydatos=array();
            $arraySumasMes2=array();

        $Anio = $this->get('Anio');
		$Mes = $this->get('Mes');
        $isDO =$this->get('isDO');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostodeptoventas = new Mcostodeptoventas();
		$Mcostodeptoventas->IdSucursal=$IdSucursal;
        $Mcostodeptoventas->Mes=$Mes;
        $Mcostodeptoventas->Anio=$Anio;
        $Respuesta =$Mcostodeptoventas->get_listDeptoOperaFiltro();
        $Respuesta2=$Mcostodeptoventas->get_SumaMontoMes();
        // var_dump($Respuesta2);

        $SumaMes2=0;
        foreach($Respuesta2 as $res){
            if ($res->Mes<=1&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }
                    foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }

                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }

                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=1)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                    
            }
            if ($res->Mes<=2 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            }
            if ($res->Mes<=3 && $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;
                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                
                $PlanMes=$res->PrimerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

            } 
            if ($res->Mes<=4&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
      
            }
            if ($res->Mes<=5&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=6&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->SegundoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
            if ($res->Mes<=7&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=8&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=9&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->TercerT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=10&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }


                
            }
            if ($res->Mes<=11&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }

                
            }
            if ($res->Mes<=12&& $res->SumaMes>=0) {
                $SumaMes2=$res->SumaMes;

                $Mes2 = $this->get('Mes');
                if ($Mes2 < 10) {
                    $Mes2 = '0' . $Mes2;
                }

                $PlanMes=$res->CuartoT/3;
                $PlanMesPorcen =0;
                //?AnioActualMonto - AnioActualPorcen
                    $oestadofinanciero = new Mestadosfinancieros();
                    $oestadofinanciero->IdSucursal = $IdSucursal;
                    $oestadofinanciero->Anio2 = $this->get('Anio2');
                    $oestadofinanciero->Mes2 = $Mes2;
                    $factura = $oestadofinanciero->get_facturacionAnioAct();
                    $factura2=$oestadofinanciero->get_facturacionMesfijo();
                    
                    foreach ($factura as $factu) {
                        $TotalFact = $factu->FacturacionS;
                    }foreach ($factura2 as $res2) {
                        $TotalFact2 = $res2->FacturacionS;
                        
                    }
                    //!validación de 0.
                    $MesActualPorcen=0;
                    if($TotalFact2>0){
                        $MesActualPorcen=($res->MontoMes*100)/$TotalFact2;
                    }
                    else{
                        $MesActualPorcen=0;
                    }
                    
                    if($TotalFact>0 ){
                        $AnioActualProcen=($res->SumaMes*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }

                    $PrimerTT=0;
                    $SegundoTT=0;
                    $TercerTT=0;
                    $CuartoTT=0;
                    $AnoAnteriorMont=0;
                    $oporcentajeoperacion=new Mporcentajeoperacion();
                    $oporcentajeoperacion->Descripcion = "Facturacion";
                    $oporcentajeoperacion->IdSucursal = $IdSucursal;
                    $oporcentajeoperacion->Anio = $Anio;
                    $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinancieroFiltro();

                    $PrimerTT = $recoveryoporcentajeoperacion['data']->PrimerT;
                    $SegundoTT = $recoveryoporcentajeoperacion['data']->SegundoT;
                    $TercerTT = $recoveryoporcentajeoperacion['data']->TercerT;
                    $CuartoTT = $recoveryoporcentajeoperacion['data']->CuartoT;
                    $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                    $AnioAnteriorPlanGA=0;
                    if($AnoAnteriorMont>0){
                        $AnioAnteriorPlanGA=($res->AnioAnterior*100)/$AnoAnteriorMont;
                    }else{
                        $AnioAnteriorPlanGA=0;
                    }

                    if($res->Mes<=2)
                    {
                        $Plan=$PrimerTT/3;
                        if($Plan>0){
                            $MesPlanPorce=($PlanMes*100)/$Plan;
                        }else{
                            $MesPlanPorce=0;
                        }
                        
                    }

                    $valorprim= $PrimerTT/3;
                    $valorseug= $SegundoTT/3;
                    $valorter= $TercerTT/3;
                    $valorcuatro=$CuartoTT/3;

                    $PlanAnual=0;
                    $count=1;

                    for ($i=1; $i <=12 ;$i++)
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

                    $valorprimGA= $res->PrimerT/3;
                    $valorseugGA= $res->SegundoT/3;
                    $valorterGA= $res->TercerT/3;
                    $valorcuatroGA= $res->CuartoT/3;

                    $PlanAnualGA=0;

                    $count=1;

                    $AnioActualMontoPlan=0;
                    $AnioActualMontoPlanPrcen=0;
                    for ($i=1; $i <=12 ;$i++)
                    {
                        if ($i <4){
                            $PlanAnualGA +=$valorprimGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                               
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <7){
                            $PlanAnualGA +=$valorseugGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else if($i <10 ){
                            $PlanAnualGA +=$valorterGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }
                        else
                        {
                            $PlanAnualGA +=$valorcuatroGA;
                            if($PlanAnual>0){
                                $AnioActualPlanPorcen= ($PlanAnualGA *100)/$PlanAnual;
                                $AnioActualMontoPlan +=  $res->MontoMes;
                                $AnioActualMontoPlanPrcen = ($AnioActualMontoPlan* 100)/$TotalFact;
                            }else{
                                $AnioActualPlanPorcen=0;
                            }
                        }

                        if($count==$Mes){
                            break;
                        }
                        $count++;
                    }
                
            }
           
            
            $arrayMesMonto = array(
                'Descripcion'           =>$res->Descripcion,

                'AnioAnteriorGA'        =>$res->AnioAnterior,
                'AnioAnteriorPlanGA'    =>$AnioAnteriorPlanGA,

                'AnioActualPlan'        =>$PlanAnualGA,
                'AnioActualPlanPorcen'  =>$AnioActualPlanPorcen,
                'AnioActualGA'          =>$SumaMes2,
                'AnioActualPorcenGA'    =>$AnioActualProcen,

                'PlanMes'               =>$PlanMes,
                'PlanMesPorcen'         =>$MesPlanPorce,
                'MesActual'             =>$res->MontoMes,
                'MesActualPorce'        =>$MesActualPorcen,
            );


            array_push($arraySumasMes2,$arrayMesMonto);
    
        }

        $data['CostoDO']         =  $arraySumasMes2;

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
    }
}