<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadoupdate extends BaseModel
{
    // Properties
    public $IdSucursal;
    public $Anio;
    public $Descripcion;
    public $Mes;
    public $Mes2;
  
    public $Monto;
    public $IdConfigServ;
    public $IdTipoServ;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdSucursal = 0;
        $this->Anio = '';
        $this->Descripcion = '';
        $this->Mes = '';
        $this->Monto = '';
        $this->IdConfigServ = '';
        $this->IdTipoServ = '';

    }

    public function get_list_estadofupdate()
    {       
        
        $this->db->select('*');
        $this->db->from('estadofupdate');
        
       
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if ($this->Descripcion != ''){
            $and = 'Descripcion=\'' . $this->Descripcion . '\'';
            $this->db->where($and);
        }

        if ($this->Anio != ''){
            $and2 = ' Anio=\'' . $this->Anio . '\'';
            $this->db->where($and2);
        }

        if ($this->Mes != '') {

            $this->db->where('Mes between \'' . $this->Mes . '\' and \'' . $this->Mes2 . '\'');
        }

        if ($this->IdConfigServ != ''){
            $and3 = ' IdConfigServ = \'' . $this->IdConfigServ . '\'';
            $this->db->where($and3);
        }

        if ($this->IdTipoServ != '') {
            $and4 = ' IdTipoServ = \'' . $this->IdTipoServ . '\'';
            $this->db->where($and4);
        } else {
            $and5 = ' IdTipoServ !=0';
            $this->db->where($and5);
        }

        // if ($this->IdTipoServ != '') {
        //     $and = 'IdTipoServ = \'' . $this->IdTipoServ . '\'';
        //     $this->db->where($and);
        //   } else {
        //     $and = ' IdTipoServ !=0';
        //     $this->db->where($and);
        //   }

        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
