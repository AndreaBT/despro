<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mctaporcobrar extends BaseModel
{
	// Properties
	public $IdCtaCobrar;
	public $IdFactura;
	public $IdSucursal;
	public $FechaCobro;
	public $Comentario;
	public $Estatus;
	public $RegEstatus;
	public $FechaReg;
	public $FechaMod;

	public $TipoFiltro;
	public $NombreCliente;
	public $FechaFacReal;

	public $Archivo;
	public $Observacion;

	public $Fecha_I;
	public $Fecha_F;

	public $FechaRealCobro;

	public function __construct()
	{
		parent::__construct();

		// Init Properties
		$this->IdCtaCobrar = 0;
		$this->IdFactura = 0;
		$this->IdSucursal = 0;
		$this->FechaCobro = '';
		$this->Comentario = '';
		$this->Estatus = '';
		$this->FechaReg = '';
		$this->RegEstatus = '';
		$this->FechaMod = '';
		$this->Archivo = '';
		$this->Observacion = '';
		$this->FechaFacReal;

		$this->FechaRealCobro = '';
	}

	public function insert()
	{

		$this->db->set('IdFactura', $this->IdFactura);
		$this->db->set('IdSucursal', $this->IdSucursal);
		$this->db->set('FechaCobro', $this->FechaCobro);
		//$this->db->set('Comentario', $this->Comentario);



		$this->db->set('Vigencia', 'No vencido');
		$this->db->set('Estatus', $this->Estatus);
		$this->db->set('RegEstatus', 'A');
		$this->db->set('FechaReg', $this->FechaReg);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Observacion', $this->Observacion);
		$this->db->set('Archivo', $this->Archivo);
		$this->db->insert('ctaporcobrar');
		return $this->db->insert_id();
	}

	public function update()
	{
		$this->db->where('IdCtaCobrar', $this->IdCtaCobrar);
		$this->db->set('FechaCobro', $this->FechaCobro);

		$this->db->set('Comentario', $this->Comentario);
		$this->db->set('FechaMod', $this->FechaMod);
		$this->db->update('ctaporcobrar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function delete()
	{
		$this->db->where('IdCtaCobrar', $this->IdCtaCobrar);
		$this->db->set('RegEstatus', 'B');
		$this->db->update('ctaporcobrar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function ChangeEstatus()
	{
		$this->db->where('IdCtaCobrar', $this->IdCtaCobrar);
		//nuevo
		$this->db->set('FechaRealCobro', $this->FechaRealCobro);
		//fin nuevo
		$this->db->set('Estatus', 'SI');
		$this->db->set('Observacion', $this->Observacion);
		$this->db->update('ctaporcobrar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function AddArchivo()
	{
		$this->db->where('IdCtaCobrar', $this->IdCtaCobrar);
		$this->db->set('Estatus', 'NO');
		$this->db->set('Archivo', $this->Archivo);
		$this->db->update('ctaporcobrar');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_recovery()
	{
		$this->db->select('*');
		$this->db->from('ctaporcobrar');
		$this->db->where('IdCtaCobrar', $this->IdCtaCobrar);
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
				'data' => new Mctaporcobrar()
			];
		}
	}



	public function get_list()
	{
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y")
         as FechaFacReal,cpc.IdCtaCobrar,cpc.Estatus,f.Monto,f.ArchivoFactura,cpc.Archivo,cpc.FechaCobro,cpc.Vigencia,f.NoContrato,DATE_FORMAT(cpc.FechaRealCobro, "%d-%m-%Y") as FechaRealCobro, f.FolioFactReal,f.NoContrato ');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}

		if (!empty($this->Fecha_I) && !empty($this->Fecha_F)) {

			$where = '(cpc.FechaRealCobro>=\'' . $this->Fecha_I . '\' and cpc.FechaRealCobro<=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
		}

		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente);
		}


		$this->db->where('cpc.Estatus', $this->TipoFiltro);


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}

		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_ListNocobrado(){
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y")
         as FechaFacReal,cpc.IdCtaCobrar,cpc.Estatus,f.Monto,f.ArchivoFactura,cpc.Archivo,cpc.FechaCobro,cpc.Vigencia,f.NoContrato,DATE_FORMAT(cpc.FechaRealCobro, "%d-%m-%Y") as FechaRealCobro, f.FolioFactReal,f.NoContrato ');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}

		if (!empty($this->Fecha_I) && !empty($this->Fecha_F)) {

			$where = '(cpc.FechaCobro>=\'' . $this->Fecha_I . '\' and cpc.FechaCobro<=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
		}

		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente);
		}


		$this->db->where('cpc.Estatus', 'NO');


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}

		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_listSinFecha()
	{
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y")
         as FechaFacReal,cpc.IdCtaCobrar,cpc.Estatus,f.Monto,f.ArchivoFactura,cpc.Archivo,cpc.FechaCobro,cpc.Vigencia,f.NoContrato,DATE_FORMAT(cpc.FechaRealCobro, "%d-%m-%Y") as FechaRealCobro, f.FolioFactReal,f.NoContrato ');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}

		/*if (!empty($this->Fecha_I) && !empty($this->Fecha_F)) {
            
            $where = '(f.FechaFacReal>=\'' . $this->Fecha_I . '\' and f.FechaFacReal<=\'' . $this->Fecha_F . '\' )';
            $this->db->where($where);
            
        }*/

		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente);
		}


		$this->db->where('cpc.Estatus', $this->TipoFiltro);


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}

		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}


	public function get_listSuma()
	{
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y") as FechaFacReal,
        cpc.IdCtaCobrar,cpc.Estatus,SUM(f.Monto) as suma,cpc.Vigencia, f.FolioFactReal');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}
		if (!empty($this->Fecha_I) && !empty($this->Fecha_I)) {

			$where = '(cpc.FechaRealCobro>=\'' . $this->Fecha_I . '\' and cpc.FechaRealCobro<=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
		}

		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente, 'none');
		}


		$this->db->where('cpc.Estatus', 'SI');


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}
		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_listSumaNo()
	{
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y") as FechaFacReal,
        cpc.IdCtaCobrar,cpc.Estatus,SUM(f.Monto) as suma,cpc.Vigencia, f.FolioFactReal');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}
		if (!empty($this->Fecha_I) && !empty($this->Fecha_I)) {

			$where = '(cpc.FechaCobro>=\'' . $this->Fecha_I . '\' and cpc.FechaCobro<=\'' . $this->Fecha_F . '\' )';
			$this->db->where($where);
		}

		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente, 'none');
		}


		$this->db->where('cpc.Estatus', 'NO');


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}
		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_listSumaSinFecha()
	{
		$this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y") as FechaFacReal,
        cpc.IdCtaCobrar,cpc.Estatus,SUM(f.Monto) as suma,cpc.Vigencia, f.FolioFactReal');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'left');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		if (!empty($this->RegEstatus)) {
			$this->db->where('f.RegEstatus ', $this->RegEstatus);
		}
		if (!empty($this->NombreCliente)) {
			$this->db->where('f.IdCliente', $this->NombreCliente);
		}
		if (!empty($this->Sucursal)) {
			$this->db->where('f.IdClienteS', $this->Sucursal);
		}


		if (!empty($this->VigenciaFiltro)) {
			$this->db->where('cpc.Vigencia', $this->VigenciaFiltro);
		}

		if (!empty($this->Cliente)) {
			$this->db->like('f.FolioFactReal', $this->Cliente, 'none');
		}


		$this->db->where('cpc.Estatus', $this->TipoFiltro);


		if (!empty($this->NoContrato)) {
			$this->db->where('f.NoContrato', $this->NoContrato);
		}
		// Filters

		$this->db->where('f.Facturado ', "SI");
		$this->db->where('f.AFacturar ', "SI");
		$this->db->where('f.FechaFacReal !=', "0000-00-00");

		$this->db->order_by('f.FechaFacReal', 'asc');
		#echo $result = $this->db->get_compiled_select();

		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_NombresEmpresa()
	{
		$this->db->select(' cl.Nombre as Empresa, cl.IdCliente');
		$this->db->from('clientes cl');
		$this->db->where('cl.IdSucursal', $this->IdSucursal);
		$this->db->where('cl.RegEstatus','A');

		// $this->db->group_by("cl.IdCliente");
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	public function get_Empresa_SucursalV2()
	{
		$this->db->select('cl.Nombre as Empresa,cs.IdClienteS,cl.IdCliente,cs.Nombre as Sucursal');
		$this->db->from('clientes cl');
		$this->db->join('clientesucursal cs', ' cs.IdCliente = cl.IdCliente', 'inner');
		$this->db->where('cs.IdClienteS IN(SELECT MAX(cs.IdClienteS) from seguimientocliente cs GROUP by cs.IdCliente)');
		$this->db->where('cl.IdSucursal', $this->IdSucursal);
		
		
			
		$this->db->where('cl.IdCliente', $this->NombreCliente);
	


		$this->db->group_by("cs.IdClienteS");
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}


	public function get_NumeroContrato()
	{
		$this->db->select('nc.IdClienteS, nc.NumeroC as NoContrato, nc.IdContrato');
		$this->db->from('numcontrato nc');
		$this->db->join('clientesucursal cl', 'cl.IdClienteS= nc.IdClienteS', 'inner');
		$this->db->where('cl.IdSucursal', $this->IdSucursal);
		
		
		
		$this->db->where('nc.IdClienteS', $this->Sucursal);
		
		// $this->db->group_by("cl.IdClienteS");

		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}

	//!Update ESTATUS
	public function updateValidity()
	{

		$this->db->where('ctp.Estatus', "NO");
		$where = ' ( CURRENT_DATE()>ctp.FechaCobro) ';
		$this->db->where($where);

		$this->db->set('ctp.Vigencia', 'Vencido');
		$this->db->update('ctaporcobrar ctp');

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	//!AQUI EMPIEZA EL GET PARA LA GRÁFICA GLOBAL -ANDREA 

	public function get_EstimadoGlobalCuentasPCobrar()
	{
		$this->db->select('MONTH(cc.FechaCobro),cc.FechaCobro, cc.IdCtaCobrar,f.IdFactura,f.Monto,f.DiasCredito,cc.Vigencia,SUM(f.Monto) as SumaGlobal');
		$this->db->from('ctaporcobrar cc');
		$this->db->join('factura f', ' cc.IdFactura=f.IdFactura', 'inner');
		$this->db->where('cc.IdSucursal', $this->IdSucursal);
		if (!empty($this->Fecha)) {
			$this->db->like('cc.FechaCobro ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('cc.FechaCobro >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('cc.FechaCobro <', $this->FechaF);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}

	public function get_ActualGlobalCuentasPCobrar()
	{
		$this->db->select('MONTH(cc.FechaRealCobro),cc.FechaRealCobro, cc.IdCtaCobrar,f.IdFactura,f.Monto,f.DiasCredito,cc.Vigencia,SUM(f.Monto) as SumaGlobalActual');
		$this->db->from('ctaporcobrar cc');
		$this->db->join('factura f', ' cc.IdFactura=f.IdFactura', 'inner');
		$this->db->where('cc.IdSucursal', $this->IdSucursal);
		if (!empty($this->Fecha)) {
			$this->db->like('cc.FechaRealCobro ', $this->Fecha);
		}
		if (!empty($this->FechaI)) {
			$this->db->where('cc.FechaRealCobro >', $this->FechaI);
		}
		if (!empty($this->FechaF)) {
			$this->db->where('cc.FechaRealCobro <', $this->FechaF);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}

	public function getSumGraphics()
	{
		$this->db->select('SUM(f.Monto) as sumAmount');
		$this->db->from('factura f');
		$this->db->join('ctaporcobrar cpc', 'f.IdFactura=cpc.IdFactura', 'inner');
		$this->db->where('f.RegEstatus', 'A');
		$this->db->where('f.IdSucursal', $this->IdSucursal);
		$this->db->where('cpc.Estatus', 'SI');
		$this->db->where('f.Facturado', 'SI');
		$this->db->where('f.AFacturar', 'SI');

		if (!empty($this->Fecha)) {
			$this->db->like('FechaFacReal', $this->Fecha);
		}

		$this->set_pagination();
		$response = $this->db->get();
		return $response->result();
		#$result = $this->db->get_compiled_select();
	}
}
