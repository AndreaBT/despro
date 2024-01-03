<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mplancostovehiculosope extends BaseModel {
    // Properties
    public $IdPlanCostoVehiculosOpe     ='';
    public $Anio                        ='';
    public $IdSucursal                  ='';
    public $NumVehiculos                =0;
    public $kmproductivo                =0;
    public $TotalAnual                  =0;
    public $TotalFinal                  =0;
    public $TotalCorregido              =0;          

    public function __construct() {
        parent::__construct();
        // Init Properties
      
    }

    public function insertOvc() {
        $this->db->set('IdSucursal',        $this->IdSucursal);
        $this->db->set('Anio',              $this->Anio);
        $this->db->set('NumVehiculos',      $this->NumVehiculos);
        $this->db->set('kmproductivo',      $this->kmproductivo); 
        $this->db->set('TotalAnual',        $this->TotalAnual);      
        $this->db->set('TotalFinal',        $this->TotalFinal);
        $this->db->set('TotalCorregido',    $this->TotalCorregido);  
        $this->db->insert('plancostovehiculosope');
        return 1;
    }

    public function updateOvc() {   
        $this->db->where('IdSucursal',            $this->IdSucursal);
        $this->db->where('Anio',                  $this->Anio);
        $this->db->set('NumVehiculos',            $this->NumVehiculos);
        $this->db->set('kmproductivo',            $this->kmproductivo); 
        $this->db->set('TotalAnual',              $this->TotalAnual);      
        $this->db->set('TotalFinal',              $this->TotalFinal);
        $this->db->set('TotalCorregido',          $this->TotalCorregido);
        $this->db->update('plancostovehiculosope');
        return 1;
    }
    
    public function get_costovehiculosope() {
        $this->db->select('*');
        $this->db->from('plancostovehiculosope');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
       if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }
       
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
 
        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data,
            ];
        } else {
            $obj=new Mplancostovehiculosope(); 
            return [
                'status' => false,
                'data' => new Mplancostovehiculosope()
            ];
        }
    }
    
    public function get_list() {
        $this->db->select('*');
        $this->db->from('plancostovehiculosope');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
 
}