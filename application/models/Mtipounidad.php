<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtipounidad extends BaseModel
{
    // Properties
    public $IdTipoU;
    public $Nombre;
    public $IdSucursal;
    public $RegEstatus;
    public $IdIconoEq;
 public $FechaMod;
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdTipoU = 0;
        $this->Nombre= '';
        $this->IdSucursal= 0;
        $this->RegEstatus = '';
        $this->IdIconoEq= '';  
        $this->FechaMod= '';   
    }

    public function insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdIconoEq', $this->IdIconoEq);   
       
        $this->db->insert('tipounidad');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdTipoU', $this->IdTipoU);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdIconoEq', $this->IdIconoEq);   
        $this->db->set('FechaMod', $this->FechaMod);   
        $this->db->update('tipounidad');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdTipoU', $this->IdTipoU);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('tipounidad');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_tipounidad()
    {
        $this->db->select('tu.*,ie.Imagen');
        $this->db->from('tipounidad tu');
        $this->db->where('IdTipoU', $this->IdTipoU);
        $this->db->join('iconos_eq ie','tu.IdIconoEq=ie.IdIconoEq','inner');

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
        $this->db->select('tu.*,ie.Imagen');
        $this->db->from('tipounidad tu');
        $this->db->join('iconos_eq ie','tu.IdIconoEq=ie.IdIconoEq','inner');
        
        $this->db->where('tu.IdSucursal', $this->IdSucursal);

        // Filters
        if (!empty($this->IdTipoU )) {
            $this->db->where('tu.IdTipoU  !=', $this->IdTipoU );
        }

        if (!empty($this->Nombre)) {
            $this->db->like('tu.Nombre', $this->Nombre);
        }
        if (!empty($this->RegEstatus)) {
            $this->db->where('tu.RegEstatus', $this->RegEstatus);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}