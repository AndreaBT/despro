<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccaja extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcaja');
        $this->load->model('Mrol');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

       
        $objCaja = new Mcaja();
        $objCaja->IdSucursal = $IdSucursal;
        $objCaja->Nombre = $this->get('Nombre');
        $objCaja->RegEstatus = $this->get('RegEstatus');
        $objCaja->Tipo = $this->get('Tipo');
    
        // Paginación
        $rows =  $objCaja->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $objCaja->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objCaja->Tamano = $pager->PageSize;
        $objCaja->Offset = $pager->Offset;

        $data['caja'] = $objCaja->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function cajacajachica_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

     
        $objCaja = new Mcaja();
        $objCaja ->IdCaja = $array['uniqueid'];
        $objCaja ->IdCaja= $this->get('IdCaja');
        $objCaja->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
    
        // Paginación
        $rows =  $objCaja->get_cajacajachica();

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objCaja->Tamano = $pager->PageSize;
        $objCaja->Offset = $pager->Offset;

        $data['cajacajachica'] = $objCaja->get_cajacajachica();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findOne_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objCaja = new Mcaja();

        $Id = (int) $this->get('IdCaja');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objCaja ->IdCaja = $Id;
        }
        $response = $objCaja->get_caja();
        if ($response['status']) {
            $data['caja'] = $response['data'];
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

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
      
        $objCaja = new Mcaja();
        $objCaja->IdCaja = $Id;
        $response = $objCaja ->get_caja();

        if ($response['status']) {

            if ( $objCaja ->delete()) {

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
    
    public  function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Nombre' => $this -> post('Nombre'),
            'Estado' => $this -> post('Estado'),
            'Tipo' => $this -> post('Tipo')
        ]);

        $v -> rule('required', [
            'Nombre',
            'Estado',
            'Tipo'
    
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdCaja');

            $objCaja = new Mcaja();
            $objCaja ->IdCaja = $this->post('IdCaja');

            $objCaja->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objCaja->Nombre = $this->post('Nombre');
            $objCaja->RegEstatus= 'A';
        
            $objCaja->Estado = $this->post('Estado');
            $objCaja->Tipo = $this->post('Tipo');
            $objCaja->FechaMod = date('Y-m-d H:i:s');

            if ($objCaja->IdCaja == 0) {
                $Id = $objCaja-> insert();
                if ($Id > 0) {
                    $objCaja->IdCaja= $Id;
                    $response = $objCaja-> get_caja();
                    $data['caja'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                if ( $objCaja-> update()) {
                    $response = $objCaja-> get_caja();
                    $data['caja'] = $response['data'];
    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
    
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}