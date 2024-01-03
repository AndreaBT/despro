<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Ccotizacion_servicio extends REST_Controller
{
    public $RutaQr;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mcotizacion_servicio');
        $this->load->model('Mservicio_manode_obra');
        $this->load->model('Mservicio_material');
        $this->load->model('Mservicio_miscelaneos');
        $this->load->model('Mcostosta');
        $this->load->model('Mmaterial');
        $this->load->model('Mclientesucursal');
        
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
       $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objeto = new Mcotizacion_servicio();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->Folio =$this->get('Nombre');
 
        $objeto->RegEstatus='A';
    
        // Paginación
        $rows =  $objeto->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $objeto->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        
    
        $data['row'] =$rows;
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
        $obj = new Mcotizacion_servicio();
        $obj->RegEstatus='B';
        
        $obj->IdCotizacionServicio = $Id;
  
        $response =   $obj->get_recovery();
     
        
        if ($response['status']) {
             $obj->FechaMod=date('Y-m-d H:i:s');
            if ($obj->delete()) {

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


    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj= new Mcotizacion_servicio();

        $Id = (int) $this->get('IdCotizacionServicio');
     
        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdCotizacionServicio = $Id;
        }
        $response =   $obj->get_recovery();
        
        $oMservicio_material=new Mservicio_material();
        $oMservicio_material->IdCotizacionServicio=$Id;
        $oMservicio_material->RegEstatus='A';
        $materiales=$oMservicio_material->get_list();
        
        $response['data']->ListaManoObra=array();
        $response['data']->ListaMaterialCot=$materiales;
        $response['data']->ListaMiscelaneCot=array();
        
           
        $oMclientesucursal=new Mclientesucursal();
        $oMclientesucursal->IdClienteS=$response['data']->IdCliente;
        $responsecli=$oMclientesucursal->get_cliente();
        
        if ($response['status']) {
            
            $data['cotizacion_servicio'] = $response['data'];
            $data['Ocliente']=$responsecli['data'];
            
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
        
        $IdClient=$this -> post('IdCliente');
        if(empty($IdClient)){
            $IdClient='';
        }
            
        $v = new Valitron\Validator([
            'Cliente' =>$IdClient,
            'GrossProfit' => $this -> post('GrossProfit'),
            'utilidad' => $this->post('utilidad'),
            'gastoOperaciones' => $this->post('gastoOperaciones'),
            'factorHoraExtra' => $this->post('factorHoraExtra'),
            'totalMateriales' => $this -> post('totalMateriales'),
            'totalManoDeObra' => $this -> post('totalManoDeObra'),
            'totalMiscelaneos' => $this -> post('totalMiscelaneos'),
            'costoKm' => $this -> post('costoKm'),
            'totalGlobal' => $this -> post('totalGlobal'),
            'distancia' => $this -> post('distancia'),
            'velocidad' => $this -> post('velocidad'),
            'TotalCostoKm' => $this -> post('TotalCostoKm'),
            'Garantia' => $this -> post('Garantia'),
            'CostoBurden' => $this -> post('CostoBurden'),
            'CostoManoObraD' => $this -> post('CostoManoObraD'),
            'ValorVenta' => $this -> post('ValorVenta')
            
        ]);
    
        $v -> rule('required', [
            'Cliente',
            'GrossProfit',
            'utilidad',
            'gastoOperaciones',
            'factorHoraExtra',
            'totalMateriales',
            'totalManoDeObra',
            'totalMiscelaneos',
            'costoKm',
            'totalGlobal',
            'distancia',
            'velocidad',
            'TotalCostoKm',
            'Garantia',
            'CostoBurden',
            'CostoManoObraD',
            'ValorVenta',
        ]) -> message('El campo {field} es requerido.');
        
        /*
        $v -> rule('integer', [
            'Utilidad','Gastos operacionales','totalMateriales','totalManoDeObra','totalMiscelaneos','costoKm','totalGlobal','distancia','velocidad','TotalCostoKm','Garantia','CostoBurden','CostoDesplazamiento','CostoManoObraD','ValorVenta'
        ]) -> message('El campo {field} solo acepta numeros enteros.');*/
        
        if ($v -> validate()) {
          $IdSucursal=  $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
    
            $Id = (int)$this -> post('IdCotizacionServicio');
            
    
            $obj = new Mcotizacion_servicio();
            $obj->IdCotizacionServicio=$Id;
            $obj->IdServicio = $this->post('IdServicio');
            $obj->IdCliente = $this->post('IdCliente');
            $obj->IdUsuario = $this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
            $obj->IdSucursal = $IdSucursal;
            $obj->GrossProfit = $this->post('GrossProfit');
            $obj->utilidad = $this->post('utilidad');
            $obj->gastoOperaciones = $this->post('gastoOperaciones');
            $obj->factorHoraExtra = $this->post('factorHoraExtra');
            $obj->totalMateriales = $this->post('totalMateriales');
            $obj->totalManoDeObra = $this->post('totalManoDeObra');
            $obj->totalMiscelaneos = $this->post('totalMiscelaneos');
            $obj->costoKm = $this->post('costoKm');
            $obj->fechaCotiServicio =date('Y-m-d H:i:s');
            $obj->totalGlobal = $this->post('totalGlobal');
            $obj->distancia = $this->post('distancia');
            $obj->velocidad = $this->post('velocidad');
            $obj->RegEstatus = 'A';
        
            $obj->TotalCostoKm = $this->post('TotalCostoKm');
            $obj->Garantia = $this->post('Garantia');
            //$obj->Comentario = $this->post('Comentario');
            $obj->CostoBurden = $this->post('CostoBurden');
            $obj->CostoManoObraD = $this->post('CostoManoObraD');
            $obj->ValorVenta = $this->post('ValorVenta');
            $obj->FechaMod = date('Y-m-d H:i:s'); 
            $obj->Observaciones = $this->post('Observaciones'); 
             
            $Folio=  get_Folio($IdSucursal,'COTIZACION SERVICIO');
            $IdFolio= $Folio['IdFolio'];
            $Numero= $Folio['Numero'];
            $obj ->Folio= $Folio['Folio'];
            
            if ($Id==0) {
                $Id = $obj->insert();
                if ($Id > 0) {
                    get_update_folio($IdFolio,$Numero);
                    //INSERT DETALLE
                    $this->insert_manoobra($this->post('ListaManoObra'),$Id);
                    $this->insert_material($this->post('ListaMaterialCot'),$Id);
                    $this->insert_miscelaneo($this->post('ListaMiscelaneCot'),$Id);
            
                    $obj->IdCotizacionServicio = $Id;
                    $response = $obj-> get_recovery();
                    $data['cotizacion_servicio'] = $response['data'];
                    
                    
                    
                    return $this -> set_response([
                        'status' => true,
                        'type' => 1,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'type' => 2,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
             
                if ($obj-> update()) {
                    //INSERT DETALLE
                    $this->insert_manoobra($this->post('ListaManoObra'),$Id);
                    $this->insert_material($this->post('ListaMaterialCot'),$Id);
                    $this->insert_miscelaneo($this->post('ListaMiscelaneCot'),$Id);
                    
                    $response = $obj -> get_recovery();
                    $data['cotizacion_servicio'] = $response['data'];
    
                    return $this -> set_response([
                        'status' => true,
                        'type' => 1,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
    
                    return $this -> set_response([
                        'type' => 2,
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }else {
    
                $data['errores'] = $v->errors();
    
                return $this->set_response([
                    'type' => 1,
                    'status' => false,
                    'message' => $data,
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
    }
    
    public function insert_manoobra($datapost,$Id){
        //$json = str_replace(array('\t','\n','\"'), "",$datapost);
        //$data = json_decode($json); 
    
        $oMservicio_manode_obra=new Mservicio_manode_obra();
        $oMservicio_manode_obra->IdCotizacionServicio=$Id;
        $oMservicio_manode_obra->delete();
        
        foreach($datapost as $element){
            $oMservicio_manode_obra=new Mservicio_manode_obra();
            $oMservicio_manode_obra->IdCotizacionServicio=$Id;
            $oMservicio_manode_obra->categoria=$element['categoria'];//$element->categoria;
            $oMservicio_manode_obra->costoMO=$element['costoMO'];//$element->costoMO;
            $oMservicio_manode_obra->horaNormal=$element['horaNormal'];//$element->horaNormal;
            $oMservicio_manode_obra->horaExtra=$element['horaExtra'];
            $oMservicio_manode_obra->totalIndividual=$element['totalIndividual'];
            $oMservicio_manode_obra->RegEstatus='A';
            $oMservicio_manode_obra->Burden=$element['Burden'];
            $oMservicio_manode_obra->insert();
            
        }
    }
    
    public function insert_material($datapost,$Id){
        /*$json = str_replace(array('\t','\n','\"'), "",$datapost);
        $data = json_decode($json); */
        $oMservicio_material=new Mservicio_material();
        $oMservicio_material->IdCotizacionServicio=$Id;
        $oMservicio_material->delete();
        
        foreach($datapost as $element){
            $IdMaterial=$element['IdMaterial'];
            $precio=$element['costoUnitario'];
            if($precio==''){
               $precio=0; 
            }
            
            if($IdMaterial<=0){
                $oMmaterial=new Mmaterial();
                $oMmaterial->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
                $oMmaterial->FechaMod=date('Y-m-d H:i:s');
                $oMmaterial->NomMaterial=trim($element['NombreMat']);
                $oMmaterial->Precio=$precio;
                $IdMaterial=$oMmaterial->insert();
            }
            
            $oMservicio_material=new Mservicio_material();
            $oMservicio_material->IdCotizacionServicio=$Id;
            $oMservicio_material->IdMaterial=$IdMaterial;
            $oMservicio_material->cantidad=$element['cantidad'];
            $oMservicio_material->costoUnitario=$precio;
            $oMservicio_material->totalIndividual=$element['totalIndividual'];
            $oMservicio_material->NombreMat=trim($element['NombreMat']);
            $oMservicio_material->insert();
            
        }
    }
    
    public function insert_miscelaneo($datapost,$Id){
        /*$json = str_replace(array('\t','\n','\"'), "",$datapost);
        $data = json_decode($json); */
        
        $oMservicio_miscelaneos=new Mservicio_miscelaneos();
        $oMservicio_miscelaneos->IdCotizacionServicio=$Id;
        $oMservicio_miscelaneos->delete();
        
        foreach($datapost as $element){
            $oMservicio_miscelaneos=new Mservicio_miscelaneos();
            $oMservicio_miscelaneos->IdCotizacionServicio=$Id;
            $oMservicio_miscelaneos->concepto=$element['concepto'];
            $oMservicio_miscelaneos->cantidad=$element['cantidad'];
            $oMservicio_miscelaneos->valor=$element['valor'];
            $oMservicio_miscelaneos->insert();
        }
    }
    
    //COSTOS TA
    
    public function ListMO_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $Burden = 0;
        $General = 300;

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objeto = new Mcostosta();
        $objeto->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objeto->RegEstatus='A';
        // Paginación
        $rows =  $objeto->get_list();
        
        $objetoBurden = new Mcostosta();
        $objetoBurden->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objetoBurden->RegEstatus='A';
        $databurden=$objetoBurden->get_recovery_burden();

        //$this->response($databurden);
        $array=array();
        $Burden=$databurden['data']->Costo;
        //$General=$databurden['data']->Costo;

                
        
        $oMservicio_manode_obra=new Mservicio_manode_obra();
        $oMservicio_manode_obra->IdCotizacionServicio=$this->get('IdCotizacionServicio');
        $oMservicio_manode_obra->RegEstatus='A';
        $listamanoObra=$oMservicio_manode_obra->get_list();

        if(count($listamanoObra)>0){
            $Contador=0;
            foreach($listamanoObra as $element){

                $element->costoMO = (float)$element->costoMO;
                $element->horaNormal = (float)$element->horaNormal;
                $element->horaExtra = (float)$element->horaExtra;
                $element->totalIndividual = (float)$element->totalIndividual;
                
                $bnd=true;
                if($element->categoria=='Tecnico' || $element->categoria=='Tecnico + Ayu'){
                    $bnd=false;
                }
                $listamanoObra[$Contador]->Input=$bnd;
                
                $Contador ++;
            }

            $array=$listamanoObra;
        }else{
            foreach($rows as $element){
                $bnd=false;
                if($element->Concepto=='TECNICO'){
                    $Categoria='Tecnico';
                    $bnd=true;
                }else if($element->Concepto=='TECNICO + AYUDANTE'){
                    $Categoria='Tecnico + Ayu';
                    $bnd=true;
                }
                
                if($bnd){
                    $array[]=['categoria'=>$Categoria,'costoMO'=>$element->Costo,'horaNormal'=>0,'horaExtra'=>0,'totalIndividual'=>0,'Burden'=>$Burden,'Input'=>false];      
                }
            }
        
            $array[]=['categoria'=>'Técnico Especializados','costoMO'=>0,'horaNormal'=>0,'horaExtra'=>0,'totalIndividual'=>0,'Burden'=>$Burden,'Input'=>true];
            $array[]=['categoria'=>'Ingeniería','costoMO'=>0,'horaNormal'=>0,'horaExtra'=>0,'totalIndividual'=>0,'Burden'=>$Burden,'Input'=>true];
            $array[]=['categoria'=>'Supervisión','costoMO'=>0,'horaNormal'=>0,'horaExtra'=>0,'totalIndividual'=>0,'Burden'=>$Burden,'Input'=>true];    
        }

        $Contadorf=0;
        foreach($array as $elementf){

            //$array[$Contadorf]->Burden=$Burden;
			if($this->get('IdCotizacionServicio') > 0){
				$elementf->Burden = $databurden['data']->Costo;
                //$elementf->costoMO = intval($elementf->costoMO);
                //$elementf->costoMO = intval($elementf->costoMO);

			}else{
				$elementf['Burden'] = $databurden['data']->Costo;
			}
        }

        $data['row'] =$array;
        $data['Burden'] =$Burden;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
			'id'=>$this->get('IdCotizacionServicio')
        ], REST_Controller::HTTP_OK);
    }
    
    public function ListMiscelaneo_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $oMservicio_miscelaneos=new Mservicio_miscelaneos();
        $oMservicio_miscelaneos->IdCotizacionServicio=$this->get('IdCotizacionServicio');
        $oMservicio_miscelaneos->RegEstatus='A';
        $miselaneo=$oMservicio_miscelaneos->get_list();
        if(count($miselaneo)>0){
            $array=$miselaneo;
        }else{
            $array[]=['concepto'=>'Contratistas','cantidad'=>0,'valor'=>0];
            $array[]=['concepto'=>'Equipos','cantidad'=>0,'valor'=>0]; 
            $array[]=['concepto'=>'','cantidad'=>0,'valor'=>0];      
            $array[]=['concepto'=>'','cantidad'=>0,'valor'=>0];   
        }

        $data['row'] =$array;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}