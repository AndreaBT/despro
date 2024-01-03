<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicioactivo extends BaseModel
{
    // Properties
    public $IdTrabajador;
    public $Fecha;
    public $Estado;
    public $IdServicio;
  

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdTrabajador = 0;
        $this->Fecha = 0;
        $this->Estado = '';
        $this->IdServicio = '';
    }

    public function insert()
    {
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('lat', $this->Lat);
        $this->db->set('lng', $this->Lng);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->insert('ubicacion');

        return $this->db->insert_id();
    }
    
    public function set_updateservicioact()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->where('Fecha', $this->Fecha);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('Estado', $this->Estado);
        //$this->db->set('FechaMod', $this->FechaMod);  
        $this->db->update('servicioactivo');

        return true;
        
    }



    public function get_usuario()
    {
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo');
        $this->db->from('usuario');
        $this->db->where('IdUsuario', $this->IdUsuario);

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
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo');
        $this->db->from('usuario');
        
         $this->db->where('IdSucursal', $this->IdSucursal);


        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }


        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

        if (!empty($this->Estatus)) {
            $this->db->where('Estatus', $this->Estatus);
        }

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

   
}
