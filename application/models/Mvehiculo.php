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
    public $FechaMod;
  
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdVehiculo = 0;
        $this->Categoria = '';
        $this->Marca= '';
        $this->Modelo = '';
        $this->Ano= '';
        $this->Placa= '';
        $this->Numero= '';
        $this->TipoVehiculo= '';
        $this->CostoAnual= '';
        $this->IdSucursal= 0;
        $this->RegEstatus= '';
        $this->IdCategoria= 0;
        $this->Inventario= '';
        $this->Historial= '';
        $this->FechaMod= '';
    }

    public function insert()
    {

        $this->db->set('Categoria', $this->Categoria);
        $this->db->set('Marca', $this->Marca);
        $this->db->set('Modelo', $this->Modelo);
        $this->db->set('Ano', $this->Ano);   
        $this->db->set('Placa', $this->Placa);
        $this->db->set('TipoVehiculo', $this->TipoVehiculo);
        $this->db->set('CostoAnual', $this->CostoAnual);   
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdCategoria', $this->IdCategoria);
        $this->db->set('Inventario', $this->Inventario);  
        $this->db->set('Historial', $this->Historial);
        $this->db->set('FechaMod', $this->FechaMod);
        
        $this->db->insert('vehiculo');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdVehiculo', $this->IdVehiculo);
        $this->db->set('Categoria', $this->Categoria);
        $this->db->set('Marca', $this->Marca);
        $this->db->set('Modelo', $this->Modelo);
        $this->db->set('Ano', $this->Ano);   
        $this->db->set('Placa', $this->Placa);
        $this->db->set('Numero', $this->Numero);
        $this->db->set('TipoVehiculo', $this->TipoVehiculo);
        $this->db->set('CostoAnual', $this->CostoAnual);   
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('IdCategoria', $this->IdCategoria);
        $this->db->set('Inventario', $this->Inventario);  
        $this->db->set('Historial', $this->Historial);
        $this->db->set('FechaMod', $this->FechaMod);
         
        $this->db->update('vehiculo');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdVehiculo', $this->IdVehiculo);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('vehiculo');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_vehiculo()
    {
        $this->db->select('*');
        $this->db->from('vehiculo');
        $this->db->where('IdVehiculo', $this->IdVehiculo);

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
        $this->db->select('v.*,cv.*,cv.Nombre as Vehiculo,v.RegEstatus as RegVehiculo');
        $this->db->from('vehiculo v' );
        $this->db->join('categoriavehiculo cv','v.IdCategoria=cv.IdCategoria','inner');
        $this->db->where('v.IdSucursal', $this->IdSucursal);
        // Filters
        if (!empty($this->IdVehiculo)) {
            $this->db->where('IdVehiculo !=', $this->IdVehiculo);
        }

        if (!empty($this->Categoria)) {
            $this->db->like('v.Categoria', $this->Categoria);
        }

       
        if (!empty($this->RegEstatus)) {
            $this->db->where('v.RegEstatus', $this->RegEstatus);
        }
        
        
        if (!empty($this->TipoVehiculo)) {
            $this->db->where('v.TipoVehiculo', $this->TipoVehiculo);
        }

        //Pagination
        $this->set_pagination();
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list2()
    {
        $this->db->select('v.*,cv.*,cv.Nombre as Vehiculo,v.RegEstatus as RegVehiculo');
        $this->db->from('vehiculo v' );
        $this->db->join('categoriavehiculo cv','v.IdCategoria=cv.IdCategoria','inner');
        $this->db->where('v.IdSucursal', $this->IdSucursal);

            $this->db->where('v.Categoria =', 'VIRTUAL');
        // Filters
        

    

        //Pagination
        $this->set_pagination();
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}