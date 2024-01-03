<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcajachica extends BaseModel
{
    // Properties
    public $IdCajaC;
    public $IdCaja;
    public $IdSucursal;
    public $Nombre;
    public $FechaInicio;
    public $FechaFin;
    public $Monto;
    public $RegEstatus;
    public $Estado;
    public $Utilizado;
    public $FechaMod;
    public $Tipo;



    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCajaC = 0;
        $this->IdCaja= 0;
        $this->IdSucursal = 0;
        $this->Nombre = '';
        $this->FechaInicio = '';
        $this->FechaFin = '';
        $this->Monto = '';
        $this->RegEstatus = '';
        $this->Estado = '';
        $this->Utilizado = '';
        $this->FechaMod = '';
        $this->Tipo = '';
    }

    public function insert()
    {

        $this->db->set('IdCaja', $this->IdCaja);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('FechaInicio', $this->FechaInicio);
        $this->db->set('FechaFin', $this->FechaFin);
        $this->db->set('Monto', $this->Monto);
        $this->db->set('RegEstatus', 'A');
        $this->db->set('Estado', $this->Estado);
        $this->db->set('FechaMod', $this->FechaMod);
       $this->db->set('Utilizado', $this->Utilizado);

        $this->db->insert('cajachica');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCajaC', $this->IdCajaC);
        $this->db->set('IdCaja', $this->IdCaja);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('FechaInicio', $this->FechaInicio);
        $this->db->set('FechaFin', $this->FechaFin);
        $this->db->set('RegEstatus', 'A');
        $this->db->set('Estado', $this->Estado);
        $this->db->set('Utilizado', $this->Utilizado);
        $this->db->set('Monto', $this->Monto);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('cajachica');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function delete()
    {
        $this->db->where('IdCajaC', $this->IdCajaC);

        $this->db->set('RegEstatus', 'B');
        $this->db->update('cajachica');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function changemontos()
    {
        $this->db->where('IdCajaC', $this->IdCajaC);

        $this->db->set('Utilizado', $this->Utilizado);
        $this->db->update('cajachica');
            return true;
    }

    public function get_cajachica()
    {
        $this->db->select('*');
        $this->db->from('cajachica');
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
        $this->db->select('c.Nombre as Caja, cc.*,DATE_FORMAT(cc.FechaInicio, "%d-%m-%Y") as FechaInicio ,DATE_FORMAT(cc.FechaFin, "%d-%m-%Y") as FechaFin ');
        $this->db->from('cajachica cc');
         $this->db->join('caja c ','c.IdCaja= cc.IdCaja ','inner');
         $this->db->where('cc.RegEstatus', 'A');
         $this->db->where('cc.IdSucursal',$this->IdSucursal);

        if (!empty($this->Nombre)) {
            $this->db->like('c.Nombre', $this->Nombre);
        }

        if ($this->Monto=='Mayor') {
            $this->db->where('cc.Monto>', '0');
        }
        if (!empty($this->FechaFin)) {
            //$this->db->where('cc.FechaFin >=', $this->FechaFin); // REMOVIDO UNICAMENTE HASTA COMPROBAR SU FUNCIONAMIENTO REAL EN LA COSULTA
        }

        if (!empty($this->Estado)) {
            $this->db->where('cc.Estado', $this->Estado);
        }
        if (!empty($this->Tipo)) {
            $this->db->where('c.Tipo', $this->Tipo);
        }

        $this->db->order_by("IdCajaC", "desc");
       #echo $result = $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

}
