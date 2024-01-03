<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfolio extends BaseModel
{
    // Properties
    public $IdFolio;
    public $IdSucursal;
    public $Serie;
    public $Numero;
    public $Tipo;
    public $RegEstatus;
    public $FechaMod;
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdFolio = 0;
        $this->IdSucursal= 0;
        $this->Serie = '';
        $this->Numero = '';
        $this->Tipo = '';
        $this->RegEstatus = '';
        $this->FechaMod = '';
    }

    public function insert()
    {
     
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Serie', $this->Serie);   
        $this->db->set('Numero', $this->Numero);
        $this->db->set('Tipo', $this->Tipo);
  
        $this->db->insert('folio');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdFolio', $this->IdFolio);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Serie', $this->Serie);   
        $this->db->set('Numero', $this->Numero);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->update('folio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateFolio()
    {
        $this->db->where('IdFolio', $this->IdFolio);
        $this->db->set('Numero', $this->Numero);
        $this->db->update('folio');
        return true;
    }

    public function delete()
    {
        $this->db->where('IdFolio', $this->IdFolio);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('folio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_folio()
    {
        $this->db->select('*');
        $this->db->from('folio');
        $this->db->where('IdFolio', $this->IdFolio);

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
                'data' => new Mfolio
            ];
        }
    }
    
    public function get_foliovalidacion()
    {
        $this->db->select('*');
        $this->db->from('folio');
        $this->db->where('IdSucursal', $this->IdSucursal);  
        
        if($this->Tipo!=''){
            $this->db->where('Tipo', $this->Tipo);    
        }
            
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
                'data' => new Mfolio
            ];
        }
    }
    
    public function get_Exist_folio()
    {
        $this->db->select('*');
        $this->db->from('folio');
        $this->db->where('IdSucursal', $this->IdSucursal);
        if($this->IdFolio!=''){
            $this->db->where('IdFolio !=', $this->IdFolio);    
        }
        if($this->Tipo!=''){
            $this->db->where('Tipo', $this->Tipo);
        }
        
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('folio');
        $this->db->where('IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdFolio)) {
            $this->db->where('IdFolio !=', $this->IdFolio);
        }

        if (!empty($this->Serie)) {
            $this->db->like('Serie', $this->Serie);
        }

        if (!empty($this->Numero)) {
            $this->db->like('Numero', $this->Numero);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->like('RegEstatus', $this->RegEstatus);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
}