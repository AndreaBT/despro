<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcategoriavehiculo extends BaseModel
{
    // Properties
    public $IdCategoria;
    public $Nombre;
    public $RegEstatus;
    public $IdSucursal;
    public $FechaMod;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdCategoria= 0;
        $this->Nombre= '';
        $this->RegEstatus = '';
        $this->IdSucursal= '';
        $this->FechaMod= '';
    }


    public function insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RegEstatus', $this->RegEstatus);   
        $this->db->set('IdSucursal', $this->IdSucursal);  
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->insert('categoriavehiculo');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCategoria', $this->IdCategoria);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RegEstatus', $this->RegEstatus);   
        $this->db->set('IdSucursal', $this->IdSucursal);  
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('categoriavehiculo');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdCategoria', $this->IdCategoria);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->update('categoriavehiculo');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_categoriavehiculo()
    {
        $this->db->select('*');
        $this->db->from('categoriavehiculo');
        $this->db->where('IdCategoria', $this->IdCategoria);

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
        $this->db->select('*');
        $this->db->from('categoriavehiculo');
        $this->db->where('IdSucursal', $this->IdSucursal);
        // $this->db->not_like('Nombre','VIRTUAL');
        
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listCat()
    {
        $this->db->select('*');
        $this->db->from('categoriavehiculo');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_Virtual(){
        $this->db->select('*');
        $this->db->from('categoriavehiculo');
        $this->db->where('IdSucursal', $this->IdSucursal);
        // $this->db->not_like('Nombre','VIRTUAL');
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

  
 
  
}