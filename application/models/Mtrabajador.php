<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtrabajador extends BaseModel
{
	// Properties
	public $IdTrabajador;
	public $Nombre;
	public $Telefono;
	public $Profesion;
	public $Categoria;
	public $CostoHora;
	public $CostoAnual;
	public $IdSucursal;
	public $Usuario;
	public $Pass;
	public $RegEstatus;
	public $Observaciones;
	public $Perfil;
	public $HorasTS;
	public $HorasPS;
	public $IdCategoria;
	public $IdRol;
	public $IdUsuario;
	public $Correo;
	public $Estatus;
	public $Token;
	public $EstadoChat;
	public $IdTipoProceso;
	public $UpdateApp;
	public $GastoAsignado;
	public $IdCajaC;
	public $Inventario;
	public $Imagen;
	public $FechaMod;
	public $Rol;
	public $Foto;
	public $IdPerfil;
	public $Foto2;
	public $Query;

	public function __construct()
	{
		parent::__construct();
		// Init Properties
		$this->IdTrabajador = 0;
		$this->Nombre = '';
		$this->Telefono = '';
		$this->Profesion = '';
		$this->Categoria = '';
		$this->CostoHora = '';
		$this->CostoAnual = '';
		$this->IdSucursal = 0;
		$this->Usuario = '';
		$this->Pass = '';
		$this->RegEstatus = '';
		$this->Observaciones = '';
		$this->Perfil = '';
		$this->HorasTS = 0;
		$this->HorasPS = 0;
		$this->IdCategoria = '';
		$this->IdRol = 0;
		$this->IdUsuario = 0;
		$this->Correo = '';
		$this->Estatus = '';
		$this->Token = '';
		$this->EstadoChat = '';
		$this->IdTipoProceso = 0;
		$this->UpdateApp = '';
		$this->GastoAsignado = '';
		$this->IdCajaC = 0;
		$this->Inventario = '';
		$this->Imagen = '';
		$this->FechaMod = '';
		$this->Rol = '';
		$this->Foto = '';
		$this->Foto2 = '';
		$this->IdPerfil = '';
		$this->Query = '';
	}

	public function insert()
	{
		//se busca el rol anterior
		$CI = &get_instance();
		$CI->load->model("Mrol");
		$CI->load->model("Mperfil");

		$Mperfil = new Mperfil();
		$Mperfil->IdPerfil = $this->IdPerfil;
		$res = $Mperfil->get_recovery();
		$IdRol = $this->IdPerfil;

		if ($res['status']) {
			$oMrol = new Mrol();
			$oMrol->Nombre = $res['data']->Nombre;
			$oMrol->IdSucursal = $this->IdSucursal;
			$rs2 = $oMrol->get_recovery();
			if ($rs2['status']) {
				$IdRol = $rs2['data']->IdRol;
			}
		}

		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Profesion', $this->Profesion);
		$this->db->set('Categoria', $this->Categoria);
		$this->db->set('CostoHora', $this->CostoHora);
		$this->db->set('CostoAnual', $this->CostoAnual);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Usuario', $this->Usuario);
		$this->db->set('Pass', $this->Pass);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('Observaciones', $this->Observaciones);
		$this->db->set('Perfil', $this->Perfil);
		$this->db->set('HorasTS', $this->HorasTS);
		$this->db->set('HorasPS', $this->HorasPS);
		$this->db->set('IdCategoria', $this->IdCategoria);
		$this->db->set('IdRol', $IdRol);
		$this->db->set('IdUsuario', $this->IdUsuario);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Estatus', $this->Estatus);
		$this->db->set('Token', $this->Token);
		$this->db->set('EstadoChat', $this->EstadoChat);
		$this->db->set('IdTipoProceso', $this->IdTipoProceso);
		$this->db->set('UpdateApp', $this->UpdateApp);
		$this->db->set('GastoAsignado', $this->GastoAsignado);
		$this->db->set('IdCajaC', $this->IdCajaC);
		$this->db->set('Inventario', $this->Inventario);
		//$this->db->set('Imagen', $this->Imagen);
		$this->db->set('Foto', $this->Foto2);
		$this->db->set('Foto2', $this->Foto2);
		$this->db->set('IdPerfil', $this->IdPerfil);

		$this->db->insert('trabajador');
		return $this->db->insert_id();
	}

	public function update()
	{
		//se busca el rol anterior
		$CI = &get_instance();
		$CI->load->model("Mrol");
		$CI->load->model("Mperfil");

		$Mperfil = new Mperfil();
		$Mperfil->IdPerfil = $this->IdPerfil;
		$res = $Mperfil->get_recovery();
		$IdRol = $this->IdPerfil;
		if ($res['status']) {
			$oMrol = new Mrol();
			$oMrol->Nombre = $res['data']->Nombre;
			$oMrol->IdSucursal = $this->IdSucursal;
			$rs2 = $oMrol->get_recovery();
			if ($rs2['status']) {
				$IdRol = $rs2['data']->IdRol;
			}
		}

		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->set('Nombre', $this->Nombre);
		$this->db->set('Telefono', $this->Telefono);
		$this->db->set('Profesion', $this->Profesion);
		$this->db->set('Categoria', $this->Categoria);
		$this->db->set('CostoHora', $this->CostoHora);
		$this->db->set('CostoAnual', $this->CostoAnual);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('Usuario', $this->Usuario);
		$this->db->set('Observaciones', $this->Observaciones);
		$this->db->set('Perfil', $this->Perfil);
		$this->db->set('HorasTS', $this->HorasTS);
		$this->db->set('HorasPS', $this->HorasPS);
		$this->db->set('IdCategoria', $this->IdCategoria);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Estatus', $this->Estatus);
		$this->db->set('Inventario', $this->Inventario);
		$this->db->set('FechaMod', $this->FechaMod);
		//$this->db->set('Imagen', $this->Imagen); 
		$this->db->set('Foto2', $this->Foto2);
		$this->db->set('IdPerfil', $this->IdPerfil);
		$this->db->set('IdRol', $IdRol);

		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function updateCredencial()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);
		$this->db->set('Correo', $this->Correo);
		$this->db->set('Usuario', $this->Usuario);
		$this->db->set('Pass', $this->Pass);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		$this->db->set('RegEstatus', 'B');
		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function update_foto()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		$this->db->set('Foto2', $this->Foto2);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function set_update_estatus_trab()
	{
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		$this->db->set('Estatus', $this->Estatus);
		$this->db->update('trabajador');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_trabajador()
	{
		$this->db->select('*');
		$this->db->from('trabajador');
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();
			$data->Pass = '';

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

	public function get_trabajadoruser()
	{
		$this->db->select('*');
		$this->db->from('trabajador');
		$this->db->where('IdUsuario', $this->IdUsuario);

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
		$this->db->select('IdTrabajador,Nombre,Telefono, Profesion,Categoria,CostoHora,CostoAnual,IdSucursal,Usuario,Pass,Perfil,HorasTS,HorasPS,IdCategoria,IdRol,Correo,Token,Foto2,IdUsuario,RegEstatus');
		$this->db->from('trabajador');
		$this->db->where('IdSucursal', $this->IdSucursal);

		// Filters
		if (!empty($this->IdTrabajador)) {
			$this->db->where('IdTrabajador !=', $this->IdTrabajador);
		}

		if (!empty($this->IdRol)) {

			if (!empty($this->IdPerfil)) {
				$where = ' (`IdRol` = ' . $this->IdRol . '  OR `IdPerfil` = ' . $this->IdPerfil . ') ';
				$this->db->where($where);
			} else {
				$this->db->where('IdRol', $this->IdRol);
			}
		} else {
			if (!empty($this->IdPerfil)) {
				$this->db->where('IdPerfil', $this->IdPerfil);
			}
		}

		if (!empty($this->Nombre)) {
			$this->db->like('Nombre', $this->Nombre);
		}

		if (!empty($this->Profesion)) {
			$this->db->like('Profesion', $this->Profesion);
		}

		if (!empty($this->RegEstatus)) {
			$this->db->where('RegEstatus', $this->RegEstatus);
		}

		//Pagination
		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_TrabajadorTkn()
	{
		$this->db->select('*');
		$this->db->from('trabajador');
		$this->db->where('IdTrabajador', $this->IdTrabajador);

		//echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();

		if ($response->num_rows() > 0) {
			$data = $response->row();
			$this->Token = $data->Token;
		} else {
			$this->Token = '';
		}
	}

	public function getEmployeeQuantity()
	{
		$this->db->select('COUNT(IdTrabajador) as Quantity');
		$this->db->from('trabajador');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('RegEstatus', 'A');

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
				'data' => new Mtrabajador()
			];
		}
	}
}
