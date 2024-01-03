<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CestadosFinancierosTodosPdf extends REST_Controller
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

        $this->load->model('Msucursal');
        $this->load->model('Mempresa');
         
        setTimeZone($this->verification,$this->input);
    }

    
    //!Costo G&A
    public function CostoGA_get(){
        $this->load->library('reports/financiero/RptCostoGA');
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       
        $arraydatos=array();

        $Anio = $this->get('Anio');
        $Mes = $this->get('Mes');
        $isCF =$this->get('isCF');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostoga = new Mcostoga();
		$Mcostoga->IdSucursal=$IdSucursal;
        $Mcostoga->Mes=$Mes;
        $Mcostoga->Anio=$Anio;
        $Respuesta =$Mcostoga->get_listNuevo();


        foreach ($Respuesta as $res) {

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

            $PrimerTT = $recoveryoporcentajeoperacion['data']->SPrimerT;
            $SegundoTT = $recoveryoporcentajeoperacion['data']->SSegundoT;
            $TercerTT = $recoveryoporcentajeoperacion['data']->STercerT;
            $CuartoTT = $recoveryoporcentajeoperacion['data']->SCuartoT;
            $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

           
            $AnioAnteriorPlan=0;
            if($AnoAnteriorMont>0){
                $AnioAnteriorPlan=($res->AnioAnterior*100)/$AnoAnteriorMont;
            }else{
                $AnioAnteriorPlan=0;
            }

            $PrimerTT2=0;
            $SegundoTT2=0;
            $TercerTT2=0;
            $CuartoTT2=0;
            $AnoAnteriorMont2=0;

            $RespuestaPlanAnual = $Mcostoga->get_recobery_costoGAEstadoFinancieroFiltro();
            $PrimerTT2 = $RespuestaPlanAnual['data']->PrimerT;
            $SegundoTT2 = $RespuestaPlanAnual['data']->SegundoT;
            $TercerTT2 = $RespuestaPlanAnual['data']->TercerT;
            $CuartoTT2 = $RespuestaPlanAnual['data']->CuartoT;
            $AnoAnteriorMont2 = $RespuestaPlanAnual['data']->AnioAnterior;

            $Trimestre1=$recoveryoporcentajeoperacion['data']->PrimerT;
            $Trimestre2=$recoveryoporcentajeoperacion['data']->SegundoT;
            $Trimestre3=$recoveryoporcentajeoperacion['data']->TercerT;
            $Trimestre4=$recoveryoporcentajeoperacion['data']->CuartoT;
            

            $PlanAnual2=0;
            $valorprim2= $PrimerTT2/3;
            $valorseug2= $SegundoTT2/3;
            $valorter2= $TercerTT2/3;
            $valorcuatro2=$CuartoTT2/3;

            //para el año actual Plan
            $PlanAnual=0;
            $valorprim= $PrimerTT/3;
            $valorseug= $SegundoTT/3;
            $valorter= $TercerTT/3;
            $valorcuatro=$CuartoTT/3;
            $count=1;

            $PlanMes = 0;
            $PlanMesPorcn=0;

            $Mes2 = $this->get('Mes');
            if ($Mes2 < 10) {
                $Mes2 = '0' . $Mes2;
            }

            
            
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

            $PlanAnioPorcen =0;
            $AnioActual=0;
            $AnioActualPorcen= 0;

            for ($i=1; $i <=12 ;$i++)
            {
                if ($i <4){
                    $Plan=$valorprim;
                    $PlanAnual +=$valorprim;
                    
                    $PlanAnual2 +=$res->PrimerT/3;
                    $PlanMes =$res->PrimerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre1;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <7){
                    $Plan=$valorseug;
                    $PlanAnual +=$valorseug;
                    $PlanAnual2 +=$res->SegundoT/3;
                    $PlanMes =$res->SegundoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre2;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <10 ){
                    $Plan=$valorter;
                    $PlanAnual +=$valorter;
                    $PlanAnual2 +=$res->TercerT/3;
                    $PlanMes =$res->TercerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre3;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else
                {
                    $Plan=$valorcuatro;
                    $PlanAnual +=$valorcuatro;
                    $PlanAnual2 +=$res->CuartoT/3;
                    $PlanMes =$res->CuartoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre4;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }

                if($count==$Mes){
                    break;
                }
                $count++;
            }

            $array = array(
                'Descripcion'         =>$res->Descripcion,

                'AnoAnteriorMont'       =>$AnoAnteriorMont,
                'PlanAnualFact'         =>$PlanAnual,
                'AnioActualFact'        =>$TotalFact,
                'PlanActualFact'        =>$Plan,
                'ActuaMesFact'          =>$TotalFact2,

                'AnioAnterior'        =>$res->AnioAnterior,
                'AnioAnteriorPlan'    =>$AnioAnteriorPlan,

                'PlanAnual'           =>$PlanAnual2,
                'PlanAnualPorcen'     =>$PlanAnioPorcen,
                'AnioActual'          =>$AnioActual,
                'AnioActualProcen'    =>$AnioActualProcen,

                "PlanMes"             =>$PlanMes,
                "PlanMesPorcen"       =>$PlanMesPorcen,
                "MesActual"           =>$res->MontoMes,
                'MesActualPorce'      =>$MesActualPorcen,
            );


            array_push($arraydatos,$array);

        }

		$data['CostoGA'] =  $arraydatos;

        $dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Costo Depto. Oper';
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        $dataResp['Lista']=$arraydatos;

        $pdf=new RptCostoGA("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        ob_end_clean();
        $pdf->Output(); 

    }

    //!Costo Depto. Venta -listo
    public function getDeptoVenta_get(){
        $this->load->library('reports/financiero/RptCostoDesptoVentaGen');

        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       
        $arraydatos=array();

        $Anio = $this->get('Anio');
        $Mes = $this->get('Mes');
        $isCF =$this->get('isCF');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mgastosdirectos = new Mgastosdirectos();
		$Mgastosdirectos->IdSucursal=$IdSucursal;
        $Mgastosdirectos->Mes=$Mes;
        $Mgastosdirectos->Anio=$Anio;
        $Respuesta =$Mgastosdirectos->get_listDeptoVentaFiltro();;


        foreach ($Respuesta as $res) {

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

            $PrimerTT = $recoveryoporcentajeoperacion['data']->SPrimerT;
            $SegundoTT = $recoveryoporcentajeoperacion['data']->SSegundoT;
            $TercerTT = $recoveryoporcentajeoperacion['data']->STercerT;
            $CuartoTT = $recoveryoporcentajeoperacion['data']->SCuartoT;
            $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

            $AnioAnteriorPlan=0;
            if($AnoAnteriorMont>0){
                $AnioAnteriorPlan=($res->MontoAnterior*100)/$AnoAnteriorMont;
            }else{
                $AnioAnteriorPlan=0;
            }

            $PrimerTT2=0;
            $SegundoTT2=0;
            $TercerTT2=0;
            $CuartoTT2=0;
            $AnoAnteriorMont2=0;

            $RespuestaPlanAnual = $Mgastosdirectos->get_recobery_costoventaEstadoFinancieroFiltro();
            $PrimerTT2 = $RespuestaPlanAnual['data']->PrimerT;
            $SegundoTT2 = $RespuestaPlanAnual['data']->SegundoT;
            $TercerTT2 = $RespuestaPlanAnual['data']->TercerT;
            $CuartoTT2 = $RespuestaPlanAnual['data']->CuartoT;
            $AnoAnteriorMont2 = $RespuestaPlanAnual['data']->AnioAnterior;

            $Trimestre1=$recoveryoporcentajeoperacion['data']->PrimerT;
            $Trimestre2=$recoveryoporcentajeoperacion['data']->SegundoT;
            $Trimestre3=$recoveryoporcentajeoperacion['data']->TercerT;
            $Trimestre4=$recoveryoporcentajeoperacion['data']->CuartoT;
            

            $PlanAnual2=0;
            $valorprim2= $PrimerTT2/3;
            $valorseug2= $SegundoTT2/3;
            $valorter2= $TercerTT2/3;
            $valorcuatro2=$CuartoTT2/3;

            //para el año actual Plan
            $PlanAnual=0;
            $valorprim= $PrimerTT/3;
            $valorseug= $SegundoTT/3;
            $valorter= $TercerTT/3;
            $valorcuatro=$CuartoTT/3;
            $count=1;

            $PlanMes = 0;
            $PlanMesPorcn=0;

            $Mes2 = $this->get('Mes');
            if ($Mes2 < 10) {
                $Mes2 = '0' . $Mes2;
            }

            
            
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

            $PlanAnioPorcen =0;
            $AnioActual=0;
            $AnioActualPorcen= 0;

            for ($i=1; $i <=12 ;$i++)
            {
                if ($i <4){
                    $Plan=$valorprim;
                    $PlanAnual +=$valorprim;
                    $PlanAnual2 +=$res->UnoT/3;
                    $PlanMes =$res->UnoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre1;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <7){
                    $Plan=$valorseug;
                    $PlanAnual +=$valorseug;
                    $PlanAnual2 +=$res->DosT/3;
                    $PlanMes =$res->DosT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre2;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <10 ){
                    $Plan=$valorter;
                    $PlanAnual +=$valorter;
                    $PlanAnual2 +=$res->TresT/3;
                    $PlanMes =$res->TresT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre3;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else
                {
                    $Plan=$valorcuatro;
                    $PlanAnual +=$valorcuatro;
                    $PlanAnual2 +=$res->CuatroT/3;
                    $PlanMes =$res->CuatroT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre4;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }

                if($count==$Mes){
                    break;
                }
                $count++;
            }

            $array = array(
                'Descripcion'         =>$res->Gasto,

                'AnoAnteriorMont'       =>$AnoAnteriorMont,
                'PlanAnualFact'         =>$PlanAnual,
                'AnioActualFact'        =>$TotalFact,
                'PlanActualFact'        =>$Plan,
                'ActuaMesFact'          =>$TotalFact2,

                'AnioAnterior'        =>$res->MontoAnterior,
                'AnioAnteriorPlan'    =>$AnioAnteriorPlan,

                'PlanAnual'           =>$PlanAnual2,
                'PlanAnualPorcen'     =>$PlanAnioPorcen,
                'AnioActual'          =>$AnioActual,
                'AnioActualProcen'    =>$AnioActualProcen,

                "PlanMes"             =>$PlanMes,
                "PlanMesPorcen"       =>$PlanMesPorcen,
                "MesActual"           =>$res->MontoMes,
                'MesActualPorce'      =>$MesActualPorcen,
            );


            array_push($arraydatos,$array);

        }

		$data['CostoDV'] =  $arraydatos;

		$dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Costo Depto. Oper';
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        $dataResp['Lista']=$arraydatos;

        $pdf=new RptCostoDesptoVentaGen("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        ob_end_clean();
        $pdf->Output(); 

    }


    //!Listo el PDF - Costo Vehiculo Oper 
    public function getCostosVehiOper_get(){
        $this->load->library('reports/financiero/RptCostoVehiculoGeneral');

        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       
        $arraydatos=array();

        $Anio = $this->get('Anio');
        $Mes = $this->get('Mes');
        $isCF =$this->get('isCF');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostovehope = new Mcostovehope();
		$Mcostovehope->IdSucursal=$IdSucursal;
        $Mcostovehope->Mes=$Mes;
        $Mcostovehope->Anio=$Anio;
        $Respuesta =$Mcostovehope->get_listCostoVehiOperFiltro();


        foreach ($Respuesta as $res) {

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

            $PrimerTT = $recoveryoporcentajeoperacion['data']->SPrimerT;
            $SegundoTT = $recoveryoporcentajeoperacion['data']->SSegundoT;
            $TercerTT = $recoveryoporcentajeoperacion['data']->STercerT;
            $CuartoTT = $recoveryoporcentajeoperacion['data']->SCuartoT;
            $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

            $AnioAnteriorPlan=0;
            if($AnoAnteriorMont>0){
                $AnioAnteriorPlan=($res->AnioAnterior*100)/$AnoAnteriorMont;
            }else{
                $AnioAnteriorPlan=0;
            }

            $PrimerTT2=0;
            $SegundoTT2=0;
            $TercerTT2=0;
            $CuartoTT2=0;
            $AnoAnteriorMont2=0;

            $RespuestaPlanAnual = $Mcostovehope->get_recobery_costovehiEstadoFinancieroFiltro();
            $PrimerTT2 = $RespuestaPlanAnual['data']->PrimerT;
            $SegundoTT2 = $RespuestaPlanAnual['data']->SegundoT;
            $TercerTT2 = $RespuestaPlanAnual['data']->TercerT;
            $CuartoTT2 = $RespuestaPlanAnual['data']->CuartoT;
            $AnoAnteriorMont2 = $RespuestaPlanAnual['data']->AnioAnterior;

            $Trimestre1=$recoveryoporcentajeoperacion['data']->PrimerT;
            $Trimestre2=$recoveryoporcentajeoperacion['data']->SegundoT;
            $Trimestre3=$recoveryoporcentajeoperacion['data']->TercerT;
            $Trimestre4=$recoveryoporcentajeoperacion['data']->CuartoT;
            

            $PlanAnual2=0;
            $valorprim2= $PrimerTT2/3;
            $valorseug2= $SegundoTT2/3;
            $valorter2= $TercerTT2/3;
            $valorcuatro2=$CuartoTT2/3;

            //para el año actual Plan
            $PlanAnual=0;
            $valorprim= $PrimerTT/3;
            $valorseug= $SegundoTT/3;
            $valorter= $TercerTT/3;
            $valorcuatro=$CuartoTT/3;
            $count=1;

            $PlanMes = 0;
            $PlanMesPorcn=0;

            $Mes2 = $this->get('Mes');
            if ($Mes2 < 10) {
                $Mes2 = '0' . $Mes2;
            }

            
            
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

            $PlanAnioPorcen =0;
            $AnioActual=0;
            $AnioActualPorcen= 0;

            for ($i=1; $i <=12 ;$i++)
            {
                if ($i <4){
                    $Plan = $valorprim;
                    $PlanAnual +=$valorprim;
                    $PlanAnual2 +=$res->PrimerT/3;
                    $PlanMes =$res->PrimerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre1;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <7){
                    $Plan = $valorseug;
                    $PlanAnual +=$valorseug;
                    $PlanAnual2 +=$res->SegundoT/3;
                    $PlanMes =$res->SegundoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre2;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <10 ){
                    $Plan = $valorter;
                    $PlanAnual +=$valorter;
                    $PlanAnual2 +=$res->TercerT/3;
                    $PlanMes =$res->TercerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre3;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else
                {
                    $Plan = $valorcuatro;
                    $PlanAnual +=$valorcuatro;
                    $PlanAnual2 +=$res->CuartoT/3;
                    $PlanMes =$res->CuartoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre4;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }

                if($count==$Mes){
                    break;
                }
                $count++;
            }

            $array = array(
                'Descripcion'         =>$res->Descripcion,

                'AnoAnteriorMont'       =>$AnoAnteriorMont,
                'PlanAnualFact'         =>$PlanAnual,
                'AnioActualFact'        =>$TotalFact,
                'PlanActualFact'        =>$Plan,
                'ActuaMesFact'          =>$TotalFact2,

                'AnioAnterior'        =>$res->AnioAnterior,
                'AnioAnteriorPlan'    =>$AnioAnteriorPlan,

                'PlanAnual'           =>$PlanAnual2,
                'PlanAnualPorcen'     =>$PlanAnioPorcen,
                'AnioActual'          =>$AnioActual,
                'AnioActualProcen'    =>$AnioActualProcen,

                "PlanMes"             =>$PlanMes,
                "PlanMesPorcen"       =>$PlanMesPorcen,
                "MesActual"           =>$res->MontoMes,
                'MesActualPorce'      =>$MesActualPorcen,
            );


            array_push($arraydatos,$array);

        }

		$data['CostoV'] =  $arraydatos;

        $dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Costo Depto. Oper';
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        $dataResp['Lista']=$arraydatos;

    
        
        $pdf=new RptCostoVehiculoGeneral("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        ob_end_clean();
        $pdf->Output(); 
    }

    //!Listo el PDF - Costo Financiero
    public function CostoFinanciero_get()
    {
        $this->load->library('reports/financiero/RptCostoFinaniceroGeneral');
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       
        $arraydatos=array();

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

        foreach ($Respuesta as $res) {

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

            $PrimerTT = $recoveryoporcentajeoperacion['data']->SPrimerT;
            $SegundoTT = $recoveryoporcentajeoperacion['data']->SSegundoT;
            $TercerTT = $recoveryoporcentajeoperacion['data']->STercerT;
            $CuartoTT = $recoveryoporcentajeoperacion['data']->SCuartoT;
            $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

            $AnioAnteriorPlan=0;
            if($AnoAnteriorMont>0){
                $AnioAnteriorPlan=($res->AnioAnterior*100)/$AnoAnteriorMont;
            }else{
                $AnioAnteriorPlan=0;
            }

            $PrimerTT2=0;
            $SegundoTT2=0;
            $TercerTT2=0;
            $CuartoTT2=0;
            $AnoAnteriorMont2=0;

            $RespuestaPlanAnual = $Mactualcostof->get_recobery_costofinanEstadoFinancieroFiltro();
            $PrimerTT2 = $RespuestaPlanAnual['data']->PrimerT;
            $SegundoTT2 = $RespuestaPlanAnual['data']->SegundoT;
            $TercerTT2 = $RespuestaPlanAnual['data']->TercerT;
            $CuartoTT2 = $RespuestaPlanAnual['data']->CuartoT;
            $AnoAnteriorMont2 = $RespuestaPlanAnual['data']->AnioAnterior;

            $Trimestre1=$recoveryoporcentajeoperacion['data']->PrimerT;
            $Trimestre2=$recoveryoporcentajeoperacion['data']->SegundoT;
            $Trimestre3=$recoveryoporcentajeoperacion['data']->TercerT;
            $Trimestre4=$recoveryoporcentajeoperacion['data']->CuartoT;
            

            $PlanAnual2=0;
            $valorprim2= $PrimerTT2/3;
            $valorseug2= $SegundoTT2/3;
            $valorter2= $TercerTT2/3;
            $valorcuatro2=$CuartoTT2/3;

            //para el año actual Plan
            $PlanAnual=0;
            $valorprim= $PrimerTT/3;
            $valorseug= $SegundoTT/3;
            $valorter= $TercerTT/3;
            $valorcuatro=$CuartoTT/3;
            $count=1;

            $PlanMes = 0;
            $PlanMesPorcn=0;

            $Mes2 = $this->get('Mes');
            if ($Mes2 < 10) {
                $Mes2 = '0' . $Mes2;
            }

            
            
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

            $PlanAnioPorcen =0;
            $AnioActual=0;
            $AnioActualPorcen= 0;

            for ($i=1; $i <=12 ;$i++)
            {
                if ($i <4){
                    $Plan =$valorprim;
                    $PlanAnual +=$valorprim;
                    $PlanAnual2 +=$res->PrimerT/3;
                    $PlanMes =$res->PrimerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre1;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <7){
                    $Plan =$valorseug;
                    $PlanAnual +=$valorseug;
                    $PlanAnual2 +=$res->SegundoT/3;
                    $PlanMes =$res->SegundoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre2;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <10 ){
                    $Plan =$valorter;
                    $PlanAnual +=$valorter;
                    $PlanAnual2 +=$res->TercerT/3;
                    $PlanMes =$res->TercerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre3;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else
                {
                    $Plan =$valorcuatro;
                    $PlanAnual +=$valorcuatro;
                    $PlanAnual2 +=$res->CuartoT/3;
                    $PlanMes =$res->CuartoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre4;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }

                if($count==$Mes){
                    break;
                }
                $count++;
            }

            $array = array(
                'Descripcion'         =>$res->Descripcion,

                'AnoAnteriorMont'       =>$AnoAnteriorMont,
                'PlanAnualFact'         =>$PlanAnual,
                'AnioActualFact'        =>$TotalFact,
                'PlanActualFact'        =>$Plan,
                'ActuaMesFact'          =>$TotalFact2,

                'AnioAnterior'        =>$res->AnioAnterior,
                'AnioAnteriorPlan'    =>$AnioAnteriorPlan,

                'PlanAnual'           =>$PlanAnual2,
                'PlanAnualPorcen'     =>$PlanAnioPorcen,
                'AnioActual'          =>$AnioActual,
                'AnioActualProcen'    =>$AnioActualProcen,

                "PlanMes"             =>$PlanMes,
                "PlanMesPorcen"       =>$PlanMesPorcen,
                "MesActual"           =>$res->MontoMes,
                'MesActualPorce'      =>$MesActualPorcen,
            );


            array_push($arraydatos,$array);

        }

		$data['CostoF'] =  $arraydatos;


		
		$dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Costo Depto. Oper';
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        $dataResp['Lista']=$arraydatos;

    
        
        $pdf=new RptCostoFinaniceroGeneral("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        ob_end_clean();
        $pdf->Output(); 

    }

    //!Listo el PDF - Costo Depto Oper
    public function getDespVentaNuevo_get(){
        $this->load->library('reports/financiero/RptCostoDeptoOper');
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       
        $arraydatos=array();

        $Anio = $this->get('Anio');
        $Mes = $this->get('Mes');
        $isCF =$this->get('isCF');
        $IdCliente = $this->get('IdCliente');
        $IdClienteS = $this->get('IdClienteS');
        $IdContrato = $this->get('IdContrato');

        $Mcostodeptoventas = new Mcostodeptoventas();
		$Mcostodeptoventas->IdSucursal=$IdSucursal;
        $Mcostodeptoventas->Mes=$Mes;
        $Mcostodeptoventas->Anio=$Anio;
        $Respuesta =$Mcostodeptoventas->get_listDeptoOperaFiltro();


        foreach ($Respuesta as $res) {

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

            $PrimerTT = $recoveryoporcentajeoperacion['data']->SPrimerT;
            $SegundoTT = $recoveryoporcentajeoperacion['data']->SSegundoT;
            $TercerTT = $recoveryoporcentajeoperacion['data']->STercerT;
            $CuartoTT = $recoveryoporcentajeoperacion['data']->SCuartoT;
            $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

            $AnioAnteriorPlan=0;
            if($AnoAnteriorMont>0){
                $AnioAnteriorPlan=($res->AnioAnterior*100)/$AnoAnteriorMont;
            }else{
                $AnioAnteriorPlan=0;
            }

            $PrimerTT2=0;
            $SegundoTT2=0;
            $TercerTT2=0;
            $CuartoTT2=0;
            $AnoAnteriorMont2=0;

            $RespuestaPlanAnual = $Mcostodeptoventas->get_recobery_costodeptoVEstadoFinancieroFiltro();
            $PrimerTT2 = $RespuestaPlanAnual['data']->PrimerT;
            $SegundoTT2 = $RespuestaPlanAnual['data']->SegundoT;
            $TercerTT2 = $RespuestaPlanAnual['data']->TercerT;
            $CuartoTT2 = $RespuestaPlanAnual['data']->CuartoT;
            $AnoAnteriorMont2 = $RespuestaPlanAnual['data']->AnioAnterior;

            $Trimestre1=$recoveryoporcentajeoperacion['data']->PrimerT;
            $Trimestre2=$recoveryoporcentajeoperacion['data']->SegundoT;
            $Trimestre3=$recoveryoporcentajeoperacion['data']->TercerT;
            $Trimestre4=$recoveryoporcentajeoperacion['data']->CuartoT;
            

            $PlanAnual2=0;
            $valorprim2= $PrimerTT2/3;
            $valorseug2= $SegundoTT2/3;
            $valorter2= $TercerTT2/3;
            $valorcuatro2=$CuartoTT2/3;

            //para el año actual Plan
            $PlanAnual=0;
            $valorprim= $PrimerTT/3;
            $valorseug= $SegundoTT/3;
            $valorter= $TercerTT/3;
            $valorcuatro=$CuartoTT/3;
            $count=1;

            $PlanMes = 0;
            $PlanMesPorcn=0;

            $Mes2 = $this->get('Mes');
            if ($Mes2 < 10) {
                $Mes2 = '0' . $Mes2;
            }

            
            
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

            $PlanAnioPorcen =0;
            $AnioActual=0;
            $AnioActualPorcen= 0;

            for ($i=1; $i <=12 ;$i++)
            {
                if ($i <4){
                    $Plan = $valorprim;
                    $PlanAnual +=$valorprim;
                    $PlanAnual2 +=$res->PrimerT/3;
                    $PlanMes =$res->PrimerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre1;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <7){
                    $Plan = $valorseug;
                    $PlanAnual +=$valorseug;
                    $PlanAnual2 +=$res->SegundoT/3;
                    $PlanMes =$res->SegundoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre2;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else if($i <10 ){
                    $Plan = $valorter;
                    $PlanAnual +=$valorter;
                    $PlanAnual2 +=$res->TercerT/3;
                    $PlanMes =$res->TercerT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre3;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }
                else
                {
                    $Plan = $valorcuatro;
                    $PlanAnual +=$valorcuatro;
                    $PlanAnual2 +=$res->CuartoT/3;
                    $PlanMes =$res->CuartoT/3;
                    $PlanMesPorcen = ($PlanMes *100)/$Trimestre4;
                    $PlanAnioPorcen=( $PlanAnual2*100)/$PlanAnual;

                    $AnioActual += $res->MontoMes;

                    if($TotalFact>0 ){
                        $AnioActualProcen=( $AnioActual*100)/$TotalFact;
                    }else{
                        $AnioActualProcen=0;
                    }
                }

                if($count==$Mes){
                    break;
                }
                $count++;
            }

            $array = array(
                'Descripcion'         =>$res->Descripcion,

                'AnoAnteriorMont'       =>$AnoAnteriorMont,
                'PlanAnualFact'         =>$PlanAnual,
                'AnioActualFact'        =>$TotalFact,
                'PlanActualFact'        =>$Plan,
                'ActuaMesFact'          =>$TotalFact2,

                'AnioAnterior'        =>$res->AnioAnterior,
                'AnioAnteriorPlan'    =>$AnioAnteriorPlan,

                'PlanAnual'           =>$PlanAnual2,
                'PlanAnualPorcen'     =>$PlanAnioPorcen,
                'AnioActual'          =>$AnioActual,
                'AnioActualProcen'    =>$AnioActualProcen,

                "PlanMes"             =>$PlanMes,
                "PlanMesPorcen"       =>$PlanMesPorcen,
                "MesActual"           =>$res->MontoMes,
                'MesActualPorce'      =>$MesActualPorcen,
            );


            array_push($arraydatos,$array);

        }

		$data['CostoDO'] =  $arraydatos;
        

		$dataResp['IdEmpresa']=$IdEmpresa;
        $dataResp['IdSucursal']=$IdSucursal;
        $dataResp['Titulo']='Costo Depto. Oper';
        $dataResp['Anio']=$Anio;
        $dataResp['Mes']=$Mes;
        $dataResp['Lista']=$arraydatos;

    
        
        $pdf=new RptCostoDeptoOper("L",'mm','Letter');
        $pdf->setDatos($dataResp);
        $pdf->AddPage();
        //$pdf->HeaderG();
        $pdf->SetMargins(5,20,5);
        $pdf->contenido();
        ob_end_clean();
        $pdf->Output(); 
    }
}