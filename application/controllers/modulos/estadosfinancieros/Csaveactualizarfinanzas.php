<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Csaveactualizarfinanzas extends REST_Controller
{
    public $RutaPdf;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('estadosf/Mpersonaloperativo');
        $this->load->model('estadosf/Mdetalleestadofinanciero');
        $this->load->model('estadosf/Mestadosfinancieros');
        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('estadosf/Mporcentajeoperacion');
        setTimeZone($this->verification,$this->input);
    }


    public  function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        
        $Facturacion = $this->post('Facturacion');
        $Mes = $this->post('Mes');
        $Anio = $this->post('Anio');
        $IdConfigS = $this->post('IdConfigS');
        $IdTipoServ = $this->post('IdTipoServ');
        $IdCliente = $this->post('IdCliente');
        $IdClienteS = $this->post('IdClienteS');
        $IdContrato = $this->post('IdContrato');

        $Materiales = $this->post('Materiales');
        $Equipos = $this->post('Equipos');
        $ManoDeObra = $this->post('ManoDeObra');
        $Vehiculos = $this->post('Vehiculos');
        $Contratistas = $this->post('Contratistas');
        $Burden = $this->post('Burden');
        $Viaticos = $this->post('Viaticos');
        $FacturacionPMensual = $this->post('FacturacionPMensual');


        $v = new Valitron\Validator([
            'Anio'      => $Anio,
            'Mes'       => $Mes,
            'IdConfig'  => $IdConfigS,
            'IdTipoServ'=> $IdTipoServ,
            'IdCliente' => $IdCliente,
            'IdClienteS'=> $IdClienteS,
            //'IdContrato'=> $IdContrato,
        ]);

        $v->rule('required', ['Anio','Mes','IdConfig','IdTipoServ','IdCliente','IdClienteS'])->message('El campo {field} es requerido.');

        if ($Mes < 10) {
            $Mes = '0' . $Mes;
        } 


        if ($v->validate())
        {
            $op = 'Guardar';

            $oMestadofinanciero= new Mestadofinanciero();
            $oMestadofinanciero->IdSucursal = $IdSucursal;
            $oMestadofinanciero->IdConfigS = $IdConfigS;
            $oMestadofinanciero->IdTipoServ=$IdTipoServ;
            $oMestadofinanciero->Anio = $Anio;
            $oMestadofinanciero->Mes = $Mes;
            $oMestadofinanciero->IdCliente = $IdCliente;
            $oMestadofinanciero->IdClienteS = $IdClienteS;
            $oMestadofinanciero->IdContrato = $IdContrato;
            $dataexist = $oMestadofinanciero->get_recovery();

            $IdEstado = 0;
            if($dataexist['status']){
                $op = 'Modificar';
                $IdEstado = $dataexist['data']->IdEstadoF;
            }

            $odetalleestadofinanciero = new Mdetalleestadofinanciero();
            $oestadofinanciero = new Mestadosfinancieros(); 

            switch ($op)
            {
                case 'Guardar':  

                    $oestadofinanciero->IdSucursal  = $IdSucursal;
                    $oestadofinanciero->IdConfigS   = $IdConfigS;
                    $oestadofinanciero->Anio        = $Anio;
                    $oestadofinanciero->Mes         = $Mes;
                    $oestadofinanciero->IdTipoServ  = $IdTipoServ;
                    $oestadofinanciero->IdCliente   = $IdCliente;
                    $oestadofinanciero->IdClienteS  = $IdClienteS;
                    $oestadofinanciero->IdContrato  = $IdContrato;

                    if($Facturacion == '') {
                        $Facturacion = 0;
                    }

                    if($Materiales == '') {
                        $Materiales = 0;
                    }
                    if($Equipos == '') {
                        $Equipos = 0;
                    }
                    if($ManoDeObra == '') {
                        $ManoDeObra = 0;
                    }
                    if($Vehiculos == '') {
                        $Vehiculos = 0;
                    }
                    if($Contratistas == '') {
                        $Contratistas = 0;
                    }
                    if($Burden == '') {
                        $Burden = 0;
                    }
                    if($Viaticos  == '') {
                        $Viaticos  = 0;
                    }
                    if($FacturacionPMensual == '') {
                        $FacturacionPMensual = 0;
                    }

                    $oestadofinanciero->Facturacion = $Facturacion;
                    $oestadofinanciero->Materiales = $Materiales;
                    $oestadofinanciero->Equipos = $Equipos;
                    $oestadofinanciero->ManoDeObra = $ManoDeObra;
                    $oestadofinanciero->Vehiculos = $Vehiculos;
                    $oestadofinanciero->Contratistas = $Contratistas;
                    $oestadofinanciero->Burden = $Burden;
                    $oestadofinanciero->Viaticos = $Viaticos;
                    $oestadofinanciero->FacturacionPMensual = $FacturacionPMensual;
                    
                    $value = $oestadofinanciero->set_insert_estadofinanciero();

                    if($value>0)
                    {
                        $cont = 0;   
                        foreach($this->post('Detalle') as $Anterior)
                        {
                            $odetalleestadofinanciero = new Mdetalleestadofinanciero();
                            $montoAnioAnterior = $Anterior['AnioAnteriorMonto'];
                            if ($montoAnioAnterior == '') {
                                $montoAnioAnterior = 0;
                            }

                            $odetalleestadofinanciero->Pasado = $montoAnioAnterior;
                            $odetalleestadofinanciero->PorcentajePasado = $Anterior['AnioAnteriorPorcen'];
                            $odetalleestadofinanciero->IdPlanFactura = $Anterior['IdPlanFactura'];
                            $odetalleestadofinanciero->IdEstadoF = $value;
                            $odetalleestadofinanciero->set_insert_detalleestadofinanciero();

                            $cont++;
                        }
                    }

                    //echo 1;

                break;

                case 'Modificar':

                    $oestadofinanciero->IdEstadoF   = $IdEstado;
                    $oestadofinanciero->IdTipoServ  = $IdTipoServ;
                    $oestadofinanciero->IdCliente   = $IdCliente;
                    $oestadofinanciero->IdClienteS  = $IdClienteS;
                    $oestadofinanciero->IdContrato  = $IdContrato;

                    if($Facturacion == '') {
                        $Facturacion = 0;
                    }

                    if($Materiales == '') {
                        $Materiales = 0;
                    }
                    if($Equipos == '') {
                        $Equipos = 0;
                    }
                    if($ManoDeObra == '') {
                        $ManoDeObra = 0;
                    }
                    if($Vehiculos == '') {
                        $Vehiculos = 0;
                    }
                    if($Contratistas == '') {
                        $Contratistas = 0;
                    }
                    if($Burden == '') {
                        $Burden = 0;
                    }
                    if($Viaticos  == '') {
                        $Viaticos  = 0;
                    }
                    if($FacturacionPMensual == '') {
                        $FacturacionPMensual = 0;
                    }

                    $oestadofinanciero->Facturacion = $Facturacion;
                    $oestadofinanciero->Materiales = $Materiales;
                    $oestadofinanciero->Equipos = $Equipos;
                    $oestadofinanciero->ManoDeObra = $ManoDeObra;
                    $oestadofinanciero->Vehiculos = $Vehiculos;
                    $oestadofinanciero->Contratistas = $Contratistas;
                    $oestadofinanciero->Burden = $Burden;
                    $oestadofinanciero->Viaticos = $Viaticos;
                    $oestadofinanciero->FacturacionPMensual = $FacturacionPMensual;
                    $oestadofinanciero->set_update_estadofinanciero();

                    if($IdEstado > 0)
                    {
                        $cont = 0;

                        $odetalleestadofinanciero->IdEstadoF = $IdEstado;
                        $odetalleestadofinanciero->set_delete_detalleestadofinanciero();

                        foreach($this->post('Detalle') as $Anterior)
                        {
                            $odetalleestadofinanciero = new Mdetalleestadofinanciero();
                            $montoAnioAnterior = $Anterior['AnioAnteriorMonto'];
                            if ($montoAnioAnterior == '') {
                                $montoAnioAnterior = 0;
                            }

                            $odetalleestadofinanciero->Pasado = $montoAnioAnterior;
                            $odetalleestadofinanciero->PorcentajePasado = $Anterior['AnioAnteriorPorcen'];
                            $odetalleestadofinanciero->IdPlanFactura = $Anterior['IdPlanFactura'];
                            $odetalleestadofinanciero->IdEstadoF = $IdEstado;
                            $odetalleestadofinanciero->set_insert_detalleestadofinanciero();

                            $cont++;
                        }
                    }

                    //echo 1;
                    break;
            }

            return $this->set_response([
                'status' => true,
                'data' => 'Insertado',
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller::HTTP_CREATED);
        }
        else
        {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'data' => 'error',
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
