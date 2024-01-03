<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccostovehope extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
       
    
        $this->load->model('finanzas/Mcostovehope');
        $this->load->model('finanzas/Mplancostovehiculosope');
        $this->load->library('FinanzasActualizacion');
        
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get() {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
   
        $v = new Valitron\Validator([ 'Anio' => $this -> get('Anio') ]);
        $v -> rule('required', [ 'Anio' ]) -> message('El campo {field} es requerido.');
    
        if ($v -> validate()) {

            $oMcostovehope = new Mcostovehope();
            $oMcostovehope->Anio = $this->get('Anio');
            $oMcostovehope->IdSucursal = $IdSucursal;
            // Paginaci�n
            $rows =  $oMcostovehope->get_list();
            
            if (count($rows)==0) {//si es igual a 0 se inserta para que busque
            
            $oFinanzasActualizacion= new FinanzasActualizacion();
            $rows=  $oFinanzasActualizacion->InsertValues(4,$this->get('Anio'),$IdSucursal,$IdEmpresa,"");
            }
        
            $data['lista'] = $rows;

            $oMcostovehopeconfig = new Mplancostovehiculosope();
            $oMcostovehopeconfig->Anio=$this -> get('Anio');
            $oMcostovehopeconfig->IdSucursal=$IdSucursal;
            $status= $oMcostovehopeconfig->get_costovehiculosope();
            
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'data02' => $status['data'],
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
            
        }
        else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'data'=>'error',
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }

    public  function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $v = new Valitron\Validator([ 'Anio' => $this -> post('Anio') ]);
        $v -> rule('required', [ 'Anio' ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {
        
            $Detalle = $this -> post('Detalle');
            
            foreach ($Detalle as $element) {
                $AnioAnterior = $element['AnioAnterior'];
                $UnoT = $element['PrimerT'];
                $DosT = $element['SegundoT'];
                $TresT = $element['TercerT'];
                $CuatroT = $element['CuartoT'];
                
                if ($AnioAnterior=='')
                {
                    $AnioAnterior=0;  
                }
                
                    if ($UnoT=='')
                {
                    $UnoT=0;  
                }
                    if ($DosT=='')
                {
                    $DosT=0;  
                }
                if ($TresT=='')
                {
                    $TresT=0;  
                }
                if ($CuatroT=='')
                {
                    $CuatroT=0;  
                }
                
                $oMcostovehope= new Mcostovehope();
                $oMcostovehope->IdCostoVehOpe   = $element['IdCostoVehOpe'];
                $oMcostovehope->Descripcion     =$element['Descripcion'];
                $oMcostovehope->NumeroCuenta    = $element['NumeroCuenta'];
                $oMcostovehope->AnioAnterior    = $AnioAnterior;
                $oMcostovehope->PrimerT         = $UnoT;
                $oMcostovehope->SegundoT        = $DosT;
                $oMcostovehope->TercerT         = $TresT;
                $oMcostovehope->CuartoT         = $CuatroT;
                $oMcostovehope->update();
            }

            $oMplancostovehiculosope = new Mplancostovehiculosope();
            $oMplancostovehiculosope->Anio=$this -> post('Anio');
            $oMplancostovehiculosope->IdSucursal=$IdSucursal;
            $status= $oMplancostovehiculosope->get_costovehiculosope();
            
            $oMplancostovehiculosope = new Mplancostovehiculosope();
            $oMplancostovehiculosope->Anio=$this -> post('Anio');
            $oMplancostovehiculosope->IdSucursal=$IdSucursal;
            
            $Objeto=$this -> post('ObjCVO');

            //si no inserta el default que es 0
            if ($Objeto['NumVehiculos']!='') {
                $oMplancostovehiculosope->NumVehiculos=$Objeto['NumVehiculos'];
            }
            if ($Objeto['kmproductivo']!='') {
                $oMplancostovehiculosope->kmproductivo=$Objeto['kmproductivo'];
            }
            if ($Objeto['TotalAnual']!='') {
                $oMplancostovehiculosope->TotalAnual=$Objeto['TotalAnual'];
            }
            if($Objeto['TotalFinal'] !='') {
                $oMplancostovehiculosope->TotalFinal=$Objeto['TotalFinal'];
            }
            if ($Objeto['TotalCorregido']!='') {
                $oMplancostovehiculosope->TotalCorregido=$Objeto['TotalCorregido'];
            }
    
            if ($status['status']) {
                $oMplancostovehiculosope->updateOvc();
            } else {
                $oMplancostovehiculosope->insertOvc();
            }  
            
            return $this -> set_response([
                'status' => true,
                'data' =>'Insertado',
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller:: HTTP_CREATED);
        }
        else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'data'=>'error',
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}