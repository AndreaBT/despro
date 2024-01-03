<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcrmforecast extends BaseModel
{
    // Properties
    public $IdForeCast=0;
    public $IdVendedor;
    public $Uno=0;
    public $Dos=0;
    public $Tres=0;
    public $Cuatro=0;
    public $Anio;
    public $IdSucursal; 
    public function __construct()
    {
        parent::__construct();

    }

    public function Insert()
    {
        $this->db->set('IdVendedor', $this->IdVendedor);
        $this->db->set('Uno', $this->Uno);
        $this->db->set('Dos',  $this->Dos);
        $this->db->set('Tres', $this->Tres);
        $this->db->set('Cuatro', $this->Cuatro);
        $this->db->set('Anio', $this->Anio);
        $this->db->set('IdSucursal', $this->IdSucursal);
       
        $this->db->insert('crmforecast');
        return true;
    }
    public function Update()
    {
        $this->db->where('IdForeCast', $this->IdForeCast);
        $this->db->where('Anio', $this->Anio);

        $this->db->set('Uno', $this->Uno);
        $this->db->set('Dos',  $this->Dos);
        $this->db->set('Tres', $this->Tres);
        $this->db->set('Cuatro', $this->Cuatro);
    
        $this->db->Update('crmforecast');
            return true;
        
    }


 
    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('crmforecast');
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('IdVendedor', $this->IdVendedor);
        $this->db->where('Anio', $this->Anio);
      
        $response = $this->db->get();
        #echo $this->db->last_query();
        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else {
            return [
                'status' => false,
                'data' => new Mcrmforecast()
            ];
        }
    }
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('crmforecast');
        $this->db->where('IdSucursal', $this->IdSucursal);
      
        // Filters
        if (!empty($this->IdVendedor)) {
            $this->db->like('IdVendedor', $this->IdVendedor);
        }
       
        //Pagination
      //  $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }


}
