<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconceptooperacion extends BaseModel
{
	// Properties
	public $IdConceptoOperacion;
	public $Nombre;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_conceptooperacion()
	{
		$this->db->select('*');
		$this->db->from('conceptooperacion');
		$this->db->where('IdConceptoOperacion', $this->IdConceptoOperacion);
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
				'data' => new Mconceptooperacion()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('conceptooperacion');

		# $this->db->order_by("IdConceptoOperacion", "desc");
		//$this->db->order_by("Nombre");
		//Pagination

		$response = $this->db->get();
		return $response->result();
	}
}
