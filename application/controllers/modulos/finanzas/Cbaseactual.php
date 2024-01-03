<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cbaseactual extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mconfigservicio');
        $this->load->model('Mtiposervicio');
        $this->load->model('finanzas/Mconfigporcensubtipo');
        $this->load->model('finanzas/Mserviciosvalores');
        
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


        $oMconfigservicio = new Mconfigservicio();
        $oMconfigservicio->Nombre = $this->get('Nombre');
        $oMconfigservicio->RegEstatus = $this->get('RegEstatus');
        $oMconfigservicio->Facturable = $this->get('Facturable');
        $rows =  $oMconfigservicio->get_list();

        // Paginacion
        $Entrada='';
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMconfigservicio->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMconfigservicio->Tamano = $pager->PageSize;
        $oMconfigservicio->Offset = $pager->Offset;

        $data['lista'] = $oMconfigservicio->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }


    public function getone_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $Anio=$this->get('Anio');
        
        $oMtiposervicio = new Mtiposervicio();
        $oMtiposervicio->IdConfigS = $this->get('IdConfigS');
        $oMtiposervicio->RegEstatus = $this->get('RegEstatus');
        $oMtiposervicio->IdSucursal=$IdSucursal;
        // Paginaci�n
        $row =  $oMtiposervicio->get_list();
        
        $count=0;
        foreach ($row as $element)
        {
            $oMserviciosvalores=  new Mserviciosvalores();
            $oMserviciosvalores->IdTipoSer=$element->IdTipoSer;
            $oMserviciosvalores->Anio=$Anio;
            $data = $oMserviciosvalores->get_configprcensubtipo();
            
            $row[$count]->data=$data['data'];
            $count ++;     
        }

        $ListaAnios= listaAnios();
        return $this->set_response([
            'status' => true,
            'data' => $row,
            'ListaAnios' => $ListaAnios,
            'AnioActual' => date('Y'),
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    

    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Detalle = $this -> post('Detalle');
        $Anio = $this -> post('Anio');
        $concatenado='';
        foreach ($Detalle as $element)
        {
            $oMserviciosvalores=  new Mserviciosvalores();
            $oMserviciosvalores->IdTipoSer=$element['IdTipoSer'];
            $oMserviciosvalores->Anio=$Anio;
            $data= $oMserviciosvalores->get_configprcensubtipo();
            $baseActual= $element['data']['BaseActual'];
            
            if ($baseActual=='')
            {
                $baseActual=0;
            }
            $oMserviciosvalores=  new Mserviciosvalores();
            $oMserviciosvalores->IdTipoSer=$element['IdTipoSer'];
            $oMserviciosvalores->Anio=$Anio; 
            $oMserviciosvalores->IdConfigS=$element['IdConfigS'];
            $oMserviciosvalores->BaseActual=$baseActual;
            $oMserviciosvalores->IdSucursal=$IdSucursal;
            
            if ($data['status'])
            {
                $oMserviciosvalores->update(); 
            }
            else
            {
                $oMserviciosvalores->insert(); 
            }
        }
                   
        return $this -> set_response([
            'status' => true,
            'data' =>$concatenado,
            'message' => 'Se ha agregado correctamente.',
        ], REST_Controller:: HTTP_CREATED);
    }
}