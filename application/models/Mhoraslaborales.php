<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mhoraslaborales extends BaseModel
{
    // Properties
    public $IdHorasL;
    public $Hora_I;
    public $Hora_F;
    public $IdSucursal;
    public $Intervalo;
    public $FechaMod;
  
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdHorasL = 0;
        $this->Hora_I = '';
        $this->Hora_F= '';
        $this->IdSucursal = 0;
        $this->Intervalo = '';
        $this->FechaMod = '';
       
    }

    public function insert()
    {

        $this->db->set('Hora_I', $this->Hora_I);
        $this->db->set('Hora_F', $this->Hora_F);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Intervalo', $this->Intervalo);
        $this->db->set('FechaMod', $this->FechaMod);
           
        $this->db->insert('horaslaborales');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdHorasL', $this->IdHorasL);
        $this->db->set('Hora_I', $this->Hora_I);
        $this->db->set('Hora_F', $this->Hora_F);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Intervalo', $this->Intervalo);
        $this->db->set('FechaMod', $this->FechaMod);   
        $this->db->update('horaslaborales');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdHorasL', $this->IdHorasL);
      
        $this->db->update('horaslaborales');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_horaslaborales()
    {
        $this->db->select('*');
        $this->db->from('horaslaborales');
        $this->db->where('IdSucursal', $this->IdSucursal);  
        
        if($this->IdHorasL>0){
            $this->db->where('IdHorasL', $this->IdHorasL);    
        }

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
                'status' => false
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('horaslaborales');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        // Filters
        if (!empty($this->IdHorasL)) {
            $this->db->where('IdHorasL !=', $this->IdHorasL);
        }

        if (!empty($this->Hora_I)) {
            $this->db->like('Hora_I', $this->Hora_I);
        }

        if (!empty($this->Hora_F)) {
            $this->db->like('Hora_F', $this->Hora_F);
        }
   
        if (!empty($this->Intervalo)) {
            $this->db->where('Intervalo', $this->Intervalo);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}