
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccategoriavehiculo extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcategoriavehiculo');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objCategoriavehiculo = new Mcategoriavehiculo();
        $objCategoriavehiculo->Nombre = $this->get('Nombre');
        $objCategoriavehiculo->RegEstatus = $this->get('RegEstatus');
        $objCategoriavehiculo->IdSucursal = $IdSucursal;
      
        // Paginación
        $rows =   $objCategoriavehiculo->get_list();
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $objCategoriavehiculo->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $objCategoriavehiculo->Tamano = $pager->PageSize;
        $objCategoriavehiculo->Offset = $pager->Offset;

        $data['categoriavehiculo'] =   $objCategoriavehiculo->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findAllCat_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objCategoriavehiculo = new Mcategoriavehiculo();
        $objCategoriavehiculo->Nombre = $this->get('Nombre');
        $objCategoriavehiculo->RegEstatus = $this->get('RegEstatus');
        $objCategoriavehiculo->IdSucursal = $IdSucursal;
      
        // Paginación
        $rows =   $objCategoriavehiculo->get_listCat();
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $objCategoriavehiculo->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $objCategoriavehiculo->Tamano = $pager->PageSize;
        $objCategoriavehiculo->Offset = $pager->Offset;

        $data['categoriavehiculo'] =   $objCategoriavehiculo->get_listCat();
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
        $objCategoriavehiculo = new Mcategoriavehiculo();
        $objCategoriavehiculo->IdCategoria = $Id;
  
        $response =  $objCategoriavehiculo->get_categoriavehiculo();

        if ($response['status']) {

            if ($objCategoriavehiculo->delete()) {

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
        $objCategoriavehiculo= new Mcategoriavehiculo();

        $Id = (int) $this->get('IdCategoria');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objCategoriavehiculo->IdCategoria = $Id;
        }
        $response =  $objCategoriavehiculo->get_categoriavehiculo();
        if ($response['status']) {
            $data['categoriavehiculo'] = $response['data'];
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
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objCategoriavehiculo= new Mcategoriavehiculo();
        $objCategoriavehiculo->IdSucursal = $IdSucursal;

        $response =  $objCategoriavehiculo->get_Virtual();
        if ($response['status']) {
            $data['categoriavehiculo'] = $response['data'];
                $Id = $this -> post('IdCategoria');
    
                $objCategoriavehiculo= new Mcategoriavehiculo();
                $objCategoriavehiculo->IdCategoria = $this -> post('IdCategoria');
                $objCategoriavehiculo->Nombre = $this->post('Nombre');
                $objCategoriavehiculo->RegEstatus = "A";
                $objCategoriavehiculo->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
                $objCategoriavehiculo->FechaMod = date('Y-m-d H:i:s');
        
                if ($objCategoriavehiculo->IdCategoria == 0 && $response['data']->Nombre ==  $objCategoriavehiculo->Nombre = $this->post('Nombre') && $this->post('Nombre')=="virtual"  ||  $this->post('Nombre')=="Virtual 1" ||  $this->post('Nombre')=="virtual1" || $this->post('Nombre')=="ViRtUaL" ) {
                    return $this->set_response([
                        'status' => false,
                        'message' => 'virtual ya exste',
                    ], REST_Controller::HTTP_BAD_REQUEST);
    
                    
                }elseif($objCategoriavehiculo->IdCategoria == 0 && $response['data']->Nombre !=  $objCategoriavehiculo->Nombre = $this->post('Nombre')){

                    $Id = $objCategoriavehiculo-> insert();
                    if ($Id > 0) {
                        $objCategoriavehiculo->IdCategoria= $Id;
                        $response=$objCategoriavehiculo-> get_categoriavehiculo();
                        $data['categoriavehiculo'] = $response['data'];
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

                    if ($objCategoriavehiculo-> update()) {
                        $response =$objCategoriavehiculo-> get_categoriavehiculo();
                        $data['categoriavehiculo'] = $response['data'];
    
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
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'EL AUTO VIRTUAL YA EXISTE',
            ],REST_Controller::HTTP_OK);
        }
        
    }
}