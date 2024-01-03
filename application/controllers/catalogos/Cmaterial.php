<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cmaterial extends REST_Controller
{
    public $RutaQr;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mmaterial');
        
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objeto = new Mmaterial();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->NomMaterial=$this->get('Nombre');
 
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();

          $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $objeto->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

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
        $obj = new Mmaterial();
        $obj->RegEstatus='B';
        
        $obj->IdMaterial = $Id;
  
        $response =   $obj->get_recovery();

        if ($response['status']) {
             $obj->FechaMod=date('Y-m-d H:i:s');
            if ($obj->delete()) {

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
        $obj= new Mmaterial();

        $Id = (int) $this->get('IdMaterial');
     

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdMaterial = $Id;
        }
        $response =   $obj->get_recovery();
        if ($response['status']) {
            $data['material'] = $response['data'];
            
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
            'NomMaterial' => $this -> post('NomMaterial'),
            'Precio' => $this -> post('Precio')
        ]);

        $v -> rule('required', [
            'NomMaterial',
            'Precio'
        ]) -> message('El campo {field} es requerido.');
        
        $v -> rule('numeric', [
            'Precio'
        ]) -> message('El campo {field} solo acepta numeros.');
        

        if ($v -> validate()) {

            $Id = (int)$this -> post('IdMaterial');
            
    
            

            $obj = new Mmaterial();
            $obj->IdMaterial=$Id;
            $obj->Precio = $this->post('Precio');
            $obj->NomMaterial = $this->post('NomMaterial');
            $obj->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $obj->RegEstatus = 'A';
            $obj->FechaMod = date('Y-m-d H:i:s');
            
            if ($Id==0) {
        
                $Id = $obj->insert();
                if ($Id > 0) {
                    $obj->IdMaterial = $Id;
                    $response = $obj-> get_recovery();
                    $data['material'] = $response['data'];
                    
                    
                    
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
            
                if ($obj-> update()) {
                    
                    $response = $obj -> get_recovery();
                    $data['material'] = $response['data'];

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
        }else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}