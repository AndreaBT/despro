<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmaterial extends BaseModel
{
    // Properties
    public $IdMaterial;
    public $NomMaterial;
    public $IdSucursal;
    public $RegEstatus;
    public $Precio;
    public $FechaMod;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdMaterial = 0;
        $this->NomMaterial= 0;
        $this->IdSucursal = '';
        $this->RegEstatus = '';
        $this->Precio = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
        $this->db->set('NomMaterial', $this->NomMaterial);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);   
        $this->db->set('Precio', $this->Precio);
        $this->db->set('FechaMod', $this->FechaMod);
  
        $this->db->insert('material');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdMaterial', $this->IdMaterial);
        $this->db->set('NomMaterial', $this->NomMaterial);
        $this->db->set('Precio', $this->Precio);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('material');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdMaterial', $this->IdMaterial);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('material');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('material');
        $this->db->where('IdMaterial', $this->IdMaterial);

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
        $this->db->from('material');
        
        $this->db->where('IdSucursal =', $this->IdSucursal);
        // Filters
        if (!empty($this->IdMaterial)) {
            $this->db->where('IdMaterial', $this->IdMaterial);
        }
         if (!empty($this->NomMaterial)) {
            $this->db->like('NomMaterial', $this->NomMaterial);
        }

        if (!empty($this->Precio)) {
            $this->db->like('Precio', $this->Precio);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->like('RegEstatus', $this->RegEstatus);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}