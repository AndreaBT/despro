<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cticket_seguimiento extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('monitoreo/Mticket_seguimiento');
        $this->load->model('monitoreo/Mticket');
      
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $oMticket= new Mticket(); 
        $oMticket->IdTiket=$this->get('IdTiket');
        $dat= $oMticket->get_recovery();
        $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];
        if($IdCliente<=0)
        {
            $IdCliente=$this->get('IdCliente');
            if ($dat['status'])
            {
                //si es cliente quiere decir que no lo he visto y lo cambio a visto
                if ($dat['data']->Estado=='Cliente'){
                   $oMticket= new Mticket(); 
                   $oMticket->IdTiket=$this->get('IdTiket');
                   $oMticket->Estado='Visto';
                   $oMticket->FechaMod=date('Y-m-d H:i:s');
                   $oMticket->updateesatus();
               }
           }
        }
        else
        {  
            if ($dat['status'])
            {
                //si es empresa quiere decir que no lo he visto y lo cambio a visto
                if ($dat['data']->Estado=='Empresa'){
                   $oMticket= new Mticket(); 
                   $oMticket->IdTiket=$this->get('IdTiket');
                   $oMticket->Estado='Visto';
                   $oMticket->FechaMod=date('Y-m-d H:i:s');
                   $oMticket->updateesatus();
               }
            }
        }
        
        $objeto = new Mticket_seguimiento();
        $objeto->IdCliente =$IdCliente;
        $objeto->IdTiket=$this->get('IdTiket');
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        
        return $this->set_response([
            'status' => true,
            'row' => $rows,
            'pagination' => $pager,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj= new Mticket_seguimiento();
        $Id = (int) $this->get('IdTiket');

        if (empty($Id))
        {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdTiket = $Id;
        }
        $response =   $obj->get_recovery();
        if ($response['status'])
        {
            $data['ticket'] = $response['data'];
            
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function Add_post()
    {
        // Valid Token    
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $Id = (int)$this -> post('IdTiket');
        $IdCliente=$this -> post('IdCliente');
        $IdClienteSucursal=$this -> post('IdClienteSucursal');
        $IdTrabajdor=$this -> post('IdTrabajador');;
        $Comentario=trim($this -> post('Comentario'));
        $Tipo=trim($this -> post('Tipo'));
        $Fecha=date('Y-m-d');
        $Hora=date('H:i:s');
        
        if($IdCliente<=0){
            $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];
        }
        
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
        if($IdCliente<=0){$IdCliente='';}
        if($IdClienteSucursal<=0){$IdClienteSucursal='';}
        if($Id<=0){$Id='';}
            
        $v = new Valitron\Validator([
            'Comentario' => $Comentario,
            'IdCliente' => $IdCliente,
            'IdClienteSucursal' => $IdClienteSucursal,
            'IdTrabajdor' => $IdTrabajdor,
            'IdTiket' => $Id,
            'Tipo'=>$Tipo,
        ]);
    
        $v -> rule('required', [
            'Comentario','IdCliente','IdClienteSucursal','IdTrabajdor','IdTiket'
        ]) -> message('El campo  es requerido.');
    
        if ($v -> validate())
        {
            $obj = new Mticket_seguimiento();
            $obj->IdTiket=$Id;
            $obj->IdCliente=$IdCliente;
            $obj->IdClienteSucursal=$IdClienteSucursal;
            $obj->IdUsuario=$IdUsuario;
            $obj->IdTrabajador=$IdTrabajdor;
            $obj->Comentario=$Comentario;
            $obj->Tipo=$Tipo;
            $obj->Fecha=$Fecha;
            $obj->Hora=$Hora;
            $obj->insert();
            
            $Visto='Cliente';
            if ($Tipo==1)
            {
                $Visto='Empresa';
            }
            
            $oMticket= new Mticket(); 
            $oMticket->IdTiket=$Id;
            $oMticket->Estado=$Visto;
            $oMticket->FechaMod=date('Y-m-d H:i:s');
            $oMticket->updateesatus();
            
            return $this -> set_response([
                'status' => true,
                'data' => $obj,
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller:: HTTP_CREATED);
            /*if ($Id > 0) {
                $obj->IdTiket = $Id;
                $response = $obj-> get_recovery();
                $data['ticket'] = $response['data'];
                
                return $this -> set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);
            } else {
                return $this -> set_response([
                    'status' => false,
                    'message' => 'Error al agregar.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }*/
        }else {
            $data['errores'] = $v->errors();
            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}