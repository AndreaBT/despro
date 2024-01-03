<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccontactos extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mclientes');
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
        $objClientes = new Mclientes();
        $objClientes->Nombre= $this->get('Nombre');
        $objClientes->Tipo= $this->get('Tipo');
        $objClientes->IdSucursal= $IdSucursal;
        $objClientes->RegEstatus= $this->get('RegEstatus');
        
        
        // Paginación
        $rows =  $objClientes->get_listcrm();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }

        $objClientes->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objClientes->Tamano = $pager->PageSize;
        $objClientes->Offset = $pager->Offset;

        $data['clientes'] = $objClientes->get_listcrm();
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
        $objClientes = new Mclientes();

        $objClientes->IdCliente = $Id;
  
        $response = $objClientes->get_clientes();

        if ($response['status']) {

            if ( $objClientes->delete()) {

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
        $objClientes = new Mclientes();

        $Id = (int) $this->get('IdCliente');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objClientes->IdCliente = $Id;
        }
        $response =  $objClientes->get_clientes();
        if ($response['status']) {
            $data['Clientes'] = $response['data'];
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
            'Telefono' => $this -> post('Telefono'),
            'Direccion' => $this -> post('Direccion'),
            'Correo' => $this -> post('Correo'),
            'Ciudad' => $this -> post('Ciudad'),
            'Contacto' => $this -> post('Contacto')
    
        ]);
    
        $v -> rule('required', [
            'Nombre',
            'Telefono',
            'Direccion',
            'Correo',
            'Ciudad',
            'Contacto'
         
        ]) -> message('El campo {field} es requerido.');
    
        if ($v -> validate()) {
            $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdCliente');
    
            $objClientes = new Mclientes();
            $objClientes->IdCliente= $this->post('IdCliente');
            $objClientes->Nombre= $this->post('Nombre');
            $objClientes->Telefono = $this->post('Telefono');
            $objClientes->Direccion= $this->post('Direccion');
            $objClientes->Correo= $this->post('Correo');
            $objClientes->Ciudad = $this->post('Ciudad');
            $objClientes->Pais= '';
            $objClientes->Estado="";
            $objClientes->CP = "";
            $objClientes->IdSucursal=$IdSucursal;
            $objClientes->RegEstatus= "A";
            $objClientes->Contacto = $this->post('Contacto');
            $objClientes->Dfac = $this->post('Dfac');
            $objClientes->FechaMod = date('Y-m-d H:i:s');
            if ( $objClientes->IdCliente == 0) {
    
                $Id =  $objClientes-> insert();
                if ($Id > 0) {
                    $objClientes->IdCliente = $Id;
                    $response =  $objClientes-> get_clientes();
                    $data['clientes'] = $response['data'];
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
                if ( $objClientes-> update()) {
                    $response =  $objClientes -> get_clientes();
                    $data['clientes'] = $response['data'];
    
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
    
    public function ListMonitoreo_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objClientes = new Mclientes();
        $objClientes ->IdCliente = $array['uniqueid'];
        $objClientes->Nombre= $this->get('Nombre');
        $objClientes->IdSucursal=  $IdSucursal;
        $objClientes->RegEstatus= $this->get('RegEstatus');
        
        
        // Paginación
        $rows =  $objClientes->get_list_monitoreo();
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $objClientes->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objClientes->Tamano = $pager->PageSize;
        $objClientes->Offset = $pager->Offset;

        $data['clientes'] = $objClientes->get_list_monitoreo();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}