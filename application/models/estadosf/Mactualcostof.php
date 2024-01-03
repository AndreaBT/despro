<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mactualcostof extends BaseModel
{
    // Properties
    public $IdCostoFinanciero;
    public $MontoMes;
    public $Anio;
    public $Mes;
    public $Mes2;
    public $FechaCompleta;
    public $IdSucursal;
    public $Tipo;
    public $Type='';
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdCostoFinanciero = 0;
        $this->MontoMes = 0;
        $this->Anio = '';
        $this->Mes='';
        $this->Mes2='';
        $this->FechaCompleta='';
        $this->IdSucursal=0;
        $this->Tipo='';
      
    }
     
    
    public function get_recobery_actualcostofrptgral2()
    {       
        
        $this->db->select('sum(ac.MontoMes) as MontoMes');
        $this->db->from('actualcostof ac');
        $this->db->join('costofinanciero cf ',' ac.IdCostoFinanciero=cf.IdCostoFinanciero','inner');
        
        $this->db->where('cf.Descripcion!=', 'Almacén'); 

        $this->db->where('cf.IdSucursal', $this->IdSucursal); 

        if ($this->Tipo==1)
        {
            if (!empty($this->Mes)){
                $this->db->where('ac.Mes ='. $this->Mes);
            }
        }
        else
        {
            if (!empty($this->Mes)){
                $this->db->where('ac.Mes <='.$this->Mes);
            }
        }
        if (!empty($this->Anio)){
            $this->db->where('ac.Anio ='. $this->Anio);
        }
        if (!empty($this->Type)){
            $this->db->where('cf.Tipo', $this->Type);
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

    public function get_listCostosFinanFiltro()
    {
        $this->db->select('acf.*,cf.*');
        $this->db->from('costofinanciero cf');
        $this->db->join('actualcostof acf','acf.IdCostoFinanciero=cf.IdCostoFinanciero','inner');
        
       $this->db->where('acf.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('acf.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('acf.Mes', $this->Mes);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SumaMontoMes()
    {
        $this->db->select('acf.IdCostoFinanciero,acf.Mes,acf.Anio,sum(MontoMes)as SumaMes,acf.MontoMes,cf.*');
        $this->db->from('actualcostof acf');
        $this->db->join('costofinanciero cf','acf.IdCostoFinanciero=cf.IdCostoFinanciero','inner');
        
       $this->db->where('acf.IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('acf.Anio', $this->Anio);
        }
        if (!empty($this->Mes))
        {
            $this->db->where('acf.Mes<=', $this->Mes);
        }

        $this->db->order_by('acf.Mes', "DESC");
        $this->db->group_by('acf.IdCostoFinanciero', $this->IdCostoFinanciero);
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costofinanEstadoFinancieroFiltro()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('costofinanciero');
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