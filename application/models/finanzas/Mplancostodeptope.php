<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mplancostodeptope extends BaseModel
{
    
    // Properties
    public $IdPlanCostoDeptOpe='';
    public $IdSucursal='';
    public $Anio=''; 
    public $NumTrabajadores=0;
    public $SemanasPro=0;
    public $HorasSemanalesPro=0;
    public $BurdenRate=0; 
    public $BurdenRateCorregido=0;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
      
    }
      public function insert()
    {   
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Anio', $this->Anio);
        $this->db->set('NumTrabajadores', $this->NumTrabajadores);   
        $this->db->set('SemanasPro', $this->SemanasPro); 
        $this->db->set('HorasSemanalesPro', $this->HorasSemanalesPro);   
        $this->db->set('BurdenRate', $this->BurdenRate);   
        $this->db->set('BurdenRateCorregido', $this->BurdenRateCorregido);
        $this->db->insert('plancostodeptope');
        return 1;
    }
    
      public function update()
    {   
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('Anio', $this->Anio);
        $this->db->set('NumTrabajadores', $this->NumTrabajadores);   
        $this->db->set('SemanasPro', $this->SemanasPro); 
        $this->db->set('HorasSemanalesPro', $this->HorasSemanalesPro);   
        $this->db->set('BurdenRate', $this->BurdenRate);   
        $this->db->set('BurdenRateCorregido', $this->BurdenRateCorregido);  
       
        $this->db->update('plancostodeptope');
        return 1;
    }

    
      public function get_plancostodepto()
    {
        $this->db->select('*');
        $this->db->from('plancostodeptope');
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
            $obj=new Mplancostodeptope(); 
            return [
                'status' => false,
                 'data' => new Mplancostodeptope()
            ];
        }
    }
    
     public function get_list()
    {
        $this->db->select('*');
        $this->db->from('plancostodeptope');
       $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
 
  
}