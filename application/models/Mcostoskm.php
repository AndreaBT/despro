<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostoskm extends BaseModel
{
    // Properties
    public $IdCostosKM;
    public $KMinicial;
    public $KMfinal;
    public $CostoKM;
    public $RegEstatus;
    public $IdSucursal;
    public $FechaMod;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCostosKM = 0;
        $this->KMinicial= 0;
        $this->KMfinal = '';
        $this->CostoKM = '';
        $this->RegEstatus = '';
        $this->IdSucursal = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
        $this->db->set('KMinicial', $this->KMinicial);
        $this->db->set('KMfinal', $this->KMfinal);
        $this->db->set('CostoKM', $this->CostoKM);   
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('FechaMod', $this->FechaMod);
  
        $this->db->insert('costoskm');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCostosKM', $this->IdCostosKM);
        
        $this->db->set('KMinicial', $this->KMinicial);
        $this->db->set('KMfinal', $this->KMfinal);
        $this->db->set('CostoKM', $this->CostoKM);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->update('costoskm');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdCostosKM', $this->IdCostosKM);
      
        $this->db->set('CostoKM', 'B');
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->update('costoskm');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('costoskm');
        $this->db->where('IdCostosKM', $this->IdCostosKM);

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
        $this->db->from('costoskm');
        
        $this->db->where('IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdCostosKM)) {
            $this->db->where('IdCostosKM', $this->IdCostosKM);
        }

      
        if (!empty($this->RegEstatus)) {
            $this->db->like('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->CostoKM)) {
            $this->db->like('CostoKM', $this->CostoKM);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
    public function get_exist()
    {
        $this->db->select('*');
        $this->db->from('costoskm');
        
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('RegEstatus','A');
        // Filters
        if ($this->KMfinal!='') {
            $where=' KMinicial<='.$this->KMfinal.' and KMfinal>='.$this->KMfinal;
            $this->db->where($where);
        }
        
        if (!empty($this->IdCostosKM)) {
            $this->db->where('IdCostosKM !=',$this->IdCostosKM);
        }


        //Pagination
        $this->set_pagination();
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return count($response->result());
    }
    
  
 
  
}