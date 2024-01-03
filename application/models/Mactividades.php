<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mactividades extends BaseModel
{
    // Properties
    public $IdActividad;
    public $Nombre;
    public $Fecha;
    public $Hora;
    public $FechaReg;
    public $FechaMod;
    public $RegEstatus;
    public $IdCliente;
    public $IdUsuario;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdActividad = 0;
        $this->Nombre = '';
        $this->Fecha = '';
        $this->Hora = '';
        $this->FechaReg = '';
        $this->FechaMod = '';
        $this->RegEstatus = '';
        $this->IdCliente=0;
        $this->IdUsuario=0;
        
    }

    public function Insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Hora', $this->Hora);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->insert('actividades');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdActividad', $this->IdActividad);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Hora', $this->Hora);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->Update('actividades');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $this->db->where('IdActividad', $this->IdActividad);
       
        $this->db->set('RegEstatus','B');
       
        $this->db->Update('actividades');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function get_actividad()
    {
        $this->db->select('*');
        $this->db->from('actividades');
        $this->db->where('IdActividad', $this->IdActividad);

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
        $this->db->from('actividades');
        $this->db->where('IdCliente', $this->IdCliente);

        // Filters
        if (!empty($this->IdUsuario)) {
            $this->db->where('IdUsuario', $this->IdUsuario);
        }

        if (!empty($this->Nombre)) {
            $this->db->like('Nombre ', $this->Nombre);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus ', $this->RegEstatus);
        }

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }


}
