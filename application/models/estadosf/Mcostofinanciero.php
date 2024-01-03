<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcostofinanciero extends BaseModel
{
	// Properties
	public $IdCostoFinanciero;
	public $Anio;
	public $IdSucursal;
	public $Tipo;
	public $Descripcion;
	public $NumeroCuenta;
	public $AnioAnterior;
	public $PrimerT;
	public $SegundoT;
	public $TercerT;
	public $CuartoT;
	public $RegEstatus;


	public function __construct()
	{
		parent::__construct();
		// Init Properties
		$this->IdCostoFinanciero = '';
		$this->Anio = '';
		$this->IdSucursal = '';
		$this->Tipo = '';
		$this->Descripcion = '';
		$this->NumeroCuenta = '';
		$this->AnioAnterior = '';
		$this->PrimerT = '';
		$this->SegundoT = '';
		$this->TercerT = '';
		$this->CuartoT = '';
	}


	public function get_recobery_costofinanciero_plangral()
	{

		$this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT, sum(CuartoT) as CuartoT');
		$this->db->from('costofinanciero');
		$this->db->where('IdSucursal', $this->IdSucursal);

		$this->db->where('Descripcion!=', 'Almacén'); 

		if (!empty($this->IdCostoFinanciero)) {
			$this->db->where('IdCostoFinanciero', $this->IdCostoFinanciero);
		}

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
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
			return [
				'status' => false,
				'data' => new Mcostofinanciero()
			];
		}
	}

	//ESTO DEBERÍA ESTAR EN EL MODELO DE FINANZAS
	public function getMonthAmount()
	{
		$this->db->select('af.MontoMes as TotalCosto');
		$this->db->from('costofinanciero cf');
		$this->db->join('actualcostof af', 'cf.IdCostoFinanciero = af.IdCostoFinanciero', 'inner');
		$this->db->where('cf.Descripcion', 'Almacén');
		$this->db->where('cf.Anio', $this->Anio);
		$this->db->where('af.Mes', $this->Mes);
		$this->db->where('cf.IdSucursal', $this->IdSucursal);

		//Pagination
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalCosto;
			
		} else {

			return 0;
		}
	}

	//ESTO DEBERÍA ESTAR EN EL MODELO DE FINANZAS
	public function getYearAmount()
	{
		$this->db->select('SUM(af.MontoMes) as TotalAnual');
		$this->db->from('costofinanciero cf');
		$this->db->join('actualcostof af', 'cf.IdCostoFinanciero = af.IdCostoFinanciero', 'inner');
		$this->db->where('cf.Descripcion', 'Almacén');
		$this->db->where('cf.Anio', $this->Anio);
		$this->db->where('cf.IdSucursal', $this->IdSucursal);

		if ($this->Mes != '') {
			$where = '(af.Mes>=\'' . '1'. '\' and af.Mes<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
		}

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalAnual;

		} else {

			return 0;
		}
	}

	public function getLastYearAmount()
	{
		$this->db->select('SUM(af.MontoMes) as TotalAnualPasado');
		$this->db->from('costofinanciero cf');
		$this->db->join('actualcostof af', 'cf.IdCostoFinanciero = af.IdCostoFinanciero', 'inner');
		$this->db->where('cf.Descripcion', 'Almacén');
		$this->db->where('cf.IdSucursal', $this->IdSucursal);
		$this->db->where('cf.Anio', $this->Anio - 1);

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalAnualPasado;
		} else {
			
			return 0;
		}
	}
}
