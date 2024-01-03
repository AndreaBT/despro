<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtiposervicio extends BaseModel
{
	// Properties
	public $IdTipoSer;
	public $Concepto;
	public $IdSucursal;
	public $RegEstatus;
	public $GrossM;
	public $Color;
	public $ColorLetra;
	public $Ingresos;
	public $IdIcono;
	public $Tipo;
	public $IdConfigS;
	public $FechaMod;



	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdTipoSer = 0;
		$this->Concepto = '';
		$this->IdSucursal = 0;
		$this->RegEstatus = '';
		$this->GrossM = '';
		$this->Color = '';
		$this->ColorLetra = '';
		$this->Ingresos = '';
		$this->IdIcono = 0;
		$this->Tipo = '';
		$this->IdConfigS = 0;
		$this->FechaMod = '';
	}

	public function insert()
	{
		$this->db->set('Concepto', $this->Concepto);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('GrossM', $this->GrossM);
		$this->db->set('Color', $this->Color);
		$this->db->set('ColorLetra', $this->ColorLetra);
		$this->db->set('Ingresos', $this->Ingresos);
		$this->db->set('IdIcono', $this->IdIcono);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->insert('tiposervicio');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdTipoSer', $this->IdTipoSer);
		$this->db->set('Concepto', $this->Concepto);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('GrossM', $this->GrossM);
		$this->db->set('Color', $this->Color);
		$this->db->set('ColorLetra', $this->ColorLetra);
		$this->db->set('Ingresos', $this->Ingresos);
		$this->db->set('IdIcono', $this->IdIcono);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('tiposervicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdTipoSer', $this->IdTipoSer);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('tiposervicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_tiposervicio()
	{
		$this->db->select('*');
		$this->db->from('tiposervicio');
		$this->db->where('IdTipoSer', $this->IdTipoSer);

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
		$this->db->select('ts.*,cs.Nombre as TipoServicio,cs.Facturable');
		$this->db->from('tiposervicio ts');
		$this->db->join('configservicio cs', 'cs.IdConfigS=ts.IdConfigS', 'inner');
		$this->db->where('IdSucursal', $this->IdSucursal);

		// Filters
		if (!empty($this->IdTipoSer)) {
			$this->db->where('ts.IdTipoSer =', $this->IdTipoSer);
		}
		if (!empty($this->IdConfigS)) {
			$this->db->where('ts.IdConfigS =', $this->IdConfigS);
		}
		if (!empty($this->Concepto)) {
			$this->db->like('ts.Concepto', $this->Concepto);
		}
		if (!empty($this->RegEstatus)) {
			$this->db->where('ts.RegEstatus', $this->RegEstatus);
		}


		//Pagination
		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}
}
