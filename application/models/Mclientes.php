<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mclientes extends BaseModel
{
	// Properties
	public $IdCliente;
	public $Nombre;
	public $Telefono;
	public $Direccion;
	public $Correo;
	public $Ciudad;
	public $Pais;
	public $Estado;
	public $CP;
	public $IdSucursal;
	public $RegEstatus;
	public $Contacto;
	public $Dfac;
	public $FechaMod;
	public $Tipo = "";



	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdCliente = 0;
		$this->Nombre = '';
		$this->Telefono = '';
		$this->Direccion = '';
		$this->Correo = '';
		$this->Ciudad = '';
		$this->Pais = '';
		$this->Estado = '';
		$this->CP = '';
		$this->IdSucursal = 0;
		$this->RegEstatus = '';
		$this->Contacto = '';
		$this->Dfac = '';
		$this->FechaMod = '';
	}

	public function insert()
	{
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('Pais', $this->Pais);
		$this->db->set('Estado', $this->Estado);
		$this->db->set('CP', $this->CP);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Contacto', $this->Contacto);
		$this->db->set('Dfac', $this->Dfac);
		$this->db->insert('clientes');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdCliente', $this->IdCliente);

		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('Pais', $this->Pais);
		$this->db->set('Estado', $this->Estado);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('CP', $this->CP);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Contacto', $this->Contacto);
		$this->db->set('Dfac', $this->Dfac);
		$this->db->update('clientes');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdCliente', $this->IdCliente);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('clientes');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_clientes()
	{
		$this->db->select('*');
		$this->db->from('clientes');
		$this->db->where('IdCliente', $this->IdCliente);

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
		$this->db->from('clientes');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('RegEstatus', "A");
		// Filters
		if (!empty($this->IdClientes)) {
			$this->db->where('IdClientes !=', $this->IdClientes);
		}

		if (!empty($this->Nombre)) {
			$this->db->like('Nombre', $this->Nombre);
		}

		$this->db->group_by("FIELD (Nombre,'House Account') DESC, IdCliente");

		//Pagination
		$this->set_pagination();

		//echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();
		return $response->result();
	}
	public function getListCta()
	{
		$this->db->select('*');
		$this->db->from('clientes');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('RegEstatus', "A");

		//Pagination
		$this->set_pagination();

		//echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_listcrm()
	{
		$this->db->select('c.*');
		$this->db->from('clientes c');
		$this->db->join('clientesucursal cs', 'on cs.IdCliente=c.IdCliente ', 'left');
		$this->db->where('c.IdSucursal', $this->IdSucursal);
		$this->db->where('c.RegEstatus', 'A');
		// Filters

		if (!empty($this->IdClientes)) {
			$this->db->where('c.IdClientes ', $this->IdClientes);
		}

		if (!empty($this->Tipo)) {
			$this->db->where('cs.Tipo ', $this->Tipo);
		}

		if (!empty($this->Nombre)) {
			$this->db->like('c.Nombre', $this->Nombre);
		}

		$this->db->group_by('c.IdCliente');

		//Pagination
		$this->set_pagination();

		#echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();
		return $response->result();
	}

	#Region CLIENTES MONITOREO
	public function get_list_monitoreo()
	{
		$select = "c.IdCliente,c.*,cs.CheckCli,
        CASE WHEN tb.Incidencia >0 THEN tb.Incidencia  else 0 end as Incidencia,
        CASE WHEN tb2.Chat >0 THEN tb2.Chat  else 0 end as Chat ";

		$this->db->select($select, false);
		$this->db->from('clientes c');
		$this->db->join('clientesucursal cs', 'on cs.IdCliente=c.IdCliente and cs.CheckCli=1', 'inner');
		$this->db->join('(select count(*) as Incidencia,IdCliente from equipos e where  e.Status in ("En Observacion","Fuera de Servicio") group by IdCliente ) as tb', ' tb.IdCliente=c.IdCliente', 'left');
		$this->db->join('(select count(*) as Chat,IdCliente from ticket where  Estado in ("Cliente")  group by IdCliente )as tb2', ' tb2.IdCliente=c.IdCliente', 'left');
		$this->db->where('c.IdSucursal', $this->IdSucursal);
		$this->db->where('c.RegEstatus', $this->RegEstatus);

		if (!empty($this->Nombre)) {
			$this->db->like('c.Nombre', $this->Nombre);
		}
		$this->db->order_by('c.IdCliente', 'asc');
		$this->db->group_by('c.IdCliente');

		#echo $this->db->get_compiled_select();
		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}
}
