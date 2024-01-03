<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masignacioncaja extends BaseModel
{
    // Properties
    public $IdCajaC;
    public $IdTrabajador;
    public $MontoAsignado;
    public $MontoActual;
    public $RegEstatus;
    public $Estado;
    public $FechaFin;

   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCajaC= 0;
        $this->IdTrabajador = 0;
        $this->MontoAsignado = '';
        $this->MontoActual ='';
    }

    public function insert()
    {
    
        $this->db->set('IdCajaC', $this->IdCajaC);  
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('MontoAsignado', $this->MontoAsignado);
        $this->db->set('MontoActual', $this->MontoActual);
        $this->db->insert('cajaasig');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->where('IdCajaC', $this->IdCajaC);   

        $this->db->set('MontoAsignado', $this->MontoAsignado);
        $this->db->set('MontoActual', $this->MontoAsignado);

        $this->db->update('cajaasig');
        return true;
        
    }

    public function updateResta()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->where('IdCajaC', $this->IdCajaC);   
        $this->db->set('MontoActual', $this->MontoActual);

        $this->db->update('cajaasig');
        return true;
        
    }

    public function delete()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->where('IdCajaC', $this->IdCajaC);   
        $this->db->delete('cajaasig');
        return true;
        
    }
    
    
        public function get_list()
    {
        
        $this->db->select('c.Nombre as Caja ,cc.* ,ca.MontoAsignado,ca.MontoActual,ca.MontoActual as Actual,cc.Utilizado as UtilizadoActual');
        $this->db->from('cajaasig  ca');
        $this->db->join('cajachica cc ','ca.IdCajaC=cc.IdCajaC','inner');
        $this->db->join('caja c ','cc.IdCaja=c.IdCaja','inner');
    
        $this->db->where('cc.RegEstatus','A');
        $this->db->where('ca.IdTrabajador', $this->IdTrabajador);
        
        if (!empty($this->Estado))
        {
        $this->db->where('cc.Estado', $this->Estado);    
        }
         if (!empty($this->FechaFin))
        {
        $this->db->where('cc.FechaFin >=', $this->FechaFin);    
        }
        
        
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    } 


    public function get_cajaasig()
    {
        $this->db->select('*');
        $this->db->from('cajaasig');
         if (!empty($this->IdTrabajador))
        {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        }
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
    
      public function get_cajaasigTotal()
    {
        $this->db->select('sum(MontoAsignado)  as MontoAsignado');
        $this->db->from('cajaasig');
        if (!empty($this->IdTrabajador))
        {
            $this->db->where('IdTrabajador', $this->IdTrabajador);
        }
        $this->db->where('IdCajaC', $this->IdCajaC);
        #echo $result = $this->db->get_compiled_select();
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