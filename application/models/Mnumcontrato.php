<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mnumcontrato extends BaseModel
{
	// Properties
	public $IdContrato;
	public $IdClienteS;
	public $NumeroC;
	public $Nombre;
	public $RegEstatus;
	public $Comentario;

	/*
        Para el modulo de spend plan
        Este campo se agrega desde spend plan proyecto
        Si se agrega desde sucursal es 0
    */
	public $IdProyectoSpend;

	public $IdProyectoSpendDif;

	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdContrato = 0;
		$this->IdClienteS = '';
		$this->NumeroC = '';
		$this->RegEstatus = 0;
		$this->Comentario = '';
		$this->IdProyectoSpend = 0;
		$this->IdProyectoSpendDif = '';
	}

	public function insert()
	{

		$this->db->set('IdContrato', $this->IdContrato);
		$this->db->set('IdClienteS', $this->IdClienteS);
		$this->db->set('NumeroC', $this->NumeroC);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('IdProyectoSpend', $this->IdProyectoSpend);

		$this->db->insert('numcontrato');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdContrato', $this->IdContrato);
		$this->db->set('NumeroC', $this->NumeroC);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('IdProyectoSpend', $this->IdProyectoSpend);


		$this->db->update('numcontrato');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdContrato', $this->IdContrato);
		$this->db->set('RegEstatus', 'B');
		$this->db->update('numcontrato');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_spendplan()
	{
		$this->db->where('IdProyectoSpend', $this->IdProyectoSpend);
		$this->db->set('RegEstatus', 'B');
		$this->db->update('numcontrato');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_all()
	{
		$this->db->where('IdClienteS', $this->IdClienteS);
		$where = '(IdProyectoSpend=0 or IdProyectoSpend is null)';
		$this->db->where($where);
		$this->db->where('IdProyectoSpend =0');
		$this->db->set('RegEstatus', 'B');
		$this->db->update('numcontrato');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_recovery()
	{
		$this->db->select('*');
		$this->db->from('numcontrato');
		$this->db->where('IdContrato', $this->IdContrato);

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

	/*
        Para buscar proyectos de spend plan
    */
	public function get_recovery_spendplan()
	{
		$this->db->select('*');
		$this->db->from('numcontrato');
		$this->db->where('IdProyectoSpend', $this->IdProyectoSpend);

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
		$this->db->from('numcontrato');

		// Filters
		if (!empty($this->IdContrato)) {
			$this->db->where('IdContrato =', $this->IdContrato);
		}

		if (!empty($this->IdClienteS)) {
			$this->db->where('IdClienteS', $this->IdClienteS);
		}

		if (!empty($this->NumeroC)) {
			$this->db->where('NumeroC', $this->NumeroC);
		}

		if ($this->IdProyectoSpendDif != '') { //trae contrantos sin spend plan
			$where = '(IdProyectoSpend=0 or IdProyectoSpend is null)';
			$this->db->where($where);
		}

		if (!empty($this->RegEstatus)) {
			$this->db->where('RegEstatus', $this->RegEstatus);
		}

		//Pagination
		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function getContract()
	{
		$this->db->select('nc.*, cs.Nombre AS ClienteSucursal ');
		$this->db->from('numcontrato nc');
		$this->db->join('clientesucursal cs', 'cs.IdClienteS = nc.IdClienteS', 'inner');
		$this->db->where('nc.IdClienteS', $this->IdClienteS);
		$this->db->where('nc.RegEstatus', 'A');


		if (!empty($this->Nombre)) {
			$this->db->like('nc.NumeroC', $this->Nombre);
		}

		//Pagination
		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}
}
