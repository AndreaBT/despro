<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cctaporcobrar extends REST_Controller
{
    public $RutaPdf;
    public $Ruta;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ctaporcobrar/Mctaporcobrar');
        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('Mservicio');
        $this->load->model('Mtiposervicio');
        $this->load->model('facturacion/Mfactura');

        $this->load->model('ctaporpagar/Mctaporpagar');

        $this->RutaPdf='assets/files/factura';
        $this->Ruta='assets/files/cuentasporcobrar';
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
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $oMctaporcobrar = new Mctaporcobrar();
        $oMctaporcobrar->IdSucursal = $IdSucursal;
        //$oMctaporcobrar->NumCuenta = $this->get('NumCuenta');
        $oMctaporcobrar->RegEstatus = $this->get('RegEstatus');
        //$oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');
        $oMctaporcobrar->NombreCliente = $this->get('NombreCliente');
        $oMctaporcobrar->Sucursal = $this->get('Sucursal');
        $oMctaporcobrar->Fecha_I = dateformato($this->get('FechaI'));
        $oMctaporcobrar->Fecha_F = dateformato($this->get('FechaF'));

        
        $oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');

        $oMctaporcobrar->VigenciaFiltro = $this->get('VigenciaFiltro'); 
        $oMctaporcobrar->Cliente = $this->get('Cliente');

        $oMctaporcobrar->NoContrato = $this->get('NoContrato');
        

        $Res= $oMctaporcobrar->get_listSuma();
        $sumaT=0;
        foreach ($Res as $element){
            if($element->suma>0){
                $sumaT=$element->suma;
            }
        }

        $data['suma'] = $sumaT;
        
        // Paginación
        $rows =  $oMctaporcobrar->get_list();

        $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMctaporcobrar->Limit=$Entrada;


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $oMctaporcobrar->Tamano = $pager->PageSize;
        $oMctaporcobrar->Offset = $pager->Offset;

        $data['ctaporcobrar'] = $oMctaporcobrar->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdSucursal.'/',
            'RutaFile' => base_url().$this->Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function ListNoCobrado_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $oMctaporcobrar = new Mctaporcobrar();
        $oMctaporcobrar->IdSucursal = $IdSucursal;
        //$oMctaporcobrar->NumCuenta = $this->get('NumCuenta');
        $oMctaporcobrar->RegEstatus = $this->get('RegEstatus');
        //$oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');
        $oMctaporcobrar->NombreCliente = $this->get('NombreCliente');
        $oMctaporcobrar->Sucursal = $this->get('Sucursal');
        $oMctaporcobrar->Fecha_I = dateformato($this->get('FechaI'));
        $oMctaporcobrar->Fecha_F = dateformato($this->get('FechaF'));

        
        $oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');

        $oMctaporcobrar->VigenciaFiltro = $this->get('VigenciaFiltro'); 
        $oMctaporcobrar->Cliente = $this->get('Cliente');

        $oMctaporcobrar->NoContrato = $this->get('NoContrato');
        

        $Res= $oMctaporcobrar->get_listSumaNo();
        $sumaT=0;
        foreach ($Res as $element){
            if($element->suma>0){
                $sumaT=$element->suma;
            }
        }

        $data['suma'] = $sumaT;
        
        // Paginación
        $rows =  $oMctaporcobrar->get_ListNocobrado();

        $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMctaporcobrar->Limit=$Entrada;


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $oMctaporcobrar->Tamano = $pager->PageSize;
        $oMctaporcobrar->Offset = $pager->Offset;

        $data['ctaporcobrar'] = $oMctaporcobrar->get_ListNocobrado();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdSucursal.'/',
            'RutaFile' => base_url().$this->Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

   

    public function ListSinFecha_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $oMctaporcobrar = new Mctaporcobrar();
        $oMctaporcobrar->IdSucursal = $IdSucursal;
        //$oMctaporcobrar->NumCuenta = $this->get('NumCuenta');
        $oMctaporcobrar->RegEstatus = $this->get('RegEstatus');
        //$oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');
        $oMctaporcobrar->NombreCliente = $this->get('NombreCliente');
        $oMctaporcobrar->Sucursal = $this->get('Sucursal');
       

        
        $oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');

        $oMctaporcobrar->VigenciaFiltro = $this->get('VigenciaFiltro'); 
        $oMctaporcobrar->Cliente = $this->get('Cliente');

        $oMctaporcobrar->NoContrato = $this->get('NoContrato');
        

        $Res= $oMctaporcobrar->get_listSumaSinFecha();
        $sumaT=0;
        foreach ($Res as $element){
            if($element->suma>0){
                $sumaT=$element->suma;
            }
        }

        $data['sumaS'] = $sumaT;
        
        // Paginación
        $rows =  $oMctaporcobrar->get_listSinFecha();

        $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMctaporcobrar->Limit=$Entrada;


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);
        $oMctaporcobrar->Tamano = $pager->PageSize;
        $oMctaporcobrar->Offset = $pager->Offset;

        $data['ctaporcobrar'] = $oMctaporcobrar->get_listSinFecha();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdSucursal.'/',
            'RutaFile' => base_url().$this->Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }  

    public function NombreCliente_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mctaporcobrar= new Mctaporcobrar();
        $Mctaporcobrar->IdSucursal=$IdSucursal;
        //$oMctaporcobrar->NumCuenta = $this->get('NumCuenta');
        //$oMctaporcobrar->TipoFiltro = $this->get('TipoFiltro');
        // $Mctaporcobrar->Fecha_I = dateformato($this->get('FechaI'));
        // $Mctaporcobrar->Fecha_F = dateformato($this->get('FechaF'));
        
        
        $data['NombreEmpresa'] = $Mctaporcobrar->get_NombresEmpresa();
        
       
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    
    public function EmpresaSucursales_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mctaporcobrar= new Mctaporcobrar();
        $Mctaporcobrar->IdSucursal=$IdSucursal;
        //$oMctaporcobrar->NumCuenta = $this->get('NumCuenta');
        $Mctaporcobrar->NombreCliente = $this->get('NombreCliente');
        $Mctaporcobrar->Sucursal = $this->get('Sucursal');

        
        // $Mctaporcobrar->Fecha_I = dateformato($this->get('FechaI'));
        // $Mctaporcobrar->Fecha_F = dateformato($this->get('FechaF'));
        
        
        $data['EmpresaSucural'] = $Mctaporcobrar->get_Empresa_SucursalV2();
       
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function NumContrato_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mctaporcobrar= new Mctaporcobrar();
        $Mctaporcobrar->IdSucursal=$IdSucursal;
        $Mctaporcobrar->Sucursal = $this->get('Sucursal');
        
        
        $data['NoContrato'] = $Mctaporcobrar->get_NumeroContrato();
       
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    

    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        $Mctaporcobrar = new Mctaporcobrar();

        $Id = (int) $this->get('IdCtaCobrar');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $Mctaporcobrar ->IdCtaCobrar = $Id;
        }
        $response = $Mctaporcobrar->get_recovery();
        if ($response['status']) {
            $data['ctaporcobrar'] = $response['data'];
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


    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
      
        $oMctaporpagar= new Mctaporpagar();
        $oMctaporpagar->IdCtaPagar = $Id;
        $response = $oMctaporpagar ->get_recovery();

        if ($response['status']) {

            if ( $oMctaporpagar ->delete()) {

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
    
    public function Add_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Factura' => $this -> post('IdFactura'),
            
        ]);

        $v -> rule('required', [
            'Factura',
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdCtaCobrar');

        

            $oMctaporcobrar = new Mctaporcobrar();
            $oMctaporcobrar ->IdCtaCobrar =  $Id;
            $oMctaporcobrar->IdSucursal = $IdSucursal;
            $oMctaporcobrar->IdFactura = $this->post('IdFactura');
            $oMctaporcobrar->FechaCobro = $this->post('FechaCobro');
            //$oMctaporcobrar->Comentario = $this->post('Comentario');
            $oMctaporcobrar->Estatus = "NO";
            $oMctaporcobrar->FechaReg = date('Y-m-d');
            $oMctaporcobrar->FechaMod = date('Y-m-d H:i:s');

            /*$route = CrearRutaArchivo($this->RutaPdf,$IdSucursal);

            $files = $this->uploadfile->savefile($route.'/', 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);
            $oMctaporcobrar->Archivo = $files;*/

            if ($oMctaporcobrar->IdCtaCobrar == 0) {
                $Id = $oMctaporcobrar-> insert();
                if ($Id > 0) {
                    $oMctaporcobrar->IdCtaCobrar= $Id;
                    $response = $oMctaporcobrar->get_recovery();
                    $data['ctaporcobrar'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                if ( $oMctaporcobrar-> update()) {
                    $response = $oMctaporcobrar->get_recovery();
                    $data['ctaporcobrar'] = $response['data'];
    
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {
    
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*public function ChangeEstatus_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
       
        
        $v = new Valitron\Validator([
            'Observacion'=> $this -> post('Observacion'),
        ]);

        $v -> rule('required', [
            'Observacion'
        ]) -> message('El campo {field} es requerido.');

        if($v->validate()){
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdCtaCobrar');
            
    
            $oMctaporcobrar = new Mctaporcobrar();
            $oMctaporcobrar->IdCtaCobrar = $Id;
            $Observacion = $this -> post('Observacion');
            $response = $oMctaporcobrar ->get_recovery();
            if($response['status'])
            {
                if($oMctaporcobrar ->ChangeEstatus()) {

                    $data['ctaporcobrar']= $response['data'];
                    //Mover esta funcion en el modulo de facturacion al Prefacturas Autorizadas al autorizar la prefectura

                    $oMfactura = new Mfactura();
                    $oMfactura->IdFactura=$response['data']->IdFactura;
                    $oMfactura->RegEstatus= 'A';
                    $objfactura =  $oMfactura->get_factura();

                    //Buscamos el servicio, idcliente, idclientes, id servicio
                    $oMservicio= new Mservicio();
                    $oMservicio->IdServicio=$this -> post('IdServicio');
                    $objservicio =  $oMservicio->get_servicio();


                    $IdClienteS=$objservicio['data']->IdClienteS;
                    $IdCliente=$objservicio['data']->IdCliente;
                    $IdTipoServicio=$objservicio['data']->Tipo_Serv;
                    $IdContrato=$objservicio['data']->IdContrato;

                    //SE BUSCA EL IDCONFIGS
                    $oMtiposervicio = new Mtiposervicio();
                    $oMtiposervicio->IdTipoSer=$IdTipoServicio;
                        $objtiposerv=  $oMtiposervicio->get_tiposervicio();


                    $IdConfigS=$objtiposerv['data']->IdConfigS;

                    $Fecha =explode("-", $response['data']->FechaCobro);

                    $Anio =$Fecha[0];
                    $Mes =$Fecha[1];

                    $oMestadofinanciero = new Mestadofinanciero();
                    $oMestadofinanciero->Anio=$Anio;
                    $oMestadofinanciero->Mes=$Mes;
                    $oMestadofinanciero->IdSucursal=$IdSucursal;
                    $oMestadofinanciero->IdClienteS=$IdClienteS;
                    $oMestadofinanciero->IdCliente=$IdCliente;
                    $oMestadofinanciero->IdTipoServ=$IdTipoServicio;
                    $oMestadofinanciero->IdConfigS=$IdConfigS;

                    if ($IdContrato==0)
                    {
                        $IdContrato='';
                    }
                    $oMestadofinanciero->IdContrato=$IdContrato;
                    $objfinanciero =  $oMestadofinanciero->get_recovery();

                    if ($objfinanciero['status'])
                    {
                        $Monto =$objfinanciero['data']->Facturacion;
                        $oMestadofinanciero->Facturacion =  $Monto + $objfactura['data']->Total;
                        $oMestadofinanciero->update();
                    }
                    else{
                        $oMestadofinanciero->Facturacion =  $objfactura['data']->Total;
                        $oMestadofinanciero->insert();
                    }

                    return $this->set_response([
                        'status' => true,
                        'data' =>$data,
                        'message' => 'Se ha realizado el cobro',
                    ], REST_Controller::HTTP_ACCEPTED);
                } else {

                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al realizar la accion.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        }else {
            $data['errores'] = $v->errors();
            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }*/

    public function ChangeEstatus_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Observacion'=> $this -> post('Observacion'),
            'FechaRealCobro' => $this->post('FechaRealCobro')
        ]);

        $v -> rule('required', [
            'Observacion',
            'FechaRealCobro'
        ]) -> message('El campo {field} es requerido.');

        if($v -> validate()){
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $Id = $this -> post('IdCtaCobrar');
            $Observacion = $this -> post('Observacion');
            $FechaRealCobro =$this->post('FechaRealCobro');

            $oMctaporcobrar = new Mctaporcobrar();
            $oMctaporcobrar->IdCtaCobrar = $Id;
            $oMctaporcobrar->Observacion = $Observacion;
            $oMctaporcobrar->FechaRealCobro = $FechaRealCobro;
            

            $response = $oMctaporcobrar ->get_recovery();
            if($response['status'])
            {
                if($oMctaporcobrar ->ChangeEstatus()) {

                
                    //Mover esta funcion en el modulo de facturacion al Prefacturas Autorizadas al autorizar la prefectura

                    $oMfactura = new Mfactura();
                    $oMfactura->IdFactura=$response['data']->IdFactura;
                    $oMfactura->RegEstatus= 'A';
                    $objfactura =  $oMfactura->get_factura();

                    //Buscamos el servicio, idcliente, idclientes, id servicio
                    $oMservicio= new Mservicio();
                    $oMservicio->IdServicio=$this -> post('IdServicio');
                    $objservicio =  $oMservicio->get_servicio();


                    $IdClienteS=$objservicio['data']->IdClienteS;
                    $IdCliente=$objservicio['data']->IdCliente;
                    $IdTipoServicio=$objservicio['data']->Tipo_Serv;
                    $IdContrato=$objservicio['data']->IdContrato;

                    //SE BUSCA EL IDCONFIGS
                    $oMtiposervicio = new Mtiposervicio();
                    $oMtiposervicio->IdTipoSer=$IdTipoServicio;
                        $objtiposerv=  $oMtiposervicio->get_tiposervicio();


                    $IdConfigS=$objtiposerv['data']->IdConfigS;

                    $Fecha =explode("-", $response['data']->FechaCobro);

                    $Anio =$Fecha[0];
                    $Mes =$Fecha[1];

                    $oMestadofinanciero = new Mestadofinanciero();
                    $oMestadofinanciero->Anio=$Anio;
                    $oMestadofinanciero->Mes=$Mes;
                    $oMestadofinanciero->IdSucursal=$IdSucursal;
                    $oMestadofinanciero->IdClienteS=$IdClienteS;
                    $oMestadofinanciero->IdCliente=$IdCliente;
                    $oMestadofinanciero->IdTipoServ=$IdTipoServicio;
                    $oMestadofinanciero->IdConfigS=$IdConfigS;

                    if ($IdContrato==0)
                    {
                        $IdContrato='';
                    }
                    $oMestadofinanciero->IdContrato=$IdContrato;
                    $objfinanciero =  $oMestadofinanciero->get_recovery();

                    if ($objfinanciero['status'])
                    {
                        $Monto =$objfinanciero['data']->Facturacion;
                        $oMestadofinanciero->Facturacion =  $Monto + $objfactura['data']->Total;
                        $oMestadofinanciero->update();
                    }
                    else{
                        $oMestadofinanciero->Facturacion =  $objfactura['data']->Total;
                        $oMestadofinanciero->insert();
                    }

                    return $this->set_response([
                        'status' => true,
                        'message' => 'Se ha realizado el cobro',
                    ], REST_Controller::HTTP_ACCEPTED);
                } else {

                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al realizar la accion.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'No encontrado.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        
    }

    public function addArchivo_post(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Id = $this -> post('IdCtaCobrar');


        $oMctaporcobrar = new Mctaporcobrar();
        $oMctaporcobrar->IdCtaCobrar = $Id;
        $route = CrearRutaEmpSuc($this->Ruta,$IdEmpresa,$IdSucursal);

        $files = $this->uploadfile->savefile($route.'/', 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);
        $oMctaporcobrar->Archivo = $files;

        $response = $oMctaporcobrar ->get_recovery();
        if($response['status'])
        {
            if($oMctaporcobrar ->AddArchivo()) {

               
                //Mover esta funcion en el modulo de facturacion al Prefacturas Autorizadas al autorizar la prefectura

                

                return $this->set_response([
                    'status' => true,
                    'message' => 'Se ha realizado el cobro',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al realizar la accion.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            return $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function UpdateValidity_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        

        $oMctaporcobrar = new Mctaporcobrar();

		if ($IdSucursal > 0) {

			$oMctaporcobrar->updateValidity();

			return $this->set_response([
				'status' => true,
				'message' => 'Se ha actualizado la vigencia',
			], REST_Controller::HTTP_ACCEPTED);
		} else {

			return $this->set_response([
				'status' => false,
				'message' => 'Error al actualizar la vigencia.',
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}


    //!AQUÍ EMPIEZAN LAS GRÁFICAS - ANDREA 

    public function EstimadosCuentasGlobal_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Anio = date('Y');
        $Meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

        //!Arrays
            //?ARRAYS ESTIMADOS 
                $EstimadoCPCGlobal=array();
                $EstimadoCPPGlobal=array();
                $EstimadoFlujoCaja=array();
            //?ARRAYS ACTUALES  
                $ActualCPCGlobal=array();
                $ActualCPPGlobal=array();
                $ActualFlujoCaja=array();
        //! fin Arrays

        for ($i = 1; $i < count($Meses) + 1; $i++) {
            //!CUENTAS POR COBRAR ESTIMADO
            $Mctaporcobrar= new Mctaporcobrar();
            $Mctaporcobrar->IdSucursal=$IdSucursal;
            if ($i < 10) {
                $Mctaporcobrar->Fecha =  $Anio . '-0' . $i;
            } else {
                $Mctaporcobrar->Fecha =  $Anio . '-' . $i;
            }

            $sumaGlobalEstiamdoCPC=0;
            $dataQ= $Mctaporcobrar->get_EstimadoGlobalCuentasPCobrar();
            $dataRes=array();
            foreach ($dataQ as $element) {
                if ($element->SumaGlobal > 0) {
                    $sumaGlobalEstiamdoCPC = $element->SumaGlobal;
                }else{
                    $sumaGlobalEstiamdoCPC=0;
                }
                
            }
            $dataRes['value'] = $sumaGlobalEstiamdoCPC;
            array_push($EstimadoCPCGlobal, $sumaGlobalEstiamdoCPC);

            //!CUENTAS POR PAGAR ESTIMADO
            $Mctaporpagar= new Mctaporpagar();
            $Mctaporpagar->IdSucursal=$IdSucursal;
            if ($i < 10) {
                $Mctaporpagar->Fecha =  $Anio . '-0' . $i;
            } else {
                $Mctaporpagar->Fecha =  $Anio . '-' . $i;
            }
            $sumaGlobalEstiamdoCPP=0;
            $RestaFlujoCaja=0;
            $dataR= $Mctaporpagar-> get_EstimadoGlobalCuentasPPagar();
            $dataResCPP=array();
            $dataFlujoCaja=array();
            foreach ($dataR as $element) {
                if ($element->SumaGlobalCPP > 0) {
                    $sumaGlobalEstiamdoCPP = $element->SumaGlobalCPP;
                    
                }else{
                    $sumaGlobalEstiamdoCPP=0;
                }
                
            }
            //!ESTIMAODS FLUJO CAJA 
            if($sumaGlobalEstiamdoCPC>=0 &&  $sumaGlobalEstiamdoCPP>=0){
                $RestaFlujoCaja=$sumaGlobalEstiamdoCPC-$sumaGlobalEstiamdoCPP;
            }


            $dataResCPP['value'] = $sumaGlobalEstiamdoCPP;
            $dataFlujoCaja['value'] = $RestaFlujoCaja;
            array_push($EstimadoCPPGlobal, $sumaGlobalEstiamdoCPP);
            array_push($EstimadoFlujoCaja, $RestaFlujoCaja);

            //!CUENTAS POR COBRAR ACTUAL 
            $Mctaporcobrar= new Mctaporcobrar();
            $Mctaporcobrar->IdSucursal=$IdSucursal;
            if ($i < 10) {
                $Mctaporcobrar->Fecha =  $Anio . '-0' . $i;
            } else {
                $Mctaporcobrar->Fecha =  $Anio . '-' . $i;
            }
            $SumaGlobalActualCPC=0;
            $dataCPCActual=$Mctaporcobrar->get_ActualGlobalCuentasPCobrar();
            $dataCPCActualArray=array();
            foreach($dataCPCActual as $actual){
                if($actual->SumaGlobalActual>0){
                    $SumaGlobalActualCPC = $actual->SumaGlobalActual;
                }else{
                    $SumaGlobalActualCPC=0;
                }
            }
            $dataCPCActualArray['value']=$SumaGlobalActualCPC;
            array_push($ActualCPCGlobal,$SumaGlobalActualCPC);

            //!CUENTAS POR PAGAR ACTUAL 
            $Mctaporpagar= new Mctaporpagar();
            $Mctaporpagar->IdSucursal=$IdSucursal;
            if ($i < 10) {
                $Mctaporpagar->Fecha =  $Anio . '-0' . $i;
            } else {
                $Mctaporpagar->Fecha =  $Anio . '-' . $i;
            }
            $SumaGlobalActualCPP = 0;
            $dataCPPActual= $Mctaporpagar-> get_ActualGlobalCuentasPPagar();
            $dataCPPActualArray=array();
            foreach($dataCPPActual as $actualCPP){
                if($actualCPP->SumaGlobalActualCPP>0){
                    $SumaGlobalActualCPP = $actualCPP->SumaGlobalActualCPP;
                }else{
                    $SumaGlobalActualCPP=0;
                }
            }
            $dataCPPActualArray['value']=$SumaGlobalActualCPP;
            array_push($ActualCPPGlobal,$SumaGlobalActualCPP);
            
            //!ACTUAL FLUJO DE CAJA 
            $RestaFlujoCajaActual=0;
            $dataFlujoCajaActual=array();
            if($SumaGlobalActualCPC>=0 &&  $SumaGlobalActualCPP>=0){
                $RestaFlujoCajaActual=$SumaGlobalActualCPC-$SumaGlobalActualCPP;
            }
            $dataFlujoCajaActual['value']= $RestaFlujoCajaActual;
            array_push($ActualFlujoCaja,$RestaFlujoCajaActual);

        }

        $data['sumaGlobalEstiamdoCPC'] = $EstimadoCPCGlobal;
        $data['sumaGlobalEstiamdoCPP'] = $EstimadoCPPGlobal;
        $data['FlujoCajaEstimado'] = $EstimadoFlujoCaja;

        $data['sumaGlobalActualCPC'] = $ActualCPCGlobal;
        $data['sumaGlobalActualCPP'] = $ActualCPPGlobal;
        $data['FlujoCajaActual'] = $ActualFlujoCaja;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);


        


    }
}