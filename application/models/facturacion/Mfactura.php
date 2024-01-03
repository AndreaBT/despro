<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfactura extends BaseModel
{
    // Properties
    public $IdFactura;
    public $IdSucursal;
    public $IdServicio;
    public $IdCliente;
    public $IdClienteS;
    public $IdContrato;
    public $FolioFactura;
    public $FolioServ;
    public $NombreCliente;
    public $Sucursal;
    public $Direccion;
    public $Contacto;
    public $Telefono;
    public $DatosFact;
    public $NoContrato;
    public $Servicio;
    public $ComentarioContrato;
    public $SubTotal;
    public $Iva;
    public $Total;
    public $Facturado;
    public $RegEstatus;
    public $FechaReg;
    public $AFacturar;
    public $FechaFacReal;
    public $FolioFactReal;
    public $ComentarioCancel;
    public $ComentarioServ;
    public $ArchivoFactura;
     public $FechaMod;
     public $Factura;

     public $Monto;
     public $ComentarioAnulada;

     public $TipoFiltro;
     public $FechaAnulado;

     public $TipoFactura;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdFactura = 0;
        $this->IdSucursal = 0;
        $this->IdServicio = 0;
        $this->IdCliente = 0;
        $this->IdClienteS = 0;
        $this->IdContrato = 0;
        $this->FolioFactura = '';
        $this->FolioServ='';
        $this->NombreCliente='';
        $this->Sucursal='';
        $this->Direccion='';
        $this->Contacto='';
        $this->Telefono='';
        $this->DatosFact='';
        $this->NoContrato='';
        $this->Servicio='';
        $this->ComentarioContrato='';
        $this->SubTotal=0;
        $this->Iva=0;
        $this->Total=0;
        $this->Facturado='';
        $this->Factura='';
        $this->RegEstatus='';
        $this->FechaReg='';
        $this->AFacturar='';
        $this->FechaFacReal='';
        $this->FolioFactReal='';
        $this->ComentarioCancel='';
        $this->ComentarioServ='';
        $this->ArchivoFactura='';
        $this->FechaMod='';
        $this->TipoFiltro='';


        $this->Monto=0;
        $this->ComentarioAnulada='';
        $this->DiasCredito=0;

        $this->Observacion='';
        $this->FechaAnulado='';
        $this->TipoFactura='';

        $this->Anulado='';
    }

    public function Insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdContrato', $this->IdContrato);
        $this->db->set('FolioFactura', $this->FolioFactura);
        $this->db->set('FolioServ', $this->FolioServ);
        $this->db->set('NombreCliente', $this->NombreCliente);
        $this->db->set('Sucursal', $this->Sucursal);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('DatosFact', $this->DatosFact);
        $this->db->set('NoContrato', $this->NoContrato);
        $this->db->set('Servicio', $this->Servicio);
        $this->db->set('ComentarioContrato', $this->ComentarioContrato);
        $this->db->set('SubTotal', $this->SubTotal);
        $this->db->set('Iva', $this->Iva);
        $this->db->set('Total', $this->Total);
        $this->db->set('Facturado', $this->Facturado);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('AFacturar', $this->AFacturar);
        $this->db->set('FechaFacReal', $this->FechaFacReal);
        $this->db->set('ComentarioCancel', '');
        $this->db->set('ComentarioServ', '');
        $this->db->set('ArchivoFactura', '');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('TipoFactura',$this->TipoFactura);
        $this->db->insert('factura');
        return $this->db->insert_id();
    }
    public function Update()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdContrato', $this->IdContrato);
        //$this->db->set('FolioFactura', $this->FolioFactura);
        $this->db->set('FolioServ', $this->FolioServ);
        $this->db->set('NombreCliente', $this->NombreCliente);
        $this->db->set('Sucursal', $this->Sucursal);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('DatosFact', $this->DatosFact);
        $this->db->set('NoContrato', $this->NoContrato);
        $this->db->set('Servicio', $this->Servicio);
        $this->db->set('ComentarioContrato', $this->ComentarioContrato);
        $this->db->set('SubTotal', $this->SubTotal);
        $this->db->set('Iva', $this->Iva);
        $this->db->set('Total', $this->Total);
        $this->db->set('Facturado', $this->Facturado);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('AFacturar', $this->AFacturar);
        $this->db->set('FechaMod', $this->FechaMod);

        $this->db->update('factura');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function Autorizar()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->set('AFacturar', $this->AFacturar);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('factura');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function Anular()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->set('FechaAnulado', $this->FechaAnulado);
        $this->db->set('Facturado', 'Anulada'); //Se cambió el Reg Estatus
        $this->db->set('FechaFacReal', '0000-00-00');
        $this->db->set('AFacturar', 'NO');
        $this->db->set('Anulado', 'SI');
        $this->db->set('ComentarioAnulada', $this->ComentarioAnulada);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('factura');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function Cancelar()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->set('ComentarioCancel', $this->ComentarioCancel);
        $this->db->set('Facturado', $this->Facturado);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('factura');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    //agregado campo de Monto para Cuentas por Cobrar.
    public function ChangeFactura()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        // $this->db->where('RegEstatus!=', 'Anulada');
        $this->db->set('FechaFacReal', $this->FechaFacReal);
        $this->db->set('FolioFactReal', $this->FolioFactReal);
        $this->db->set('ArchivoFactura', $this->ArchivoFactura);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaMod', $this->FechaMod);

        $this->db->set('Monto', $this->Monto);

        $this->db->set('DiasCredito', $this->DiasCredito);
        $this->db->set('Observacion', $this->Observacion);

        $this->db->update('factura');
        //echo $this->db->last_query();

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function UpdateServicio()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->set('EstadoFactura', $this->Facturado);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('servicio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function UpdateComentarioServ()
    {
        $this->db->where('IdFactura', $this->IdFactura);
        $this->db->set('ComentarioServ', $this->ComentarioServ);
         $this->db->update('factura');
        return true;
    }

    public function delete()
    {
        $this->db->where('IdFactura', $this->IdFactura);

        $this->db->set('RegEstatus','B');

        $this->db->Update('factura');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_factura()
    {
        $this->db->select('*');
        $this->db->from('factura');
        //  $this->db->where('RegEstatus',$this->RegEstatus);
        if (!empty($this->IdFactura))
        {
            $this->db->where('IdFactura', $this->IdFactura);
        }
        if (!empty($this->IdServicio))
        {
            $this->db->where('IdServicio', $this->IdServicio);
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
                'status' => false
            ];
        }
    }

    public function get_facturaLibreRecovery()
    {
        $this->db->select('f.*,c.Correo');
        $this->db->from('factura f');
        $this->db->join('clientes c','f.IdCliente=c.IdCliente','inner');
         $this->db->where('f.RegEstatus',$this->RegEstatus);
        if (!empty($this->IdFactura))
        {
            $this->db->where('f.IdFactura', $this->IdFactura);
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
                'status' => false
            ];
        }
    }


    public function get_list()
    {
        $this->db->select('f.*,DATE_FORMAT(f.FechaReg, "%d-%m-%Y") as FechaReg,DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as FechaI2,,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y") as FechaFacReal, s.EstadoFactura, f.RegEstatus, f.ComentarioAnulada,f.Observacion,f.IdServicio');
        $this->db->from('factura f');
        $this->db->join('servicio s','s.IdServicio=f.IdServicio','inner');
        $this->db->where('f.IdSucursal', $this->IdSucursal);
        if (!empty($this->RegEstatus)) {
            $this->db->where('f.RegEstatus ', $this->RegEstatus);
        }
        if (!empty($this->NombreCliente)) {
            $like =" (f.NombreCliente like '%".$this->NombreCliente."%' or f.FolioServ like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }
        // Filters
        if (!empty($this->IdServicio)) {
            $this->db->where('f.IdServicio', $this->IdServicio);
        }

        if (!empty($this->Facturado)) {
            $this->db->where('f.Facturado ', $this->Facturado);
        }
        if (!empty($this->AFacturar)) {
            $this->db->where('f.AFacturar ', $this->AFacturar);
        }

        if (!empty($this->Anulado)) {
            $this->db->where('f.Anulado ', $this->Anulado);
        }


        if (!empty($this->FechaFacReal)) {
            if ($this->TipoFiltro==1)
            {
                $this->db->where('f.FechaFacReal ', $this->FechaFacReal);
            }
            else
            {
                $this->db->where('f.FechaFacReal !=', $this->FechaFacReal);
            }
        }
        # echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_factLibreAutorize(){
        $this->db->select('f.*,DATE_FORMAT(f.FechaFacReal, "%d-%m-%Y") as FechaFacReal');
        $this->db->from('factura f');
        $this->db->where('f.IdSucursal', $this->IdSucursal);
        if (!empty($this->RegEstatus)) {
            $this->db->where('f.RegEstatus ', $this->RegEstatus);
        }
        if (!empty($this->NombreCliente)) {
            $like =" (f.NombreCliente like '%".$this->NombreCliente."%' or f.Sucursal like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }
        if (!empty($this->Facturado)) {
            $this->db->where('f.Facturado ', $this->Facturado);
        }
        if (!empty($this->AFacturar)) {
            $this->db->where('f.AFacturar ', $this->AFacturar);
        }
        if (!empty($this->TipoFactura)) {
            $this->db->where('f.TipoFactura =','2');
        }
        if (!empty($this->FechaFacReal)) {
            if ($this->TipoFiltro==1)
            {
                $this->db->where('f.FechaFacReal ', $this->FechaFacReal);
            }
            else
            {
                $this->db->where('f.FechaFacReal !=', $this->FechaFacReal);
            }
        }
        # echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();

    }

    public function get_listservfac()
    {
        $this->db->select('s.*,DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as FechaI2,cs.Nombre as Sucursal,c.Nombre as ClienteP,ts.Concepto as TipoServ,nc.NumeroC,nc.Comentario as ComentarioC,f.ComentarioCancel,f.IdFactura,f.RegEstatus,f.Facturado');
        $this->db->from('servicio s ');
        $this->db->where('s.IdSucursal', $this->IdSucursal);
        $this->db->where('s.Factura  ', 's');
        $this->db->join('clientesucursal cs','s.IdClienteS=cs.IdClienteS','inner');
        $this->db->join('clientes c','s.IdCliente=c.IdCliente','inner');
        $this->db->join('tiposervicio ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->join('numcontrato nc ','s.IdContrato=nc.IdContrato','left');
        $this->db->join('factura f ','s.IdServicio=f.IdServicio','left');

        $where=' ( s.EstadoS="PENDIENTE" OR s.EstadoS="CERRADA") ';
        $this->db->where($where);
        $where=' (s.EstadoFactura !="SI") ';
        $this->db->where($where);

        //$where=' ((s.EstadoFactura !="SI" and f.RegEstatus!="Anulada")  or s.EstadoFactura is null) ';


        if (!empty($this->RegEstatus)) {
            $this->db->where('s.RegEstatus ', $this->RegEstatus);
        }

        if (!empty($this->FolioServ)) {
        	$where=' ( s.Folio like\'%'.$this->FolioServ.'%\' OR cs.Nombre like\'%'.$this->FolioServ.'%\' OR c.Nombre like\'%'.$this->FolioServ.'%\' OR nc.NumeroC like \'%'.$this->FolioServ.'%\') ';
            $this->db->like($where,true);
             //$this->db->or_like('c.Nombre', $this->FolioServ);
             //$this->db->or_like('cs.Nombre', $this->FolioServ);
        }
        if (!empty($this->Factura)) {
            $this->db->where('s.Factura ', $this->Factura);
        }
        // Filters
        if (!empty($this->IdServicio)) {
            $this->db->where('s.IdServicio', $this->IdServicio);
        }

         if (!empty($this->IdCliente)) {
            $this->db->where('s.IdCliente', $this->IdCliente);
        }

         if (!empty($this->IdFactura)) {
            $this->db->where('f.IdFactura', $this->IdFactura);
        }

        $where=' ( f.Facturado is null) ';
        $this->db->where($where);
        // if (!empty($this->TipoFactura)) {
        //     $this->db->where('f.TipoFactura',$this->TipoFactura);
        // }



        $where=' not exists (select * from factura_serfolio as fs where s.IdServicio=fs.IdServicio) ';
        $this->db->where($where,'',false);

        $this->db->order_by('s.Folio', 'DESC');

        //echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_FacturaLibre(){
        $this->db->select('*');
        $this->db->from('factura');
        $this->db->where('IdSucursal', $this->IdSucursal);
        if (!empty($this->TipoFactura))
        {
            $this->db->where('TipoFactura', $this->TipoFactura);
        }

            $this->db->where('Facturado=','NO');


        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function FacturaLibreSave(){
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdContrato', $this->IdContrato);
        $this->db->set('FolioFactura', $this->FolioFactura);
        $this->db->set('FolioServ', $this->FolioServ);
        $this->db->set('NombreCliente', $this->NombreCliente);
        $this->db->set('Sucursal', $this->Sucursal);
        $this->db->set('Direccion', $this->Direccion);
        $this->db->set('Contacto', $this->Contacto);
        $this->db->set('Telefono', $this->Telefono);
        $this->db->set('DatosFact', $this->DatosFact);
        $this->db->set('NoContrato', $this->NoContrato);
        $this->db->set('Servicio', $this->Servicio);
        $this->db->set('ComentarioContrato', $this->ComentarioContrato);
        $this->db->set('SubTotal', $this->SubTotal);
        $this->db->set('Iva', $this->Iva);
        $this->db->set('Total', $this->Total);
        $this->db->set('Facturado', $this->Facturado);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('AFacturar', $this->AFacturar);
        $this->db->set('FechaFacReal', $this->FechaFacReal);
        $this->db->set('ComentarioCancel', '');
        $this->db->set('ComentarioServ', '');
        $this->db->set('ArchivoFactura', '');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('TipoFactura',$this->TipoFactura);
        $this->db->insert('factura');
        return $this->db->insert_id();
    }

    public function get_factura_serfolio(){
        $this->db->select('*');
        $this->db->from('factura_serfolio');
        if (!empty($this->IdServicio))
        {
            $this->db->where('IdServicio', $this->IdServicio);
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
                'status' => false
            ];
        }
    }

    public function deleteFacturaxServ()
    {
        // $this->db->where('IdServicio', $this->IdServicio);
        $this->db->where('IdFactura', $this->IdFactura);

        // $this->db->set('RegEstatus','B');

        $this->db->Delete('factura_serfolio');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_FacturaPendiente(){
        $this->db->select('f.FolioServ as Folio, DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as FechaI2,f.NombreCliente as ClienteP, f.Sucursal as Sucursal,ts.Concepto as TipoServ,f.Facturado,f.IdServicio ');
        $this->db->from('factura f');
        $this->db->join('servicio s','f.IdServicio=s.IdServicio','inner');
        $this->db->join('tiposervicio ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->where('f.IdSucursal', $this->IdSucursal);
        if (!empty($this->Facturado))
        {
            $this->db->where('f.Facturado','NO');
        }

        if (!empty($this->NombreCliente)) {
            $like =" (f.NombreCliente like '%".$this->NombreCliente."%' or f.FolioServ like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }

        $this->db->order_by('s.Folio', 'DESC');
        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_FacturaCancelada(){
        $this->db->select('f.FolioServ as Folio, DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as FechaI2,f.NombreCliente as ClienteP, f.Sucursal as Sucursal,ts.Concepto as TipoServ, f.ComentarioCancel,f.Facturado,f.IdServicio ');
        $this->db->from('factura f');
        $this->db->join('servicio s','f.IdServicio=s.IdServicio','inner');
        $this->db->join('tiposervicio ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->where('f.IdSucursal', $this->IdSucursal);
        if (!empty($this->Facturado))
        {
            $this->db->where('f.Facturado','Cancelado');
        }

        if (!empty($this->NombreCliente)) {
            $like =" (f.NombreCliente like '%".$this->NombreCliente."%' or f.FolioServ like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }

        $this->db->order_by('s.Folio', 'DESC');

        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_FacturaAnulada(){
        $this->db->select('f.FolioServ as Folio, DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as FechaI2,f.NombreCliente as ClienteP, f.Sucursal as Sucursal,ts.Concepto as TipoServ, f.RegEstatus,f.ComentarioAnulada,f.IdServicio,f.Facturado');
        $this->db->from('factura f');
        $this->db->join('servicio s','f.IdServicio=s.IdServicio','inner');
        $this->db->join('tiposervicio ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->where('f.IdSucursal', $this->IdSucursal);



        if (!empty($this->Facturado))
        {
            $this->db->where('f.Facturado','Anulada');
        }

        if (!empty($this->NombreCliente)) {
            $like =" (f.NombreCliente like '%".$this->NombreCliente."%' or f.FolioServ like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }

        $this->db->order_by('s.Folio', 'DESC');

        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_facturaLibreAnulada(){
        $this->db->select('*');
        $this->db->from('factura');
        $this->db->where('IdSucursal', $this->IdSucursal);
        if (!empty($this->TipoFactura))
        {
            $this->db->where('TipoFactura', $this->TipoFactura);
        }

            $this->db->where('Facturado=','Anulada');

            if (!empty($this->NombreCliente)) {
                $like =" (NombreCliente like '%".$this->NombreCliente."%' or FolioServ like '%".$this->NombreCliente."%')";
                $this->db->where($like);
            }

            $this->db->order_by('s.FolioServ', 'DESC');

        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_facturaLibreCancelada(){
        $this->db->select('*');
        $this->db->from('factura');
        $this->db->where('IdSucursal', $this->IdSucursal);
        if (!empty($this->TipoFactura))
        {
            $this->db->where('TipoFactura', $this->TipoFactura);
        }

        if (!empty($this->NombreCliente)) {
            $like =" (NombreCliente like '%".$this->NombreCliente."%' or FolioServ like '%".$this->NombreCliente."%')";
            $this->db->where($like);
        }

            $this->db->where('Facturado=','Cancelado');
            $this->db->order_by('s.FolioServ', 'DESC');

        #echo $result = $this->db->get_compiled_select();

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

}
?>
