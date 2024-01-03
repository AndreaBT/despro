<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdetalleestadofinanciero extends BaseModel
{
    // Properties
    public $IdEstadoF;
    public $Pasado;  
    public $PorcentajePasado;
    public $IdPlanFactura;


    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdEstadoF = '';
        $this->Pasado = 0;
        $this->PorcentajePasado = 0;
        $this->IdPlanFactura = '';
    }

    
    public function set_insert_detalleestadofinanciero()
    {
        $this->db->set('IdEstadoF', $this->IdEstadoF);  
        $this->db->set('Pasado', $this->Pasado);
        $this->db->set('PorcentajePasado', $this->PorcentajePasado); 
        $this->db->set('IdPlanFactura', $this->IdPlanFactura);
        $this->db->insert('detalleestadofinanciero');
        return $this->db->insert_id();
	}

    public function set_delete_detalleestadofinanciero()
    {
        $this->db->where('IdEstadoF', $this->IdEstadoF);
        $this->db->delete('detalleestadofinanciero');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
    
}
