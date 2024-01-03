<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mspend_horas extends BaseModel
{
    // Properties
    public $IdSpendHora;
    public $IdSucursal;
    public $IdProyecto;
    public $FechaReg;
    public $Descripcion;
    public $Horas;
    public $FechaMod;
    public $RegEstatus;
    
    public $IdCliente;
    public $IdClienteS;
    public $FechaI;
    public $FechaF;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdSpendHora = 0;
        $this->IdSucursal= 0;
        $this->IdProyecto = '';
        $this->FechaReg = '';
        $this->Descripcion = '';
        $this->Horas = '';
        $this->FechaMod = '';
        $this->RegEstatus = '';
        
        $this->IdCliente=0;
        $this->IdClienteS=0;
        $this->FechaI='';
        $this->FechaF='';
    }

    public function insert()
    {
        
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdProyecto', $this->IdProyecto);   
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('Horas', $this->Horas);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
  
        $this->db->insert('spend_horas');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdSpendHora', $this->IdSpendHora);
        $this->db->set('IdProyecto', $this->IdProyecto); 
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('Horas', $this->Horas);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->update('spend_horas');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdSpendHora', $this->IdSpendHora);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('spend_horas');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('spend_horas');
        $this->db->where('IdSpendHora', $this->IdSpendHora);

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


    public function get_list()
    {
        $this->db->select('soc.*,c.Nombre as Cliente,cs.Nombre as Sucursal,sp.Proyecto,DATE_FORMAT(soc.FechaReg, "%d-%m-%Y %H:%i:%s") as FechaReg');
        $this->db->from('spend_horas soc');
        $this->db->join('spend_proyecto sp','sp.IdProyecto=soc.IdProyecto','inner');
        $this->db->join('clientes c','c.IdCliente=sp.IdCliente','inner');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sp.IdClienteS','inner');
        
        $this->db->where('soc.IdSucursal ', $this->IdSucursal);
        // Filters
        if (!empty($this->IdSpendHora)) {
            $this->db->where('soc.IdSpendHora', $this->IdSpendHora);
        }
               
        if ($this->IdProyecto>0) {
            $this->db->where('sp.IdProyecto', $this->IdProyecto);
        }
        
        if ($this->IdCliente>0) {
            $this->db->where('sp.IdCliente', $this->IdCliente);
        }
        
        if ($this->IdClienteS>0) {
            $this->db->where('sp.IdClienteS', $this->IdClienteS);
        }


        if (!empty($this->Descripcion)) {
            $this->db->like('soc.Descripcion', $this->Descripcion);
        }
        
        if ($this->FechaI !='' && $this->FechaF!='') {
            $where='cast(soc.FechaReg as date) >= \''.$this->FechaI.'\' and cast(soc.FechaReg as date) <= \''.$this->FechaF.'\'';
            $this->db->where($where);
        }
    
        if (!empty($this->RegEstatus)) {
            $this->db->where('soc.RegEstatus', $this->RegEstatus);
        }

        $this->db->order_by('soc.FechaReg','desc');
        //Pagination
        $this->set_pagination();
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }
    
    public function get_sum_horas()
    {
        $this->db->select('sum(soc.Horas) as Horas');
        $this->db->from('spend_horas soc');
        
        $this->db->where('soc.IdProyecto ', $this->IdProyecto);
        $this->db->where('soc.RegEstatus ', 'A');
        // Filters


        $this->db->order_by('soc.FechaReg','desc');
        //Pagination
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        if ($response->num_rows() > 0) {
            $data = $response->row();
            $this->Horas=$data->Horas;
        } else {
            $this->Horas=0;
        }
    }
  
}