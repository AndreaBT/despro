<?php
//defined("BASEPATH") or die("El acceso al script no est� permitido");
defined('BASEPATH') or exit('No direct script access allowed');

class Finanzas
{
	public $porcentaje1 = 0;
	public $porcentaje2 = 0;
	public $TotalUno = 0;
	public $TotalDos = 0;
	public $TotalTres = 0;
	public $TotalCuatro = 0;
	public $UnoT = 0;
	public $DosT = 0;
	public $TresT = 0;
	public $CuatroT = 0;

	public $BaseActual = 0;
	public function __construct()
	{
		$CI = &get_instance();
		$CI->load->model('finanzas/Mconfigporcensubtipo');
		$CI->load->model('finanzas/Mfinventas');
		$CI->load->model('finanzas/Mserviciosvalores');
		$CI->load->model('finanzas/Mconceptooperacion');
		$CI->load->model('finanzas/Mporcentajeoperacion');
		$CI->load->model('Mconfigservicio');
		$CI->load->model('Mtiposervicio');

		$CI->load->model('Mperfil');
		$CI->load->model('Mrol');
		$CI->load->model('Mtrabajador');
	}

	public  function Porcentajes($IdConfigS)
	{
		//***Porcentaje 1 y dos
		$oMconfigservicio = new Mconfigservicio();
		$oMconfigservicio->IdConfigS = $IdConfigS;
		$dataporcen = $oMconfigservicio->get_configservicio();

		$this->porcentaje1 = 0;
		$this->porcentaje2 = 0;

		if ($dataporcen['status']) {

			$porcentaje1 = $dataporcen['data']->Porcentaje;
			$porcentaje2 = $dataporcen['data']->Porcentaje2;

			$this->porcentaje1 = $porcentaje1;
			$this->porcentaje2 = $porcentaje2;

			if ($porcentaje1 > 0) {
				$this->porcentaje1 = $porcentaje1 / 100;
			}
			if ($porcentaje2 > 0) {
				$this->porcentaje2 = $porcentaje2 / 100;
			}
		}
	}

	public function GetTrabajadores($IdSucursal)
	{
		//Totales por trabajador
		$IdIdComp2 = array('Vendedor', 'Gerente de ventas');
		$oMrol = new Mrol();
		$oMrol->Nombre = $IdIdComp2; //'Usuario APP';
		$oMrol->IdSucursal = $IdSucursal;
		$orol = $oMrol->get_listName();

		$rowtrabajador = array();
		foreach ($orol as $element) {

			$operfil = new Mperfil();
			$operfil->Busqueda = $element->Nombre;
			$obj = $operfil->get_recovery();

			$oMtrabajador = new Mtrabajador();
			$oMtrabajador->IdSucursal = $IdSucursal;
			$oMtrabajador->RegEstatus = 'A';
			$oMtrabajador->IdRol = $element->IdRol;
			$oMtrabajador->IdPerfil = $obj['data']->IdPerfil;
			$row = $oMtrabajador->get_list();

			foreach ($row as $element) {
				array_push($rowtrabajador, $element);
			}
		}

		return $rowtrabajador;
	}

	public function GetBase($anio, $IdSucursal, $IdConfigS, $IdSubTipo)
	{
		// ***Base Actual***
		$this->BaseActual = 0;
		$oMserviciosvalores = new Mserviciosvalores();
		$oMserviciosvalores->Anio = $anio;
		$oMserviciosvalores->IdSucursal = $IdSucursal;
		$oMserviciosvalores->IdConfigS = $IdConfigS;
		$oMserviciosvalores->IdTipoSer = $IdSubTipo;
		$base = $oMserviciosvalores->get_configprcensubtipo();

		if ($base['status']) {
			$this->BaseActual = $base['data']->BaseActual;
		}
	}

