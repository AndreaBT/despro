<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mticket_seguimiento extends BaseModel
{
    // Properties
    public $IdTiket;
    public $IdCliente;
    public $IdClienteSucursal;
    public $IdUsuario;
    public $IdTrabajador;
    public $Comentario;
    public $Tipo;
    public $Fecha;
    public $Hora;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdTiket = 0;
        $this->IdCliente= 0;
        $this->IdUsuario= '';
        $this->IdTrabajador= '';
        $this->Comentario = '';
        $this->IdClienteSucursal = '';
        $this->Fecha = '';
        $this->Tipo = '';
        $this->Hora = '';
    }

    public function insert()
    {
        $this->db->set('IdTiket', $this->IdTiket);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteSucursal', $this->IdClienteSucursal);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('Comentario', $this->Comentario);      
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Hora', $this->Hora);
        $this->db->insert('ticket_seguimiento');
        return $this->db->insert_id();
    }



    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('ticket_seguimiento');
        $this->db->where('IdTiket', $this->IdTiket);

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
        $this->db->from('ticket_seguimiento');
        $this->db->where('IdTiket', $this->IdTiket);
        
        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

        if (!empty($this->IdClienteSucursal)) {
            $this->db->where('IdClienteSucursal', $this->IdClienteSucursal);
        }

        //Pagination
        $this->set_pagination();
        
        // echo $result = $this->db->get_compiled_select();
        
        $response = $this->db->get();
        return $response->result();
    }
  
}