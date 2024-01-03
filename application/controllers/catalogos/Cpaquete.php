<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;
use SebastianBergmann\Environment\Console;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpaquete extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpaquete');
        $this->load->model('Musuarios');
        $this->load->model('Mpaquetexsucursal');
        $this->load->model('Mpermisoxpaquete');
        $this->load->model('Mperfil');
        $this->load->model('Mrol');
        $this->load->model('Mtrabajador');

        setTimeZone($this->verification,$this->input);
    }

    public function findAllg_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $objPaquete = new Mpaquete();
        // Paginación
        $rows = $objPaquete->get_list();
        $rows2 = $objPaquete->get_list();
        $pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('1000'));

        $objPaquete->Tamano = $pager->PageSize;
        $objPaquete->Offset = $pager->Offset;
        $data['Paquetes'] = $rows2;
        $data['pagination'] = $pager;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $objPaquete = new Mpaquete();
        // Paginación
        $rows = $objPaquete->get_list();
        $rows2 = $objPaquete->get_list();
        $pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));
        $ListaPaqueteMenu = [];
        $ListaPaqueteSubMenu = [];

        $Lista = [];

        $Lis = array();
        foreach ($rows as $elemento) {
            if ($elemento->Tipo == 'SubMenu') {
                $rows = $elemento;
                array_push($ListaPaqueteSubMenu, $rows);
            }
        }

        foreach ($rows2 as $item) {
            if ($item->Tipo == 'Menu') {
                foreach ($ListaPaqueteSubMenu as $ItemSubMenu) {
                    if ('PAQ' . $ItemSubMenu->Asociado == $item->Clave) {
                        $item->Nombre = $item->Nombre . ' (' . $ItemSubMenu->Nombre . ')';
                        array_push($ListaPaqueteMenu, $item);
                    }
                }
            }
        }

        $objPaquete->Tamano = $pager->PageSize;
        $objPaquete->Offset = $pager->Offset;
        $data['Paquetes'] = $ListaPaqueteMenu;
        $data['pagination'] = $pager;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function PaquetexSucursal_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $objPaquete = new Mpaquete();
        $Id = (int) $this->get('IdSucursal');

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objPaquete->IdSucursal = $Id;
        }

        $objPaquete->Tipo = 'Menu';
        $response = $objPaquete->get_list();
        $contador = 0;


        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Perfil = $this->verification->getTokenData($this->input->request_headers('Authorization'))['Perfil'];
        $uniqueid = $this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $IdRol = 0;

        $obttabajador = new Mtrabajador();
        $obttabajador->IdUsuario = $uniqueid;
        $obj = $obttabajador->get_trabajadoruser();

        if($obj['status'])
        {
            $IdRol = $obj['data']->IdRol;
        }

        foreach ($response as $menu)
        {
            $opaquetexsucursal = new Mpaquetexsucursal();
            $opaquetexsucursal->IdPaquete = $menu->IdPaquete;
            $opaquetexsucursal->IdSucursal = $Id;
            $obj = $opaquetexsucursal->get_paqutexsucursal();


            $objPaquete = new Mpaquete();
            $objPaquete->Tipo = 'SubMenu';
            $objPaquete->Asociado = $menu->IdPaquete;
            $submenu = $objPaquete->get_list();

            $response[$contador]->Submenu = $submenu;
            $response[$contador]->Estatus = $obj['status'];

            $clase = 'nav-link disabled';

            if ($IdEmpresa > 0)
            {
                if ($Perfil == 'Admin' || $Perfil == 'ROOT' || $Perfil == 'ADMIN') {

                    if ($obj['status']) {
                        $clase = 'nav-link';
                    }

                    $response[$contador]->Clase = $clase;
                }
                else
                {
                    //Vendedor1@gmail.com
                    $objPxP = new Mpermisoxpaquete();
                    $objPxP->IdPaquete     = $menu->IdPaquete;
                    $objPxP->IdSucursal    = $IdSucursal;
                    $objPxP->IdPerfil      = $IdRol;
                    $exist = $objPxP->get_exist();

                    if ($obj['status'] && $exist) {
                        $clase = 'nav-link';
                    } else {
                        $response[$contador]->Estatus = false;
                    }

                    $response[$contador]->Clase = $clase;
                }
            } else {

                if ($obj['status']) {
                    $clase = 'nav-link';
                }

                $response[$contador]->Clase = $clase;
            }

            $contador++;
        }

        $data['paquetexsucursal'] = $response;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'Authorization' => $this->verification->getTokenData($this->input->request_headers('Authorization')),
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}