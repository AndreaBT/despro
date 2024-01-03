<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpaquetexsucursal extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $IdSucursal;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete= 0;
        $this->IdSucursal= 0;
    


    }
    public function insert()
    {
        $this->db->set('IdPaquete', $this->IdPaquete);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->insert('paquetexsucursal');
            return true;
        
    }

    public function update()
    {
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->set('IdPaquete', $this->IdPaquete);
        $this->db->update('paquetexsucursal');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

  
    public function delete()
    {
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->delete('paquetexsucursal');
        return true;
        
    }
    
     public function get_paqutexsucursal()
    {
        $this->db->select('*');
        $this->db->from('paquetexsucursal');
        $this->db->where('IdPaquete', $this->IdPaquete);
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        
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
                'data' => ''
            ];
        }
    }
    
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('paquetexsucursal');
        $this->db->where('IdSucursal', $this->IdSucursal);
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
    

  
  
}