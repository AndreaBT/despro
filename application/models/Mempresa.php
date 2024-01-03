<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mempresa extends BaseModel
{
    // Properties
    public $IdEmpresa;
    public $Nombre;
    public $RFC;
    public $Direccion;
    public $Telefono;
    public $Correo;
    public $Ciudad;
    public $Pais;
    public $Estado;
    public $CP;
    public $RegEstatus;
    public $Imagen;
    public $Type;
    public $CotMant;
    public $CotServ;
    public $FechaMod;
    public $Logo;
    public $Version;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdEmpresa = 0;
        $this->Nombre = '';
        $this->RFC = '';
        $this->Direccion = '';
        $this->Telefono = '';
        $this->Correo = '';
        $this->Ciudad = '';
        $this->Pais = '';
        $this->Estado = '';
        $this->CP = '';
        $this->RegEstatus = '';
        $this->Imagen = '';
        $this->Type = '';
        $this->CotMant = '';
        $this->CotServ = '';
        $this->FechaMod = '';
        $this->Logo = '';
        $this->Version = '';
    }

    public function insert()
    {
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RFC', $this->RFC);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('Correo', $this->Correo);
        $this->db->set('Ciudad', $this->Ciudad);
        $this->db->set('Pais', $this->Pais);
        $this->db->set('Estado', $this->Estado);
        $this->db->set('CP', $this->CP);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Type', $this->Type);
        $this->db->set('CotMant', $this->CotMant);
        $this->db->set('CotServ', $this->CotServ);
        $this->db->set('Logo', $this->Logo);
        $this->db->set('Version', 4);

        $this->db->insert('empresa');

        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdEmpresa', $this->IdEmpresa);

        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('RFC', $this->RFC);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('Correo', $this->Correo);
        $this->db->set('Ciudad', $this->Ciudad);
        $this->db->set('Pais', $this->Pais);
        $this->db->set('Estado', $this->Estado);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('CP', $this->CP);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('Type', $this->Type);
        $this->db->set('CotMant', $this->CotMant);
        $this->db->set('CotServ', $this->CotServ);
        if($this->Logo!=''){
            $this->db->set('Logo', $this->Logo);
        }

        $this->db->update('empresa');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

        public function update_logo()
    {
        $this->db->where('IdEmpresa', $this->IdEmpresa);

        $this->db->set('Logo', $this->Logo);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('empresa');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdEmpresa', $this->IdEmpresa);

        $this->db->set('RegEstatus', 'B');
        $this->db->update('empresa');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_empresa()
    {
        $this->db->select('*');
        $this->db->from('empresa');
        $this->db->where('IdEmpresa', $this->IdEmpresa);

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
        $this->db->select('IdEmpresa,Nombre,RFC,Direccion,Telefono,Correo,Ciudad,Pais,Logo,Version');
        $this->db->from('empresa');

        // Filters
        if (!empty($this->IdEmpresa)) {
            $this->db->where('IdEmpresa !=', $this->IdEmpresa);
        }

        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }

        if (!empty($this->RFC)) {
            $this->db->like('RFC', $this->RFC);
        }

        if (!empty($this->Direccion)) {
            $this->db->like('Direccion', $this->Direccion);
        }

        if (!empty($this->Telefono)) {
            $this->db->like('Telefono', $this->Telefono);
        }

        if (!empty($this->Correo)) {
            $this->db->where('Correo', $this->Correo);
        }

        if (!empty($this->Ciudad)) {
            $this->db->where('Ciudad', $this->Ciudad);
        }

        if (!empty($this->Pais)) {
            $this->db->where('Pais', $this->Pais);
        }

        if (!empty($this->Estado)) {
            $this->db->where('Estado', $this->Estado);
        }

        if (!empty($this->CP)) {
            $this->db->where('CP', $this->CP);
        }
        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }




}
