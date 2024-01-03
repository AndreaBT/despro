<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cperfil extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mperfil');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $Mperfil = new Mperfil();
        $Mperfil ->IdPerfil= $this->get('IdPerfil');
        $Mperfil->RegEstatus = 'A';
        // Paginación
        $rows = $Mperfil->get_list();

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $Mperfil->Tamano = $pager->PageSize;
        $Mperfil->Offset = $pager->Offset;

        $data['perfil'] =$Mperfil->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $oMperfil  = new Mperfil();

        $Id = (int) $this->get('IdPerfil');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMperfil ->IdPerfil = $Id;
        }
        $response =$oMperfil ->get_recovery();
        if ($response['status']) {
            $data['perfil'] = $response['data'];
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
    
}