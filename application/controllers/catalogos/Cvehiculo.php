<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cvehiculo extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mvehiculo');
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
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objVehiculo = new Mvehiculo();
        $objVehiculo->IdVehiculo= $this->get('IdVehiculo');
        $objVehiculo->Categoria = $this->get('Nombre');
        $objVehiculo->TipoVehiculo = $this->get('TipoVehiculo');
        $objVehiculo->IdSucursal = $IdSucursal;
        $objVehiculo->RegEstatus= $this->get('RegEstatus');
    
        // Paginación
        $rows =  $objVehiculo->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $objVehiculo->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'),$Entrada);

        $objVehiculo->Tamano = $pager->PageSize;
        $objVehiculo->Offset = $pager->Offset;

        $data['vehiculo'] =   $objVehiculo->get_list();
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
        $objVehiculo= new Mvehiculo();

        $objVehiculo->IdVehiculo = $Id;
  
        $response =  $objVehiculo->get_vehiculo();

        if ($response['status']) {

            if (  $objVehiculo->delete()) {

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
        $objVehiculo= new Mvehiculo();

        $Id = (int) $this->get('IdVehiculo');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objVehiculo->IdVehiculo = $Id;
        }
        $response =   $objVehiculo->get_vehiculo();
        if ($response['status']) {
            $data['vehiculo'] = $response['data'];
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
    
    $oMcategoriavehiculo = new Mcategoriavehiculo();
    $oMcategoriavehiculo->IdCategoria=$this -> post('IdCategoria');
    $res= $oMcategoriavehiculo->get_categoriavehiculo();
   
    $v = new Valitron\Validator([
        'Categoria' => $this -> post('Categoria'),
        'Marca' => $this -> post('Marca'),
        'Modelo' => $this -> post('Modelo'),
        'Ano' => $this -> post('Ano'),
        'Placa' => $this -> post('Placa'),
        'Numero' => trim($this -> post('Numero')),
        'TipoVehiculo' => $this -> post('TipoVehiculo'),
        'CostoAnual' => $this -> post('CostoAnual'),
        'IdCategoria' => $this -> post('IdCategoria')
    ]);
    
     $v -> rule('required', [
        'Categoria',
        'Numero',
        'Marca',
        'Modelo',
        'Ano',
        'Placa',
        'TipoVehiculo',
        'CostoAnual',
        'IdCategoria'
    ]) -> message('El campo {field} es requerido.');
   
   if ($res['status'])
   {
       if ($res['data']->Nombre=='VIRTUAL')
       {
          $v = new Valitron\Validator([
                'Categoria' => $this -> post('Categoria'),
                'TipoVehiculo' => $this -> post('TipoVehiculo'),
                'IdCategoria' => $this -> post('IdCategoria')
            ]); 
            
            
            $v -> rule('required', [
                'Categoria',
                'TipoVehiculo',
                'IdCategoria'
            ]) -> message('El campo {field} es requerido.'); 
       }
   }
   
    if ($v -> validate()) {
        
        $CostoA=$this->post('CostoAnual');
        if ($this->post('CostoAnual')=='')
        {
            $CostoA=0;
        }

        $Id = $this -> post('IdVehiculo');
        $objVehiculo= new Mvehiculo();
        $objVehiculo->IdVehiculo= $this->post('IdVehiculo');
        $objVehiculo->Categoria = $this->post('Categoria');
        $objVehiculo->Marca = $this->post('Marca');
        $objVehiculo->Modelo = $this->post('Modelo');
        $objVehiculo->Ano= $this->post('Ano');
        $objVehiculo->Placa = $this->post('Placa');
        $objVehiculo->Numero = $this->post('Numero');
        $objVehiculo->TipoVehiculo = $this->post('TipoVehiculo');
        $objVehiculo->CostoAnual= $this->post('CostoAnual');
        $objVehiculo->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objVehiculo->RegEstatus= "A";
        $objVehiculo->IdCategoria= $this->post('IdCategoria');
        $objVehiculo->Inventario= $this->post('Inventario');
        $objVehiculo->Historial = $this->post('Historial');
        $objVehiculo->FechaMod=date('Y-m-d H:i:s');

        if ( $objVehiculo->IdVehiculo == 0) {

            $Id = $objVehiculo-> insert();
            if ($Id > 0) {
                $objVehiculo->IdVehiculo = $Id;
                $response =$objVehiculo-> get_vehiculo();
                $data['vehiculo'] = $response['data'];
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
            if ($objVehiculo-> update()) {
                $response = $objVehiculo-> get_vehiculo();
                $data['vehiculo'] = $response['data'];

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