<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cseguimientocliente extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('crm/Mseguimientocliente');
        $this->load->model('crm/Mcrmproceso');
        $this->load->model('crm/Mcrmtipoproceso');
        $this->load->model('crm/Moportunidades');
        $this->load->library('UploadFile');
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        setTimeZoneEmpresa($IdSucursal);
        $Mseguimientocliente = new Mseguimientocliente();
        $Mseguimientocliente->Actividad = $this->get('Nombre');
        $Mseguimientocliente->Fecha = $this->get('Fecha');
        $Mseguimientocliente->IdTrabajador = $this->get('IdTrabajador');
        $Mseguimientocliente->IdSucursal = $IdSucursal;

        // Paginación
        $rows =  $Mseguimientocliente->get_list();
        $obj =  $Mseguimientocliente->get_list_Obj();
        //echo $this->get('Fecha');
        // $Entrada=10;
        // if ($this->get('Entrada')!='')
        // {
        //    $Entrada =$this->get('Entrada');
        // }

        // $Mseguimientocliente->Limit=$Entrada;

        // $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        // $Mseguimientocliente->Tamano = $pager->PageSize;
        // $Mseguimientocliente->Offset = $pager->Offset;
        $data['seguimiento'] = $rows; //$Mseguimientocliente->get_list();
        $data['objeto'] = $obj['data'];
        //$data['pagination']= $pager;

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
        $Mseguimientocliente = new Mseguimientocliente();
        $Mseguimientocliente->IdSeguimientoCliente = $Id;
        //$Mseguimientocliente->IdOportunidad = ;
        /*$Mseguimientocliente->FechaMod = date('Y-m-d H:i:s');*/

        $response = $Mseguimientocliente->get_recovery();

        if ($response['status']) {

            $Oportunidad = new Moportunidades();
            $Oportunidad->IdOportunidad = $response['data']->IdOportunidad;
            $Oportunidad->FechaMod = date('Y-m-d H:i:s');

            $resultado = $Oportunidad->UpdateEstadoDelete();

            if ($response['data']->IdEstatus == "Cerrada" || $response['data']->IdEstatus == "Vendido") {

                if ($resultado) {
                    if ($Mseguimientocliente->delete()) {

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
                }
            } else {
                if ($Mseguimientocliente->delete()) {

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
        $IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Mseguimientocliente = new Mseguimientocliente();

        $Id = (int) $this->get('IdSeguimientoCliente');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $Mseguimientocliente->IdSeguimientoCliente = $Id;
        }
        $response =  $Mseguimientocliente->get_recovery();
        if ($response['status']) {

            $RutaPrincipal = "assets/files/comprobantecrm/" . $IdSucursal . '/';

            $data['seguimiento'] = $response['data'];
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'ruta' => base_url() . $RutaPrincipal,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $Monto = '';
        $MontoProp = 0;

        if ($this->post('Estatus') == 'Abierta' || $this->post('Estatus') == 'Cerrada') {
            $Monto = '10';
        } else {
            $Monto = $this->post('MontoP');
        }

        if ($this->post('MontoPropuesta') > 0) {
            $MontoProp = $this->post('MontoPropuesta');
        }

        $HoraIni = $this->post('HoraInicio');
        $HoraFin = $this->post('HoraFin');

        if($HoraIni!=''){
            $HoraIni = $HoraIni.':00';
        }

        if($HoraFin!=''){
            $HoraFin = $HoraFin.':00';
        }

        $v = new Valitron\Validator([
            'Vendedor'          => $this->post('IdTrabajador'),
            'Cliente'           => $this->post('IdClienteSucursal'),
            //antiguamente llamado proceso (es etapa)
            'Etapa'             => $this->post('IdProceso'),
            'Actividad'         => $this->post('Actividad'),
            'Servicio'          => $this->post('IdConfigS'),
            'Monto'             => $Monto,
            'MontoPropuesta'    => $MontoProp,
            'Oportunidad'       => $this->post('IdOportunidad'),
            'HoraInicio'        => $HoraIni,
            'HoraFin'           => $HoraFin,
            'Estatus'           => $this->post('Estatus'),
            'TipoProceso'       => $this->post('IdTipoProceso')

        ]);

        $v->rule('required', [
            'Vendedor',
            'Cliente',
            'Etapa',
            'Servicio',
            'Actividad',
            'Monto',
            'MontoPropuesta',
            'Oportunidad',
            'HoraInicio',
            'HoraFin',
            'Estatus',
            'TipoProceso'

        ])->message('El campo {field} es requerido.');

        $v->rule('lengthMin', 'HoraInicio', 6)->message('El campo {field} debe contaner hora y minuto.');
        $v->rule('lengthMin', 'HoraFin', 6)->message('El campo {field} debe contaner hora y minuto.');

        $v->rule('numeric', [
            'Vendedor',
            'Cliente',
            'Etapa',
            'Monto',
            'Servicio',
            'MontoPropuesta',
            'Oportunidad',
            'TipoProceso'

        ])->message('El campo {field} debe ser numerico.');


        if ($v->validate()) {
            $IdSucursal =  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this->post('IdSeguimientoCliente');

            $RutaPrincipal = "assets/files/comprobantecrm/" . $IdSucursal . '/';
            if (!is_dir($RutaPrincipal)) {
                mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
            }

            $route = $RutaPrincipal;
            $files = $this->uploadfile->savefile($route, 'File', $this->post('NombreFoto'), '*', UploadFile::SINGLE);


            $Mseguimientocliente = new Mseguimientocliente();
            $Mseguimientocliente->IdSeguimientoCliente  = $Id;
            $Mseguimientocliente->IdSucursal            = $IdSucursal;
            $Mseguimientocliente->IdTrabajador          = $this->post('IdTrabajador');
            $Mseguimientocliente->IdClienteSucursal     = $this->post('IdClienteSucursal');

            //antiguamente llamado proceso (es etapa)
            $Mseguimientocliente->IdProceso             = $this->post('IdProceso');
            $Mseguimientocliente->Actividad             = $this->post('Actividad');
            $Mseguimientocliente->FechaReg              = date('Y-m-d');
            $Mseguimientocliente->Fecha                 = date('Y-m-d', strtotime($this->post('Fecha')));; //date('Y-m-d');
            $Mseguimientocliente->HoraDuracion          = "";
            $Mseguimientocliente->Comentarios           = $this->post('Comentarios');
            $Mseguimientocliente->Archivo               = $files;
            $Mseguimientocliente->IdConfigS             = $this->post('IdConfigS');
            $Mseguimientocliente->Anio                  = date('Y');
            $Mseguimientocliente->IdOportunidad         = $this->post('IdOportunidad');
            $Mseguimientocliente->HoraInicio            = $HoraIni;
            $Mseguimientocliente->HoraFin               = $HoraFin;
            $Mseguimientocliente->Estatus               = $this->post('Estatus');
            $Mseguimientocliente->IdTipoProceso         = $this->post('IdTipoProceso');
            $Mseguimientocliente->FechaMod              = date('Y-m-d H:i:s');

            if ($this->post('Estatus') == 'Abierta' || $this->post('Estatus') == 'Cerrada') {
                $Mseguimientocliente->MontoP = 0;
            } else {
                $Mseguimientocliente->MontoP = $this->post('MontoP');
            }

            if ($this->post('MontoPropuesta') > 0) {
                $Mseguimientocliente->MontoPropuesta = $this->post('MontoPropuesta');
            } else {
                $Mseguimientocliente->MontoPropuesta = 0;
            }
            
            if ($Mseguimientocliente->IdConfigS <= 0) {
                return $this->set_response([
                    'status' => false,
                    'Id'   => $Id,
                    'message' => 'Error al agregar a la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

            $Mcrmtipoproceso = new Mcrmtipoproceso();
            $Mcrmtipoproceso->IdTipoProceso = $this->post('IdTipoProceso');
            $Mcrmtipoproceso->IdConfigS = $this->post('IdConfigS');

            $Moportunidades = new Moportunidades();
            $Moportunidades->IdOportunidad = $this->post('IdOportunidad');
            $Moportunidades->Estado = $this->post('Estatus');
            $Moportunidades->FechaMod = date('Y-m-d H:i:s');

            if ($Mseguimientocliente->IdSeguimientoCliente == 0) {

                $Id =  $Mseguimientocliente->agregar();

                if ($Id > 0) {

                    $Mseguimientocliente->IdSeguimientoCliente = $Id;
                    $response =  $Mseguimientocliente->get_recovery();

                    if ($Mseguimientocliente->IdConfigS > 0) {
                        $Mcrmtipoproceso->UpdateIdConfigS();
                    }

                    $Moportunidades->UpdateEstado();

                    $data['seguimiento'] = $response['data'];
                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'Id'   => $Id,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    return $this->set_response([
                        'status' => false,
                        'Id'   => $Id,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                if ($Mseguimientocliente->update()) {
                    $response =  $Mseguimientocliente->get_recovery();
                    $data['oportunidad'] = $response['data'];

                    $Moportunidades->UpdateEstado();

                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'Id'   => $Id,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller::HTTP_ACCEPTED);
                } else {

                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    //***Lista de pipedrive */
    public function Listpipedrive_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $IdTipoProceso = $this->get('IdTipoProceso');
        if ($this->get('IdTipoProceso') == '') {
            $IdTipoProceso = '-1';
        }

        $oMcrmproceso = new Mcrmproceso();
        $oMcrmproceso->IdTipoProceso = $IdTipoProceso;
        $oMcrmproceso->IdSucursal = $IdSucursal;
        $procesos = $oMcrmproceso->get_list();

        $contador = 0;
        foreach ($procesos as $element) {
            $Mseguimientocliente = new Mseguimientocliente();
            $Mseguimientocliente->IdSucursal = $IdSucursal;
            $Mseguimientocliente->IdTrabajador = $this->get('IdTrabajador');
            $Mseguimientocliente->IdTipoProceso = $IdTipoProceso;
            $Mseguimientocliente->IdProceso = $element->IdCrmProceso;
            $Mseguimientocliente->Anio = $this->get('Anio');
            $Mseguimientocliente->IdOportunidad = $this->get('IdOportunidad');
            $rows =  $Mseguimientocliente->get_listpipedrive();
            $procesos[$contador]->historial = $rows;
            $procesos[$contador]->Total = count($rows);
            $contador++;
        }

        // Paginación
        return $this->set_response([
            'status' => true,
            'seguimiento' =>  $procesos,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function SucursalesPipe_get(){
         // Valid Token
         if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $seguimiento = new Mseguimientocliente();
        $seguimiento->IdEmpresa = $IdEmpresa;

        $data['Sucursales'] = $seguimiento->get_Sucursales();
        
       
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);

    }
}