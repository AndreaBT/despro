<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cfunciones extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mfolio');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
       
        $ListaAnios= listaAnios();
        $Meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
       'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        return $this->set_response([
            'status' => true,
            'ListaAnios' => $ListaAnios,
            'ListaMeses' => $Meses,
            'AnioActual' => date('Y'),
             'MesActual' => date('m'),
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}