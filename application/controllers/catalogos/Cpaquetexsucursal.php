<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpaquetexsucursal extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpaquetexsucursal');
        setTimeZone($this->verification,$this->input);
    }

    public function Add_post() {
        // Valid Token
        if (!$this->verification->validToken($this ->input-> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
        $v = new Valitron\Validator([
            'IdSucursal' => $this -> post('IdSucursal')  
        ]);
        $v -> rule('required', [
            'IdSucursal'
        ]) -> message('El campo {field} es requerido.');
        if ($v -> validate()) {
            $Id = $this -> post('IdSucursal');
            
            $Paquetes =$this -> post('ListaPaquetes');
            
            $OMpaquetexsucursal = new Mpaquetexsucursal();
            $OMpaquetexsucursal->IdSucursal=$Id;
            $OMpaquetexsucursal->delete();
            
            foreach ($Paquetes as $elemtn)
            {
                if ($elemtn['Estatus']==true)
                {
                    $OMpaquetexsucursal = new Mpaquetexsucursal();
                    $OMpaquetexsucursal->IdSucursal=$Id;
                    $OMpaquetexsucursal->IdPaquete=$elemtn['IdPaquete'];
                    $OMpaquetexsucursal->insert();
                }
            
            
            }
    
        return $this -> set_response([
            'status' => true,
            'message' => 'Se ha agregado correctamente.',
            'typemsg'=>1,
        ], REST_Controller:: HTTP_CREATED);
        
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
                'typemsg'=>2,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }    
    }
}