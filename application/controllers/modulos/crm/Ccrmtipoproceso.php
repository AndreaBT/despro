<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccrmtipoproceso extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Mcrmtipoproceso');
        $this->load->model('crm/Mcrmproceso');
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
        $Mcrmtipoproceso->Nombre = $this->get('Nombre');
        $Mcrmtipoproceso->IdSucursal = $IdSucursal;


        // Paginación
        $rows =  $Mcrmtipoproceso->get_list();
        $Entrada = 10;
        if ($this->get('Entrada') != '') {
            $Entrada = $this->get('Entrada');
        }

        $Mcrmtipoproceso->Limit = $Entrada;

        $pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

        $Mcrmtipoproceso->Tamano = $pager->PageSize;
        $Mcrmtipoproceso->Offset = $pager->Offset;

        $data['tipoproceso'] = $Mcrmtipoproceso->get_list();
        $data['pagination'] = $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $Mcrmtipoproceso = new Mcrmtipoproceso();

        $Mcrmtipoproceso->IdTipoProceso = $Id;

        $response = $Mcrmtipoproceso->get_recovery();

        if ($response['status']) {

            if ($Mcrmtipoproceso->delete()) {

                return $this->set_response([
                    'status' => true,
                    'message' => 'Se ha eliminado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al eliminar la información.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            return $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $Mcrmtipoproceso = new Mcrmtipoproceso();

        $Id = (int) $this->get('IdTipoProceso');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $Mcrmtipoproceso->IdTipoProceso = $Id;
        }
        $response =  $Mcrmtipoproceso->get_recovery();
        if ($response['status']) {
            $data['tipoproceso'] = $response['data'];
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

    public function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Tipo_Servicio' => $this->post('IdConfigS')

        ]);
        $v->rule('required', [
            'Nombre',
            'Tipo_Servicio'
        ])->message('El campo {field} es requerido.');

        if ($v->validate()) {
            $IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this->post('IdTipoProceso');

            $Mcrmtipoproceso = new Mcrmtipoproceso();
            $Mcrmtipoproceso->IdTipoProceso = $Id;
            $Mcrmtipoproceso->Nombre = $this->post('Nombre');
            $Mcrmtipoproceso->IdConfigS = $this->post('IdConfigS');
            $Mcrmtipoproceso->RegEstatus = "A";
            $Mcrmtipoproceso->IdSucursal = $IdSucursal;
            $Mcrmtipoproceso->FechaReg = date('Y-m-d');
            $Mcrmtipoproceso->FechaMod = date('Y-m-d H:i:s');

            if ($Mcrmtipoproceso->IdConfigS <= 0) {
                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al agregar a la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

            if ($Mcrmtipoproceso->IdTipoProceso == 0) {

                $Id =  $Mcrmtipoproceso->Insert();
                if ($Id > 0) {

                    $array = array("Prospectar", "Llamada en frio", "Reunion de ventas", "Propuestas", "Cierre");
                    $Color = array("#ec2222", "#017abc", "#0bd0b6", "#1e8a00", "#c2a800");

                    $count = 1;

                    $this->db->set('FechaMod', $this->FechaMod);
                    foreach ($array as $element) {
                        $Mcrmproceso = new Mcrmproceso();
                        $Mcrmproceso->IdCrmProceso = 0;
                        $Mcrmproceso->IdSucursal = $IdSucursal;
                        $Mcrmproceso->Nombre = $element;
                        $Mcrmproceso->RegEstatus = 'A';
                        $Mcrmproceso->Numero = $count;
                        $Mcrmproceso->IdTipoProceso = $Id;
                        $Mcrmproceso->Color = $Color[$count - 1];
                        $Mcrmproceso->FechaMod = date('Y-m-d H:i:s');
                        $Mcrmproceso->Insert();

                        $count++;
                    }

                    $Mcrmtipoproceso->IdTipoProceso = $Id;
                    $response =  $Mcrmtipoproceso->get_recovery();
                    $data['tipoproceso'] = $response['data'];
                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    return $this->set_response(
                        [
                            'status' => false,
                            'message' => 'Error al agregar a la base de datos.',
                        ],
                        REST_Controller::HTTP_BAD_REQUEST
                    );
                }
            } else {
                if ($Mcrmtipoproceso->update()) {
                    $response =  $Mcrmtipoproceso->get_recovery();
                    $data['tipoproceso'] = $response['data'];

                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller::HTTP_ACCEPTED);
                } else {

                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function RecoveryTipoandproceso_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mcrmtipoproceso = new Mcrmtipoproceso();
        $Id = (int) $this->get('IdTipoProceso');
        $IdOportunidad = $this->get('IdOportunidad');
        $IdTrabajador = $this->get('IdTrabajador');


        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $Mcrmtipoproceso->IdTipoProceso = $Id;
            $Mcrmtipoproceso->IdOportunidad = $IdOportunidad;
            $Mcrmtipoproceso->IdTrabajador = $IdTrabajador;
        }
        $response =  $Mcrmtipoproceso->get_recovery();
        $seguimiento = $Mcrmtipoproceso->get_seguimiento_oportunidad();

        $Mcrmproceso = new Mcrmproceso();
        $Mcrmproceso->Nombre = '';
        $Mcrmproceso->IdSucursal = $IdSucursal;
        $Mcrmproceso->IdTipoProceso = $this->get('IdTipoProceso');
        $rows =  $Mcrmproceso->get_list();

        if ($response['status']) {
            $data['tipoproceso'] = $response['data'];
            $data['procesos'] = $rows;
            $data['actividad'] = $seguimiento['data'];
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