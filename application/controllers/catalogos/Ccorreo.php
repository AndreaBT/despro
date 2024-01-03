<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccorreo extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcorreo');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMcorreo= new Mcorreo();
        $oMcorreo->Titulo = $this->get('Nombre');
        // Paginaci�n
        $rows =  $oMcorreo->get_list();
         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMcorreo->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMcorreo->Tamano = $pager->PageSize;
        $oMcorreo->Offset = $pager->Offset;

        $data['correo'] = $oMcorreo->get_list();
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
       
        $oMcorreo= new Mcorreo();

        $Id = (int) $this->get('IdCorreo');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Par�metros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMcorreo ->IdCorreo = $Id;
        }
        $response = $oMcorreo->get_Corrreo();
        if ($response['status']) {
            $data['correo'] = $response['data'];
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
      
        $oMcorreo= new Mcorreo();
        $oMcorreo->IdCorreo = $Id;
        $response = $oMcorreo ->get_Corrreo();

        if ($response['status']) {

            if ( $oMcorreo ->delete()) {

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
            'Titulo' => $this -> post('Titulo'),
            'Leyenda' => $this -> post('Leyenda'),
            'Pie' => $this -> post('Pie'),
        ]);

        $v -> rule('required', [
            'Titulo',
            'Leyenda',
            'Pie'
    
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdCorreo');

            $oMcorreo= new Mcorreo();
            $oMcorreo ->IdCorreo = $Id;
            $oMcorreo->Titulo = $this->post('Titulo');
            $oMcorreo->Leyenda = $this->post('Leyenda');
            $oMcorreo->Pie = $this->post('Pie');
            $oMcorreo->FechaMod = date('Y-m-d H:i:s');

                if ($oMcorreo->IdCorreo == 0) {
                    $Id = $oMcorreo->insert();
                    if ($Id > 0) {
                        $oMcorreo->IdCorreo= $Id;
                        $response = $oMcorreo->get_Corrreo();
                        $data['correo'] = $response['data'];
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
                    if ( $oMcorreo->update()) {
                        $response = $oMcorreo->get_Corrreo();
                        $data['correo'] = $response['data'];

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