<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Csucursal extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mclientesucursal');
        $this->load->model('Mnumcontrato');
        $this->load->model('Miconosemp');
        
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
      
        $objClientes = new Mclientesucursal();
        $objClientes->IdCliente= $this->get('IdCliente');
        $objClientes->Nombre= $this->get('Nombre');
        $objClientes->IdSucursal= $IdSucursal;
       
        // Paginación
        $rows =  $objClientes->get_list();
        $Entrada = '';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $objClientes->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $objClientes->Tamano = $pager->PageSize;
        $objClientes->Offset = $pager->Offset;

        $data['clientesucursal'] = $objClientes->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'RutaIcoEmp'=>base_url().'assets/files/iconemp/',
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
        $objClientes = new Mclientesucursal();
        $objClientes->IdClienteS = $Id;
        $response = $objClientes->get_cliente();

        if ($response['status'])
        {
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


    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objClientes = new Mclientesucursal();

        $Id = (int) $this->get('IdClienteS');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $objClientes->IdClienteS = $Id;
        }
        $response =  $objClientes->get_cliente();
        if ($response['status']) {
            
            $Mnumcontrato=new Mnumcontrato();
            $Mnumcontrato->IdClienteS=$Id;
            $Mnumcontrato->IdProyectoSpendDif='0';
            $Mnumcontrato->RegEstatus='A';
            $data['Clientes'] = $response['data'];
            $data['ListaContratos']=$Mnumcontrato->get_list();
            
            //$data['numcontrato'] = $regcontra;
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
            'IdCliente' => $this -> post('IdCliente'),
            'Nombre' => $this -> post('Nombre'),
            'Direccion' => $this -> post('Direccion'),
            'Telefono' => $this -> post('Telefono'),
            'Correo' => $this -> post('Correo'),
            'Ciudad' => $this -> post('Ciudad'),
            'ContactoS' => $this -> post('ContactoS'),
            'Estatus'=>$this -> post('Tipo')
        ]);

        $v -> rule('required', [
            'IdCliente',
            'Nombre',
            'Direccion',
            'Telefono',
            'Correo',
            'Ciudad',
            'ContactoS',
            'Estatus'
        ]) -> message('El campo {field} es requerido.');

        if($v -> validate())
        {
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

            $objClientes = new Mclientesucursal();
            $objClientes->IdClienteS= $this->post('IdClienteS');
            $objClientes->IdCliente= $this->post('IdCliente');
            $objClientes->Nombre= $this->post('Nombre');
            $objClientes->Direccion= $this->post('Direccion');
            $objClientes->Telefono= $this->post('Telefono');
            $objClientes->Correo = $this->post('Correo');
            $objClientes->Ciudad= $this->post('Ciudad');
            $objClientes->IdSucursal=$IdSucursal;
            $objClientes->RegEstatus= "A";
            $objClientes->ContactoS = $this->post('ContactoS');
            $objClientes->CheckCli = $this->post('CheckCli');
            $objClientes->Tipo = $this->post('Tipo');
            $objClientes->IdVendedor = 0;
            $objClientes->DistanciaAprox = "0";
            $objClientes->Comentario = $this->post('Comentario');
            $objClientes->Cargo = $this->post('Posicion');
            $objClientes->FechaMod = date('Y-m-d H:i:s');
            $objClientes->Latitud = $this->post('Latitud');
            $objClientes->Longitud = $this->post('Longitud');
            
            if ( $objClientes->IdClienteS == 0) {
                $Id =  $objClientes-> insert();
                if ($Id > 0) {
                    $objClientes->IdClienteS = $Id;
                    $response =  $objClientes-> get_cliente();
                    
                    
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
            
                    $response =  $objClientes -> get_cliente();
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

    function insert_contratos($dataListaContratos,$IdClienteS){
        
        $Mnumcontrato= new Mnumcontrato();
        $Mnumcontrato->IdClienteS=$IdClienteS;
        $Mnumcontrato->delete_all();
 
        foreach ($dataListaContratos as $elemt)
        {
            $Mnumcontrato= new Mnumcontrato();
            $Mnumcontrato->IdContrato=$elemt->IdContrato;
            $Mnumcontrato->IdClienteS=$IdClienteS;
            $Mnumcontrato->NumeroC=$elemt->NumeroC;
            $Mnumcontrato->RegEstatus=$elemt->RegEstatus;
            $Mnumcontrato->Comentario=$elemt->Comentario;
            $Mnumcontrato->RegEstatus='A';
            
            if($elemt->IdContrato>0){//update
                   
                $Mnumcontrato->update();
            }else{//insert
                $Mnumcontrato->insert();
            }        
        }    
    }
}