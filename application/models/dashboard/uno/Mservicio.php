<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mservicio extends BaseModel
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
    public $HoraInicio;
    public $HoraFin;


      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdServicio = '';
        $this->Cliente = '';
        $this->Tipo_Serv = '';
        $this->Personal = '';
        $this->Vehiculo ='';
        $this->Fecha_I='';
        $this->Fecha_F='';
        $this->Hora_I='';
        $this->Hora_F='';
        $this->Distancia='';
        $this->Observaciones='';
        $this->Materiales='';
        $this->IdSucursal='';
        $this->RegEstatus='';
        $this->Direccion=''; 
        $this->Folio='';
        $this->IdVehiculo='';
        $this->IdCliente=0;
        $this->IdClienteS=0;
        $this->EstadoS='';
        $this->Velocidad='';
        $this->Econtacto='';
        $this->Contacto='';
        $this->EquiposD='';
        $this->MaterialesD='';
        $this->ViaticosD='';
        $this->ContratistasD='';
        $this->EstadoServicio='';
        $this->Comentario='';
        $this->NombreArchivo='';
        $this->IdEquipo ='';
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
        $this->HoraInicio = '';
        $this->HoraFin = '';

      
    }
    
    public function get_list_serviciograficas()
    {       
        
        
        $this->db->select('s.*,f.IdTrabajador ,f.FechaInicio,f.HoraInicio,f.HoraFin');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio f','s.IdServicio=f.IdServicio','inner');
        $this->db->join('tiposervicio ts',' ts.IdTipoSer=s.Tipo_Serv','inner');
        
        if (!empty($this->RegEstatus)){
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }


        if (!empty($this->IdSucursal)){
            $this->db->where('s.IdSucursal', $this->IdSucursal);
        }

        if (!empty($this->IdTrabajador)){
            $this->db->where('f.IdTrabajador', $this->IdTrabajador);
        }

        if (!empty($this->EstadoS)){
            $this->db->where('s.EstadoS', $this->EstadoS);
        }

        if (!empty($this->Facturable)){
            $this->db->where('ts.Ingresos', $this->Facturable);
        }

        if($this->Fecha_I!='' && $this->Fecha_F!='')
        {
            $and=' f.FechaInicio>=\''.$this->Fecha_I.'\' and  f.FechaInicio<=\''.$this->Fecha_F.'\'';
            $this->db->where($and);
        }
        
        //echo $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }



    /*public function get_list_serviciograficas_v2($ArrTrabajadores = array())
    {       
        
        
        $this->db->select('s.*,f.IdTrabajador ,f.FechaInicio,f.HoraInicio,f.HoraFin');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio f','s.IdServicio=f.IdServicio','inner');
        $this->db->join('tiposervicio ts',' ts.IdTipoSer=s.Tipo_Serv','inner');

        if (!empty($ArrTrabajadores)){
            $this->db->where_in('f.IdTrabajador', $ArrTrabajadores);
        }
        
        if (!empty($this->RegEstatus)){
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }


        if (!empty($this->IdSucursal)){
            $this->db->where('s.IdSucursal', $this->IdSucursal);
        }

        

        if (!empty($this->EstadoS)){
            $this->db->where('s.EstadoS', $this->EstadoS);
        }

        if (!empty($this->Facturable)){
            $this->db->where('ts.Ingresos', $this->Facturable);
        }

        if($this->Fecha_I!='' && $this->Fecha_F!='')
        {
            $and=' f.FechaInicio>=\''.$this->Fecha_I.'\' and  f.FechaInicio<=\''.$this->Fecha_F.'\'';
            $this->db->where($and);
        }
        
        //echo $this->db->get_compiled_select();
        //Pagination
        $response = $this->db->get();
        return $response->result();
    }*/



    public function get_list_servicio()
    {       
        
        
        $this->db->select('s.*,fs.HoraInicio,fs.HoraFin');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio fs','s.IdServicio=fs.IdServicio','inner');
        
        if (!empty($this->IdSucursal)){
            $this->db->where('s.IdSucursal', $this->IdSucursal);
        }

        if (!empty($this->IdServicio)){
            $this->db->where('s.IdServicio', $this->IdServicio);
        }

        if (!empty($this->RegEstatus)){
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->Tipo_Serv)){
            $this->db->where('s.Tipo_Serv', $this->Tipo_Serv);
        }

        if($this->Fecha_I!='' && $this->Fecha_F!='')
        {
            $and=' fs.FechaInicio>=\''.$this->Fecha_I.'\' and  fs.FechaInicio<=\''.$this->Fecha_F.'\'';
            $this->db->where($and);
        }
          
        //Pagination
        
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        
        #echo $result = $this->db->get_compiled_select();

        // $response = $this->db->get();
 
        // if ($response->num_rows() > 0) {
        //     $data = $response->row();

        //     return [
        //         'status' => true,
        //         'data' => $data
        //     ];
        // } else { 
        //     return [
        //         'status' => false,
        //          'data' => new Mservicio()
        //     ];
        // }
    }

    public function get_list_graficavehiculo()
    {       
        
        
        $this->db->select('s.*');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio fs','s.IdServicio=fs.IdServicio','inner');
        $this->db->join('vehiculoservicio vs',' s.IdServicio=vs.IdServicio','inner');
        
        if (!empty($this->IdSucursal)){
            $this->db->where('s.IdSucursal', $this->IdSucursal);
        }

        if (!empty($this->RegEstatus)){
            $this->db->where('s.RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdVehiculo)){
            $this->db->where('vs.IdVehiculo', $this->IdVehiculo);
        }

        if($this->Fecha_I!='' && $this->Fecha_F!='')
        {
            $and=' fs.FechaInicio>=\''.$this->Fecha_I.'\' and  fs.FechaInicio<=\''.$this->Fecha_F.'\'';
            $this->db->where($and);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list_finanzashorasfacturables()
    {
        
        $this->db->select('s.IdServicio,f.HoraInicio,f.HoraFin,ts.Color,f.EstadoServicio,ts.Concepto as Servicio,ts.Ingresos');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio f','s.IdServicio=f.IdServicio','inner');
        $this->db->join('tiposervicio ts',' s.Tipo_Serv=ts.IdTipoSer','inner');
        
        $this->db->where('s.RegEstatus', 'A');
        $this->db->where('f.EstadoServicio !=','CANCELADA');
        $this->db->where('s.IdSucursal', $this->IdSucursal);

        if($this->Fecha_F!='')
        {
          $and2 =' s.Fecha_F between \''.$this->Fecha_I.'\' and \''.$this->Fecha_F.'\'';
          $this->db->where($and2);
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        
    }

    public function get_list_servicioFinancieroAnioBurdenMano2()
    {
        
        $this->db->select('s.*');
        $this->db->from('servicio s');
        
        $this->db->join('tiposervicio ts',' s.Tipo_Serv=ts.IdTipoSer','inner');
        $this->db->join('configservicio cs','ts.IdConfigS = cs.IdConfigS','inner');
        
        $this->db->where('s.EstadoS !=','Cancelada');
        $this->db->where('s.EstadoS','CERRADA');

        $this->db->where('s.IdSucursal', $this->IdSucursal);

        if ($this->RegEstatus!=''){
            $and1 = 's.RegEstatus=\''.$this->RegEstatus.'\'';
            $this->db->where($and1);
        }

        if ($this->IdClienteS != '') {
            $and2 = ' s.IdClienteS=' . $this->IdClienteS . '';
            $this->db->where($and2);
        }
        if ($this->IdCliente != '') {
            $and3 = ' s.IdCliente=' . $this->IdCliente . '';
            $this->db->where($and3);
        }
        if ($this->IdContrato != '') {
            $and4 = ' s.IdContrato=' . $this->IdContrato . '';
            $this->db->where($and4);
        }

        if ($this->Tipo_Serv != '') {
            $and5 = ' cs.IdConfigS=' . $this->Tipo_Serv . '';
            $this->db->where($and5);
        }

        if($this->IdSubIndice!='')
        {
            $and6 =' s.Tipo_Serv='.$this->IdSubIndice.'';
            $this->db->where($and6);
        }

        if ($this->Fecha_I =='')
        {
            if ($this->Fecha_F != '') {
                $and7 = ' s.Fecha_F like \'%' . $this->Fecha_F . '%\'';
                $this->db->where($and7);
            }

        } else {

            if ($this->Fecha_I != '') {
                $and8 = ' s.Fecha_F between \'' . $this->Fecha_I . '\' and  \'' . $this->Fecha_F . '\'';
                $this->db->where($and8);
            }
        }
          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        
    }



}
