<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadofinanciero extends BaseModel
{
    // Properties
    public $IdEstadoF='';
    public $IdConfigS='';
    public $IdSucursal='';
    public $Anio='0';
    public $Mes='';
    public $Facturacion='0';
    public $IdTipoServ='0';
    public $IdClienteS='0';
    public $IdCliente='0';
    public $FacturacionMes="";
    public $FacturacionCompleto="";
    public $IdContrato="";
    

    public function __construct()
    {
        parent::__construct();
        // Init Properties
      
    }
      public function insert()
    {   
        $this->db->set('IdConfigS', $this->IdConfigS);
        $this->db->set('IdSucursal', $this->IdSucursal);   
        $this->db->set('Anio', $this->Anio); 
        $this->db->set('Mes', $this->Mes);   
        $this->db->set('Facturacion', $this->Facturacion);   
        $this->db->set('IdTipoServ', $this->IdTipoServ);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdCliente', $this->IdCliente);
      
        if (!empty($this->IdContrato))
        {
         
        $this->db->set('IdContrato', $this->IdContrato);
        }
        $this->db->insert('estadofinanciero');
      
        return true;
    }
    
    public function update()
    {   
        $this->db->where('Anio', $this->Anio);
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('Mes', $this->Mes);
        $this->db->where('IdClienteS', $this->IdClienteS);
        $this->db->where('IdCliente', $this->IdCliente);
        $this->db->where('IdTipoServ', $this->IdTipoServ);
        $this->db->where('IdConfigS', $this->IdConfigS);
        if (!empty($this->IdContrato))
        {
            $this->db->where('IdContrato', $this->IdContrato);
        }
        
        $this->db->set('Facturacion', $this->Facturacion);
    
        $this->db->update('estadofinanciero');
     
        return true;
    }
    
         public function get_recovery()
    {
        
        $this->db->select('*');
        $this->db->from('estadofinanciero');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
       if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
         if (!empty($this->Mes))
        {
            $this->db->where('Mes', $this->Mes);
        }
        if (!empty($this->IdConfigS))
        {
            $this->db->where('IdConfigS', $this->IdConfigS);
        }
         if (!empty($this->IdTipoServ))
        {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
         if (!empty($this->IdCliente))
        {
            $this->db->where('IdCliente', $this->IdCliente);
        }
        if (!empty($this->IdClienteS))
        {
            $this->db->where('IdClienteS', $this->IdClienteS);
        }
        if (!empty($this->IdContrato))
        {
            $this->db->where('IdContrato', $this->IdContrato);
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
                 'data' => new Mestadofinanciero()
            ];
        }
    }

    
    public function get_estadofinanciero()
    {
        $FiltroMes="";
        $FiltroIdConfig="";
        $FiltroTipoServ="";
        $FiltroIdClienteS="";
        $FiltroIdCliente="";
        $FiltroIdContrato="";

        if (!empty($this->Mes))
        {
            $this->db->where('Mes', $this->Mes,false);
            $FiltroMes=" and Mes<= ".$this->Mes;
        }
        
        if (!empty($this->IdConfigS))
        {
            $this->db->where('IdConfigS', $this->IdConfigS);
            $FiltroIdConfig=" and IdConfigS= ".$this->IdConfigS;
        }
        
        if (!empty($this->IdTipoServ))
        {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
            $FiltroTipoServ =" and IdTipoServ= ".$this->IdTipoServ;
        }
        
        if (!empty($this->IdCliente))
        {
            $this->db->where('IdCliente', $this->IdCliente);
            $FiltroIdCliente =" and IdCliente = ".$this->IdCliente;
        }
        
        if (!empty($this->IdClienteS))
        {
            $this->db->where('IdClienteS', $this->IdClienteS);
            $FiltroIdClienteS =" and IdClienteS = ".$this->IdClienteS;
        }

        if (!empty($this->IdContrato))
        {
            $this->db->where('IdContrato', $this->IdContrato);
            $FiltroIdContrato=" and IdContrato = ".$this->IdContrato;
        }
        
        $this->db->select('sum(Facturacion)  as FacturacionMes,
        (select sum(Facturacion)  as Facturacion from estadofinanciero where IdSucursal='.$this->IdSucursal.' and Anio='.$this->Anio.' '.$FiltroMes.' '.$FiltroIdConfig.' '.$FiltroTipoServ.' '.$FiltroIdCliente.' '.$FiltroIdClienteS.' '.$FiltroIdContrato.'  ) as FacturacionCompleto
        ');
        $this->db->from('estadofinanciero');
        $this->db->where('IdSucursal', $this->IdSucursal);
        
        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
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
                 'data' => new Mestadofinanciero()
            ];
        }
    }
    
     public function get_list2()
    {
        $this->db->select('IdSucursal,Anio,Descripcion,Mes,IdConfigServ,IdTipoServ ,
        case   WHEN Monto = 0 THEN "" else Monto
        end  as Monto',false);
        $this->db->from('estadofupdate');
       $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->Anio))
        {
            $this->db->where('Anio', $this->Anio);
        }
         if (!empty($this->Mes))
        {
            $this->db->where('Mes', $this->Mes);
        }
         if (!empty($this->IdConfigServ))
        {
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
         if (!empty($this->IdTipoServ))
        {
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
 
  
}