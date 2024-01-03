<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cspend_proyecto extends REST_Controller
{
  
     public $ruta='assets/files/spend_profirma';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mspend_proyecto');
        $this->load->model('Mclientes');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mspend_proyectodet');
        $this->load->model('Mspend_ordenc');
        $this->load->model('Mspend_horas');
        $this->load->model('Mnumcontrato');
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
        
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $objeto = new Mspend_proyecto();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->Proyecto=$this->get('Nombre');
        $objeto->Estatus=$this->get('Estatus');
        
        $objeto->RegEstatus='A';
        $objeto->IdClienteS=$this->get('IdClienteS');
        $objeto->IdCliente=$this->get('IdCliente');
        if($this->get('FechaI')!=''){
            $objeto->FechaI=date("Y-m-d", strtotime($this->get('FechaI')));
            $objeto->FechaF=date("Y-m-d", strtotime($this->get('FechaF')));
        }

    
        // Paginación
        $rows =  $objeto->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
           $Entrada =$this->get('Entrada');
        }

        $objeto->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $objeto->Tamano = $pager->PageSize;
        $objeto->Offset = $pager->Offset;
        $rows=$objeto->get_list();
        

        return $this->set_response([
            'status' => true,
            'proyecto' => $rows,
            'UrlPdf'=>base_url().$this->ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
            'pagination' => $pager,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $obj = new Mspend_proyecto();
        $obj->RegEstatus='B';
        
        $obj->IdProyecto = $Id;
  
        $response =   $obj->get_recovery();

        if ($response['status']) {

             $obj->FechaMod=date('Y-m-d H:i:s');
            if ($obj->delete()) {
                #====================Eliminamos Contratos de spend plan ============================
                $oMnumcontrato=new Mnumcontrato();
                $oMnumcontrato->IdProyectoSpend=$Id;
                $oMnumcontrato->delete_spendplan();
                #====================FIN Eliminamos Contratos de spend plan ============================
            
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
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        
        $obj= new Mspend_proyecto();

        $Id = (int) $this->get('IdProyecto');
     

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $obj->IdProyecto = $Id;
        }
        $response =   $obj->get_recovery();
        
        $oMclientes=new Mclientes();
        $oMclientes->IdCliente=$response['data']->IdCliente;
        $rescliente=$oMclientes->get_clientes();
        if($rescliente['status']){
           $response['data']->Cliente=$rescliente['data']->Nombre;
        }
        
        $oMclientesucursal=new Mclientesucursal();
        $oMclientesucursal->IdClienteS=$response['data']->IdClienteS;
        $resclientes=$oMclientesucursal->get_cliente();
        if($resclientes['status']){
           $response['data']->Sucursal=$resclientes['data']->Nombre;
           $response['data']->Direccion=$resclientes['data']->Direccion;
           $response['data']->ContactoS=$resclientes['data']->ContactoS;
           $response['data']->Correo=$resclientes['data']->Correo;
           $response['data']->Telefono=$resclientes['data']->Telefono;
           $response['data']->Ciudad=$resclientes['data']->Ciudad;
        }
        
        
        if ($response['status']) {
            /*$oMspend_proyectodet=new Mspend_proyectodet();
            $oMspend_proyectodet->IdProyecto=$Id;
            $row=$oMspend_proyectodet->get_list();*/
            $response['data']->Detalle=array();
            $data['proyecto'] = $response['data'];
            
            //$data['detalle'] = $row;
            
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'Ruta' => base_url().$IdEmpresa.'/'.$IdSucursal,
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
        
        $IdCliente=$this -> post('IdCliente');
        $IdClienteS=$this -> post('IdClienteS');
        $FechaI=$this -> post('FechaI');
        if(!empty($FechaI)){
            $FechaI=date('Y-m-d',strtotime($FechaI));   
        }
        $FechaTermino=$this -> post('FechaTermino');
        $CantidadTermino=$this -> post('CantidadTermino');
        if($CantidadTermino<=0){
            $CantidadTermino='';
        }
        
        if($IdCliente<=0){$IdCliente='';}
        if($IdClienteS<=0){$IdClienteS='';}
        
        $Proyecto=trim($this -> post('Proyecto'));
        $ValorHora=$this -> post('ValorHora');
        $ValorBurden=$this -> post('ValorBurden');
        if($ValorHora==''){$ValorHora=0;}
        if($ValorBurden==''){$ValorBurden=0;}
        
        $Tc=0;
        $CostoOperacional=$this -> post('CostoOperacional');
        $GAVentas=$this -> post('GAVentas');
        $NetProfit=$this -> post('NetProfit');
        $CostoOpePorc=$this -> post('CostoOpePorc');
        $GAVentasPorc=$this -> post('GAVentasPorc');
        $NetProfitPorc=$this -> post('NetProfitPorc');
        $Observaciones=trim($this -> post('Observaciones'));
            
        $v = new Valitron\Validator([
            'IdCliente' => $IdCliente,
            'IdClienteS' => $IdClienteS,
            'FechaI' => $FechaI,
            'FechaTermino' => $FechaTermino,
            'CantidadTermino' => $CantidadTermino,
            'Proyecto' => $Proyecto,
            'CostoOperacional' => $CostoOperacional,
            'GAVentas' =>$GAVentas,
            'NetProfit' => $NetProfit,
            'CostoOpePorc' => $CostoOpePorc,
            'GAVentasPorc' =>$GAVentasPorc,
            'NetProfitPorc' => $NetProfitPorc,
        ]);

        $v -> rule('required', [
            'IdCliente','IdClienteS','FechaI','FechaTermino','Proyecto','CostoOperacional','GAVentas','NetProfit','CostoOpePorc','GAVentasPorc','NetProfitPorc','CantidadTermino'
        ]) -> message('El campo  es requerido.');
        
        $v -> rule('numeric', [
            'CostoOperacional','GAVentas','NetProfit','GAVentasPorc','NetProfitPorc','CostoOpePorc','CantidadTermino'
        ]) -> message('El campo  solo acepta numeros.');
        
            
        $v -> rule('date', [
            'FechaI'
        ]) -> message('El campo  no tiene el formato de fecha.');

        if ($v -> validate()) {

            $Id = (int)$this -> post('IdProyecto');
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
            $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
            
            $rutafinal = CrearRutaEmpSuc($this->ruta,$IdEmpresa,$IdSucursal,'');
            $files = $this->uploadfile->savefile($rutafinal.'/', 'File',$this->post('Archivo'), '*', UploadFile::SINGLE);
    
            $Folio=  get_Folio($IdSucursal,'SPEND PLAN PROYECTO');
            $IdFolio= $Folio['IdFolio'];
            $Numero= $Folio['Numero'];
            $NumeroFolio=$Folio['Folio'];

            $obj = new Mspend_proyecto();
            $obj->IdProyecto=$Id;
            $obj->IdSucursal =$IdSucursal;
            $obj->IdCliente = $IdCliente;
            $obj->IdClienteS = $IdClienteS;
            $obj->IdUsuario = $IdUsuario;
            $obj->FechaReg = date('Y-m-d H:i:s');
            $obj->Folio=$NumeroFolio;
            $obj->FechaI=$FechaI;
            $obj->FechaTermino=$FechaTermino;
            $obj->CantidadTermino=$CantidadTermino;
            $obj->Proyecto=$Proyecto;
            $obj->ValorHora=$ValorHora;
            $obj->ValorBurden=$ValorBurden;
            $obj->Tc=$Tc;
            $obj->CostoOperacional=$CostoOperacional;
            $obj->GAVentas=$GAVentas;
            $obj->NetProfit=$NetProfit;
            $obj->NetProfitPorc=$NetProfitPorc;
            $obj->GAVentasPorc=$GAVentasPorc;
            $obj->CostoOpePorc=$CostoOpePorc;
            $obj->Archivo=$files;
            $obj->Observaciones=$Observaciones;
            $obj->Estatus='Abierto';
            
            $obj->FechaMod = date('Y-m-d H:i:s');
            $obj->RegEstatus = 'A';
            
            #datos numero contrato
            $oMnumcontrato=new Mnumcontrato();
            $oMnumcontrato->IdClienteS=$IdClienteS;
            $oMnumcontrato->NumeroC=$NumeroFolio;
            $oMnumcontrato->Comentario=$NumeroFolio;
            $oMnumcontrato->RegEstatus='A';
            $oMnumcontrato->IdProyectoSpend=$Id;
        
            if ($Id==0) {
        
                $Id = $obj->insert();
                if ($Id > 0) {
                    $oMnumcontrato->IdProyectoSpend=$Id;
                    $oMnumcontrato->insert();
                    
                    
                    get_update_folio($IdFolio,$Numero);
                    $obj->IdProyecto = $Id;
                    $response = $obj-> get_recovery();
                    $data['proyecto'] = $response['data'];
                    
                    $listdetalle=$this->add_detalle(json_decode($this->post('Detalle')),$Id);        
                    $oMspend_proyectodet=new Mspend_proyectodet();
                    $result=$oMspend_proyectodet->insert($listdetalle);
                    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
            else
            {
                if ($obj-> update()) {
                    $listdetalle=$this->add_detalle(json_decode($this->post('Detalle')),$Id);        
                    $oMspend_proyectodet=new Mspend_proyectodet();
                    $result=$oMspend_proyectodet->insert($listdetalle);
                    
                    $response = $obj -> get_recovery();
                    $data['proyecto'] = $response['data'];
                    
                    #====================Contratos============================
                    $responsecontrato=$oMnumcontrato->get_recovery_spendplan();
                    
                    $IdContrato=0;
                    $oMnumcontrato->NumeroC=$data['proyecto']->Folio;
                    $oMnumcontrato->Comentario=$data['proyecto']->Folio;
                    if($responsecontrato['status']){
                        $oMnumcontrato->IdContrato=$responsecontrato['data']->IdContrato;
                        $oMnumcontrato->update();
                    }else{
                        $oMnumcontrato->insert();
                    }

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'typemsg'=>1,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                        'typemsg'=>1,
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }
        else
        {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
                'typemsg'=>2,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function add_detalle($datapost,$Id){       
        $listdetalle=array();
        //ReqInventario
        $oMspend_proyectodet=new Mspend_proyectodet();
        $oMspend_proyectodet->IdProyecto=$Id;
        $oMspend_proyectodet->delete();
        
        #JSON
        /*
        foreach($datapost as $element){
            $listdetalle[] = array(
                'IdProyecto' => $Id,
                'Concepto' =>$element['Concepto'],
                'Monto' => $element['Monto'],
                'Porcentaje'=>$element['Porcentaje'],
            );
        }*/
        
        #Multipart
        foreach($datapost as $element){
            $listdetalle[] = array(
                'IdProyecto' => $Id,
                'Concepto' =>$element->Concepto,
                'Monto' => $element->Monto,
                'Porcentaje'=>$element->Porcentaje,
            );
        }
        
        return $listdetalle;
    }
    
    public function Conceptos_get(){
            
        $listdetalle=array();
        $Tipo=$this->get('Tipo');
        $listceptos=["Valor Venta","Materiales","Equipos","Mano de Obra","Vehículos","Contrastista","Viáticos","Burden"];
        if($Tipo=='2'){
            $listceptos=["Facturación","Materiales","Equipos","Mano de Obra","Vehículos","Contrastista","Viáticos","Burden"];
        }
        
        #DATOS DEL PROYECTO
        $IdProyecto=$this->get('IdProyecto');
        $oMspend_proyecto=new Mspend_proyecto();
        $oMspend_proyecto->IdProyecto=$this->get('IdProyecto');
        $responseproyect=$oMspend_proyecto->get_recovery();
        $Proyecto=null;
        $FechaTermino='';
        $CantidadTermino=0;
        $GAVentas=0;
        $NetProfitPlan=0;
        $DiasTranscurridos=0;
        $FechaActual=date('Y-m-d');
        $FechaInicio='';
        if($responseproyect['status']){
            $Proyecto=$responseproyect['data'];
            $FechaTermino=$Proyecto->FechaTermino;
            $FechaInicio=$Proyecto->FechaI;
            $Proyecto->FechaI=date("d-m-Y",strtotime($FechaInicio));
            if($Proyecto->Estatus=='Cerrado'){
                $FechaActual=$Proyecto->FechaCierre;
            }
            
            $datetime1 = date_create($FechaInicio);
            $datetime2 = date_create($FechaActual);
            $contadorday = date_diff($datetime1, $datetime2);
            $differenceFormat = '%a';
            $DiasTranscurridos= $contadorday->format($differenceFormat);
            
            
            $CantidadTermino=$Proyecto->CantidadTermino;
            $GAVentas=$Proyecto->GAVentas;
            $NetProfitPlan=$Proyecto->NetProfit;
        }
            
        #FIN Datos del proyecto
        
        $MontoValorVenta=0;
        $ValorFacturaActual=0;
        
        $GastosOperacionPlan=0;
        $GastosOperacionActual=0;
      
        foreach($listceptos as $element){
            $MontoActual=0;
            
            $oMspend_proyectodet=new Mspend_proyectodet();
            $oMspend_proyectodet->Concepto=$element;
            $oMspend_proyectodet->IdProyecto=$this->get('IdProyecto');
            $response=$oMspend_proyectodet->get_recovery();
            
            $Monto=0;
            $Porcentaje=0;
            if($response['status']){#MONTO es el plan esperado
               $Monto= $response['data']->Monto;
               $Porcentaje=$response['data']->Porcentaje;
            }
            
            $bnd=false;
            $ConceptoActual=$element;
            if($element=='Valor Venta'){
                $bnd=true;   
                $MontoValorVenta= $Monto;
                $ConceptoActual='Facturación';
            }else{
                $GastosOperacionPlan+=$Monto;
            }
            
            //Monto actual
            $Mspend_ordenc=new Mspend_ordenc();
            $Mspend_ordenc->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Mspend_ordenc->Concepto=$ConceptoActual;
            $Mspend_ordenc->RegEstatus='A';
            $Mspend_ordenc->IdProyecto=$this->get('IdProyecto');
            $responseoc=$Mspend_ordenc->get_recovery_MontoActual();
            $MontoActual=0;
            
            if($responseoc['status']){
                $MontoActual=$responseoc['MontoActual'];
                if($element=='Valor Venta'){//Gastos OC Facturacion
                    $ValorFacturaActual=$MontoActual;        
                }else{
                    $GastosOperacionActual+=$MontoActual;
                }
            }
            $PorcentajeActual=0;
            if($Porcentaje>0){
                $PorcentajeActual=($MontoActual*$Porcentaje)/$Monto;    
            }
            
            $DispYTD=$Monto-$MontoActual;
            #sumas
            #Semaforos .9 es la base del color verde
            $baseVerde=$Monto*.9;
            $color='';
            if($MontoActual>$Monto){//Perdiendo
                $color='Perdiendo';    
            }else if($MontoActual>$baseVerde){//Neutro
                $color='Neutro';
            }
            else if($MontoActual<=$baseVerde){//GANANDO
                $color='Ganando';
            }
            
            $listdetalle[] = array(
                'Concepto' => $element,
                'Monto' =>number_format($Monto,2,'.',''),
                'Porcentaje' =>number_format($Porcentaje,0,'.',''),
                'Habilitado'=>$bnd,
                'MontoActual'=>number_format($MontoActual,2,'.',''),
                'PorcentajeAct'=>number_format($PorcentajeActual,0,'.',''),
                'DispYTD'=>number_format($DispYTD,2,'.',''),
                'Color'=>$color,
            );
        }//FIN FOREACH
        
        #Calculos grafica
        $CostoOperacionalPorc=0;
        if($MontoValorVenta>0){
            $CostoOperacionalPorc=($ValorFacturaActual*100)/$MontoValorVenta;    
        }
        
        $GrossActualPorc=0;
        if($GastosOperacionPlan>0){
            $GrossActualPorc=($GastosOperacionActual*100)/$GastosOperacionPlan;    
        }
        
        #GROSS ACTUAl = GASTADO ACTUAL
        $GrossActual=0;
        $Dias=$this->getDiasProyecto($FechaTermino,$CantidadTermino);
        
        $GADias=0;
        $PorcentajeDias=0;
        if($GAVentas>0){
            $GADias=($GAVentas/$Dias)*$DiasTranscurridos;
        }
        $GrossActual=$GastosOperacionActual + $GADias;
        
        if($Dias>0){
            $PorcentajeDias=($DiasTranscurridos*100)/$Dias;    
        }
        
        
        #NET PROFIT
        
        $NetProfitActual=$MontoValorVenta-$GrossActual;
        
        $NetProfitPorcAct=0;
        if($NetProfitPlan>0){
            $NetProfitPorcAct=($NetProfitActual*100)/$MontoValorVenta;    
        }
        
        $FechaTermino='';
        if($FechaInicio!=''){
            $FechaTermino=date("d-m-Y",strtotime($FechaInicio."+ $Dias days"));
        }
        
        return $this -> set_response([
            'status' => true,
            'conceptos' => $listdetalle,
            'FechaActual'=>date('d-m-Y'),
            'proyecto'=>$Proyecto,
            'ValorVenta'=>$MontoValorVenta,
            'ValorVentaPorcentaje'=>100,
            
            'CostoOperacionalAct'=>number_format($ValorFacturaActual,2,'.',''),
            'CostoOperacionalPorcAct'=>number_format($CostoOperacionalPorc,0,'.',''),
            'GrossActual'=>number_format($GrossActual,2,'.',''),
            'GrossActualPorc'=>number_format($GrossActualPorc,0,'.',''),
            'NetProfitActual'=>number_format($NetProfitActual,2,'.',''),
            'NetProfitPorcAct'=>number_format($NetProfitPorcAct,0,'.',''),
            'DiasTranscurridos'=>$DiasTranscurridos,
            'PorcentajeDias'=>number_format($PorcentajeDias,0,"",""),
            'FechaTermino'=>$FechaTermino, 
            'message' => 'Se ha agregado correctamente.',
        ], REST_Controller:: HTTP_CREATED);
    }
    
    public function getDiasProyecto($FechaTermino,$CantidadTermino){
        if($FechaTermino=='Dia'){
            return $CantidadTermino;
        }else if($FechaTermino=='Semana'){
            return (7*$CantidadTermino);
        }else if($FechaTermino=='Mes'){
            return (30*$CantidadTermino);
        }
    }
    
    //Lista de proyectos
    public function ListYTD_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $objeto = new Mspend_proyecto();
        $objeto->IdSucursal =$IdSucursal;
        $objeto->Proyecto=$this->get('Nombre');
        $objeto->IdProyecto=$this->get('IdProyecto');
        $objeto->RegEstatus='A';
        $objeto->IdClienteS=$this->get('IdClienteS');
        $objeto->IdCliente=$this->get('IdCliente');
        if($this->get('FechaI')!=''){
            $objeto->FechaI=date("Y-m-d", strtotime($this->get('FechaI')));
            $objeto->FechaF=date("Y-m-d", strtotime($this->get('FechaF')));
        }

    
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
        
        $arrayresult=array();
        foreach($rows as $element){
            $reg=array();
            $GAVentas=$element->GAVentas;
            $NetProfitPlan=$element->NetProfit;
            
            $ValorVenta=0;
            $ValorVentaPorc=0;
            
            $FacturacionActual=0;
            $FacturacionActPorc=0;
            
            $GastoActual=0;
            $GastoActPorc=0;
            
            $NetProfitAct=0;
            $NetProfitActPorc=0;
            
            $AvanceProyecto=0;
            $AvanceProyPorc=0;
            
            #VALOR VENTA
            $oMspend_proyectodet=new Mspend_proyectodet();
            $oMspend_proyectodet->Concepto='Valor Venta';
            $oMspend_proyectodet->IdProyecto=$element->IdProyecto;
            $ValorVenta=$oMspend_proyectodet->get_montoplan();
            if($ValorVenta==''){
                $ValorVenta=0.00;
            }
            $ValorVentaPorc=100;
            
            #FACTURACION
            $Mspend_ordenc=new Mspend_ordenc();
            $Mspend_ordenc->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Mspend_ordenc->Concepto='Facturación';
            $Mspend_ordenc->IdProyecto=$element->IdProyecto;
            $FacturacionActual=$Mspend_ordenc->get_MontoSum();
            if($FacturacionActual==''){
                $FacturacionActual=0;
            }
            if($ValorVenta>0){
                $FacturacionActPorc=($FacturacionActual*100)/$ValorVenta;    
            } 
            
            #GastoACtual           
            #GROSS ACTUAl = GASTADO ACTUAL
            #PLAN
            $oMspend_proyectodet=new Mspend_proyectodet();
            $oMspend_proyectodet->ConceptoDif='Valor Venta';
            $oMspend_proyectodet->IdProyecto=$element->IdProyecto;
            $GastosOperacionPlan=$oMspend_proyectodet->get_montoplan();
            #REAL
            $Mspend_ordenc=new Mspend_ordenc();
            $Mspend_ordenc->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Mspend_ordenc->ConceptoDif='Facturación';
            $Mspend_ordenc->IdProyecto=$element->IdProyecto;
            $GastosOperacionActual=$Mspend_ordenc->get_MontoSum();
            $Dias=$this->getDiasProyecto($element->FechaTermino,$element->CantidadTermino);
            
            $datetime1 = date_create($element->FechaI);
            $datetime2 = date_create(date('Y-m-d'));
            $contador = date_diff($datetime1, $datetime2);
            $differenceFormat = '%a';
            $DiasTranscurridos= $contador->format($differenceFormat);
            
            $GADias=0;
            $PorcentajeDias=0;
            if($GAVentas>0){
                $GADias=($GAVentas/$Dias)*$DiasTranscurridos;
            }
            $GastoActual=$GastosOperacionActual + $GADias;
            
            
            if($GastosOperacionPlan>0){
                $GastoActPorc=($GastosOperacionActual*100)/$GastosOperacionPlan;    
            }
            
            #NET PROFIT
            $NetProfitAct=$ValorVenta-$GastoActual;
            
            if($NetProfitPlan>0){
                $NetProfitActPorc=($NetProfitAct*100)/$ValorVenta;    
            }
            
            #------------Porcentaje del proyecto
            $AvanceProyecto=$DiasTranscurridos;
            if($Dias>0){
                $AvanceProyPorc=($DiasTranscurridos*100)/$Dias;    
            }
            
            $reg['IdProyecto']=$element->IdProyecto;
            $reg['Folio']=$element->Folio;
            $reg['Proyecto']=$element->Proyecto;
            $reg['ValorVenta']=number_format($ValorVenta,2,'.','');
            $reg['ValorVentaPorc']=number_format($ValorVentaPorc,0,'','');
            $reg['FacturacionActual']=number_format($FacturacionActual,2,'.','');
            $reg['FacturacionActPorc']=number_format($FacturacionActPorc,0,'','');
            $reg['GastoActual']=number_format($GastoActual,2,'.','');
            $reg['GastoActPorc']=number_format($GastoActPorc,0,'','');
            $reg['NetProfitAct']=number_format($NetProfitAct,2,'.','');
            $reg['NetProfitActPorc']=number_format($NetProfitActPorc,0,'','');
            $reg['AvanceProyecto']=$AvanceProyecto;
            $reg['AvanceProyPorc']=number_format($AvanceProyPorc,0,'','');
            
            array_push($arrayresult,$reg);
        }
        

        return $this->set_response([
            'status' => true,
            'proyecto' => $arrayresult,
            'UrlPdf'=>base_url().$this->ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
            'pagination' => $pager,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    function Finish_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        $objeto = new Mspend_proyecto();
        $objeto->IdProyecto=$this->get('IdProyecto');
        $objeto->Estatus='Cerrado';
        $objeto->FechaMod=date('Y-m-d H:i:s');
        $objeto->FechaCierre=date('Y-m-d');
        $objeto->update_estatus();
        
        return $this->set_response([
            'status' => true,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}