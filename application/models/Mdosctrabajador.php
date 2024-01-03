<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdosctrabajador extends BaseModel
{
	// Properties
	public $IdTrabajador;
	public $File;
	public $Tipo;
	public $Titulo;



	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdTrabajador = 0;
		$this->File = '';
		$this->Tipo = 0;
		$this->Titulo = '';
	}

	public function insert()
	{
		$this->db->set('IdTrabajador', $this->IdTrabajador);
		$this->db->set('File', $this->File);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('Titulo', $this->Titulo);
		$this->db->insert('filestrabajador');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->set('File', $this->File);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('Titulo', $this->Titulo);
		$this->db->update('filestrabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function delete()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->where('File', $this->File);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->delete('filestrabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('filestrabajador');
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		$this->db->where('Tipo', $this->Tipo);

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function updateInsert()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->where('File', $this->File);
		$this->db->set('Titulo', $this->Titulo);
		$this->db->update('filestrabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function exist()
	{
		$this->db->select('File');
		$this->db->from('filestrabajador');
		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->where('File', $this->File);

		//Pagination
		// $this->set_pagination();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return true;
		} else {
			
			return false;
		}
	}
}
