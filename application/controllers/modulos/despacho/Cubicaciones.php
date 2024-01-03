<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cubicaciones extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mubicaciont');
        $this->load->model('Mubicacionv');
        $this->load->model('Mservicio');
        $this->load->model('crm/Mseguimientocliente');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll2_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMubicaciont = new Mubicaciont();
        $oMubicaciont->IdSucursal = $IdSucursal;
        // Paginaci�n
        $rows =  $oMubicaciont->get_list();
        $ubicaciones = array();

        foreach($rows as $element)
        {
            $z = [
            'Tipo' => 1,
            'lat' => $element->lat,
            'lng' => $element->lng,
            'Nombre' => $element->Nombre,
            'Cliente' => $element->Cliente,
            'Folio' => $element->Folio,
            'Concepto' => $element->Concepto,
            'Fecha_I' => $element->Fecha_I,
            'Fecha_F' => $element->Fecha_F,
            'HoraInicio' => $element->HoraInicio,
            'HoraFin' => $element->HoraFin,
            'Estatus' => $element->Estatus,
            'Foto2' => $element->Foto2,
            ];
            
            array_push($ubicaciones, $z);
        }

        $oMserv = new Mservicio();
        $oMserv->IdSucursal = $IdSucursal;
        $oMserv->Fecha_I=date('Y-m-d');
        $oMserv->Fecha_F=date('Y-m-d');
        $rowser =  $oMserv->get_list();

        foreach($rowser as $serv1)
        {
            $zzw = [
                'Tipo' => 2,
                'lat' => $serv1->Latitud,
                'lng' => $serv1->Longitud,
                'Nombre' => $serv1->Cliente,
                'Cliente' => $serv1->NomCli,
                'Folio' => $serv1->Folio,
                'Concepto' => $serv1->Observaciones,
                'Fecha_I' => $serv1->Fecha_I,
                'Fecha_F' => $serv1->Fecha_F,
                'HoraInicio' => '',
                'HoraFin' => $serv1->Fecha_I,
                'Estatus' => 'Disponible',
                'Foto2' => $serv1->IdIconoEmp,
            ];
                
            array_push($ubicaciones, $zzw);
        }
        

        $data['ubicaciones'] = $ubicaciones;
        $data['servicios'] = $rowser;
        $data['ruta'] = base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMubicaciont = new Mubicaciont();
        $oMubicaciont->IdSucursal = $IdSucursal;
        // Paginaci�n
        $rows =  $oMubicaciont->get_list();

        $data['ubicaciones'] = $rows;
        $data['ruta'] = base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findAllVendedor_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $Mubicacionv = new Mubicacionv();
        $Mubicacionv->IdSucursal = $IdSucursal;
        //$Mubicacionv->HoraInicio=date('H:i');
        $Mubicacionv->Fecha=date('Y-m-d');
        // Paginaci�n
        $rows =  $Mubicacionv->get_list();

        $oMubiacionescliente = new Mseguimientocliente();
        $oMubiacionescliente->IdSucursal=$IdSucursal;
        //$oMubiacionescliente->HoraInicio=date('H:i');
        $oMubiacionescliente->Fecha=date('Y-m-d');
        $rowclientes = $oMubiacionescliente->get_list_ubiaciones();

        $data['ubicaciones'] = $rows;
        $data['clientes'] = $rowclientes;
        $data['ruta'] = base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}