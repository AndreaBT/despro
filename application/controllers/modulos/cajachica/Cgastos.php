<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cgastos extends REST_Controller
{
    public $ruta='assets/files/comprobantegastos';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('caja/Mgastostrabajador');
        $this->load->model('caja/Mimagencajachica');
        
        setTimeZone($this->verification,$this->input);
    }
    
    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $oMgastostrabajador = new Mgastostrabajador();
        $oMgastostrabajador->IdCajaC= $this->get('IdCajaC');
        $oMgastostrabajador->IdTrabajador = $this->get('IdTrabajador');

        // Paginación
        $rows =  $oMgastostrabajador->get_list();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }
        $oMgastostrabajador->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $oMgastostrabajador->Tamano = $pager->PageSize;
        $oMgastostrabajador->Offset = $pager->Offset;
        
        
        //****Total Gasto por empleado o general**********
        $oMgastostrabajador = new Mgastostrabajador();
        $oMgastostrabajador->IdCajaC= $this->get('IdCajaC');
        $oMgastostrabajador->IdTrabajador = $this->get('IdTrabajador');
        $Resp= $oMgastostrabajador->get_TotalGasto();
        $TotalGastado=0;

        if ($Resp['status'])
        {
            if (!empty($Resp['data']->Total))
            {
                $TotalGastado =$Resp['data']->Total; 
            }
        }
        
        $data['pagination']= $pager;
        $data['TotalGastado']= $TotalGastado;
        $data['gastos'] = $oMgastostrabajador->get_list();

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public function findAllEvidencia_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMimagencajachica = new Mimagencajachica();
        $oMimagencajachica->IdConcepto= $this->get('IdConcepto');

        // Paginaci�n
         /* 
        $oMimagencajachica->Actualizado='s';
        $rowscom =  $oMimagencajachica->get_list(1);
        $contador=1;
     
        foreach ($rowscom as $element)
        {
            $nombre='Imagen'.$contador;
          $rutafinal = CrearRutaEmpSuc($this->ruta,$IdEmpresa,$IdSucursal,$element->IdConcepto);
          
          Base64ToPathFix($element->Imagen,$nombre,$rutafinal);//convierte el base 64 en archivo imagen
          $contador ++;
        }*/
            
         $oMimagencajachica->Actualizado='';
        $rows =  $oMimagencajachica->get_list(2);

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $oMimagencajachica->Tamano = $pager->PageSize;
        $oMimagencajachica->Offset = $pager->Offset;

        $data['gastos'] = $oMimagencajachica->get_list(2);
        $data['pagination']= $pager;
        $data['ruta']=base_url().$this->ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/';
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
                    'message' => 'Error al eliminar la informaci�n.',
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
                'message' => 'Par�metros no recibidos.',
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

    public function Add_post()
    {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
    
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Lista =$this->post('Lista');

        $Id = '';

        foreach ($Lista as $element)
        {
            $Mgastostrabajador = new Mgastostrabajador();
            $Mgastostrabajador ->IdGasto = $Id;
            $Mgastostrabajador->Fecha = date('Y-m-d');
            $Mgastostrabajador ->Concepto = $this->post('Concepto');
            $Mgastostrabajador ->Total = $this->post('Total');
            $Mgastostrabajador ->IdTrabajador = $this->post('IdTrabajador');
            $Mgastostrabajador ->IdCajaC = $this->post('IdCajaC');
            $Mgastostrabajador->insert();
        }
    }
}