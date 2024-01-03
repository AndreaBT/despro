<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicio_manode_obra extends BaseModel
{
    // Properties
    public $IdCotizacionServicio;
    public $categoria;
    public $costoMO;
    public $horaNormal;
    public $horaExtra;
    public $totalIndividual;
    public $RegEstatus;
    public $Burden;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCotizacionServicio = 0;
        $this->categoria= 0;
        $this->costoMO = '';
        $this->horaNormal = '';
        $this->horaExtra = '';
        $this->totalIndividual = '';
        $this->RegEstatus = '';
        $this->Burden = '';
    }

    public function insert()
    {
        
        $this->db->set('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->set('categoria', $this->categoria);
        $this->db->set('costoMO', $this->costoMO);
        $this->db->set('horaNormal', $this->horaNormal);   
        $this->db->set('horaExtra', $this->horaExtra);
        $this->db->set('totalIndividual', $this->totalIndividual);
        $this->db->set('RegEstatus','A');
        $this->db->set('Burden', $this->Burden);
  
        $this->db->insert('servicio_manode_obra');
        //print_r($this->db->last_query());    

        return $this->db->insert_id();
    }

    public function delete()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->delete('servicio_manode_obra');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('servicio_manode_obra');
        
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->where('RegEstatus', $this->RegEstatus);


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}