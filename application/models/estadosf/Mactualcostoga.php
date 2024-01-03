<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mactualcostoga extends BaseModel
{
    // Properties
    public $IdCostoGA;
    public $MontoMes;
    public $Anio;
    public $Mes;
    public $FechaCompleta;
    public $IdSucursal;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdCostoGA = 0;
        $this->MontoMes = 0;
        $this->Anio = '';
        $this->Mes='';
        $this->FechaCompleta='';
        $this->IdSucursal=0;
      
    }
     
    public function get_recobery_actualcostoga()
    {
        $this->db->select('*');
        $this->db->from('actualcostoga');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        
        $this->db->where('IdCostoGA', $this->IdCostoGA);
    
        $this->db->where('Mes='.$this->Mes);
    
        $this->db->where('Anio='.$this->Anio);
       

        //echo $this->IdSucursal.'-'.$this->IdCostoGA.'-'.$this->Anio;
        
        ##echo $this->db->get_compiled_select();
        //echo $result = $this->db->get_compiled_select();
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
                 'data' => new Mactualcostoga()
            ];
        } 
    } 

    public function get_costoGA()
    {
        $this->db->select('PrimerT, SegundoT, TercerT, CuartoT');
        $this->db->from('costoga' );
        
       $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
        
          
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
                 'data' => new Mactualcostoga()
            ];
        } 
    }
}
?>