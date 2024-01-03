
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccostosvarios extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcostosvarios');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $Mcostosvarios= new Mcostosvarios();
        $Mcostosvarios->Concepto = $this->get('Concepto');
        $Mcostosvarios->RegEstatus = $this->get('RegEstatus');
        $Mcostosvarios->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
      
        // Paginación
        $rows =   $Mcostosvarios->get_list();

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $Mcostosvarios->Tamano = $pager->PageSize;
        $Mcostosvarios->Offset = $pager->Offset;

        $data['costosvarios'] =  $Mcostosvarios->get_list();
        $data['pagination']= $pager;

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
        $oMcostosvarios = new Mcostosvarios();
        $oMcostosvarios->IdCostosV = $Id;
        $oMcostosvarios->FechaMod=date('Y-m-d H:i:s');
        $response =   $oMcostosvarios->get_recovery();

        if ($response['status']) {

            if ($oMcostosvarios->delete()) {

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
        $oMcostosvarios = new Mcostosvarios();

        $Id = (int) $this->get('IdCostosV');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMcostosvarios->IdCostosV = $Id;
        }
        $response = $oMcostosvarios->get_recovery();
        if ($response['status']) {
            $data['costosvarios'] = $response['data'];
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

    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Concepto' => trim($this -> post('Concepto'))
        ]);

        $v -> rule('required', [
            'Concepto'
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdCostosV');

            $oMcostosvarios = new Mcostosvarios();
            $oMcostosvarios->IdCostosV = $Id;
            $oMcostosvarios->Concepto = $this->post('Concepto');
            $oMcostosvarios ->RegEstatus = "A";
            $oMcostosvarios->IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $oMcostosvarios->FechaMod=date('Y-m-d H:i:s');
    
            if ($oMcostosvarios->IdCostosV == 0) {

                $Id =  $oMcostosvarios->insert();
                if ($Id > 0) {
                    $oMcostosvarios->IdCostosV= $Id;
                    $response =  $oMcostosvarios -> get_recovery();
                    $data['costosvarios'] = $response['data'];
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
                if ($oMcostosvarios-> update()) {
                    $response = $oMcostosvarios-> get_recovery();
                    $data['costosvarios'] = $response['data'];

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