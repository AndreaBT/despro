<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mspend_proyecto extends BaseModel
{
    // Properties
    public $IdProyecto;
    public $IdSucursal;
    public $IdCliente;
    public $IdClienteS;
    public $IdUsuario;
    public $FechaReg;
    public $Folio;
    public $FechaI;
    public $FechaTermino;
    public $CantidadTermino;
    public $Proyecto;
    public $ValorHora;
    public $ValorBurden;
    public $Tc;
    public $CostoOperacional;
    public $GAVentas;
    public $NetProfit;
    public $CostoOpePorc;
    public $GAVentasPorc;
    public $NetProfitPorc;
    public $Archivo;
    
    public $Observaciones;
    public $FechaMod;
    public $Estatus;
    public $FechaCierre;
    public $RegEstatus;
    
    public $FechaF;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdProyecto=0;
        $this->IdSucursal=0;
        $this->IdCliente=0;
        $this->IdClienteS=0;
        $this->IdUsuario=0;
        $this->FechaReg=0;
        $this->Folio='';
        $this->FechaI='';
        $this->FechaTermino='';
        $this->CantidadTermino='';
        $this->Proyecto='';
        $this->ValorHora=0;
        $this->ValorBurden=0;
        $this->Tc=0;
        $this->CostoOperacional=0;
        $this->GAVentas=0;
        $this->NetProfit=0;
        $this->CostoOpePorc=0;
        $this->GAVentasPorc=0;
        $this->NetProfitPorc=0;
        $this->Archivo='';
        $this->Observaciones='';
        $this->FechaMod='';
        $this->Estatus='';
        $this->FechaCierre='';
        $this->RegEstatus='';
        
        $this->FechaF='';
    }

    public function insert()
    {
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdUsuario', $this->IdUsuario);   
        $this->db->set('FechaReg', $this->FechaReg);
        $this->db->set('Folio', $this->Folio);
        $this->db->set('FechaI', $this->FechaI);
        $this->db->set('FechaTermino', $this->FechaTermino);
        $this->db->set('CantidadTermino', $this->CantidadTermino);
        $this->db->set('Proyecto', $this->Proyecto);
        $this->db->set('ValorHora', $this->ValorHora);
        $this->db->set('ValorBurden', $this->ValorBurden);
        $this->db->set('Tc', $this->Tc);
        $this->db->set('CostoOperacional', $this->CostoOperacional);
        $this->db->set('GAVentas', $this->GAVentas);
        $this->db->set('NetProfit', $this->NetProfit);
        $this->db->set('CostoOpePorc', $this->CostoOpePorc);
        $this->db->set('GAVentasPorc', $this->GAVentasPorc);
        $this->db->set('NetProfitPorc', $this->NetProfitPorc);
        $this->db->set('Archivo', $this->Archivo);
        $this->db->set('Observaciones', $this->Observaciones);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->set('FechaCierre', $this->FechaCierre);
        $this->db->set('RegEstatus', $this->RegEstatus);
  
        $this->db->insert('spend_proyecto');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdProyecto', $this->IdProyecto);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('FechaI', $this->FechaI);
        $this->db->set('FechaTermino', $this->FechaTermino);
        $this->db->set('CantidadTermino', $this->CantidadTermino);
        $this->db->set('Proyecto', $this->Proyecto);
        $this->db->set('ValorHora', $this->ValorHora);
        $this->db->set('ValorBurden', $this->ValorBurden);
        $this->db->set('Tc', $this->Tc);
        $this->db->set('CostoOperacional', $this->CostoOperacional);
        $this->db->set('GAVentas', $this->GAVentas);
        $this->db->set('NetProfit', $this->NetProfit);
        $this->db->set('CostoOpePorc', $this->CostoOpePorc);
        $this->db->set('GAVentasPorc', $this->GAVentasPorc);
        $this->db->set('NetProfitPorc', $this->NetProfitPorc);
        $this->db->set('Observaciones', $this->Observaciones);
        $this->db->set('Archivo', $this->Archivo);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('spend_proyecto');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdProyecto', $this->IdProyecto);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('spend_proyecto');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update_estatus()
    {
        $this->db->where('IdProyecto', $this->IdProyecto);
      
        $this->db->set('Estatus', $this->Estatus);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('FechaCierre', $this->FechaCierre);
        $this->db->update('spend_proyecto');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('spend_proyecto');
        $this->db->where('IdProyecto', $this->IdProyecto);
        
        if($this->Estatus!=''){
            $this->db->where('Estatus', $this->Estatus);    
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
                'status' => false
            ];
        }
    }
    
 

    public function get_list()
    {
        $this->db->select('sp.*,DATE_FORMAT(sp.FechaI, "%d-%m-%Y") as FechaI,c.Nombre as Cliente,cs.Nombre as Sucursal,cs.Direccion,cs.Ciudad');
        $this->db->from('spend_proyecto sp');
        $this->db->join('clientes c','c.IdCliente=sp.IdCliente','inner');
        $this->db->join('clientesucursal cs','cs.IdClienteS=sp.IdClienteS','inner');
        
        $this->db->where('sp.IdSucursal =', $this->IdSucursal);
        // Filters
        if (!empty($this->IdProyecto)) {
            $this->db->where('sp.IdProyecto', $this->IdProyecto);
        }
        
        if ($this->IdCliente>0) {
            $this->db->where('sp.IdCliente', $this->IdCliente);
        }
        
        if ($this->IdClienteS>0) {
            $this->db->where('sp.IdClienteS', $this->IdClienteS);
        }
      
        if ($this->FechaI !='' && $this->FechaF!='') {
            $where='sp.FechaI >= \''.$this->FechaI.'\' and sp.FechaI <= \''.$this->FechaF.'\'';
            $this->db->where($where);
        }
        
        if (!empty($this->Proyecto)) {
            $where='( sp.Proyecto like \'%'.$this->Proyecto.'%\' or sp.Folio like \''.$this->Proyecto.'%\')';
            $this->db->where($where);
        }

        if (!empty($this->RegEstatus)) {
            $this->db->where('sp.RegEstatus', $this->RegEstatus);
        }
        
        if ($this->Estatus!='') {
            $this->db->where('sp.Estatus', $this->Estatus);
        }
        
        $this->db->order_by('sp.FechaReg','desc');
        //Pagination
        $this->set_pagination();
        
        //echo $result = $this->db->get_compiled_select();

        $response = $this->db->get();
        return $response->result();
    }
  
}