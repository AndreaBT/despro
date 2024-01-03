<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccrmforecast extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Mcrmforecast');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmforecast= new Mcrmforecast();
        $Mcrmforecast->IdVendedor= $this->get('IdVendedor');
        $Mcrmforecast->IdSucursal= $IdSucursal;
        $Mcrmforecast->Anio=$this->get('Anio');
        $data=  $Mcrmforecast->get_recovery();

        $arreglo=array();

        for ($var =0; $var <4;$var ++)
        {
            $Monto=0;
            $Porcentaje="";
            $Descripcion="";
            if ($var==0)
            {
                $Monto=$data['data']->Uno;
                $Porcentaje="99 %";
                $Descripcion="Mes Actual";
            }
            if ($var==1)
            {
                $Monto=$data['data']->Dos;
                $Porcentaje="75 %";
                $Descripcion="+1 mes";
            }
            if ($var==2)
            {
                $Monto=$data['data']->Tres;
                $Porcentaje="50 %";
                $Descripcion="+2 mes";
            }
            if ($var==3)
            {
                $Monto=$data['data']->Cuatro;
                $Porcentaje="25 %";
                $Descripcion="No definido";
            }
            $Valores =array(
                    'IdForeCast'=>$data['data']->IdForeCast,
                    'Porcentaje'=>$Porcentaje,
                    'Monto'=>$Monto,
                    'Descripcion'=>$Descripcion
            );
            array_push( $arreglo,$Valores);
        }
       
        return $this->set_response([
            'status' => true,
            'data' => $arreglo,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
        $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        //$Lista= $this->post('Lista');

        $IdIdCompJ = str_replace(array('\t','\n','\"'), "", $this->post('Lista'));
        $Lista = json_decode($IdIdCompJ);
       
        $Monto1=$Lista[0]->Monto;
        $Monto2=$Lista[1]->Monto;
        $Monto3=$Lista[2]->Monto;
        $Monto4=$Lista[3]->Monto;
        if($Monto1=='')
        {
            $Monto1=0;
        }
        if($Monto2=='')
        {
            $Monto2=0;
        }
        if($Monto3=='')
        {
            $Monto3=0;
        }
        if($Monto4=='')
        {
            $Monto4=0;
        }

        $Mcrmforecast= new Mcrmforecast();
        $Mcrmforecast->IdVendedor= $this->post('IdVendedor');
        $Mcrmforecast->IdSucursal= $IdSucursal;
        $Mcrmforecast->Anio=$this->post('Anio');
        $Mcrmforecast->Uno=$Monto1;
        $Mcrmforecast->Dos= $Monto2;
        $Mcrmforecast->Tres=$Monto3;
        $Mcrmforecast->Cuatro=$Monto4;
        $Mcrmforecast->IdForeCast=$Lista[0]->IdForeCast;
        if ($Lista[0]->IdForeCast>0)
        {
            $Mcrmforecast->Update();
        }
        else
        {
            $Mcrmforecast->Insert();
        }

        return $this -> set_response([
            'status' => true,
            'data' =>  $Mcrmforecast,
            'message' => 'Se ha realizado correctamente.',
        ], REST_Controller:: HTTP_ACCEPTED);
    }
}