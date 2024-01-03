<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mactualoperaciones extends BaseModel
{
    // Properties
    public $IdCostoDeptoVenta;
    public $MontoMes;
    public $Anio;
    public $Mes;
    public $FechaCompleta;
    public $IdSucursal;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdCostoDeptoVenta = 0;
        $this->MontoMes = 0;
        $this->Anio = '';
        $this->Mes='';
        $this->FechaCompleta='';
        $this->IdSucursal=0;
      
    }
     
    
    public function get_recobery_actualoperaciones()
    {       

        $this->db->select('*');
        $this->db->from('actualoperaciones');
        $this->db->where('IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
        
        if (!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
        }
        if (!empty($this->Mes)){
            $this->db->where('Mes', $this->Mes);
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
                 'data' => new Mactualoperaciones()
            ];
        }
    }
}
?>