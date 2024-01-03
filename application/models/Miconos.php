<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Miconos extends BaseModel
{
    // Properties
    public $IdIcono;
    public $Imagen;
    public $RegEstatus;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdIcono = 0;
        $this->Imagen= '';
        $this->RegEstatus = '';

    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('iconos');
        $this->db->where('RegEstatus', 'A');
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}