<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mclientesucursal extends BaseModel
{
	// Properties
	public $IdClienteS;
	public $IdCliente;
	public $Nombre;
	public $Direccion;
	public $Telefono;
	public $Correo;
	public $Ciudad;
	public $IdSucursal;
	public $RegEstatus;
	public $ContactoS;
	public $Ncontrato;
	public $CheckCli;
	public $Tipo;
	public $IdVendedor;
	public $IdIconoEmp;
	public $DistanciaAprox;
	public $Comentario;
	public $Cargo;
	public $FechaMod;
	public $Latitud;
	public $Longitud;



	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdClienteS = 0;
		$this->IdCliente = '';
		$this->Nombre = '';
		$this->Direccion = '';
		$this->Telefono = '';
		$this->Correo = '';
		$this->Ciudad = '';
		$this->IdSucursal = '';
		$this->RegEstatus = '';
		$this->ContactoS = 0;
		$this->Ncontrato = '';
		$this->CheckCli = '';
		$this->Tipo = '';
		$this->IdVendedor = '';
		$this->IdIconoEmp = '';
		$this->DistanciaAprox = '';
		$this->Comentario = '';
		$this->Cargo = '';
		$this->FechaMod = '';
		$this->Latitud = '';
		$this->Longitud = '';
	}

	public function insert()
	{
		$this->db->set('IdCliente', $this->IdCliente);
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('ContactoS', $this->ContactoS);
		$this->db->set('Ncontrato', $this->Ncontrato);
		$this->db->set('CheckCli', $this->CheckCli);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('IdVendedor', $this->IdVendedor);
		$this->db->set('IdIconoEmp', $this->IdIconoEmp);
		$this->db->set('DistanciaAprox', $this->DistanciaAprox);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('Cargo', $this->Cargo);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Latitud', $this->Latitud);
		$this->db->set('Longitud', $this->Longitud);

		$this->db->insert('clientesucursal');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdClienteS', $this->IdClienteS);
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Ciudad', $this->Ciudad);
		$this->db->set('ContactoS', $this->ContactoS);
		$this->db->set('CheckCli', $this->CheckCli);
		$this->db->set('IdIconoEmp', $this->IdIconoEmp);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('DistanciaAprox', $this->DistanciaAprox);
		$this->db->set('Tipo', $this->Tipo);
		$this->db->set('Cargo', $this->Cargo);
		$this->db->set('Latitud', $this->Latitud);
		$this->db->set('Longitud', $this->Longitud);

		$this->db->update('clientesucursal');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdClienteS', $this->IdClienteS);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('clientesucursal');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_cliente()
	{
		$this->db->select('*');
		$this->db->from('clientesucursal');
		$this->db->where('IdClienteS', $this->IdClienteS);

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
		$this->db->select('cs.*,cl.Nombre as Empresa,cl.Dfac,
                    CASE WHEN tb.Incidencia >0 THEN tb.Incidencia else 0 end as Incidencia,
                    CASE WHEN tb2.TotalEquipos >0 THEN tb2.TotalEquipos else 0 end as TotalE', false);
		$this->db->from('clientesucursal cs');
		$this->db->where('cs.IdSucursal', $this->IdSucursal);
		$this->db->join('(select count(*) as Incidencia,IdClienteS from equipos e where  e.Status in ("En Observacion","Fuera de Servicio") group by IdClienteS ) as tb', ' tb.IdClienteS=cs.IdClienteS', 'left');
		$this->db->join('(select count(*) as TotalEquipos,IdClienteS from equipos e where e.RegEstatus ="A" group by IdClienteS ) as tb2', ' tb2.IdClienteS=cs.IdClienteS', 'left');
		$this->db->join('clientes cl', 'cl.IdCliente=cs.IdCliente','left');
		$this->db->where('cs.RegEstatus', "A");
		$this->db->where('cs.IdCliente =', $this->IdCliente);

		if (!empty($this->Nombre)) {
			$this->db->like('cs.Nombre', $this->Nombre);
		}

		//Pagination
		$this->set_pagination();

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function getBranchOffice()
	{
		$this->db->select('*');
		$this->db->from('clientesucursal');
		$this->db->where('IdCliente =', $this->IdCliente);
		$this->db->where('RegEstatus', "A");

		//Pagination
		$this->set_pagination();

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}
}
