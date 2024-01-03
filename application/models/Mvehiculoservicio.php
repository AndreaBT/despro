<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mvehiculoservicio extends BaseModel
{
    // Properties
    public $IdServicio;
    public $FechaInicio;
    public $HoraInicio;
    public $HoraFin;
    public $IdVehiculo;


    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdServicio = 0;
        $this->FechaInicio= '';
        $this->HoraInicio= '';
        $this->HoraFin = '';
        $this->IdTrabajador = '';

       
    }

    public function insert()
    {
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('FechaInicio', $this->FechaInicio);
        $this->db->set('HoraInicio', $this->HoraInicio);
        $this->db->set('HoraFin', $this->HoraFin);   
        $this->db->set('IdVehiculo', $this->IdVehiculo);
    
        
        $this->db->insert('vehiculoservicio');
        return $this->db->insert_id();
    }

    public function delete()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->delete('vehiculoservicio');
        
        return true;
        
    }

    public function get_vehiculoservicio()
    {
          $this->db->select('v.*');
        $this->db->from('vehiculoservicio vs');
             $this->db->join('vehiculo v','vs.IdVehiculo=v.IdVehiculo','inner');
        $this->db->where('vs.IdServicio', $this->IdServicio);

        // Filters
        if (!empty($this->IdVehiculo)) {
            $this->db->where('vs.IdVehiculo', $this->IdVehiculo);
        }
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
                 'data' => new Mvehiculoservicio()
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('vehiculoservicio vs');
             $this->db->join('vehiculo v','vs.IdVehiculo=v.IdVehiculo','inner');
        $this->db->where('vs.IdServicio', $this->IdServicio);

        // Filters
        if (!empty($this->IdVehiculo)) {
            $this->db->where('vs.IdVehiculo', $this->IdVehiculo);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}