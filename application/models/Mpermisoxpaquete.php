<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpermisoxpaquete extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $IdSucursal;
    public $IdPerfil;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete = 0;
        $this->IdSucursal = 0;
        $this->IdPerfil = '';
    }
    
    public function insert()
    {

		$this->db->set('IdPerfil', $this->IdPerfil);
        $this->db->set('IdPaquete', $this->IdPaquete);
        $this->db->set('IdSucursal', $this->IdSucursal);

        
        $this->db->insert('permisoxpaquete');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdPaquete', $this->IdPaquete);
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('IdPerfil', $this->IdPerfil);
         
        $this->db->update('permisoxpaquete');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        //$this->db->where('IdPaquete', $this->IdPaquete);
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->IdPerfil)) {
            $this->db->where('IdPerfil', $this->IdPerfil);
        }
        $this->db->delete('permisoxpaquete');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function get_list()
    {
        $this->db->select('IdPaquete,IdPerfil');
        $this->db->from('permisoxpaquete');
        
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->IdPaquete)) {
            $this->db->where('IdPaquete', $this->IdPaquete);
        }
        if (!empty($this->IdPerfil)) {
            $this->db->where('IdPerfil', $this->IdPerfil);
        }
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
    public function get_exist()
    {
        $this->db->select('IdPaquete,IdPerfil');
        $this->db->from('permisoxpaquete');
        $this->db->where('IdPerfil', $this->IdPerfil);
        $this->db->where('IdSucursal', $this->IdSucursal);

		
        if($this->IdPaquete>0){
            $this->db->where('IdPaquete=', $this->IdPaquete);    
        }

        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}