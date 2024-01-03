<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mproveedores extends BaseModel
{
    // Properties
    public $IdProveedor;
    public $IdSucursal;
    public $Nombre;
    public $Rfc;
    public $DiasCredito;
    public $Comentario;
    public $RegEstatus;
    public $FechaReg;
    public $FechaMod;
    
    //nuevos 
    public $Contacto;
    public $Telefono;
    public $Direccion;
    public $DatosBancarios;
   

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
    }

    public function insert()
    {
    
        $this->db->set('IdSucursal', $this->IdSucursal);  
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Rfc', $this->Rfc);
        $this->db->set('DiasCredito', $this->DiasCredito);
        $this->db->set('Comentario', $this->Comentario);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('FechaMod', $this->FechaMod);

        //nuevos 
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('DatosBancarios', $this->DatosBancarios);

        $this->db->insert('proveedores');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdProveedor', $this->IdProveedor);

        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Rfc', $this->Rfc);
        $this->db->set('DiasCredito', $this->DiasCredito);
        $this->db->set('Comentario', $this->Comentario);
        $this->db->set('FechaMod', $this->FechaMod);

         //nuevos 
         $this->db->set('Contacto', $this->Contacto);
         $this->db->set('Telefono', $this->Telefono);
         $this->db->set('Direccion', $this->Direccion);
         $this->db->set('DatosBancarios', $this->DatosBancarios);
        $this->db->update('proveedores');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    public function delete()
    {
        $this->db->where('IdProveedor', $this->IdProveedor);
        $this->db->set('RegEstatus', 'B');
        $this->db->update('proveedores');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('proveedores');
        $this->db->where('IdProveedor', $this->IdProveedor);
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
                'data' => new Mproveedores()
            ];
        }
    }
    
    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('proveedores'); 
        $this->db->where('RegEstatus', 'A');
        $this->db->where('IdSucursal',$this->IdSucursal);
        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
}