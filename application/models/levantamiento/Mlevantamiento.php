<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mlevantamiento extends BaseModel
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
    
    //referencia a trablas externas
    public $IdTrabajador;
    public $IdConfigS=0;
    public $TypeFinanciero=0;
    

    public function __construct()
    {
        parent::__construct();

        // Init Properties
     
        $this->IdServicio= 0;
        $this->Cliente= '';
        $this->Tipo_Serv= '';
        $this->Personal= '';
        $this->Vehiculo= '';
        $this->Fecha_I= '';
        $this->Fecha_F= '';
        $this->Hora_I= '';
        $this->Hora_F= '';
        $this->Distancia= '';
        $this->Observaciones= '';
        $this->Materiales= '';
        $this->IdSucursal= '';
        $this->RegEstatus= '';   
        $this->Direccion= '';
        $this->Folio= '';
        $this->IdVehiculo= '';
        $this->IdCliente= '';
        $this->IdClienteS= '';
        $this->EstadoS= '';
        $this->Velocidad= '';       
        $this->Econtacto= '';
        $this->Contacto= '';
        $this->EquiposD= '';
        $this->MaterialesD= '';
        $this->ViaticosD= '';
        $this->ContratistasD= '';
        $this->Comentario= '';
        $this->BurdenTotal= '';
        $this->ManoObraT= '';
        $this->CostoV= '';
        $this->ComentarioFin= '';
        $this->IdContrato= '';
        $this->NumContrato= '';
        $this->Factura= '';   
        $this->EstadoFactura= '';
        $this->FechaMod= '';
        $this->Facturable= '';
        $this->TipoVehiculo='';
        $this->FechaInicioFin='';
        $this->KeyReferences='';
    }

    public function get_list()
    {
                
        $this->db->select('

        distinct(s.IdServicio), 
        s.IdServicio,s.Cliente,
        s.Tipo_Serv,
        s.Fecha_I,
        s.Fecha_F,
        s.Distancia,
        s.ComentarioFin,
        s.Direccion,
        s.Folio,
        s.RegEstatus,
        DATE_FORMAT(fs.FechaInicio, "%d-%m-%Y") as FechaTrabajo,
        cli.Nombre as NomCli,
        s.Observaciones,
        ts.Concepto as Servicio,
        cs.Latitud,
        cs.Longitud,
        cs.IdIconoEmp,
        css.IdCotizacionServicio,
        css.Folio as FolioC,
        css.totalMateriales,
        css.totalManoDeObra,
        css.totalMiscelaneos,
        css.costoKm,
        css.totalGlobal,
        s.EstadoS,
        css.Estatus

        ');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio fs','s.IdServicio=fs.IdServicio','inner');
        $this->db->join('vehiculoservicio vs','vs.IdServicio=s.IdServicio','inner');
        $this->db->join('clientes cli','s.IdCliente=cli.IdCliente','inner');
        $this->db->join('clientesucursal cs','s.IdClienteS=cs.IdClienteS','inner');
        $this->db->join('tiposervicio ts','s.Tipo_Serv=ts.IdTipoSer','inner');

        $this->db->join('cotizacion_servicio css','s.IdServicio=css.IdServicio','left');

        
        $this->db->where('ts.IdConfigS', '6');
        $this->db->where('s.EstadoS', 'FINALIZADA');

        $this->db->where('s.IdSucursal', $this->IdSucursal);
        
        if (!empty($this->IdTrabajador)) {
            $this->db->where('fs.IdTrabajador', $this->IdTrabajador);
        }
         if (!empty($this->Tipo_Serv)) {
            $this->db->where('s.Tipo_Serv', $this->Tipo_Serv);
        }
        if (!empty($this->Fecha_I) && !empty($this->Fecha_F)) {
             $and =' s.Fecha_I >=\''.$this->Fecha_I.'\' and s.Fecha_F <= \''.$this->Fecha_F.'\' ';
            $this->db->where($and);
        }
       
        // if (!empty($this->EstadoS)) {
        //     $this->db->where('s.EstadoS', $this->EstadoS);
        // }

        if (!empty($this->RegEstatus)) {
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }
         if (!empty($this->Folio)) {
            $where =' (s.folio like \'%'.$this->Folio.'%\'  or s.NumContrato like \'%'.$this->Folio.'%\' or cli.Nombre like  \'%'.$this->Folio.'%\' or cs.Nombre like \'%'.$this->Folio.'%\')';
            $this->db->where($where);
        }
        
        $this->db->order_by('s.IdServicio', 'DESC');
        //Pagination
        $this->set_pagination();
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    
}