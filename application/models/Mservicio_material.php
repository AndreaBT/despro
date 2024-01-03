<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicio_material extends BaseModel
{
    // Properties
    public $IdCotizacionServicio;
    public $IdMaterial;
    public $cantidad;
    public $costoUnitario;
    public $totalIndividual;
    public $RegEstatus;
    public $NombreMat;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCotizacionServicio = 0;
        $this->IdMaterial= 0;
        $this->cantidad = '';
        $this->costoUnitario = '';
        $this->totalIndividual = '';
        $this->RegEstatus = '';
        $this->NombreMat = '';
    }

    public function insert()
    {
        
        $this->db->set('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->set('IdMaterial', $this->IdMaterial);
        $this->db->set('cantidad', $this->cantidad);
        $this->db->set('costoUnitario', $this->costoUnitario);   
        $this->db->set('totalIndividual', $this->totalIndividual);
        $this->db->set('RegEstatus','A');
        $this->db->set('NombreMat', $this->NombreMat);
  
        $this->db->insert('servicio_material');
        //print_r($this->db->last_query());    

        return $this->db->insert_id();
    }

    public function delete()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->delete('servicio_material');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('servicio_material');
        
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}