<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mperfil extends BaseModel
{
	// Properties
	public $IdPerfil;
	public $Nombre;
	public $Busqueda;
	public $RegEstatus;
	public $Perfiles = array();
	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdPerfil = 0;
		$this->Nombre = '';
		$this->Busqueda = 0;
		$this->RegEstatus = '';
	}

	public function insert()
	{
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Busqueda', $this->Busqueda);
		$this->db->set('RegEstatus', $this->RegEstatus);

		$this->db->insert('perfil');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdPerfil', $this->IdPerfil);
		$this->db->set('Nombre', $this->Nombre);

		$this->db->update('perfil');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdPerfil', $this->IdPerfil);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('perfil');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_recovery()
	{
		$this->db->select('*');
		$this->db->from('perfil');

		if ($this->IdPerfil > 0) {
			$this->db->where('IdPerfil', $this->IdPerfil);
		}

		if ($this->Busqueda != '') {
			$this->db->where('Busqueda', $this->Busqueda);
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
				'data' => new  Mperfil()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('perfil tu');

		if (!empty($this->Nombre)) {
			$this->db->like('tu.Nombre', $this->Nombre);
		}
		if (!empty($this->RegEstatus)) {
			$this->db->like('tu.RegEstatus', $this->RegEstatus);
		}
		if (count($this->Perfiles) > 0) {
			$this->db->where_in('tu.Nombre', $this->Perfiles);
		}

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}
}
