<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostoga extends BaseModel
{
    // Properties
    public $IdCostoGA;
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
        $this->IdCostoGA = '';
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
     
    
    public function get_list_costoga()
    {
        $this->db->select('*');
        $this->db->from('costoga');
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if (!empty($this->IdCostoGA))
        {
            $this->db->where('IdCostoGA', $this->IdCostoGA);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listNuevo()
    {
        $this->db->select('ac.*,cg.*');
        $this->db->from('actualcostoga ac');
        $this->db->join('costoga cg','ac.IdCostoGA=cg.IdCostoGA','inner');
        
       $this->db->where('ac.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('ac.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('ac.Mes', $this->Mes);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SumaMontoMes()
    {
        $this->db->select('ac.*,sum(ac.MontoMes) as SumaMes,ac.MontoMes,cg.*');
        $this->db->from('actualcostoga ac');
        $this->db->join('costoga cg','ac.IdCostoGA=cg.IdCostoGA','inner');
        
       $this->db->where('ac.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('ac.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('ac.Mes<=', $this->Mes);
        }

        $this->db->order_by('ac.Mes', "DESC");
        $this->db->group_by('ac.IdCostoGA', $this->IdCostoGA);
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costoGAEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('costoga');
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