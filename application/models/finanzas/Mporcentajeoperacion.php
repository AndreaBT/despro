<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mporcentajeoperacion extends BaseModel
{
	// Properties
	public $IdPorcentajeOperacion = '';
	public $IdTipoSer = '';
	public $IdPlanFactura = '';
	public $Anio = '';
	public $IdSucursal = '';
	public $Descripcion = '';
	public $AnioAnterior = 0;
	public $PorcentajeAnterior = 0;
	public $PorcentajeAnual = 0;
	public $PrimerT = 0;
	public $SegundoT = 0;
	public $TercerT = 0;
	public $CuartoT = 0;
	public $IdSubtipoServ = '';
	public $IdConceptoOperacion = '';
	public $PlanMensual = 0;

	//IdTipoServ Es IdConfigServ
	//IdSubtipo Es IdTipoServicio

	public function __construct()
	{
		parent::__construct();

		// Init Properties

	}

	public function insert()
	{

		$this->db->set('IdTipoSer', $this->IdTipoSer);
		$this->db->set('IdPlanFactura', $this->IdPlanFactura);
		$this->db->set('Anio', $this->Anio);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Descripcion', $this->Descripcion);
		$this->db->set('AnioAnterior', $this->AnioAnterior);
		$this->db->set('PorcentajeAnterior', $this->PorcentajeAnterior);
		$this->db->set('PorcentajeAnual', $this->PorcentajeAnual);
		$this->db->set('PrimerT', $this->PrimerT);
		$this->db->set('SegundoT', $this->SegundoT);
		$this->db->set('TercerT', $this->TercerT);
		$this->db->set('CuartoT', $this->CuartoT);
		$this->db->set('IdSubtipoServ', $this->IdSubtipoServ);
		$this->db->set('IdConceptoOperacion', $this->IdConceptoOperacion);
		$this->db->insert('porcentajeoperacion');
		return 1;
	}

	public function update()
	{
		$this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
		$this->db->where('IdTipoSer', $this->IdTipoSer);
		$this->db->where('Anio', $this->Anio);
		$this->db->where('Descripcion', $this->Descripcion);
		$this->db->set('AnioAnterior', $this->AnioAnterior);
		$this->db->set('PorcentajeAnterior', $this->PorcentajeAnterior);
		$this->db->set('PorcentajeAnual', $this->PorcentajeAnual);
		$this->db->set('PrimerT', $this->PrimerT);
		$this->db->set('SegundoT', $this->SegundoT);
		$this->db->set('TercerT', $this->TercerT);
		$this->db->set('CuartoT', $this->CuartoT);
		$this->db->update('porcentajeoperacion');

		return true;
	}


	public function get_porcentajeopearcion()
	{
		$this->db->select('*');
		$this->db->from('porcentajeoperacion');

		if (!empty($this->Descripcion)) {
			$this->db->where('Descripcion', $this->Descripcion);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}
		if (!empty($this->IdPlanFactura)) {
			$this->db->where('IdPlanFactura', $this->IdPlanFactura);
		}
		if (!empty($this->IdPorcentajeOperacion)) {
			$this->db->where('IdPorcentajeOperacion', $this->IdPorcentajeOperacion);
		}
		if (!empty($this->IdTipoSer)) {
			$this->db->where('IdTipoSer', $this->IdTipoSer);
		}
		if (!empty($this->IdSubtipoServ)) {
			$this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
		}
		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		# echo $result = $this->db->get_compiled_select();
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
				'data' => new Mporcentajeoperacion()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('*');
		$this->db->from('porcentajeoperacion');
		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->Descripcion)) {
			$this->db->where('Descripcion', $this->Descripcion);
		}

		if (!empty($this->IdTipoSer)) {
			$this->db->where('IdTipoSer', $this->IdTipoSer);
		}
		if (!empty($this->IdSubtipoServ)) {
			$this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
		}

		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}

		# echo $result = $this->db->get_compiled_select();
		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_recobery_porcentajeoperacion()
	{
		$this->db->select('*');
		$this->db->from('porcentajeoperacion');

		if (!empty($this->Descripcion)) {
			$this->db->where('Descripcion', $this->Descripcion);
		}
		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}
		if (!empty($this->IdPlanFactura)) {
			$this->db->where('IdPlanFactura', $this->IdPlanFactura);
		}
		if (!empty($this->IdPorcentajeOperacion)) {
			$this->db->where('IdPorcentajeOperacion', $this->IdPorcentajeOperacion);
		}
		if (!empty($this->IdTipoSer)) {
			$this->db->where('IdTipoSer', $this->IdTipoSer);
		}
		if (!empty($this->IdSubtipoServ)) {
			$this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
		}
		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}
		//echo $this->db->get_compiled_select();

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
				'data' => new Mporcentajeoperacion()
			];
		}
	}

	public function get_recobery_porcentajeoperacionEstadoFinanciero()
	{
		$this->db->select('*');
		$this->db->from('porcentajeoperacion');

		if (!empty($this->IdSucursal)) {
			$this->db->where('IdSucursal', $this->IdSucursal);
		}
		if (!empty($this->IdPlanFactura)) {
			$this->db->where('IdPlanFactura', $this->IdPlanFactura);
		}
		if (!empty($this->IdPorcentajeOperacion)) {
			$this->db->where('IdPorcentajeOperacion', $this->IdPorcentajeOperacion);
		}
		if (!empty($this->IdTipoSer)) {
			$this->db->where('IdTipoSer', $this->IdTipoSer);
		}
		if (!empty($this->IdSubtipoServ)) {
			$this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
		}
		if (!empty($this->Anio)) {
			$this->db->where('Anio', $this->Anio);
		}

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
				'data' => new Mporcentajeoperacion()
			];
		}
	}
}
