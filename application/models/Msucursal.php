<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Msucursal extends BaseModel
{
	// Properties
	public $IdSucursal;
	public $Nombre;
	public $Telefono;
	public $Direccion;
	public $Correo;
	public $Ciudad;
	public $Estado;
	public $CP;
	public $IdEmpresa;
	public $TipSucursal;
	public $RegEstatus;
	public $Fecha_Fac;
	public $Plazo;
	public $LatSuc;
	public $LongSuc;
	public $PaqueteU;
	public $Comentario;
	public  $FechaMod;

	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdSucursal = 0;
		$this->Nombre = '';
		$this->Telefono = '';
		$this->Direccion = '';
		$this->Correo = '';
		$this->Ciudad = '';
		$this->Estado = '';
		$this->CP = '';
		$this->IdEmpresa = 0;
		$this->TipoSucursal = '';
		$this->RegEstatus = '';
		$this->Fecha_Fac = '';
		$this->Plazo = '';
		$this->LatSuc = '';
		$this->LongSuc = '';
		$this->PaqueteU = '';
		$this->Comentario = '';
		$this->FechaMod = '';
	}

	public function insert()
	{
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('Estado', $this->Estado);
		$this->db->set('CP', $this->CP);
		$this->db->set('IdEmpresa', $this->IdEmpresa);
		$this->db->set('TipSucursal', $this->TipSucursal);
		$this->db->set('RegEstatus', 'A');
		$this->db->set('Fecha_Fac', $this->Fecha_Fac);
		$this->db->set('Plazo', $this->Plazo);
		$this->db->set('PaqueteU', $this->PaqueteU);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->insert('sucursal');

		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdSucursal', $this->IdSucursal);

		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('Estado', $this->Estado);
		$this->db->set('CP', $this->CP);
		$this->db->set('Fecha_Fac', $this->Fecha_Fac);
		$this->db->set('Plazo', $this->Plazo);
		$this->db->set('PaqueteU', $this->PaqueteU);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('sucursal');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdSucursal', $this->IdSucursal);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('sucursal');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_all()
	{
		$this->db->where('IdEmpresa', $this->IdEmpresa);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('sucursal');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_sucursal()
	{
		$this->db->select('*');
		$this->db->from('sucursal');
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}
		if (!empty($this->IdEmpresa)) {
			$this->db->where('IdEmpresa', $this->IdEmpresa);
		}
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
		$this->db->from('sucursal');
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		// Filters

		if (!empty($this->Nombre)) {
			$this->db->like('Nombre', $this->Nombre);
		}

		if (!empty($this->RegEstatus)) {
			$this->db->where('RegEstatus', $this->RegEstatus);
		}
		#echo $result = $this->db->get_compiled_select();
		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function getEmployeeLimit()
	{
		$this->db->select('PaqueteU');
		$this->db->from('sucursal');
		$this->db->where('IdSucursal', $this->IdSucursal);

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
				'data' => new Mctaporpagar()
			];
		}
	}
}
