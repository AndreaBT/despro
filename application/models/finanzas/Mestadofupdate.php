<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadofupdate extends BaseModel
{
    // Properties
    public $IdSucursal = '';
    public $Anio = '';
    public $Descripcion = '';
    public $Mes = '0';
    public $Monto = '';
    public $IdConfigServ = '0';
    public $IdTipoServ = '0';



    public function __construct()
    {
        parent::__construct();
        // Init Properties

    }
    public function insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Anio', $this->Anio);
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('Mes', $this->Mes);
        $this->db->set('Monto', $this->Monto);
        $this->db->set('IdConfigServ', $this->IdConfigServ);
        $this->db->set('IdTipoServ', $this->IdTipoServ);

        $this->db->insert('estadofupdate');

        return 1;
    }

    public function update()
    {
        $this->db->where('Anio', $this->Anio);
        $this->db->where('Mes', $this->Mes);
        $this->db->where('IdConfigServ', $this->IdConfigServ);
        $this->db->where('IdTipoServ', $this->IdTipoServ);
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('Descripcion', $this->Descripcion);
        $this->db->set('Monto', $this->Monto);

        $this->db->update('estadofupdate');

        return 1;
    }


    public function get_estadoupdate()
    {
        $this->db->select('*');
        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }
        if (!empty($this->Mes)) {
            $this->db->where('Mes', $this->Mes);
        }
        if (!empty($this->IdConfigServ)) {
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }


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
                'data' => new Mestadofupdate()
            ];
        }
    }

    public function get_list()
    {
        $this->db->select('IdSucursal,Anio,Descripcion,Mes,IdConfigServ,IdTipoServ ,
        case   WHEN Monto = 0 THEN "" else Monto
        end  as Monto', false);
        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }
        if (!empty($this->Mes)) {
            $this->db->where('Mes', $this->Mes);
        }
        if (!empty($this->IdConfigServ)) {
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }

        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listIndividual($Tipo = 1)
    {
        if ($Tipo == 1) {
            $this->db->select('*');
        } else {
            $this->db->select('sum(Monto) as Monto ,Descripcion,Anio');
        }

        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->IdConfigServ)) {
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }

        if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }

        if ($Tipo == 1) {
            if (!empty($this->Mes)) {
                $this->db->where('Mes', $this->Mes);
            }
        } else {
            if (!empty($this->Mes)) {
                $this->db->where('Mes <=', $this->Mes);
            }
        }
        //se agrupa
        if ($Tipo == 2) {
            $this->db->group_by('Descripcion');
        }

        #echo $result = $this->db->get_compiled_select();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_listTodos($Tipo = 1)
    {

        $this->db->select('sum(Monto) as Monto ,Descripcion,Anio');
        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->Anio)) {
            $this->db->where('Anio', $this->Anio);
        }

        if ($Tipo == 1) {
            if (!empty($this->Mes)) {
                $this->db->where('Mes', $this->Mes);
            }
        } else {
            if (!empty($this->Mes)) {
                $this->db->where('Mes <=', $this->Mes);
            }
        }

        $this->db->group_by('Descripcion');
        $this->db->order_by('Descripcion');
        
        //echo $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }
}
