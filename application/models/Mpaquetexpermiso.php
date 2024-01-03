<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpaquetexpermiso extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $IdPermiso;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete = 0;
        $this->IdPermiso = '';
    }
    public function insert()
    {
        $this->db->set('IdPaquete', $this->IdPaquete);
        $this->db->set('IdPermiso', $this->IdPermiso);
        
        $this->db->insert('paquetexpermiso');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdPaquete', $this->IdPaquete);
        $this->db->where('IdPermiso', $this->IdPermiso);
         
        $this->db->update('paquetexpermiso');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdPaquete', $this->IdPaquete);
        if (!empty($this->IdPermiso)) {
            $this->db->where('IdPermiso', $this->IdPermiso);
        }
        $this->db->delete('paquetexpermiso');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function get_list()
    {
        $this->db->select('IdPermiso,IdPaquete');
        $this->db->from('paquetexpermiso');
        
        if (!empty($this->IdPaquete)) {
            $this->db->where('IdPaquete', $this->IdPaquete);
        }
        if (!empty($this->IdPermiso)) {
            $this->db->where('IdPermiso', $this->IdPermiso);
        }
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
    public function get_exist()
    {
        $this->db->select('IdPermiso,IdPaquete');
        $this->db->from('paquetexpermiso');
        $this->db->where('IdPermiso', $this->IdPermiso);
		
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

    public function get_listPermisosxPaquete()
    {
        $this->db->select('mp.IdPermiso,mp.IdPaquete,p.Nombre,p.Clave');
        $this->db->from('paquetexpermiso mp');
        $this->db->join('permiso p','p.IdPermiso=mp.IdPermiso','inner');
        
        if (!empty($this->IdPaquete)) {
            $this->db->where('mp.IdPaquete', $this->IdPaquete);
        }
        if (!empty($this->IdPermiso)) {
            $this->db->where('mp.IdPermiso', $this->IdPermiso);
        }
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

}