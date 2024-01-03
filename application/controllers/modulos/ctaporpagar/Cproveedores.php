<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cproveedores extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ctaporpagar/Mproveedores');
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

        
        $oMproveedores = new Mproveedores();
        $oMproveedores->IdSucursal = $IdSucursal;
        $oMproveedores->Nombre = $this->get('Nombre');
        $oMproveedores->RegEstatus = $this->get('RegEstatus');
        // Paginación
        $rows =  $oMproveedores->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMproveedores->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMproveedores->Tamano = $pager->PageSize;
        $oMproveedores->Offset = $pager->Offset;

        $data['proveedores'] = $oMproveedores->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $oMproveedores = new Mproveedores();

        $Id = (int) $this->get('IdProveedor');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMproveedores ->IdProveedor = $Id;
        }
        $response = $oMproveedores->get_recovery();
        if ($response['status']) {
            $data['proveedor'] = $response['data'];
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
      
        $oMproveedores = new Mproveedores();
        $oMproveedores->IdProveedor = $Id;
  
        $response = $oMproveedores ->get_recovery();

        if ($response['status']) {

            if ( $oMproveedores ->delete()) {

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
    

    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Nombre' => $this -> post('Nombre'),
            'Rfc' => $this -> post('Rfc')
        ]);

        $v -> rule('required', [
            'Nombre',
            'Rfc',
    
        ]) -> message('El campo {field} es requerido.');
        

        if ($v -> validate()) {
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdProveedor');
            $DiasCredito=$this->post('DiasCredito');
            if ($DiasCredito=='')
            {
                $DiasCredito=0;
            }

            $oMproveedores = new Mproveedores();
            $oMproveedores ->IdProveedor =  $Id;
            $oMproveedores->IdSucursal = $IdSucursal;
            $oMproveedores->Nombre = $this->post('Nombre');
            $oMproveedores->Rfc = $this->post('Rfc');
            $oMproveedores->DiasCredito = $DiasCredito;
            $oMproveedores->Comentario = $this->post('Comentario');
            $oMproveedores->RegEstatus ="A";
            $oMproveedores->FechaReg = date('Y-m-d');
            $oMproveedores->FechaMod = date('Y-m-d H:i:s');

            //nuevo 
            $oMproveedores->Contacto = $this->post('Contacto');
            $oMproveedores->Telefono = $this->post('Telefono');
            $oMproveedores->Direccion = $this->post('Direccion');
            $oMproveedores->DatosBancarios = $this->post('DatosBancarios');

            if ($oMproveedores->IdProveedor == 0) {
                $Id = $oMproveedores-> insert();
                if ($Id > 0) {
                    $oMproveedores->IdProveedor= $Id;
                    $response = $oMproveedores->get_recovery();
                    $data['proveedor'] = $response['data'];
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
                if ( $oMproveedores-> update()) {
                    $response = $oMproveedores->get_recovery();
                    $data['proveedor'] = $response['data'];
    
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