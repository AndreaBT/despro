<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Moportunidades extends BaseModel
{
    // Properties
    public $IdOportunidad = 0;
    public $Nombre;
    public $RegEstatus;
    public $Estado;
    public $FechaReg;
    public $FechaMod;
    public $IdSucursal;
    public $IdVendedor;
    public $IdClienteS;
    public $IdTipoP;


    public function __construct()
    {
        parent::__construct();
        $this->IdOportunidad = 0;
        $this->Nombre       = '';
        $this->RegEstatus   = '';
        $this->Estado       = '';
        $this->FechaReg     = '';
        $this->FechaMod     = '';
        $this->IdSucursal   = 0;
        $this->IdVendedor   = 0;
        $this->IdClienteS   = 0;
        $this->IdTipoP      = '';
    }

    public function Insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Estado', "Pendiente");
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdVendedor', $this->IdVendedor);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdTipoP', $this->IdTipoP);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->insert('oportunidades');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdOportunidad', $this->IdOportunidad);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('IdVendedor', $this->IdVendedor);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdTipoP', $this->IdTipoP);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('oportunidades');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function UpdateEstado()
    {
        $this->db->where('IdOportunidad', $this->IdOportunidad);
        $this->db->set('Estado', $this->Estado);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('oportunidades');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function UpdateEstadoDelete()
    {
        $this->db->where('IdOportunidad', $this->IdOportunidad);
        $this->db->set('Estado', 'Abierta');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('oportunidades');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $this->db->where('IdOportunidad', $this->IdOportunidad);
        $this->db->set('RegEstatus', 'B');

        $this->db->Update('oportunidades');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function get_recovery()
    {
        $this->db->select('o.*,cs.Nombre as Sucursal');
        $this->db->from('oportunidades o');
        $this->db->join('clientesucursal cs', 'on cs.IdClienteS=o.IdClienteS ', 'left');
        $this->db->where('o.IdOportunidad', $this->IdOportunidad);

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
                'data' => new Moportunidades()
            ];
        }
    }
    public function get_list()
    {
        $this->db->select('o.*,cs.Nombre as Sucursal,t.Nombre as Vendedor,ctp.Nombre as TipoProceso, cs.Nombre as Cliente ,cs.Direccion ,cs.Telefono , cs.Correo');
        $this->db->from('oportunidades o');
        $this->db->join('clientesucursal cs', ' o.IdClienteS=cs.IdClienteS', 'inner');
        $this->db->join('crmtipoproceso ctp', ' o.IdTipoP=ctp.IdTipoProceso', 'inner');
        $this->db->join('trabajador t', ' o.IdVendedor=t.IdUsuario', 'inner');
        $this->db->where('o.IdSucursal', $this->IdSucursal);
        $this->db->where('o.RegEstatus ', 'A');

        // Filters

        if (!empty($this->Nombre)) {
            $this->db->like('o.Nombre ', $this->Nombre);
        }

        if (!empty($this->IdVendedor)) {
            $this->db->like('o.IdVendedor ', $this->IdVendedor);
        }

        if (!empty($this->IdTipoP)) {
            $this->db->like('o.IdTipoP ', $this->IdTipoP);
        }

        //Pagination
        $this->set_pagination();

        //echo $result = $this->db->get_compiled_select();

        $response = $this->db->get();
        return $response->result();
    }


    public function get_listvendedoroportunidad()
    {
        $this->db->select('o.*,cs.Nombre as Sucursal,t.Nombre as Vendedor');
        $this->db->from('oportunidades o');
        $this->db->join('clientesucursal cs', ' o.IdClienteS=cs.IdClienteS', 'inner');
        $this->db->join('trabajador t', ' o.IdVendedor=t.IdUsuario', 'inner');
        $this->db->where('o.IdSucursal', $this->IdSucursal);
        $this->db->where('o.RegEstatus ', 'A');
        $this->db->where('o.IdVendedor ', $this->IdVendedor);
        $this->db->where("(o.Estado = 'Abierta' OR o.Estado ='Pendiente')");

        // Filters
        if (!empty($this->Nombre)) {
            $this->db->like('o.Nombre ', $this->Nombre);
        }
        if (!empty($this->IdTipoP)) {
            $this->db->like('o.IdTipoP ', $this->IdTipoP);
        }


        //Pagination
        $this->set_pagination();
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }
}
