<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccostoskm extends REST_Controller
{
    public $RutaQr;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcostoskm');
        
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objeto = new Mcostoskm();
        $objeto->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
 
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        
    
        $data['row'] =$rows;
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
        $obj = new Mcostoskm();
        $obj->RegEstatus='B';
        
        $obj->IdCostosKM = $Id;
  
        $response =   $obj->get_recovery();

        if ($response['status']) {
             $obj->FechaMod=date('Y-m-d H:i:s');
            if ($obj->delete()) {

                return $this->set_response([
                    'type' => 2,
                    'status' => true,
                    'message' => 'Se ha eliminado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'type' => 2,
                    'status' => false,
                    'message' => 'Error al eliminar la información.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            return $this->set_response([
                'type' => 2,
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
        $obj= new Mcostoskm();

        $Id = (int) $this->get('IdCostosKM');
     

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'type' => 2,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdCostosKM = $Id;
        }
        $response =   $obj->get_recovery();
        if ($response['status']) {
            $data['costoskm'] = $response['data'];
            
            return $this->set_response([
                'status' => true,
                'type' => 1,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                'status' => false,
                'type' => 1,
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
            'KMinicial' => $this -> post('KMinicial'),
            'KMfinal' => $this -> post('KMfinal'),
            'CostoKM'=>$this -> post('CostoKM')
        ]);

        $v -> rule('required', [
            'KMinicial',
            'KMfinal',
            'CostoKM'
        ]) -> message('El campo {field} es requerido.');
        
        $v -> rule('integer', [
            'KMinicial','KMfinal'
        ]) -> message('El campo {field} solo acepta numeros enteros.');

        
        $v -> rule('numeric', [
            'CostoKM'
        ]) -> message('El campo {field} solo acepta numeros.');
        

        if ($v -> validate()) {

            $Id = (int)$this -> post('IdCostosKM');

            $obj = new Mcostoskm();
            $obj->IdCostosKM=$Id;
            $obj->KMinicial = $this->post('KMinicial');
            $obj->KMfinal = $this->post('KMfinal');
            $obj->CostoKM = $this->post('CostoKM');
            $obj->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $obj->RegEstatus = 'A';
            $obj->FechaMod = date('Y-m-d H:i:s');
            
            if($obj->get_exist()>0){
                return $this -> set_response(['status' => false,
                    'type' => 2,
                    'message' => 'Error. Ya existe información en la base de datos con esos valores',
                ], REST_Controller:: HTTP_BAD_REQUEST);                
            }
            
            if($this->post('KMfinal')<=$this->post('KMinicial')){
                return $this -> set_response(['status' => false,
                    'type' => 2,
                    'message' => 'Error. El campo Rango Final/KM debe ser mayor al Rango Inicial/KM',
                ], REST_Controller:: HTTP_BAD_REQUEST);                
            }
                
            if ($Id==0) {
    
                $Id = $obj->insert();
                if ($Id > 0) {
                    $obj->IdCostosKM = $Id;
                    $response = $obj-> get_recovery();
                    $data['costoskm'] = $response['data'];
                    
                    
                    
                    return $this -> set_response([
                        'status' => true,
                        'type' => 2,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'type' => 2,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
            
                if ($obj-> update()) {
                    
                    $response = $obj -> get_recovery();
                    $data['costoskm'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'type' => 2,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {

                    return $this -> set_response([
                        'status' => false,
                        'type' => 2,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }

        }else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'type' => 1,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}