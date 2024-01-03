<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CactualizarCostOp extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('finanzas/Mconceptooperacion');
        $this->load->model('finanzas/Mestadofupdate');

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
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $v = new Valitron\Validator([
            'Anio' => $this->get('Anio'),
            'Mes' => $this->get('Mes'),
            'IdConfigS' => $this->get('IdConfigS'),
            'IdTipoServ' => $this->get('IdTipoServ')
        ]);

        $v->rule('required', [
            'Anio',
            'Mes',
            'IdConfigS',
            'IdTipoServ'

        ])->message('El campo {field} es requerido.');


        if ($v->validate()) {

            $Anio = $this->get('Anio');
            $Mes = $this->get('Mes');
            $IdConfigS = $this->get('IdConfigS');
            $IdTipoServ = $this->get('IdTipoServ');


            $oMestadofupdate = new Mestadofupdate();
            $oMestadofupdate->IdConfigServ = $IdConfigS;
            $oMestadofupdate->IdTipoServ = $IdTipoServ;
            $oMestadofupdate->Anio = $Anio;
            $oMestadofupdate->Mes = $Mes;
            $oMestadofupdate->IdSucursal = $IdSucursal;
            $row = $oMestadofupdate->get_list();

            if (count($row) == 0) {

                $oMconceptooperacion = new Mconceptooperacion();
                // Paginaci�n
                $row =  $oMconceptooperacion->get_list();

                $count = 0;
                foreach ($row as $element) {
                    $row[$count]->Descripcion = $element->Nombre;
                    $row[$count]->Monto = '';
                    $count++;
                }
            }

            $data['lista'] = $row;

            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'data' => 'error',
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public  function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $v = new Valitron\Validator([
            'Anio' => $this->post('Anio'),
            'Mes' => $this->post('Mes'),
            'IdConfigS' => $this->post('IdConfigS'),
            'IdTipoServ' => $this->post('IdTipoServ')
        ]);

        $v->rule('required', [
            'Anio',
            'Mes',
            'IdConfigS',
            'IdTipoServ'

        ])->message('El campo {field} es requerido.');


        if ($v->validate()) {

            $Anio = $this->post('Anio');
            $Mes = $this->post('Mes');
            $IdConfigS = $this->post('IdConfigS');
            $IdTipoServ = $this->post('IdTipoServ');

            $Detalle = $this->post('Detalle');

            $oMestadofupdate = new Mestadofupdate();
            $oMestadofupdate->IdConfigServ = $IdConfigS;
            $oMestadofupdate->IdTipoServ = $IdTipoServ;
            $oMestadofupdate->Anio = $Anio;
            $oMestadofupdate->Mes = $Mes;
            $oMestadofupdate->IdSucursal = $IdSucursal;
            $row = $oMestadofupdate->get_list();

            foreach ($Detalle as $element) {
                $Monto = $element['Monto'];
                if ($Monto == '') {
                    $Monto = 0;
                }

                $oMestadofupdate = new Mestadofupdate();
                $oMestadofupdate->IdConfigServ = $IdConfigS;
                $oMestadofupdate->IdTipoServ = $IdTipoServ;
                $oMestadofupdate->Anio = $Anio;
                $oMestadofupdate->Mes = $Mes;
                $oMestadofupdate->Monto = $Monto;
                $oMestadofupdate->IdSucursal = $IdSucursal;
                $oMestadofupdate->Descripcion = $element['Descripcion'];
                if (count($row) == 0) {
                    $oMestadofupdate->insert();
                } else {
                    //echo $Monto;
                    $oMestadofupdate->update();
                }
            }

            return $this->set_response([
                'status' => true,
                'data' => 'Insertado',
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller::HTTP_CREATED);
        } else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'data' => 'error',
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}