<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cfolio extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mfolio');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
       $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $objFolio = new Mfolio();
        $objFolio ->IdFolio= $this->get('IdFolio');
        $objFolio->IdSucursal = $IdSucursal;
        $objFolio->RegEstatus = $this->get('RegEstatus');
        // Paginación
        $rows = $objFolio->get_list();

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objFolio->Tamano = $pager->PageSize;
        $objFolio->Offset = $pager->Offset;

        $data['folio'] =$objFolio->get_list();
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
      
        $objFolio = new Mfolio();
        $objFolio ->IdFolio = $Id;
  
        $response =$objFolio ->get_folio();

        if ($response['status']) {

            if ($objFolio ->delete()) {

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


    public function findOne_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objFolio  = new Mfolio();

        $Id = (int) $this->get('IdFolio');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objFolio ->IdFolio = $Id;
        }
        $response =$objFolio ->get_folio();
        if ($response['status']) {
            $data['folio'] = $response['data'];
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
        
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $objFolio  = new Mfolio();
        $objFolio->IdFolio = $this->post('IdFolio');
        $objFolio->Tipo = $this->post('Tipo');
        $objFolio->IdSucursal=$IdSucursal;
        $Folio2='';
        if($objFolio->get_Exist_folio()>0){
            $Folio2 =$this->post('Tipo');
            }
        
        $v = new Valitron\Validator([
            'Serie' => $this -> post('Serie'),
            'Numero' => $this -> post('Numero'),
            'Tipo' => $this -> post('Tipo'),
            'Tipo2' => $Folio2
        ]);

        $v -> rule('required', [
            'Serie',
            'Numero',
            'Tipo',
    
        ]) -> message('El campo {field} es requerido.');
        
            $v->rule('different','Tipo', 'Tipo2')->message('Ya hay un registro con ese tipo de folio');

        if ($v -> validate()) {

            $Id = $this -> post('IdFolio');

            $objFolio  = new Mfolio();

            $objFolio->IdFolio = $this->post('IdFolio');
            $objFolio->Serie = $this->post('Serie');
            $objFolio->IdSucursal = $IdSucursal;
            $objFolio->RegEstatus ="A";
            $objFolio->Numero = $this->post('Numero');
            $objFolio->Tipo = $this->post('Tipo');
            $objFolio->FechaMod = date('Y-m-d H:i:s');
            
            if($objFolio->get_Exist_folio()>0){
                return $this -> set_response([
                        'status' => false,
                        'message' => 'Ya existe un folio con el tipo seleccionado.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }else{
                if ( $objFolio->IdFolio == 0) {
                    $Id =$objFolio-> insert();
                    if ($Id > 0) {
                        $objFolio->IdFolio= $Id;
                        $response =$objFolio-> get_folio();
                        $data['folio'] = $response['data'];
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
                    if ($objFolio-> update()) {
                        $response =$objFolio-> get_folio();
                        $data['folio'] = $response['data'];
        
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