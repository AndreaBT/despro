<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccostodeptooper extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
       
    
        $this->load->model('finanzas/Mcostodeptoventa');
        $this->load->model('finanzas/Mplancostodeptope');
        $this->load->library('FinanzasActualizacion');
        
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
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
   
        $v = new Valitron\Validator([
            'Anio' => $this -> get('Anio')
        ]);

        $v -> rule('required', [
            'Anio'
    
        ]) -> message('El campo {field} es requerido.');
    

        if ($v -> validate()) {

            $Anio=$this->get('Anio');
            if ($Anio=="")
            {
                $Anio=date("Y");
            }
    
            $oMcostodeptoventa = new Mcostodeptoventa();
            $oMcostodeptoventa->Anio = $Anio;
            $oMcostodeptoventa->IdSucursal = $IdSucursal;
            // Paginacion
            $rows =  $oMcostodeptoventa->get_list();
            
            if (count($rows)==0)
            {
                //si es igual a 0 se inserta para que busque
                $oFinanzasActualizacion= new FinanzasActualizacion();
                $rows=  $oFinanzasActualizacion->InsertValues(3,$this->get('Anio'),$IdSucursal,$IdEmpresa,"");
            }

            $data['lista'] = $rows;
                
            $oMplancostodeptope = new Mplancostodeptope();
            $oMplancostodeptope->Anio=$this -> get('Anio');
            $oMplancostodeptope->IdSucursal=$IdSucursal;
            $status= $oMplancostodeptope->get_plancostodepto();

            return $this->set_response([
                'status' => true,
                'data' => $data,
                'data2' => $status['data'],
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
        
        $v = new Valitron\Validator([
            'Anio' => $this -> post('Anio')
        ]);

        $v -> rule('required', [
            'Anio'
    
        ]) -> message('El campo {field} es requerido.');
    

        if ($v -> validate()) {
    
            $Detalle = $this -> post('Detalle');
        
            foreach ($Detalle as $element)
            {
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
                    
                $oMcostodeptoventa = new Mcostodeptoventa();
                $oMcostodeptoventa->IdCostoDeptoVenta = $element['IdCostoDeptoVenta'];
                $oMcostodeptoventa->Descripcion =$element['Descripcion'];
                $oMcostodeptoventa->NumeroCuenta = $element['NumeroCuenta'];
                $oMcostodeptoventa->AnioAnterior = $AnioAnterior;
                $oMcostodeptoventa->PrimerT = $UnoT;
                $oMcostodeptoventa->SegundoT = $DosT;
                $oMcostodeptoventa->TercerT = $TresT;
                $oMcostodeptoventa->CuartoT = $CuatroT;
            
                $oMcostodeptoventa->update();
            }  
           
            $oMplancostodeptope = new Mplancostodeptope();
            $oMplancostodeptope->Anio=$this -> post('Anio');
            $oMplancostodeptope->IdSucursal=$IdSucursal;
            $status= $oMplancostodeptope->get_plancostodepto();
            
            $oMplancostodeptope = new Mplancostodeptope();
            $oMplancostodeptope->Anio=$this -> post('Anio');
            $oMplancostodeptope->IdSucursal=$IdSucursal;
            
            $Objeto=$this -> post('ObjBurden');
            //si no inserta el default que es 0
            if ($Objeto['NumTrab']!=''){
                $oMplancostodeptope->NumTrabajadores=$Objeto['NumTrab'];
            }
            if ($Objeto['SemProd']!=''){
                $oMplancostodeptope->SemanasPro=$Objeto['SemProd'];
            }
            if ($Objeto['HorasSemProd']!=''){
                $oMplancostodeptope->HorasSemanalesPro=$Objeto['HorasSemProd'];
            }
            if ($Objeto['BurdenRate'] !=''){
                $oMplancostodeptope->BurdenRate=$Objeto['BurdenRate'];
            }
            if ($Objeto['BurdenRateC']!=''){
                $oMplancostodeptope->BurdenRateCorregido=$Objeto['BurdenRateC'];
            }

            if ($status['status'])
            {
                $oMplancostodeptope->update();
            }
            else
            {
                $oMplancostodeptope->insert();
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