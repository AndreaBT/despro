<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mticket extends BaseModel
{
    // Properties
    public $IdTiket;
    public $IdCliente;
    public $IdClienteS;
    public $Folio;
    public $Lugar;
    public $Contacto;
    public $Correo;
    public $Telefono;
    public $Asunto;
    public $RegEstatus;
    public $FechaReg;
    public $Estado;
    public $FechaMod ;
    
    
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdTiket = 0;
        $this->IdCliente= 0;
        $this->Folio='';
        $this->Lugar= '';
        $this->Contacto= '';
        $this->Correo= '';
        $this->Telefono = '';
        $this->IdClienteS = '';
        $this->Telefono = '';
        $this->Asunto = '';
        $this->RegEstatus = '';
        $this->FechaReg = '';
        $this->Estado = '';
        $this->FechaMod = '';
        
    }

    public function insert()
    {

        $this->db->set('IdTiket', $this->IdTiket);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('Folio', $this->Folio);
        $this->db->set('Lugar', $this->Lugar);
        $this->db->set('Asunto', $this->Asunto);      
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Correo', $this->Correo);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('Estado', $this->Estado);
        $this->db->insert('ticket');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdTiket', $this->IdTiket);
        
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('Lugar', $this->Lugar);
        $this->db->set('Asunto', $this->Asunto);      
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->update('ticket');
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdTiket', $this->IdTiket);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod',$this->FechaMod);
        $this->db->update('ticket');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
     public function updateesatus()
    {
        $this->db->where('IdTiket', $this->IdTiket);
      
        $this->db->set('Estado', $this->Estado);
        $this->db->set('FechaMod',$this->FechaMod);
        $this->db->update('ticket');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('ticket');
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
        $this->db->from('ticket');
        $this->db->where('IdCliente', $this->IdCliente);
        
        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdClienteS)) {
            $this->db->where('IdClienteS', $this->IdClienteS);
        }
        $this->db->order_by('IdTiket','desc');
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
  
}