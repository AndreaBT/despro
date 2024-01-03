<?php
defined('BASEPATH') OR exit('No direct script access allowed');//No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;
use SebastianBergmann\Environment\Console;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpermiso extends REST_Controller {
	private $ruta = 'catalogos/Cpermiso/';
    
    public function __construct() {
		parent::__construct();
		$this->load->model('Mpermiso'); 
		$this->load->model('Mpaquetexpermiso'); 
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
        $datosObjeto = new Mpermiso();
        $datosObjeto->RegEstatus    = 'A';
        $datosObjeto->Descripcion   =  $this->get('Nombre');
        $rows = $datosObjeto->get_list_permiso();
        $Entrada=10;

        if ($this->get('Entrada')!='') {
            $Entrada =$this->get('Entrada');
        }

        $datosObjeto->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $datosObjeto->Tamano = $pager->PageSize;
        $datosObjeto->Offset = $pager->Offset;

        return $this->set_response([
            'status'    => true,
            'row'       => $datosObjeto->get_list_permiso(),
            'pagination'=> $pager,
            // 'headers'   => array("#","Descripcion","Llave","Acciones"),
            'message'   => 'Success',
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

        $datosObjeto  = new Mpermiso();
        $Id = (int) $this->get('IdPermiso');

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $datosObjeto ->IdPermiso = $Id;
        }

        $response =$datosObjeto ->get_recovery_permiso();

        if ($response['status']) {
            $data['permisos'] = $response['data'];
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
    
        $v = new Valitron\Validator([
            'Descripcion'   => $this -> post('Descripcion'),
            'Llave'         => $this -> post('Llave'),
        ]);

        $v -> rule('required', [
            'Descripcion',
            'Llave',
        ]) -> message('El campo {field} es requerido.');
    
        if ($v -> validate()) {
            $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id=$this->post('IdPermiso');

            $datosObjeto  = new Mpermiso();
            $datosObjeto->IdPermiso     = $Id;
            $datosObjeto->Descripcion   = $this->post('Descripcion');
            $datosObjeto->Llave         = $this->post('Llave');

            if ( $datosObjeto->IdPermiso == 0) {
    
                $Id =  $datosObjeto->set_insert();
                if ($Id > 0) {
                    $datosObjeto->IdPermiso = $Id;
                    $response =  $datosObjeto->get_recovery_permiso();
                    $data['tipoproceso'] = $response['data'];
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
                if ( $datosObjeto-> update()) {
                    $response =  $datosObjeto ->get_recovery_permiso();
                    $data['tipoproceso'] = $response['data'];
    
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

    public function Nuevo22_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    
        $Id=$this->post('IdPermiso');
    
        $datosObjeto  = new Mpermiso();
        $datosObjeto->IdPermiso     = $Id;
        $datosObjeto->Descripcion   = $this->post('Descripcion');
        $datosObjeto->Llave         = $this->post('Llave');
      
        $v = new Valitron\Validator([
            'Descripcion'   => $this -> post('Descripcion'),
            'Llave'         => $this -> post('Llave'),
        ]);

        $v -> rule('required', [
            'Descripcion',
            'Llave',
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {

            $Id = $this -> post('IdPermiso');
            $datosObjeto  = new Mpermiso();
            $datosObjeto->IdPermiso = $Id;
            $datosObjeto->Descripcion = $this->post('Descripcion');
            $datosObjeto->Llave = $this->post('Llave');
            $datosObjeto->RegEstatus ="A";
            
            if ($Id == 0) {
                $Id =$datosObjeto-> set_insert();
                if ($Id > 0) {
                    $datosObjeto->IdPermiso= $Id;
                    $response =$datosObjeto-> get_recovery_permiso();
                    $data['permiso'] = $response['data'];
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
                //print_r($datosObjeto);
                if ($datosObjeto-> update()) {
                    $response =$datosObjeto-> get_recovery_permiso();
                    $data['permiso'] = $response['data'];
    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
    
                    return $this -> set_response([
                        'status' => false,
                        'data' => $datosObjeto-> update(),
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

        $datosObjeto = new Mpermiso();
        $datosObjeto->IdPermiso= $Id;
        $response = $datosObjeto->get_recovery_permiso();

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

    #Nuevas apis
    public function ListPermisosxMenu_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $obj = new Mpermiso();
        $obj->RegEstatus = 'A';
        $rows = $obj->get_list_permiso();
        
        $mxp = new Mpaquetexpermiso();
        $mxp->IdPaquete = $this->get('IdPaquete');
        $rowMxP = $mxp->get_list();

    
        return $this->set_response([
            'status'    => true,
            'permisos' => $rows,
            'menuxpermiso'=> $rowMxP,
            'typemsj'   =>1,
            'message'   => 'Success',
        ], REST_Controller::HTTP_OK);
    }


    public  function AddMenuxpermiso_post() {
        // Valid Token
            if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
                return $this -> set_unauthorized_response();
            }
    
            if(count($this->post('arrayPermisos')))
            {
                $delete = new Mpaquetexpermiso();
                $delete->IdPaquete = $this->post('IdPaquete');
                $delete->delete();
    
                foreach($this->post('arrayPermisos') as $elemento)
                {
                    $add = new Mpaquetexpermiso();
                    $add->IdPaquete = $this->post('IdPaquete');
                    $add->IdPermiso = $elemento['IdPermiso'];
                    $add->insert();
                }
    
                return $this -> set_response([
                    'status' => true,
                    'typemsj' => 1,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);
            }
            else
            {
                return $this->set_response([
                    'status' => false,
                    'typemsj' => 1,
                    'message' => 'Debe Seleccionar al menos un permiso',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }

    #Fin de Funciones
}
?>