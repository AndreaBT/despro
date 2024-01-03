<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgastoxtrabajador extends BaseModel
{
    // Properties
    public $IdGasto;
    public $Fecha;
    public $Concepto;
    public $Total;
    public $IdTrabajador;
    public $IdCajaC;
    public $IdSucursal;
  
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdGasto = 0;
        $this->Fecha='';
        $this->Concepto = 0;
        $this->Total= '';
        $this->IdTrabajador = '';
        $this->IdCajaC = '';
        $this->IdSucursal= '';
        
    }


    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('gastosxtrabajador');
        $this->db->join('cajachica', 'gastosxtrabajador.IdCajaC = cajachica.IdCajaC');
        $this->db->where('cajachica.IdSucursal',$this->IdSucursal);
        $this->db->where('cajachica.RegEstatus','A');
  /*      $response = $this->db->get();
        if ($response->num_rows() > 0) {
            $data = $response->row();
            return [
                'status' => true,
                'data' => $data
            ];
        } else {
            return [
                'status' => false,
                'data' => ''
            ];
        }*/
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }


        if (!empty($this->Estado)) {
            $this->db->where('Estado', $this->Estado);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
}