<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cfactura extends REST_Controller
{
    public $RutaPdf;
    public $ruta='assets/cajas';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('facturacion/Mfactura');
        $this->load->model('facturacion/Mfac_serfol');
        $this->load->model('facturacion/Mdatallefactura');
        $this->load->model('ctaporcobrar/Mctaporcobrar');
         $this->load->library('UploadFile');
        $this->load->model('Mservicio');
        $this->load->model('Mfolio');
        $this->RutaPdf='assets/files/factura';
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->AFacturar=$this->get('AFacturar');
        $oMfactura->Facturado=$this->get('Facturado');
        $oMfactura->FechaFacReal=$this->get('FechaFacReal');
        $oMfactura->TipoFiltro=$this->get('TipoFiltro');
        $oMfactura->RegEstatus=$this->get('RegEstatus');
        $oMfactura->NombreCliente=$this->get('Nombre');
        $oMfactura->Anulado=$this->get('Anulado');

        // Paginación
        $rows =  $oMfactura->get_list();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['prefacturas'] = $oMfactura->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
        ], REST_Controller::HTTP_OK);
    }

    public function findFacrlibreAutorize_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->AFacturar=$this->get('AFacturar');
        $oMfactura->Facturado=$this->get('Facturado');
        $oMfactura->FechaFacReal=$this->get('FechaFacReal');
        $oMfactura->TipoFiltro=$this->get('TipoFiltro');
        $oMfactura->RegEstatus=$this->get('RegEstatus');
        $oMfactura->TipoFactura=$this->get('TipoFactura');
        $oMfactura->NombreCliente=$this->get('Nombre');

        $rows =  $oMfactura->get_factLibreAutorize();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['prefacturaslibreAutorizada'] = $oMfactura->get_factLibreAutorize();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
        ], REST_Controller::HTTP_OK);
    }

    public function listservfact_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->IdCliente=$this->get('IdCliente');
        $oMfactura->FolioServ=$this->get('Nombre');
        $oMfactura->Factura='s';

        // Paginaci�n
        $rows =  $oMfactura->get_listservfac();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['servxfact'] = $oMfactura->get_listservfac();
        //$data['facturaAnulada'] = $oMfactura->get_facturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function ListFacturaLibre_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->TipoFactura=$this->get('TipoFactura');

        // Paginaci�n
        $rows =  $oMfactura->get_FacturaLibre();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['facturaLibre'] = $oMfactura->get_FacturaLibre();
        //$data['facturaAnulada'] = $oMfactura->get_facturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    /* public function facturasAnuladas_get(){
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->RegEstatus='Anulada';

        // Paginaci�n
        $rows =  $oMfactura->get_facturaAnulada();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['facturaAnulada'] = $oMfactura->get_facturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }*/

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


    public function getFactura_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $oMservicio = new Mservicio();
        $oMservicio->IdServicio=$this->get('IdServicio');
        $resultserv= $oMservicio->get_servicio();

        $oMfactura = new Mfactura();
        $oMfactura ->IdServicio =$this->get('IdServicio');
        $oMfactura ->RegEstatus ='A';
        $response = $oMfactura->get_factura();


        if ($response['status'] || $resultserv['status']) {
            $data['factura'] ='';
            $data['servicio'] ='';
            $ArregloServ=[];
            $ArregloDetalle=[];

            if($response['status'])
            {
                $data['factura'] = $response['data'];

                $oMfac_serfol = new Mfac_serfol();
                $oMfac_serfol->IdFactura=$response['data']->IdFactura;
                $ArregloServ= $oMfac_serfol->get_list();

                $oMdatallefactura = new Mdatallefactura();
                $oMdatallefactura->IdFactura=$response['data']->IdFactura;
                $ArregloDetalle= $oMdatallefactura->get_list();
            }

            if ($resultserv['status'])
            {
                $data['servicio'] = $resultserv['data'];
            }

            $data['Relacionados'] = $ArregloServ;
            $data['Detalle'] = $ArregloDetalle;

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

    public function getFacturaLibre_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $oMfactura = new Mfactura();
        $oMfactura ->IdFactura =$this->get('IdFactura');
        $oMfactura ->RegEstatus ='A';
        $response = $oMfactura->get_facturaLibreRecovery();


        if ($response['status'] ) {
            $data['factura'] ='';
            $ArregloDetalle=[];

            if($response['status'])
            {
                $data['factura'] = $response['data'];

                $oMfac_serfol = new Mfac_serfol();
                $oMfac_serfol->IdFactura=$response['data']->IdFactura;
                $ArregloServ= $oMfac_serfol->get_list();

                $oMdatallefactura = new Mdatallefactura();
                $oMdatallefactura->IdFactura=$response['data']->IdFactura;
                $ArregloDetalle= $oMdatallefactura->get_list();
            }

            // if ($resultserv['status'])
            // {
            //     $data['servicio'] = $resultserv['data'];
            // }

            $data['Relacionados'] = $ArregloServ;
            $data['Detalle'] = $ArregloDetalle;
            $data['facturaLibre'] =  $response['data'];

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

        $v = new Valitron\Validator([
            'IdFactura' => $this -> post('IdFactura'),
            'IdServicio' => $this -> post('IdServicio'),
            'IdCliente' => $this -> post('IdCliente'),
            'IdClienteS' => $this -> post('IdClienteS')
        ]);

        $v->rule('required', [
            'IdFactura',
            'IdServicio',
            'IdCliente',
            'IdClienteS'
        ])->message('El campo {field} es requerido.');

        if ($v -> validate())
        {
            $Id = $this -> post('IdFactura');
            $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $oMfactura = new Mfactura();
            $oMfactura->IdFactura = $Id;
            $oMfactura->IdSucursal =$IdSucursal;
            $oMfactura->IdServicio = $this->post('IdServicio');
            $oMfactura->IdCliente=$this->post('IdCliente');
            $oMfactura->IdClienteS=$this->post('IdClienteS');
            $oMfactura->IdContrato=$this->post('IdContrato');

            $oMfactura->FolioServ=$this->post('FolioServ');
            $oMfactura->NombreCliente=$this->post('NombreCliente');
            $oMfactura->Sucursal=$this->post('Sucursal');
            $oMfactura->Direccion=$this->post('Direccion');
            $oMfactura->Contacto=$this->post('Contacto');
            $oMfactura->Telefono=$this->post('Telefono');
            $oMfactura->DatosFact=$this->post('DatosFact');
            $oMfactura->NoContrato=$this->post('NoContrato');
            $oMfactura->Servicio=$this->post('Servicio');
            $oMfactura->ComentarioContrato=$this->post('ComentarioContrato');
            $oMfactura->SubTotal=$this->post('Total');
            $oMfactura->Iva=0;
            $oMfactura->Total=$this->post('Total');
            $oMfactura->Facturado=$this->post('Facturado');
            $oMfactura->RegEstatus='A';
            $oMfactura->FechaReg=date('Y-m-d');
            $oMfactura->AFacturar='NO';
            $oMfactura->FechaFacReal='0000-00-00';
            $oMfactura->FolioFactReal='';
            $oMfactura->FechaMod=date('Y-m-d H:i:s');

            $DetalleServ=$this->post('DetalleServ');
            $Detalle=$this->post('Detalle');
            $Detalle1 = array();
            $Detalle2 = array();

            if($oMfactura->IdFactura == 0)
            {
                $ofolio= new Mfolio();
                $ofolio->IdSucursal = $IdSucursal;
                $ofolio->Tipo = 'FACTURACION';
                $respFol = $ofolio->get_foliovalidacion();

                $FolioFactura = '';
                $IdFolio = 0;
                $numero = 0;

                if($respFol['status'])
                {
                    $numero = $respFol['data']->Numero+1;
                    $FolioFactura =  $respFol['data']->Serie.'-'.$numero;
                    $IdFolio = $respFol['data']->IdFolio;
                }

                $oMfactura->FolioFactura = $FolioFactura;
                $Id = $oMfactura->Insert();

                if($Id > 0)
                {
                    $ofolio = new Mfolio();
                    $ofolio->IdFolio = $IdFolio;
                    $ofolio->Numero = $numero;
                    $ofolio->updateFolio();

                    $Contador=0;
                    foreach ($DetalleServ as $element)
                    {
                        if ($Contador>0)
                        {
                            $Detalle1[] = array(
                                'IdFactura' => $Id,
                                'IdServicio' => $element['IdServicio'],
                                'Folio' =>  $element['Folio'],
                                'Descripcion' => $element['Descripcion']
                            );
                        }
                        else
                        {
                            $oMfactura= new Mfactura();
                            $oMfactura->IdFactura=$Id;
                            $oMfactura->ComentarioServ= $element['Descripcion'];
                            $oMfactura->UpdateComentarioServ();
                        }
                        $Contador ++;
                    }
                    if (count($Detalle1)>0)
                    {
                        $oMfac_serfol = new Mfac_serfol();
                        $oMfac_serfol->Insert($Detalle1);
                    }

                    //detalle factura
                    foreach ($Detalle as $element)
                    {
                        $Detalle2[] = array(
                            'IdFactura' => $Id,
                            'Descripcion' => $element['Descripcion'],
                            'Cantidad' =>  $element['Cantidad'],
                            'CostoUni' => $element['CostoUni'],
                            'Iva' => 0,
                            'Total' => $element['Total']
                        );
                    }
                    if (count($Detalle2)>0)
                    {
                        $oMdatallefactura= new Mdatallefactura();
                        $oMdatallefactura->Insert($Detalle2);
                    }

                    $oMfactura = new Mfactura();
                    $oMfactura->IdServicio=$this ->post('IdServicio');
                    $oMfactura->FechaMod=date('Y-m-d H:i:s');
                    $oMfactura->Facturado=$this->post('Facturado');
                    $oMfactura->UpdateServicio();

                    $oMfactura->IdFactura= $Id;
                    $response = $oMfactura->get_factura();
                    $data['factura'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                }
                else
                {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos. 1',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
            else
            {
                if ($oMfactura-> update())
                {
                    $IdFactura=$oMfactura->IdFactura;
                    //se inserta el detalle de la factura
                    $oMfac_serfol = new Mfac_serfol();
                    $oMfac_serfol->IdFactura=$IdFactura;
                    $oMfac_serfol->delete();
                    $Contador=0;

                    foreach ($DetalleServ as $element)
                    {
                        if ($Contador>0)
                        {
                            $Detalle1[] = array(
                                'IdFactura' => $IdFactura,
                                'IdServicio' => $element['IdServicio'],
                                'Folio' =>  $element['Folio'],
                                'Descripcion' => $element['Descripcion']
                            );
                        }
                         else
                        {
                            $oMfactura= new Mfactura();
                            $oMfactura->IdFactura=$IdFactura;
                            $oMfactura->ComentarioServ= $element['Descripcion'];
                            $oMfactura->UpdateComentarioServ();
                        }

                        $Contador ++;
                    }
                    if (count($Detalle1)>0)
                    {
                        $oMfac_serfol = new Mfac_serfol();
                        $oMfac_serfol->Insert($Detalle1);
                    }

                    //detalle factura
                    $oMdatallefactura = new Mdatallefactura();
                    $oMdatallefactura->IdFactura=$IdFactura;
                    $oMdatallefactura->delete();

                    foreach ($Detalle as $element)
                    {
                        $Detalle2[] = array(
                            'IdFactura' => $IdFactura,
                            'Descripcion' => $element['Descripcion'],
                            'Cantidad' =>  $element['Cantidad'],
                            'CostoUni' => $element['CostoUni'],
                            'Iva' => 0,
                            'Total' => $element['Total']
                        );
                    }
                    if (count($Detalle2)>0)
                    {
                        $oMdatallefactura= new Mdatallefactura();
                        $oMdatallefactura->Insert($Detalle2);
                    }

                    $oMfactura = new Mfactura();
                    $oMfactura->IdServicio=$this ->post('IdServicio');
                    $oMfactura->FechaMod=date('Y-m-d H:i:s');
                    $oMfactura->Facturado=$this->post('Facturado');
                    $oMfactura->UpdateServicio();

                    $response = $oMfactura->get_factura();
                    $data['factura'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                }
                else
                {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
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
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public  function Autorizar_post() {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdFactura' => $this -> post('IdFactura')
        ]);

        $v -> rule('required', [
            'IdFactura'

        ]) -> message('El campo {field} es requerido.');


        if ($v -> validate()) {


        $Id = $this -> post('IdFactura');
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $oMfactura = new Mfactura();
        $oMfactura->IdFactura = $Id;
        $oMfactura->AFacturar ='SI';
        $oMfactura->FechaFacReal = '0000-00-00';
        $oMfactura->FechaMod= date('Y-m-d H:i:s');

            if ($oMfactura->Autorizar()) {
            $oMfactura->RegEstatus='A';
                    $response = $oMfactura->get_factura();
                    $data['factura'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);

            } else {
                return $this -> set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }
        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function Cancelar_post() {

        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdFactura' => $this -> post('IdFactura'),
            'Motivo' => $this -> post('Motivo'),
            'IdServicio' => $this -> post('IdServicio'),
        ]);

        $v -> rule('required', [
            'IdFactura',
            'Motivo',
            'IdServicio'

        ]) -> message('El campo {field} es requerido.');


        if ($v -> validate())
        {
            $Id = $this -> post('IdFactura');
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $oMfactura = new Mfactura();
            $oMfactura->IdFactura = $Id;
            $oMfactura->ComentarioCancel = $this ->post('Motivo');
            $oMfactura->Facturado='Cancelado';
            $oMfactura->FechaMod= date('Y-m-d H:i:s');

            if ($oMfactura->Cancelar())
            {
                $oMfactura = new Mfactura();
                $oMfactura->IdServicio=$this ->post('IdServicio');
                $oMfactura->FechaMod=date('Y-m-d H:i:s');
                $oMfactura->Facturado='NO';
                $oMfactura->UpdateServicio();

                $oMfactura->RegEstatus='A';
                $response = $oMfactura->get_factura();
                $data['factura'] = $response['data'];
                return $this -> set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);

            } else {
                return $this -> set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ChangeFactura_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdFactura' => $this->post('IdFactura'),
            //'Fecha_Facturacion' => $this->post('FechaFacReal'),
            'Dias_de_Credito' => $this->post('DiasCredito'),
            'Folio_Factura' => $this->post('FolioFactReal'),
            'RegEstatus' => $this->post('RegEstatus'),
            'Monto' => $this->post('Monto'),
            'Observacion'=> $this -> post('Observacion'),
        ]);

        $v -> rule('required', [
            'IdFactura',
            //'Fecha_Facturacion',
            'Folio_Factura',
            'RegEstatus',
            'Monto',
            'Dias_de_Credito',
            'Observacion',
        ])->message('El campo {field} es requerido.');


        //$v->rule('date', ['Fecha_Facturacion']) -> message('El campo {field} no es una fecha valida.');
        $v->rule('numeric', ['Dias_de_Credito']) -> message('El campo {field} es requerido.');

        if($v->validate())
        {
            $Id = $this -> post('IdFactura');
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

            $oMfactura = new Mfactura();
            $oMfactura->IdFactura = $Id;
            $oMfactura->Observacion = $this->post('Observacion');
            $oMfactura->FechaFacReal = dateformato($this->post('FechaFacReal'));
            $oMfactura->FolioFactReal = $this->post('FolioFactReal');
            $oMfactura->RegEstatus = $this->post('RegEstatus');
            //agredo monto para Cuentas por Cobrar.
            $oMfactura->Monto = $this->post('Monto');
            $oMfactura->ComentarioAnulada = $this->post('ComentarioAnulada');
            $oMfactura->FechaAnulado=dateformato($this->post('FechaAnulado'));
            $oMfactura->FechaMod = date('Y-m-d H:i:s');

            $oMfactura->DiasCredito = $this->post('DiasCredito');

            $route = CrearRutaEmpSuc($this->RutaPdf,$IdEmpresa,$IdSucursal);

            //echo $this->post('FilePrevious').'imagenprevia';
            $files = $this->uploadfile->savefile($route.'/', 'File',$this->post('FilePrevious'), '*', UploadFile::SINGLE);
            $oMfactura->ArchivoFactura = $files;

            if( $oMfactura->ChangeFactura()){

                $data['factura'] = $response['data'];
                return $this -> set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);


            }else{
                return $this -> set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller:: HTTP_BAD_REQUEST);

            }

        }
    }

    public function FacturaAnulada_post(){

        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdServicio' => $this->post('IdServicio'),

        ]);

        $v -> rule('required', [
            'IdServicio',

        ])->message('El campo {field} es requerido.');



        if($v->validate())
        {
            $Id = $this -> post('IdServicio');
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

            $oMfactura = new Mfactura();
            $oMfactura->IdServicio = $Id;

            $oMfactura->RegEstatus = $this->post('RegEstatus');
            //agredo monto para Cuentas por Cobrar.
            $oMfactura->ComentarioAnulada = $this->post('ComentarioAnulada');
            $oMfactura->FechaAnulado=dateformato($this->post('FechaAnulado'));
            $oMfactura->FechaMod = date('Y-m-d H:i:s');

            if($oMfactura->Anular()){
                $data['factura'] = $response['data'];
                return $this -> set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller:: HTTP_CREATED);
            }else{
                return $this -> set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos aaaaaa.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
    }

    public function FacturaLibre_post(){
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'IdCliente' => $this -> post('IdCliente'),
            'IdClienteS' => $this -> post('IdClienteS'),
            'NoContrato'   =>$this->post('NoContrato')
        ]);

        $v->rule('required', [
            'IdCliente',
            'IdClienteS',
            'NoContrato'
        ])->message('El campo Contrato es requerido.');

        if ($v -> validate())
        {
            $Id = $this -> post('IdFactura');
            $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $oMfactura = new Mfactura();
            $oMfactura->IdFactura = $Id;
            $oMfactura->IdSucursal =$IdSucursal;
            $oMfactura->IdServicio = $this->post('IdServicio');
            $oMfactura->IdCliente=$this->post('IdCliente');
            $oMfactura->IdClienteS=$this->post('IdClienteS');
            $oMfactura->IdContrato=$this->post('IdContrato');

            $oMfactura->FolioServ=$this->post('FolioServ');
            $oMfactura->NombreCliente=$this->post('NombreCliente');
            $oMfactura->Sucursal=$this->post('Sucursal');
            $oMfactura->Direccion=$this->post('Direccion');
            $oMfactura->Contacto=$this->post('Contacto');
            $oMfactura->Telefono=$this->post('Telefono');
            $oMfactura->DatosFact=$this->post('DatosFact');
            $oMfactura->NoContrato=$this->post('NoContrato');
            $oMfactura->Servicio=$this->post('Servicio');
            $oMfactura->ComentarioContrato=$this->post('ComentarioContrato');
            $oMfactura->SubTotal=$this->post('Total');
            $oMfactura->Iva=0;
            $oMfactura->Total=$this->post('Total');
            $oMfactura->Facturado=$this->post('Facturado');
            $oMfactura->RegEstatus='A';
            $oMfactura->FechaReg=date('Y-m-d');
            $oMfactura->AFacturar='NO';
            $oMfactura->FechaFacReal='0000-00-00';
            $oMfactura->FolioFactReal='';
            $oMfactura->FechaMod=date('Y-m-d H:i:s');
            $oMfactura->TipoFactura="2";

            $DetalleServ=$this->post('DetalleServ');
            $Detalle=$this->post('Detalle');
            $Detalle1 = array();
            $Detalle2 = array();

            if($oMfactura->IdFactura == 0)
            {
                $ofolio= new Mfolio();
                $ofolio->IdSucursal = $IdSucursal;
                $ofolio->Tipo = 'FACTURACION';
                $respFol = $ofolio->get_foliovalidacion();

                $FolioFactura = '';
                $IdFolio = 0;
                $numero = 0;

                if($respFol['status'])
                {
                    $numero = $respFol['data']->Numero+1;
                    $FolioFactura =  $respFol['data']->Serie.'-'.$numero;
                    $IdFolio = $respFol['data']->IdFolio;
                }

                $oMfactura->FolioFactura = $FolioFactura;
                $Id = $oMfactura->FacturaLibreSave();

                if($Id > 0)
                {
                    $ofolio = new Mfolio();
                    $ofolio->IdFolio = $IdFolio;
                    $ofolio->Numero = $numero;
                    $ofolio->updateFolio();

                    $Contador=0;

                    //detalle factura
                    foreach ($Detalle as $element)
                    {
                        $Detalle2[] = array(
                            'IdFactura' => $Id,
                            'Descripcion' => $element['Descripcion'],
                            'Cantidad' =>  $element['Cantidad'],
                            'CostoUni' => $element['CostoUni'],
                            'Iva' => 0,
                            'Total' => $element['Total']
                        );
                    }
                    if (count($Detalle2)>0)
                    {
                        $oMdatallefactura= new Mdatallefactura();
                        $oMdatallefactura->Insert($Detalle2);
                    }

                    $oMfactura = new Mfactura();
                    $oMfactura->IdServicio=$this ->post('IdServicio');
                    $oMfactura->FechaMod=date('Y-m-d H:i:s');
                    $oMfactura->Facturado=$this->post('Facturado');
                    $oMfactura->UpdateServicio();

                    $oMfactura->IdFactura= $Id;
                    $oMfactura->RegEstatus = 'A';
                    $response = $oMfactura->get_factura();
                    $data['factura'] = $response['data'];
                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller:: HTTP_CREATED);
                }
                else
                {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos. 1',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
            else
            {
                if ($oMfactura-> update())
                {
                    $IdFactura=$oMfactura->IdFactura;
                    //se inserta el detalle de la factura
                    $oMfac_serfol = new Mfac_serfol();
                    $oMfac_serfol->IdFactura=$IdFactura;
                    $oMfac_serfol->delete();
                    $Contador=0;


                    //detalle factura
                    $oMdatallefactura = new Mdatallefactura();
                    $oMdatallefactura->IdFactura=$IdFactura;
                    $oMdatallefactura->delete();

                    foreach ($Detalle as $element)
                    {
                        $Detalle2[] = array(
                            'IdFactura' => $IdFactura,
                            'Descripcion' => $element['Descripcion'],
                            'Cantidad' =>  $element['Cantidad'],
                            'CostoUni' => $element['CostoUni'],
                            'Iva' => 0,
                            'Total' => $element['Total']
                        );
                    }
                    if (count($Detalle2)>0)
                    {
                        $oMdatallefactura= new Mdatallefactura();
                        $oMdatallefactura->Insert($Detalle2);
                    }

                    $oMfactura = new Mfactura();
                    $oMfactura->IdServicio=$this ->post('IdServicio');
                    $oMfactura->FechaMod=date('Y-m-d H:i:s');
                    $oMfactura->Facturado=$this->post('Facturado');
                    $oMfactura->UpdateServicio();

					$oMfactura->RegEstatus = 'A';
                    $response = $oMfactura->get_factura();
                    $data['factura'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                }
                else
                {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
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
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

    }

    public function recoveryFactura_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $facturaxServ= new Mfactura();

        $Id = (int) $this->get('IdServicio');
        $IdFactura = (int) $this->get('IdFactura');


        if (empty($Id)) {
            return $this->set_response([
                'status' => false,
                'type' => 2,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $facturaxServ->IdServicio = $Id;
        }
        $response =   $facturaxServ->get_factura();
        if ($response['status']) {
            $data['facturaxserv'] = $response['data'];

            $factura_serfolio= new Mfactura();
            $factura_serfolio->IdServicio = $Id;
            $factura_folioResp = $factura_serfolio->get_factura_serfolio();

            $Servicio = new Mservicio();
            $Servicio->IdServicio = $Id;
            $Servicio->FechaMod = date("Y-m-d H:i:s");
            $ServicioUp = $Servicio->UpdateServAnulado();

            if ($response['status']) {
                // $factura_serfolioDelete= new Mfactura();
                $factura_serfolio->IdServicio = $Id;
                $factura_serfolio->IdFactura = $IdFactura;
                // $factura_serfolio = $factura_serfolioDelete->deleteFacturaxServ();

                if ($factura_serfolio->deleteFacturaxServ()) {
                    return $this->set_response([
                        'status' => true,
                        'message' => 'Se ha eliminado correctamente.',
                    ], REST_Controller::HTTP_ACCEPTED);
                }else{
                     return $this->set_response([
                    'status' => false,
                    'message' => 'Error al eliminar la información.',
                ], REST_Controller::HTTP_BAD_REQUEST);
                }



            }else{
                $this->set_response([
                    'status' => false,
                    'type' => 1,
                    'message' => 'No encontrado.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }


            return $this->set_response([
                'status' => true,
                'type' => 1,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                'status' => false,
                'type' => 1,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function facturasxEstatusPendiente_get(){
         // Valid Token
         if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->Facturado=$this->get('Facturado');
        $oMfactura->RegEstatus=$this->get('RegEstatus');
        $oMfactura->NombreCliente=$this->get('Nombre');

        // Paginación
        $rows =  $oMfactura->get_FacturaPendiente();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['Pendientes'] = $oMfactura->get_FacturaPendiente();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
        ], REST_Controller::HTTP_OK);
    }

    public function facturasxEstatusCancelada_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
           return $this->set_unauthorized_response();
       }

       $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
       $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
       $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

       $oMfactura = new Mfactura();
       $oMfactura->IdSucursal= $IdSucursal;
       $oMfactura->Facturado=$this->get('Facturado');
       $oMfactura->RegEstatus=$this->get('RegEstatus');
       $oMfactura->NombreCliente=$this->get('Nombre');

       // Paginación
       $rows =  $oMfactura->get_FacturaCancelada();
       $Entrada=10;
       if ($this->get('Entrada')!='')
       {
           $Entrada =$this->get('Entrada');
       }
       $oMfactura->Limit=$Entrada;
       $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

       $oMfactura->Tamano = $pager->PageSize;
       $oMfactura->Offset = $pager->Offset;

       $data['Pendientes'] = $oMfactura->get_FacturaPendiente();
       $data['Canceladas'] = $oMfactura->get_FacturaCancelada();
       $data['pagination']= $pager;

       return $this->set_response([
           'status' => true,
           'data' => $data,
           'message' => 'Success',
           'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
       ], REST_Controller::HTTP_OK);
   }

    public function facturasxEstatusAnulada_get(){
        // Valid Token
            if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->Facturado=$this->get('Facturado');
        $oMfactura->RegEstatus=$this->get('RegEstatus');
        $oMfactura->NombreCliente=$this->get('Nombre');

        // Paginación
        $rows =  $oMfactura->get_FacturaAnulada();
        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['Anuladas'] = $oMfactura->get_FacturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
            'RutaFileOrg' => base_url().$this->RutaPdf.'/'.$IdEmpresa.'/'.$IdSucursal.'/',
        ], REST_Controller::HTTP_OK);
    }

    public function facturaLibreAnulada_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->TipoFactura=$this->get('TipoFactura');
        $oMfactura->NombreCliente=$this->get('Nombre');

        // Paginaci�n
        $rows =  $oMfactura->get_facturaLibreAnulada();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['facturaLibreAnulada'] = $oMfactura->get_facturaLibreAnulada();
        //$data['facturaAnulada'] = $oMfactura->get_facturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function facturaLibreCancelada_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMfactura = new Mfactura();
        $oMfactura->IdSucursal= $IdSucursal;
        $oMfactura->TipoFactura=$this->get('TipoFactura');
        $oMfactura->NombreCliente=$this->get('Nombre');

        // Paginaci�n
        $rows =  $oMfactura->get_facturaLibreCancelada();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
        $oMfactura->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMfactura->Tamano = $pager->PageSize;
        $oMfactura->Offset = $pager->Offset;

        $data['facturaLibreCancelada'] = $oMfactura->get_facturaLibreCancelada();
        //$data['facturaAnulada'] = $oMfactura->get_facturaAnulada();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
?>