	public function PorcentajeOperacionCalculo($IdSucursal, $anio, $IdConfigS, $IdSubTipo = '')
	{
		//Porcentaje
		$this->Porcentajes($IdConfigS);
		//Trabajadores de ventas
		$rowtrabajador = $this->GetTrabajadores($IdSucursal);
		$row = null;
		if (!empty($IdSubTipo)) {
			$row = $this->Individual($rowtrabajador, $anio, $IdSucursal, $IdConfigS, $IdSubTipo);
		} else {
			$row = $this->General($rowtrabajador, $anio, $IdSucursal, $IdConfigS);
		}

		return $row;
	}

	public function Individual($rowtrabajador, $anio, $IdSucursal, $IdConfigS, $IdSubTipo)
	{

		$this->GetBase($anio, $IdSucursal, $IdConfigS, $IdSubTipo);
		$this->TotalUno = 0;
		$this->TotalDos = 0;
		$this->TotalTres = 0;
		$this->TotalCuatro = 0;
		$this->UnoT  = 0;
		$this->DosT  = 0;
		$this->TresT = 0;
		$this->CuatroT = 0;

		foreach ($rowtrabajador as $element) {

			$Mfinventas = new Mfinventas();
			$Mfinventas->IdSucursal = $IdSucursal;
			$Mfinventas->Anio = $anio;
			$Mfinventas->IdConfigS = $IdConfigS;
			$Mfinventas->IdVendedor = $element->IdTrabajador;
			$data = $Mfinventas->get_finventas();

			if ($data['status']) {
				//subtipo filtrado
				$oMconfigporcensubtipo = new Mconfigporcensubtipo();
				$oMconfigporcensubtipo->IdConfigS = $IdConfigS;
				$oMconfigporcensubtipo->IdVendedor = $element->IdTrabajador;
				$oMconfigporcensubtipo->IdTipoS = $IdSubTipo;
				$oMconfigporcensubtipo->Anio = $anio;
				$data2 = $oMconfigporcensubtipo->get_configprcensubtipo();

				$porcentaje = 0;
				if ($data2['status']) {
					if ($data2['data']->Porcentaje != '') {
						if ($data2['data']->Porcentaje > 0) {
							$porcentaje = $data2['data']->Porcentaje / 100;
						}
					}
				}


				$this->TotalUno += $data['data']->UnoT * $porcentaje;
				$this->TotalDos += $data['data']->DosT * $porcentaje;
				$this->TotalTres += $data['data']->TresT * $porcentaje;
				$this->TotalCuatro += $data['data']->CuatroT * $porcentaje;
			}
		}
		//****Fin totales por trabajador

		//Si es 1 es mantenimiento
		if ($IdConfigS == 1) {
			$this->UnoT  = $this->TotalUno * $this->porcentaje1 * $this->porcentaje2 + ($this->BaseActual * $this->porcentaje2);
			$this->DosT  = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
			$this->TresT = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
			$this->CuatroT = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres *  $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
		}

		//Si es 2 es igual a servicio
		if ($IdConfigS == 2) {
			$this->UnoT  = (($this->TotalUno * $this->porcentaje1) + $this->BaseActual / 4);
			$this->DosT  = ((($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1)) + $this->BaseActual / 4);
			$this->TresT = ((($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1)) + $this->BaseActual / 4);
			$this->CuatroT = ((($this->TotalTres * $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1)) + $this->BaseActual / 4);
		}

		//Si es 3 es igual a Proyecto
		if ($IdConfigS == 3) {
			$this->UnoT  =  ($this->TotalUno * $this->porcentaje1) + $this->BaseActual;
			$this->DosT  = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1);
			$this->TresT = ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1);
			$this->CuatroT = ($this->TotalTres * $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1);
		}
		//Obtener los conceptos de acuerdo al tipo de mantenimiento

		$this->UnoT = round($this->UnoT, 0, PHP_ROUND_HALF_UP);
		$this->DosT = round($this->DosT, 0, PHP_ROUND_HALF_UP);
		$this->TresT = round($this->TresT, 0, PHP_ROUND_HALF_UP);
		$this->CuatroT = round($this->CuatroT, 0, PHP_ROUND_HALF_UP);


		$oMconceptooperacion = new Mconceptooperacion();
		$row = $oMconceptooperacion->get_list();
		$contador = 0;
		foreach ($row as $element) {
			$oMporcentajeoperacion = new Mporcentajeoperacion();
			$oMporcentajeoperacion->IdTipoSer = $IdConfigS;
			$oMporcentajeoperacion->IdSubtipoServ = $IdSubTipo;
			$oMporcentajeoperacion->Anio = $anio;
			$oMporcentajeoperacion->Descripcion = $element->Nombre;
			$data = $oMporcentajeoperacion->get_porcentajeopearcion();

			$Porcentajeoper = $data['data']->PorcentajeAnual;
			$AnioAnterior = $data['data']->AnioAnterior;
			$PorcentajeAnterior = $data['data']->PorcentajeAnterior;

			//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE MANTENIMIENTO
			if ($IdConfigS == 1) {
				if ($element->Nombre == "Materiales") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 8;
					}
				}
				if ($element->Nombre == "Equipos") {
					if ($Porcentajeoper == '') {
						$Porcentajeoper = 0;
					}
				}
				if ($element->Nombre == "Mano de Obra") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 26;
					}
				}
				if ($element->Nombre == "Vehiculos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 7;
					}
				}
				if ($element->Nombre == "Contratistas") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 2;
					}
				}
				if ($element->Nombre == "Viaticos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 1;
					}
				}
				if ($element->Nombre == "Burden") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 16;
					}
				}
			}
			//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE SERVICIO
			if ($IdConfigS == 2) {
				if ($element->Nombre == "Materiales") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 11;
					}
				}
				if ($element->Nombre == "Equipos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 8;
					}
				}
				if ($element->Nombre == "Mano de Obra") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 22;
					}
				}
				if ($element->Nombre == "Vehiculos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 6;
					}
				}
				if ($element->Nombre == "Contratistas") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 2;
					}
				}
				if ($element->Nombre == "Viaticos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 1;
					}
				}
				if ($element->Nombre == "Burden") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 10;
					}
				}
			}
			//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE PROYECTO
			if ($IdConfigS == 3) {
				if ($element->Nombre == "Materiales") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 20;
					}
				}
				if ($element->Nombre == "Equipos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 20;
					}
				}
				if ($element->Nombre == "Mano de Obra") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 9;
					}
				}
				if ($element->Nombre == "Vehiculos") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 2;
					}
				}
				if ($element->Nombre == "Contratistas") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 13;
					}
				}
				if ($element->Nombre == "Viaticos") {
					if ($Porcentajeoper == '') {
						$Porcentajeoper = 0;
					}
				}
				if ($element->Nombre == "Burden") {
					if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
						$Porcentajeoper = 6;
					}
				}
			}

			if ($AnioAnterior == '') {
				$AnioAnterior = 0;
			}

			if ($PorcentajeAnterior == '') {
				$PorcentajeAnterior = 0;
			}

			if ($contador == 0) {
				$row[$contador]->AnioAnterior  =  $AnioAnterior;
				$row[$contador]->PorcenAnioAnte  =  100;

				$row[$contador]->PrimerT  =  $this->UnoT;
				$row[$contador]->SegundoT = $this->DosT;
				$row[$contador]->TercerT    = $this->TresT;
				$row[$contador]->CuartoT  = $this->CuatroT;

				$TotalAnual = $this->UnoT + $this->DosT + $this->TresT + $this->CuatroT;
				$row[$contador]->TotalAnual = $TotalAnual;
				$row[$contador]->PorcenAnual  =  100;
			} else {
				$Valor1 = ($Porcentajeoper * $this->UnoT) / 100;
				$Valor2 = ($Porcentajeoper * $this->DosT) / 100;
				$Valor3 = ($Porcentajeoper * $this->TresT) / 100;
				$Valor4 = ($Porcentajeoper * $this->CuatroT) / 100;

				$Valor1 = round($Valor1, 0, PHP_ROUND_HALF_UP);
				$Valor2 = round($Valor2, 0, PHP_ROUND_HALF_UP);
				$Valor3 = round($Valor3, 0, PHP_ROUND_HALF_UP);
				$Valor4 = round($Valor4, 0, PHP_ROUND_HALF_UP);

				$row[$contador]->AnioAnterior  =  $AnioAnterior;
				$row[$contador]->PorcenAnioAnte  = number_format($PorcentajeAnterior, 1, '.', '');
				$row[$contador]->PrimerT  = $Valor1;
				$row[$contador]->SegundoT = $Valor2;
				$row[$contador]->TercerT    = $Valor3;
				$row[$contador]->CuartoT  = $Valor4;

				$TotalAnual = $Valor1 + $Valor2 + $Valor3 + $Valor4;
				$row[$contador]->TotalAnual = round($TotalAnual, 0, PHP_ROUND_HALF_UP);
				$row[$contador]->PorcenAnual  = number_format($Porcentajeoper, 1, '.', '');
			}


			$contador++;
		}

		return $row;
	}

	public function General($rowtrabajador, $anio, $IdSucursal, $IdConfigS)
	{
		//se limpian los valores
		$this->UnoT  = 0;
		$this->DosT  = 0;
		$this->TresT = 0;
		$this->CuatroT = 0;

		//obtenemos el total de cada trimestre de los tipos de servicios

		$Mtiposervicio = new Mtiposervicio();
		$Mtiposervicio->IdConfigS = $IdConfigS;
		$Mtiposervicio->RegEstatus = 'A';
		$Mtiposervicio->IdSucursal = $IdSucursal;
		$rowsubtipos =  $Mtiposervicio->get_list();

		$oMconceptooperacion = new Mconceptooperacion();
		$row = $oMconceptooperacion->get_list();

		//Suma de todos los tipos de servicio del IdConfig seleccionado
		$Vueltas = 0;
		foreach ($rowsubtipos as $element) {
			$IdSubTipo =  $element->IdTipoSer;
			$this->GetBase($anio, $IdSucursal, $IdConfigS, $IdSubTipo);
			$this->TotalUno = 0;
			$this->TotalDos = 0;
			$this->TotalTres = 0;
			$this->TotalCuatro = 0;

			foreach ($rowtrabajador as $element) {
				$Mfinventas = new Mfinventas();
				$Mfinventas->IdSucursal = $IdSucursal;
				$Mfinventas->Anio = $anio;
				$Mfinventas->IdConfigS = $IdConfigS;
				$Mfinventas->IdVendedor = $element->IdUsuario;
				$data = $Mfinventas->get_finventas();

				if ($data['status']) {
					//subtipo filtrado
					$oMconfigporcensubtipo = new Mconfigporcensubtipo();
					$oMconfigporcensubtipo->IdConfigS = $IdConfigS;
					$oMconfigporcensubtipo->IdVendedor = $element->IdUsuario;
					$oMconfigporcensubtipo->IdTipoS = $IdSubTipo;
					$oMconfigporcensubtipo->Anio = $anio;
					$data2 = $oMconfigporcensubtipo->get_configprcensubtipo();

					$porcentaje = 0;
					if ($data2['status']) {
						if ($data2['data']->Porcentaje != '') {
							if ($data2['data']->Porcentaje > 0) {
								$porcentaje = $data2['data']->Porcentaje / 100;
							}
						}
					}

					$this->TotalUno += $data['data']->UnoT * $porcentaje;
					$this->TotalDos += $data['data']->DosT * $porcentaje;
					$this->TotalTres += $data['data']->TresT * $porcentaje;
					$this->TotalCuatro += $data['data']->CuatroT * $porcentaje;
				}
			}

			//Si es 1 es mantenimiento
			if ($IdConfigS == 1) {
				$this->UnoT  = $this->TotalUno * $this->porcentaje1 * $this->porcentaje2 + ($this->BaseActual * $this->porcentaje2);
				$this->DosT  = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
				$this->TresT  = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
				$this->CuatroT = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres *  $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1 * $this->porcentaje2) + ($this->BaseActual * $this->porcentaje2);
			}

			//Si es 2 es igual a servicio
			if ($IdConfigS == 2) {
				$this->UnoT  = (($this->TotalUno * $this->porcentaje1) + $this->BaseActual / 4);
				$this->DosT  = ((($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1)) + $this->BaseActual / 4);
				$this->TresT = ((($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1)) + $this->BaseActual / 4);
				$this->CuatroT = ((($this->TotalTres * $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1)) + $this->BaseActual / 4);
			}

			//Si es 3 es igual a Proyecto
			if ($IdConfigS == 3) {
				$this->UnoT  =  ($this->TotalUno * $this->porcentaje1) + $this->BaseActual;
				$this->DosT  = ($this->TotalUno * $this->porcentaje2) + ($this->TotalDos * $this->porcentaje1);
				$this->TresT = ($this->TotalDos * $this->porcentaje2) + ($this->TotalTres * $this->porcentaje1);
				$this->CuatroT = ($this->TotalTres * $this->porcentaje2) + ($this->TotalCuatro * $this->porcentaje1);
			}

			//Obtener los conceptos de acuerdo al tipo de mantenimiento

			$this->UnoT = round($this->UnoT, 0, PHP_ROUND_HALF_UP);
			$this->DosT = round($this->DosT, 0, PHP_ROUND_HALF_UP);
			$this->TresT = round($this->TresT, 0, PHP_ROUND_HALF_UP);
			$this->CuatroT = round($this->CuatroT, 0, PHP_ROUND_HALF_UP);


			$contador = 0;

			foreach ($row as $element) {
				$oMporcentajeoperacion = new Mporcentajeoperacion();
				$oMporcentajeoperacion->IdTipoSer = $IdConfigS;
				$oMporcentajeoperacion->IdSubtipoServ = $IdSubTipo;
				$oMporcentajeoperacion->Anio = $anio;
				$oMporcentajeoperacion->Descripcion = $element->Nombre;
				$data = $oMporcentajeoperacion->get_porcentajeopearcion();

				$Porcentajeoper = $data['data']->PorcentajeAnual;
				$AnioAnterior = $data['data']->AnioAnterior;

				//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE MANTENIMIENTO
				if ($IdConfigS == 1) {
					if ($element->Nombre == "Materiales") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 8;
						}
					}
					if ($element->Nombre == "Equipos") {
						if ($Porcentajeoper == '') {
							$Porcentajeoper = 0;
						}
					}
					if ($element->Nombre == "Mano de Obra") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 26;
						}
					}
					if ($element->Nombre == "Vehiculos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 7;
						}
					}
					if ($element->Nombre == "Contratistas") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 2;
						}
					}
					if ($element->Nombre == "Viaticos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 1;
						}
					}
					if ($element->Nombre == "Burden") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 16;
						}
					}
				}
				//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE SERVICIO
				if ($IdConfigS == 2) {
					if ($element->Nombre == "Materiales") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 11;
						}
					}
					if ($element->Nombre == "Equipos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 8;
						}
					}
					if ($element->Nombre == "Mano de Obra") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 22;
						}
					}
					if ($element->Nombre == "Vehiculos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 6;
						}
					}
					if ($element->Nombre == "Contratistas") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 2;
						}
					}
					if ($element->Nombre == "Viaticos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 1;
						}
					}
					if ($element->Nombre == "Burden") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 10;
						}
					}
				}
				//PORCENTAJES POR DEFECTO PARA TODOS LOS TIPOS DE PROYECTO
				if ($IdConfigS == 3) {
					if ($element->Nombre == "Materiales") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 20;
						}
					}
					if ($element->Nombre == "Equipos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 20;
						}
					}
					if ($element->Nombre == "Mano de Obra") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 9;
						}
					}
					if ($element->Nombre == "Vehiculos") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 2;
						}
					}
					if ($element->Nombre == "Contratistas") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 13;
						}
					}
					if ($element->Nombre == "Viaticos") {
						if ($Porcentajeoper == '') {
							$Porcentajeoper = 0;
						}
					}
					if ($element->Nombre == "Burden") {
						if ($Porcentajeoper == '' || $Porcentajeoper == 0) {
							$Porcentajeoper = 6;
						}
					}
				}

				if ($AnioAnterior == '') {
					$AnioAnterior = 0;
				}

				if ($contador == 0) {
					if ($Vueltas > 0) {
						$row[$contador]->AnioAnterior  +=  $AnioAnterior;
						$row[$contador]->PorcenAnioAnte  =  100;
						$row[$contador]->PrimerT  +=  $this->UnoT;
						$row[$contador]->SegundoT += $this->DosT;
						$row[$contador]->TercerT   += $this->TresT;
						$row[$contador]->CuartoT  += $this->CuatroT;
						$TotalAnual = $this->UnoT + $this->DosT + $this->TresT + $this->CuatroT;

						$row[$contador]->TotalAnual += $TotalAnual;
						$row[$contador]->PorcenAnual  =  100;
					} else {
						$row[$contador]->AnioAnterior  =  $AnioAnterior;
						$row[$contador]->PorcenAnioAnte  =  100;
						$row[$contador]->PrimerT  =  $this->UnoT;
						$row[$contador]->SegundoT = $this->DosT;
						$row[$contador]->TercerT   = $this->TresT;
						$row[$contador]->CuartoT  = $this->CuatroT;
						$TotalAnual = $this->UnoT + $this->DosT + $this->TresT + $this->CuatroT;
						$row[$contador]->TotalAnual = $TotalAnual;
						$row[$contador]->PorcenAnual  =  100;
					}
				} else {
					$Valor1 = ($Porcentajeoper * $this->UnoT) / 100;
					$Valor2 = ($Porcentajeoper * $this->DosT) / 100;
					$Valor3 = ($Porcentajeoper * $this->TresT) / 100;
					$Valor4 = ($Porcentajeoper * $this->CuatroT) / 100;

					$Valor1 = round($Valor1, 0, PHP_ROUND_HALF_UP);
					$Valor2 = round($Valor2, 0, PHP_ROUND_HALF_UP);
					$Valor3 = round($Valor3, 0, PHP_ROUND_HALF_UP);
					$Valor4 = round($Valor4, 0, PHP_ROUND_HALF_UP);

					if ($Vueltas > 0) {
						$row[$contador]->AnioAnterior  +=  $AnioAnterior;
						$row[$contador]->PrimerT  += $Valor1;
						$row[$contador]->SegundoT += $Valor2;
						$row[$contador]->TercerT   += $Valor3;
						$row[$contador]->CuartoT  += $Valor4;

						$TotalAnual = $Valor1 + $Valor2 + $Valor3 + $Valor4;
						$row[$contador]->TotalAnual += round($TotalAnual, 0, PHP_ROUND_HALF_UP);
					} else {
						$row[$contador]->AnioAnterior  =  $AnioAnterior;
						$row[$contador]->PorcenAnioAnte  =  0;
						$row[$contador]->PrimerT  = $Valor1;
						$row[$contador]->SegundoT = $Valor2;
						$row[$contador]->TercerT   = $Valor3;
						$row[$contador]->CuartoT  = $Valor4;

						$TotalAnual = $Valor1 + $Valor2 + $Valor3 + $Valor4;
						$row[$contador]->TotalAnual = round($TotalAnual, 0, PHP_ROUND_HALF_UP);
						$row[$contador]->PorcenAnual  =  0;
					}
				}

				$contador++;
			}

			$Vueltas++;
		}

		//CALCULO DE PORCENTAJES
		$contadorgral = 0;
		foreach ($row as $element) {
			$TotalGral = $row[0]->AnioAnterior;
			$TotalGralAnual = $row[0]->TotalAnual;
			if ($contadorgral > 0) {
				$porcentaje = 0;
				if ($TotalGral != 0) {
					$porcentaje = ($element->AnioAnterior * 100) / $TotalGral;
				}

				$row[$contadorgral]->PorcenAnioAnte = number_format($porcentaje, 1, '.', '');

				$porcentaje2 = 0;
				if ($TotalGralAnual != 0) {
					$porcentaje2 = ($element->TotalAnual * 100) / $TotalGralAnual;
				}


				$row[$contadorgral]->PorcenAnual = number_format($porcentaje2, 1, '.', '');
			}

			$contadorgral++;
		}


		return $row;
	}
}
