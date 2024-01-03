<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Casignacioncaja extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Masignacioncaja');
        $this->load->model('Mcajachica');
  
        setTimeZone($this->verification,$this->input);
    }
    
    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $oMasignacioncaja = new Masignacioncaja();
        $oMasignacioncaja  ->IdTrabajador = $this->get('IdTrabajador');
        $oMasignacioncaja->FechaFin=date('Y-m-d');
        $row= $oMasignacioncaja->get_list();
 

        $data['asignados'] = $row;
        $data['asignados2'] = $row;

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
        $objcajaasig =  new Masignacioncaja();

        $Id = (int) $this->get('IdCajaC');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        $objcajaasig  ->IdTrabajador = $this->get('IdTrabajador');
        $objcajaasig->IdCajaC=$this->get('IdCajaC');
        $response =  $objcajaasig ->get_cajaasig();
        $response2 =  $objcajaasig ->get_cajaasigTotal();
        if ($response['status']) {
            $data['cajaasig'] = $response['data'];
            $data['cajaasig2'] = $response2['data'];
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
                'data' =>'',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    

        $v = new Valitron\Validator([
            'MontoAsignado' => $this -> post('MontoAsignado'),
            'IdTrabajador' => $this -> post('IdTrabajador'),
            'IdCajaC'=>$this ->post('IdCajaC'),
            'Asignado' => $this -> post('Asignado'),
        ]);

        $v -> rule('required', [
            'MontoAsignado',
            'IdTrabajador',
            'IdCajaC',
            'Asignado'
    
        ]) -> message('El campo {field} es requerido.');
    

        if ($v -> validate()) {
            
            $objcajaasig =  new Masignacioncaja();
            $objcajaasig->IdTrabajador =$this->post('IdTrabajador');
            $objcajaasig->IdCajaC=$this->post('IdCajaC');
            $objeto= $objcajaasig->get_cajaasig();

            $objcajaasig =  new Masignacioncaja();
            $objcajaasig ->IdTrabajador = $this->post('IdTrabajador');
            $objcajaasig  ->IdCajaC = $this->post('IdCajaC');
            $objcajaasig->MontoActual=$this ->post('MontoAsignado');
            $objcajaasig->MontoAsignado=$this ->post('MontoAsignado');
            
            $Mcajachica = new Mcajachica();
            $Mcajachica->IdCajaC=$this->post('IdCajaC');
            $objcc=  $Mcajachica->get_cajachica();
            
            if ($objcc['status'])
            {
                $NuevoValor = $objcc['data']->Utilizado  - $this -> post('Asignado');
                $Mcajachica = new Mcajachica();
            $Mcajachica->IdCajaC=$this->post('IdCajaC');
            $Mcajachica->Utilizado=$NuevoValor;
            $Mcajachica->changemontos();
                
            }
            

            if ( $objeto['status']) {
            
                
                $NuevoValor=$objeto['data']->MontoAsignado + $this -> post('Asignado');
                $objcajaasig->MontoAsignado =$NuevoValor;
                $objcajaasig->update();

                return $this -> set_response([
                    'status' => true,
                    'message' => 'Se ha actualizado correctamente.',
                ], REST_Controller:: HTTP_ACCEPTED);
            } else {
                $objcajaasig->insert();
                return $this -> set_response([
                    'status' => true,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_ACCEPTED);
            }
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function Delete_delete($IdT,$IdC)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objcajaasig =  new Masignacioncaja();
        $objcajaasig->IdTrabajador = $IdT;
        $objcajaasig->IdCajaC = $IdC;
        $objeto= $objcajaasig->get_cajaasig();

        if ($objeto['status']) {

            if (  $objcajaasig->delete()) {

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
}