<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cspend_horas extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mspend_horas');
        $this->load->model('Mspend_proyecto');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mclientes');
        
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objeto = new Mspend_horas();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->IdProyecto =$this->get('IdProyecto');
        $objeto->IdCliente =$this->get('IdCliente');
        $objeto->IdClienteS =$this->get('IdClienteS');
        if($this->get('FechaI')!='' && $this->get('FechaF')!=''){
            $objeto->FechaI=date("Y-m-d", strtotime($this->get('FechaI')));
            $objeto->FechaF=date("Y-m-d", strtotime($this->get('FechaF')));
        }
        $objeto->Descripcion=$this->get('Nombre');
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        

        return $this->set_response([
            'status' => true,
            'spend_horas' => $rows,
            'pagination' => $pager,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj = new Mspend_horas();
        $obj->RegEstatus='B';
        
        $obj->IdSpendHora = $Id;
  
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
        $obj= new Mspend_horas();

        $Id = (int) $this->get('IdSpendHora');
     

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdSpendHora = $Id;
        }
        $response =   $obj->get_recovery();
        
        if ($response['status']) {
            $datasucursal=null;
            $dataCliente=null;
            $IdProyecto=$response['data']->IdProyecto;
            if($IdProyecto>0){
                $oMspend_proyecto=new Mspend_proyecto();
                $oMspend_proyecto->IdProyecto=$IdProyecto;  
                $respro=$oMspend_proyecto->get_recovery();
                if($respro['status']){
                    $IdClienteS=$respro['data']->IdClienteS;
                    $IdCliente=$respro['data']->IdCliente;                    
                }

                
                $oMclientes=new Mclientes();
                $oMclientes->IdCliente=$IdCliente;
                $rescliente=$oMclientes->get_clientes();
                
                if($rescliente['status']){
                    $dataCliente=$rescliente['data'];    
                }
                
                
                $oMclientesucursal=new Mclientesucursal();
                $oMclientesucursal->IdClienteS=$IdClienteS;
                $resclisuc=$oMclientesucursal->get_cliente();
                if($resclisuc['status']){
                    $datasucursal=$resclisuc['data'];    
                }
                
            }
            
            
            $data['spend_horas'] = $response['data'];
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'cliente' => $dataCliente,
                'sucursal' => $datasucursal,
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
        
        $Id = (int)$this -> post('IdSpendHora');
        $IdProyecto=$this -> post('IdProyecto');
        $Descripcion=trim($this -> post('Descripcion'));
        $Horas=$this -> post('Horas');
        
        if($IdProyecto<=0){$IdProyecto='';}
        if($Horas<=0){$Horas='';}
            
        $v = new Valitron\Validator([
            'IdProyecto' => $IdProyecto,
            'IdSpendHora' => $Id,
            'Descripcion' => $Descripcion,
            'Horas' => $Horas,
        ]);

        $v -> rule('required', [
            'IdProyecto','Descripcion','Horas'
        ]) -> message('El campo  es requerido.');
        
        $v -> rule('numeric', [
            'Horas'
        ]) -> message('El campo  solo acepta numeros.');
        
        if ($v -> validate()) {
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            
            $obj = new Mspend_horas();
            $obj->IdSpendHora=$Id;
            $obj->IdSucursal =$IdSucursal;
            $obj->IdProyecto = $IdProyecto;
            $obj->FechaReg = date('Y-m-d H:i:s');
            $obj->Descripcion=$Descripcion;
            $obj->Horas=$Horas;
            
            $obj->FechaMod = date('Y-m-d H:i:s');
            $obj->RegEstatus = 'A';
            
            
            if ($Id==0) {
        
                $Id = $obj->insert();
                if ($Id > 0) {
                    $obj->IdSpendHora = $Id;
                    $response = $obj-> get_recovery();
                    $data['spend_horas'] = $response['data'];
                    
                    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar la información.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
            
                if ($obj-> update()) {
                    $response = $obj -> get_recovery();
                    $data['spend_horas'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {

                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos.',
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
}