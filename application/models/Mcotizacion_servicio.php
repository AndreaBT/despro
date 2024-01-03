<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcotizacion_servicio extends BaseModel
{
    // Properties
    public $IdCotizacionServicio;
    public $IdServicio;
    public $IdCliente;
    public $IdUsuario;
    public $IdSucursal;
    public $GrossProfit;
    public $utilidad;
    public $gastoOperaciones;
    public $factorHoraExtra;
    public $totalMateriales;
    public $totalManoDeObra;
    public $totalMiscelaneos;
    public $costoKm;
    public $fechaCotiServicio;
    public $totalGlobal;
    public $distancia;
    public $velocidad;
    public $RegEstatus;
    public $Folio;
    public $TotalCostoKm;
    public $Garantia;
    public $Comentario;
    public $CostoBurden;
    public $CostoDesplazamiento;
    public $CostoManoObraD;
    public $ValorVenta;
    public $FechaMod;
    public $Observaciones;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdCotizacionServicio = 0;
        $this->IdServicio= 0;
        $this->IdCliente= 0;
        $this->IdUsuario = '';
        $this->IdSucursal = '';
        $this->GrossProfit = '';
        $this->utilidad = '';
        $this->gastoOperaciones= '';
        $this->factorHoraExtra= '';
        $this->totalMateriales= '';
        $this->totalManoDeObra = '';
        $this->totalMiscelaneos= '';
        $this->costoKm= '';
        $this->fechaCotiServicio= '';
        $this->totalGlobal= '';
        $this->distancia= '';
        $this->velocidad= '';
        $this->RegEstatus= '';
        $this->Folio= '';
        $this->TotalCostoKm= '';
        $this->Garantia= 0;
        $this->Comentario= '';
        $this->CostoBurden= 0;
        $this->CostoDesplazamiento= 0;
        $this->CostoManoObraD= 0;
        $this->ValorVenta= '';
        $this->FechaMod= '';
        $this->Observaciones= '';
    }

    public function insert()
    {
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdUsuario', $this->IdUsuario);
        $this->db->set('IdSucursal', $this->IdSucursal);   
        $this->db->set('GrossProfit', $this->GrossProfit);
        $this->db->set('utilidad', $this->utilidad);
        
        $this->db->set('gastoOperaciones',$this->gastoOperaciones);
        $this->db->set('factorHoraExtra',$this->factorHoraExtra);
        $this->db->set('totalMateriales',$this->totalMateriales);
        $this->db->set('totalManoDeObra',$this->totalManoDeObra);
        $this->db->set('totalMiscelaneos',$this->totalMiscelaneos);
        $this->db->set('costoKm',$this->costoKm);
        $this->db->set('fechaCotiServicio',$this->fechaCotiServicio);
        $this->db->set('totalGlobal',$this->totalGlobal);
        $this->db->set('distancia',$this->distancia);
        $this->db->set('velocidad',$this->velocidad);
        $this->db->set('RegEstatus',$this->RegEstatus);
        $this->db->set('Folio',$this->Folio);
        $this->db->set('TotalCostoKm',$this->TotalCostoKm);
        $this->db->set('Garantia',$this->Garantia);
        $this->db->set('Comentario',$this->Comentario);
        $this->db->set('CostoBurden',$this->CostoBurden);
        $this->db->set('CostoManoObraD',$this->CostoManoObraD);
        $this->db->set('ValorVenta',$this->ValorVenta);
        $this->db->set('FechaMod',$this->FechaMod);
        $this->db->set('Observaciones',$this->Observaciones);        
  
        $this->db->insert('cotizacion_servicio');
        
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        
        $this->db->set('IdCliente', $this->IdCliente);
           
        $this->db->set('GrossProfit', $this->GrossProfit);
        $this->db->set('utilidad', $this->utilidad);
        
        $this->db->set('gastoOperaciones',$this->gastoOperaciones);
        $this->db->set('factorHoraExtra',$this->factorHoraExtra);
        $this->db->set('totalMateriales',$this->totalMateriales);
        $this->db->set('totalManoDeObra',$this->totalManoDeObra);
        $this->db->set('totalMiscelaneos',$this->totalMiscelaneos);
        $this->db->set('costoKm',$this->costoKm);
        $this->db->set('fechaCotiServicio',$this->fechaCotiServicio);
        $this->db->set('totalGlobal',$this->totalGlobal);
        $this->db->set('distancia',$this->distancia);
        $this->db->set('velocidad',$this->velocidad);
        
        
        $this->db->set('TotalCostoKm',$this->TotalCostoKm);
        $this->db->set('Garantia',$this->Garantia);
        $this->db->set('Comentario',$this->Comentario);
        $this->db->set('CostoBurden',$this->CostoBurden);
        $this->db->set('CostoManoObraD',$this->CostoManoObraD);
        $this->db->set('ValorVenta',$this->ValorVenta);
        $this->db->set('FechaMod',$this->FechaMod);
        $this->db->set('Observaciones',$this->Observaciones);         
        
        $this->db->update('cotizacion_servicio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
      
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('cotizacion_servicio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('cotizacion_servicio');
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);

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
        $this->db->select('cs.*,csuc.Nombre as Sucursal,c.Nombre as Cliente,DATE_FORMAT(cs.fechaCotiServicio, "%d-%m-%Y") as fechaCotiServicio');
        $this->db->from('cotizacion_servicio cs');
        $this->db->join('clientesucursal csuc','csuc.IdClienteS=cs.IdCliente','inner');
        $this->db->join('clientes c','c.IdCliente=csuc.IdCliente','inner');
        $this->db->where('cs.IdSucursal', $this->IdSucursal);

        $this->db->where('cs.IdServicio', '0');

        
        // Filters
        if (!empty($this->IdCotizacionServicio)) {
            $this->db->where('cs.IdCotizacionServicio', $this->IdCotizacionServicio);
        }

        if (!empty($this->Folio)) {
            $this->db->like('cs.Folio', $this->Folio);
             //$this->db->or_like('c.Nombre', $this->Folio);
             // $this->db->or_like('csuc.Nombre', $this->Folio);
        }
        
        if (!empty($this->RegEstatus)) {
            $this->db->where('cs.RegEstatus', $this->RegEstatus);
        }
            $this->db->order_by("IdCotizacionServicio", "desc");
        //Pagination
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function updatestatus()
    {
        $this->db->where('IdCotizacionServicio', $this->IdCotizacionServicio);
        $this->db->set('Estatus',$this->Estatus);
        $this->db->set('FechaMod',$this->FechaMod);        
        
        $this->db->update('cotizacion_servicio');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
  
}