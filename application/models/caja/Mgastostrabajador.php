<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgastostrabajador extends BaseModel
{
    // Properties
    public $IdGasto;
    public $Fecha;
    public $Concepto;
    public $Total;
    public $IdTrabajador;
    public $IdCajaC;
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdGasto= 0;
        $this->Fecha = '';
        $this->Concepto = '';
        $this->Total = 0;
        $this->IdTrabajador = '';
        $this->IdCajaC = 0;
    }

    public function insert()
    {
    
        $this->db->set('IdGasto', $this->IdGasto);  
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Concepto', $this->Concepto);
        $this->db->set('Total', $this->Total);
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('IdCajaC', $this->IdCajaC);
        $this->db->insert('gastosxtrabajador');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdGasto', $this->IdGasto);
        $this->db->set('Concepto', $this->Concepto);
        $this->db->set('Total', $this->Total);
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->update('gastosxtrabajador');
            return true;
        
    }
    

    public function delete()
    {
        $this->db->where('IdGasto', $this->IdGasto);
        $this->db->delete('gastosxtrabajador');
    }

    public function get_caja()
    {
        $this->db->select('*');
        $this->db->from('gastosxtrabajador');
        $this->db->where('IdCajaC', $this->IdCajaC);
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
        $this->db->select('gt.*,t.Nombre as Trabajador');
        $this->db->from('gastosxtrabajador gt'); 
        $this->db->join('trabajador t ','gt.IdTrabajador=t.IdTrabajador','inner');
        $this->db->where('gt.IdCajaC',$this->IdCajaC);
        
        if (!empty($this->IdTrabajador)) {
            $this->db->like('gt.IdTrabajador', $this->IdTrabajador);
        }
        if (!empty($this->Fecha)) {
            $this->db->where('gt.Fecha', $this->Fecha);
        }
         
       # echo $result = $this->db->get_compiled_select();
        
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
     public function get_TotalGasto()
    {
        $this->db->select('sum(gt.Total) as Total');
        $this->db->from('gastosxtrabajador gt');
        $this->db->where('IdCajaC', $this->IdCajaC);
          if (!empty($this->IdTrabajador)) {
            $this->db->where('gt.IdTrabajador', $this->IdTrabajador);
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
                'data' => ''
            ];
        }
    }
    
}