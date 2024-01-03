<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cconfigservicio extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mconfigservicio');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $objConfigservicio = new Mconfigservicio();


        $Facturable='';
        if ($this->get('Facturable')!='')
        {
           $Facturable =$this->get('Facturable');
        }
        $objConfigservicio->Facturable = $Facturable;
        // Paginación
        $rows =     $objConfigservicio->get_list();
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));


        $objConfigservicio->Tamano = $pager->PageSize;
        $objConfigservicio->Offset = $pager->Offset;
        $data['configservicio'] =  $rows;
        $data['pagination']= $pager;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}