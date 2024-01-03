<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Miconosemp extends BaseModel
{
    // Properties
    public $IdIconoEmp;
    public $Nombre;
    public $Imagen;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdIconoEmp = 0;
        $this->Nombre= '';
        $this->Imagen = '';

    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('iconosemp');
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}