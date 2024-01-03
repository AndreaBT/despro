<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MactualFinanzas extends BaseModel
{
	// Properties
	public $Id = '';
	public $NameId = '';
	public $Tabla = '';

	public $MontoMes = '';
	public $MontoCuenta = '';
	public $Anio = '';
	public $Mes = '';
	public $FechaCompleta = '';
	public $IdSucursal = '';
	public $IdSucursalCuentas = '';
	public $SemanaUno = '';
	public $SemanaDos = '';
	public $SemanaTres = '';
	public $SemanaCuatro = '';

	public function __construct()
	{
		parent::__construct();
		// Init Properties

	}
	public function insert()
	{
		$this->db->set('MontoMes', $this->MontoMes);
		$this->db->set('MontoCuenta', $this->MontoCuenta);
		$this->db->set('SemanaUno', $this->SemanaUno);
		$this->db->set('SemanaDos', $this->SemanaDos);
		$this->db->set('SemanaTres', $this->SemanaTres);
		$this->db->set('SemanaCuatro', $this->SemanaCuatro);
		$this->db->set('Anio', $this->Anio);
		$this->db->set('Mes', $this->Mes);
		$this->db->set('FechaCompleta', $this->FechaCompleta);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set($this->NameId, $this->Id);


		$this->db->insert($this->Tabla);
		return 1;
	}

	public function update()
	{
		$this->db->where($this->NameId, $this->Id);
		$this->db->where("Anio", $this->Anio);
		$this->db->where("Mes", $this->Mes);
		$this->db->set('MontoMes', $this->MontoMes);
		$this->db->set('MontoCuenta', $this->MontoCuenta);
		$this->db->set('SemanaUno', $this->SemanaUno);
		$this->db->set('SemanaDos', $this->SemanaDos);
		$this->db->set('SemanaTres', $this->SemanaTres);
		$this->db->set('SemanaCuatro', $this->SemanaCuatro);
		$this->db->update($this->Tabla);

		#echo $result = $this->db->get_compiled_select();

		return 1;
	}


	// public function get_costoga()
	// {
	// 	$this->db->select('*');
	// 	$this->db->from($this->Tabla);
	// 	$this->db->where('IdSucursal', $this->IdSucursal);

	// 	if (!empty($this->Anio)) {
	// 		$this->db->where('Anio', $this->Anio);
	// 	}
	// 	if (!empty($this->NameId)) {
	// 		$this->db->where($this->NameId, $this->Id);
	// 	}

	// 	if (!empty($this->Mes)) {
	// 		$this->db->where('Mes', $this->Mes);
	// 	}

	// 	#echo $result = $this->db->get_compiled_select();
	// 	$response = $this->db->get();

	// 	if ($response->num_rows() > 0) {
	// 		$data = $response->row();

	// 		return [
	// 			'status' => true,
	// 			'data' => $data
	// 		];
	// 	} else {
	// 		return [
	// 			'status' => false,
	// 			'data' => new MactualFinanzas()
	// 		];
	// 	}
	// }

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from($this->Tabla);
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}

		//Pagination
		#echo $result = $this->db->get_compiled_select();

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
	}


	public function get_TotalesAct()
	{
		$sql = 'sum(MontoMes) as Total ,(select sum(MontoMes) as Total from ' . $this->Tabla . ' where Anio=\'' . $this->Anio . '\' and IdSucursal=' . $this->IdSucursal . ' and ' . $this->NameId . '=\'' . $this->Id . '\'  and Mes =' . intval($this->Mes) . ') as TotalMes';
		$this->db->select($sql);
		$this->db->from($this->Tabla);
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		if (!empty($this->NameId)) {
			$this->db->where($this->NameId, $this->Id);
		}

		if (!empty($this->Mes)) {
			$this->db->where('Mes <= ', intval($this->Mes));
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
				'data' => new MactualFinanzas()
			];
		}
	}

	public function sumFinanceAmmount()
	{
		$this->db->where($this->NameId, $this->Id);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', $this->Mes);
		$this->db->set('MontoCuenta', $this->MontoCuenta);
		$this->db->update($this->Tabla);

		#echo $result = $this->db->get_compiled_select();

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getListTwo()
	{
		$sql = '*';
		$this->db->select($sql);
		$this->db->from($this->Tabla);
		$this->db->where($this->NameId, $this->Id);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', intval($this->Mes));

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
				'data' => new MactualFinanzas()
			];
		}
	}

	public function updateCountAmmount()
	{
		$this->db->where($this->NameId, $this->Id);
		$this->db->where("Anio", $this->Anio);
		$this->db->where("Mes", $this->Mes);
		$this->db->set('MontoCuenta', $this->MontoCuenta);
		$this->db->set('MontoMes', $this->MontoMes);
		$this->db->update($this->Tabla);
		return 1;
	}
}
