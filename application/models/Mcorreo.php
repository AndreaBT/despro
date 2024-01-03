<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcorreo extends BaseModel
{
    // Properties
    public $IdCorreo;
    public $Titulo;
    public $Leyenda;
    public $Pie;
    public $Establecido;
    public $FechaMod;

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCorreo = 0;
        $this->Titulo= '';
        $this->Leyenda = '';
        $this->Pie = '';
        $this->Establecido = '';
        $this->FechaMod = '';
        
    }

    public function insert()
    {
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('Leyenda', $this->Leyenda);
        $this->db->set('Pie',$this->Pie);
        $this->db->set('FechaMod',$this->FechaMod);      
        $this->db->insert('correo');
        return true;
    }

    public function update()
    {
        $this->db->where('IdCorreo', $this->IdCorreo);
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('Leyenda', $this->Leyenda);
        $this->db->set('Pie',$this->Pie);
        $this->db->set('FechaMod',$this->FechaMod); 
        $this->db->update('correo');
        
        return true;
        
    }


    public function get_Corrreo()
    {
        $this->db->select('*');
        $this->db->from('correo');
        $this->db->where('IdCorreo', $this->IdCorreo);

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
        $this->db->from('correo'); 
        
        if (!empty($this->Titulo))
        {
            $this->db->like('Titulo', $this->Titulo);    
        }
       
        $this->db->order_by("IdCorreo", "desc");
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
  
}