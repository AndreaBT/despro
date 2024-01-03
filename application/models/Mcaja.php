<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcaja extends BaseModel
{
    // Properties
    public $IdCaja;
    public $Nombre;
    public $RegEstatus;
    public $IdSucursal;
    public $Estado;
    public $IdRol;
    public $FechaMod;
    public $Tipo;
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCaja= 0;
        $this->Nombre = '';
        $this->RegEstatus = '';
        $this->IdSucursal = 0;
        $this->Estado = '';
        $this->IdRol = 0;
        $this->FechaMod;
    }

    public function insert()
    {
    
        $this->db->set('Nombre', $this->Nombre);  
        $this->db->set('RegEstatus', 'A');
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Estado', $this->Estado);
        $this->db->set('IdRol', $this->IdRol);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->insert('caja');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCaja', $this->IdCaja);
        $this->db->set('Nombre', $this->Nombre);  
        $this->db->set('Estado', $this->Estado);
        $this->db->set('IdRol', $this->IdRol);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->update('caja');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function delete()
    {
        $this->db->where('IdCaja', $this->IdCaja);
        $this->db->set('RegEstatus', 'B');
        $this->db->update('caja');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_caja()
    {
        $this->db->select('*');
        $this->db->from('caja');
        $this->db->where('IdCaja', $this->IdCaja);
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
                'data' => ''
            ];
        }
    }
  /*  $this->db->select('a.*,d.*');
    $this->db->from('entradas a');
    $this->db->join('proveedor d', 'a.proveedor = d.id');

    $aResult = $this->db->get();

    if(!$aResult->num_rows() == 1)
    {
        return false;
    }

    return $aResult->result_array();*/
    
    public function get_cajacajachica()
    {
        $this->db->select('cajachica.IdCajaC,caja.*');
        $this->db->from('caja');
        $this->db->join('cajachica', 'caja.IdCaja = cajachica.IdCaja');
        $this->db->where('caja.IdSucursal',$this->IdSucursal);
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
                'data' => ''
            ];
        }
    }


    
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('caja'); 
        $this->db->where('RegEstatus', 'A');
        $this->db->where('IdSucursal',$this->IdSucursal);
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }
        if (!empty($this->Estado)) {
            $this->db->where('Estado', $this->Estado);
        }
        
        if (!empty($this->Tipo)) {
            if ($this->Tipo=='Vendedor')
            {
                $this->db->where('Tipo ', 'Vendedor');
            }
            else{
                $where ='  Tipo  is null or  Tipo="Tecnico" ';
                $this->db->where($where);
            }
           
        }
        $this->db->order_by("IdCaja", "desc");
        //Pagination
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }
    
}