<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconfigporcensubtipo extends BaseModel
{
	// Properties
	public $IdConfigS;
	public $IdTipoS;
	public $IdVendedor;
	public $Porcentaje;
	public $Anio;

	public function __construct()
	{
		parent::__construct();
		// Init Properties
		$this->IdConfigS = 0;
		$this->IdTipoS = '';
		$this->IdVendedor = '';
		$this->Porcentaje = '';
		$this->Anio = '';
	}
	public function insert()
	{
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('IdTipoS', $this->IdTipoS);
		$this->db->set('IdVendedor', $this->IdVendedor);
		$this->db->set('Porcentaje', $this->Porcentaje);
		$this->db->set('Anio', $this->Anio);

		$this->db->insert('configporcensubtipo');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdConfigS', $this->IdConfigS);
		$this->db->where('IdTipoS', $this->IdTipoS);
		$this->db->where('IdVendedor', $this->IdVendedor);
		$this->db->where('Anio', $this->Anio);

		$this->db->set('Porcentaje', $this->Porcentaje);

		$this->db->update('configporcensubtipo');
		return 1;
	}




	public function delete()
	{
		$this->db->where('IdConfigS', $this->IdConfigS);
		$this->db->where('IdTipoS', $this->IdTipoS);
		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function get_configprcensubtipo()
	{
		$this->db->select('*');
		$this->db->from('configporcensubtipo');

		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}
		if (!empty($this->IdTipoS)) {
			$this->db->where('IdTipoS', $this->IdTipoS);
		}
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}
		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
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
			$obj = new Mconfigporcensubtipo();
			return [
				'status' => false,
				'data' => $obj
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('configporcensubtipo');


		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}
		if (!empty($this->IdTipoS)) {
			$this->db->where('IdTipoS', $this->IdTipoS);
		}
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}
		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}

		//Pagination
		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}
}
