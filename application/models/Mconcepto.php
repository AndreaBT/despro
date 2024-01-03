<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconcepto extends BaseModel
{
    // Properties
    public $IdConcepto;
    public $Nombre;
    public $IdEquipamiento;
    public $Valor;
    public $Meses;
    public $RegEstatus;
    public $Foto;
    public $FechaMod;

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdConcepto = 0;
        $this->Nombre= '';
        $this->IdEquipamiento = 0;
        $this->Valor ='';
        $this->Meses ='';
        $this->RegEstatus ='';
        $this->Foto ='';
        $this->FechaMod ='';
    }

    public function insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdEquipamiento', $this->IdEquipamiento);
        $this->db->set('Valor', $this->Valor);  
        $this->db->set('Meses', $this->Meses);
        $this->db->set('RegEstatus', $this->RegEstatus);   
        $this->db->set('Foto', $this->Foto);  
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->insert('concepto');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdConcepto', $this->IdConcepto);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdEquipamiento', $this->IdEquipamiento);
        $this->db->set('Valor', $this->Valor);  
        $this->db->set('Meses', $this->Meses); 
        $this->db->set('Foto', $this->Foto);  
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('concepto');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdConcepto', $this->IdConcepto);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('concepto');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_concepto()
    {
        $this->db->select('*');
        $this->db->from('concepto');
        $this->db->where('IdConcepto', $this->IdConcepto);

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
        $this->db->from('concepto');
        $this->db->where('IdEquipamiento', $this->IdEquipamiento);

        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}