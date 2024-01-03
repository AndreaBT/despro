<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cfactura extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mempresa');
        $this->load->model('Msucursal');
        $this->load->model('facturacion/Mfactura');
        $this->load->model('facturacion/Mfac_serfol');
        $this->load->model('facturacion/Mdatallefactura');

        setTimeZone($this->verification,$this->input);
    }

    public function factura_get()
    {
        $this->load->library('reports/Factura');

        $Id=$this->get('IdFactura');
		$IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        if($Id<=0){
            echo 'error';
            return false;
        }

        $Mfactura=new Mfactura();
        $Mfactura->IdFactura=$Id;
        $Mfactura->RegEstatus='A';
        $response=$Mfactura->get_factura();
		// print_r($response['data']);

        if($response['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']		= $IdEmpresa;
            $dataResp['IdSucursal']		= $IdSucursal;
            $dataResp['Titulo']			= 'PREFACTURA';
            $dataResp['FolioFactura']	= $response['data']->FolioFactura;

            // SERVICIOS
            $Mfac_serfol				= new Mfac_serfol();
            $Mfac_serfol->IdFactura		= $Id;
            $row						= $Mfac_serfol->get_list();

            // DETALLE FACTURA
            $datallefactura				= new Mdatallefactura();
            $datallefactura->IdFactura	= $Id;
            $row2						= $datallefactura->get_list();

            // CARGA DE DATOS
            $dataResp['factura']		= $response['data'];
            $dataResp['rowfacser']		= $row;
            $dataResp['rowdetfac']		= $row2;
            
            $pdf=new Factura("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            $pdf->SetMargins(5,20,5);
            $pdf->contenido();
            $pdf->Output();
        }
    }
}