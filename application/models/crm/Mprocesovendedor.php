<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mprocesovendedor extends BaseModel
{
    // Properties
    public $IdTrabajador=0;
    public $IdTipoProceso;
    
    public function __construct()
    {
        parent::__construct();

    }

    public function Insert()
    {
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('IdTipoProceso', $this->IdTipoProceso);
      
        $this->db->insert('procesovendedor');
        return true;
    }
  
    public function delete()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->delete('procesovendedor');
        return true;
        
    }

   
    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('crmproceso');
        $this->db->where('IdCrmProceso', $this->IdCrmProceso);

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
                'data' => new Mcrmproceso()
            ];
        }
    }
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('crmproceso');
        $this->db->where('RegEstatus ', 'A');
        $this->db->where('IdTipoProceso ', $this->IdTipoProceso);
        // Filters
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre ', $this->Nombre);
        }
        $this->db->order_by("Numero", "asc");
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }


}
