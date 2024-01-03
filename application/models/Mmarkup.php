<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmarkup extends BaseModel
{
    // Properties
    public $IdMarkUp;
    public $Monto_I;
    public $Monto_F;
    public $CostoM;
    public $RegEstatus;
    public $IdSucursal;
    public $FechaMod;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdMarkUp = 0;
        $this->Monto_I= 0;
        $this->Monto_F = '';
        $this->CostoM = '';
        $this->RegEstatus = '';
        $this->IdSucursal = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
        $this->db->set('Monto_I', $this->Monto_I);
        $this->db->set('Monto_F', $this->Monto_F);
        $this->db->set('CostoM', $this->CostoM);   
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('FechaMod', $this->FechaMod);
  
        $this->db->insert('markup');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdMarkUp', $this->IdMarkUp);
        
        $this->db->set('Monto_I', $this->Monto_I);
        $this->db->set('Monto_F', $this->Monto_F);
        $this->db->set('CostoM', $this->CostoM);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->update('markup');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdMarkUp', $this->IdMarkUp);
      
        $this->db->set('CostoM', 'B');
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->update('markup');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('markup');
        $this->db->where('IdMarkUp', $this->IdMarkUp);

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
        $this->db->from('markup');
        
        $this->db->where('IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdMarkUp)) {
            $this->db->where('IdMarkUp', $this->IdMarkUp);
        }

      
        if (!empty($this->RegEstatus)) {
            $this->db->like('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->CostoM)) {
            $this->db->like('CostoM', $this->CostoM);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
    public function get_exist()
    {
        $this->db->select('*');
        $this->db->from('markup');
        
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('RegEstatus','A');
        // Filters
        
        if ($this->Monto_F!='') {
            $where=' Monto_I<='.floatval($this->Monto_F).' and Monto_F>='.floatval($this->Monto_F);
            $this->db->where($where);
        }
        
        if (!empty($this->IdMarkUp)) {
            $this->db->where('IdMarkUp !=',$this->IdMarkUp);
        }


        //Pagination
        $this->set_pagination();
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return count($response->result());
    }
    
  
 
  
}