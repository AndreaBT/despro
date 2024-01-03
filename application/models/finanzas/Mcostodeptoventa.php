<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostodeptoventa extends BaseModel
{
	// Properties
	public $IdCostoDeptoVenta = '';
	public $Anio = '';
	public $IdSucursal = '';
	public $Descripcion = '';
	public $NumeroCuenta = '';
	public $AnioAnterior = '';

	public $PrimerT = '';
	public $SegundoT = '';
	public $TercerT = '';
	public $CuartoT = '';


	public function __construct()
	{
		parent::__construct();
		// Init Properties

	}
	public function insert()
	{
		$this->db->set('Anio', $this->Anio);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Descripcion', $this->Descripcion);
		$this->db->set('NumeroCuenta', $this->NumeroCuenta);
		$this->db->set('AnioAnterior', $this->AnioAnterior);
		$this->db->set('PrimerT', $this->PrimerT);
		$this->db->set('SegundoT', $this->SegundoT);
		$this->db->set('TercerT', $this->TercerT);
		$this->db->set('CuartoT', $this->CuartoT);


		$this->db->insert('costodeptoventa');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
		$this->db->set('Descripcion', $this->Descripcion);
		$this->db->set('NumeroCuenta', $this->NumeroCuenta);
		$this->db->set('AnioAnterior', $this->AnioAnterior);
		$this->db->set('PrimerT', $this->PrimerT);
		$this->db->set('SegundoT', $this->SegundoT);
		$this->db->set('TercerT', $this->TercerT);
		$this->db->set('CuartoT', $this->CuartoT);

		$this->db->update('costodeptoventa');
		return 1;
	}


	public function get_costodeptoventa()
	{
		$this->db->select('*');
		$this->db->from('costodeptoventa');
		$this->db->where('IdSucursal', $this->IdSucursal);

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
			$obj = new Mcostodeptoventa();
			return [
				'status' => false,
				'data' => new Mcostodeptoventa()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('costodeptoventa');
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}

		#echo $result = $this->db->get_compiled_select();
		//Pagination
		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}
}
