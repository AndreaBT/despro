<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MpdfEstadosFGparche extends BaseModel
{
    //! MPlanfactura 
    public $IdPlanFactura;
    public $IdSucursal;
    public $Descripcion;

    //! Mserviciosf

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

    //!Mestadofinanciero
    public $IdEstadoF;
    public $IdConfigS;
    public $Anio;
    public $Mes;
    public $Facturacion;
    public $Mes2;
    public $IdTipoServ;
    public $Equipos;
    public $ManoDeObra;
    public $Vehiculos;
    public $Contratistas;
    public $Burden;

    //!MdetalleEstado
    public $Pasado;  
    public $PorcentajePasado;

    //!McostoGa
    public $IdCostoGA;
    public $NumeroCuenta;
    public $AnioAnterior;
    public $PrimerT;
    public $SegundoT;
    public $TercerT;
    public $CuartoT;

    //!Mactualcostoga
    public $MontoMes;
    public $FechaCompleta;

    //!MgastosDirectos
    public $IdEmpresa;
    public $NumCuenta;
    public $FechaAnterior;
    public $MontoAnterior; 
    public $MontoAnual;
    public $UnoT;
    public $DosT;
    public $TresT;
    public $CuatroT;
    public $FechaAct;
    public $Tipo;
    public $IdGasto;

    //!MactualVentas
            //?TODAS LAS PRPIEDADES YA ESTÁN 

    //!Mcostodeptoventas
    public $IdCostoDeptoVenta;

    //!Mactualoperaciones
        //?TODAS LAS PRPIEDADES YA ESTÁN 

    //!Mestadofupdate
    public $IdConfigServ;

    //!Mcostovehope
    public $IdCostoVehOpe;

    //!McostoFinanciero
    public $IdCostoFinanciero;

    //!MactualCostof
    public $Type='';

    //!MporcentajeOperacion
    public $IdPorcentajeOperacion;
    public $IdTipoSer;
    public $PorcentajeAnterior;
    public $PorcentajeAnual;
    public $IdSubtipoServ;

    //!Mactualcostove+
      //?TODAS LAS PRPIEDADES YA ESTÁN 

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        //! Plan Factura 
        $this->IdPlanFactura = '';
        $this->IdSucursal = '';
        $this->Descripcion = '';

        //!Mserviciof
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

        //!Mestadosfinancieros
        $this->IdEstadoF = 0;
        $this->IdConfigS = '';
        $this->Anio = '';
        $this->IdSucursal = '';
        $this->Anio = '';
        $this->Mes = '';
        $this->Mes2 = '';
        $this->Facturacion = 0;
        $this->IdTipoServ = 0;
        $this->Equipos = 0;
        $this->ManoDeObra = 0;
        $this->Vehiculos = 0;
        $this->Contratistas = 0;
        $this->Burden = 0;

        //!MdetalleEstado
        $this->Pasado = 0;
        $this->PorcentajePasado = 0;

        //!McostoGa
        $this->IdCostoGA = '';
        $this->NumeroCuenta='';
        $this->AnioAnterior='';
        $this->PrimerT='';
        $this->SegundoT='';
        $this->TercerT='';
        $this->CuartoT='';

        //!Mactualcostoga
        $this->MontoMes = 0;
        $this->FechaCompleta='';

        //!Mgastosdirectos
        $this->IdEmpresa = 0;
        $this->IdGasto = 0;
        $this->Gasto = '';
        $this->NumCuenta = '';
        $this->FechaAnterior ='';
        $this->MontoAnterior ='';
        $this->MontoAnual ='';
        $this->UnoT ='';
        $this->DosT ='';
        $this->TresT ='';
        $this->CuatroT ='';
        $this->FechaAct='';
        $this->Tipo='';

         //!MactualVentas
            //?TODAS LAS PRPIEDADES YA ESTÁN 

        //!Mcostodeptoventas
        $this->IdCostoDeptoVenta = '';

        //!Mactualoperaciones
        //?TODAS LAS PRPIEDADES YA ESTÁN 

        //!Mestadofupdate
        $this->IdConfigServ = '';
       
        //!Mcostovehope
        $this->IdCostoVehOpe = '';

        //!McostoFinanciero
        $this->IdCostoFinanciero = '';

        //!MporcentajeOperacion
        $this->IdPorcentajeOperacion = '0';
        $this->IdTipoSer = '0';
        $this->PorcentajeAnterior='0';
        $this->PorcentajeAnual='0';
        $this->IdSubtipoServ=0;

        //!Mactualcostove+
      //?TODAS LAS PRPIEDADES YA ESTÁN 
      
    }

    public function get_planfactura()
    {
        $this->db->select('*');
        $this->db->from('planfactura');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        if (!empty($this->IdPlanFactura))
        {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }
    
    public function get_list_planfactura()
    {
        $this->db->select('*');
        $this->db->from('planfactura');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->IdPlanFactura))
        {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        //echo $this->db->get_compiled_select();
    }

    public function get_list_servicioFinancieroAnioBurdenMano2()
    {
        // $sql = 'select * from servicio s inner join tiposervicio ts on s.Tipo_Serv=ts.IdTipoSer inner join configservicio cs  on cs.IdConfigS=ts.IdConfigS where s.EstadoS !="Cancelada" and s.EstadoS="CERRADA" and s.IdSucursal=' . $this->IdSucursal;
        $this->db->select('s.*');
        $this->db->from('servicio s');
        $this->db->join('tiposervicio ts ',' s.Tipo_Serv=ts.IdTipoSer','inner');
        $this->db->join('configservicio cs  ',' cs.IdConfigS=ts.IdConfigS','inner');
        $this->db->where('s.EstadoS !="Cancelada"');
        $this->db->where('s.EstadoS = "CERRADA"');



        $this->db->where('s.IdSucursal', $this->IdSucursal);

        if (!empty($this->RegEstatus)) {
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdPlanFactura)) {
            $this->db->where('s.IdPlanFactura', $this->IdPlanFactura);
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

    public function get_recobery_detalleestadofinanciero()
    {

        $this->db->select('*');
        $this->db->from('detalleestadofinanciero');

        if (!empty($this->IdPlanFactura)) {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->IdEstadoF)) {
            $this->db->where('IdEstadoF', $this->IdEstadoF);
        }

        //echo $result = $this->db->get_compiled_select();
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_list_costoga()
    {
        $this->db->select('*');
        $this->db->from('costoga');
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if (!empty($this->IdCostoGA))
        {
            $this->db->where('IdCostoGA', $this->IdCostoGA);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_gastosdirectos()
    {
        $this->db->select('*');
        $this->db->from('gastosdirectos');
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if (!empty($this->Tipo))
        {
            $this->db->where('Tipo', $this->Tipo);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->IdGasto))
        {
            $this->db->where('IdGasto', $this->IdGasto);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_actualventas()
    {       

        $this->db->select('*');
        $this->db->from('actualventas');
        $this->db->where('IdSucursal', $this->IdSucursal);
        

        if (!empty($this->IdGasto)){
            $this->db->where('IdGasto', $this->IdGasto);
        }
        if (!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
        }
        if (!empty($this->Mes)){
            $this->db->where('Mes', $this->Mes);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_list_costodeptoventa()
    {
        $this->db->select('*');
        $this->db->from('costodeptoventa');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->IdCostoDeptoVenta)){
            $this->db->where('IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
        }
        if (!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_actualoperaciones()
    {       

        $this->db->select('*');
        $this->db->from('actualoperaciones');
        $this->db->where('IdCostoDeptoVenta', $this->IdCostoDeptoVenta);
        
        if (!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
        }
        if (!empty($this->Mes)){
            $this->db->where('Mes', $this->Mes);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_recobery_estadofupdate()
    {
        $this->db->select('*');
        $this->db->from('estadofupdate');

        if(!empty($this->IdSucursal)){
            $this->db->where('IdSucursal', $this->IdSucursal);
        }
        if(!empty($this->IdConfigServ)){
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if($this->IdTipoServ){
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        else
        {
            $this->db->where('IdTipoServ!=0');
        }
        if(!empty($this->Mes)){
            $this->db->where('Mes', $this->Mes);
        }
        if(!empty($this->Descripcion)){
            $this->db->where('Descripcion', $this->Descripcion);
        }
        if(!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
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
                'data' => new MpdfEstadosFGparche()
            ];
        }   
    }
     
    public function get_list_estadofupdate()
    {
        $this->db->select('*');
        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->Descripcion)){
            $this->db->where('Descripcion', $this->Descripcion);
        }
        if (!empty($this->Anio)){
            $this->db->where('Anio='.$this->Anio);
        }
        if (!empty($this->Mes)){
            $and= 'Mes between ' . $this->Mes . ' and ' . $this->Mes2;
            $this->db->where($and);
        }
        if ($this->IdConfigServ != ''){
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if ($this->IdTipoServ != ''){
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        else
        {
            $this->db->where('IdTipoServ !=0');
        }

        #echo $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_costovehope()
    {
        $this->db->select('*');
        $this->db->from('costovehope');
       $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->IdCostoVehOpe))
        {
            $this->db->where('IdCostoVehOpe', $this->IdCostoVehOpe);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_recobery_costofinanciero_plangral()
    {

        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT, sum(CuartoT) as CuartoT');
        $this->db->from('costofinanciero');
        $this->db->where('IdSucursal', $this->IdSucursal);

       
       if (!empty($this->IdCostoFinanciero))
        {
            $this->db->where('IdCostoFinanciero', $this->IdCostoFinanciero);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->Tipo))
        {
            $this->db->where('Tipo', $this->Tipo);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_recobery_actualcostofrptgral2()
    {       
        
        $this->db->select('sum(ac.MontoMes) as MontoMes');
        $this->db->from('actualcostof ac');
        $this->db->join('costofinanciero cf ',' ac.IdCostoFinanciero=cf.IdCostoFinanciero','inner');
        
        $this->db->where('cf.IdSucursal', $this->IdSucursal); 

        if ($this->Tipo==1)
        {
            if (!empty($this->Mes)){
                $this->db->where('ac.Mes ='. $this->Mes);
            }
        }
        else
        {
            if (!empty($this->Mes)){
                $this->db->where('ac.Mes <='.$this->Mes);
            }
        }
        if (!empty($this->Anio)){
            $this->db->where('ac.Anio ='. $this->Anio);
        }
        if (!empty($this->Type)){
            $this->db->where('cf.Tipo', $this->Type);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }
    public function get_recobery_porcentajeoperacionEstadoFinanciero2()
    {
    
        $this->db->select('sum(AnioAnterior) as AnioAnterior, sum(PrimerT) as PrimerT, sum(SegundoT) as SegundoT,sum(TercerT) as TercerT,sum(CuartoT)  as CuartoT');
        $this->db->from('porcentajeoperacion');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->Descripcion))
        {
            $this->db->where('Descripcion', $this->Descripcion);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }

        if (!empty($this->IdSubtipoServ))
        {
            $this->db->where('IdSubtipoServ', $this->IdSubtipoServ);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_recobery_porcentajeoperacionEstadoFinanciero()
    {
    
        $this->db->select('po.*');
        $this->db->from('porcentajeoperacion po');
        $this->db->where('po.IdSucursal', $this->IdSucursal);

        if ($this->IdPlanFactura > 0) {
            $and1 = ' po.IdPlanFactura=' . $this->IdPlanFactura . '';
           $this->db->where($and1);
        }

        if ($this->IdPorcentajeOperacion > 0) {
            $and2 = ' po.IdPorcentajeOperacion=' . $this->IdPorcentajeOperacion . '';
           $this->db->where($and2);
        }

        if ($this->IdTipoSer > 0) {
            $and3 = ' po.IdTipoSer=' . $this->IdTipoSer . '';
           $this->db->where($and3);
        }

        if ($this->IdSubtipoServ > 0) {
            $and4 = ' po.IdSubtipoServ=' . $this->IdSubtipoServ . '';
           $this->db->where($and4);
        }

        if ($this->Anio != "") {
            $and5 = ' po.Anio=' . $this->Anio . '';
           $this->db->where($and5);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_recobery_actualcostove()
    {
        $this->db->select('*');
        $this->db->from('actualcostove');
       $this->db->where('IdSucursal', $this->IdSucursal);
       
       if (!empty($this->IdCostoVehOpe))
        {
            $this->db->where('IdCostoVehOpe', $this->IdCostoVehOpe);
        }

        if (!empty($this->Anio))
        {
            $this->db->where('Anio='. $this->Anio);
        }

        if (!empty($this->Mes))
        {
            $this->db->where('Mes='. $this->Mes);
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
                 'data' => new MpdfEstadosFGparche()
            ];
        }
    }

    public function get_recobery_actualcostoga()
    {
        $this->db->select('*');
        $this->db->from('actualcostoga');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        
        $this->db->where('IdCostoGA', $this->IdCostoGA);
    
        $this->db->where('Mes='.$this->Mes);
    
        $this->db->where('Anio='.$this->Anio);
       

        //echo $this->IdSucursal.'-'.$this->IdCostoGA.'-'.$this->Anio;
        
        ##echo $this->db->get_compiled_select();
        //echo $result = $this->db->get_compiled_select();
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
                 'data' => new MpdfEstadosFGparche()
            ];
        } 
    } 

}