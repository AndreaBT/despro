<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cctaporpagar extends REST_Controller
{
	public $ruta = "assets/files/ctappevidencia/";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('finanzas/MactualFinanzas');
		$this->load->model('ctaporpagar/Mctaporpagar');
		$this->load->model('estadosf/Mdetalleestadofinanciero');
		$this->load->model('estadosf/Mestadosfinancieros');
		$this->load->model('finanzas/Mestadofinanciero');
		$this->load->library('FinanzasActualizacion');
		$this->load->model('estadosf/Mpersonaloperativo');

		$this->load->library('UploadFile');

		setTimeZone($this->verification, $this->input);
	}

	public function List_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


		$oMctaporpagar = new Mctaporpagar();


		$oMctaporpagar->IdSucursal = $IdSucursal;
		$oMctaporpagar->NumCuenta = trim($this->get('Nombre'));
		$oMctaporpagar->RegEstatus = $this->get('RegEstatus');
		$oMctaporpagar->TipoFiltro = $this->get('TipoFiltro');
		$oMctaporpagar->Vigencia = $this->get('Vigencia');
		$oMctaporpagar->IdProveedor = $this->get('IdProveedor');
		$oMctaporpagar->TipoCuenta = $this->get('TipoCuenta');
		//////////////////////////////////////////////////////////
		$oMctaporpagar->Fecha_I = dateformato($this->get('FechaI'));
		$oMctaporpagar->Fecha_F = dateformato($this->get('FechaF'));

		// Paginación

		$rows =  $oMctaporpagar->get_list();
		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$oMctaporpagar->Limit = $Entrada;

		$pager = Pager::get_pager(count($rows), $this->get('pag'), $Entrada);

		$oMctaporpagar->Tamano = $pager->PageSize;
		$oMctaporpagar->Offset = $pager->Offset;

		$sumaTotal = $oMctaporpagar->get_sumAmount();

		$data['ctaporpagar'] = $oMctaporpagar->get_list();
		$data['pagination'] = $pager;
		$data['sumamonto'] = $sumaTotal['data'];

		return $this->set_response([
			'status' => true,
			'data' => $data,
			'ruta' => base_url() . $this->ruta . "$IdSucursal/",
			'message' => 'Success',
		], REST_Controller::HTTP_OK);
	}

	public function Recovery_get()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$oMctaporpagar = new MctaporPagar();

		$Id = (int) $this->get('IdCtaPagar');

		if (empty($Id)) {

			return $this->set_response([
				'status' => false,
				'message' => 'Parámetros no recibidos.',
			], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$oMctaporpagar->IdCtaPagar = $Id;
		}
		$response = $oMctaporpagar->get_recovery();
		if ($response['status']) {
			$data['ctaporpagar'] = $response['data'];
			return $this->set_response([
				'status' => true,
				'data' => $data,
				'ruta' => base_url() . $this->ruta . "$IdSucursal/",
				'message' => 'Success',
			], REST_Controller::HTTP_OK);
		} else {

			$this->set_response([
				'status' => false,
				'message' => 'No encontrado.',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}


	public function Delete_delete($Id)
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		$oMctaporpagar = new Mctaporpagar();
		$oMctaporpagar->IdCtaPagar = $Id;

		$response = $oMctaporpagar->get_recovery();

		if ($response['status']) {

			if ($oMctaporpagar->delete()) {

				return $this->set_response([
					'status' => true,
					'message' => 'Se ha eliminado correctamente.',
				], REST_Controller::HTTP_ACCEPTED);
			} else {

				return $this->set_response([
					'status' => false,
					'message' => 'Error al eliminar la información.',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {

			return $this->set_response([
				'status' => false,
				'message' => 'No encontrado.',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function Add_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}

		//PUEDEN SER NULL
		$Cuentas = $this->post('IdAsociado');
		$Contrato = $this->post('IdContrato');
		$Servicio = $this->post('IdConfigS');
		$TipoServicio = $this->post('IdTipoServicio');
		//

		//NO PUEDEN SER NULL
		$Departamento = $this->post('TipoSelect');
		if ($Departamento == 0) {
			$Departamento = "";
		}
		$Proveedor = $this->post('IdProveedor');
		if ($Proveedor == 0) {
			$Proveedor  = "";
		}
		$Cliente = $this->post('IdCliente');
		// if ($Cliente == 0) {
		// 	$Cliente  = "";
		// }
		$Sucursal = $this->post('IdSucursalCliente');
		// if ($Sucursal == 0) {
		// 	$Sucursal  = "";
		// }

		//VEHÍCULOS, MATERIALES, EQUIPOS, VIÁTICOS
		if (
			$Departamento == 6 ||
			$Departamento == 7 ||
			$Departamento == 8 ||
			$Departamento == 9
		) {
			if ($Servicio == 0) {
				$Servicio = "";
			}
			if ($TipoServicio == 0) {
				$TipoServicio = "";
			}
			if ($Contrato == null) {
				$Contrato = "";
			}
		}

		if ($this->post('TipoCuenta') == "ADMIN") {

			//CUENTAS NO PUEDE SER NULL EN ADMINISTRATIVO
			if ($Cuentas == 0) {
				$Cuentas  = "";
			}

			$v = new Valitron\Validator([
				'Departamento' => $Departamento,
				'Cuentas' => $Cuentas,
				'Monto' => $this->post('Monto'),
				'Proveedor' => $Proveedor,
				'Numero_Factura' => $this->post('NumFactura'),
				'Fecha_Factura' => $this->post('FechaFactura'),
				'Fecha_Pago' => $this->post('FechaPago'),
			]);

			$v->rule('required', [
				'Departamento',
				'Cuentas',
				'Monto',
				'Proveedor',
				'Numero_Factura',
				'Fecha_Factura',
				'Fecha_Pago',
			])->message('El campo {field} es requerido.');
		}

		if ($this->post('TipoCuenta') == "OPERA") {

			$v = new Valitron\Validator([
				'Departamento' => $Departamento,
				'Cuentas' => $Cuentas,
				'Monto' => $this->post('Monto'),
				'Proveedor' => $Proveedor,
				'Numero_Factura' => $this->post('NumFactura'),
				'Fecha_Factura' => $this->post('FechaFactura'),
				'Fecha_Pago' => $this->post('FechaPago'),
				'Cliente' => $Cliente,
				'Sucursal' => $Sucursal,
				'Contrato' => $Contrato,
				'Servicio' => $Servicio,
				'Tipo_Servicio' => $TipoServicio,
			]);

			$v->rule('required', [
				'Departamento',
				'Cuentas',
				'Monto',
				'Proveedor',
				'Numero_Factura',
				'Fecha_Factura',
				'Fecha_Pago',
				'Cliente',
				'Sucursal',
				'Contrato',
				'Servicio',
				'Tipo_Servicio',
			])->message('El campo {field} es requerido.');
		}


		if ($v->validate()) {
			$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
			$Id = $this->post('IdCtaPagar');

			$RutaPrincipal = "assets/files/ctappevidencia/" . $IdSucursal . '/';
			if (!is_dir($RutaPrincipal)) {
				mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
			}

			$route = $RutaPrincipal;
			$FacturaFile = $this->uploadfile->savefile($route, 'File', $this->post('Factura'), '*', UploadFile::SINGLE);
			$EvidenciaUnoFile = $this->uploadfile->savefile($route, 'File2', $this->post('ArchivoUno'), '*', UploadFile::SINGLE);
			$EvidenciaDosFile = $this->uploadfile->savefile($route, 'File3', $this->post('ArchivoDos'), '*', UploadFile::SINGLE);

			$Credito = $this->post('Credito');
			if ($Credito == '') {
				$Credito = 0;
			}

			$oMctaporpagar = new Mctaporpagar();
			$oMctaporpagar->IdCtaPagar =  $Id;
			$oMctaporpagar->IdSucursal = $IdSucursal;
			$oMctaporpagar->IdAsociado = $Cuentas;
			$oMctaporpagar->IdProveedor = $Proveedor;
			$oMctaporpagar->TipoCuenta = $this->post('TipoCuenta');
			$oMctaporpagar->NumFactura = $this->post('NumFactura');
			$oMctaporpagar->Credito = $Credito;
			$oMctaporpagar->Monto = $this->post('Monto');
			$oMctaporpagar->TipoSelect = $Departamento;
			$oMctaporpagar->RegEstatus = "A";
			$oMctaporpagar->FechaFactura = $this->post('FechaFactura');
			$oMctaporpagar->FechaPago = $this->post('FechaPago');
			$oMctaporpagar->FechaReg = date('Y-m-d');
			$oMctaporpagar->FechaMod = date('Y-m-d H:i:s');
			$oMctaporpagar->IdCliente = $Cliente;
			$oMctaporpagar->IdSucursalCliente = $Sucursal;
			$oMctaporpagar->IdContrato = $Contrato;
			$oMctaporpagar->IdConfigS = $Servicio;
			$oMctaporpagar->IdTipoServicio = $TipoServicio;

			$oMctaporpagar->Factura = $FacturaFile;
			$oMctaporpagar->ArchivoUno = $EvidenciaUnoFile;
			$oMctaporpagar->ArchivoDos = $EvidenciaDosFile;

			if ($oMctaporpagar->IdCtaPagar == 0) {
				$Id = $oMctaporpagar->insert();
				if ($Id > 0) {
					$oMctaporpagar->IdCtaPagar = $Id;
					$response = $oMctaporpagar->get_recovery();
					$data['ctaporpagar'] = $response['data'];

					if ($response['status']) {
						if ($response['data']->TipoCuenta == "ADMIN") {
								$this->AddCostosAdmins($response, $IdSucursal);
							}
					}
					
					return $this->set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha agregado correctamente.',
					], REST_Controller::HTTP_CREATED);
				} else {
					return $this->set_response([
						'status' => false,
						'message' => 'Error al agregar a la base de datos.',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			} else {
				if ($oMctaporpagar->update()) {
					$response = $oMctaporpagar->get_recovery();
					$data['ctaporpagar'] = $response['data'];

					return $this->set_response([
						'status' => true,
						'data' => $data,
						'message' => 'Se ha actualizado correctamente.',
					], REST_Controller::HTTP_ACCEPTED);
				} else {

					return $this->set_response([
						'status' => false,
						'message' => 'Error al actualizar los datos de la base de datos.',
					], REST_Controller::HTTP_BAD_REQUEST);
				}
			}
		} else {
			$data['errores'] = $v->errors();

			return $this->set_response([
				'status' => false,
				'message' => $data,
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function ChangeEstatus_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
		$IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

		$oMctaporpagar = new Mctaporpagar();
		$oMctaporpagar->IdCtaPagar = $this->post('IdCtaPagar');
		$oMctaporpagar->Observacion = $this->post('Observacion');
		$oMctaporpagar->FechaRealPago = $this->post('FechaRealPago');

		$response = $oMctaporpagar->get_recovery();

		if ($response['status']) {

			if ($oMctaporpagar->changePago()) {

				// $Fecha = explode("-", $response['data']->FechaPago);

				// if ($response['data']->TipoCuenta == "ADMIN") {
				// 	$this->AddCostosAdmins($response, $IdSucursal);
				// }

				if ($response['data']->TipoCuenta == "OPERA") {

					//ACTUALIZAR COSTOS ADMINISTRATIVOS
					if ( //Burden (Costo Depto. Operaciones)
						$response['data']->TipoSelect == 2 ||
						//Vehículos (Costo Depto. Vehículos)
						$response['data']->TipoSelect == 3
					) {
						$this->AddCostosAdmins($response, $IdSucursal);
					}

					// //ACTUALIZAR FACTURACION DE SERVICIOS
					// if ( //Materiales
					// 	$response['data']->TipoSelect == 6 ||
					// 	//Equipos
					// 	$response['data']->TipoSelect == 7 ||
					// 	//Contratistas 
					// 	$response['data']->TipoSelect == 8 ||
					// 	//Viáticos
					// 	$response['data']->TipoSelect == 9
					// ) {
					// 	$this->AddCostosOpera($response, $IdSucursal);
					// }

					//ACTUALIZAR SUELDO PERSONAL OPERATIVO
					if ( //Mano de Obra Directa (Sueldo Base)
						$response['data']->TipoSelect == 10 ||
						//Mano de Obra Leyes Sociales (Obligaciones Ley)
						$response['data']->TipoSelect == 11 ||
						//Mano de Obra Otros (Sueldo Base)
						$response['data']->TipoSelect == 12
					) {
						$this->addSueldoProveedor($response, $IdSucursal, $IdEmpresa);
					}
				}

				return $this->set_response([
					'status' => true,
					'message' => 'Se ha realizado el pago.',
				], REST_Controller::HTTP_ACCEPTED);
			} else {

				return $this->set_response([
					'status' => false,
					'message' => 'Error al realizar la accion.',
				], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {

			return $this->set_response([
				'status' => false,
				'message' => 'No encontrado.',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function UpdateValidity_post()
	{
		// Valid Token
		if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
			return $this->set_unauthorized_response();
		}
		$IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


		$oMctaporpagar = new Mctaporpagar();

		if ($IdSucursal > 0) {

			$oMctaporpagar->updateValidity();

			return $this->set_response([
				'status' => true,
				'message' => 'Se ha actualizado la vigencia',
			], REST_Controller::HTTP_ACCEPTED);
		} else {

			return $this->set_response([
				'status' => false,
				'message' => 'Error al actualizar la vigencia.',
			], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function AddCostosAdmins($response, $IdSucursal)
	{

		// $Fecha = explode("-", $response['data']->FechaPago);
		$Id = $response['data']->IdAsociado;

		$Anio = date("Y");
		$Mes = date('n');
		// echo $Mes . '-' . $response['data']->FechaPago;

		if ($response['data']->TipoSelect == 1) {
			$IdName = 'IdGasto';
			$Tabla = 'actualventas';
		}

		if ($response['data']->TipoSelect == 2) {
			$IdName = 'IdCostoDeptoVenta';
			$Tabla = 'actualoperaciones';
		}
		if ($response['data']->TipoSelect == 3) {
			$IdName = 'IdCostoVehOpe';
			$Tabla = 'actualcostove';
		}

		if ($response['data']->TipoSelect == 4) {
			$IdName = 'IdCostoGA';
			$Tabla = 'actualcostoga';
		}

		if ($response['data']->TipoSelect == 5) {
			$IdName = 'IdCostoFinanciero';
			$Tabla = 'actualcostof';
		}

		$oMactualFinanzas = new MactualFinanzas();
		$oMactualFinanzas->Tabla = $Tabla;
		$oMactualFinanzas->NameId = $IdName;
		$oMactualFinanzas->Id = $Id;
		$oMactualFinanzas->Anio = $Anio;
		$oMactualFinanzas->Mes = $Mes;
		$oMactualFinanzas->IdSucursal = $IdSucursal;
		// $dataexit = $oMactualFinanzas->get_costoga();
		$dataexit = $oMactualFinanzas->getListTwo();


		//Datos a insertar o modificar
		$oMactualFinanzas = new MactualFinanzas();
		$oMactualFinanzas->Tabla = $Tabla;
		$oMactualFinanzas->NameId = $IdName;
		$oMactualFinanzas->Id = $Id;
		$oMactualFinanzas->Anio = $Anio;
		$oMactualFinanzas->Mes = $Mes;
		$oMactualFinanzas->IdSucursal = $IdSucursal;
		$oMactualFinanzas->FechaCompleta = date('Y-m-d H:i:s');

		$TotalRowWeeks =
			$dataexit['data']->SemanaUno +
			$dataexit['data']->SemanaDos +
			$dataexit['data']->SemanaTres +
			$dataexit['data']->SemanaCuatro;

		if ($dataexit['status']) {
			$MontoCuenta = $response['data']->Monto + $dataexit['data']->MontoCuenta;
			$MontoMes = $MontoCuenta + $TotalRowWeeks;

			$oMactualFinanzas->MontoCuenta = $MontoCuenta;
			$oMactualFinanzas->MontoMes = $MontoMes;
			$oMactualFinanzas->updateCountAmmount();
		} else {
			$MontoCuenta = $response['data']->Monto;
			$MontoMes = $MontoCuenta + $TotalRowWeeks;

			$oMactualFinanzas->MontoMes = $MontoMes;
			$oMactualFinanzas->MontoCuenta = $MontoCuenta;
			$oMactualFinanzas->insert();
		}
	}

	public function AddCostosOpera($response, $IdSucursal)
	{	//VARIABLES NECESARIAS
		$IdConfigS = $response['data']->IdConfigS;
		$Anio = date("Y");
		$Mes = str_pad(date("n"), 2, '0', STR_PAD_LEFT);
		$IdTipoServicio = $response['data']->IdTipoServicio;
		$IdCliente = $response['data']->IdCliente;
		$IdClienteSucursal = $response['data']->IdSucursalCliente;
		$IdContrato = $response['data']->IdContrato;

		//SE COMPRUEBA SI EXISTE
		$recoveryDetalle = new Mestadofinanciero();
		$recoveryDetalle->IdSucursal = $IdSucursal;
		$recoveryDetalle->IdConfigS 	= $IdConfigS;
		$recoveryDetalle->IdTipoServ	= $IdTipoServicio;
		$recoveryDetalle->Anio 		= $Anio;
		$recoveryDetalle->Mes 		= $Mes;
		$recoveryDetalle->IdCliente 	= $IdCliente;
		$recoveryDetalle->IdClienteS = $IdClienteSucursal;
		$recoveryDetalle->IdContrato = $IdContrato;
		$detalleexist = $recoveryDetalle->get_recovery();

		//SI EXISTE GUARDAMOS SU ID, CUANDO ESTA EXISTE SE HACE UPDATE
		$IdEstado = 0;
		if ($detalleexist['status']) {
			$IdEstado = $detalleexist['data']->IdEstadoF;
		}

		//SE TIENE QUE ASIGNAR EL VALOR DEL RECOVERY, SI NO, SE PLANCHAN
		$Facturacion = $detalleexist['data']->Facturacion;
		$Materiales = $detalleexist['data']->Materiales;
		$Equipos = $detalleexist['data']->Equipos;
		$ManoDeObra = $detalleexist['data']->ManoDeObra;
		$Vehiculos = $detalleexist['data']->Vehiculos;
		$Contratistas = $detalleexist['data']->Contratistas;
		$Burden = $detalleexist['data']->Burden;
		$Viaticos = $detalleexist['data']->Viaticos;
		$FacturacionPMensual = $detalleexist['data']->FacturacionPMensual;

		//INSERTA EL MONTO EN MATERIALES
		if ($response['data']->TipoSelect == 6) {
			$Materiales = $response['data']->Monto + $detalleexist['data']->Materiales;
		}
		//INSERTA EL MONTO EN EQUIPOS
		if ($response['data']->TipoSelect == 7) {
			$Equipos = $response['data']->Monto + $detalleexist['data']->Equipos;
		}
		//INSERTA EL MONTO EN CONTRATISTAS
		if ($response['data']->TipoSelect == 8) {
			$Contratistas = $response['data']->Monto + $detalleexist['data']->Contratistas;
		}
		//INSERTA EL MONTO EN VIATICOS
		if ($response['data']->TipoSelect == 9) {
			$Viaticos = $response['data']->Monto + $detalleexist['data']->Viaticos;
		}

		//ASIGNAMOS VALORES AL INSERT
		$addCostoFinanciero = new Mestadosfinancieros();
		$addCostoFinanciero->IdEstadoF  = $IdEstado;
		$addCostoFinanciero->IdConfigS   = $IdConfigS;
		$addCostoFinanciero->IdSucursal  = $IdSucursal;
		$addCostoFinanciero->Anio        = $Anio;
		$addCostoFinanciero->Mes         = $Mes;

		$addCostoFinanciero->Facturacion  = $Facturacion;
		$addCostoFinanciero->Materiales  = $Materiales;
		$addCostoFinanciero->Equipos  = $Equipos;
		$addCostoFinanciero->ManoDeObra  = $ManoDeObra;
		$addCostoFinanciero->Vehiculos  = $Vehiculos;
		$addCostoFinanciero->Contratistas  = $Contratistas;
		$addCostoFinanciero->Burden  = $Burden;
		$addCostoFinanciero->Viaticos  = $Viaticos;
		$addCostoFinanciero->FacturacionPMensual  = $FacturacionPMensual;

		$addCostoFinanciero->IdTipoServ  = $IdTipoServicio;
		$addCostoFinanciero->IdCliente   = $IdCliente;
		$addCostoFinanciero->IdClienteS  = $IdClienteSucursal;
		$addCostoFinanciero->IdContrato  = $IdContrato;

		if ($IdEstado == 0) {
			$addCostoFinanciero->set_insert_estadofinanciero();
		} else {
			$addCostoFinanciero->set_update_estadofinanciero();
		}
	}

	public function addSueldoProveedor($response, $IdSucursal, $IdEmpresa)
	{
		$Anio = date("Y");
		$Mes = str_pad(date("n"), 2, '0', STR_PAD_LEFT);

		$addSueldoPersonal = new Mpersonaloperativo();

		$addSueldoPersonal->Anio            = $Anio;
		$addSueldoPersonal->Mes             = $Mes;
		$addSueldoPersonal->IdEmpresa       = $IdEmpresa;
		$addSueldoPersonal->IdSucursal      = $IdSucursal;
		$addSueldoPersonal->IdProveedor     = $response['data']->IdProveedor;
		$data = $addSueldoPersonal->get_recovery();
		$addSueldoPersonal->Sueldo       	= $data['data']->Sueldo;
		$addSueldoPersonal->Obligaciones    = $data['data']->Obligaciones;

		if ($response['data']->TipoSelect == 10 || $response['data']->TipoSelect == 12) {
			$addSueldoPersonal->Sueldo = $data['data']->Sueldo + $response['data']->Monto;
			$addSueldoPersonal->Total = $data['data']->Sueldo + $response['data']->Monto + $data['data']->Obligaciones;;
		}

		if ($response['data']->TipoSelect == 11) {
			$addSueldoPersonal->Obligaciones = $data['data']->Obligaciones + $response['data']->Monto;
			$addSueldoPersonal->Total = $data['data']->Obligaciones + $response['data']->Monto + $data['data']->Sueldo;
		}

		if ($data['data']->IdProveedor == "") {
			$addSueldoPersonal->Nombre = $response['data']->Proveedor;
			$addSueldoPersonal->insert();
		} else {
			$addSueldoPersonal->update();
		}
	}
}
