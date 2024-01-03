<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mvehiculo extends BaseModel
{
    // Properties
    public $IdVehiculo;
    public $Categoria;
    public $Marca;
    public $Modelo;
    public $Ano;
    public $Placa;
    public $Numero;
    public $TipoVehiculo;
    public $CostoAnual;
    public $IdSucursal;
    public $RegEstatus;
    public $IdCategoria;
    public $Inventario;
    public $Historial;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdVehiculo = '';
        $this->Categoria = '';
        $this->Marca = '';
        $this->Modelo = '';
        $this->Ano = '';
        $this->Placa = '';
        $this->Numero = '';
        $this->TipoVehiculo = '';
        $this->CostoAnual = '';
        $this->IdSucursal = '';
        $this->RegEstatus = '';
        $this->IdCategoria ='';
        $this->Inventario ='';
        $this->Historial ='';
      
    }
    
    public function get_list_vehiculo()
    {       
        
        
        $this->db->select('*');
        $this->db->from('vehiculo');
        
        if (!empty($this->IdSucursal)){
            $this->db->where('IdSucursal', $this->IdSucursal);
        }

        if (!empty($this->TipoVehiculo)){
            $this->db->where('TipoVehiculo', $this->TipoVehiculo);
        }

        $this->db->where('RegEstatus !=','B');

        if($this->Categoria!='')
        {
            $and=' Categoria like \'%'.$this->Categoria.'%\'';
            $this->db->where($and);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

}
