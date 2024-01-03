<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostosvarios extends BaseModel
{
    // Properties
    public $IdCostosV;
    public $Concepto;
    public $IdSucursal;
    public $RegEstatus;
    public $FechaMod;
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCostosV = 0;
        $this->IdSucursal= 0;
        $this->Concepto = '';
        $this->RegEstatus = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Concepto', $this->Concepto);   
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaMod', $this->FechaMod);
  
        $this->db->insert('costosvarios');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCostosV', $this->IdCostosV);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Concepto', $this->Concepto);   

        $this->db->update('costosvarios');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdCostosV', $this->IdCostosV);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('costosvarios');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('costosvarios');
        $this->db->where('IdCostosV', $this->IdCostosV);

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
        $this->db->from('costosvarios');
        $this->db->where('IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdCostosV)) {
            $this->db->where('IdCostosV !=', $this->IdCostosV);
        }

        if (!empty($this->Concepto)) {
            $this->db->like('Concepto', $this->Concepto);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}