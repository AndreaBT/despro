<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ciconos_eq extends REST_Controller
{
    public $rutaiconos='assets/files/iconos_eq/';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Miconos_eq');
        setTimeZone($this->verification,$this->input);
    }

    public function Lista_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $Miconos_eq=new Miconos_eq();
        $Miconos_eq->RegEstatus='A';
        $Miconos_eq->Tipo=$this->get('Tipo');
        $row=$Miconos_eq->get_list();
        foreach($row as $datares){
           $datares->Imagen=base_url().$this->rutaiconos.$datares->Imagen;
        }
        $data['iconos']=$row;
        
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}