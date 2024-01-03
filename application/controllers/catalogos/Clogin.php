<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Clogin extends REST_Controller
{
	public $RutaFoto;

	public function __construct()
	{
		$this->RutaFoto='assets/files/foto_trabajador/';
		$this->RutaFotoE='assets/files/logo_empresa/';

		parent::__construct();

		$this->load->model('Msucursal');
		$this->load->model('Musuarios');
		$this->load->model('Mclientes');
		$this->load->model('Mempresa');
		$this->load->model('Mtrabajador');
		$this->load->model('Mrol');
		$this->load->model('Mperfil');
		$this->load->model('Mpaquetexsucursal');
		$this->load->model('Mpaquete');
		$this->load->model('Mconfiguracion');
		$this->load->model('Mubicaciont');
		$this->load->model('Mubicacionv');

		$this->load->library('UploadFile');
	}

	public function Login_post()
	{
		$v = new Valitron\Validator([
			'Usuario' => $this->post('Usuario'),
			'Contraseña' => $this->post('Contrasenia'),
		]);

		$v->rule('required', [
			'Usuario',
			'Contraseña',
		])->message('El campo {field} es requerido.');

		if($v->validate())
		{
			$objUsuarios = new Musuarios();
			$objUsuarios->Usuario = $this->post('Usuario');
			$objUsuarios->Contrasenia = $this->post('Contrasenia');

			$response = $objUsuarios->get_usuario_login();

			if($response['status'])
			{
				#Dato del perfil
				$Perfil='';
				$Perfil=$this->getNamePerfil($response['data']);
				#Fin datos perfil

				// OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
				$IdSuc = $response['data']->IdSucursal;

				$oMconfiguracion = new Mconfiguracion();
				$oMconfiguracion->RegEstatus = 'A';
				$oMconfiguracion->IdSucursal = $IdSuc;
				$datConfig = $oMconfiguracion->get_concepto();

				$Zona = 'America/Mexico_City';
				if($datConfig['status']){
					$Zona = $datConfig['data']->ZonaHoraria;
				}

				$IdCliente=$response['data']->IdCliente;
				$jwtToken = $this->verification->LoginToken($response['data']->IdUsuario,$response['data']->Nombre,$response['data']->IdPerfil,date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa,$IdCliente,$Perfil,$Zona);

				$rescli['data']=null;
				if($response['data']->IdCliente>0){
					$oMclientes=new Mclientes();
					$oMclientes->IdCliente=$IdCliente;
					$rescli=$oMclientes->get_clientes();
				}

				$oMempresa= new Mempresa();
				$oMempresa->IdEmpresa=$response['data']->IdEmpresa;
				$dataEmpresa= $oMempresa->get_empresa();

				if($response['data']->Foto !='' && empty($response['data']->Foto2))
				{
					$nombre = date("YmdHis").quitarCaracteres($response['data']->Nombre);
					$ruta='assets/files/foto_trabajador';
					$rutafinal = CrearRutaEmpSuc($ruta,$response['data']->IdEmpresa,$response['data']->IdSucursal,'');

					if ($rutafinal=='')
					{//si ya existe la ruta
						$rutafinal =$ruta.'/'.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal;
					}

					$nombre = Base64ToPathFix($response['data']->Foto,$nombre,$rutafinal);//convierte el base 64 en archivo imagen

					$objUsuarios->Foto2 = $nombre;
					$objUsuarios->IdUsuario = $response['data']->IdUsuario;
					$objUsuarios->FechaMod= date("Y-m-d H:i:s");
					$objUsuarios->update_Foto();
					$response['data']->Foto2=$nombre;
					//Actualizamos al trabajdor
					$oMtrabajador = new Mtrabajador();
					$oMtrabajador->IdUsuario=$response['data']->IdUsuario;
					$datatrab= $oMtrabajador->get_trabajadoruser();

					if($datatrab['status'])
					{
						if($datatrab['data']->Foto2=='')
						{
							$oMtrabajador->IdTrabajador=$datatrab['data']->IdTrabajador;
							$oMtrabajador->Foto2 =$nombre;
							$oMtrabajador->FechaMod = date("Y-m-d H:i:s");
							$oMtrabajador->update_foto();
						}
					}
				}

				$opaquetexsucursal=new Mpaquetexsucursal();
				$opaquetexsucursal->IdSucursal=$response['data']->IdSucursal;
				$rowpaquete=$opaquetexsucursal->get_list();

				$arrayPaquete=array();
				foreach($rowpaquete as $itempaquete)
				{
					$oMpaquete=new Mpaquete();
					$oMpaquete->IdSucursal=$response['data']->IdSucursal;
					$oMpaquete->Tipo='SubMenu';
					$oMpaquete->Asociado=$itempaquete->IdPaquete;
					$rowpaquetesub=$oMpaquete->get_list();
					foreach($rowpaquetesub as $itemsub){
						$arrayPaquete[]=array(
							'IdPaquete'=>$itemsub->IdPaquete,
							'Paquete'=>trim($itemsub->Nombre),
						);
					}
				}

				$response['data']->Perfil=$Perfil;
				$response['data']->listaPaquetes=$arrayPaquete;
				$data['Zona'] = $Zona;
				$data['usuario'] = $response['data'];
				$data['token'] = $jwtToken;
				$data['ruta'] = base_url().$this->RutaFoto.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal.'/';
				$data['cliente'] = $rescli['data'];

				if($dataEmpresa['status'])
				{
					//actualziamos el logo de la empresa
					if ($dataEmpresa['data']->Imagen !='' && $dataEmpresa['data']->Logo =='' )
					{
						$nombre = quitarCaracteres($dataEmpresa['data']->Nombre);
						$rutafinal='assets/files/logo_empresa';

						$nombre= Base64ToPathFix($dataEmpresa['data']->Imagen,$nombre,$rutafinal);//convierte el base 64 en archivo imagen

						$oMempresa->IdEmpresa=$dataEmpresa['data']->IdEmpresa;
						$oMempresa->Logo=$nombre;
						$oMempresa->FechaMod= date("Y-m-d H:i:s");
						$oMempresa->update_logo();
						$dataEmpresa['data']->Logo=$nombre;
					}

					$data['empresa'] = $dataEmpresa['data'];
				}
				else
				{
					$data['empresa'] = '';
				}
				$data['rutaE'] = base_url().$this->RutaFotoE;

				return $this->set_response([
					'status' => true,
					'data' => $data,
					'message' => 'Logueado',
				], REST_Controller::HTTP_OK);
			}
			else
			{
				return $this->set_response([
					'status' => false,
					'message' => $response['message'],
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		}
		else
		{
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function LoginRoot_post()
	{
		$v = new Valitron\Validator([
			'IdUsuario' => $this->post('IdUsuario')
		]);

		$v->rule('required', [
			'IdUsuario'
		])->message('El campo {field} es requerido.');

		if($v->validate()){

			$objUsuarios = new Musuarios();
			$objUsuarios->IdUsuario = $this->post('IdUsuario');
			$response = $objUsuarios->get_usuario_root();

			if($response['status']){
				#Dato del perfil
				$Perfil='';
				$Perfil=$this->getNamePerfil($response['data']);
				#Fin datos perfil

				// OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
				$IdSuc = $response['data']->IdSucursal;

				$oMconfiguracion = new Mconfiguracion();
				$oMconfiguracion->RegEstatus = 'A';
				$oMconfiguracion->IdSucursal = $IdSuc;
				$datConfig = $oMconfiguracion->get_concepto();

				$Zona = 'America/Mexico_City';
				if($datConfig['status']){
					$Zona = $datConfig['data']->ZonaHoraria;
				}

				$IdCliente=$response['data']->IdCliente;
				$jwtToken = $this->verification->LoginToken($response['data']->IdUsuario,$response['data']->Nombre,$response['data']->IdPerfil,date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa,$IdCliente,$Perfil,$Zona);

				$rescli['data']=null;
				if($response['data']->IdCliente>0){
					$oMclientes=new Mclientes();
					$oMclientes->IdCliente=$IdCliente;
					$rescli=$oMclientes->get_clientes();
				}

				//Cambios de lista de paquetes
				$opaquetexsucursal=new Mpaquetexsucursal();
				$opaquetexsucursal->IdSucursal=$response['data']->IdSucursal;
				$rowpaquete=$opaquetexsucursal->get_list();

				$arrayPaquete=array();
				foreach($rowpaquete as $itempaquete){
					$oMpaquete=new Mpaquete();
					$oMpaquete->IdSucursal=$response['data']->IdSucursal;
					$oMpaquete->Tipo='SubMenu';
					$oMpaquete->Asociado=$itempaquete->IdPaquete;
					$rowpaquetesub=$oMpaquete->get_list();
					foreach($rowpaquetesub as $itemsub){
						$arrayPaquete[]=array(
							'IdPaquete'=>$itemsub->IdPaquete,
							'Paquete'=>trim($itemsub->Nombre),
						);
					}
				}
				//Fin de lista de paquetes
				$response['data']->listaPaquetes=$arrayPaquete;

				$oMempresa= new Mempresa();
				$oMempresa->IdEmpresa=$response['data']->IdEmpresa;
				$dataEmpresa= $oMempresa->get_empresa();

				$response['data']->Perfil=$Perfil;
				$data['Zona'] = $Zona;
				$data['usuario'] = $response['data'];
				$data['token'] = $jwtToken;
				$data['ruta'] = base_url().$this->RutaFoto.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal.'/';
				$data['cliente'] = $rescli['data'];
				if ($dataEmpresa['status'])
				{
					$data['empresa'] = $dataEmpresa['data'];
				}
				else
				{
					$data['empresa'] = '';
				}
				$data['rutaE'] = base_url().$this->RutaFotoE;

				return $this->set_response([
					'status' => true,
					'data' => $data,
					'message' => 'Logueado',
				], REST_Controller::HTTP_OK);

			}else{

				return $this->set_response([
					'status' => false,
					'message' => $response['message'],
				], REST_Controller::HTTP_BAD_REQUEST);
			}

		}
		else{

			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	private function getNamePerfil($objUsuario){
		$Perfil='';

		if($objUsuario->IdPerfil2>0){#Buscamos en la nueva tabla
			$oMperfil=new Mperfil();
			$oMperfil->IdPerfil=$objUsuario->IdPerfil2;
			$responsePerfil=$oMperfil->get_recovery();
			if($responsePerfil['status']){
				$Perfil=$responsePerfil['data']->Busqueda;
			}
		}

		if($Perfil==''){#Buscamos en la tabala antigua
			if($objUsuario->IdPerfil>0){
				$oMrol=new Mrol();
				$oMrol->IdRol=$objUsuario->IdPerfil;
				$responsePerfil=$oMrol->get_recovery();
				if($responsePerfil['status']){
					$Perfil=$responsePerfil['data']->Nombre;
				}
			}
		}

		return $Perfil;
	}


	// LOGIN DEL APP DESPROSOFT
	public function Loginapp_post()
	{
		$v = new Valitron\Validator([
			'Usuario' 		=> trim($this->post('Usuario')),
			'Contrasenia' 	=> trim($this->post('Contrasenia')),
		]);

		$v->rule('required', [
			'Usuario',
			'Contrasenia',
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			$objUsuarios = new Musuarios();

			$objUsuarios->Usuario 		= trim($this->post('Usuario'));
			$objUsuarios->Contrasenia 	= trim($this->post('Contrasenia'));

			$response = $objUsuarios->get_usuario_loginApp();

			if ($response['status'])
			{
				// OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
				$IdSuc = $response['data']->IdSucursal;

				$oMconfiguracion = new Mconfiguracion();
				$oMconfiguracion->RegEstatus = 'A';
				$oMconfiguracion->IdSucursal = $IdSuc;
				$datConfig = $oMconfiguracion->get_concepto();

				$Zona = 'America/Mexico_City';
				if($datConfig['status']){
					$Zona = $datConfig['data']->ZonaHoraria;
				}

				$oMtrabajador = new Mtrabajador();
				$oMtrabajador->IdUsuario = $response['data']->IdUsuario;
				$response2 = $oMtrabajador->get_trabajadoruser();
				$IdTrabajador = 0;
				$IdPerfil = 0;

				if ($response2['status']) {
					if (empty($response['data']->IdPerfil)) {
						$IdPerfil =  $response2['data']->IdPerfil;
					} else {
						if ($response2['data']->Perfil == 'Usuario APP') {
							$IdPerfil = 4;
						}
					}
				}

				if ($response2['status'] && $IdPerfil == 4) {

					$IdTrabajador = $response2['data']->IdTrabajador;

					$Perfil = '';
					$Perfil = $this->getNamePerfil($response['data']);
					$jwtToken = $this->verification->LoginToken($response['data']->IdUsuario, $response['data']->Nombre, $response['data']->IdPerfil, date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa, 0, $Perfil,$Zona);

					/*
					$data['usuario'] = $response['data'];
					$data['token'] = $jwtToken;
					$data['ruta'] = base_url();
					*/

					$Mubicaciont = new Mubicaciont();
					$Mubicaciont->IdTrabajador = $IdTrabajador;
					$obj = $Mubicaciont->get_ubicacionexist();

					if ($obj['status']) { //modifica
						$Mubicaciont = new Mubicaciont();
						$Mubicaciont->IdTrabajador = $IdTrabajador;
						$Mubicaciont->update_estatus();
					} else { //inserta
						$Mubicaciont = new Mubicaciont();
						$Mubicaciont->IdTrabajador = $IdTrabajador;
						$Mubicaciont->Estatus = 'CERROSESION';
						$Mubicaciont->insert_estatus_lo();
					}

					$response['data']->Password = '';
					return $this->set_response([
						'status' => true,
						'IdTrabajador' => $IdTrabajador,
						'token' => $jwtToken,
						'ruta' => base_url(),
						'usuario' => $response['data'],
						'message' => 'Logueado',
					], REST_Controller::HTTP_OK);

				} else {
					return $this->set_response([
						'status' => false,
						'message' => 'El usuario no es un tecnico',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {

				return $this->set_response([
					'status' => false,
					'message' => $response['message'],
				], REST_Controller::HTTP_BAD_REQUEST);
			}

		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	// LOGIN DE LA APP SALES
	public function LoginappCrm_post()
	{
		$v = new Valitron\Validator([
			'Usuario' => $this->post('Usuario'),
			'Contrasenia' => $this->post('Contrasenia'),
		]);

		$v->rule('required', [
			'Usuario',
			'Contrasenia',
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			$objUsuarios = new Musuarios();

			$objUsuarios->Usuario = $this->post('Usuario');
			$objUsuarios->Contrasenia = $this->post('Contrasenia');

			$response = $objUsuarios->get_usuario_login();

			if ($response['status'])
			{
				// OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
				$IdSuc = $response['data']->IdSucursal;

				$oMconfiguracion = new Mconfiguracion();
				$oMconfiguracion->RegEstatus = 'A';
				$oMconfiguracion->IdSucursal = $IdSuc;
				$datConfig = $oMconfiguracion->get_concepto();

				$Zona = 'America/Mexico_City';
				if($datConfig['status']){
					$Zona = $datConfig['data']->ZonaHoraria;
				}

				$oMtrabajador = new Mtrabajador();
				$oMtrabajador->IdUsuario = $response['data']->IdUsuario;
				$response2 = $oMtrabajador->get_trabajadoruser();
				$IdTrabajador = 0;
				$IdPerfil = 0;

				if ($response2['status']) {
					if (empty($response['data']->IdPerfil)) {
						$IdPerfil =  $response2['data']->IdPerfil;
					} else {
						if ($response2['data']->Perfil == 'Vendedor' || $response2['data']->Perfil == 'Gerente de ventas') {
							$IdPerfil = 5;
						}
					}
				}

				if ($response2['status'] && $IdPerfil == 5) {

					$IdTrabajador = $response2['data']->IdTrabajador;

					$Perfil = '';
					$Perfil = $this->getNamePerfil($response['data']);
					$jwtToken = $this->verification->LoginToken($response['data']->IdUsuario, $response['data']->Nombre, $response['data']->IdPerfil, date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa, 0, $Perfil,$Zona);
					/*
				$data['usuario'] = $response['data'];
				$data['token'] = $jwtToken;
				$data['ruta'] = base_url();
				*/

					$Mubicacionv = new Mubicacionv();
					$Mubicacionv->IdTrabajador = $IdTrabajador;
					$obj = $Mubicacionv->get_ubicacionexist();

					if ($obj['status']) { //modifica
						$Mubicacionv = new Mubicacionv();
						$Mubicacionv->IdTrabajador = $IdTrabajador;
						$Mubicacionv->update_estatus();
					} else { //inserta
						$Mubicacionv = new Mubicacionv();
						$Mubicacionv->IdTrabajador = $IdTrabajador;
						$Mubicacionv->Estatus = 'CERROSESION';
						$Mubicacionv->insert_estatus_lo();
					}

					$response['data']->Password = '';
					return $this->set_response([
						'status' => true,
						'usuario' => $response['data'],
						'token' => $jwtToken,
						'IdTrabajador' => $IdTrabajador,
						'ruta' => base_url(),
						'message' => 'Logueado',
					], REST_Controller::HTTP_OK);
				} else {
					return $this->set_response([
						'status' => false,
						'message' => 'El usuario no es un vendedor',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {

				return $this->set_response([
					'status' => false,
					'message' => $response['message'],
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {

			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
?>
