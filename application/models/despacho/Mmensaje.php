<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmensaje extends BaseModel
{
    // Properties
    public $IdMensaje;
    public $IdUsuario;
    public $IdContacto;
    public $Fecha;
    public $RegEstatus;
    public $FechaMod;
   

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdMensaje = 0;
        $this->IdUsuario = '';
        $this->IdContacto = '';
        $this->Fecha = '';
        $this->RegEstatus = '';
        $this->FechaMod = '';
        
    }

    public function Insert()
    {
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('IdContacto', $this->IdContacto);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->insert('mensaje');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdMensaje', $this->IdMensaje);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('IdContacto', $this->IdContacto);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('mensaje');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $this->db->where('IdMensaje', $this->IdMensaje);
       
        $this->db->set('RegEstatus','B');
        $this->db->set('FechaMod', $this->FechaMod);
       
        $this->db->Update('mensaje');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function get_mensaje()
    {
        $this->db->select('*');
        $this->db->from('mensaje');
        if (!empty($this->IdMensaje))
        {
        $this->db->where('IdMensaje', $this->IdMensaje);
        }
        
        if (!empty($this->IdContacto))
        {
        $this->db->where('IdContacto', $this->IdContacto);
        }
         
         if (!empty($this->IdUsuario))
        {
        $this->db->where('IdUsuario', $this->IdUsuario);
        }
       # echo  $this->db->get_compiled_select();
        
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
        $this->db->from('mensaje');
        $this->db->where('IdMensaje', $this->IdMensaje);

        // Filters
        if (!empty($this->IdUsuario)) {
            $this->db->where('IdUsuario', $this->IdUsuario);
        }

        if (!empty($this->IdContacto)) {
            $this->db->like('IdContacto ', $this->IdContacto);
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
