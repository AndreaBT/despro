<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class CestadosFinancieros extends REST_Controller
{
    public $RutaPdf;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('finanzas/Mconceptooperacion');
        $this->load->model('finanzas/Mporcentajeoperacion');
        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('Mconfigservicio');
        $this->load->model('Mtiposervicio');
        $this->load->library('UploadFile');
        $this->load->library('EstadoFinanciero');
         
         setTimeZone($this->verification,$this->input);
    }
    

    public function getData_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $IdConfigS =$this->get('IdConfigS');
        $IdSubtipoServ =$this->get('IdTipoServ');
        $Anio =$this->get('Anio');
        $Mes=$this->get('Mes');
        $IdCliente=$this->get('IdCliente');
        $IdClienteS=$this->get('IdClienteS');
        $IdContrato=$this->get('IdContrato');
        $Type=$this->get('Tipo');

        if (empty($Type))
        {
             $Type=1;  
        }
        
        if($Mes < 10)
        {
            $Mes = '0'+$Mes;
        }
        
        $oEstadoFinanciero= new EstadoFinanciero();
        $row= $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ,$Mes,$IdCliente,$IdClienteS,$IdContrato,$Type,$IdEmpresa);
      
        $data['detalle'] =$row['resultados'];
   
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public function getDataTodos_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $Anio =$this->get('Anio');
        $Mes=$this->get('Mes');
        $IdCliente=$this->get('IdCliente');
        $IdClienteS=$this->get('IdClienteS');
        $IdContrato=$this->get('IdContrato');
        $Type=$this->get('Tipo');

        if (empty($Type))
        {
            $Type=1;  
        }
        
        $oEstadoFinanciero= new EstadoFinanciero();
        $row= $oEstadoFinanciero->GetEstadoFinancieroTodos($IdSucursal,$Anio,$Mes,$IdCliente,$IdClienteS,$IdContrato,$Type,$IdEmpresa);
      
        $data['detalle'] =$row['resultados'];
   
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public   function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdConfigS' => $this -> post('IdConfigS'),
            'IdTipoServ' => $this -> post('IdTipoServ'),
            'Anio' => $this -> post('Anio'),
            'Mes' => $this -> post('Mes'),
            'IdCliente' => $this -> post('IdCliente'),
            'IdClienteS' => $this -> post('IdClienteS')

        ]);

        $v -> rule('required', [
            'IdConfigS',
            'IdTipoServ',
            'Anio',
            'Mes',
            'IdCliente',
            'IdClienteS'
        ]) -> message('El campo {field} es requerido.');
    

        if ($v -> validate()) {
        
            $IdConfigS = $this -> post('IdConfigS');
            $IdTipoServ = $this -> post('IdTipoServ');
            $Anio = $this -> post('Anio');
            $Mes = $this -> post('Mes');

            if($Mes < 10)
            {
                $Mes = '0'.$Mes;
            }
            
            $IdCliente = $this -> post('IdCliente');
            $IdClienteS = $this -> post('IdClienteS');
            $Facturacion = $this -> post('Facturacion');
            $IdContrato = $this -> post('IdContrato');

            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
            $oMestadofinanciero= new Mestadofinanciero();
            $oMestadofinanciero->IdSucursal=$IdSucursal;
            $oMestadofinanciero->IdConfigS=$IdConfigS;
            $oMestadofinanciero->IdTipoServ=$IdTipoServ;
            $oMestadofinanciero->Anio=$Anio;
            $oMestadofinanciero->Mes=$Mes;
            $oMestadofinanciero->IdCliente=$IdCliente;
            $oMestadofinanciero->IdClienteS=$IdClienteS;
            $oMestadofinanciero->IdContrato=$IdContrato;
            $dataexist= $oMestadofinanciero->get_recovery();
            
            $oMestadofinanciero= new Mestadofinanciero();
            $oMestadofinanciero->IdSucursal=$IdSucursal;
            $oMestadofinanciero->IdConfigS=$IdConfigS;
            $oMestadofinanciero->IdTipoServ=$IdTipoServ;
            $oMestadofinanciero->Anio=$Anio;
            $oMestadofinanciero->Mes=$Mes;
            $oMestadofinanciero->IdCliente=$IdCliente;
            $oMestadofinanciero->IdContrato=$IdContrato;
            $oMestadofinanciero->IdClienteS=$IdClienteS;

            if ($Facturacion=="")
            {
                $Facturacion =0;
            }
            $oMestadofinanciero->Facturacion=$Facturacion;
            if ($dataexist['status'])
            {
                $oMestadofinanciero->update();
            }
            else
            {
                $oMestadofinanciero->insert(); 
            }
        
            
            return $this -> set_response([
                'status' => true,
                'data' => 'Guardado con exito',
                'message' => 'Se ha agregado correctamente.',
                'estatus2' => $dataexist['status'],
            ], REST_Controller:: HTTP_CREATED);
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}