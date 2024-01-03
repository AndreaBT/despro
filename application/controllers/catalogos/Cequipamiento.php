<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cequipamiento extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mequipamiento');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMequipamiento= new Mequipamiento();
        $oMequipamiento->Nombre = $this->get('Nombre');
        $oMequipamiento->RegEstatus = 'A';
        // Paginaci�n
        $rows =  $oMequipamiento->get_list();
         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMequipamiento->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMequipamiento->Tamano = $pager->PageSize;
        $oMequipamiento->Offset = $pager->Offset;

        $data['equipamiento'] = $oMequipamiento->get_list();
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
       
        $oMequipamiento= new Mequipamiento();
        $oMequipamiento->RegEstatus='A';

        $Id = (int) $this->get('IdEquipamiento');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Par�metros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMequipamiento ->IdEquipamiento = $Id;
        }
        $response = $oMequipamiento->get_equipamiento();
        if ($response['status']) {
            $data['equipamiento'] = $response['data'];
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
      
        $oMequipamiento= new Mequipamiento();
        $oMequipamiento->IdEquipamiento = $Id;
        $response = $oMequipamiento ->get_equipamiento();

        if ($response['status']) {

            if ( $oMequipamiento ->delete()) {

                return $this->set_response([
                    'status' => true,
                    'message' => 'Se ha eliminado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al eliminar la informaci�n.',
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
            'Nombre' => $this -> post('Nombre')
        ]);

        $v -> rule('required', [
            'Nombre'
    
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdEquipamiento');

            $oMequipamiento = new Mequipamiento();
            $oMequipamiento ->IdEquipamiento = $Id;
            $oMequipamiento->Nombre = $this->post('Nombre');
            $oMequipamiento->RegEstatus= 'A';
            $oMequipamiento->FechaMod = date('Y-m-d H:i:s');

            if ($oMequipamiento->IdEquipamiento == 0) {
                $Id = $oMequipamiento->insert();
                if ($Id > 0) {
                    $oMequipamiento->IdEquipamiento= $Id;
                    $response = $oMequipamiento->get_equipamiento();
                    $data['equipamiento'] = $response['data'];
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
                if ( $oMequipamiento->update()) {
                    $response = $oMequipamiento->get_equipamiento();
                    $data['equipamiento'] = $response['data'];

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