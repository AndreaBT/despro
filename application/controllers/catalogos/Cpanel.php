<?php
defined('BASEPATH') OR exit('No direct script access allowed');//No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpanel extends REST_Controller {
	private $ruta = 'catalogos/Cpanel/';
    
    public function __construct() {
		parent::__construct();
		$this->load->model('Mpanel');
        // $this->load->model('Mpanel');
  	}
    
    //CRUD DE LA CLASE
  	public function Lista_get() {
        // Valid Token
        // if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
        //     return $this->set_unauthorized_response();
        // }

        // $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        // $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdUsuario'];
        // $IdRol=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdRol'];

        //Paginacion
        $datosObjeto = new Mpanel();
        $datosObjeto->RegEstatus    = 'A';
        $datosObjeto->Nombre =  $this->get('Nombre');
        $datosObjeto->Tipo =  $this->get('Tipo');
        $rows = $datosObjeto->get_list_panel();
        $Entrada=10;

        if ($this->get('Entrada')!='') {
            $Entrada =$this->get('Entrada');
        }

        $datosObjeto->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $datosObjeto->Tamano = $pager->PageSize;
        $datosObjeto->Offset = $pager->Offset;

        return $this->set_response([
            'status' => true,
            'row' => $datosObjeto->get_list_panel(),
            'pagination'=> $pager,
            // 'headers' =>array("#","Nombre","Tipo","Asociado","ReqPermiso","Clave","Acciones"),
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
  	}
    
    //Recovery
    public function Recovery_get() {
       // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        // $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        // $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdUsuario'];
        // $IdRol=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdRol'];

        $datosObjeto = new Mpanel();
        $Id = (int) $this->get('IdPanel');

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $datosObjeto ->IdPanel = $Id;
        }

        $response =$datosObjeto ->get_recovery_panel();

        if ($response['status']) {
            $data['panel'] = $response['data'];
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
    
    public function Nuevo_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    
        $Id=$this->post('IdPanel');
    
        $datosObjeto  = new Mpanel();
        $datosObjeto->IdPanel       = $Id;
        $datosObjeto->Nombre        = $this->post('Nombre');
        $datosObjeto->Tipo          = $this->post('Tipo');
        $datosObjeto->Asociado      = $this->post('Asociado');
        $datosObjeto->ReqPermiso    = $this->post('ReqPermiso');
        $datosObjeto->Clave         = $this->post('Clave');
        $datosObjeto->LinkJs        = $this->post('LinkJs');
        $datosObjeto->Lugar         = $this->post('Lugar');
        $datosObjeto->Icono         = $this->post('Icono');
      
        $v = new Valitron\Validator([
            'Nombre'        => $this -> post('Nombre'),
            'Tipo'          => $this -> post('Tipo'),
            'Asociado'      => $this -> post('Asociado'),
            'ReqPermiso'    => $this -> post('ReqPermiso'),
            'Clave'         => $this -> post('Clave'),
            'LinkJs'        => $this -> post('LinkJs'),
            'Lugar'         => $this -> post('Lugar'),
            'Icono'         => $this -> post('Icono')
        ]);

        $v -> rule('required', [
            'Nombre',
            'Tipo',
            'Asociado',
            'ReqPermiso',
            'Clave',
            'LinkJs',
            'Lugar',
            'Icono',
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {

            $Id = $this -> post('IdPanel');
            $datosObjeto  = new Mpanel();
            $datosObjeto->IdPanel = $this->post('IdPanel');
            $datosObjeto->Nombre        = $this->post('Nombre');
            $datosObjeto->Tipo          = $this->post('Tipo');
            $datosObjeto->Asociado      = $this->post('Asociado');
            $datosObjeto->ReqPermiso    = $this->post('ReqPermiso');
            $datosObjeto->Clave         = $this->post('Clave');
            $datosObjeto->LinkJs        = $this->post('LinkJs');
            $datosObjeto->Lugar         = $this->post('Lugar');
            $datosObjeto->Icono         = $this->post('Icono');
            
            if ( $datosObjeto->IdPanel == 0) {
                $Id =$datosObjeto-> set_insert();
                if ($Id > 0) {
                    $datosObjeto->IdPanel= $Id;
                    $response =$datosObjeto-> get_recovery_panel();
                    $data['panel'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => '1s.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                if ($datosObjeto-> update()) {
                    $response =$datosObjeto-> get_recovery_panel();
                    $data['panel'] = $response['data'];
    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
    
                    return $this -> set_response([
                        'status' => false,
                        'message' => '2.',
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
    
    public function Eliminar_delete($Id) {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        // $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        // $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdUsuario'];
        // $IdRol=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdRol'];

        $datosObjeto = new Mpanel();
        $datosObjeto->IdPanel = $Id;
        $response = $datosObjeto->get_recovery_panel();

        if ($response['status']) {

            if ( $datosObjeto ->set_delete()) {
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
?>