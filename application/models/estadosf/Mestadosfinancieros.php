<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadosfinancieros extends BaseModel
{
    // Properties
    public $IdEstadoF;
    public $IdConfigS;

    public $IdSucursal;
    public $Anio;
    public $Mes;
    public $Facturacion;
    public $Mes2;
    public $IdTipoServ;
    public $IdCliente;
    public $IdClienteS;
    public $IdContrato;

    public $Materiales;
    public $Equipos;
    public $ManoDeObra;
    public $Vehiculos;
    public $Contratistas;
	public $Viaticos;
    public $Burden;
	public $FacturacionPMensual;

    public $Anio2;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdEstadoF = 0;
        $this->IdConfigS = '';
        $this->Anio = '';
        $this->IdSucursal = '';
        $this->Anio2 = '';
        $this->Mes2 = '';
        $this->Mes = '';
        $this->Mes2 = '';
        $this->Facturacion = 0;
        $this->IdTipoServ = 0;
        $this->IdCliente = 0;
        $this->IdClienteS = 0;
        $this->IdContrato = 0;

        $this->Materiales = 0;
        $this->Equipos = 0;
        $this->ManoDeObra = 0;
        $this->Vehiculos = 0;
        $this->Contratistas = 0;
		$this->Viaticos = 0;
        $this->Burden = 0;
		$this->FacturacionPMensual = 0;
    }


    public function get_list_estadofinanciero()
    {

        $this->db->select('*');
        $this->db->from('estadofinanciero');
        $this->db->where('IdSucursal', $this->IdSucursal);

        if (!empty($this->IdPlanFactura)) {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->Anio)) {
            $this->db->where('Anio='.$this->Anio);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }
        if (!empty($this->IdClienteS)) {
            $this->db->where('IdClienteS', $this->IdClienteS);
        }
        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }
        if (!empty($this->IdContrato)) {
            $this->db->where('IdContrato', $this->IdContrato);
        }
        if (!empty($this->IdConfigS)) {
            $this->db->where('IdConfigS', $this->IdConfigS);
        }
        if (!empty($this->IdTipoServ)) {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        if (!empty($this->IdSubIndice)) {
            $this->db->where('s.Tipo_Serv', $this->IdSubIndice);
        }
        if (!empty($this->Mes)) {

            $this->db->where('Mes between \'' . $this->Mes . '\' and \'' . $this->Mes2 . '\'');
        }

        //Pagination
        //echo $this->db->get_compiled_select();
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }


    public function set_insert_estadofinanciero()
    {
    
        $this->db->set('IdConfigS', $this->IdConfigS);  
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Anio', $this->Anio); 
        $this->db->set('Mes', $this->Mes);
        $this->db->set('Facturacion', $this->Facturacion);
        $this->db->set('IdTipoServ', $this->IdTipoServ);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdContrato', $this->IdContrato);
        $this->db->set('Materiales', $this->Materiales);
        $this->db->set('Equipos', $this->Equipos);
        $this->db->set('ManoDeObra', $this->ManoDeObra);
        $this->db->set('Vehiculos', $this->Vehiculos);
        $this->db->set('Contratistas', $this->Contratistas);
        $this->db->set('Burden', $this->Burden);
        $this->db->set('Viaticos', $this->Viaticos);
        $this->db->set('FacturacionPMensual', $this->FacturacionPMensual);

        $this->db->insert('estadofinanciero');
        return $this->db->insert_id();
    }

    public function set_update_estadofinanciero()
    {
        $this->db->where('IdEstadoF', $this->IdEstadoF);
        $this->db->set('Facturacion', $this->Facturacion);
        $this->db->set('IdCliente', $this->IdCliente); 
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdContrato', $this->IdContrato);
        $this->db->set('Materiales', $this->Materiales);
        $this->db->set('Equipos', $this->Equipos);
        $this->db->set('ManoDeObra', $this->ManoDeObra);
        $this->db->set('Vehiculos', $this->Vehiculos);
        $this->db->set('Contratistas', $this->Contratistas);
        $this->db->set('Burden', $this->Burden);
        $this->db->set('Viaticos', $this->Viaticos);
        $this->db->set('FacturacionPMensual', $this->FacturacionPMensual);
        $this->db->update('estadofinanciero');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_facturacion(){
        $this->db->select('ef.*,SUM(Facturacion) as FacturacionS ');
        $this->db->from('estadofinanciero ef');
        $this->db->where('ef.IdSucursal', $this->IdSucursal);
        if (!empty($this->Anio2))
        {
            $this->db->where('ef.Anio', $this->Anio2);
        }
        if (!empty($this->Mes2))
        {
            $this->db->where('ef.Mes<=', $this->Mes2);
        }
        #echo $this->db->get_compiled_select();
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_facturacionMesfijo(){
        $this->db->select('ef.*,SUM(Facturacion) as FacturacionS ');
        $this->db->from('estadofinanciero ef');
        $this->db->where('ef.IdSucursal', $this->IdSucursal);
        if (!empty($this->Anio2))
        {
            $this->db->where('ef.Anio', $this->Anio2);
        }
        if (!empty($this->Mes2))
        {
            $this->db->where('ef.Mes', $this->Mes2);
        }
        #echo $this->db->get_compiled_select();
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_facturacionAnioAct(){
        $this->db->select('ef.*,SUM(Facturacion) as FacturacionS ');
        $this->db->from('estadofinanciero ef');
        $this->db->where('ef.IdSucursal', $this->IdSucursal);
        if (!empty($this->Anio2))
        {
            $this->db->where('ef.Anio', $this->Anio2);
        }
        if (!empty($this->Mes2))
        {
            $this->db->where('ef.Mes<=', $this->Mes2);
        }
        #echo $this->db->get_compiled_select();
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
