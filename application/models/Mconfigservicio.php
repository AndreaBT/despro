<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconfigservicio extends BaseModel
{
	// Properties
	public $IdConfigS;
	public $Nombre;
	public $Porcentaje;
	public $Porcentaje2;
	public $RegEstatus;
	public $Facturable;

	public function __construct()
	{
		parent::__construct();
		// Init Properties
		$this->IdConfigS = 0;
		$this->Nombre = '';
		$this->Porcentaje = '';
		$this->Porcentaje2 = '';
		$this->RegEstatus = '';
		$this->Facturable = '';
	}

	public function get_configservicio()
	{
		$this->db->select('*');
		$this->db->from('configservicio');
		$this->db->where('IdConfigS', $this->IdConfigS);
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
				'data' => new Mconfigservicio()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('configservicio');
		$this->db->where('RegEstatus', 'A');

		if (!empty($this->Facturable)) {
			$this->db->where('Facturable', $this->Facturable);
		}

		//Pagination
		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}
}
