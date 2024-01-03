<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cchat extends REST_Controller
{
    public $RutaQr;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('despacho/Mmensaje');
        $this->load->model('despacho/Mdetallemensaje');
        
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
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
        $v = new Valitron\Validator([
            'IdContacto' => $this -> get('IdContacto'),
            'Tipo' => $this -> get('Tipo')
        ]);

        $v -> rule('required', [
            'IdContacto',
            'Tipo'
        ]) -> message('El campo {field} es requerido.');
    
        if ($v -> validate()) {

            //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
        
            $objeto = new Mmensaje();

        
            if ($this -> get('Tipo')==1)
            {
                $objeto->IdContacto =$this->get('IdContacto');
                $objeto->IdUsuario=$IdUsuario;
            }
            if ($this -> get('Tipo')==2)
            {
                $objeto->IdUsuario =$this->get('IdContacto');
                $objeto->IdContacto=$IdUsuario;
            }

            $objeto->RegEstatus='A';
    
            // Paginaci�n
            $resp =  $objeto->get_mensaje();

            //$this->response($resp);

            $rowmsj= array();
            if ($resp['status'])
            {
                //IdMensaje Mensaje padre
                $mdetallem= new Mdetallemensaje();
                $mdetallem->IdMensaje=$resp['data']->IdMensaje;
                $rowmsj= $mdetallem->get_list();


                $mdetallemu= new Mdetallemensaje();
                $mdetallemu->IdMensaje=$resp['data']->IdMensaje;
                $rowmsju= $mdetallemu->Update();

            }
            return $this->set_response([
                'status' => true,
                'Lista' => $rowmsj,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        }
        else {
            $data['errores'] = $v->errors();
            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
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

    public function Add_post() {
        // Valid Token
        
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
        
        $v = new Valitron\Validator([
            'IdContacto' => $this->post('IdContacto'),
            'Mensaje' => $this -> post('Mensaje'),
            'Tipo' => $this -> post('Tipo')
        ]);

        $v -> rule('required', [
            'IdContacto',
            'Mensaje',
            'Tipo'
            
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {
            $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
    
            //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
    
            $objeto = new Mmensaje();
            if ($this -> post('Tipo')==1)
            {
                $objeto->IdContacto =$this->post('IdContacto');
                $objeto->IdUsuario=$IdUsuario;
            }
            if ($this -> post('Tipo')==2)
            {
                $objeto->IdUsuario =$this->post('IdContacto');
                $objeto->IdContacto=$IdUsuario;
            }
            $objeto->RegEstatus='A';
            // Paginaci�n
            $resp =  $objeto->get_mensaje();
            $rowmsj= array();
            if ($resp['status'])
            {
            
                //inserta detalle
                $mdetallem= new Mdetallemensaje();
                $mdetallem->IdMensaje=$resp['data']->IdMensaje;
                $mdetallem->IdUsuario=$IdUsuario;
                $mdetallem->Mensaje=$this->post('Mensaje');
                $mdetallem->Fecha= date('Y-m-d H:i:s');
                $mdetallem->Hora= date('H:i:s');
                $mdetallem->Estatus='No leido';
                $mdetallem->Insert();
                
                $objeto->IdMensaje = $resp['data']->IdMensaje;
                $response = $objeto->get_mensaje();
                $data['chat'] = $response['data'];
                    
                return $this -> set_response([
                    'status' => true,
                    'data' => "Agregado",
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);
            
            }
            else
            {
                //inserta
                $objeto = new Mmensaje();
                if ($this -> post('Tipo')==1)
                    {
                    $objeto->IdContacto =$this->post('IdContacto');
                    $objeto->IdUsuario=$IdUsuario;
                    }
                    if ($this -> post('Tipo')==2)
                    {
                    $objeto->IdUsuario =$this->post('IdContacto');
                    $objeto->IdContacto=$IdUsuario;
                    }
                $objeto->Fecha= date('Y-m-d');
                $objeto->FechaMod= date('Y-m-d H:i:s');
                $objeto->RegEstatus='A';
                $Id =  $objeto->Insert();
                if ($Id > 0) {
                
                    $objeto = new Mmensaje();
                    $objeto->IdMensaje =$Id;
                    $objeto->RegEstatus='A';
                    // Paginaci�n
                    $resp =  $objeto->get_mensaje();
                
                    //inserta detalle
                    $mdetallem= new Mdetallemensaje();
                    $mdetallem->IdMensaje=$resp['data']->IdMensaje;
                    $mdetallem->Mensaje=$this->post('Mensaje');
                    $mdetallem->IdUsuario=$IdUsuario;
                    $mdetallem->Fecha= date('Y-m-d H:i:s');
                    $mdetallem->Hora= date('H:i:s');
                    $mdetallem->Estatus='No leido';
                    $mdetallem->Insert();
                    
                    $objeto->IdMensaje = $Id;
                    $response = $objeto->get_mensaje();
                    $data['chat'] = $response['data'];
                    
                    return $this -> set_response([
                        'status' => true,
                        'data' => "Agregado",
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                }
                else
                {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
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

    public function Getnotification_get(){

        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];

        $mdetallem= new Mdetallemensaje();
        $mdetallem->IdUsuario=$IdUsuario;
        
        // Paginación
        $rows =  $mdetallem->get_listMensajeUser();
        $Entrada=5;

        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $mdetallem->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'),$Entrada);

        $mdetallem->Tamano = $pager->PageSize;
        $mdetallem->Offset = $pager->Offset;
        $rowmsj =   $mdetallem->get_listMensajeUser();

        $pagination = $pager;


        return $this->set_response([
            'status' => true,
            'Lista' => $rowmsj,
            'pagination' => $pagination,
            'IdUsuario' => $IdUsuario,
            'Total' => count($rows),
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}