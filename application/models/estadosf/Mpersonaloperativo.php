<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpersonaloperativo extends BaseModel
{
	// Properties
	public $IdEmpresa = 0;
	public $IdSucursal = 0;
	public $Anio = 0;
	public $Mes = 0;
	public $Nombre = '';
	public $Sueldo = 0;
	public $Obligaciones = 0;
	public $Prestaciones = 0;
	public $ComisionesBonos = 0;
	public $Extras = 0;
	public $Descuentos = 0;
	public $Total = 0;
	public $IdProveedor;
	public function __construct()
	{
		parent::__construct();
	}

	public function Insert()
	{
		$this->db->set('IdEmpresa', $this->IdEmpresa);
		$this->db->set('IdSucursal', $this->IdSucursal);

		if (!empty($this->IdProveedor)) {
			$this->db->set('IdProveedor', $this->IdProveedor);
		}

		$this->db->set('Anio', $this->Anio);
		$this->db->set('Mes', $this->Mes);
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Sueldo', $this->Sueldo);
		$this->db->set('Obligaciones', $this->Obligaciones);
		$this->db->set('Prestaciones', $this->Prestaciones);
		$this->db->set('ComisionesBonos', $this->ComisionesBonos);
		$this->db->set('Extras', $this->Extras);
		$this->db->set('Descuentos', $this->Descuentos);
		$this->db->set('Total', $this->Total);

		$this->db->insert('personaloperativo');
		return true;
	}

	public function update()
	{
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', $this->Mes);
		$this->db->where('IdProveedor', $this->IdProveedor);

		$this->db->set('Sueldo', $this->Sueldo);
		$this->db->set('Obligaciones', $this->Obligaciones);

		$this->db->update('personaloperativo');
		return true;
	}

	public function get_recovery()
	{
		$this->db->select('po.*, p.Nombre as Proveedor');
		$this->db->from('personaloperativo po');
		$this->db->join('proveedores p', 'po.IdProveedor = p.IdProveedor', 'inner');
		$this->db->where('po.IdSucursal', $this->IdSucursal);
		$this->db->where('po.Anio', $this->Anio);
		$this->db->where('po.Mes', $this->Mes);
		$this->db->where('po.IdEmpresa', $this->IdEmpresa);

		if (!empty($this->IdProveedor)) {
			$this->db->WHERE('po.IdProveedor', $this->IdProveedor);
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
				'data' => new Mpersonaloperativo()
			];
		}
	}
	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('personaloperativo');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdProveedor IS NULL');
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', $this->Mes);
		#echo $this->db->get_compiled_select();
		//Pagination
		//  $this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function getEmpleadosCuentas()
	{
		$this->db->select('*');
		$this->db->from('personaloperativo');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdProveedor IS NOT NULL');
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', $this->Mes);
		#echo $this->db->get_compiled_select();
		//Pagination
		//  $this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}


	public function delete()
	{
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Mes', $this->Mes);
		$this->db->where('IdProveedor IS NULL');
		$this->db->delete('personaloperativo');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function sumaPersonal()
	{
		$this->db->select('SUM(Sueldo + Obligaciones + Prestaciones +ComisionesBonos + Extras - Descuentos) as Total');
		$this->db->from('personaloperativo');
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Mes', $this->Mes);
		$this->db->where('Anio', $this->Anio);

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
				'data' => new Mseguimientocliente()
			];
		}
	}

	public function sumaPersonalAnual()
	{
		$this->db->select('SUM(Sueldo + Obligaciones + Prestaciones +ComisionesBonos + Extras - Descuentos) as TotalAnual');
		$this->db->from('personaloperativo');
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Anio', $this->Anio);

		if ($this->Mes != '') {
			$where = '(Mes>=\'' . '1'. '\' and Mes<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
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
				'data' => new Mseguimientocliente()
			];
		}
	}

	public function sumaPersonalAnualPasado()
	{
		$this->db->select('SUM(Sueldo + Obligaciones + Prestaciones +ComisionesBonos + Extras - Descuentos) as TotalAnualPasado');
		$this->db->from('personaloperativo');
		$this->db->where('IdEmpresa', $this->IdEmpresa);
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Anio', $this->Anio - 1);

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
				'data' => new Mseguimientocliente()
			];
		}
	}
}
