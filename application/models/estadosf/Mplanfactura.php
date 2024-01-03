<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mplanfactura extends BaseModel
{
    // Properties
    public $IdPlanFactura;
    public $IdSucursal;
    public $Descripcion;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdPlanFactura = '';
        $this->IdSucursal = '';
        $this->Descripcion = '';
      
    }
      public function set_insert_planfactura()
    {   
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Descripcion', $this->Descripcion);
           
        $this->db->insert('planfactura');
        return 1;
    }
    
      public function update()
    {   
        $this->db->where('IdPlanFactura', $this->IdPlanFactura);
       $this->db->set('Descripcion', $this->Descripcion); 
       
        $this->db->update('planfactura');
        return 1;
    }

    
    public function get_planfactura()
    {
        $this->db->select('*');
        $this->db->from('planfactura');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        if (!empty($this->IdPlanFactura))
        {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
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
                 'data' => new Mplanfactura()
            ];
        }
    }
    
    public function get_list_planfactura()
    {
        $this->db->select('*');
        $this->db->from('planfactura');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->IdPlanFactura))
        {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        //echo $this->db->get_compiled_select();
    }
 
  
}