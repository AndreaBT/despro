<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mseguimientocliente extends BaseModel
{
	// Properties
	public $IdSeguimientoCliente = 0;
	public $IdSucursal;
	public $IdTrabajador;
	public $IdClienteSucursal;
	public $IdProceso;
	public $Actividad;
	public $Cliente;
	public $Contacto;
	public $FechaReg;
	public $Fecha;
	public $HoraDuracion;
	public $Comentarios;
	public $MontoP;
	public $MontoPropuesta;
	public $IdConfigS;
	public $Anio;
	public $IdOportunidad;
	public $HoraInicio;
	public $HoraFin;
	public $Estatus;
	public $IdTipoProceso;
	public $FechaMod;
	public $Archivo;

	public $Nombre;
	public $FechaI;
	public $FechaF;


	public $IdEmpresa;

	public function __construct()
	{
		parent::__construct();
	}

	public function agregar()
	{
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('IdTrabajador', $this->IdTrabajador);
		$this->db->set('IdClienteSucursal',  $this->IdClienteSucursal);
		$this->db->set('IdProceso', $this->IdProceso);
		$this->db->set('Actividad', $this->Actividad);
		$this->db->set('Cliente', '');
		$this->db->set('Contacto', '');
		$this->db->set('FechaReg', $this->FechaReg);
		$this->db->set('Fecha', $this->Fecha);
		$this->db->set('HoraDuracion', $this->HoraDuracion);
		$this->db->set('Comentarios', $this->Comentarios);
		$this->db->set('MontoP', $this->MontoP);
		$this->db->set('MontoPropuesta', $this->MontoPropuesta);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('Anio', $this->Anio);
		$this->db->set('IdOportunidad', $this->IdOportunidad);
		$this->db->set('HoraInicio', $this->HoraInicio);
		$this->db->set('HoraFin', $this->HoraFin);
		$this->db->set('Estatus', $this->Estatus);
		$this->db->set('IdTipoProceso', $this->IdTipoProceso);
		$this->db->set('Archivo', $this->Archivo);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->insert('seguimientocliente');

		return $this->db->insert_id();
	}

	public function Update()
	{
		$this->db->where('IdSeguimientoCliente', $this->IdSeguimientoCliente);
		$this->db->set('IdTrabajador', $this->IdTrabajador);
		$this->db->set('IdClienteSucursal',  $this->IdClienteSucursal);
		$this->db->set('IdProceso', $this->IdProceso);
		$this->db->set('Actividad', $this->Actividad);
		$this->db->set('Cliente', $this->Cliente);
		$this->db->set('Contacto', $this->Contacto);
		$this->db->set('Fecha', $this->Fecha);
		$this->db->set('HoraDuracion', $this->HoraDuracion);
		$this->db->set('Comentarios', $this->Comentarios);
		$this->db->set('MontoPropuesta', $this->MontoPropuesta);
		$this->db->set('MontoP', $this->MontoP);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('IdOportunidad', $this->IdOportunidad);
		$this->db->set('HoraInicio', $this->HoraInicio);
		$this->db->set('HoraFin', $this->HoraFin);
		$this->db->set('Estatus', $this->Estatus);
		$this->db->set('IdTipoProceso', $this->IdTipoProceso);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Archivo', $this->Archivo);
		$this->db->Update('seguimientocliente');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function delete()
	{
		$this->db->where('IdSeguimientoCliente', $this->IdSeguimientoCliente);
		$this->db->set('Estatus', 'B');

		$this->db->Update('seguimientocliente');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function get_recovery()
	{
		$this->db->select('sc.*,cs.Nombre  as ClienteSucursal, o.Nombre as Oportunidad ,cp.Nombre  as Proceso,cp.Color,concat_ws(" ",sc.Fecha,sc.HoraInicio )as "start",concat_ws(" ",sc.Fecha,sc.HoraFin )as "end"');
		$this->db->from('seguimientocliente sc');
		$this->db->join('clientesucursal cs', ' sc.IdClienteSucursal=cs.IdClienteS', 'inner');
		$this->db->join('oportunidades o', ' sc.IdOportunidad=o.IdOportunidad ', 'inner');
		$this->db->join('crmproceso cp', ' sc.IdTipoProceso=cp.IdTipoProceso ', 'inner');
		$this->db->where('sc.IdSeguimientoCliente', $this->IdSeguimientoCliente);
		$this->db->group_by('sc.IdSeguimientoCliente');
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
	public function get_list()
	{
		$this->db->select('sc.*,cs.Nombre  as ClienteSucursal,cs.Telefono , cs.Correo, o.Nombre as Oportunidad ,cp.Nombre  as Proceso,cp.Color,concat_ws(" ",sc.Fecha,sc.HoraInicio )as "start",concat_ws(" ",sc.Fecha,sc.HoraFin )as "end"');
		$this->db->from('seguimientocliente sc');
		$this->db->join('clientesucursal cs', ' sc.IdClienteSucursal=cs.IdClienteS', 'inner');
		$this->db->join('oportunidades o', ' sc.IdOportunidad=o.IdOportunidad ', 'inner');
		$this->db->join('crmproceso cp', ' sc.IdProceso=cp.IdCrmProceso ', 'inner');

		//IdProceso
		//cp.ColorLetra

		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.Estatus !=', 'B');

		if (!empty($this->Fecha)) {
			$this->db->where('sc.Fecha ', $this->Fecha);
		}
		// Filters

		if (!empty($this->Actividad)) {
			$this->db->like('sc.Actividad ', $this->Actividad);
		}
		if (!empty($this->IdTrabajador)) {
			$this->db->like('sc.IdTrabajador', $this->IdTrabajador);
		}

		$this->db->group_by('sc.IdSeguimientoCliente');
		//Pagination
		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_list_Obj()
	{
		$this->db->select('sc.*,cs.Nombre  as ClienteSucursal,cs.Telefono , cs.Correo, o.Nombre as Oportunidad ,cp.Nombre  as Proceso,cp.Color,concat_ws(" ",sc.Fecha,sc.HoraInicio )as "start",concat_ws(" ",sc.Fecha,sc.HoraFin )as "end"');
		$this->db->from('seguimientocliente sc');
		$this->db->join('clientesucursal cs', ' sc.IdClienteSucursal=cs.IdClienteS', 'inner');
		$this->db->join('oportunidades o', ' sc.IdOportunidad=o.IdOportunidad ', 'inner');
		$this->db->join('crmproceso cp', ' sc.IdProceso=cp.IdCrmProceso ', 'inner');

		//IdProceso
		//cp.ColorLetra

		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.Estatus !=', 'B');

		if (!empty($this->Fecha)) {
			$this->db->where('sc.Fecha ', $this->Fecha);
		}
		// Filters

		if (!empty($this->Actividad)) {
			$this->db->like('sc.Actividad ', $this->Actividad);
		}
		if (!empty($this->IdTrabajador)) {
			$this->db->like('sc.IdTrabajador', $this->IdTrabajador);
		}

		$this->db->group_by('sc.IdSeguimientoCliente');
		//Pagination
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



	public function get_listpipedrive()
	{
		$this->db->select('sc.Anio,sc.IdSeguimientoCliente, sc.Actividad,sc.HoraInicio,sc.HoraFin,sc.Comentarios,sc.MontoP ,sc.MontoPropuesta , o.Nombre as Oportunidad,sc.Fecha, sc.IdConfigS, o.IdOportunidad,sc.IdClienteSucursal,cs.Nombre as NombreCliente');
		$this->db->from('seguimientocliente sc');
		$this->db->join('clientesucursal cs', ' sc.IdClienteSucursal=cs.IdClienteS', 'inner');
		$this->db->join('oportunidades o', ' sc.IdOportunidad=o.IdOportunidad ', 'inner');
		$this->db->join('crmproceso cp', ' sc.IdTipoProceso=cp.IdTipoProceso ', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.Estatus !=', 'B');
		$this->db->where('o.RegEstatus ', 'A');
		// Filters


		$this->db->where('o.IdVendedor ', $this->IdTrabajador);
		$this->db->where('sc.IdProceso', $this->IdProceso);

		if (!empty($this->IdClienteSucursal)) {
			$this->db->where('o.IdClienteS ', $this->IdClienteSucursal);
		}
		if (!empty($this->IdOportunidad)) {
			$this->db->where('o.IdOportunidad ', $this->IdOportunidad);
		}

		if (!empty($this->IdConfigS)) {
			$this->db->where('sc.IdConfigS', $this->IdConfigS);
		}
		if (!empty($this->Fecha)) {
			$this->db->where('sc.Fecha', $this->Fecha);
		}
		if (!empty($this->Anio)) {
			$this->db->where('sc.Anio', $this->Anio);
		}
		$this->db->group_by('sc.IdSeguimientoCliente');

		//Pagination
		// $this->set_pagination();
		# echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_list_grafica1($Tipo = 1)
	{
		if ($Tipo == 1) {
			$this->db->select('sum(sc.MontoP) as Vendido ');
		} else if ($Tipo == 2) {
			$this->db->select('Count(*) as Total');
		}
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');

		if ($this->IdConfigS > 0) {
			$this->db->where('sc.IdConfigS', $this->IdConfigS);
		}

		$this->db->where('sc.IdSucursal', $this->IdSucursal);

		if (!empty($this->IdTipoProceso)) {
			$this->db->where('cp.IdTipoProceso', $this->IdTipoProceso);
		}

		if (!empty($this->IdProceso)) {
			$this->db->where('cp.IdCrmProceso', $this->IdProceso);
		}

		if (!empty($this->Estatus)) {
			$this->db->where('sc.Estatus', $this->Estatus);
		}
		if (!empty($this->Nombre)) {
			$this->db->where('cp.Nombre', $this->Nombre);
		}

		if (!empty($this->Anio)) {
			$this->db->where('sc.Anio ', $this->Anio);
		}
		if (!empty($this->IdTrabajador)) {
			$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		}

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}
		// Filters
		//echo $result = $this->db->get_compiled_select();
		/**/
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

	public function get_list_ubiaciones()
	{
		$this->db->select(' cs.Nombre,cs.Latitud as lat,cs.Longitud as lng,sc.IdSeguimientoCliente,cs.IdIconoEmp,cs.Telefono');
		$this->db->from('seguimientocliente sc');
		$this->db->join('clientesucursal cs', ' sc.IdClienteSucursal=cs.IdClienteS', 'inner');
		//$this->db->join('oportunidades o',' sc.IdOportunidad=o.IdOportunidad ','inner');
		//$this->db->join('crmproceso cp',' sc.IdTipoProceso=cp.IdTipoProceso ','inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.Estatus !=', 'B');

		if (!empty($this->Fecha)) {
			$this->db->where('sc.Fecha ', $this->Fecha);
		}
		// Filters

		if (!empty($this->Actividad)) {
			$this->db->like('sc.Actividad ', $this->Actividad);
		}
		if (!empty($this->IdTrabajador)) {
			$this->db->like('sc.IdTrabajador', $this->IdTrabajador);
		}

		if ($this->HoraInicio != '') {
			$where = '(sc.HoraInicio <= \'' . $this->HoraInicio . '\' and sc.HoraFin > \'' . $this->HoraInicio . '\' )';
			$this->db->where($where);
		}

		//Pagination
		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	//////////////nuevo para gráfica metas-CRM

	public function get_oportinidades()
	{

		$this->db->select('Estatus, IdSucursal, IdTrabajador, MONTH(Fecha), IdOportunidad, IdTipoProceso');
		$this->db->from('seguimientocliente sc');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS', $this->IdConfigS);
		$this->db->where('sc.Estatus', "Abierta");
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');

		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_oportinidadesVendidas()
	{

		$this->db->select('sc.Estatus, sc.IdSucursal, sc.IdTrabajador, MONTH(sc.Fecha), sc.IdOportunidad, sc.IdTipoProceso, cp.Nombre');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS ', $this->IdConfigS);
		$this->db->where('cp.Nombre', "Cierre");
		$this->db->where('sc.Estatus', "Vendido");
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');



		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		//var_dump($response);
		return $response->result();
	}

	public function get_oportinidadesVendidasSuma()
	{

		$this->db->select('SUM(MontoP) as suma ');

		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');

		$this->db->where('sc.IdSucursal', $this->IdSucursal);

		if (!empty($this->IdConfigS)) {
			$this->db->where('sc.IdConfigS', $this->IdConfigS);
		}

		if (!empty($this->IdProceso)) {
			$this->db->where('cp.IdCrmProceso', $this->IdProceso);
		}

		if (!empty($this->Estatus)) {
			$this->db->where('sc.Estatus', $this->Estatus);
		}
		if (!empty($this->Nombre)) {
			$this->db->where('cp.Nombre', "Cierre");
		}

		if (!empty($this->Anio)) {
			$this->db->where('sc.Anio ', $this->Anio);
		}
		if (!empty($this->IdTrabajador)) {
			$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		}

		if (!empty($this->Fecha)) {
			$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');
		}

		// Filters
		#echo $result = $this->db->get_compiled_select();
		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_todo()
	{
		$this->db->select('sc.Estatus, sc.IdSucursal, sc.IdTrabajador, sc.IdOportunidad, sc.IdTipoProceso, cp.Nombre');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS', $this->IdConfigS);
		$this->db->where('cp.Nombre', "Cierre");
		$this->db->where('sc.Estatus', "Vendido");
		$this->db->where('sc.Anio ', $this->Anio);

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}
		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#var_dump($result = $this->db->get_compiled_select()); 
		$response = $this->db->get();
		return $response->result();
	}
	public function get_todo_reuniones()
	{
		$this->db->select('*, cp.Nombre');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS', $this->IdConfigS);
		$this->db->where('cp.Nombre', "Reunion de ventas");
		$this->db->where('sc.Anio ', $this->Anio);

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}
		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#var_dump($result = $this->db->get_compiled_select()); 
		$response = $this->db->get();
		return $response->result();
	}
	public function get_totalsumacrm()
	{
		$this->db->select('*');
		$this->db->from('finventas fn');
		$this->db->join('seguimientocliente sc', ' sc.IdConfigS= fn.IdConfigS', 'inner');
		$this->db->join('crmproceso cp', 'sc.IdProceso=cp.IdCrmProceso', 'inner');
		$this->db->where('fn.IdSucursal', $this->IdSucursal);
		$this->db->where('fn.IdVendedor ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS', $this->IdConfigS);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('cp.Nombre', "Cierre");
		$this->db->where('sc.Estatus', "Vendido");
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');

		$this->db->group_by("IdOportunidad");

		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		//var_dump($response);
		return $response->result();
	}

	////////////////////////CONTADORES GLOBALES PARA EL CRM
	public function get_contadorGlobalCerradas()
	{
		$this->db->select('sc.Estatus, sc.IdSucursal, sc.IdTrabajador, MONTH(sc.Fecha), sc.IdOportunidad, sc.IdTipoProceso, cp.Nombre');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('cp.Nombre', "Cierre");
		$this->db->where('sc.Estatus', "Vendido");
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');



		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		//var_dump($response);
		return $response->result();
	}

	public function get_contadorGlobalAbiertas()
	{
		$this->db->select('Estatus, IdSucursal, IdTrabajador, MONTH(Fecha), IdOportunidad, IdTipoProceso');
		$this->db->from('seguimientocliente sc');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.Estatus', "Abierta");
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');

		$this->db->group_by("IdOportunidad");


		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_sumacrmglobal()
	{
		$this->db->select('*');
		$this->db->from('finventas fn');
		$this->db->join('seguimientocliente sc', ' sc.IdConfigS= fn.IdConfigS', 'inner');
		$this->db->join('crmproceso cp', 'sc.IdProceso=cp.IdCrmProceso', 'inner');
		$this->db->where('fn.IdSucursal', $this->IdSucursal);
		$this->db->where('fn.IdVendedor ', $this->IdTrabajador);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('cp.Nombre', "Cierre");
		$this->db->where('sc.Estatus', "Vendido");
		$this->db->like('MONTH(sc.Fecha) ', $this->Fecha, 'both');

		$this->db->group_by("IdOportunidad");

		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		//var_dump($response);
		return $response->result();
	}

	public function get_actualPropAnual()
	{
		$this->db->select('SUM(MontoPropuesta) as sumaProp');
		$this->db->from('seguimientocliente sc');
		$this->db->join('(SELECT IdCrmProceso, RegEstatus from crmproceso WHERE Nombre="Propuestas" AND RegEstatus="A" ) cp', 'sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSeguimientoCliente IN(SELECT MAX(sc.IdSeguimientoCliente) from seguimientocliente sc GROUP by sc.IdClienteSucursal)');
		//$this->db->where('sc.IdSeguimientoCliente', '(select max(IdSeguimientoCliente) from seguimientocliente where IdProceso=cp.IdCrmProceso )');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('sc.IdConfigS',  $this->IdConfigS);
		$this->db->where('sc.Estatus !=', "B");
		//$this->db->where('cp.RegEstatus', "A");

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}

		$this->db->group_by("IdConfigS");

		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		return $response->result();
	}
	public function get_actualReunion()
	{
		$this->db->select('COUNT(Actividad) as TotalActi, sc.Estatus');
		$this->db->from('seguimientocliente sc');
		$this->db->join('(SELECT IdCrmProceso, RegEstatus from crmproceso WHERE Nombre="Reunion de ventas" AND RegEstatus="A" ) cp', 'sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('sc.IdConfigS',  $this->IdConfigS);
		$this->db->where('sc.Estatus !=', "B");

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}

		$this->db->group_by("IdConfigS");

		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		return $response->result();
	}

	public function get_llamadas()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdConfigS', $this->IdConfigS);
		$this->db->where('cp.Nombre', "Llamada en frio");
		$this->db->where('sc.Estatus !=', "B");
		$this->db->where('sc.Anio ', $this->Anio);

		if (!empty($this->Fecha)) {
			$this->db->like('sc.Fecha ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('sc.Fecha >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('sc.Fecha <', $this->FechaF);
		}


		$this->set_pagination();
		#var_dump($result = $this->db->get_compiled_select()); 
		$response = $this->db->get();
		return $response->result();
	}

	//////////////////////////PIPE DRIVE 
	public function get_prospectar()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
		$this->db->where('cp.Nombre', "Prospectar");
		if (!empty($this->IdOportunidad)) {
			$this->db->where('sc.IdOportunidad', $this->IdOportunidad);
		}
		$this->db->where('sc.Estatus !=', "B");
		$this->db->where('sc.Anio ', $this->Anio);

		/*if (!empty($this->Fecha)) {
            $this->db->like('sc.Fecha ', $this->Fecha);
        }
        if (!empty($this->FechaI)) {
            $this->db->where('sc.Fecha >', $this->FechaI);
        }
        if (!empty($this->FechaF)) {
            $this->db->where('sc.Fecha <', $this->FechaF);
        }*/


		$this->set_pagination();
		#var_dump($result = $this->db->get_compiled_select()); 
		$response = $this->db->get();
		return $response->result();
	}

	public function get_llamadasPipeDrive()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
		$this->db->where('cp.Nombre', "Llamada en frio");
		if (!empty($this->IdOportunidad)) {
			$this->db->where('sc.IdOportunidad', $this->IdOportunidad);
		}
		$this->db->where('sc.Estatus !=', "B");
		$this->db->where('sc.Anio ', $this->Anio);

		/*if (!empty($this->Fecha)) {
            $this->db->like('sc.Fecha ', $this->Fecha);
        }
        if (!empty($this->FechaI)) {
            $this->db->where('sc.Fecha >', $this->FechaI);
        }
        if (!empty($this->FechaF)) {
            $this->db->where('sc.Fecha <', $this->FechaF);
        }*/


		$this->set_pagination();
		#var_dump($result = $this->db->get_compiled_select()); 
		$response = $this->db->get();
		return $response->result();
	}

	public function get_reunionesPipeDrive()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
		$this->db->where('cp.Nombre', "Reunion de ventas");
		$this->db->where('sc.Estatus !=', "B");
		if (!empty($this->IdOportunidad)) {
			$this->db->where('sc.IdOportunidad', $this->IdOportunidad);
		}
		/*if (!empty($this->Fecha)) {
            $this->db->like('sc.Fecha ', $this->Fecha);
        }
        if (!empty($this->FechaI)) {
            $this->db->where('sc.Fecha >', $this->FechaI);
        }
        if (!empty($this->FechaF)) {
            $this->db->where('sc.Fecha <', $this->FechaF);
        }*/


		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		return $response->result();
	}

	public function get_PropuestaPipeDrive()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);
		$this->db->where('cp.Nombre', "Propuestas");
		$this->db->where('sc.Estatus !=', "B");
		//$this->db->where('sc.IdOportunidad', $this->IdOportunidad);

		if (!empty($this->IdOportunidad)) {
			$this->db->where('sc.IdOportunidad', $this->IdOportunidad);
		}

		/*if (!empty($this->Fecha)) {
            $this->db->like('sc.Fecha ', $this->Fecha);
        }
        if (!empty($this->FechaI)) {
            $this->db->where('sc.Fecha >', $this->FechaI);
        }
        if (!empty($this->FechaF)) {
            $this->db->where('sc.Fecha <', $this->FechaF);
        }*/


		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		return $response->result();
	}

	public function get_cierrePipeDrive()
	{
		$this->db->select('*');
		$this->db->from('seguimientocliente sc');
		$this->db->join('crmproceso cp', ' sc.IdProceso= cp.IdCrmProceso', 'inner');
		$this->db->where('sc.IdSucursal', $this->IdSucursal);
		$this->db->where('sc.IdTrabajador ', $this->IdTrabajador);
		$this->db->where('cp.Nombre', "Cierre");
		// $this->db->where('sc.Estatus', "Vendido");
		$this->db->where('sc.Anio ', $this->Anio);
		$this->db->where('sc.Estatus !=', "B");
		if (!empty($this->IdOportunidad)) {
			$this->db->where('sc.IdOportunidad', $this->IdOportunidad);
		}
		$this->db->where('sc.IdTipoProceso', $this->IdTipoProceso);

		/*if (!empty($this->Fecha)) {
            $this->db->like('sc.Fecha ', $this->Fecha);
        }
        if (!empty($this->FechaI)) {
            $this->db->where('sc.Fecha >', $this->FechaI);
        }
        if (!empty($this->FechaF)) {
            $this->db->where('sc.Fecha <', $this->FechaF);
        }*/



		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		//var_dump($response);
		return $response->result();
	}

	//Get de Sucursales para filtro de Pipe Drive
	public function get_Sucursales(){
		$this->db->select('*');
		$this->db->from('sucursal');
		$this->db->where('IdEmpresa', $this->IdEmpresa);

		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}
}
