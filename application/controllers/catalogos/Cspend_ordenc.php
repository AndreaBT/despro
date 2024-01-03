<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cspend_ordenc extends REST_Controller
{
    public $RutaFile;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mspend_ordenc');
        $this->RutaFile='assets/files/spend_oc/';
        $this->load->library('UploadFile');
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

        $objeto = new Mspend_ordenc();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->IdProyecto =$this->get('IdProyecto');
        $objeto->IdCliente =$this->get('IdCliente');
        $objeto->IdClienteS =$this->get('IdClienteS');
        if($this->get('FechaI')!='' && $this->get('FechaF')!=''){
            $objeto->FechaI=date("Y-m-d", strtotime($this->get('FechaI')));
            $objeto->FechaF=date("Y-m-d", strtotime($this->get('FechaF')));
        }
        $objeto->Descripcion=$this->get('Nombre');
        $objeto->Concepto=$this->get('Concepto');
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        

        return $this->set_response([
            'status' => true,
            'ordencompra' => $rows,
            'pagination' => $pager,
            'UrlPdf' => base_url().$this->RutaFile.$IdEmpresa.'/'.$IdSucursal.'/',
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj = new Mspend_ordenc();
        $obj->RegEstatus='B';
        
        $obj->IdOrdenCompra = $Id;
  
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
        $obj= new Mspend_ordenc();

        $Id = (int) $this->get('IdOrdenCompra');
     

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdOrdenCompra = $Id;
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
                $IdClienteS=$respro['data']->IdClienteS;
                $IdCliente=$respro['data']->IdCliente;
                
                $oMclientes=new Mclientes();
                $oMclientes->IdCliente=$IdCliente;
                $rescliente=$oMclientes->get_clientes();
                $dataCliente=$rescliente['data'];
                
                $oMclientesucursal=new Mclientesucursal();
                $oMclientesucursal->IdClienteS=$IdClienteS;
                $resclisuc=$oMclientesucursal->get_cliente();
                $datasucursal=$resclisuc['data'];
            }
            
            
            $data['ordencompra'] = $response['data'];
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
        
        $Id = (int)$this -> post('IdOrdenCompra');
        $IdProyecto=$this -> post('IdProyecto');
        $Concepto=trim($this -> post('Concepto'));
        $Descripcion=trim($this -> post('Descripcion'));
        $FolioArchivo=trim($this -> post('FolioArchivo'));
        $Monto=$this -> post('Monto');
        if($Monto<=0){
            $Monto='';
        }
        if($IdProyecto<=0){
            $IdProyecto='';
        }
            
        $v = new Valitron\Validator([
            'IdProyecto' => $IdProyecto,
            'IdOrdenCompra' => $Id,
            'Concepto' => $Concepto,
            'Descripcion' => $Descripcion,
            'Monto' => $Monto,
        ]);

        $v -> rule('required', [
            'IdProyecto','Concepto','Descripcion','Monto'
        ]) -> message('El campo  es requerido.');
        
        $v -> rule('numeric', [
            'Monto'
        ]) -> message('El campo  solo acepta numeros.');
        
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        $RutaPrincipal=$this->RutaFile.$IdEmpresa.'/';
        if (!is_dir($RutaPrincipal)) {
            mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
        }
            

        if ($v -> validate()) {
            
            $route =$RutaPrincipal.$IdSucursal.'/';        
            $files = $this->uploadfile->savefile($route, 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);
    
            

            $obj = new Mspend_ordenc();
            $obj->IdOrdenCompra=$Id;
            $obj->IdSucursal =$IdSucursal;
            $obj->IdProyecto = $IdProyecto;
            $obj->IdUsuario = $IdUsuario;
            $obj->FechaReg = date('Y-m-d H:i:s');
            $obj->Concepto=$Concepto;
            $obj->Descripcion=$Descripcion;
            $obj->FolioArchivo=$FolioArchivo;
            $obj->Archivo=$files;
            $obj->Monto=$Monto;
            
            $obj->FechaMod = date('Y-m-d H:i:s');
            $obj->RegEstatus = 'A';
            
            
            if ($Id==0) {
        
                $Id = $obj->insert();
                if ($Id > 0) {
                    $obj->IdOrdenCompra = $Id;
                    $response = $obj-> get_recovery();
                    $data['ordencompra'] = $response['data'];
                    
                    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar la información.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
            
                if ($obj-> update()) {
                    $response = $obj -> get_recovery();
                    $data['ordencompra'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {

                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
                'typemsg'=>2,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}