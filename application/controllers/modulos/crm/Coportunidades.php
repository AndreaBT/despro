<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Coportunidades extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Moportunidades');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Moportunidades = new Moportunidades();
        $Moportunidades->Nombre= $this->get('Nombre');
        $Moportunidades->IdVendedor= $this->get('IdVendedor');
        $Moportunidades->IdTipoP= $this->get('IdTipoP');
        $Moportunidades->IdSucursal= $IdSucursal;
        
        
        // Paginación
        $rows =  $Moportunidades->get_list();
        $Entrada = '';
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }

        $Moportunidades->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $Moportunidades->Tamano = $pager->PageSize;
        $Moportunidades->Offset = $pager->Offset;

        $data['oportunidades'] = $Moportunidades->get_list();
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
        $Moportunidades= new Moportunidades();

        $Moportunidades->IdOportunidad = $Id;
  
        $response = $Moportunidades->get_recovery();

        if ($response['status']) {

            if ( $Moportunidades->delete()) {

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
        $Moportunidades= new Moportunidades();

        $Id = (int) $this->get('IdOportunidad');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $Moportunidades->IdOportunidad = $Id;
        }
        $response =  $Moportunidades->get_recovery();
        if ($response['status']) {
            $data['oportunidad'] = $response['data'];
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
            'Vendedor' => $this -> post('IdVendedor'),
            'Sucursal' => $this -> post('IdClienteS'),
            'Proceso' => $this -> post('IdTipoP')
        ]);
    
        $v -> rule('required', [
            'Nombre',
            'Vendedor',
            'Sucursal',
            'Proceso'
        ]) -> message('El campo {field} es requerido.');
    
        if($v->validate())
        {
            $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdOportunidad');
    
            $Moportunidades= new Moportunidades();
            $Moportunidades->IdOportunidad= $Id;
            $Moportunidades->Nombre= $this->post('Nombre');
            $Moportunidades->RegEstatus= "A";
            $Moportunidades->IdVendedor = $this->post('IdVendedor');
            $Moportunidades->IdClienteS = $this->post('IdClienteS');
            $Moportunidades->IdTipoP = $this->post('IdTipoP');
        
            $Moportunidades->IdSucursal=$IdSucursal;
            $Moportunidades->FechaReg = date('Y-m-d');
            $Moportunidades->FechaMod = date('Y-m-d H:i:s');
            
            if( $Moportunidades->IdOportunidad == 0)
            {
                $Id =  $Moportunidades-> insert();
                if ($Id > 0) {
                    $Moportunidades->IdOportunidad = $Id;
                    $response =  $Moportunidades->get_recovery();
                    $data['oportunidad'] = $response['data'];
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
                if ( $Moportunidades-> update()) {
                    $response =  $Moportunidades ->get_recovery();
                    $data['oportunidad'] = $response['data'];
    
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


    public function Listoportunidadvendedor_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Moportunidades = new Moportunidades();
        $Moportunidades->Nombre= $this->get('Nombre');
        $Moportunidades->IdVendedor= $this->get('IdVendedor');
        $Moportunidades->IdTipoP= $this->get('IdTipoP');
        $Moportunidades->IdSucursal= $IdSucursal;
        
        // Paginación
        $rows =  $Moportunidades->get_listvendedoroportunidad();
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $Moportunidades->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $Moportunidades->Tamano = $pager->PageSize;
        $Moportunidades->Offset = $pager->Offset;
        $rows = $Moportunidades->get_listvendedoroportunidad();

        $data['oportunidades'] = $rows;
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}