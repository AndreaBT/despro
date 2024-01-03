<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mspend_ordenc extends BaseModel
{
    // Properties
    public $IdOrdenCompra;
    public $IdSucursal;
    public $IdProyecto;
    public $IdUsuario;
    public $IdServicio;
    public $FechaReg;
    public $Concepto;
    public $Descripcion;
    public $Archivo;
    public $FolioArchivo;
    public $Monto;
    public $FechaMod;
    public $RegEstatus;
    
    public $IdCliente;
    public $IdClienteS;
    public $FechaI;
    public $FechaF;
    
    public $ConceptoDif;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdOrdenCompra = 0;
        $this->IdSucursal= 0;
        $this->IdProyecto = '';
        $this->IdUsuario = '';
        $this->IdServicio = 0;
        $this->FechaReg = '';
        $this->Concepto = '';
        $this->Descripcion = '';
        $this->Archivo = '';
        $this->FolioArchivo = '';
        $this->Monto = '';
        $this->FechaMod = '';
        $this->RegEstatus = '';
        
        $this->IdCliente=0;
        $this->IdClienteS=0;
        $this->FechaI='';
        $this->FechaF='';
        
        $this->ConceptoDif='';
    }

    public function insert()
    {
        
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdProyecto', $this->IdProyecto);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('IdServicio', $this->IdServicio);     
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('Concepto', $this->Concepto);
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('Archivo', $this->Archivo);
        $this->db->set('FolioArchivo', $this->FolioArchivo);
        $this->db->set('Monto', $this->Monto);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
  
        $this->db->insert('spend_ordenc');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdOrdenCompra', $this->IdOrdenCompra);
        
        $this->db->set('IdProyecto', $this->IdProyecto);
        $this->db->set('IdServicio', $this->IdServicio);  
        $this->db->set('Concepto', $this->Concepto);
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('Archivo', $this->Archivo);
        $this->db->set('FolioArchivo', $this->FolioArchivo);
        $this->db->set('Monto', $this->Monto);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', 'A');
        
        
        $this->db->update('spend_ordenc');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdOrdenCompra', $this->IdOrdenCompra);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('spend_ordenc');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function deletexservicio()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        if($this->IdProyecto>0){# eliminamos todos menos el proyecto con el id recibido
            $this->db->where('IdProyecto !=', $this->IdProyecto);    
        }
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('spend_ordenc');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('spend_ordenc');
        $this->db->where('IdOrdenCompra', $this->IdOrdenCompra);

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
    
    public function get_recoveryxservicio()
    {
        $this->db->select('*');
        $this->db->from('spend_ordenc');
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->where('Concepto', $this->Concepto);

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
    
    public function get_recovery_MontoActual()
    {
        $this->db->select('sum(Monto) as MontoActual');
        $this->db->from('spend_ordenc');
        $this->db->where('IdSucursal', $this->IdSucursal);
        $this->db->where('RegEstatus', $this->RegEstatus);
        if($this->IdProyecto>0){
            $this->db->where('IdProyecto', $this->IdProyecto);    
        }
        $this->db->where('Concepto', $this->Concepto);

        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'MontoActual' => $data->MontoActual
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }
    
    public function get_MontoSum()
    {
        $this->db->select('sum(Monto) as Monto');
        $this->db->from('spend_ordenc');
        $this->db->where('IdSucursal', $this->IdSucursal);
        if($this->IdProyecto>0){
            $this->db->where('IdProyecto', $this->IdProyecto);    
        }
        
        if($this->Concepto!=''){
            $this->db->where('Concepto', $this->Concepto);    
        }
        
        if($this->ConceptoDif!=''){
            $this->db->where('Concepto !=', $this->ConceptoDif);    
        }
        
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
      
        if ($response->num_rows() > 0) {
            $data = $response->row();
            return $data->Monto;
        } else {
            return 0;
        }
    }

    public function get_list()
    {
        $this->db->select('soc.*,c.Nombre as Cliente,cs.Nombre as Sucursal,sp.Proyecto,DATE_FORMAT(soc.FechaReg, "%d-%m-%Y %H:%i:%s") as FechaReg, CONCAT_WS(" ",u.Nombre,u.Apellido) as Usuario');
        $this->db->from('spend_ordenc soc');
        $this->db->join('spend_proyecto sp','sp.IdProyecto=soc.IdProyecto','inner');
        $this->db->join('clientes c','c.IdCliente=sp.IdCliente','inner');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sp.IdClienteS','inner');
        $this->db->join('usuario u','u.IdUsuario=soc.IdUsuario','inner');
        
        $this->db->where('soc.IdSucursal ', $this->IdSucursal);
        // Filters
        if (!empty($this->IdOrdenCompra)) {
            $this->db->where('soc.IdOrdenCompra', $this->IdOrdenCompra);
        }
               
        if ($this->IdProyecto>0) {
            $this->db->where('sp.IdProyecto', $this->IdProyecto);
        }
        
        if ($this->IdCliente>0) {
            $this->db->where('sp.IdCliente', $this->IdCliente);
        }
        
        if ($this->IdClienteS>0) {
            $this->db->where('sp.IdClienteS', $this->IdClienteS);
        }
        
        if ($this->Concepto!='') {
            $this->db->where('soc.Concepto', $this->Concepto);
        }


        if (!empty($this->Descripcion)) {
            $this->db->like('soc.Descripcion', $this->Descripcion);
        }
        
        if ($this->FechaI !='' && $this->FechaF!='') {
            $where='cast(soc.FechaReg as date) >= \''.$this->FechaI.'\' and cast(soc.FechaReg as date) <= \''.$this->FechaF.'\'';
            $this->db->where($where);
        }
    
        if (!empty($this->RegEstatus)) {
            $this->db->where('soc.RegEstatus', $this->RegEstatus);
        }

        $this->db->order_by('soc.FechaReg','desc');
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}