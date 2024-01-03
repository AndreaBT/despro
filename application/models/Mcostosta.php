<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostosta extends BaseModel
{
    // Properties
    public $IdCostosTA;
    public $Concepto;
    public $Costo;
    public $RegEstatus;
    public $IdSucursal;
    public $FechaMod;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCostosTA = 0;
        $this->Concepto= 0;
        $this->Costo = '';
        $this->RegEstatus = '';
        $this->IdSucursal = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
        $this->db->set('Concepto', $this->Concepto);
        $this->db->set('Costo', $this->Costo);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('FechaMod', $this->FechaMod);
  
        $this->db->insert('costosta');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCostosTA', $this->IdCostosTA);
        
        $this->db->set('Costo', $this->Costo);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->update('costosta');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdCostosTA', $this->IdCostosTA);
      
        $this->db->set('CostoKM', 'B');
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->update('costosta');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('costosta');
        $this->db->where('IdCostosTA', $this->IdCostosTA);

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
    
    public function get_recovery_burden()
    {
        $this->db->select('*');
        $this->db->from('costosta');
        $this->db->where('Concepto','BURDEN RATE');
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
                'status' => false
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('costosta');
        
        $this->db->where('IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdCostosTA)) {
            $this->db->where('IdCostosTA', $this->IdCostosTA);
        }

      
        if (!empty($this->RegEstatus)) {
            $this->db->like('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->Costo)) {
            $this->db->like('Costo', $this->Costo);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
}