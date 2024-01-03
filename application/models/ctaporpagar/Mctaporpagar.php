<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mctaporpagar extends BaseModel
{
	// Properties
	public $IdCtaPagar;
	public $IdSucursal;
	public $IdAsociado;
	public $IdProveedor;
	public $NumCuenta;
	public $NumFactura;
	public $Credito;
	public $Monto;
	public $TipoSelect;
	public $FechaFactura;
	public $FechaPago;
	public $FechaRealPago;
	public $RegEstatus;
	public $FechaReg;
	public $FechaMod;
	public $Estatus;
	public $Vigencia;
	public $TipoCuenta;
	public $Factura;
	public $ArchivoUno;
	public $ArchivoDos;
	public $Observacion;
	public $IdCliente;
	public $IdSucursalCliente;
	public $IdContrato;
	public $IdConfigS;
	public $IdTipoServicio;
	public $Fecha_I;
	public $Fecha_F;
	public $Fecha;

	public $Anio;
	public $Mes;

	public $IdClienteS;
	public $Tipo_Serv;
	public $IdTipoServ;


	public function __construct()
	{
		parent::__construct();

		// Init Properties
	}

	public function insert()
	{

		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('IdAsociado', $this->IdAsociado);
		$this->db->set('IdProveedor', $this->IdProveedor);
		$this->db->set('NumFactura', $this->NumFactura);
		$this->db->set('Credito', $this->Credito);
		$this->db->set('Monto', $this->Monto);
		$this->db->set('TipoCuenta', $this->TipoCuenta);
		$this->db->set('TipoSelect', $this->TipoSelect);
		$this->db->set('RegEstatus', $this->RegEstatus);
		$this->db->set('FechaFactura', $this->FechaFactura);
		$this->db->set('Estatus', "NO");
		$this->db->set('Vigencia', "No vencido");
		$this->db->set('FechaPago', $this->FechaPago);
		$this->db->set('FechaReg', $this->FechaReg);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Factura', $this->Factura);
		$this->db->set('ArchivoUno', $this->ArchivoUno);
		$this->db->set('ArchivoDos', $this->ArchivoDos);
		$this->db->set('Observacion', "");
		$this->db->set('IdCliente', $this->IdCliente);
		$this->db->set('IdSucursalCliente', $this->IdSucursalCliente);
		$this->db->set('IdContrato', $this->IdContrato);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('IdTipoServicio', $this->IdTipoServicio);
		$this->db->insert('ctaporpagar');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdCtaPagar', $this->IdCtaPagar);

		$this->db->set('IdAsociado', $this->IdAsociado);
		$this->db->set('IdProveedor', $this->IdProveedor);
		$this->db->set('NumFactura', $this->NumFactura);
		$this->db->set('Monto', $this->Monto);
		$this->db->set('Credito', $this->Credito);
		$this->db->set('TipoSelect', $this->TipoSelect);
		$this->db->set('FechaFactura', $this->FechaFactura);
		$this->db->set('FechaPago', $this->FechaPago);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Factura', $this->Factura);
		$this->db->set('ArchivoUno', $this->ArchivoUno);
		$this->db->set('ArchivoDos', $this->ArchivoDos);
		$this->db->set('IdCliente', $this->IdCliente);
		$this->db->set('IdSucursalCliente', $this->IdSucursalCliente);
		$this->db->set('IdContrato', $this->IdContrato);
		$this->db->set('IdConfigS', $this->IdConfigS);
		$this->db->set('IdTipoServicio', $this->IdTipoServicio);
		$this->db->update('ctaporpagar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function delete()
	{
		$this->db->where('IdCtaPagar', $this->IdCtaPagar);
		$this->db->set('RegEstatus', 'B');
		$this->db->update('ctaporpagar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function changePago()
	{
		$this->db->where('IdCtaPagar', $this->IdCtaPagar);
		$this->db->set('Estatus', 'SI');
		$this->db->set('Observacion', $this->Observacion);
		$this->db->set('FechaRealPago', $this->FechaRealPago);
		$this->db->update('ctaporpagar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_recovery()
	{
		$this->db->select('cta.*,p.Nombre as Proveedor');
		$this->db->from('ctaporpagar cta');
		$this->db->join('proveedores p', 'cta.IdProveedor = p.IdProveedor');
		$this->db->where('cta.IdCtaPagar', $this->IdCtaPagar);

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
				'status' => false,
				'data' => new Mctaporpagar()
			];
		}
	}

	public function get_list()
	{
		$this->db->select('cpp.*, cl.Nombre as Cliente, pv.Nombre as Proveedor, 
		DATE_FORMAT(cpp.FechaFactura, "%d-%m-%Y") as FechaFactura,
		DATE_FORMAT(cpp.FechaPago, "%d-%m-%Y") as FechaPago,
		DATE_FORMAT(cpp.FechaRealPago, "%d-%m-%Y") as FechaRealPago,
        case 
        when TipoSelect =1 then "Ventas"
        WHEN TipoSelect =2 then "Burden"
        WHEN TipoSelect =3 then "Vehiculos"
        WHEN TipoSelect =4 then "G&A"
        WHEN TipoSelect =5 then "Costos Financieros"
		WHEN TipoSelect =6 then "Materiales"
		WHEN TipoSelect =7 then "Equipos"
		WHEN TipoSelect =8 then "Contratistas"
		WHEN TipoSelect =9 then "Viáticos"
		WHEN TipoSelect =10 then "Mano de Obra Directa"
		WHEN TipoSelect =11 then "Mano de Obra Leyes Sociles"
		WHEN TipoSelect =12 then "Mano de Obra Otros"
        end as Departamento, 
        case 
        when TipoSelect =1 then gd.Gasto
        WHEN  TipoSelect =2 then  cdv.Descripcion
        WHEN  TipoSelect =3 then  cvo.Descripcion
        WHEN  TipoSelect =4 then  cga.Descripcion
        WHEN  TipoSelect =5 then  cf.Descripcion
        end as Descripcion,
        @NumeroC := case 
         when TipoSelect =1 then gd.NumCuenta
        WHEN  TipoSelect =2 then  cdv.NumeroCuenta
        WHEN  TipoSelect =3 then  cvo.NumeroCuenta
        WHEN  TipoSelect =4 then  cga.NumeroCuenta
        WHEN  TipoSelect =5 then  cf.NumeroCuenta
        end as Cuenta', false);
		$this->db->from('ctaporpagar cpp');
		$this->db->join(' gastosdirectos gd', ' cpp.IdAsociado=gd.IdGasto ', 'left');
		$this->db->join(' costodeptoventa cdv', ' cpp.IdAsociado=cdv.IdCostoDeptoVenta', 'left');
		$this->db->join(' costovehope cvo', ' cpp.IdAsociado=cvo.IdCostoVehOpe', 'left');
		$this->db->join(' costoga cga', ' cpp.IdAsociado=cga.IdCostoGA ', 'left');
		$this->db->join(' costofinanciero cf', ' cpp.IdAsociado=cf.IdCostoFinanciero ', 'left');
		$this->db->join(' clientes cl', ' cpp.IdCliente=cl.IdCliente ', 'left');
		$this->db->join(' proveedores pv', ' cpp.IdProveedor=pv.IdProveedor ', 'left');
		$this->db->where('cpp.RegEstatus', 'A');
		$this->db->where('cpp.IdSucursal', $this->IdSucursal);
		$this->db->where('cpp.TipoCuenta', $this->TipoCuenta);

		if (!empty($this->NumCuenta)) {
			$this->db->like('cpp.NumFactura', $this->NumCuenta);

			//$like =" (cga.NumeroCuenta like '%".$this->NumCuenta."%' or cga.NumFactura like '%".$this->NumCuenta."%')";
			//$this->db->where($like);
		}

		if ($this->IdProveedor > 0) {
			$this->db->where('cpp.IdProveedor', $this->IdProveedor);
		}

		if (!empty($this->TipoFiltro)) {
			$this->db->like('cpp.Estatus', $this->TipoFiltro);
		}

		if ($this->Fecha_I != '') {
			if ($this->TipoFiltro == "NO") {
				$where = '(cpp.FechaPago>=\'' . $this->Fecha_I . '\' and cpp.FechaPago<=\'' . $this->Fecha_F . '\' )';
			}
			if ($this->TipoFiltro == "SI") {
				$where = '(cpp.FechaRealPago>=\'' . $this->Fecha_I . '\' and cpp.FechaRealPago<=\'' . $this->Fecha_F . '\' )';
			}
			$this->db->where($where);
		}

		if (!empty($this->Vigencia)) {
			$this->db->WHERE('cpp.Vigencia', $this->Vigencia);
		}

		$this->db->order_by('cpp.FechaPago', 'ASC');


		//Pagination
		$this->set_pagination();
		#echo $result = $this->db->get_compiled_select();
		$response = $this->db->get();
		return $response->result();
	}

	public function get_sumAmount()
	{
		$this->db->select('SUM(Monto) as sumAmount');
		$this->db->from('ctaporpagar');
		$this->db->where('RegEstatus', 'A');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('TipoCuenta', $this->TipoCuenta);

		if (!empty($this->TipoFiltro)) {
			$this->db->WHERE('Estatus', $this->TipoFiltro);
		}

		if (!empty($this->Vigencia)) {
			$this->db->WHERE('Vigencia', $this->Vigencia);
		}

		if ($this->IdProveedor > 0) {
			$this->db->where('IdProveedor', $this->IdProveedor);
		}

		if ($this->Fecha_I != '') {
			$where = '(FechaPago>=\'' . $this->Fecha_I . '\' and FechaPago<=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
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
				'status' => false,
				'data' => new Mseguimientocliente()
			];
		}
	}

	public function updateValidity()
	{
		$this->db->where('Estatus', "NO");
		$where = ' ( CURRENT_DATE()>FechaPago) ';
		$this->db->where($where);
		$this->db->set('Vigencia', 'Vencido');
		$this->db->update('ctaporpagar');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//!AQUI EMPIEZA EL GET PARA LA GRÁFICA GLOBAL -ANDREA 

	public function get_EstimadoGlobalCuentasPPagar()
	{
		$this->db->select('MONTH(cp.FechaPago),cp.FechaPago, cp.IdCtaPagar, cp.Vigencia,SUM(cp.Monto) as SumaGlobalCPP');
		$this->db->from('ctaporpagar cp');
		$this->db->where('cp.IdSucursal', $this->IdSucursal);
		if (!empty($this->Fecha)) {
			$this->db->like('cp.FechaPago', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('cp.FechaPago>', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('cp.FechaPago<', $this->FechaF);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}

	public function get_ActualGlobalCuentasPPagar()
	{
		$this->db->select('MONTH(cp.FechaRealPago),cp.FechaRealPago, cp.IdCtaPagar, cp.Vigencia,SUM(cp.Monto) as SumaGlobalActualCPP');
		$this->db->from('ctaporpagar cp');
		$this->db->where('cp.IdSucursal', $this->IdSucursal);
		if (!empty($this->Fecha)) {
			$this->db->like('cp.FechaRealPago', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('cp.FechaRealPago>', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('cp.FechaRealPago<', $this->FechaF);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}

	public function getSumGraphics()
	{
		$this->db->select('SUM(Monto) as sumAmount');
		$this->db->from('ctaporpagar');
		$this->db->where('RegEstatus', 'A');
		$this->db->where('IdSucursal', $this->IdSucursal);
		$this->db->where('Estatus', 'SI');

		if (!empty($this->Fecha)) {
			$this->db->like('FechaRealPago', $this->Fecha);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}

	//PARA MES

	public function get_listMaterial(){
        $this->db->select('SUM(Monto) as MontoMaterial');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 6);

        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

		if (!empty($this->IdClienteS)) {
            $this->db->where('IdSucursalCliente', $this->IdClienteS);
        }

		if (!empty($this->IdContrato)) {
            $this->db->where('IdContrato', $this->IdContrato);
        }

		if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServicio', $this->IdTipoServ);
        }

		if ($this->FechaRealPago!='') {
			$this->db->where('FechaRealPago like \'%'.$this->FechaRealPago.'%\'');
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listEquipos(){
        $this->db->select('SUM(Monto) as MontoEquipo');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 7);

        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

		if (!empty($this->IdClienteS)) {
            $this->db->where('IdSucursalCliente', $this->IdClienteS);
        }

		if (!empty($this->IdContrato)) {
            $this->db->where('IdContrato', $this->IdContrato);
        }

		if (!empty($this->IdSubIndice)) {
            $this->db->where('IdTipoServicio', $this->IdSubIndice);
        }

		if ($this->FechaRealPago!='') {
			$this->db->where('FechaRealPago like \'%'.$this->FechaRealPago.'%\'');
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listContratista(){
        $this->db->select('SUM(Monto) as MontoContratista');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 8);

        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

		if (!empty($this->IdClienteS)) {
            $this->db->where('IdSucursalCliente', $this->IdClienteS);
        }

		if (!empty($this->IdContrato)) {
            $this->db->where('IdContrato', $this->IdContrato);
        }
        if (!empty($this->Tipo_Serv)) {
            $this->db->where('IdConfigS', $this->Tipo_Serv);
        }

		if (!empty($this->IdSubIndice)) {
            $this->db->where('IdTipoServicio', $this->IdSubIndice);
        }

		if ($this->FechaRealPago!='') {
			$this->db->where('FechaRealPago like \'%'.$this->FechaRealPago.'%\'');
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listViaticos(){
        $this->db->select('SUM(Monto) as MontoViaticos');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 9);

        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }

		if (!empty($this->IdClienteS)) {
            $this->db->where('IdSucursalCliente', $this->IdClienteS);
        }

		if (!empty($this->IdContrato)) {
            $this->db->where('IdContrato', $this->IdContrato);
        }
        if (!empty($this->Tipo_Serv)) {
            $this->db->where('IdConfigS', $this->Tipo_Serv);
        }

		if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServicio', $this->IdTipoServ);
        }

		if ($this->FechaRealPago!='') {
			$this->db->where('FechaRealPago like \'%'.$this->FechaRealPago.'%\'');
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
				'data' => new Mctaporpagar()
			];
		}
    }

	//PARA ANIO

	public function get_listMaterialAnio(){
        $this->db->select('SUM(Monto) as MontoMaterialAnio');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 6);

       // if (!empty($this->IdCliente)) {
        //     $this->db->where('IdCliente', $this->IdCliente);
        // }

		// if (!empty($this->IdClienteS)) {
        //     $this->db->where('IdSucursalCliente', $this->IdClienteS);
        // }

		// if (!empty($this->IdContrato)) {
        //     $this->db->where('IdContrato', $this->IdContrato);
        // }

		// if (!empty($this->IdTipoServ)) {
        //     $this->db->where('IdTipoServicio', $this->IdTipoServ);
        // }

		if ($this->Mes != '') {
			$where = '(MONTH(FechaRealPago)>=\'' . '01'. '\' and MONTH(FechaRealPago)<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listEwuipoAnioAct(){
        $this->db->select('SUM(Monto) as MontoEquiAnio');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 7);

        // if (!empty($this->IdCliente)) {
        //     $this->db->where('IdCliente', $this->IdCliente);
        // }

		// if (!empty($this->IdClienteS)) {
        //     $this->db->where('IdSucursalCliente', $this->IdClienteS);
        // }

		// if (!empty($this->IdContrato)) {
        //     $this->db->where('IdContrato', $this->IdContrato);
        // }

		// if (!empty($this->IdTipoServ)) {
        //     $this->db->where('IdTipoServicio', $this->IdTipoServ);
        // }

		if ($this->Mes != '') {
			$where = '(MONTH(FechaRealPago)>=\'' . '01'. '\' and MONTH(FechaRealPago)<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
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
				'status' => false,
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listContratistaAnio(){
        $this->db->select('SUM(Monto) as MontoContratistaAnio');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 8);

        // if (!empty($this->IdCliente)) {
        //     $this->db->where('IdCliente', $this->IdCliente);
        // }

		// if (!empty($this->IdClienteS)) {
        //     $this->db->where('IdSucursalCliente', $this->IdClienteS);
        // }

		// if (!empty($this->IdContrato)) {
        //     $this->db->where('IdContrato', $this->IdContrato);
        // }

		// if (!empty($this->IdTipoServ)) {
        //     $this->db->where('IdTipoServicio', $this->IdTipoServ);
        // }

		if ($this->Mes != '') {
			$where = '(MONTH(FechaRealPago)>=\'' . '01'. '\' and MONTH(FechaRealPago)<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listViaticosAnio(){
        $this->db->select('SUM(Monto) as MontoViaticosAnio');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 9);

        // if (!empty($this->IdCliente)) {
        //     $this->db->where('IdCliente', $this->IdCliente);
        // }

		// if (!empty($this->IdClienteS)) {
        //     $this->db->where('IdSucursalCliente', $this->IdClienteS);
        // }

		// if (!empty($this->IdContrato)) {
        //     $this->db->where('IdContrato', $this->IdContrato);
        // }

		// if (!empty($this->IdTipoServ)) {
        //     $this->db->where('IdTipoServicio', $this->IdTipoServ);
        // }

		if ($this->Mes != '') {
			$where = '(MONTH(FechaRealPago)>=\'' . '01'. '\' and MONTH(FechaRealPago)<=\'' . $this->Mes . '\' )';
			$this->db->where($where);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	//PARA ANIO PASADO
	public function get_listMaterialAnioP(){
        $this->db->select('SUM(Monto) as MontoMaterialAnioP');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 6);

		if (!empty($this->Anio)) {
            $this->db->where('YEAR(FechaRealPago)', $this->Anio -1);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function  get_listEquiAnioP(){
        $this->db->select('SUM(Monto) as MontoEquiAnioP');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 6);


		if (!empty($this->Anio)) {
            $this->db->where('YEAR(FechaRealPago)', $this->Anio -1);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listContratistaAnioP(){
        $this->db->select('SUM(Monto) as MontoContratistaAnioP');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 8);

       

		if (!empty($this->Anio)) {
            $this->db->where('YEAR(FechaRealPago)', $this->Anio -1);
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
				'data' => new Mctaporpagar()
			];
		}
    }

	public function get_listViaticosAnioP(){
        $this->db->select('SUM(Monto) as MontoViaticosAnioP');
        $this->db->from('ctaporpagar'); 
        $this->db->where('IdSucursal',$this->IdSucursal);
		$this->db->where('TipoSelect=', 9);

		if (!empty($this->Anio)) {
            $this->db->where('YEAR(FechaRealPago)', $this->Anio -1);
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
				'data' => new Mctaporpagar()
			];
		}
    }




}
