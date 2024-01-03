
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccategoriapersonal extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcategoriapersonal');
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

        $objCategoriapersonal = new Mcategoriapersonal();
        $objCategoriapersonal->Nombre = $this->get('Nombre');
        $objCategoriapersonal->RegEstatus = $this->get('RegEstatus');
        $objCategoriapersonal->IdSucursal = $IdSucursal;
      
        // Paginación
        $rows =   $objCategoriapersonal->get_list();
        
         $Entrada='';
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }

        $objCategoriapersonal->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'),$Entrada);

        $objCategoriapersonal->Tamano = $pager->PageSize;
        $objCategoriapersonal->Offset = $pager->Offset;

        $data['categoriapersonal'] =  $objCategoriapersonal->get_list();
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
        $objCategoriapersonal = new Mcategoriapersonal();
        $objCategoriapersonal ->IdCategoria = $Id;
  
        $response =   $objCategoriapersonal->get_categoriapersonal();

        if ($response['status']) {

            if (   $objCategoriapersonal->delete()) {

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
        $objCategoriapersonal = new Mcategoriapersonal();

        $Id = (int) $this->get('IdCategoria');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objCategoriapersonal->IdCategoria = $Id;
        }
        $response = $objCategoriapersonal->get_categoriapersonal();
        if ($response['status']) {
            $data['categoriapersonal'] = $response['data'];
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
        'Nombre' => $this -> post('Nombre')
    ]);

    $v -> rule('required', [
        'Nombre'
    ]) -> message('El campo {field} es requerido.');

    if ($v -> validate()) {

        $Id = $this -> post('IdCategoria');

        $objCategoriapersonal = new Mcategoriapersonal();
        $objCategoriapersonal->IdCategoria = $this -> post('IdCategoria');
        $objCategoriapersonal->Nombre = $this->post('Nombre');
        $objCategoriapersonal ->RegEstatus = "A";
        $objCategoriapersonal->IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objCategoriapersonal->FechaMod=date('Y-m-d H:i:s');
   
        if ($objCategoriapersonal->IdCategoria == 0) {

            $Id =  $objCategoriapersonal-> insert();
            if ($Id > 0) {
                $objCategoriapersonal->IdCategoria= $Id;
                $response =  $objCategoriapersonal -> get_categoriapersonal();
                $data['categoriapersonal'] = $response['data'];
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
            if ($objCategoriapersonal-> update()) {
                $response = $objCategoriapersonal-> get_categoriapersonal();
                $data['categoriapersonal'] = $response['data'];

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