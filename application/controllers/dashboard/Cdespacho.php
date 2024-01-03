<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cdespacho extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mservicio');
        $this->load->model('Mvehiculo');
        $this->load->model('Mtiposervicio');
        
        setTimeZone($this->verification,$this->input);
    }

    public function HorasP_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $IdTrabajador=$this->get('IdTrabajador');
        
        $obj= new Mservicio();

        $obj->IdTrabajador=$IdTrabajador;
        $obj->IdSucursal=$IdSucursal;
        $obj->EstadoS='CERRADA';
        $obj->Facturable='s';       
        
        $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
        $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));
        
        $data=$obj->get_TotalHoras();
        $horasT=0;
        if($data['status']){
            $horasT=$data['TotHorasTrabajo'];
        }
        
        $HoraTrabajoSemanal=100;

        $HoraTrabajoSemanal=$this->get('HorasTS');
        $HoraPS=$this->get('HorasTS');//$otrabajador->HorasPS;
        
        if($HoraPS<=0){
            $HoraPS=0;
            $HoraTrabajoSemanal=50;
        }

        $HoraPSmenos=$HoraPS-5;

        
        $datafirst['HoraPSmenos']=$HoraPSmenos;
        $datafirst['HoraTrabajoSemanal']=$HoraTrabajoSemanal;
        $datafirst['HoraPS']=$HoraPS;
        $datafirst['horasT']=number_format($horasT,2,'.','');
        
        
        #GRAFICA 2
        

        $HoraPSmenos2=0;
        $horasT2=0;
        
        $IdTrabajador2=$this->get('IdTrabajador2');
        $HoraTrabajoSemanal2=$this->get('HorasTS2');//$otrabajador->HorasTS;
        $HoraPS2=$this->get('HorasPS2');//$otrabajador->HorasPS;
        
        $obj= new Mservicio();
        $obj->IdTrabajador=$IdTrabajador2;
        $obj->IdSucursal=$IdSucursal;
        $obj->EstadoS='CERRADA';
        $obj->Facturable='s';       
        
        $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
        $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));
        $data=$obj->get_TotalHoras();
        
        if($data['status']){
            $horasT2=$data['TotHorasTrabajo'];
        }
        
        $numTrabajadores=$this->get('numTrabajadores');
        
        if($IdTrabajador2>0){
            $HoraPSmenos2=$HoraPS-5;
        }else{
            if(floatval($horasT2)==0 && $numTrabajadores==0){
                $horasT2=0;
            }else{                    
                $horasT2=floatval($horasT2)/$numTrabajadores;            
            }
            
            $HoraPS2=$HoraPS;    
        }

        $datasecond['HoraPSmenos2']=$HoraPSmenos2;
        $datasecond['HoraTrabajoSemanal2']=$HoraTrabajoSemanal;
        $datasecond['HoraPS']=$HoraPS2;
        $datasecond['horasT2']=number_format($horasT2,2,'.','');
        
        return $this->set_response([
            'status' => true,
            'datafirst'=>$datafirst,
            'datasecond'=>$datasecond,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    //PIE chasrt
    public function ServFac_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $IdTrabajador=$this->get('IdTrabajador');
        
        $obj= new Mservicio();
        $obj->IdSucursal=$IdSucursal;
        $obj->EstadoS='CERRADA';
        $obj->Facturable='s';        
        $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
        $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));
        
        $data=$obj->get_TotalHoras();
        
        $Facturable=0;
        if($data['status']){
            $Facturable=$data['TotalServicio'];    
        }
        
        
        $obj= new Mservicio();
        $obj->IdSucursal=$IdSucursal;
        $obj->EstadoS='CERRADA';
        $obj->Facturable='n';        
        $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
        $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));
        
        $data=$obj->get_TotalHoras();
        $NoFacturable=0;
        if($data['status']){
            $NoFacturable=$data['TotalServicio'];    
        }
        
        if($Facturable==0 && $NoFacturable==0){
            $Facturable=100;
        }

        
        return $this->set_response([
            'status' => true,
            'Facturable'=>$Facturable,
            'NoFacturable'=>$NoFacturable,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    #GRAFICA DE VEHICUKOS 
    
    public function VentaxVehiculo_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        
        $obj= new Mservicio();
        $obj->IdSucursal=$IdSucursal;
        if($this->get('Fecha_I')!='' && $this->get('Fecha_F')!=''){
            $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
            $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));            
        }
        $obj->TipoVehiculo=$this->get('TipoVehiculo');
        
        $data=$obj->get_TotalVehiculo();
        
        $oMvehiculo=new Mvehiculo();
        $oMvehiculo->IdSucursal=$IdSucursal;
        $oMvehiculo->TipoVehiculo=$this->get('TipoVehiculo');
        $oMvehiculo->RegEstatus='A';
        $row=$oMvehiculo->get_list();
        
       $category=array();
       $dataset=array();
       $paletteColors=array('#ff8f00','#5d62b5','#29c3be','#ffff00','#c6ff00','#f2726f','#ff5722','#64dd17','#0091ea','#7e57c2','#1de9b6');

       $contador=0;
       $contadorColor=0;
      
        foreach($row as $element){
            $clave = array_search($element->IdVehiculo,array_column($data, 'IdVehiculo') ); // $clave = 2;
            
            $arraycat=array();
            $arraycat['label']=$element->Categoria;
            
            
            $arraydataset=array();
            $arraydataset['color']=$paletteColors[$contadorColor];
            $arraydataset['value']=0;
            if($clave>=0 && $clave>=$contador){
                if(isset($data[$clave]->Value)){
                    $arraydataset['value']=$data[$clave]->Value;    
                }   
            }
            
            array_push($category,$arraycat);
            array_push($dataset,$arraydataset);
            
            $contador ++;
            $contadorColor ++;
            if($contadorColor>=count($paletteColors)){
                $contadorColor=0;
            }
        }
         
        
        return $this->set_response([
            'status' => true,
            'category'=>$category,
            'data'=>$dataset,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    //HORAS POR SERVICIO
    public function ServicioxHora_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        
        $obj= new Mservicio();
        $obj->IdSucursal=$IdSucursal;
        if($this->get('Fecha_I')!='' && $this->get('Fecha_F')!=''){
            $obj->Fecha_I=date("Y-m-d", strtotime($this->get('Fecha_I')));
            $obj->Fecha_F=date("Y-m-d", strtotime($this->get('Fecha_F')));            
        }
       
        
        $rowdata=$obj->get_list_ServxHoras();
        
    
        $oMtiposervicio=new Mtiposervicio();
        $oMtiposervicio->IdSucursal=$IdSucursal;
        $oMtiposervicio->RegEstatus='A';
        $row=$oMtiposervicio->get_list();
        
        $category=array();
        $dataset=array();
        $paletteColors=array('#ff8f00','#5d62b5','#29c3be','#ffff00','#c6ff00','#f2726f','#ff5722','#64dd17','#0091ea','#7e57c2','#1de9b6');

        $contador=0;
        $contadorColor=0;
        
        foreach($row as $element){
            $clave = array_search($element->IdTipoSer,array_column($rowdata, 'IdTipoSer') ); // $clave = 2;
           
            $arraycat=array();
            $arraycat['label']=$element->Concepto;
            
            
            $arraydataset=array();
            $arraydataset['color']=$paletteColors[$contadorColor];
            $arraydataset['value']=0;

            if($clave>=0 && $clave>=$contador){
             
                if(isset($rowdata[$clave]->Value)){
                    $arraydataset['value']=$rowdata[$clave]->Value;  
                }   
            }
            
            array_push($category,$arraycat);
            array_push($dataset,$arraydataset);
            
            $contador ++;
            $contadorColor ++;
            if($contadorColor>=count($paletteColors)){
                $contadorColor=0;
            }
        }
        
        
        return $this->set_response([
            'status' => true,
            'category'=>$category,
            'data'=>$dataset,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}