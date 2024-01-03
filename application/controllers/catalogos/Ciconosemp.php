<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ciconosemp extends REST_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Miconosemp');
        
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
      
        $oMiconosemp=new Miconosemp();
       
        $data['iconosemp'] = $oMiconosemp->get_list();
        $data['ruta'] = base_url().'assets/files/iconemp/';

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}