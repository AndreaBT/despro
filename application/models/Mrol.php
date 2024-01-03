<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mrol extends BaseModel
{
	// Properties
	public $IdRol;
	public $IdSucursal;
	public $Nombre;
	public $Estatus;
	public $Tipo;
	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdRol = 0;
		$this->IdSucursal = 0;
		$this->Nombre = '';
		$this->Estatus = '';
		$this->Tipo = '';
	}

	public function get_recovery()
	{
		$this->db->select('*');
		$this->db->from('rol');

		if ($this->IdRol != '') {
			$this->db->where('IdRol', $this->IdRol);
		}
		if ($this->IdSucursal != '') {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}
		if ($this->Nombre != '') {
			$this->db->where('Nombre', $this->Nombre);
		}

		//echo $result = $this->db->get_compiled_select();
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
				'data' => new Mrol()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('rol');
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Nombre)) {
			$this->db->where('Nombre', $this->Nombre);
		}
		if (!empty($this->Estatus)) {
			$this->db->where('Estatus', $this->Estatus);
		}
		if (!empty($this->IdRol)) {
			$this->db->where('IdRol', $this->IdRol);
		}


		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_listName()
	{
		$this->db->select('*');
		$this->db->from('rol');
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Nombre)) {
			$this->db->where_in('Nombre', $this->Nombre);
		}
		if (!empty($this->Estatus)) {
			$this->db->where('Estatus', $this->Estatus);
		}
		if (!empty($this->IdRol)) {
			$this->db->where('IdRol', $this->IdRol);
		}

		#echo $result = $this->db->get_compiled_select();
		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}
}
