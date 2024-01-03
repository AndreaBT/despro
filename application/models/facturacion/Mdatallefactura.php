<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdatallefactura extends BaseModel
{
    // Properties
    public $IdFactura;
    public $Descripcion;
    public $Cantidad;
    public $CostoUni;
    public $Iva;
    public $Total;
 
     
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdFactura = 0;
        $this->Descripcion = 0;
        $this->Cantidad = 0;
        $this->CostoUni = 0;
        $this->Iva = 0;
        $this->Total = 0;
    }

    public function Insert($data)
    {
        $this->db->insert_batch('datallefactura', $data);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
   
    public function delete()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->delete('datallefactura');
        
        return true;
       
    }
    public function get_factura()
    {
        $this->db->select('*');
        $this->db->from('datallefactura');
        if (!empty($this->IdFactura))
        {
        $this->db->where('IdFactura', $this->IdFactura);
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
                'status' => false
            ];
        }
    }

    
     public function get_list()
    {
        $this->db->select('*');
        $this->db->from('datallefactura  ');
        $this->db->where('IdFactura', $this->IdFactura);    
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }


}
