<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mserviciosvalores extends BaseModel
{
	// Properties
	public $IdConfigS = '';
	public $BaseActual = '';
	public $ComisionA = '';
	public $IdSucursal = '';
	public $Anio = '';
	public $IdTipoSer = '';

	public function __construct()
	{
		parent::__construct();
		// Init Properties

	}
	public function insert()
	{
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('BaseActual', $this->BaseActual);
		$this->db->set('ComisionA', '0');
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Anio', $this->Anio);
		$this->db->set('IdTipoSer', $this->IdTipoSer);

		$this->db->insert('serviciosvalores');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdTipoSer', $this->IdTipoSer);
		$this->db->where('IdConfigS', $this->IdConfigS);
		$this->db->set('BaseActual', $this->BaseActual);
		$this->db->set('ComisionA', '0');
		$this->db->set('Anio', $this->Anio);

		$this->db->update('serviciosvalores');
		return 1;
	}


	public function get_configprcensubtipo()
	{
		$this->db->select('*');
		$this->db->from('serviciosvalores');

		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}

		if (!empty($this->IdTipoSer)) {
			$this->db->where('IdTipoSer', $this->IdTipoSer);
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
				'data' => new Mserviciosvalores()
			];
		}
	}
}
