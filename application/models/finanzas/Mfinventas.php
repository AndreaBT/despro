<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfinventas extends BaseModel
{
	// Properties
	public $IdVendedor = '';
	public $IdConfigS = '';
	public $UnoT = '';
	public $DosT = '';
	public $TresT = '';
	public $CuatroT = '';
	public $BaseContrato = '';
	public $ValorPromedio = '';
	public $PorcentajeBCierre = '';
	//NUEVOS
	public $PorcentajePrimeraR = '';
	public $PorcentajeLlamadaF = '';
	//FIN NUEVOS
	public $Anio = '';
	public $IdSucursal = '';
	public $UnoP = '0';
	public $DosP = '0';
	public $TresP = '0';
	public $CuatroP = '0';
	public $TotalAnual = '';
	public $Comision = '0';



	public function __construct()
	{
		parent::__construct();

		// Init Properties


	}

	public function Insert()
	{
		$this->db->set('IdVendedor', $this->IdVendedor);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('UnoT', $this->UnoT);
		$this->db->set('DosT', $this->DosT);
		$this->db->set('TresT', $this->TresT);
		$this->db->set('CuatroT', $this->CuatroT);
		$this->db->set('Comision', $this->Comision);
		$this->db->set('BaseContrato', $this->BaseContrato);
		$this->db->set('ValorPromedio', $this->ValorPromedio);
		$this->db->set('PorcentajeBCierre', $this->PorcentajeBCierre);
		//nuevos
		$this->db->set('PorcentajePrimeraR', $this->PorcentajePrimeraR);
		$this->db->set('PorcentajeLlamadaF', $this->PorcentajeLlamadaF);
		//nuevos
		$this->db->set('Anio', $this->Anio);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('UnoP', $this->UnoP);
		$this->db->set('DosP', $this->DosP);
		$this->db->set('TresP', $this->TresP);
		$this->db->set('CuatroP', $this->CuatroP);
		$this->db->set('TotalAnual', $this->TotalAnual);
		$this->db->insert('finventas');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdVendedor', $this->IdVendedor);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('IdConfigS', $this->IdConfigS);

		$this->db->set('UnoT', $this->UnoT);
		$this->db->set('DosT', $this->DosT);
		$this->db->set('TresT', $this->TresT);
		$this->db->set('CuatroT', $this->CuatroT);
		$this->db->set('Comision', $this->Comision);
		$this->db->set('BaseContrato', $this->BaseContrato);
		$this->db->set('ValorPromedio', $this->ValorPromedio);
		$this->db->set('PorcentajeBCierre', $this->PorcentajeBCierre);
		//nuevo
		$this->db->set('PorcentajePrimeraR', $this->PorcentajePrimeraR);
		$this->db->set('PorcentajeLlamadaF', $this->PorcentajeLlamadaF);
		//fin nuevo
		$this->db->set('UnoP', $this->UnoP);
		$this->db->set('DosP', $this->DosP);
		$this->db->set('TresP', $this->TresP);
		$this->db->set('CuatroP', $this->CuatroP);
		$this->db->set('TotalAnual', $this->TotalAnual);
		$this->db->update('finventas');
		return 1;
	}

	public function delete()
	{
		$this->db->where('IdVendedor', $this->IdVendedor);
		$this->db->delete('actividades');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function get_finventas()
	{
		$this->db->select('*');
		$this->db->from('finventas');

		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}

		if ($this->IdConfigS > 0) {
			$this->db->where('IdConfigS', $this->IdConfigS);
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
			$oMventas = new Mfinventas();
			return [
				'status' => false,
				'data' => $oMventas
			];
		}
	}
	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('finventas');

		// Filters
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}
		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	////////////////////nuevo
	public function suma_get()
	{
		$this->db->select('UnoT, DosT, TresT, CuatroT');
		$this->db->from('finventas');
		$this->db->where('Anio', $this->Anio);

		// Filters
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}
		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}


		//Pagination
		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();

			return [
				'status' => true,
				'data' => $data
			];
		} else {
			$oMventas = new Mfinventas();
			return [
				'status' => false,
				'data' => $oMventas
			];
		}
	}

	////////////suma total Anual 
	public function sumaAnual_get()
	{
		$this->db->select('SUM(TotalAnual) as sumas');
		$this->db->from('finventas');
		$this->db->where('Anio', $this->Anio);

		// Filters
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}



		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function grafCRM_get()
	{
		$this->db->select('*');
		$this->db->from('finventas');
		$this->db->where('Anio', $this->Anio);

		// Filters
		if (!empty($this->IdVendedor)) {
			$this->db->where('IdVendedor', $this->IdVendedor);
		}
		if (!empty($this->IdConfigS)) {
			$this->db->where('IdConfigS', $this->IdConfigS);
		}


		//Pagination
		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();

			return [
				'status' => true,
				'data' => $data
			];
		} else {
			$oMventas = new Mfinventas();
			return [
				'status' => false,
				'data' => $oMventas
			];
		}
	}

	public function get_Vendedores()
	{
		$this->db->select('u.IdUsuario, u.Nombre AS NombreTrabajador, u.IdPerfil as IdPerdilUsuario, p.Nombre, p.IdPerfil as IdPerfilPerfiles,u.IdPerfil2, u.IdSucursal');
		$this->db->from('usuario u');
		$this->db->join('perfil p', 'p.IdPerfil=u.IdPerfil2', 'inner');
		$this->db->where('u.Estatus', 'A');
		$where = '(u.IdPerfil2=5 or u.IdPerfil2=9)';
		$this->db->where($where);
		$this->db->having('u.IdSucursal', $this->IdSucursal);

		// Filters

		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}
}
