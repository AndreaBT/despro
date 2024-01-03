<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script

use phpDocumentor\Reflection\Types\Integer;
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Clevantamiento extends REST_Controller
{
    public $RutaFile='assets/files/temporal/';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('levantamiento/Mlevantamiento');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mhoraslaborales');
        $this->load->model('Mcotizacion_servicio');

        $this->load->library('Mail');
        
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objServicio = new Mlevantamiento();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->Folio=$this->get('Nombre');
        $objServicio->IdTrabajador=$this->get('IdTrabajador');
        $objServicio->Tipo_Serv=$this->get('IdTipoServicio');
        $objServicio->EstadoS='FINALIZADA';//$this->get('EstatusS');
        $objServicio->Fecha_I=dateformato($this->get('FechaI'));
        $objServicio->Fecha_F=dateformato($this->get('FechaF'));  
        $objServicio->RegEstatus='A'; 

        // Paginación
        $rows = $objServicio->get_list();
         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }

        $objServicio->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objServicio->Tamano = $pager->PageSize;
        $objServicio->Offset = $pager->Offset;

        $data['servicio'] =$objServicio->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findOne_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objServicio  = new Mservicio();

        $Id = (int) $this->get('IdServicio');

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objServicio->IdServicio = $Id;
        }
        
        $response =$objServicio->get_servicio();
        
        $Mclientesucursal=new Mclientesucursal();
        $Mclientesucursal->IdClienteS=$response['data']->IdClienteS;
        $resclisucursal=$Mclientesucursal->get_cliente();
        
        if ($response['status']) {
            $valores= $this->CalcularValores($Id,$response['data']->Distancia,$response['data']->Velocidad);
            $response['data']->ManoObraT=$valores['CostoMO'];
            $response['data']->BurdenTotal=$valores['Burden'];
            $response['data']->CostoV=$valores['CostoVH'];
            
            $data['servicio'] = $response['data'];
            $data['clientesuc'] = $resclisucursal['data'];
            $data['valores'] = $valores;
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function attendservice()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
    }

    public function CalcularValores($Id,$Distancia,$Velocidad)
    {
        $oMfechaservicio = new Mfechaservicio();
        $oMfechaservicio->IdServicio=$Id;
        $row= $oMfechaservicio->get_list();
       
        $BurdenTotal =0 ;
        $CostoManoObra =0; 
    
        foreach ($row as $elemento)
        {
            $LisHoras=0;
            $minutos_iniciales=0;
            
            $HoraInit=substr($elemento->HoraInicio, 0, -3);
            $HoraFin=substr($elemento->HoraFin, 0, -3);
            if ($HoraFin=='00:00')
            {
                $HoraFin ='24:00';
            }


            $minutos_iniciales+=Calcular_Minutos($HoraInit,$HoraFin);
            if ($minutos_iniciales<0)
            {
                $minutos_iniciales=$minutos_iniciales*-1;
            }
            $minuto = $minutos_iniciales%60;
            $hora = ($minutos_iniciales-($minutos_iniciales%60))/60;

            if($hora==0 && $minuto==0){
                $LisHoras=0;
            }else{
                $LisHoras =$hora.'.'.$minuto;    
            }

            $oMtrabajador = new Mtrabajador();
            $oMtrabajador->IdTrabajador=$elemento->IdTrabajador;
            $datoemp= $oMtrabajador->get_trabajador();       
                     
            $BurdenT =  ($LisHoras  * $datoemp['data']->CostoAnual);
            $CostoManoO = ($LisHoras * $datoemp['data']->CostoHora);
            
            $Distancia=$Distancia;
            $Velocidad=$Velocidad;
            
            if ($Distancia  =='' || $Distancia =='0')
            {
                $Distancia =0;
            }
            if ($Velocidad =='' || $Velocidad =='0')
            {
                $Velocidad =1;
            }
        
            //para sacar el costo de traslado es distancia entre velocidad x 2 x el costo hora trabajador
            $CostoTraslado= (($Distancia/$Velocidad)*2) * $datoemp['data']->CostoHora;
            $BurdenTotal += intval( $BurdenT) ;
            $CostoManoObra +=intval($CostoManoO) + intval($CostoTraslado); 
        }
        
        //vehiculos
        
        $oMvehiculoservicio = new Mvehiculoservicio();
        $oMvehiculoservicio->IdServicio=$Id;
        $rowv= $oMvehiculoservicio->get_list();
        $CostoVehiculo =0;
        
        foreach($rowv as $element)
        {
            $ovehiculo =new Mvehiculo();
            $ovehiculo->IdVehiculo =$element->IdVehiculo;
            $data=$ovehiculo->get_vehiculo();

            $CostoVehiculo +=($Distancia * 2) * $data['data']->CostoAnual;
        }
        
        $valores['Burden']=$BurdenTotal;
        $valores['CostoMO']=$CostoManoObra;
        $valores['CostoVH']=$CostoVehiculo;
        return $valores;   
    }

    public function HorasDisponibles_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $FechaBusqueda=$this->get('FechaBusqueda');
        if ($FechaBusqueda=='')
        {
            $FechaBusqueda =date('Y-m-d');
        }
        
        $objHoraslaborales = new Mhoraslaborales();
        $objHoraslaborales->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $response=$objHoraslaborales->get_horaslaborales();
         
        $Hora_I=explode(':',$response['data']->Hora_I);
        $Hora_F=$response['data']->Hora_F;
        $horaactual=date('H:i');
        $HoraI=$Hora_I[0];
        $HoraMin=$Hora_I[1];
        
        //PARA BUSQUEDA DE SERVICIOS 
        $arrHorasReales=array();
        //VISTA
        $arrhoradata=array();
        
        ##Este arreglo busca por 24 hroas
        array_push($arrHorasReales,$response['data']->Hora_I);
      
        $classhora=$this->get_class_hora($response['data']->Hora_I,$horaactual);
        $arrhora=array("hora"=>$response['data']->Hora_I,'class'=>$classhora);
        array_push($arrhoradata,$arrhora);
           
        $HoraFinal=$Hora_F;
        for($i=0;$i<=90;$i++)
        {
            if($HoraMin==30){
                $HoraI ++;  
                $HoraMin='00';  
            }else{
                $HoraMin ='30';
            }
            $HoraFinal=$HoraI.':'.$HoraMin;
            $HoraFinalReal=$HoraI.':'.$HoraMin;
            
            if($HoraFinal>=24){
                //$HoraFinal='00:00';
                $HoraFinal='24:00';
            }
            
           $classhora=$this->get_class_hora($HoraFinal,$horaactual);
           $arrhora=array("hora"=>$HoraFinal,'class'=>$classhora);
           array_push($arrhoradata,$arrhora);
           array_push($arrHorasReales,$HoraFinalReal);
           
            if($HoraFinal==$Hora_F){ 
                break;
            }

            if($HoraFinal=='24:00'){
                break;
            }
        }

        $data['horaslaborales'] =$arrhoradata;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function get_class_hora($hora,$horaactual){
        //devuelve classe de la hora actual (indica la fleccha en las horas en la tabla)
        $Hora_I=explode(':',$hora);
        $HoraI=$Hora_I[0];
        $HoraMin=$Hora_I[1];
        
        if($HoraMin==30){
            $HoraI ++;  
            $HoraMin='00';  
        }else{
            $HoraMin ='30';
        }
        if($HoraI<10){
            $HoraI=$HoraI;
        }
        
        $HoraFinal=$HoraI.':'.$HoraMin;
        
        if($HoraFinal>=24){
            $HoraFinal='00:00';
        }
        $class='';
        
         $horaactual = strtotime($horaactual);
         $hora = strtotime($hora);
         $HoraFinal = strtotime($HoraFinal);
        
        if($horaactual>=$hora && $horaactual<$HoraFinal){
            $class='bg-table';
        }
        
        //echo "( $horaactual>=$hora   Y   $horaactual<$HoraFinal ==$class) ";
        return $class;
    }

    public function UpdatestatusCot_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objServicio = new Mcotizacion_servicio();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->Estatus=$this->get('Estatus');
        $objServicio->IdCotizacionServicio=$this->get('IdCotizacionServicio'); 
        $objServicio->RegEstatus='A'; 
        $row = $objServicio->updatestatus();

        return $this->set_response([
            'status' => true,
            'data' => 'Success',
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}