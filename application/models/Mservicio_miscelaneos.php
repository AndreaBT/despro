<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicio_miscelaneos extends BaseModel
{
    // Properties
    public $IdCotizacionServicio;
    public $concepto;
    public $cantidad;
    public $valor;
    public $RegEstatus;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCotizacionServicio = 0;
        $this->concepto= 0;
        $this->cantidad = '';
        $this->valor = '';
        $this->RegEstatus = '';
    }

    public function insert()
    {
        
        $this->db->set('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->set('concepto', $this->concepto);
        $this->db->set('cantidad', $this->cantidad);
        $this->db->set('valor', $this->valor);   
        $this->db->set('RegEstatus','A');
  
        $this->db->insert('servicio_miscelaneos');
        //print_r($this->db->last_query());    

        return $this->db->insert_id();
    }

    public function delete()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->delete('servicio_miscelaneos');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('servicio_miscelaneos');
        
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}