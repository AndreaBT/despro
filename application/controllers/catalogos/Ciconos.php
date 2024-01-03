<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ciconos extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Miconos');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $objIconos = new Miconos();
        // Paginación
        $rows =   $objIconos->get_list();
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));


        $objIconos ->Tamano = $pager->PageSize;
        $objIconos  ->Offset = $pager->Offset;
        $data['iconos'] =  $rows;
        $data['ruta'] =   base_url().'assets/files/tpiconos/';
        $data['pagination']= $pager;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}