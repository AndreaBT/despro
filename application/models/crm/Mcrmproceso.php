<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcrmproceso extends BaseModel
{
    // Properties
    public $IdCrmProceso=0;
    public $IdSucursal;
    public $Nombre;
    public $Numero;
    public $RegEstatus;
    public $Color;
    public $ColorLetra;
    public $Tipo;
    public $IdTipoProceso;
    public $FechaMod;

    public $IdProceso;
    public $IdClienteSucursal;
    public $IdTrabajador;
    public $IdOportunidad;
    public $Fecha;
    public $IdCliente;
    
    public function __construct()
    {
        parent::__construct();

    }

    public function Insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Numero',  $this->Numero);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Color', $this->Color);
        $this->db->set('ColorLetra', $this->ColorLetra);
        $this->db->set('Tipo', $this->Tipo);
        $this->db->set('IdTipoProceso', $this->IdTipoProceso);
        $this->db->set('FechaMod', $this->FechaMod);
       
        $this->db->insert('crmproceso');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdCrmProceso', $this->IdCrmProceso);

        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Color', $this->Color);
        $this->db->set('ColorLetra', $this->ColorLetra);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->Update('crmproceso');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete()
    {
        $this->db->where('IdCrmProceso', $this->IdCrmProceso);
        $this->db->set('RegEstatus','B');

        $this->db->Update('crmproceso');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function UpdateNumber()
    {
        $this->db->where('IdCrmProceso', $this->IdCrmProceso);

        $this->db->set('Numero', $this->Numero);
        $this->db->Update('crmproceso');
        #echo $this->db->last_query();
        return true;
    }
    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('crmproceso');
        $this->db->where('IdCrmProceso', $this->IdCrmProceso);

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
                'data' => new Mcrmproceso()
            ];
        }
    }
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('crmproceso');
        $this->db->where('IdSucursal ', $this->IdSucursal);
        $this->db->where('RegEstatus ', 'A');
        if (!empty($this->IdTipoProceso)) {
            $this->db->where('IdTipoProceso ', $this->IdTipoProceso);
        }
        // Filters
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre ', $this->Nombre);
        }
        $this->db->order_by("Numero", "asc");
        $this->db->group_by("Nombre");
        //Pagination
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recovery_nombre()
    {
        $this->db->select('*');
        $this->db->from('crmproceso');
        $this->db->where('IdTipoProceso', $this->IdTipoProceso);
        $this->db->where('Nombre', $this->Nombre);

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
                'data' => new Mcrmproceso()
            ];
        }
    }

    //Procesos para el pipe drive --NUEVO

    public function get_trabajadorxproceso(){
        $this->db->select('crm.IdCrmProceso, crm.Nombre, sc.MontoPropuesta,pv.IdTrabajador');
        $this->db->from('crmproceso crm');
        $this->db->join('procesovendedor pv','crm.IdTipoProceso=pv.IdTipoProceso','inner');
        $this->db->join('seguimientocliente sc','sc.IdProceso=crm.IdCrmProceso','left');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sc.IdClienteSucursal','left');
        $this->db->join('clientes c','c.IdCliente=cs.IdCliente','left');
        $this->db->where('crm.IdTipoProceso', $this->IdTipoProceso);
        $this->db->where('crm.IdSucursal', $this->IdSucursal);
        $this->db->where('crm.RegEstatus ', 'A');

        $this->db->order_by("Numero", "asc");

        $this->db->where('pv.IdTrabajador', $this->IdTrabajador);

        if (!empty($this->IdClienteSucursal)) {
            $this->db->where('sc.IdClienteSucursal', $this->IdClienteSucursal);

            $this->db->group_by("sc.IdClienteSucursal");
        }

        if (!empty($this->IdOportunidad)) {
            $this->db->where('sc.IdOportunidad', $this->IdOportunidad);
            $this->db->group_by("sc.IdOportunidad");
        }

        if (!empty($this->IdCliente)) {
            $this->db->where('c.IdCliente', $this->IdCliente);
            $this->db->group_by("c.IdCliente");
        }

        if (!empty($this->Fecha)) {
            $this->db->where('YEAR(sc.Fecha)', $this->Fecha);
        }

        $this->db->group_by("crm.IdCrmProceso");
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_oportunidadxProceso(){
        $this->db->select('sc.Fecha,cs.Nombre ,sc.Actividad,sc.Comentarios,sc.IdClienteSucursal, op.Nombre as Oportunidad, crm.IdTipoProceso, sc.MontoPropuesta');
        $this->db->from('seguimientocliente sc');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sc.IdClienteSucursal','inner');
        $this->db->join('clientes c','c.IdCliente=cs.IdCliente','left');
        $this->db->join('oportunidades op','op.IdOportunidad=sc.IdOportunidad','left');
        $this->db->join('crmproceso crm','crm.IdCrmProceso = sc.IdProceso','left');
        
        $this->db->where('sc.IdTrabajador', $this->IdTrabajador);
        $this->db->where('sc.IdSucursal', $this->IdSucursal);

        
            $this->db->where('crm.IdTipoProceso', $this->IdTipoProceso);
        
       
            $this->db->where('sc.IdProceso ', $this->IdProceso);
        

        if (!empty($this->IdClienteSucursal)) {
            $this->db->where('sc.IdClienteSucursal', $this->IdClienteSucursal);
        }

        if (!empty($this->IdCliente)) {
            $this->db->where('c.IdCliente', $this->IdCliente);
        }

        if (!empty($this->IdOportunidad)) {
            $this->db->where('sc.IdOportunidad', $this->IdOportunidad);
        }

        if (!empty($this->Fecha)) {
            $this->db->where('YEAR(sc.Fecha)', $this->Fecha);
        }
        

        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_SucursalxProceso(){
        $this->db->select('sc.Fecha,cs.Nombre ,sc.Actividad,sc.Comentarios,sc.IdClienteSucursal,c.Nombre as Sucursal,c.IdCliente');
        $this->db->from('seguimientocliente sc');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sc.IdClienteSucursal','inner');
        $this->db->join('clientes c','cs.IdCliente=c.IdCliente','inner');
        $this->db->where('sc.IdTrabajador', $this->IdTrabajador);
        $this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
        // $this->db->where('sc.IdSucursal', $this->IdSucursal);

        $this->db->group_by("sc.IdClienteSucursal");

        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_ClienteSucxProceso(){
        $this->db->select('sc.Fecha,cs.Nombre ,sc.Actividad,sc.Comentarios,sc.IdClienteSucursal');
        $this->db->from('seguimientocliente sc');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sc.IdClienteSucursal','inner');
        $this->db->join('clientes c','c.IdCliente=cs.IdCliente','left');
        $this->db->where('sc.IdTrabajador', $this->IdTrabajador);
        $this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
        $this->db->where('c.IdCliente', $this->IdCliente);

        $this->db->group_by("sc.IdClienteSucursal");

        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_VendedorxProcesoxOportunidad(){
        $this->db->select('sc.Fecha,sc.Actividad,sc.Comentarios,sc.IdClienteSucursal, op.Nombre as Oportunidad, op.IdOportunidad');
        $this->db->from('seguimientocliente sc');
        $this->db->join('oportunidades op','op.IdOportunidad=sc.IdOportunidad','inner');
        $this->db->where('sc.IdTrabajador', $this->IdTrabajador);

        $this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
        // $this->db->where('sc.IdSucursal', $this->IdSucursal);
        if (!empty($this->IdClienteSucursal)) {
            $this->db->where('sc.IdClienteSucursal', $this->IdClienteSucursal);
        }

        $this->db->group_by("sc.IdClienteSucursal");

        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }



}
