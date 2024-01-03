<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccrmproceso extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Mcrmproceso');
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
        $Mcrmproceso= new Mcrmproceso();
        $Mcrmproceso->Nombre= $this->get('Nombre');
        $Mcrmproceso->IdSucursal= $IdSucursal;
        $Mcrmproceso->IdTipoProceso=$this->get('IdTipoProceso');
        $Mcrmproceso->IdClienteSucursal=$this->get('IdClienteSucursal');
        $Mcrmproceso->IdOportunidad=$this->get('IdOportunidad');
        $Mcrmproceso->IdCliente=$this->get('IdCliente');
        $Mcrmproceso->IdTrabajador=$this->get('IdTrabajador');
        
        // Paginación
        $rows =  $Mcrmproceso->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }

        $Mcrmproceso->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $Mcrmproceso->Tamano = $pager->PageSize;
        $Mcrmproceso->Offset = $pager->Offset;

       

        $data['procesoxvendedor'] = $Mcrmproceso->get_trabajadorxproceso();

        $data['procesos'] = $Mcrmproceso->get_list();
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
        $Mcrmproceso= new Mcrmproceso();
        $Mcrmproceso->IdCrmProceso = $Id;
        $response = $Mcrmproceso->get_recovery();
        if ($response['status']) {

            if ( $Mcrmproceso->delete()) {

                $Mcrmproceso= new Mcrmproceso();
                $Mcrmproceso->IdTipoProceso=$response['data']->IdTipoProceso;
                // Paginación
                $rows =  $Mcrmproceso->get_list();
                
                $Contador=1;
                foreach ($rows as $element)
                {
                    $Mcrmproceso= new Mcrmproceso();
                    $Mcrmproceso->IdCrmProceso=$element->IdCrmProceso;
                    $Mcrmproceso->Numero=$Contador;
                    $Mcrmproceso->UpdateNumber();
                    
                    $Contador ++;
                }

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
        $Mcrmproceso= new Mcrmproceso();

        $Id = (int) $this->get('IdCrmProceso');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $Mcrmproceso->IdCrmProceso = $Id;
        }
        $response =  $Mcrmproceso->get_recovery();
        if ($response['status']) {
            $data['procesos'] = $response['data'];
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

    public     function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    
        $v = new Valitron\Validator([
            'Nombre' => $this -> post('Nombre'),
            'Color' => $this -> post('Color'),
            'Color_Letra' => $this -> post('ColorLetra')
        ]);
    
        $v -> rule('required', [
            'Nombre',
            'Color',
            'Color_Letra'
        ]) -> message('El campo {field} es requerido.');
    
        if ($v -> validate()) {
            $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this->post('IdCrmProceso');

            $Mcrmproceso= new Mcrmproceso();
            $Mcrmproceso->IdSucursal= $IdSucursal;
            $Mcrmproceso->IdTipoProceso=$this->post('IdTipoProceso');
            // Paginación
            $rows =  $Mcrmproceso->get_list();
            $Numero =count( $rows) + 1;

            $Mcrmproceso= new Mcrmproceso();
            $Mcrmproceso->IdSucursal= $IdSucursal;
            $Mcrmproceso->IdTipoProceso=$this->post('IdTipoProceso');
            $Mcrmproceso->Nombre='Cierre';
            // Paginación
            $objeto =  $Mcrmproceso->get_recovery_nombre();
            if ($objeto['status'])
            {
                $Mcrmproceso= new Mcrmproceso();
                $Mcrmproceso->IdCrmProceso=$objeto['data']->IdCrmProceso;
                $Mcrmproceso->Numero=$Numero;
                $Mcrmproceso->UpdateNumber();
                // Paginación
            }
    
            $Mcrmproceso= new Mcrmproceso();
            $Mcrmproceso->IdCrmProceso= $Id;
            $Mcrmproceso->IdSucursal=$IdSucursal;
            $Mcrmproceso->Nombre= $this->post('Nombre');
            $Mcrmproceso->Numero=count($rows) ;
            $Mcrmproceso->Color= $this->post('Color');
            $Mcrmproceso->ColorLetra= $this->post('ColorLetra');
            $Mcrmproceso->RegEstatus= "A";
            $Mcrmproceso->Tipo= "0";
            $Mcrmproceso->IdTipoProceso= $this->post('IdTipoProceso');
           
            $Mcrmproceso->FechaMod = date('Y-m-d H:i:s');
            if ( $Mcrmproceso->IdCrmProceso == 0) {
    
                $Id =  $Mcrmproceso->Insert();
                if ($Id > 0) {
                    $Mcrmproceso->IdCrmProceso = $Id;
                    $response =  $Mcrmproceso->get_recovery();
                    $data['procesos'] = $response['data'];
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
                if ( $Mcrmproceso-> update()) {
                    $response =  $Mcrmproceso ->get_recovery();
                    $data['procesos'] = $response['data'];
    
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

    public     function changeposition_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    
            $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            
        //$Lista =$this->post('Lista');
        $IdIdCompJ = str_replace(array('\t','\n','\"'), "", $this->post('Lista'));
        $Lista = json_decode($IdIdCompJ);

           $Contador=1;
           foreach ($Lista as $element)
           {
                $Mcrmproceso= new Mcrmproceso();
                $Mcrmproceso->IdCrmProceso= $element->IdCrmProceso;
                $Mcrmproceso->Numero=$Contador;
                $Mcrmproceso->UpdateNumber();
                $Contador ++;
           }

           return $this -> set_response([
            'status' => true,
            'message' => 'Se ha actualizado correctamente.',
        ], REST_Controller:: HTTP_ACCEPTED);  
    }

    public function oportunidadxProceso_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmproceso= new Mcrmproceso();
        $Mcrmproceso->IdSucursal= $IdSucursal;
        $Mcrmproceso->IdTipoProceso=$this->get('IdTipoProceso');
        $Mcrmproceso->IdProceso=$this->get('IdProceso');
        $Mcrmproceso->IdTrabajador=$this->get('IdTrabajador');
        $Mcrmproceso->IdClienteSucursal=$this->get('IdClienteSucursal');
        $Mcrmproceso->IdCliente=$this->get('IdCliente');
        $Mcrmproceso->IdOportunidad=$this->get('IdOportunidad');
        $Mcrmproceso->Fecha=$this->get('Fecha');
        // Paginación
        $rows =  $Mcrmproceso->get_oportunidadxProceso();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }

        $Mcrmproceso->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $Mcrmproceso->Tamano = $pager->PageSize;
        $Mcrmproceso->Offset = $pager->Offset;

       


        $data['oportunidadxProceso'] = $Mcrmproceso->get_oportunidadxProceso();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function procesoxSucursal_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmproceso= new Mcrmproceso();
        // $Mcrmproceso->IdSucursal= $IdSucursal;
        $Mcrmproceso->IdTrabajador=$this->get('IdTrabajador');
        $Mcrmproceso->IdTipoProceso=$this->get('IdTipoProceso');
        // Paginación
       
       


        $data['SucursalxProceso'] = $Mcrmproceso->get_SucursalxProceso();

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function procesoxClienteSucursal_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmproceso= new Mcrmproceso();
        $Mcrmproceso->IdTrabajador=$this->get('IdTrabajador');
        $Mcrmproceso->IdCliente=$this->get('IdCliente');
        $Mcrmproceso->IdTipoProceso=$this->get('IdTipoProceso');
        // Paginación
       
       


        $data['ClienteSucursalxProceso'] = $Mcrmproceso->get_ClienteSucxProceso();

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function VendedorxProcesoxOportunidad_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mcrmproceso= new Mcrmproceso();
        // $Mcrmproceso->IdSucursal= $IdSucursal;
        $Mcrmproceso->IdTipoProceso=$this->get('IdTipoProceso');
        $Mcrmproceso->IdClienteSucursal=$this->get('IdClienteSucursal');
        $Mcrmproceso->IdTrabajador=$this->get('IdTrabajador');
        // Paginación
       
       


        $data['VendedorxProcesoxOportunidad'] = $Mcrmproceso->get_VendedorxProcesoxOportunidad();

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}