<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcrmtipoproceso extends BaseModel
{
    // Properties
    public $IdTipoProceso = 0;
    public $Nombre;
    public $IdConfigS;
    public $RegEstatus;
    public $FechaReg;
    public $IdSucursal;
    public $FechaMod;
    public $IdTrabajador;
    public $IdOportunidad;





    public function __construct()
    {
        parent::__construct();
    }

    public function Insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdConfigS', $this->IdConfigS);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaReg',  $this->FechaReg);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('FechaMod', $this->FechaMod);

        $this->db->insert('crmtipoproceso');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdTipoProceso', $this->IdTipoProceso);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('IdConfigS', $this->IdConfigS);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('crmtipoproceso');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function UpdateIdConfigS()
    {
        $this->db->where('IdTipoProceso', $this->IdTipoProceso);
        $this->db->set('IdConfigS', $this->IdConfigS);
        $this->db->Update('crmtipoproceso');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $this->db->where('IdTipoProceso', $this->IdTipoProceso);
        $this->db->set('RegEstatus', 'B');

        $this->db->Update('crmtipoproceso');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('crmtipoproceso');
        $this->db->where('IdTipoProceso', $this->IdTipoProceso);

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
                'data' => new Mcrmtipoproceso()
            ];
        }
    }
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('crmtipoproceso');
        $this->db->where('RegEstatus ', 'A');
        $this->db->where('IdSucursal ', $this->IdSucursal);
        // Filters
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre ', $this->Nombre);
        }

        //Pagination
        //$this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_asignados()
    {
        $this->db->select('cp.*,procs.IdTrabajador,
        CASE 
        when procs.IdTrabajador>0 then "true"
        else "false" end as Estatus ', false);
        $this->db->from('crmtipoproceso cp');
        $this->db->join('(select * from procesovendedor  where IdTrabajador=' . $this->IdTrabajador . '  group by IdTipoProceso) as procs', 'procs.IdTipoProceso=cp.IdTipoProceso', 'left');
        $this->db->where('cp.RegEstatus ', 'A');
        $this->db->where('cp.IdSucursal ', $this->IdSucursal);
        // Filters

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_asignadospipe()
    {
        $this->db->select('cp.*,procs.IdTrabajador,
        CASE 
        when procs.IdTrabajador>0 then "true"
        else "false" end as Estatus ', false);
        $this->db->from('crmtipoproceso cp');
        $this->db->join('(select * from procesovendedor  where IdTrabajador=' . $this->IdTrabajador . '  group by IdTipoProceso) as procs', 'procs.IdTipoProceso=cp.IdTipoProceso', 'left');
        $this->db->where('cp.RegEstatus ', 'A');
        $this->db->where('cp.IdSucursal ', $this->IdSucursal);
        $this->db->where('procs.IdTrabajador>0');
        // Filters
        #echo $result = $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_seguimiento_oportunidad()
    {
        $this->db->select('sc.*');
        $this->db->from('seguimientocliente sc');
        $this->db->join('oportunidades o', ' sc.IdOportunidad=o.IdOportunidad ', 'inner');
        $this->db->where('o.IdOportunidad', $this->IdOportunidad);
        $this->db->where('o.IdVendedor', $this->IdTrabajador);
        $this->db->where('sc.Estatus', 'Abierta');

        //echo $result = $this->db->get_compiled_select();
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
                'data' => new Mcrmtipoproceso()
            ];
        }
    }
}
