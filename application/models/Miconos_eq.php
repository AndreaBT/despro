<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Miconos_eq extends BaseModel
{
    // Properties
    public $IdIconoEq;
    public $Nombre;
    public $Imagen;
    public $RegEstatus;
    public $Tipo;
   
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdIconoEq = 0;
        $this->Nombre= 0;
        $this->Imagen = '';
        $this->RegEstatus = '';
        $this->Tipo = '';
    }

    public function insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Imagen', $this->Imagen);
        $this->db->set('RegEstatus', $this->RegEstatus);   
        $this->db->set('Tipo', $this->Tipo);
  
        $this->db->insert('iconos_eq');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdIconoEq', $this->IdIconoEq);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->update('iconos_eq');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdIconoEq', $this->IdIconoEq);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('iconos_eq');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_folio()
    {
        $this->db->select('*');
        $this->db->from('iconos_eq');
        $this->db->where('IdIconoEq', $this->IdIconoEq);

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
        $this->db->from('iconos_eq');
   
        // Filters
        if (!empty($this->IdIconoEq)) {
            $this->db->where('IdIconoEq !=', $this->IdIconoEq);
        }

      
        if (!empty($this->Tipo)) {
            $this->db->like('Tipo', $this->Tipo);
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