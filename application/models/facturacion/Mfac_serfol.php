<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfac_serfol extends BaseModel
{
    // Properties
    public $IdFactura;
    public $IdServicio;
    public $Folio;
    public $Descripcion;
     
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdFactura = 0;
        $this->IdServicio = 0;
        $this->Folio = 0;
        $this->Descripcion = 0;
    }

    public function Insert($data)
    {
      
    
        $this->db->insert_batch('factura_serfolio', $data);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function Update()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->set('Folio', $this->Folio);
        $this->db->set('Descripcion', $this->Descripcion);
        
        $this->db->Update('factura_serfolio');
        
        return true;
      
    }
    public function delete()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->delete('factura_serfolio');
        
        return true;
       
    }
    public function get_factura()
    {
        $this->db->select('*');
        $this->db->from('factura_serfolio');
        if (!empty($this->IdFactura))
        {
        $this->db->where('IdFactura', $this->IdFactura);
        }
        if (!empty($this->IdServicio))
        {
        $this->db->where('IdServicio', $this->IdServicio);
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
        $this->db->from('factura_serfolio  ');
        $this->db->where('IdFactura', $this->IdFactura);    
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }


}
