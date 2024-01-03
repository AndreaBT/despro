<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cdespacho extends REST_Controller
{
    public $Ruta;
    public function __construct()
    {
        parent::__construct();
        $this->Ruta='assets';
        
        
        $this->load->model('Mempresa');
        $this->load->model('Msucursal');
        
        $this->load->model('Mservicio');
        $this->load->model('Mhoraslaborales');
        $this->load->model('Mtrabajador');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mfechaservicio');
        $this->load->model('Mequipocomentario');
        $this->load->model('Mimagenequipo2');
        $this->load->model('Mfirmas');
        
        setTimeZone($this->verification,$this->input);
    }

    public function createimage_get(){

        $oMfirmas= new Mfirmas();
        $oMfirmas->IdServicio=1307;//$this->get('IdServicio');
        $datafirmas= $oMfirmas->get_recovery();

        

        Base64ToPath2($datafirmas['data']->Firma,'Ejemplo',1,$this->Ruta);
    }
	
	public function servicio2_get()
    {
        $this->load->library('reports/Servicio');
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $IdServicio=$this->get('IdServicio');
        
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();
        if($responsedes['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Folio']=$responsedes['data']->Folio;
            $dataResp['Titulo']='Reporte de Servicios';
            
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();
            
            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();
            
            $oMequipocomentario=new Mequipocomentario();
            $oMequipocomentario->IdServicio=$IdServicio;
            $oMequipocomentario->Permitir="s";
            $row=$oMequipocomentario->get_list();
            
            $oMfirmas= new Mfirmas();
            $oMfirmas->IdServicio=$IdServicio;
            $datafirmas= $oMfirmas->get_recovery();
			
			$trajoimagen = false; 
            if($datafirmas['status'])
            {
                $trajoimagen = true; 
            }
            
            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();
            
            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$responsefecha['data'];
            $dataResp['equipocomentario']=$row;
            $dataResp['firma']=$datafirmas['data'];
            $dataResp['personal']=$datapersonal['data'];
			$dataResp['statusimg']=$trajoimagen;
            
            
            //$this->response($dataResp);
            $pdf=new Servicio("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            //Header
         
            $pdf->SetMargins(5,20,5);
            $pdf->dasto_servicioG();
            $pdf->contenido();
            $pdf->Output(); 
        }
        
     
    }

    public function servicio_get()
    {
        $this->load->library('reports/Servicio');
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $IdServicio=$this->get('IdServicio');
        
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();
        if($responsedes['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Folio']=$responsedes['data']->Folio;
            $dataResp['Titulo']='Reporte de Servicios';
            
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();
            
            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();
            
            $oMequipocomentario=new Mequipocomentario();
            $oMequipocomentario->IdServicio=$IdServicio;
            $oMequipocomentario->Permitir="s";
            $row=$oMequipocomentario->get_list();
            
            $oMfirmas= new Mfirmas();
            $oMfirmas->IdServicio=$IdServicio;
            $datafirmas= $oMfirmas->get_recovery();
			$trajoimagen = false; 
            if($datafirmas['status'])
            {
                $trajoimagen = true; 
            }
            
            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();
            
            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$responsefecha['data'];
            $dataResp['equipocomentario']=$row;
            $dataResp['firma']=$datafirmas['data'];
            $dataResp['personal']=$datapersonal['data'];
			$dataResp['statusimg']=$trajoimagen;
            
            
            //$this->response($dataResp);
            $pdf=new Servicio("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            //Header
         
            $pdf->SetMargins(5,20,5);
            $pdf->dasto_servicioG();
            $pdf->contenido();
            $pdf->Output(); 
        }
        
     
    }

    public function servicioevidenciaEjemplo_get()
    {
        $this->load->library('reports/ServicioEvidenciaEjemplo');
        $IdServicio=$this->get('IdServicio');
        $IdEquipo=$this->get('IdEquipo');
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
       	$IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();
        if($responsedes['status']){
            #obtener datos del token **datos obligtorios
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Titulo']='Reporte Fotográfico';
            $dataResp['Folio']=$responsedes['data']->Folio;
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();
            
            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();

            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();
            
           
            $oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->IdEquipo=$IdEquipo;
            $oMimagenequipo2->Mostrar ="s";
            $row=$oMimagenequipo2->get_listImgEquip();

            $oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->Mostrar ="s";
            $row2=$oMimagenequipo2->get_list2();
            
            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$responsefecha['data'];
            $dataResp['row']=$row;
            $dataResp['row2']=$row2;
            $dataResp['personal']=$datapersonal['data'];
			
			//var_dump($dataResp['row']);
        
            $pdf=new ServicioEvidenciaEjemplo("P",'mm','letter',true,'UTF-8');
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Desprosoft');
			$pdf->SetTitle('ReporteFotografico');
			
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
			$pdf->SetAutoPageBreak(true,30); # Punto de quiebre para el final de la hoja

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			
			$pdf->setDatos($dataResp);
			$pdf->contenido();
			
            $pdf->Output();
        }
    }
    
    public function servicioevidencia_get()
    {
        $this->load->library('reports/ServicioEvidencia');
        $IdServicio=$this->get('IdServicio');
        $IdEquipo=$this->get('IdEquipo');
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();

        if($responsedes['status'])
        {
            #obtener datos del token **datos obligtorios
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Titulo']='Reporte Fotográfico';
            $dataResp['Folio']=$responsedes['data']->Folio;
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();
            
            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();

            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();
            
           
            $oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->IdEquipo=$IdEquipo;
            $oMimagenequipo2->Mostrar ="s";
            $row=$oMimagenequipo2->get_listImgEquip();

            $oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->Mostrar ="s";
            $row2=$oMimagenequipo2->get_list2();
            
            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$responsefecha['data'];
            $dataResp['row']=$row;
            $dataResp['row2']=$row2;
            $dataResp['personal']=$datapersonal['data'];
        
            $pdf=new ServicioEvidencia("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            $pdf->SetMargins(5,20,5);
            $pdf->dasto_servicioG();
            $pdf->contenido();
            $pdf->Output();
        }
    }
    
    
    public function serviciomail_get()
    {
        $this->load->library('reports/ServicioEmail');
        //$IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        //$IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $IdEmpresa=1;
        $IdSucursal=1;
        $IdServicio=$this->get('IdServicio');
        
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();
        if($responsedes['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Folio']=$responsedes['data']->Folio;
            $dataResp['Titulo']='Reporte de Servicios';
            
            
            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();
            
            
            
            $oMservicio=new Mservicio();
            $oMservicio->IdServicio=$IdServicio;
            $rowserv=$oMservicio->list_serviciosreferences();
            
            $ListaFechas=array();
            foreach ($rowserv as $element)
            {
                $oMfechaservicio =new Mfechaservicio();
                $oMfechaservicio->IdServicio =$element->IdServicio;
                $responsefecha = $oMfechaservicio->get_recovery();
                if ($responsefecha['status'])
                {
                    array_push($ListaFechas,$responsefecha['data']);
                }
               
            }
         
            
            $oMfirmas= new Mfirmas();
            $oMfirmas->IdServicio=$IdServicio;
           $datafirmas= $oMfirmas->get_recovery();
            
            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$ListaFechas;
            $dataResp['firma']=$datafirmas['data'];
            
            $pdf=new ServicioEmail("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->SetAutoPageBreak(true,15);
            $pdf->AddPage();
            //Header
         
            $pdf->SetMargins(5,20,5);
            //$pdf->dasto_servicioG();
            $pdf->contenido();
            $pdf->Output(); 
        }
        
     
    }

}