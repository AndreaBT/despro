<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicio extends BaseModel
{
	// Properties
	public $IdServicio;
	public $Cliente;
	public $Tipo_Serv;
	public $Personal;
	public $Vehiculo;
	public $Fecha_I;
	public $Fecha_F;
	public $Hora_I;
	public $Hora_F;
	public $Distancia;
	public $Observaciones;
	public $Materiales;
	public $IdSucursal;
	public $RegEstatus;
	public $Direccion;
	public $Folio;
	public $IdVehiculo;
	public $IdCliente;
	public $IdClienteS;
	public $EstadoS;
	public $Velocidad;
	public $Econtacto;
	public $Contacto;
	public $EquiposD;
	public $MaterialesD;
	public $ViaticosD;
	public $ContratistasD;
	public $Comentario;
	public $BurdenTotal;
	public $ManoObraT;
	public $CostoV;
	public $ComentarioFin;
	public $IdContrato;
	public $NumContrato;
	public $Factura;
	public $EstadoFactura;
	public $FechaMod;

	public $Facturable;
	public $TipoVehiculo;
	public $FechaInicioFin;
	public $KeyReferences;
	public $NumPersonal;
	public $TiempoAcceso;
	public $TiempoSalida;
	public $TiempoEM;
	public $NumVehiculos;
	public $TiempoCapacitacion;



	//referencia a trablas externas
	public $IdTrabajador;
	public $IdConfigS = 0;
	public $TypeFinanciero = 0;

	public $Tipo = 0;
	public $FechaBusqueda;


	public function __construct()
	{
		parent::__construct();

		// Init Properties

		$this->IdServicio = 0;
		$this->Cliente = '';
		$this->Tipo_Serv = '';
		$this->Personal = '';
		$this->Vehiculo = '';
		$this->Fecha_I = '';
		$this->Fecha_F = '';
		$this->Hora_I = '';
		$this->Hora_F = '';
		$this->Distancia = '';
		$this->Observaciones = '';
		$this->Materiales = '';
		$this->IdSucursal = '';
		$this->RegEstatus = '';
		$this->Direccion = '';
		$this->Folio = '';
		$this->IdVehiculo = '';
		$this->IdCliente = '';
		$this->IdClienteS = '';
		$this->EstadoS = '';
		$this->Velocidad = '';
		$this->Econtacto = '';
		$this->Contacto = '';
		$this->EquiposD = '';
		$this->MaterialesD = '';
		$this->ViaticosD = '';
		$this->ContratistasD = '';
		$this->Comentario = '';
		$this->BurdenTotal = '';
		$this->ManoObraT = '';
		$this->CostoV = '';
		$this->ComentarioFin = '';
		$this->IdContrato = '';
		$this->NumContrato = '';
		$this->Factura = '';
		$this->EstadoFactura = '';
		$this->FechaMod = '';
		$this->Facturable = '';
		$this->TipoVehiculo = '';
		$this->FechaInicioFin = '';
		$this->KeyReferences = '';
		$this->NumPersonal = 0;
		$this->TiempoAcceso = '';
		$this->TiempoSalida = '';
		$this->TiempoEM = '';
		$this->TiempoCapacitacion = '';
		$this->NumVehiculos = '';
		$this->Tipo = 0;
		$this->FechaBusqueda = '';
	}

	public function insert()
	{
		$this->db->set('Cliente', $this->Cliente);
		$this->db->set('Tipo_Serv', $this->Tipo_Serv);
		$this->db->set('Vehiculo', $this->Vehiculo);
		$this->db->set('Personal', $this->Personal);
		$this->db->set('Fecha_I', $this->Fecha_I);
		$this->db->set('Fecha_F', $this->Fecha_F);
		$this->db->set('Hora_I', $this->Hora_I);
		$this->db->set('Hora_F', $this->Hora_F);
		$this->db->set('Distancia', $this->Distancia);
		$this->db->set('Observaciones', $this->Observaciones);
		$this->db->set('Materiales', $this->Materiales);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('Folio', $this->Folio);
		$this->db->set('IdVehiculo', $this->IdVehiculo);
		$this->db->set('IdCliente', $this->IdCliente);
		$this->db->set('IdClienteS', $this->IdClienteS);
		$this->db->set('EstadoS', $this->EstadoS);
		$this->db->set('Velocidad', $this->Velocidad);
		$this->db->set('Econtacto', $this->Econtacto);
		$this->db->set('Contacto', $this->Contacto);
		$this->db->set('EquiposD', $this->EquiposD);
		$this->db->set('MaterialesD', $this->MaterialesD);
		$this->db->set('ViaticosD', $this->ViaticosD);
		$this->db->set('ContratistasD', $this->ContratistasD);
		$this->db->set('BurdenTotal', $this->BurdenTotal);
		$this->db->set('ManoObraT', $this->ManoObraT);
		$this->db->set('CostoV', $this->CostoV);
		$this->db->set('ComentarioFin', $this->ComentarioFin);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('IdContrato', $this->IdContrato);
		$this->db->set('NumContrato', $this->NumContrato);
		$this->db->set('FechaInicioFin', $this->FechaInicioFin);
		$this->db->set('KeyReferences', $this->KeyReferences);
		$this->db->set('Factura', $this->Factura);

		$this->db->insert('servicio');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdServicio', $this->IdServicio);
		$this->db->set('Cliente', $this->Cliente);
		$this->db->set('Tipo_Serv', $this->Tipo_Serv);
		$this->db->set('Vehiculo', $this->Vehiculo);
		$this->db->set('Fecha_I', $this->Fecha_I);
		$this->db->set('Fecha_F', $this->Fecha_F);
		$this->db->set('Hora_I', $this->Hora_I);
		$this->db->set('Hora_F', $this->Hora_F);
		$this->db->set('Distancia', $this->Distancia);
		$this->db->set('Observaciones', $this->Observaciones);
		$this->db->set('Materiales', $this->Materiales);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Direccion', $this->Direccion);
		$this->db->set('IdVehiculo', $this->IdVehiculo);
		$this->db->set('Personal', $this->Personal);

		$this->db->set('IdCliente', $this->IdCliente);
		$this->db->set('IdClienteS', $this->IdClienteS);
		$this->db->set('EstadoS', $this->EstadoS);
		$this->db->set('Velocidad', $this->Velocidad);
		$this->db->set('Econtacto', $this->Econtacto);
		$this->db->set('Contacto', $this->Contacto);
		$this->db->set('EquiposD', $this->EquiposD);
		$this->db->set('MaterialesD', $this->MaterialesD);
		$this->db->set('ViaticosD', $this->ViaticosD);
		$this->db->set('ContratistasD', $this->ContratistasD);
		$this->db->set('BurdenTotal', $this->BurdenTotal);
		$this->db->set('ManoObraT', $this->ManoObraT);
		$this->db->set('CostoV', $this->CostoV);
		$this->db->set('ComentarioFin', $this->ComentarioFin);

		$this->db->set('IdContrato', $this->IdContrato);
		$this->db->set('NumContrato', $this->NumContrato);
		$this->db->set('Factura', $this->Factura);
		$this->db->set('EstadoFactura', $this->EstadoFactura);
		$this->db->set('FechaMod', $this->FechaMod);

		$this->db->update('servicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function UpdateValores()
	{
		$this->db->where('IdServicio', $this->IdServicio);
		$this->db->set('BurdenTotal', $this->BurdenTotal);
		$this->db->set('ManoObraT', $this->ManoObraT);
		$this->db->set('CostoV', $this->CostoV);

		$this->db->update('servicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdServicio', $this->IdServicio);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('servicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function updateEstatus()
	{
		$this->db->where('IdServicio', $this->IdServicio);

		$this->db->set('EstadoS', $this->EstadoS);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('servicio');

		return true;
	}

	public function personalUpdate()
	{
		$this->db->where('IdServicio', $this->IdServicio);
		$this->db->set('Personal', $this->Personal);
		$this->db->update('servicio');

		return true;
	}

	public function get_servicio()
	{
		$this->db->select('s.*,cli.Nombre as Client,cli.Dfac,cs.Nombre as Sucursal,cs.Direccion as DireccionCS,cs.Telefono as TelCS,cs.Correo as CorreoCS,nc.Comentario as ComentarioNC,fs.HoraInicio,fs.HoraFin,ts.Concepto as Servicio,
        case when KeyReferences =0 then s.IdServicio else   KeyReferences
        end  as LLave,
        ts.IdConfigS
        ', false);
		$this->db->from('servicio s');
		$this->db->join('clientes cli', 's.IdCliente=cli.IdCliente', 'inner');
		$this->db->join('clientesucursal cs', 's.IdClienteS=cs.IdClienteS', 'inner');
		$this->db->join('numcontrato nc ', 's.IdContrato=nc.IdContrato', 'left');
		$this->db->join(' tiposervicio ts ', 'ts.IdTipoSer=s.Tipo_Serv', 'inner');
		$this->db->join('fechaservicio fs ', 's.IdServicio = fs.IdServicio ', 'inner');

		$this->db->where('s.IdServicio', $this->IdServicio);

		if (!empty($this->IdTrabajador)) {
			//$this->db->where('fs.IdTrabajador', $this->IdTrabajador);
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
				'status' => false
			];
		}
	}

	public function get_servicioDate()
	{
		$this->db->select('*');
		$this->db->from('servicio s');


		$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('s.IdServicio != ', $this->IdServicio);
		$this->db->like('s.FechaMod', $this->FechaMod);
		$this->db->where('s.Personal', $this->Personal);
		$this->db->where('s.EstadoS', 'PENDIENTE');

		// echo $result = $this->db->get_compiled_select();
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

		$this->db->select('distinct(s.IdServicio), s.IdServicio,s.EstadoS,s.Cliente,s.Tipo_Serv,s.Fecha_I,s.Fecha_F,s.Distancia,s.ComentarioFin,s.Direccion,s.Folio,s.RegEstatus,DATE_FORMAT(fs.FechaInicio, "%d-%m-%Y") as FechaTrabajo,cli.Nombre as NomCli,s.Observaciones,ts.Concepto as Servicio,cs.Latitud,cs.Longitud,cs.IdIconoEmp');
		$this->db->from('servicio s');
		$this->db->join('fechaservicio fs', 's.IdServicio=fs.IdServicio', 'inner');
		$this->db->join('vehiculoservicio vs', 'vs.IdServicio=s.IdServicio', 'inner');
		$this->db->join('clientes cli', 's.IdCliente=cli.IdCliente', 'inner');
		$this->db->join('clientesucursal cs', 's.IdClienteS=cs.IdClienteS', 'inner');
		$this->db->join('tiposervicio ts', 's.Tipo_Serv=ts.IdTipoSer', 'inner');

		$this->db->where('ts.IdConfigS !=', '6');

		$this->db->where('s.IdSucursal', $this->IdSucursal);

		if (!empty($this->IdTrabajador)) {
			$this->db->where('fs.IdTrabajador', $this->IdTrabajador);
		}
		if (!empty($this->Tipo_Serv)) {
			$this->db->where('s.Tipo_Serv', $this->Tipo_Serv);
		}
		if (!empty($this->Fecha_I) && !empty($this->Fecha_F)) {
			$and = ' s.Fecha_I >=\'' . $this->Fecha_I . '\' and s.Fecha_F <= \'' . $this->Fecha_F . '\' ';
			$this->db->where($and);
		}

		if (!empty($this->EstadoS)) {
			
			if($this->EstadoS == "PENDIENTE" || $this->EstadoS == "REALIZADA"){
				$this->db->where("(s.EstadoS = 'PENDIENTE' OR s.EstadoS = 'REALIZADA')");
			}else{
				$this->db->where('s.EstadoS', $this->EstadoS);
			}
		}

		if (!empty($this->RegEstatus)) {
			$this->db->where('s.RegEstatus', $this->RegEstatus);
		}
		if (!empty($this->Folio)) {
			$where = ' (s.folio like \'%' . $this->Folio . '%\'  or s.NumContrato like \'%' . $this->Folio . '%\' or cli.Nombre like  \'%' . $this->Folio . '%\' or cs.Nombre like \'%' . $this->Folio . '%\')';
			$this->db->where($where);
		}

		$this->db->order_by('s.IdServicio', 'DESC');
		//Pagination
		$this->set_pagination();

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_recoveryDespacho()
	{
		$this->db->select('s.*,f.IdTrabajador ,f.FechaInicio,f.HoraInicio,f.HoraFin,ts.Color,s.EstadoS ');
		$this->db->from('servicio s ');
		$this->db->join('fechaservicio f ', 's.IdServicio= f.IdServicio ', 'inner');
		$this->db->join('tiposervicio ts ', 's.Tipo_Serv= ts.IdTipoSer ', 'inner');

		//$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('f.IdTrabajador', $this->IdTrabajador);
		$this->db->where('s.EstadoS !=', 'CANCELADA');
		$this->db->where('s.RegEstatus', 'A');

		if ($this->IdServicio != '') {
			$this->db->where('s.IdServicio', $this->IdServicio);
		}

		if ($this->Fecha_I != '') {
			$where = '(s.Fecha_I<=\'' . $this->Fecha_I . '\' and s.Fecha_F>=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
		}

		if ($this->Hora_I != '') {

			$where = '(f.HoraInicio<=\'' . $this->Hora_I . '\' and f.HoraFin>\'' . $this->Hora_I . '\' )';
			$this->db->where($where);
		}

		$this->db->order_by('f.HoraFin', 'desc');
		$this->db->limit(1);



		// if('3:00:00'==$this->Hora_I &&  $this->IdTrabajador==119){
		// echo $result = $this->db->get_compiled_select(); 
		//}

		//echo $result = $this->db->get_compiled_select();
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

	public function get_listDespachoApp()
	{
		$this->db->select('
        s.NumPersonal,
        s.TiempoAcceso,
        s.TiempoSalida,
        s.TiempoEM,
        s.NumVehiculos,
        s.TiempoCapacitacion,
        s.Observaciones as Tareas,
        CONCAT(v.Categoria," ",v.Modelo," ",v.Placa) as Vehicuos,
        cs.Telefono as TelCS,
        s.IdServicio,
        s.Cliente,
        s.Tipo_Serv,
        s.Fecha_I,
        s.Fecha_F,
        s.Distancia,
        s.Direccion,
        s.Folio,
        s.Folio,
        s.IdCliente,
        s.IdClienteS,
        s.Contacto,
        s.Personal,
        ds.IdTrabajador,
        ds.FechaInicio,
        ds.HoraInicio,
        ds.HoraFin,
        ts.Concepto as Servicio,
        ds.EstadoServicio,
        i.Imagen2
        , case when  t.Nombre is null then "No asignado" 
        else t.Nombre   
        end as Responsable', false);
		$this->db->from('servicio s ');
		$this->db->join('fechaservicio ds ', 's.IdServicio=ds.IdServicio ', 'inner');
		$this->db->join('tiposervicio ts ', 'ts.IdTipoSer=s.Tipo_Serv', 'inner');
		$this->db->join('iconos i ', 'ts.IdIcono=i.IdIcono', 'inner');
		$this->db->join('trabajador t ', 't.IdTrabajador=s.Personal', 'left');
		$this->db->join('vehiculoservicio vs ', 'vs.IdServicio=s.IdServicio', 'left');
		$this->db->join('clientesucursal cs', 's.IdClienteS=cs.IdClienteS', 'inner');
		$this->db->join('vehiculo v', 'vs.IdVehiculo=v.IdVehiculo', 'inner');


		$this->db->where('s.EstadoS !=', 'CANCELADA');
		$this->db->where('s.EstadoS !=', 'REALIZADA');
		$this->db->where('s.EstadoS !=', 'CERRADA');
		$this->db->where('s.RegEstatus', 'A');
		//$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('ds.IdTrabajador', $this->IdTrabajador);

		//Esto sera para validar el tipo de servicio si son los normales o de levantamiento
		//$this->db->where('ts.IdConfigS !=', '6');

		if ($this->Tipo == 1) {
			$this->db->where('ts.IdConfigS !=', '6');
		}

		if ($this->Tipo == 2) {
			$this->db->where('ts.IdConfigS', '6');
		}


		if ($this->IdServicio != '') {
			$this->db->where('s.IdServicio', $this->IdServicio);
		}

		if ($this->Fecha_I != '') {
			$this->db->where('ds.FechaInicio', $this->Fecha_I);
		}


		if ($this->Hora_I != '') {
			$this->db->where('ds.HoraInicio', $this->Hora_I);
		}

		$this->db->order_by('FechaInicio', 'DESC');
		$this->db->order_by('HoraInicio', 'ASC');
		//Pagination
		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_listEquipoTrab()
	{
		$this->db->select('t.Nombre,t.IdTrabajador,
        case when s.Personal = t.IdTrabajador then "Responsable" else "" end Responsable', false);
		$this->db->from('fechaservicio  fs');
		$this->db->join('servicio s ', ' fs.IdServicio=s.IdServicio', 'inner');
		$this->db->join('trabajador t ', 'fs.IdTrabajador=t.IdTrabajador', 'inner');
		$this->db->where('s.RegEstatus', 'A');
		$this->db->where('fs.IdServicio', $this->IdServicio);


		//Pagination
		$this->set_pagination();
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_recoveryDespachoOcupado()
	{
		$this->db->select('s.*,fs.IdTrabajador,fs.FechaInicio,fs.HoraInicio,fs.HoraFin,s.EstadoS ');
		$this->db->from('servicio s ');
		$this->db->join('fechaservicio fs ', 's.IdServicio = fs.IdServicio ', 'inner');

		//$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('s.EstadoS !=', 'CANCELADA');
		$this->db->where('s.RegEstatus', 'A');

		if ($this->IdTrabajador != '') {
			$this->db->where('fs.IdTrabajador', $this->IdTrabajador);
		}

		if ($this->IdServicio != '') {
			$this->db->where('s.IdServicio', $this->IdServicio);
		}

		if ($this->Fecha_I != '') {
			$this->db->where('fs.FechaInicio=', $this->Fecha_I);
		}

		if ($this->Hora_I != '') {
			$this->db->where('fs.HoraFin >', $this->Hora_I);
		}

		if ($this->Hora_F != '') {
			$this->db->where('fs.HoraInicio <', $this->Hora_F);
		}
		/*
        if($this->Hora_F!='')
        {
            $this->db->where('f.HoraFin',$this->Hora_F);
        }*/
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();

			return [
				'status' => true,
				'data' => $data,
				'Total' => $response->num_rows()
			];
		} else {
			return [
				'status' => false
			];
		}
	}

	public function get_recoveryVehiculoDespachoOcupado()
	{
		$this->db->select('s.*,vs.*,v.*');
		$this->db->from('servicio s ');
		$this->db->join('vehiculoservicio vs ', 's.IdServicio= vs.IdServicio ', 'inner');
		$this->db->join('vehiculo v ', 'v.IdVehiculo= vs.IdVehiculo ', 'inner');

		//$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('s.EstadoS !=', 'CANCELADA');
		//$this->db->where('v.Categoria !=','VIRTUAL');
		$this->db->where('s.RegEstatus', 'A');
		//$this->db->where('v.RegEstatus','A');
		$this->db->where('v.IdVehiculo', $this->IdVehiculo);

		if ($this->IdServicio != '') {
			$this->db->where('s.IdServicio',$this->IdServicio);
		}

		if ($this->Fecha_I != '') {

			$this->db->where('vs.FechaInicio=', $this->Fecha_I);
		}


		if ($this->Hora_I != '') {
			$this->db->where('vs.HoraFin >', $this->Hora_I);
		}

		if ($this->Hora_F != '') {
			$this->db->where('vs.HoraInicio <', $this->Hora_F);
		}
		/*
        if($this->Hora_F!='')
        {
            $this->db->where('f.HoraFin',$this->Hora_F);
        }*/
		//echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();

			return [
				'status' => true,
				'data' => $data,
				'Total' => $response->num_rows()
			];
		} else {
			return [
				'status' => false
			];
		}
	}

	public function get_listcalendar()
	{
		$this->db->select('s.IdServicio as id,s.Folio, s.Cliente as title,ts.Color as color,ts.ColorLetra,fs.FechaInicio as FechaInicio,s.Direccion as direccion,t.Nombre,s.Cliente as Cliente,fs.HoraInicio as HoraI,concat_ws(" ",fs.FechaInicio,fs.HoraInicio )as "start",concat_ws(" ",fs.FechaInicio,fs.HoraFin )as "end"');
		$this->db->from('servicio s ');
		$this->db->join('fechaservicio fs ', 's.IdServicio=fs.IdServicio ', 'inner');
		$this->db->join('tiposervicio ts ', 's.Tipo_Serv= ts.IdTipoSer', 'inner');
		$this->db->join('trabajador t ', 't.IdTrabajador=fs.IdTrabajador', 'inner');
		// $this->db->join('iconos i ','ts.IdIcono=i.IdIcono','inner');
		$this->db->where('s.EstadoS !=', 'CANCELADA');
		$this->db->where('s.RegEstatus', 'A');
		//$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('s.IdSucursal', $this->IdSucursal);

		//para monitoreo
		if ($this->IdCliente > 0) {
			$this->db->where('s.IdCliente', $this->IdCliente);
		}

		if ($this->IdClienteS > 0) {
			$this->db->where('s.IdClienteS', $this->IdClienteS);
		}

		if (!empty($this->FechaBusqueda)) {
			$this->db->where('fs.FechaInicio', $this->FechaBusqueda);
		}

		$this->db->group_by('s.IdServicio');

		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}


	public function get_listServiciosTerminados()
	{

		$this->db->select('s.IdServicio ,s.Folio,fs.HoraInicio,fs.HoraFin ,t.Nombre');
		$this->db->from('servicio s ');
		$this->db->join('fechaservicio fs ', 's.IdServicio=fs.IdServicio ', 'inner');
		//$this->db->join('tiposervicio ts ','s.Tipo_Serv= ts.IdTipoSer','inner');
		$this->db->join('trabajador t ', 't.IdTrabajador=fs.IdTrabajador', 'inner');
		// $this->db->join('iconos i ','ts.IdIcono=i.IdIcono','inner');
		$this->db->where('s.EstadoS', 'REALIZADA'); //REALIZADA-ABIERTA
		$this->db->where('s.RegEstatus', 'A');
		$this->db->where('s.IdSucursal', $this->IdSucursal);

		if (!empty($this->Fecha_I)) {
			$this->db->where('fs.FechaInicio', $this->Fecha_I);
		}


		$this->db->group_by('s.IdServicio');
		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	//DASHBOARD 

	public function get_TotalHoras()
	{

		$this->db->select('sum((TIMESTAMPDIFF(MINUTE, f.HoraInicio, f.HoraFin))/60) as TotHorasTrabajo,count(s.IdServicio) as TotalServicio');

		$this->db->from('servicio s ');
		$this->db->join('fechaservicio f ', 's.IdServicio=f.IdServicio ', 'inner');
		$this->db->join('tiposervicio ts ', 's.Tipo_Serv= ts.IdTipoSer', 'inner');


		$this->db->where('s.RegEstatus', 'A');
		$this->db->where('s.IdSucursal', $this->IdSucursal);

		if ($this->Fecha_I != '' && $this->Fecha_F != '') {
			$and = ' f.FechaInicio>=\'' . $this->Fecha_I . '\' and  f.FechaInicio<=\'' . $this->Fecha_F . '\'';
			$this->db->where($and);
		}

		if ($this->IdTrabajador != '') {
			$this->db->where('f.IdTrabajador', $this->IdTrabajador);
		}

		if ($this->EstadoS != '') {
			$this->db->where('s.EstadoS', $this->EstadoS); //REALIZADA
		}

		if ($this->Facturable != '') {
			$this->db->where('ts.Ingresos', $this->Facturable); //s | n
		}

		//echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();
			return [
				'status' => true,
				'TotHorasTrabajo' => $data->TotHorasTrabajo,
				'TotalServicio' => $data->TotalServicio,
			];
		} else {
			return [
				'status' => false
			];
		}
	}

	public function get_TotalVehiculo()
	{
		/*SELECT  v.Categoria, sum(s.Distancia*2) as TotalKm
        FROM `vehiculo` `v` 
        left JOIN `vehiculoservicio` `vs` ON `vs`.`IdVehiculo`= `v`.`IdVehiculo` 
        left JOIN `servicio` `s` ON `s`.`IdServicio`= `vs`.`IdServicio` and `s`.`RegEstatus` = 'A' and s.IdSucursal=41
        left JOIN `fechaservicio` `fs` ON `fs`.`IdServicio`= `s`.`IdServicio`

        WHERE `v`.`IdSucursal` = '41' 
        group by  v.IdVehiculo*/

		if ($this->TipoVehiculo != '') {
			$this->db->select('v.IdVehiculo, v.Categoria as label, sum(s.Distancia*v.CostoAnual) as Value');
		} else {
			$this->db->select('v.IdVehiculo, v.Categoria as label, sum(s.Distancia*2) as Value');
		}

		$this->db->from('vehiculo v ');
		$this->db->join('vehiculoservicio vs ', 'vs.IdVehiculo= v.IdVehiculo', 'left');
		$this->db->join('servicio s ', 's.IdServicio= vs.IdServicio and s.RegEstatus ="A" and s.IdSucursal=\'' . $this->IdSucursal . '\'', 'left');
		$this->db->join('fechaservicio fs ', 'fs.IdServicio=s.IdServicio ', 'left');

		$this->db->where('v.IdSucursal', $this->IdSucursal);


		if ($this->TipoVehiculo != '') {
			$this->db->where('v.TipoVehiculo', $this->TipoVehiculo);
		}


		if ($this->Fecha_I != '' && $this->Fecha_F != '') {
			$and = ' fs.FechaInicio>=\'' . $this->Fecha_I . '\' and  fs.FechaInicio<=\'' . $this->Fecha_F . '\'';
			$this->db->where($and);
		}

		$this->db->group_by('v.IdVehiculo');


		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_list_ServxHoras()
	{

		$this->db->select('s.IdServicio,s.EstadoS,ts.IdTipoSer,ts.Color as color,ts.Concepto as label,sum((TIMESTAMPDIFF(MINUTE, f.HoraInicio, f.HoraFin))/60)  as Value,count(s.IdServicio) as TotalServicio,ts.Ingresos ');


		/*
        SELECT 
        ts.IdTipoSer, 
        ts.Color as color, 
        ts.Concepto as label,
        s.*,
        sum((TIMESTAMPDIFF(MINUTE, f.HoraInicio, f.HoraFin))/60) as Value,
        count(s.IdServicio) as TotalServicio,
        ts.Ingresos  
        
        FROM servicio s 
        INNER JOIN fechaservicio f ON s.IdServicio=f.IdServicio 
        INNER JOIN tiposervicio ts ON s.Tipo_Serv= ts.IdTipoSer  
        WHERE s.IdSucursal = 41 and 
        s.RegEstatus = 'A' 
        AND f.FechaInicio >= '.$this->Fecha_I.' 
        and f.FechaInicio <= '2021-12-28' 
        and EstadoS = 'FINALIZADA' 
        GROUP BY ts.IdTipoSer

        */

		$this->db->from('servicio s ');
		$this->db->join('fechaservicio f ', 's.IdServicio=f.IdServicio ', 'inner');
		$this->db->join('tiposervicio ts ', 's.Tipo_Serv= ts.IdTipoSer', 'inner');


		$this->db->where('s.RegEstatus', 'A');
		$this->db->where('s.IdSucursal', $this->IdSucursal);

		if ($this->Fecha_I != '' && $this->Fecha_F != '') {
			$and = ' f.FechaInicio>=\'' . $this->Fecha_I . '\' and  f.FechaInicio<=\'' . $this->Fecha_F . '\'';
			$this->db->where($and);
		}


		if ($this->EstadoS != '') {
			$this->db->where('s.EstadoS', $this->EstadoS); //REALIZADA
		}

		$this->db->group_by('ts.IdTipoSer');

		//echo $result = $this->db->get_compiled_select();

		$response = $this->db->get();
		return $response->result();
	}

	#Busca todos los trabajaodres del sercicio
	public function list_trabajadorxservicio()
	{
		$this->db->select('t.Nombre as Trabajador,t.Foto2,t.IdTrabajador');
		$this->db->from('fechaservicio fs ');
		$this->db->join('trabajador t ', 't.IdTrabajador=fs.IdTrabajador', 'inner');

		$this->db->where('fs.IdServicio', $this->IdServicio);

		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	#Estados Financieros query


	public function get_costosfinancieros()
	{
		$this->db->select('sum(BurdenTotal) as BurdenT ,sum(ViaticosD) as ViaticosT,sum(ContratistasD) as ContratistaT,sum(CostoV) as CostoV,sum(ManoObraT) as ManoObraT,sum(EquiposD) as EquipoT,sum(MaterialesD) as MaterialesT');
		$this->db->from('servicio s ');
		$this->db->join('tiposervicio ts ', ' s.Tipo_Serv=ts.IdTipoSer ', 'inner');
		$this->db->join('configservicio cs ', 'ts.IdConfigS=cs.IdConfigS ', 'inner');

		$this->db->where('s.IdSucursal', $this->IdSucursal);
		$this->db->where('s.EstadoS !=', 'CANCELADA');
		$this->db->where('s.EstadoS ', 'CERRADA');
		//$this->db->where('v.Categoria !=','VIRTUAL');
		$this->db->where('s.RegEstatus', 'A');



		if ($this->IdConfigS != '') {
			$this->db->where('cs.IdConfigS=', $this->IdConfigS);
		}

		if ($this->Tipo_Serv != '') {
			$this->db->where('s.Tipo_Serv=', $this->Tipo_Serv);
		}

		if ($this->IdCliente != '') {
			$this->db->where('s.IdCliente=', $this->IdCliente);
		}

		if ($this->IdClienteS != '') {
			$this->db->where('s.IdClienteS=', $this->IdClienteS);
		}


		if ($this->TypeFinanciero == 1) {
			if ($this->Fecha_F != '') {
				//A�o-Mes
				$where = ' s.Fecha_F like \'%' . $this->Fecha_F . '%\'';
				$this->db->where($where);
			}
		}

		if ($this->TypeFinanciero == 2) {
			if ($this->Fecha_F != '') {
				//A�o-mes-dia
				$bettwn = ' s.Fecha_F between \'' . $this->Fecha_I . '\' and  \'' . $this->Fecha_F . '\'';
				$this->db->where($bettwn);
			}
		}



		# echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();

			return [
				'status' => true,
				'data' => $data,
			];
		} else {
			return [
				'status' => false
			];
		}
	}

	#Busca todos los trabajaodres del sercicio
	public function list_serviciosreferences()
	{
		$this->db->select('IdServicio,KeyReferences, 
        case when KeyReferences =0 then IdServicio else   KeyReferences
        end  as LLave', false);
		$this->db->from('servicio');

		$this->db->having('LLave', $this->IdServicio);
		$this->db->order_by('IdServicio', 'asc');
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	//Conuslta de gantt

	public function get_listGetgant($arrHorasReales, $FechaGant)
	{
		$consulta = "  t.IdUsuario, t.IdTrabajador, t.Nombre,t.Foto2 ";
		$contador = 0;
		$casos = "";

		foreach ($arrHorasReales as $element) {
			if ($element == '24:00') {
				$element = '23:59';
			}

			$casos .= " , case when hr" . $contador . ".HoraInicio is null then 0 else hr" . $contador . ".IdServicio end item" . $contador . ", 

            case when hr" . $contador . ".Folio is null then 0
            else hr" . $contador . ".Folio end Folio" . $contador . ",
            case when hr" . $contador . ".EstadoS is null then 0
            else hr" . $contador . ".EstadoS end EstadoS" . $contador . ",
            case when hr" . $contador . ".Cliente is null then 0
            else hr" . $contador . ".Cliente end Sucursal" . $contador . ",
            case when hr" . $contador . ".Nombre is null then 0
            else hr" . $contador . ".Nombre end Cliente" . $contador . ",
            case when hr" . $contador . ".Color is null then 0
            else hr" . $contador . ".Color end Color" . $contador . ",
            case when hr" . $contador . ".HoraInicio is null then 0
            else hr" . $contador . ".HoraInicio end HoraInicio" . $contador . ",
            case when hr" . $contador . ".HoraFin is null then 0
            else hr" . $contador . ".HoraFin end HoraFin" . $contador;

			$this->db->join('(select  
            fs.IdServicio,
            fs.FechaInicio,
            fs.HoraInicio,
            fs.HoraFin,
            fs.IdTrabajador,
            s.Cliente,
            s.Folio,
            s.EstadoS,
            c.Nombre,
            ts.Color 
            
            from fechaservicio fs 
            inner join servicio s on s.IdServicio=fs.IdServicio 
            inner join clientes c on s.IdCliente=c.IdCliente 
            inner join tiposervicio ts on ts.IdTipoSer=s.Tipo_Serv 
            where s.RegEstatus="A" 
            and s.EstadoS !="CANCELADA" 
            and fs.HoraInicio <= \'' . $element . '\' 
            and fs.HoraFin > \'' . $element . '\' 
            and fs.FechaInicio=\'' . date("Y-m-d", strtotime($FechaGant)) . '\' 
            group by fs.IdTrabajador) as hr' . $contador . '', ' hr' . $contador . '.IdTrabajador=t.IdTrabajador', 'left');

			$contador++;
		}

		$consulta .= $casos;

		$this->db->select($consulta, false);
		$this->db->from('trabajador t ');
		$this->db->where('t.IdSucursal', $this->IdSucursal);
		$this->db->where('t.RegEstatus', 'A');
		$this->db->where('t.Perfil', 'Usuario APP');

		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}


	//Update Servicio Levantamiento
	public function updateEstatusLevantamiento()
	{
		$this->db->where('IdServicio', $this->IdServicio);

		$this->db->set('EstadoS', $this->EstadoS);
		$this->db->set('Materiales', $this->Materiales);
		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('TiempoAcceso', $this->TiempoAcceso);
		$this->db->set('TiempoSalida', $this->TiempoSalida);
		$this->db->set('TiempoEM', $this->TiempoEM);
		$this->db->set('TiempoCapacitacion', $this->TiempoCapacitacion);
		$this->db->set('NumPersonal', $this->NumPersonal);
		$this->db->set('NumVehiculos', $this->NumVehiculos);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('servicio');

		return true;
	}

	public function getMonthAmount()
	{
		$this->db->select('SUM(MaterialesD) AS TotalServicio');
		$this->db->from('servicio');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('EstadoS', 'CERRADA');
		$this->db->where('MONTH(Fecha_F)',  $this->Mes);
		$this->db->where('YEAR(Fecha_F)',  $this->Anio);

		//Pagination
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalServicio;
		} else {
			
			return ;
		}
	}

	//ESTO DEBERÍA ESTAR EN EL MODELO DE FINANZAS
	public function getYearAmount()
	{
		$this->db->select('SUM(MaterialesD) AS TotalServicioAnual');
		$this->db->from('servicio');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('EstadoS', 'CERRADA');
		$this->db->where('YEAR(Fecha_F)',  $this->Anio);

		if ($this->Mes != '') {
			$where = '(MONTH(Fecha_F)>=\'' . '1' . '\' AND MONTH(Fecha_F)<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
		}

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalServicioAnual;
		} else {

			return 0;
		}
	}

	public function getLastYearAmount()
	{
		$this->db->select('SUM(MaterialesD) AS TotalServicioAnualPasado');
		$this->db->from('servicio');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('EstadoS', 'CERRADA');
		$this->db->where('YEAR(Fecha_F)',  $this->Anio - 1);

		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {

			return $response->row()->TotalServicioAnualPasado;

		} else {
			return 0;
		}
	}

	public function UpdateServAnulado()
	{
		$this->db->where('IdServicio', $this->IdServicio);
		$this->db->set('EstadoFactura', 'NO');
		$this->db->set('FechaMod', $this->FechaMod);

		$this->db->update('servicio');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
