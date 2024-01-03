<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostovehope extends BaseModel
{
    // Properties
    public $IdCostoVehOpe;
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
        $this->IdCostoVehOpe = '';
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

    
     public function get_list_costovehope()
    {
        $this->db->select('*');
        $this->db->from('costovehope');
       $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->IdCostoVehOpe))
        {
            $this->db->where('IdCostoVehOpe', $this->IdCostoVehOpe);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listCostoVehiOperFiltro()
    {
        $this->db->select('cvo.*,acv.*');
        $this->db->from('costovehope cvo');
        $this->db->join('actualcostove acv','acv.IdCostoVehOpe=cvo.IdCostoVehOpe','inner');
        
       $this->db->where('acv.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('acv.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('acv.Mes', $this->Mes);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SumaMontoMes()
    {
        $this->db->select('acv.IdCostoVehOpe,acv.Mes,acv.Anio,sum(MontoMes)as SumaMes,acv.MontoMes,cvo.*');
        $this->db->from('actualcostove acv');
        $this->db->join('costovehope cvo','acv.IdCostoVehOpe=cvo.IdCostoVehOpe','inner');
        
       $this->db->where('acv.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('acv.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('acv.Mes<=', $this->Mes);
        }

        $this->db->order_by('acv.Mes', "DESC");
        $this->db->group_by('acv.IdCostoVehOpe', $this->IdCostoVehOpe);
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costovehiEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('costovehope');
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