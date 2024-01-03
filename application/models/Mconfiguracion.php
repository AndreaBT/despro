<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconfiguracion extends BaseModel
{
    // Properties
    public $IdSucursal;
    public $ZonaHoraria;
    public $RegEstatus;

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdSucursal = 0;
        $this->ZonaHoraria= '';
        $this->RegEstatus = 0;
        
    }

    public function insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('ZonaHoraria', $this->ZonaHoraria);
        $this->db->set('RegEstatus', 'A');   
        $this->db->insert('configuracion');
        return true;
    }

    public function update()
    {
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->set('ZonaHoraria', $this->ZonaHoraria);
        $this->db->update('configuracion');
        
        return true;
    }


    public function get_concepto()
    {
        $this->db->select('*');
        $this->db->from('configuracion');
        $this->db->where('IdSucursal', $this->IdSucursal);

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
}