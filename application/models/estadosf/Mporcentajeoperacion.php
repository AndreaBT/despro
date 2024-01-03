<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mporcentajeoperacion extends BaseModel
{
    // Properties
    public $IdPorcentajeOperacion;
    public $IdTipoSer;
    public $IdPlanFactura;
    public $Anio;
    public $IdSucursal;
    public $Descripcion;
  
    public $AnioAnterior;
    public $PorcentajeAnterior;
    public $PorcentajeAnual;
    public $PrimerT;
    public $SegundoT;
    public $TercerT;
    public $CuartoT;
    public $RegEstatus;
    public $IdSubtipoServ;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdPorcentajeOperacion = '0';
        $this->IdTipoSer = '0';
        $this->IdPlanFactura = '0';
        $this->Anio = '';
        $this->IdSucursal = '0';
        $this->Descripcion='';
        $this->AnioAnterior='0';
        
        $this->PorcentajeAnterior='0';
        $this->PorcentajeAnual='0';
        
        $this->PrimerT='0';
        $this->SegundoT='0';
        $this->TercerT='0';
        $this->CuartoT='0';
         $this->IdSubtipoServ=0;
      
    }

    
    public function get_recobery_porcentajeoperacionEstadoFinanciero2()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('porcentajeoperacion');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->IdSubtipoServ))
        {
            $this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
        }
          
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
 
        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else { 
            return [
                'status' => false,
                 'data' => new Mporcentajeoperacion()
            ];
        }
    }

    public function get_recobery_porcentajeoperacionEstadoFinanciero()
    {
    
        $this->db->select('po.*');
        $this->db->from('porcentajeoperacion po');
        $this->db->where('po.IdSucursal', $this->IdSucursal);

        if ($this->IdPlanFactura > 0) {
            $and1 = ' po.IdPlanFactura=' . $this->IdPlanFactura . '';
           $this->db->where($and1);
        }

        if ($this->IdPorcentajeOperacion > 0) {
            $and2 = ' po.IdPorcentajeOperacion=' . $this->IdPorcentajeOperacion . '';
           $this->db->where($and2);
        }

        if ($this->IdTipoSer > 0) {
            $and3 = ' po.IdTipoSer=' . $this->IdTipoSer . '';
           $this->db->where($and3);
        }

        if ($this->IdSubtipoServ > 0) {
            $and4 = ' po.IdSubtipoServ=' . $this->IdSubtipoServ . '';
           $this->db->where($and4);
        }

        if ($this->Anio != "") {
            $and5 = ' po.Anio=' . $this->Anio . '';
           $this->db->where($and5);
        }
          
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
 
        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else { 
            return [
                'status' => false,
                 'data' => new Mporcentajeoperacion()
            ];
        }
    }

    public function get_recobery_porcentajeoperacionEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as SPrimerT, sum(SegundoT) as SSegundoT,sum(TercerT) as STercerT,sum(CuartoT)  as SCuartoT,PrimerT,SegundoT, TercerT,CuartoT');
        $this->db->from('porcentajeoperacion');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', 'Facturacion');
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        
          
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
 
        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else { 
            return [
                'status' => false,
                 'data' => new Mporcentajeoperacion()
            ];
        }
    }

    


    
 
  
}