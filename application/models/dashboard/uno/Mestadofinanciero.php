<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadofinanciero extends BaseModel
{
    // Properties
    public $IdEstadoF;
    public $IdConfigS;

    public $IdSucursal;
    public $Anio;
    public $Mes;
    public $Facturacion;
    public $Mes2;
    public $IdTipoServ;
    public $IdCliente;
    public $IdClienteS;
    public $IdContrato;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdEstadoF = 0;
        $this->IdConfigS = '';
        $this->Anio = '';
        $this->IdSucursal = '';
        $this->Anio = '';
        $this->Mes='';
        $this->Mes2='';
        $this->Facturacion=0;
        $this->IdTipoServ=0;
        $this->IdCliente=0;
        $this->IdClienteS=0;
        $this->IdContrato=0; 

    }

    public function get_list_estadofinanciero()
    {       
        
        $this->db->select('*');
        $this->db->from('estadofinanciero');
        
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->IdConfigS)){
            $this->db->where('IdConfigS', $this->IdConfigS);
        }

        if ($this->IdClienteS!="0"){
            $this->db->where('IdClienteS', $this->IdClienteS);
        }

        if ($this->IdCliente!="0"){
            $this->db->where('IdCliente', $this->IdCliente);
        }

        if (!empty($this->IdTipoSer)){
            $this->db->where('IdTipoSer', $this->IdTipoSer);
        }

        if ($this->IdContrato >0){
            $this->db->where('IdContrato'.$this->IdContrato);
        }

        if($this->Anio!='')
      {
        $and1 =' Anio=\''.$this->Anio.'\'';
        $this->db->where($and1);
      }
      
        if($this->Mes!='')
      {
        $and2 =' Mes between \''.$this->Mes.'\' and \''.$this->Mes2.'\'';
        $this->db->where($and2);
      }

        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
