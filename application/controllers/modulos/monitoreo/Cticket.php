<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cticket extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('monitoreo/Mticket');
        $this->load->model('monitoreo/Mticket_seguimiento');
        $this->load->model('Mclientes');
        $this->load->model('Mempresa');
        
        $this->load->library('Mail');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdCliente=$this->get('IdCliente');
        if($IdCliente<=0){
            $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];
        }

        $objeto = new Mticket();
        $objeto->IdCliente =$IdCliente;
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
        $obj= new Mticket();
        $Id = (int) $this->get('IdTiket');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdTiket = $Id;
        }
        $response =   $obj->get_recovery();
        if ($response['status']) {
            $data['ticket'] = $response['data'];
            
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
        $Id = (int)$this -> post('IdTiket');
        $IdCliente=$this -> post('IdCliente');
        $IdClienteS=$this -> post('IdClienteS');
        $Contacto=trim($this -> post('Contacto'));
        $Correo=trim($this -> post('Correo'));
        $Telefono=trim($this -> post('Telefono'));
        $Asunto=trim($this -> post('Asunto'));
        $Estado=trim($this -> post('Estado'));
        $Tipo=trim($this -> post('Tipo'));
        $Para=trim($this -> post('Para'));
        
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        if($IdCliente<=0){
            $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];
        }
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
        if($IdCliente<=0){$IdCliente='';}
        if($IdClienteS<=0){$IdClienteS='';}
        
            
        $v = new Valitron\Validator([
            'Contacto' => $Contacto,
            'IdCliente' => $IdCliente,
            'IdClienteS' => $IdClienteS,
            'Tipo' => $Tipo,
            'Estado' => $Estado,
            'Asunto'=>$Asunto,
            'Para'=>$Para,
            'Correo'=>$Correo,
        ]);
    
        $v -> rule('required', [
            'Tipo','IdCliente','Estado','IdClienteS','Asunto','Para'
        ]) -> message('El campo  es requerido.');
        
        $v->rule('email', 'Para')->message('Correo electrónico no válido.');
        $v->rule('email', 'Correo')->message('Correo electrónico no válido.');
        
    
        if ($v -> validate()) {

            $oFolio =  get_Folio($IdSucursal,'SOLICITUD SERVICIO');
            $IdFolio= $oFolio['IdFolio'];
            $Numero= $oFolio['Numero'];
            $NumeroAntiguo= $oFolio['NumeroAntiguo'];
            $Folio= $oFolio['Folio'];
    
            $obj = new Mticket();
            $obj->IdTiket=$Id;
            $obj->IdCliente=$IdCliente;
            $obj->IdClienteS=$IdClienteS;
            $obj->Folio=$Folio;
            $obj->Lugar = '';
            $obj->Contacto = $Contacto;
            $obj->Correo =$Para;
            $obj->Telefono=$Telefono;
            $obj->Asunto=$Asunto;
            $obj->RegEstatus = 'A';
            $obj->Estado = $Estado;
            $obj->FechaReg = date('Y-m-d');
            $obj->FechaMod = date('Y-m-d H:i:s');
            
             
            if ($Id==0) {

                $Sucursal=trim($this -> post('Sucursal'));

                $Logo = '';

                $dataview['Folio']=$Folio;
                $dataview['Sucursal']=$Sucursal;
                $dataview['Contacto']=$Contacto;
                $dataview['Mensaje']=$Asunto;
                $dataview['Fecha']=date('d/m/Y');
                $dataview['Hora']=date('H:i');
                $dataview['Correo']=$Correo;


                $objCliente = new Mclientes();
                $objCliente->IdCliente = $IdCliente;
                $dataCliente = $objCliente->get_clientes();

                $objempresa = new Mempresa();
                $objempresa->IdEmpresa = $IdEmpresa;
                $dataEmpresa = $objempresa->get_empresa();

                $Normal = 1;
                
                if($dataEmpresa['status'])
                {
                    if($dataEmpresa['data']->Logo != '')
                    {
                        $Logo = base_url().'assets/files/logo_empresa/'.$dataEmpresa['data']->Logo;
                    }else{
                        $Logo = $dataEmpresa['data']->Imagen;
                        $Normal = 2;
                    }
                }

                if($Logo == '')
                {
                    $Logo = 'https://lugarcreativo.mx/email/logo_des.gif';
                }

                $dataview['Logo']=$Logo;
                $dataview['Normal']=$Normal;

                $ClienteNombre = '';

                if($dataCliente['status'])
                {
                    $ClienteNombre = $dataCliente['data']->Nombre;
                }

                $dataview['Cliente'] = $ClienteNombre;

                $vista=$this->load->view('catalogos/correo/SolicitudServ.php',$dataview,TRUE);
                
                $oMail=new Mail();
                $oMail->To=$Para;
                $oMail->Subject=$dataEmpresa['data']->Nombre.' - Solicitud de servicio';
                $oMail->Message=$vista;
  
                $Id = $obj->insert();

                if ($Id > 0) {
                    #Envio de correo
                    $resmail=$oMail->SendEmail();
                    
                    get_update_folio($IdFolio,$Numero);
                    $objdet = new Mticket_seguimiento();
                    $objdet->IdTiket=$Id;
                    $objdet->IdCliente=$IdCliente;
                    $objdet->IdClienteSucursal=$IdClienteS;
                    $objdet->IdUsuario=$IdUsuario;
                    $objdet->IdTrabajador=0;
                    $objdet->Comentario=$Asunto;
                    $objdet->Tipo=$Tipo;
                    $objdet->Fecha=date('Y-m-d');;
                    $objdet->Hora=date('H:i:s');;
                    $objdet->insert();
            
                    $obj->IdTiket = $Id;
                    $response = $obj-> get_recovery();
                    $data['ticket'] = $response['data'];
                    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'resmail'=>$resmail,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
             
                if ($obj-> update()) {
                    $response = $obj -> get_recovery();
                    $data['ticket'] = $response['data'];
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
    
    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj = new Mticket();
        $obj->RegEstatus='B';
        
        $obj->IdTiket = $Id;
  
        $response =   $obj->get_recovery();

        if ($response['status'])
        {
            $obj->FechaMod=date('Y-m-d H:i:s');
            if ($obj->delete())
            {
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