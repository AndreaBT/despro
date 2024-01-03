<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccotizacion extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        #Obligatorios
        $this->load->model('Mempresa');
        $this->load->model('Msucursal');
        
        #Datos del modulo
        $this->load->model('Mcotizacion_servicio');
        $this->load->model('Mservicio_manode_obra');
        $this->load->model('Mservicio_material');
        $this->load->model('Mservicio_miscelaneos');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mclientes');

        setTimeZone($this->verification,$this->input);
    }

    public function Cotizacion_get()
    {
        $this->load->library('reports/ReporteCotizacion');
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $Id=$this->get('key');
        
        if($Id<=0){
            echo 'error';
            return false;
        }
        
        $Mcotizacion_servicio=new Mcotizacion_servicio();
        $Mcotizacion_servicio->IdCotizacionServicio=$Id;
        $response=$Mcotizacion_servicio->get_recovery();
    
        if($response['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Titulo']='Cotización';
            $dataResp['Folio']=$response['data']->Folio;
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$response['data']->IdCliente;
            $ressuc=$oMclientesucursal->get_cliente();
            
            $Mclientes=new Mclientes();
            $Mclientes->IdCliente=$ressuc['data']->IdCliente;
            $rescliente=$Mclientes->get_clientes();
            
            $oMservicio_material=new Mservicio_material();
            $oMservicio_material->IdCotizacionServicio=$Id;
            $oMservicio_material->RegEstatus='A';
            $rowmateriales=$oMservicio_material->get_list();
            
            $oMservicio_manode_obra=new Mservicio_manode_obra();
            $oMservicio_manode_obra->IdCotizacionServicio=$Id;
            $oMservicio_manode_obra->RegEstatus='A';
            $listamanoObra=$oMservicio_manode_obra->get_list();
            
            $oMservicio_miscelaneos=new Mservicio_miscelaneos();
            $oMservicio_miscelaneos->IdCotizacionServicio=$Id;
            $oMservicio_miscelaneos->RegEstatus='A';
            $miselaneo=$oMservicio_miscelaneos->get_list();
            
            $TotalGarantia=0;
            if($response['data']->Garantia>0){
                $TotalGarantia=($response['data']->totalMateriales+$response['data']->totalManoDeObra+$response['data']->TotalCostoKm+$response['data']->CostoManoObraD+$response['data']->totalMiscelaneos+$response['data']->CostoBurden) *($response['data']->Garantia/100);    
            }
            
            $dataResp['cotizacion']=$response['data'];
            $dataResp['clientesucursal']=$ressuc['data'];
            $dataResp['clientes']=$rescliente['data'];
            $dataResp['materiales']=$rowmateriales;
            $dataResp['manoobra']=$listamanoObra;
            $dataResp['miselaneo']=$miselaneo;
            $dataResp['TotalGarantia']=$TotalGarantia;
            
            /*$pdf=new RptCotizacion("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->SetAutoPageBreak(true,20);
            $pdf->SetMargins(10,30,10);
            $pdf->AddPage();
            $pdf->SetMargins(5,20,5);
            $pdf->contenido();
            $pdf->Output(); */
			
			
			$pdf = new ReporteCotizacion("P",'mm','LETTER',true,'UTF-8');
			// set document information
            $Folio = $response['data']->Folio;
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Desprosoft');
			$pdf->SetTitle("Cotizacion_".$Folio);


			//Activar o Desactivar la cabecera y/o Pie de pagina
			$pdf->setPrintHeader(true);
			$pdf->setPrintFooter(true);

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(10, 30, 10);   # esto modifica si  hay margenes o no $left, $top, $right=-1, $keepmargins=false
			#$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  # margen de donde empieza el texto
			#$pdf->SetFooterMargin(25);              # margen de donde empieza el footer

			// set auto page breaks
			$pdf->SetAutoPageBreak(true,25); # Punto de quiebre para el final de la hoja

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->setDatos($dataResp);
			$pdf->Documento();


			$pdf->Output("Cotizacion_".$Folio,'I');
        }
    }
}