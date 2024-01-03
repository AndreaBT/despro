<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ctiposervicio extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mtiposervicio');
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

        $objTiposervicio = new Mtiposervicio();
        $objTiposervicio->Concepto = $this->get('Nombre');
        $objTiposervicio->IdSucursal =$IdSucursal;
        $objTiposervicio->RegEstatus = $this->get('RegEstatus');
        $objTiposervicio->IdConfigS = $this->get('IdConfigS');
        $objTiposervicio->IdTipoSer = $this->get('IdTipoSer');
    
        // Paginación
        $rows = $objTiposervicio->get_list();
        
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }

        $objTiposervicio->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'),$Entrada);

        $objTiposervicio->Tamano = $pager->PageSize;
        $objTiposervicio->Offset = $pager->Offset;

        $data['tiposervicio'] =  $objTiposervicio->get_list();
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
        $objTiposervicio = new Mtiposervicio();

        $objTiposervicio->IdTipoSer = $Id;
  
        $response =$objTiposervicio->get_tiposervicio();

        if ($response['status']) {

            if ($objTiposervicio->delete()) {

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
        $objTiposervicio = new Mtiposervicio();

        $Id = (int) $this->get('IdTipoSer');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objTiposervicio->IdTipoSer = $Id;
        }
        $response = $objTiposervicio->get_tiposervicio();
        if ($response['status']) {
            $data['tiposervicio'] = $response['data'];
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
            'Concepto' => $this -> post('Concepto'),
    
            'GrossM' => $this -> post('GrossM'),
            'Color' => $this -> post('Color'),
            'Icono' => $this -> post('IdIcono'),
            'IdConfigS' => $this -> post('IdConfigS'),
            'Factura' => $this -> post('Ingresos')
            
        ]);

        $v -> rule('required', [
            'Concepto',
            'GrossM',
            'Color',
            'Icono',
            'IdConfigS',
            'Factura'
        ]) -> message('El campo {field} es requerido.');
        
        
        $v -> rule('numeric', [
            'GrossM'
        ]) -> message('El campo {field} debe ser numerico.');

        if ($v -> validate()) {

            $Id = $this -> post('IdTipoSer');

            $objTiposervicio = new Mtiposervicio();
            $objTiposervicio->IdTipoSer= $this->post('IdTipoSer');
            $objTiposervicio->Concepto = $this->post('Concepto');
            $objTiposervicio->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objTiposervicio->RegEstatus =    "A";
            $objTiposervicio->GrossM = $this->post('GrossM');
            $objTiposervicio->Color = $this->post('Color');
            $objTiposervicio->ColorLetra = $this->post('ColorLetra');
            $objTiposervicio->Ingresos = $this->post('Ingresos');
            $objTiposervicio->IdIcono = $this->post('IdIcono');
            $objTiposervicio->Tipo = $this->post('IdConfigS');
            $objTiposervicio->IdConfigS = $this->post('IdConfigS');
            $objTiposervicio->FechaMod = date('Y-m-d H:i:s');
            if ($objTiposervicio->IdTipoSer == 0) {

                $Id =  $objTiposervicio-> insert();
                if ($Id > 0) {
                    $objTiposervicio->IdTipoSer = $Id;
                    $response =    $objTiposervicio-> get_tiposervicio();
                    $data['tiposervicio'] = $response['data'];
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
                if ($objTiposervicio-> update()) {
                    $response = $objTiposervicio -> get_tiposervicio();
                    $data['tiposervicio'] = $response['data'];

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