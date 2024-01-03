<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpaquete extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $Nombre;
    public $Tipo;
    public $Asociado;
    public $ReqPermiso;
    public $Clave;
    public $RegEstatus;
    public $Check;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete= 0;
        $this->Nombre= '';
        $this->Tipo= '';
        $this->Asociado= '';
        $this->ReqPermiso= '';
        $this->Clave= '';
        $this->RegEstatus = '';
        $this->IdSucursal= 0;
        $this->Check= false;


    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('paquete');
        $this->db->where('RegEstatus', 'A');
        
        if (! empty($this->Tipo))
        {
            $this->db->where('Tipo', $this->Tipo);    
        }
        
          if (! empty($this->Asociado))
        {
            $this->db->where('Asociado', $this->Asociado);    
        }
        $this->db->order_by('Orden', 'asc');
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_paquetexsucursal()
    {
        $this->db->select('*');
        $this->db->from('paquetexsucursal');
        
        //Pagination

        if (!empty($this->IdSucursal)) {
            $this->db->where('IdSucursal', $this->IdSucursal);
        }

        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
  
 
  
}