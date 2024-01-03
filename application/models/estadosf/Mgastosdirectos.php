<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgastosdirectos extends BaseModel
{
    // Properties
    public $IdSucursal;
    public $IdEmpresa;
    public $Gasto;
    public $NumCuenta;
    public $FechaAnterior;
    public $MontoAnterior; 
    public $MontoAnual;
    public $UnoT;
    public $DosT;
    public $TresT;
    public $CuatroT;
    public $FechaAct;
    public $Tipo;
    public $IdGasto;
    public $Anio;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdSucursal = 0;
        $this->IdEmpresa = 0;
        $this->IdGasto = 0;
        $this->Gasto = '';
        $this->NumCuenta = '';
        $this->FechaAnterior ='';
        $this->MontoAnterior ='';
        $this->MontoAnual ='';
        $this->UnoT ='';
        $this->DosT ='';
        $this->TresT ='';
        $this->CuatroT ='';
        $this->FechaAct='';
        $this->Tipo='';
        $this->Anio='';
      
    }
     
    
    public function get_list_gastosdirectos()
    {
        $this->db->select('*');
        $this->db->from('gastosdirectos');
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if (!empty($this->Tipo))
        {
            $this->db->where('Tipo', $this->Tipo);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->IdGasto))
        {
            $this->db->where('IdGasto', $this->IdGasto);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listDeptoVentaFiltro()
    {
        $this->db->select('av.*,gd.*');
        $this->db->from('gastosdirectos gd');
        $this->db->join('actualventas av','av.IdGasto=gd.IdGasto','inner');
        
       $this->db->where('av.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('av.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('av.Mes', $this->Mes);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SumaMontoMes()
    {
        $this->db->select('av.IdGasto,av.Mes,av.Anio,sum(av.MontoMes)as SumaMes,av.MontoMes,gd.*');
        $this->db->from('actualventas av');
        $this->db->join('gastosdirectos gd','av.IdGasto=gd.IdGasto','inner');
        
       $this->db->where('av.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('av.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('av.Mes<=', $this->Mes);
        }

        $this->db->order_by('av.Mes', "DESC");
        $this->db->group_by('av.IdGasto', $this->IdGasto);
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costoventaEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(MontoAnterior) as AnioAnterior, sum(UnoT) as PrimerT, sum(DosT) as SegundoT,sum(TresT) as TercerT,sum(CuatroT)  as CuartoT');
        $this->db->from('gastosdirectos');
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