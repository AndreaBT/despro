<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgastosdirectos extends BaseModel
{
	// Properties
	public $IdGasto = '';
	public $IdEmpresa = '';
	public $IdSucursal = '';
	public $Gasto = '';
	public $NumCuenta = '';
	public $FechaAnterior = '';
	public $MontoAnterior = '';
	public $MontoAnual = '';
	public $UnoT = '';
	public $DosT = '';
	public $TresT = '';
	public $CuatroT = '';
	public $FechaAct = '';
	public $Tipo = '';
	public $Anio = '';

	public function __construct()
	{
		parent::__construct();
		// Init Properties

	}
	public function insert()
	{
		$this->db->set('IdEmpresa', $this->IdEmpresa);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Gasto', $this->Gasto);
		$this->db->set('NumCuenta', $this->NumCuenta);
		$this->db->set('FechaAnterior', $this->FechaAnterior);
		$this->db->set('MontoAnterior', $this->MontoAnterior);
		$this->db->set('MontoAnual', $this->MontoAnual);
		$this->db->set('UnoT', $this->UnoT);
		$this->db->set('DosT', $this->DosT);
		$this->db->set('TresT', $this->TresT);
		$this->db->set('CuatroT', $this->CuatroT);
		$this->db->set('FechaAct', $this->FechaAct);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('Anio', $this->Anio);

		$this->db->insert('gastosdirectos');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdGasto', $this->IdGasto);
		$this->db->set('NumCuenta', $this->NumCuenta);
		$this->db->set('MontoAnterior', $this->MontoAnterior);
		$this->db->set('MontoAnual', $this->MontoAnual);
		$this->db->set('Gasto', $this->Gasto);
		$this->db->set('UnoT', $this->UnoT);
		$this->db->set('DosT', $this->DosT);
		$this->db->set('TresT', $this->TresT);
		$this->db->set('CuatroT', $this->CuatroT);

		$this->db->update('gastosdirectos');
		return 1;
	}


	public function get_gastosdirectos()
	{
		$this->db->select('*');
		$this->db->from('gastosdirectos');

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}

		if (!empty($this->Tipo)) {
			$this->db->where('Tipo', $this->Tipo);
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
			$obj = new Mgastosdirectos();
			return [
				'status' => false,
				'data' => new Mgastosdirectos()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('gastosdirectos');


		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}

		if (!empty($this->Tipo)) {
			$this->db->where('Tipo', $this->Tipo);
		}

		//Pagination
		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_list2()
	{
		$this->db->select('IdGasto,Gasto as Descripcion,NumCuenta as NumeroCuenta,MontoAnterior as AnioAnterior,MontoAnual,UnoT  as PrimerT,DosT as SegundoT, TresT as TercerT,CuatroT as CuartoT, Tipo,Anio');
		$this->db->from('gastosdirectos');


		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}

		if (!empty($this->Tipo)) {
			$this->db->where('Tipo', $this->Tipo);
		}

		//Pagination
		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}
}
