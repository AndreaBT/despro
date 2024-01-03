<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdetallemensaje extends BaseModel
{
    // Properties
    public $IdMensaje;
    public $IdUsuario;
    public $Fecha;
    public $Mensaje;
    public $Estatus;
    public $Hora;
    public $IdContacto;
   

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdMensaje = 0;
        $this->IdUsuario = '';
        $this->Fecha = '';
        $this->Mensaje = '';
        $this->Estatus = '';
        $this->Hora = '';
        $this->IdContacto = 0;
        
    }

    public function Insert()
    {
        $this->db->set('IdMensaje', $this->IdMensaje);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Mensaje', $this->Mensaje);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->set('Hora', $this->Hora);
        $this->db->insert('detallemensaje');
        return $this->db->insert_id();
    }

    public function Update(){

        $this->db->where('IdMensaje', $this->IdMensaje);
        $this->db->set('Estatus', 'Leido');
        $this->db->Update('detallemensaje');
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
  
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('detallemensaje');
        $this->db->where('IdMensaje', $this->IdMensaje);

        // Filters
        if (!empty($this->IdUsuario)) {
            $this->db->where('IdUsuario', $this->IdUsuario);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_listMensajeUser()
    {
        $this->db->select('m.*,d.*,CONCAT(u.Nombre," ",u.Apellido) AS Contacto, u.Foto2');
        $this->db->from('mensaje m');
        $this->db->join('detallemensaje d','m.IdMensaje=d.IdMensaje','inner');
        $this->db->join('usuario u','m.IdContacto=u.IdUsuario','inner');


        $this->db->where('d.Estatus', 'No leido');
        // Filters
        if (!empty($this->IdMensaje)) {
            $this->db->where('m.IdMensaje', $this->IdMensaje);
        }
        if (!empty($this->IdUsuario)) {
            $this->db->where('m.IdUsuario', $this->IdUsuario);
        }

        $this->db->order_by('m.IdMensaje','DESC');


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function countmessage(){

        $this->db->select('Count(m.IdMensaje) as messages');
        $this->db->from('mensaje m');
        $this->db->join('detallemensaje d','m.IdMensaje=d.IdMensaje','inner');
        $this->db->join('usuario u','m.IdContacto=u.IdUsuario','inner');


        $this->db->where('d.Estatus', 'No leido');
        // Filters
        if (!empty($this->IdMensaje)) {
            $this->db->where('m.IdMensaje', $this->IdMensaje);
        }
        if (!empty($this->IdContacto)) {
            $this->db->where('m.IdContacto', $this->IdContacto);
        }

        $this->db->order_by('m.IdMensaje','DESC');

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
                 'data' => new Mdetallemensaje()
            ];
        }
    }
    
}