<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccajachica extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcajachica');
        
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

        $objCajachica = new Mcajachica();
        $objCajachica  ->IdCajaC= $this->get('IdCajaC');
        $objCajachica->IdSucursal = $IdSucursal;
        $objCajachica->Nombre = $this->get('Nombre');
        $objCajachica->FechaInicio = $this->get('FechaInicio');
        $objCajachica->FechaFin=dateformato($this->get('FechaFin'));
        $objCajachica->Estado = $this->get('Estado');
        $objCajachica->Tipo = $this->get('Tipo');
        // Paginación
        $rows =  $objCajachica->get_list();
        
         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $objCajachica->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objCajachica->Tamano = $pager->PageSize;
        $objCajachica->Offset = $pager->Offset;

        $data['cajachica'] = $objCajachica->get_list();
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
      
        $objCajachica = new Mcajachica();
        $objCajachica ->IdCajaC = $Id;
  
        $response = $objCajachica  ->get_cajachica();

        if ($response['status']) {

            if ( $objCajachica ->delete()) {

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
        $objCajachica = new Mcajachica();

        $Id = (int) $this->get('IdCajaC');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objCajachica ->IdCajaC = $Id;
        }
        $response = $objCajachica->get_cajachica();
        if ($response['status']) {
            $data['cajachica'] = $response['data'];
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
            'Caja' => $this -> post('IdCaja'),
            'Monto' => $this -> post('Monto'),
            'FechaInicio' => $this -> post('FechaInicio'),
            'FechaFin' => $this -> post('FechaFin'),
            

        ]);

        $v -> rule('required', [
            'Caja',
            'Monto',
            'FechaInicio',
            'FechaFin'
    
        ]) -> message('El campo {field} es requerido.');
        
        $v->rule('min', 'Monto', 1);
        $v->rule('min', 'Caja', 1);

        if ($v -> validate()) {

            $Id = $this -> post('IdCajaC');

            $objCajachica = new Mcajachica();
            $objCajachica ->IdCajaC = $this->post('IdCajaC');
            $objCajachica ->IdCaja = $this->post('IdCaja');

            $objCajachica ->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objCajachica ->Nombre = '';
            $objCajachica ->FechaInicio = dateformato($this->post('FechaInicio'));
            $objCajachica ->FechaFin = dateformato($this->post('FechaFin'));
            $objCajachica ->Monto = $this->post('Monto');
            $objCajachica->RegEstatus =    "A";
            $objCajachica->Estado = $this->post('Estado');
            $objCajachica->Utilizado = $this->post('Monto');
            $objCajachica->FechaMod = date('Y-m-d H:i:s');

            if (  $objCajachica->IdCajaC == 0) {
                $Id = $objCajachica-> insert();
                if ($Id > 0) {
                    $objCajachica->IdCajaC= $Id;
                    $response = $objCajachica-> get_cajachica();
                    $data['cajachica'] = $response['data'];
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
                $objCajachica->Utilizado = $this->post('Utilizado');
                $Aumentar=$this->post('Aumentar');
                if ($this->post('Aumentar')=='')
                {
                    $Aumentar=0;
                }
                $objCajachica ->Monto = $this->post('Monto') +$Aumentar;
                if ( $objCajachica-> update()) {
                    $response = $objCajachica-> get_cajachica();
                    $data['cajachica'] = $response['data'];
    
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