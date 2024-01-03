<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ctipounidad extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mtipounidad');
        $this->load->model('Miconos_eq');
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

        $objTipounidad = new Mtipounidad();
        $objTipounidad ->Nombre= $this->get('Nombre');
        $objTipounidad ->IdSucursal = $IdSucursal;
        $objTipounidad ->RegEstatus = $this->get('RegEstatus');
    
        // Paginación
        $rows =  $objTipounidad ->get_list();
        
        $Entrada = '';
        if ($this->get('Entrada')!='')
        {
            $Entrada = $this->get('Entrada');
        }
 
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objTipounidad ->Tamano = $pager->PageSize;
        $objTipounidad ->Offset = $pager->Offset;
        $objTipounidad->Limit=$Entrada;

        $data['tipounidad'] =  $objTipounidad->get_list();
        $data['rutaicono']=base_url().'assets/files/iconos_eq/';
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
        $objTipounidad  = new Mtipounidad();

        $objTipounidad ->IdTipoU = $Id;
  
        $response =  $objTipounidad ->get_tipounidad();

        if ($response['status']) {

            if (  $objTipounidad ->delete()) {

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
        $objTipounidad = new Mtipounidad();

        $Id = (int) $this->get('IdTipoU');

        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objTipounidad ->IdTipoU= $Id;
        }
        $response =   $objTipounidad ->get_tipounidad();
        
        $data['rutaicono']=base_url().'assets/files/iconos_eq/';

        if ($response['status']) {
            $data['tipounidad'] = $response['data'];
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
            'Nombre' => $this -> post('Nombre'),
            'Icono' => $this -> post('IdIconoEq'),
        ]);

        $v -> rule('required', [
            'Nombre',
            'Icono',
        
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdTipoU');

            $objTipounidad = new Mtipounidad();
            $objTipounidad->IdTipoU= $this->post('IdTipoU');
            $objTipounidad->Nombre = $this->post('Nombre');
            $objTipounidad->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objTipounidad->RegEstatus =    "A";
            $objTipounidad->IdIconoEq = $this->post('IdIconoEq');
            $objTipounidad->FechaMod = date('Y-m-d H:i:s');

            if ($objTipounidad->IdTipoU == 0) {

                $Id =  $objTipounidad-> insert();
                if ($Id > 0) {
                    $objTipounidad->IdTipoU = $Id;
                    $response = $objTipounidad-> get_tipounidad();
                    $data['tipounidad'] = $response['data'];
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
                
                if ($objTipounidad-> update()) {
                    $response = $objTipounidad -> get_tipounidad();
                    $data['tipounidad'] = $response['data'];

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