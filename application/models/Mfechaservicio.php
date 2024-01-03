<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfechaservicio extends BaseModel
{
    // Properties
    public $IdServicio;
    public $FechaInicio;
    public $HoraInicio;
    public $HoraFin;
    public $IdTrabajador;
    public $EstadoServicio;
    public $Comentario;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdServicio = 0;
        $this->FechaInicio= '';
        $this->HoraInicio= '';
        $this->HoraFin = '';
        $this->IdTrabajador = '';
        $this->EstadoServicio = '';
        $this->Comentario = '';
       
    }

    public function insert()
    {
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('FechaInicio', $this->FechaInicio);
        $this->db->set('HoraInicio', $this->HoraInicio);
        $this->db->set('HoraFin', $this->HoraFin);   
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('EstadoServicio', $this->EstadoServicio);
        $this->db->set('Comentario', $this->Comentario);
        
        $this->db->insert('fechaservicio');
        return $this->db->insert_id();
    }
    
       public function delete()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->delete('fechaservicio');
       
       return true;
    }


    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('fechaservicio');
        $this->db->where('IdServicio', $this->IdServicio);
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
        $this->db->from('fechaservicio');
        $this->db->where('IdServicio', $this->IdServicio);

        // Filters
        if (!empty($this->IdTrabajador)) {
            $this->db->where('IdTrabajador', $this->IdTrabajador);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
      public function get_listtrabajador()
    {
        $this->db->select('fs.*, t.Nombre,t.Telefono,t.Foto2,t.Profesion,t.Foto2,t.Imagen');
        $this->db->from('fechaservicio fs');
        $this->db->join('trabajador t ',' fs.IdTrabajador=t.IdTrabajador ','inner');
        $this->db->where('IdServicio', $this->IdServicio);
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
  
 
  
}