<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mequipocomentario extends BaseModel
{
    // Properties
    public $IdEquipo;
    public $IdServicio;
    public $Comentario;
    public $Comentario2;
    public $Fecha;
    public $Permitir;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdEquipo = 0;
        $this->IdServicio= 0;
        $this->Comentario= '';
        $this->Comentario2= '';
        $this->Fecha = '';
        $this->Permitir = '';
        
    }

    public function insert()
    {

        $this->db->set('IdEquipo', $this->IdEquipo);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('Comentario', $this->Comentario);   
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Permitir', $this->Permitir);
        $this->db->insert('equipocomentario');
        return true;
    }

    public function updateApp()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->set('Permitir', $this->Permitir);
        $this->db->set('Comentario', $this->Comentario);
        $this->db->update('equipocomentario');
        #echo $this->db->last_query();
        return true;
    }

    public function update()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->set('Permitir', $this->Permitir);
        $this->db->set('Comentario2', $this->Comentario2);
        $this->db->update('equipocomentario');
        #echo $this->db->last_query();
        return true;
    }

    public function delete()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->where('IdServicio', $this->IdServicio);

        $this->db->delete('equipocomentario');
        
        return true;
    }

    public function get_equipocomentario()
    {
        $this->db->select('*');
        $this->db->from('equipocomentario');
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->where('IdServicio', $this->IdServicio);

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
                'data' => new Mequipocomentario()
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('e.IdEquipo,e.Nequipo,e.Status,ec.Comentario,ec.Permitir,ec.Comentario2');
        $this->db->from('equipocomentario ec');
        $this->db->join('equipos e','ec.IdEquipo=e.IdEquipo','inner');
        $this->db->where('ec.IdServicio', $this->IdServicio);

        if (!empty($this->Permitir))
        {
            $this->db->where('ec.Permitir', $this->Permitir);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
   
  
}