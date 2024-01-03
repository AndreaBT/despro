<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mequipamiento extends BaseModel
{
    // Properties
    public $IdEquipamiento;
    public $Nombre;
    public $RegEstatus;
    public $FechaMod;
   
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdEquipamiento= 0;
        $this->Nombre = '';
        $this->RegEstatus = '';
        $this->FechaMod='';
    }

    public function insert()
    {
    
        $this->db->set('Nombre', $this->Nombre);  
        $this->db->set('RegEstatus', 'A');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->insert('equipamiento');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdEquipamiento', $this->IdEquipamiento);
        $this->db->set('Nombre', $this->Nombre);  
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('equipamiento');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function delete()
    {
        $this->db->where('IdEquipamiento', $this->IdEquipamiento);
        $this->db->set('RegEstatus', 'B');        
        $this->db->update('equipamiento');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    
    public function get_equipamiento()
    {
        $this->db->select('*');
        $this->db->from('equipamiento');
        $this->db->where('IdEquipamiento', $this->IdEquipamiento);
        $this->db->where('RegEstatus','A');
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
                'data' => ''
            ];
        }
    }
    
    
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('equipamiento'); 
        $this->db->where('RegEstatus', 'A');
        
        if (!empty($this->Nombre))
        {
            $this->db->like('Nombre', $this->Nombre);    
        }
       
        $this->db->order_by("IdEquipamiento", "desc");
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
}