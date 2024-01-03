<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cprocesovendedor extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Mcrmtipoproceso');
        $this->load->model('crm/Mprocesovendedor');

        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmtipoproceso = new Mcrmtipoproceso();
        $Mcrmtipoproceso->IdSucursal = $IdSucursal;
        $Mcrmtipoproceso->IdTrabajador = $this->get('IdTrabajador');

        $data['asignados'] = $Mcrmtipoproceso->get_list_asignados();


        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Listasignados_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmtipoproceso = new Mcrmtipoproceso();
        $Mcrmtipoproceso->IdSucursal = $IdSucursal;
        $Mcrmtipoproceso->IdTrabajador = $this->get('IdTrabajador');

        $data['asignados'] =  $Mcrmtipoproceso->get_list_asignadospipe();
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function asignarproceso_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $IdIdCompJ = str_replace(array('\t', '\n', '\"'), "", $this->post('Lista'));
        $Lista = json_decode($IdIdCompJ);

        $valor = count($Lista);
        $oMprocesovendedor = new Mprocesovendedor();
        $oMprocesovendedor->IdTrabajador = $this->post('IdTrabajador');
        $oMprocesovendedor->delete();

        if ($valor > 0) {
            foreach ($Lista as $element) {
                if ($element->Estatus == "true") {
                    $oMprocesovendedor = new Mprocesovendedor();
                    $oMprocesovendedor->IdTipoProceso = $element->IdTipoProceso;
                    $oMprocesovendedor->IdTrabajador = $this->post('IdTrabajador');
                    $oMprocesovendedor->Insert();
                }
            }
        }

        return $this->set_response([
            'status' => true,
            'message' => 'Se ha actualizado correctamente.',
        ], REST_Controller::HTTP_ACCEPTED);
    }
}