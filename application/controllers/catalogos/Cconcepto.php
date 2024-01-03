<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cconcepto extends REST_Controller
{
    public $Ruta='assets/files/concepto/';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mconcepto');
        $this->load->library('UploadFile');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
         if (empty($this->get('IdEquipamiento'))) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } 

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objConcepto = new Mconcepto();
        $objConcepto->IdEquipamiento= $this->get('IdEquipamiento');
        $objConcepto->Nombre= $this->get('Nombre');
        $objConcepto->RegEstatus= $this->get('RegEstatus');
        $rows= $objConcepto->get_list();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
        $Entrada =$this->get('Entrada');
        }
        $objConcepto->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objConcepto->Tamano = $pager->PageSize;
        $objConcepto->Offset = $pager->Offset;

        $objConcepto->get_list();

        $data['ruta'] =base_url().$this->Ruta;
        $data['concepto'] =$rows;
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
        $objConcepto= new Mconcepto();

        $objConcepto->IdConcepto= $Id;
  
        $response = $objConcepto->get_concepto();

        if ($response['status']) {

            if ( $objConcepto->delete()) {

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
        $objConcepto= new Mconcepto();

        $Id = (int) $this->get('IdConcepto');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objConcepto->IdConcepto = $Id;
        }
        $response =  $objConcepto->get_concepto();
        if ($response['status']) {
            $data['concepto'] = $response['data'];
            $data['ruta'] =base_url().$this->Ruta;
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

    public  function Add_post() {
        // Valid Token
        if (!$this->verification->validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Nombre' => $this -> post('Nombre'),
            'Equipamiento' => $this -> post('IdEquipamiento'),
            'Valor' => $this -> post('Valor'),
            'Meses' => $this -> post('Meses'),
        ]);

        $v -> rule('required', [
            'Nombre',
            'Equipamiento',
            'Valor',
            'Meses',
        
        ]) -> message('El campo {field} es requerido.');
        if ($v -> validate()) {
            $Id = $this -> post('IdConcepto');
            $objConcepto= new Mconcepto();
            $objConcepto->IdConcepto= $Id;
            $objConcepto->IdEquipamiento= $this->post('IdEquipamiento');
            $objConcepto->Nombre= $this->post('Nombre');
            $objConcepto->Valor= $this->post('Valor');
            $objConcepto->Meses= $this->post('Meses');
            $objConcepto->RegEstatus="A";
            $objConcepto->FechaMod = date('Y-m-d H:i:s');

            $files = $this->uploadfile->savefile($this->Ruta, 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);
            $objConcepto->Foto=$files;
        
            if ($objConcepto->IdConcepto == 0) {
                $Id =   $objConcepto-> insert();
                if ($Id > 0) {
                    $objConcepto->IdConcepto = $Id;
                    $response =  $objConcepto-> get_concepto();
                    $data['concepto'] = $response['data'];
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
                if (  $objConcepto-> update()) {
                    $response =  $objConcepto-> get_concepto();
                    $data['concepto'] = $response['data'];

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