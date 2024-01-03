<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfirmas extends BaseModel
{
    // Properties
    public $IdServicio = '';
    public $IdCliente = '';
    public $IdClienteS = '';
    public $Nombre = '';
    public $Firma2 = '';

    public function __construct()
    {
        parent::__construct();

        // Init Properties

    }

    public function insert()
    {
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('Firma', '');
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Firma2', $this->Firma2);

        $this->db->insert('firmas');
        return $this->db->insert_id();
    }

    public function get_recovery()
    {
        $this->db->select('IdServicio,IdCliente,IdClienteS,Nombre,Firma2,Firma');
        $this->db->from('firmas');
        $this->db->where('IdServicio', $this->IdServicio);

        //echo $result = $this->db->get_compiled_select();
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
                'data' => new Mfirmas()
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('firmas ');
        $this->db->where('IdServicio', $this->IdServicio);

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
}
