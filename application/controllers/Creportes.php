<?php


class CPdf extends CI_Controller{

    private $ruta = 'assets/uploads/anomalias';
    public $rutalogo = 'assets/uploads/Compania/';

    public function __construct(){
        parent::__construct();
        //$this->load->model('Mcotizacionserviciocalibracion');
    }
    
    public function Pedido_get(){
        $this->load->library('Pedido');
        //$this->load->library('phpqrcode/qrlib');
        
        $pdf=new PDF("P",'mm','Letter');
        $pdf->AliasNbPages();
        //$pdf->set_objetos($dataResp);
        $pdf->contenido();
      
        $pdf->Output();
    }
}