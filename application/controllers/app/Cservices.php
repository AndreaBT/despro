<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cservices extends REST_Controller
{
	public $RutaQr;
	public $ruta = 'assets/files/firmas';
	public $RutaPdf;
	public function __construct()
	{
		parent::__construct();
		$this->RutaPdf = 'assets/files/pdf_equipo/';

		$this->load->model('Mrol');
		$this->load->model('Mperfil');
		$this->load->model('Musuarios');
		$this->load->model('Mtrabajador');
		$this->load->model('Mservicio');
		$this->load->model('Mubicaciont');
		$this->load->model('Mubicacionv');
		$this->load->model('Mservicioactivo');
		$this->load->model('Mimagenequipo2');
		$this->load->model('Mvehiculoservicio');
		$this->load->model('Mfirmas');
		$this->load->model('Mequipo');
		$this->load->model('Mequipocomentario');
		$this->load->model('Mpaquetexsucursal');

		//recursos cajachica
		$this->load->model('Masignacioncaja');
		$this->load->model('caja/Mgastostrabajador');
		$this->load->model('caja/Mimagencajachica');
		$this->load->model('caja/Masignacioncaja');

		//Recursos para chat
		$this->load->model('despacho/Mmensaje');
		$this->load->model('despacho/Mdetallemensaje');
		$this->load->model('despacho/Mubicaciont');

		//crm
		$this->load->model('Mubicacionv');
		$this->load->model('Mfechaservicio');

		setTimeZone($this->verification,$this->input);
	}



	private function getNamePerfil($objUsuario)
	{
		$Perfil = '';

		if ($objUsuario->IdPerfil2 > 0) { #Buscamos en la nueva tabla
			$oMperfil = new Mperfil();
			$oMperfil->IdPerfil = $objUsuario->IdPerfil2;
			$responsePerfil = $oMperfil->get_recovery();
			if ($responsePerfil['status']) {
				$Perfil = $responsePerfil['data']->Busqueda;
			}
		}

		if ($Perfil == '') { #Buscamos en la tabala antigua
			if ($objUsuario->IdPerfil > 0) {
				$oMrol = new Mrol();
				$oMrol->IdRol = $objUsuario->IdPerfil;
				$responsePerfil = $oMrol->get_recovery();
				if ($responsePerfil['status']) {
					$Perfil = $responsePerfil['data']->Nombre;
				}
			}
		}

		return $Perfil;
	}

	public function Listservices_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMservicio = new Mservicio();
		$oMservicio->IdSucursal = $IdSucursal;

		$oMservicio->IdTrabajador = $this->get('IdTrabajador');
		if (!empty($this->get('FechaI'))) {
			$oMservicio->Fecha_I = $this->get('FechaI');
		} else {
			$oMservicio->Fecha_I = date('Y-m-d');
		}

		if (!empty($this->get('Tipo'))) {
			$oMservicio->Tipo = $this->get('Tipo');
		} else {
			$oMservicio->Tipo = 1;
		}

		// Paginaci�n
		$rows =  $oMservicio->get_listDespachoApp();

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));

		$oMservicio->Tamano = $pager->PageSize;
		$oMservicio->Offset = $pager->Offset;
		$rows = $oMservicio->get_listDespachoApp();

		$iteracion = 0;
		foreach ($rows as $value) {

			$rows[$iteracion]->Telefono = '9992090909990';
			$rows[$iteracion]->Vehiculo = 'Camion Modelo:008 Placa:XDF3222';

			$iteracion++;
		}


		return $this->set_response([
			'status' => true,
			'Lista' => $rows,
			'pagination' => $pager,
			'ruta' => base_url() . 'assets/files/tpiconos/',
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function ListEquipoTrab_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMservicio = new Mservicio();
		$oMservicio->IdSucursal = $IdSucursal;
		$oMservicio->IdServicio = $this->get('IdServicio');

		// Paginaci�n
		$rows =  $oMservicio->get_listEquipoTrab();

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));
		$oMservicio->Tamano = $pager->PageSize;
		$oMservicio->Offset = $pager->Offset;
		$rows = $oMservicio->get_listEquipoTrab();


		return $this->set_response([
			'status' => true,
			'Lista' => $rows,
			'pagination' => $pager,
			'ruta' => base_url() . 'assets/files/tpiconos/',
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function RecoveryServ_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdTrabajador' => $this->get('IdTrabajador'),
			'IdServicio' => $this->get('IdServicio'),
		]);

		$v->rule('required', [
			'IdTrabajador',
			'IdServicio',
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {

			$Id = $this->get('IdTrabajador');

			$oMservicio = new Mservicio();
			$oMservicio->IdSucursal = $IdSucursal;
			$oMservicio->IdServicio = $this->get('IdServicio');
			$oMservicio->IdTrabajador = $Id;
			$response =  $oMservicio->get_servicio();


			if ($response['status']) {

				$oMvehiculoservicio = new Mvehiculoservicio();
				$oMvehiculoservicio->IdServicio = $response['data']->IdServicio;
				$vehiculo = $oMvehiculoservicio->get_vehiculoservicio();

				$Permiso = false;
				$oMpaquetexsucursal = new Mpaquetexsucursal();
				$oMpaquetexsucursal->IdSucursal = $IdSucursal;
				$oMpaquetexsucursal->IdPaquete = 8;
				$datapermiso = $oMpaquetexsucursal->get_paqutexsucursal();

				if ($datapermiso['status']) {
					$Permiso = true;
				}

				$oFechaServicio = new Mfechaservicio();
				$oFechaServicio->IdServicio = $response['data']->IdServicio;
				$fechasSer = $oFechaServicio->get_recovery();

				if ($fechasSer['status']) {
					$response['data']->Hora_I = substr($fechasSer['data']->HoraInicio, 0, 5);
					$response['data']->Hora_F = substr($fechasSer['data']->HoraFin, 0, 5);
				} else {

					$response['data']->Hora_I = '00:00:00';
					$response['data']->Hora_F = '00:00:00';
				}

				return $this->set_response([
					'status' => true,
					'Datos' => $response['data'],
					'datav' => $vehiculo['data'],
					'Permiso' => $Permiso,
					'message' => 'Success',
				], REST_Controller::HTTP_OK);
			} else {

				$this->set_response([
					'status' => false,
					'message' => 'No encontrado.',
				], REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function Serviciochange_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdServicio' => $this->post('IdServicio'),
			'Estatus' => $this->post('Estatus'),
			'IdTrabajador' => $this->post('IdTrabajador'),
			'EstadoS' => $this->post('EstadoS')
		]);

		$v->rule('required', [
			'IdServicio',
			'Estatus',
			'IdTrabajador',
			'EstadoS'
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdServicio',
			'IdTrabajador'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {

			$Id = (int)$this->post('IdServicio');

			$Estatus = $this->post('Estatus');

			$oMservicio = new Mservicio();
			$oMservicio->IdServicio = $Id;
			$dataser = $oMservicio->get_servicio();

			if ($dataser['status']) {

				//SE ASIGNA PERSONAL SI NO TIENE UNO ASIGANDO Y SERA EL PRIMERO QUE ATIENDA EL SERVICIO
				if ($dataser['data']->Personal == '') {
					$oMservicio = new Mservicio();
					$oMservicio->IdServicio = $Id;
					$oMservicio->Personal = $this->post('IdTrabajador');
					$oMservicio->personalUpdate();
					$dataser['data']->Personal = $this->post('IdTrabajador');
				}

				$oMservicio = new Mservicio();
				$oMservicio->FechaMod = date('Y-m-d');
				$oMservicio->IdSucursal = $IdSucursal;
				$oMservicio->IdServicio = $Id;
				$oMservicio->Personal = $this->post('IdTrabajador');
				$response = $oMservicio->get_servicioDate();

				//Si es diferente de true es decir que no encontro otro servicio y puede inicar uno nuevo
				if (!$response['status']) {

					//Compara si el que cierra o inicia el servicio es el responsable
					if ($dataser['data']->Personal == $this->post('IdTrabajador')) {
						$oMtrabajador = new Mtrabajador();
						$oMtrabajador->IdTrabajador = $this->post('IdTrabajador');
						$oMtrabajador->Estatus = $this->post('Estatus');
						$oMtrabajador->set_update_estatus_trab();

						$oMservicio = new Mservicio();
						$oMservicio->Comentario = $this->post('Comentario');
						$oMservicio->IdServicio = $Id;
						$oMservicio->EstadoS = $this->post('EstadoS');
						$oMservicio->FechaMod = date('Y-m-d H:i:s');
						$oMservicio->updateEstatus();

						$IdServUbi = $Id;

						if ($this->post('Estatus') == "Disponible") {
							$IdServUbi = "0";
						}

						$Mubicaciont = new Mubicaciont();
						$Mubicaciont->IdTrabajador = $this->post('IdTrabajador');
						$Mubicaciont->IdServicio = $IdServUbi;
						$Mubicaciont->Estatus = $this->post('Estatus');
						$Mubicaciont->update_ubicacionactual();

						/*
						$oMservicioactivo= new Mservicioactivo();
						$oMservicioactivo->IdServicio=$Id;
						$oMservicioactivo->IdTrabajador=$this->post('IdTrabajador');
						$oMservicioactivo->Fecha=$this->post('Fecha');
						$oMservicioactivo->Estado=$this->post('Estatus');
						$oMservicioactivo->set_updateservicioact();
						*/
						$oMservicio = new Mservicio();
						$oMservicio->IdServicio = $Id;
						$response = $oMservicio->get_servicio();
						$data['servicio'] = $response['data'];

						return $this->set_response([
							'status' => true,
							'data' => $data,
							'message' => 'Se ha actualizado correctamente.',
						], REST_Controller::HTTP_ACCEPTED);
					} else {
						return $this->set_response([
							'status' => false,
							'type' => 1,
							'EstatusS' => $dataser['data']->EstadoS,
							'message' => 'Solo el usuario responsable puede hacer esta acción',
						], REST_Controller::HTTP_BAD_REQUEST);
					}
				} else {
					return $this->set_response([
						'status' => false,
						'type' => 1,
						'message' => 'Termine el Servicio Iniciado para Empezar Otro',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				return $this->set_response([
					'status' => false,
					'type' => 1,
					'message' => 'Servicio no encontrado',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function CancelServicio_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdServicio' => $this->post('IdServicio'),
			'Estatus' => $this->post('Estatus'),
			'IdTrabajador' => $this->post('IdTrabajador')
		]);

		$v->rule('required', [
			'IdServicio',
			'Estatus',
			'IdTrabajador',
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdServicio',
			'IdTrabajador'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {

			$Id = (int)$this->post('IdServicio');



			$oMservicio = new Mservicio();
			$oMservicio->IdServicio = $Id;
			$dataser = $oMservicio->get_servicio();

			if ($dataser['status']) {
				//SE ASIGNA PERSONAL SI NO TIENE UNO ASIGANDO Y SERA EL PRIMERO QUE ATIENDA EL SERVICIO
				if ($dataser['data']->Personal == '') {

					$oMservicio = new Mservicio();
					$oMservicio->IdServicio = $Id;
					$oMservicio->Personal = $this->post('IdTrabajador');
					$oMservicio->personalUpdate();
					$dataser['data']->Personal = $this->post('IdTrabajador');
				}

				$oMservicio = new Mservicio();
				$oMservicio->FechaMod = date('Y-m-d');
				$oMservicio->IdSucursal = $IdSucursal;
				$oMservicio->IdServicio = $Id;
				$oMservicio->Personal = $this->post('IdTrabajador');
				$response = $oMservicio->get_servicioDate();

				//Si es diferente de true es decir que no encontro otro servicio y puede inicar uno nuevo
				if (!$response['status']) {
					//Compara si el que cierra o inicia el servicio es el responsable
					if ($dataser['data']->Personal == $this->post('IdTrabajador')) {
						$oMtrabajador = new Mtrabajador();
						$oMtrabajador->IdTrabajador = $this->post('IdTrabajador');
						$oMtrabajador->Estatus = "Disponible";
						$oMtrabajador->set_update_estatus_trab();

						$oMservicio = new Mservicio();
						$oMservicio->Comentario = $this->post('Comentario');
						$oMservicio->IdServicio = $Id;
						$oMservicio->EstadoS = "PENDIENTE";
						$oMservicio->FechaMod = date('Y-m-d H:i:s');
						$oMservicio->updateEstatus();


						$Mubicaciont = new Mubicaciont();
						$Mubicaciont->IdTrabajador = $this->post('IdTrabajador');
						$Mubicaciont->IdServicio = "0";
						$Mubicaciont->Estatus = "Disponible";
						$Mubicaciont->update_ubicacionactual();


						$oMservicio = new Mservicio();
						$oMservicio->IdServicio = $Id;
						$response = $oMservicio->get_servicio();
						$data['servicio'] = $response['data'];

						return $this->set_response([
							'status' => true,
							'data' => $data,
							'message' => 'Se ha actualizado correctamente.',
						], REST_Controller::HTTP_ACCEPTED);
					} else {
						return $this->set_response([
							'status' => false,
							'type' => 1,
							'EstatusS' => $dataser['data']->EstadoS,
							'message' => 'Solo el usuario responsable puede hacer esta acción',
						], REST_Controller::HTTP_BAD_REQUEST);
					}
				} else {
					return $this->set_response([
						'status' => false,
						'type' => 1,
						'message' => 'Termine el Servicio Iniciado para Empezar Otro',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				return $this->set_response([
					'status' => false,
					'type' => 1,
					'message' => 'Servicio no encontrado',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function firmaservicio_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$v = new Valitron\Validator([
			'IdServicio' => $this->post('IdServicio'),
			'IdCliente' => $this->post('IdCliente'),
			'IdClienteS' => $this->post('IdClienteS'),
			'Nombre' => $this->post('Nombre')
		]);

		$v->rule('required', [
			'IdServicio',
			'IdCliente',
			'IdClienteS',
			'Nombre'
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdServicio',
			'IdCliente',
			'IdClienteS'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {

			$IdServicio = (int)$this->post('IdServicio');
			$IdCliente = (int)$this->post('IdCliente');
			$IdClienteS = (int)$this->post('IdClienteS');
			$Nombre = $this->post('Nombre');
			$Firma = $this->post('Firma');

			$firmaname = 'firma' . $IdServicio;
			CrearRutaEmpSuc($this->ruta, $IdEmpresa, $IdSucursal, "");

			$rutafinal = $this->ruta . '/' . $IdEmpresa . '/' . $IdSucursal;

			$firmaname = Base64ToPathFix($Firma, $firmaname, $rutafinal); //convierte el base 64 en archivo imagen

			$oMfirmas = new Mfirmas();
			$oMfirmas->IdServicio = $IdServicio;
			$oMfirmas->IdCliente = $IdCliente;
			$oMfirmas->IdClienteS = $IdClienteS;
			$oMfirmas->Nombre = $Nombre;
			$oMfirmas->Firma2 = $firmaname;
			$oMfirmas->insert();

			return $this->set_response([
				'status' => true,
				'data' => 'Realizado',
				'message' => 'Se ha actualizado correctamente.',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();
			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function ServicioFiles_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$v = new Valitron\Validator([
			'IdEquipo' => $this->post('IdEquipo'),
			'IdServicio' => $this->post('IdServicio')
		]);

		$v->rule('required', [
			'IdEquipo',
			'IdServicio'
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdEquipo',
			'IdServicio'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {



			$oMimagenequipo2 = new Mimagenequipo2();
			$oMimagenequipo2->IdEquipo  = $this->post('IdEquipo');
			$oMimagenequipo2->IdServicio = $this->post('IdServicio');
			$rowimagnes = $oMimagenequipo2->get_list();

			//maximo de imagenes 10
			if (count($rowimagnes) < 20) {
				$Detalle = $this->post('Imagenes');

				$rutaimg = "assets/files/servicios";
				CrearRutaEmpSuc($rutaimg, $IdEmpresa, $IdSucursal, $this->post('IdServicio'));
				$rutafinal = $rutaimg . '/' . $IdEmpresa . '/' . $IdSucursal . '/' . $this->post('IdServicio');

				foreach ($Detalle as $element) {
					$Idgenerador =   substr(md5(uniqid(rand())), 0, 8);  //Generamos un nombreal azar
					$firmaname = $Idgenerador;
					$Namefoto = Base64ToPathFix($element['Imagen'], $firmaname, $rutafinal); //convierte el base 64 en archivo imagen

					$oMimagenequipo2 = new Mimagenequipo2();
					$oMimagenequipo2->IdEquipo = $this->post('IdEquipo');
					$oMimagenequipo2->Fecha = date('Y-m-d');
					$oMimagenequipo2->Descripcion = $element['Descripcion'];
					$oMimagenequipo2->IdServicio = $this->post('IdServicio');
					$oMimagenequipo2->Mostrar = 'n';
					$oMimagenequipo2->Contador = $Idgenerador;
					$oMimagenequipo2->Imagen = $Namefoto;
					$oMimagenequipo2->insert();
				}

				//aqui debemos enviar el base 64 a la ruta
				return $this->set_response([
					'status' => true,
					'data' => 'mensaje',
					'message' => 'Se agrego la foto',
				], REST_Controller::HTTP_ACCEPTED);

			} else {

				$Restantes =  20 - count($rowimagnes);
				return $this->set_response([
					'status' => false,
					'type' => 1,
					'data' => $Restantes,
					'message' => "Solo se pueden subir 10 imagenes",
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {

			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'data' => $data,
				'message' => "Faltan algunos campos",
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//getEquipos

	public function RecoveryEquipo_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdEquipo' => $this->get('IdEquipo'),
		]);
		$v->rule('required', [
			'IdEquipo',
		])->message('El campo {field} es requerido.');

		if ($v->validate()) {
			$IdEquipo = $this->get('IdEquipo');

			$oMequipo = new Mequipo();
			$oMequipo->IdEquipo = $this->get('IdEquipo');
			$response =  $oMequipo->get_equipos();


			if ($response['status']) {

				return $this->set_response([
					'status' => true,
					'equipo' => $response['data'],
					'message' => 'Success',
				], REST_Controller::HTTP_OK);
			} else {

				$this->set_response([
					'status' => false,
					'message' => 'No encontrado.',
				], REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function equipocomentario_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdEquipo' => $this->post('IdEquipo'),
			'IdServicio' => $this->post('IdServicio')
		]);

		$v->rule('required', [
			'IdEquipo',
			'IdServicio'
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdEquipo',
			'IdServicio'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {

			$IdEquipo = $this->post('IdEquipo');
			$IdServicio = $this->post('IdServicio');
			$Comentario = $this->post('Comentario');
			$oMequipocomentario = new Mequipocomentario();
			$oMequipocomentario->IdEquipo = $IdEquipo;
			$oMequipocomentario->IdServicio = $IdServicio;
			$resp = $oMequipocomentario->get_equipocomentario();

			$mensaje = "";
			if ($resp['status']) {
				$oMequipocomentario = new Mequipocomentario();
				$oMequipocomentario->IdEquipo = $IdEquipo;
				$oMequipocomentario->IdServicio = $IdServicio;
				$oMequipocomentario->Comentario = $Comentario;
				$oMequipocomentario->updateApp();
				$mensaje = "Se actualizo el registro";
			} else {
				$oMequipocomentario = new Mequipocomentario();
				$oMequipocomentario->IdEquipo = $IdEquipo;
				$oMequipocomentario->IdServicio = $IdServicio;
				$oMequipocomentario->Comentario = $Comentario;
				$oMequipocomentario->Fecha = date('Y-m-d');
				$oMequipocomentario->Permitir = 'n';
				$oMequipocomentario->insert();
				$mensaje = "Se Agrego el registro";
			}



			return $this->set_response([
				'status' => true,
				'data' => 'mensaje',
				'message' => $mensaje,
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function changestatusequipo_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdEquipo' => $this->post('IdEquipo'),
			'Status' => $this->post('Status')
		]);

		$v->rule('required', [
			'IdEquipo',
			'Status'
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdEquipo = $this->post('IdEquipo');
			$Estatus = $this->post('Status');

			$oMequipo = new Mequipo();
			$oMequipo->IdEquipo = $IdEquipo;
			$oMequipo->Status = $Estatus;
			$oMequipo->FechaMod = date('Y-m-d H:i:s');
			$oMequipo->update_statusequipo();


			return $this->set_response([
				'status' => true,
				'data' => 'mensaje',
				'message' => 'Se actualizo la informacion',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function deleteequipo_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdEquipo' => $this->post('IdEquipo'),
			'IdServicio' => $this->post('IdServicio')
		]);

		$v->rule('required', [
			'IdEquipo',
			'IdServicio'
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdEquipo = $this->post('IdEquipo');
			$IdServicio = $this->post('IdServicio');

			$oMequipocomentario = new Mequipocomentario();
			$oMequipocomentario->IdEquipo = $IdEquipo;
			$oMequipocomentario->IdServicio = $IdServicio;
			$oMequipocomentario->delete();


			return $this->set_response([
				'status' => true,
				'data' => 'mensaje',
				'message' => 'Equipo elimiado',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//Lista historial de servicios por equipo

	public function ListaHistorialEq_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$oMequipo = new Mequipo();
		$oMequipo->IdEquipo = $this->get('IdEquipo');

		// Paginaci�n
		$rows =  $oMequipo->get_list_historialapp();


		$pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));

		$oMequipo->Tamano = $pager->PageSize;
		$oMequipo->Offset = $pager->Offset;
		$rows = $oMequipo->get_list_historialapp();


		return $this->set_response([
			'status' => true,
			'Lista' => $rows,
			'ruta' => base_url() . 'assets/files/tpiconos/',
			'pagination' => $pager,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	//CAJA CHICA

	public function Listcajas_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$oMasignacioncaja = new Masignacioncaja();
		$oMasignacioncaja->IdSucursal = $IdSucursal;
		$oMasignacioncaja->Estado = 'Abierta';
		$oMasignacioncaja->IdTrabajador = $this->get('IdTrabajador');
		$oMasignacioncaja->FechaFin = date('Y-m-d');

		// Paginaci�n
		$rows =  $oMasignacioncaja->get_list();


		$pager = Pager::get_pager(count($rows), $this->get('pag'), $this->get('Entrada'));

		$oMasignacioncaja->Tamano = $pager->PageSize;
		$oMasignacioncaja->Offset = $pager->Offset;
		$rows = $oMasignacioncaja->get_list();


		return $this->set_response([
			'status' => true,
			'Lista' => $rows,
			'pagination' => $pager,
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function gastos_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];


		$Lista = $this->post('Lista');

		$TotalGatado = 0;
		$IdTrabajador = 0;
		$IdCajaC = 0;
		foreach ($Lista as $element) {
			$TotalGatado += $element['Total'];
			$IdTrabajador = $element['IdTrabajador'];
			$IdCajaC = $element['IdCajaC'];
			$Idgenerador =   substr(md5(uniqid(rand())), 0, 8);  //Generamos un nombreal azar
			$oMgastostrabajador = new Mgastostrabajador();
			$oMgastostrabajador->IdGasto = $Idgenerador;
			$oMgastostrabajador->IdTrabajador = $element['IdTrabajador'];
			$oMgastostrabajador->Concepto = $element['Concepto'];
			$oMgastostrabajador->Total = $element['Total'];
			$oMgastostrabajador->IdCajaC = $element['IdCajaC'];
			$oMgastostrabajador->Fecha = date('Y-m-d');
			$oMgastostrabajador->insert();

			$Contador = 1;
			foreach ($element['Imagenes'] as $element2) {
				$nameimage = 'comprobante' . $Idgenerador . $Contador;
				$Ruta = "assets/files/comprobantegastos";
				CrearRutaEmpSuc($Ruta, $IdEmpresa, $IdSucursal, "");

				$rutafinal = $Ruta . '/' . $IdEmpresa . '/' . $IdSucursal;

				$nameimage = Base64ToPathFix($element2['Imagen'], $nameimage, $rutafinal); //convierte el base 64 en archivo imagen

				$Mimagencajachica = new Mimagencajachica();
				$Mimagencajachica->IdConcepto = $Idgenerador;
				$Mimagencajachica->Imagen = "";
				$Mimagencajachica->IdTrabajador = $element['IdTrabajador'];
				$Mimagencajachica->Imagen2 = $nameimage;
				$Mimagencajachica->Actualizado = "s";
				$Mimagencajachica->insert();
				$Contador++;
			}
		}

		$Masignacioncaja = new Masignacioncaja();
		$Masignacioncaja->IdTrabajador = $IdTrabajador;
		$Masignacioncaja->IdCajaC = $IdCajaC;
		$objt = $Masignacioncaja->get_cajaasig();

		if ($objt['status']) {

			$MontoActual = $objt['data']->MontoActual - $TotalGatado;

			$Masignacioncaja = new Masignacioncaja();
			$Masignacioncaja->IdTrabajador = $IdTrabajador;
			$Masignacioncaja->IdCajaC = $IdCajaC;
			$Masignacioncaja->MontoActual = $MontoActual;
			$Masignacioncaja->updateResta();
		}

		return $this->set_response([
			'status' => true,
			'data' => 'Información Guardada',
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
		//aqui debemos enviar el base 64 a la ruta



	}

	//ListaChat
	public function Listcontactos_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$Tipo = 1;
		if (!empty($this->get('Tipo'))) {
			$Tipo = $this->get('Tipo');
		}

		$Perfiles = array("DESPACHADOR", "ADMIN");
		if ($Tipo == 2) {
			$Perfiles = array("Gerente de ventas", "ADMIN");
		}


		$oMrol = new Mrol();
		$oMrol->Nombre = $Perfiles; //'Usuario APP';
		$oMrol->IdSucursal = $IdSucursal;
		$orol = $oMrol->get_listName();

		$rowtrabajador = array();

		if(empty($orol)){

			$Musuarios = new Musuarios();
			$Musuarios->IdSucursal 	= $IdSucursal;
			$Musuarios->Estatus 	= 'A';
			$Musuarios->IdPerfil 	=  ($Tipo == 2) ? array(2,9) : array(2,3);
			$rowtrabajador 			= $Musuarios->getChatListUsers();


		}else {
			foreach ($orol as $element) {

				$operfil = new Mperfil();
				$operfil->Busqueda = $element->Nombre;
				$obj = $operfil->get_recovery();

				$Musuarios = new Musuarios();
				$Musuarios->IdSucursal = $IdSucursal;
				$Musuarios->RegEstatus = 'A';
				$Musuarios->IdPerfil = $element->IdRol;
				$Musuarios->IdPerfil2 = $obj['data']->IdPerfil;
				$row = $Musuarios->get_list();

				foreach ($row as $element1) {
					array_push($rowtrabajador, $element1);
				}
			}
		}

		// Paginaci�n

		return $this->set_response([
			'status' => true,
			'Lista' => $rowtrabajador,
			'url' => base_url() . 'assets/files/foto_trabajador/' . $IdEmpresa . '/' . $IdSucursal . '/',
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function ubicaciont_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdTrabajador' => $this->post('IdTrabajador'),
			'Estatus' => $this->post('Estatus'),
			'lat' => $this->post('lat'),
			'lng' => $this->post('lng')
		]);

		$v->rule('required', [
			'IdTrabajador',
			'lat',
			'lng',
			'Estatus'
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdTrabajador = $this->post('IdTrabajador');
			$Estatus = $this->post('Estatus');
			$lat = $this->post('lat');
			$lng = $this->post('lng');

			$Mubicaciont = new Mubicaciont();
			$Mubicaciont->IdTrabajador = $IdTrabajador;
			$obj = $Mubicaciont->get_ubicacionexist();

			if ($obj['status']) { //modifica
				$Mubicaciont = new Mubicaciont();
				$Mubicaciont->IdTrabajador = $IdTrabajador;
				$Mubicaciont->Lat = $lat;
				$Mubicaciont->Lng = $lng;
				$Mubicaciont->update_ubicacion();
			} else { //inserta
				$Mubicaciont = new Mubicaciont();
				$Mubicaciont->IdTrabajador = $IdTrabajador;
				$Mubicaciont->IdSucursal = $IdSucursal;
				$Mubicaciont->Lat = $lat;
				$Mubicaciont->Lng = $lng;
				$Mubicaciont->Estatus = $Estatus;
				$Mubicaciont->insert_ubicacion();
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Realizado',
				'message' => 'Realizado con exito',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//ListaChat
	public function get_Perfil_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
		$uniqueid = $this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];

		$Musuarios = new Musuarios();
		$Musuarios->IdUsuario = $uniqueid;
		$Musuarios->RegEstatus = 'A';
		$user =  $Musuarios->get_usuario();


		if ($user['status']) {
			if ($user['data']->IdPerfil2 > 0) {
				$oMperfil = new  Mperfil();
				$oMperfil->IdPerfil = $user['data']->IdPerfil2;
				$dtrol = $oMperfil->get_recovery();
			} else {
				$oMrol = new Mrol();
				$oMrol->IdSucursal = $IdSucursal;
				$oMrol->IdRol = $user['data']->IdPerfil;
				$dtrol = $orol = $oMrol->get_recovery();
			}

			$data['usuario'] = $user['data'];

			// Paginaci�n

			return $this->set_response([
				'status' => true,
				'usuario' => $user['data'],
				'rol' => $dtrol['data'],
				'url' => base_url() . 'assets/files/foto_trabajador/' . $IdEmpresa . '/' . $IdSucursal . '/',
				'message' => 'Success',
			], REST_Controller::HTTP_OK);
		} else {
			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => "No encontrado",
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function ubicacionVen_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdTrabajador' => $this->post('IdTrabajador'),
			'lat' => $this->post('lat'),
			'lng' => $this->post('lng')
		]);

		$v->rule('required', [
			'IdTrabajador',
			'lat',
			'lng',
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdTrabajador = $this->post('IdTrabajador');
			$lat = $this->post('lat');
			$lng = $this->post('lng');

			$Mubicacionv = new Mubicacionv();
			$Mubicacionv->IdTrabajador = $IdTrabajador;
			$obj = $Mubicacionv->get_ubicacionexist();

			if ($obj['status']) { //modifica
				$Mubicacionv = new Mubicacionv();
				$Mubicacionv->IdTrabajador = $IdTrabajador;
				$Mubicacionv->Lat = $lat;
				$Mubicacionv->Lng = $lng;
				$Mubicacionv->update_ubicacion();
			} else { //inserta
				$Mubicacionv = new Mubicacionv();
				$Mubicacionv->IdTrabajador = $IdTrabajador;
				$Mubicacionv->IdSucursal = $IdSucursal;
				$Mubicacionv->Lat = $lat;
				$Mubicacionv->Lng = $lng;
				$Mubicacionv->Estatus = "Disponible";
				$Mubicacionv->insert_ubicacion();
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Realizado',
				'message' => 'Realizado con exito',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//Api de cambiar el estatus de servicio de levantamiento
	public function ServiciochangeLevantamiento_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdServicio' => $this->post('IdServicio'),
			'Estatus' => $this->post('Estatus'),
			'IdTrabajador' => $this->post('IdTrabajador'),
			'EstadoS' => $this->post('EstadoS')
		]);

		$v->rule('required', [
			'IdServicio',
			'Estatus',
			'IdTrabajador',
			'EstadoS'
		])->message('El campo {field} es requerido.');

		$v->rule('integer', [
			'IdServicio',
			'IdTrabajador'
		])->message('El campo {field} solo acepta numeros enteros.');

		if ($v->validate()) {

			$Id = (int)$this->post('IdServicio');

			$Estatus = $this->post('Estatus');

			$oMservicio = new Mservicio();
			$oMservicio->IdServicio = $Id;
			$dataser = $oMservicio->get_servicio();

			if ($dataser['status']) {

				//SE ASIGNA PERSONAL SI NO TIENE UNO ASIGANDO Y SERA EL PRIMERO QUE ATIENDA EL SERVICIO
				if ($dataser['data']->Personal == '') {
					$oMservicio = new Mservicio();
					$oMservicio->IdServicio = $Id;
					$oMservicio->Personal = $this->post('IdTrabajador');
					$oMservicio->personalUpdate();
					$dataser['data']->Personal = $this->post('IdTrabajador');
				}

				$oMservicio = new Mservicio();
				$oMservicio->FechaMod = date('Y-m-d');
				$oMservicio->IdSucursal = $IdSucursal;
				$oMservicio->IdServicio = $Id;
				$oMservicio->Personal = $this->post('IdTrabajador');
				$response = $oMservicio->get_servicioDate();

				//Si es diferente de true es decir que no encontro otro servicio y puede inicar uno nuevo
				if (!$response['status']) {
					//Compara si el que cierra o inicia el servicio es el responsable
					if ($dataser['data']->Personal == $this->post('IdTrabajador')) {
						$oMtrabajador = new Mtrabajador();
						$oMtrabajador->IdTrabajador = $this->post('IdTrabajador');
						$oMtrabajador->Estatus = $this->post('Estatus');
						$oMtrabajador->set_update_estatus_trab();

						$oMservicio = new Mservicio();
						$oMservicio->Comentario = $this->post('Comentario');
						$oMservicio->Materiales = $this->post('Materiales');
						$oMservicio->TiempoAcceso = $this->post('TiempoAcceso');
						$oMservicio->TiempoSalida = $this->post('TiempoSalida');
						$oMservicio->TiempoEM = $this->post('TiempoEM');
						$oMservicio->TiempoCapacitacion = $this->post('TiempoCapacitacion');
						$oMservicio->NumPersonal = $this->post('NumPersonal');
						$oMservicio->NumVehiculos = $this->post('NumVehiculos');
						$oMservicio->IdServicio = $Id;
						$oMservicio->EstadoS = $this->post('EstadoS');
						$oMservicio->FechaMod = date('Y-m-d H:i:s');
						$oMservicio->updateEstatusLevantamiento();

						$IdServUbi = $Id;
						if ($this->post('Estatus') == "Disponible") {
							$IdServUbi = "0";
						}

						$Mubicaciont = new Mubicaciont();
						$Mubicaciont->IdTrabajador = $this->post('IdTrabajador');
						$Mubicaciont->IdServicio = $IdServUbi;
						$Mubicaciont->Estatus = $this->post('Estatus');
						$Mubicaciont->update_ubicacionactual();

						/*
						$oMservicioactivo= new Mservicioactivo();
						$oMservicioactivo->IdServicio=$Id;
						$oMservicioactivo->IdTrabajador=$this->post('IdTrabajador');
						$oMservicioactivo->Fecha=$this->post('Fecha');
						$oMservicioactivo->Estado=$this->post('Estatus');
						$oMservicioactivo->set_updateservicioact();
						*/
						$oMservicio = new Mservicio();
						$oMservicio->IdServicio = $Id;
						$response = $oMservicio->get_servicio();
						$data['servicio'] = $response['data'];

						return $this->set_response([
							'status' => true,
							'data' => $data,
							'message' => 'Se ha actualizado correctamente.',
						], REST_Controller::HTTP_ACCEPTED);
					} else {
						return $this->set_response([
							'status' => false,
							'type' => 1,
							'EstatusS' => $dataser['data']->EstadoS,
							'message' => 'Solo el usuario responsable puede hacer esta acción',
						], REST_Controller::HTTP_BAD_REQUEST);
					}
				} else {
					return $this->set_response([
						'status' => false,
						'type' => 1,
						'message' => 'Termine el Servicio Iniciado para Empezar Otro',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				return $this->set_response([
					'status' => false,
					'type' => 1,
					'message' => 'Servicio no encontrado',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => 'Internal Error', //$data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	#CerrarSesion
	public function CerrarSesion_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdTrabajador' => $this->post('IdTrabajador'),
			//'lat' => $this->post('lat'),
			//'lng' => $this->post('lng')

		]);

		$v->rule('required', [
			'IdTrabajador',
			//'lat',
			//'lng',
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdTrabajador = $this->post('IdTrabajador');
			$lat = $this->post('lat');
			$lng = $this->post('lng');

			$Mubicaciont = new Mubicaciont();
			$Mubicaciont->IdTrabajador = $IdTrabajador;
			$obj = $Mubicaciont->get_ubicacionexist();

			if ($obj['status']) { //modifica
				$Mubicaciont = new Mubicaciont();
				$Mubicaciont->IdTrabajador = $IdTrabajador;
				$Mubicaciont->Lat = $lat;
				$Mubicaciont->Lng = $lng;
				$Mubicaciont->update_ubicacioncerrar();
			} else { //inserta
				$Mubicaciont = new Mubicaciont();
				$Mubicaciont->IdTrabajador = $IdTrabajador;
				$Mubicaciont->IdSucursal = $IdSucursal;
				$Mubicaciont->Lat = $lat;
				$Mubicaciont->Lng = $lng;
				$Mubicaciont->Estatus = 'CERROSESION';
				$Mubicaciont->insert_ubicacion();
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Realizado',
				'message' => 'Realizado con exito',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function CerrarSesionVendedor_post()
	{
		// Valid Token

		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

		$v = new Valitron\Validator([
			'IdTrabajador' => $this->post('IdTrabajador'),
			//'lat' => $this->post('lat'),
			//'lng' => $this->post('lng')

		]);

		$v->rule('required', [
			'IdTrabajador',
			//'lat',
			//'lng',
		])->message('El campo {field} es requerido.');


		if ($v->validate()) {

			$IdTrabajador = $this->post('IdTrabajador');
			$lat = $this->post('lat');
			$lng = $this->post('lng');

			$Mubicacionv = new Mubicacionv();
			$Mubicacionv->IdTrabajador = $IdTrabajador;
			$obj = $Mubicacionv->get_ubicacionexist();

			if ($obj['status']) { //modifica
				$Mubicacionv = new Mubicacionv();
				$Mubicacionv->IdTrabajador = $IdTrabajador;
				$Mubicacionv->Lat = $lat;
				$Mubicacionv->Lng = $lng;
				$Mubicacionv->update_ubicacioncerrar();
			} else { //inserta
				$Mubicacionv = new Mubicacionv();
				$Mubicacionv->IdTrabajador = $IdTrabajador;
				$Mubicacionv->IdSucursal = $IdSucursal;
				$Mubicacionv->Lat = $lat;
				$Mubicacionv->Lng = $lng;
				$Mubicacionv->Estatus = 'CERROSESION';
				$Mubicacionv->insert_ubicacion();
			}

			return $this->set_response([
				'status' => true,
				'data' => 'Realizado',
				'message' => 'Realizado con exito',
			], REST_Controller::HTTP_ACCEPTED);
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'type' => 1,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
