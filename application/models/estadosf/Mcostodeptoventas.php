<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostodeptoventas extends BaseModel
{
    // Properties
    public $IdCostoDeptoVenta;
    public $Anio;
    public $IdSucursal;
    public $Descripcion;
    public $NumeroCuenta;
    public $AnioAnterior;
    public $PrimerT;
    public $SegundoT;
    public $TercerT;
    public $CuartoT;
    public $RegEstatus;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdCostoDeptoVenta = '';
        $this->Anio = '';
        $this->IdSucursal = '';
        $this->Descripcion='';
        $this->NumeroCuenta='';
        $this->AnioAnterior='';
        $this->PrimerT='';
        $this->SegundoT='';
        $this->TercerT='';
        $this->CuartoT='';
      
    }
     
    public function get_list_costodeptoventa()
    {
        $this->db->select('*');
        $this->db->from('costodeptoventa');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->IdCostoDeptoVenta)){
            $this->db->where('IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
        }
        if (!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listDeptoOperaFiltro()
    {
        $this->db->select('ao.*,cd.*');
        $this->db->from('costodeptoventa cd');
        $this->db->join('actualoperaciones ao','ao.IdCostoDeptoVenta=cd.IdCostoDeptoVenta','inner');
        
       $this->db->where('ao.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('ao.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('ao.Mes', $this->Mes);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SumaMontoMes()
    {
        $this->db->select('ao.*,sum(ao.MontoMes)as SumaMes,ao.MontoMes,cd.*');
        $this->db->from('actualoperaciones ao');
        $this->db->join('costodeptoventa cd','cd.IdCostoDeptoVenta=ao.IdCostoDeptoVenta','inner');
       
       $this->db->where('ao.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('ao.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('ao.Mes<=', $this->Mes);
        }

        $this->db->order_by('ao.Mes', "DESC");
        $this->db->group_by('ao.IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costodeptoVEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('costodeptoventa');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
       

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
                 'data' => new Mactualcostof()
            ];
        }
    }
}
?>