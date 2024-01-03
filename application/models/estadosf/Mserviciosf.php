<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mserviciosf extends BaseModel
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
    public $Color;
    public $EstadoS;
    public $NombreArchivo;
    public $IdEquipo;
    public $Imagen;

    public $Velocidad;
    public $Econtacto;
    public $Contacto;

    public $MaterialesD;
    public $EquiposD;
    public $ViaticosD;
    public $ContratistasD;

    public $IdTrabajador;
    public $EstadoServicio;
    public $Servicio;
    public $Comentario;

    public $BurdenTotal;
    public $ManoObraT;
    public $CostoV;
    public $ComentarioTec;
    public $ComentarioFin;
    public $IdContrato;
    public $NumContrato;
    public $IdSubIndice;
    public $Facturable;
    public $Factura;
    public $Count;
    public $nIni;
    public $nTam;


    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdServicio = '';
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
        $this->IdCliente = 0;
        $this->IdClienteS = 0;
        $this->EstadoS = '';
        $this->Velocidad = '';
        $this->Econtacto = '';
        $this->Contacto = '';
        $this->EquiposD = '';
        $this->MaterialesD = '';
        $this->ViaticosD = '';
        $this->ContratistasD = '';
        $this->EstadoServicio = '';
        $this->Comentario = '';
        $this->NombreArchivo = '';
        $this->IdEquipo = '';
        $this->Imagen = '';
        $this->BurdenTotal = '';
        $this->ManoObraT = '';
        $this->CostoV = '';

        $this->ComentarioFin = '';

        $this->IdContrato = '';
        $this->NumContrato = '';
        $this->IdSubIndice = '';
        $this->Factura = '';
        $this->Facturable = '';
        $this->Count = '';
        $this->nIni = '';
        $this->nTam = '';
    }


    public function get_list_servicioFinancieroAnioBurdenMano2()
    {
        // $sql = 'select * from servicio s inner join tiposervicio ts on s.Tipo_Serv=ts.IdTipoSer inner join configservicio cs  on cs.IdConfigS=ts.IdConfigS where s.EstadoS !="Cancelada" and s.EstadoS="CERRADA" and s.IdSucursal=' . $this->IdSucursal;
        $this->db->select('s.*');
        $this->db->from('servicio s');
        $this->db->join('tiposervicio ts ',' s.Tipo_Serv=ts.IdTipoSer','inner');
        $this->db->join('configservicio cs  ',' cs.IdConfigS=ts.IdConfigS','inner');
        // $this->db->where('s.EstadoS !="Cancelada"');
        $this->db->where('s.EstadoS =', "CERRADA");



        $this->db->where('s.IdSucursal', $this->IdSucursal);

        if (!empty($this->RegEstatus)) {
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdPlanFactura)) {
            $this->db->where('s.IdPlanFactura', $this->IdPlanFactura); // no está en la tabla de servicios.
        }

        if (!empty($this->IdClienteS)) {
            $this->db->where('s.IdClienteS', $this->IdClienteS);
        }
        
        if (!empty($this->IdCliente)) {
            $this->db->where('s.IdCliente', $this->IdCliente);
        }
        
        if (!empty($this->IdContrato)) {
            $this->db->where('s.IdContrato', $this->IdContrato);
        }
        if (!empty($this->Tipo_Serv)) {
            $this->db->where('cs.IdConfigS', $this->Tipo_Serv);
        }
    
        if (!empty($this->IdSubIndice)) {
            $this->db->where('s.Tipo_Serv', $this->IdSubIndice);
        }
        if ($this->Fecha_I=='') { 
            if ($this->Fecha_F!='') {
                //$this->db->like('s.Fecha_F', $this->Fecha_F);
                $this->db->where('s.Fecha_F like \'%'.$this->Fecha_F.'%\'');
            }
        }else{
            if($this->Fecha_I!=''){
                $this->db->where('s.Fecha_F between \'' . $this->Fecha_I . '\' and  \'' . $this->Fecha_F . '\'');
            }
            
        }
        //echo $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
