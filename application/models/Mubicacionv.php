<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mubicacionv extends BaseModel
{
    // Properties
    public $IdTrabajador;
    public $IdSucursal;
    public $Lat;
    public $Lng ;
    public $Estatus;
    public $IdSeguimientoCliente;
    public $HoraInicio;
    public $Fecha;
    

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdTrabajador = 0;
        $this->IdSucursal = 0;
        $this->Lat = '';
        $this->Lng = '';
        $this->Estatus = '';
        $this->IdSeguimientoCliente = '';
        $this->HoraInicio = '';
        $this->Fecha = '';
    }

    public function insert_ubicacion()
    {
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('lat', $this->Lat);
        $this->db->set('lng', $this->Lng);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->insert('ubicacionv');

        return true;
    }
    public function update_ubicacion()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('lat', $this->Lat);
        $this->db->set('lng', $this->Lng);
        //$this->db->set('FechaMod', $this->FechaMod);  
        $this->db->update('ubicacionv');
        return true;
    }
    
    public function update_ubicacionactual()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Estatus', $this->Estatus);
        //$this->db->set('FechaMod', $this->FechaMod);  
        $this->db->update('ubicacionv');
        return true;
    }

  

    public function get_ubicacionexist()
    {
        $this->db->select('*');
        $this->db->from('ubicacionv');
        $this->db->where('IdTrabajador', $this->IdTrabajador);

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
        $this->db->distinct('u.IdTrabajador');
        $this->db->select('u.*, t.Nombre, t.Telefono, t.Estatus, t.Foto2,cs.Nombre as ClienteSucursal,cs.Direccion,sc.Actividad,
        sc.HoraInicio,sc.HoraFin');
        $this->db->from('ubicacionv u');
        $this->db->join('trabajador t', 'u.IdTrabajador=t.IdTrabajador', 'inner');
        $this->db->join('seguimientocliente sc ', ' sc.IdTrabajador=u.IdTrabajador ', 'inner');
        $this->db->join('clientesucursal cs ', 'sc.IdClienteSucursal=cs.IdClienteS ', 'inner');
        
        $this->db->where('u.IdSucursal', $this->IdSucursal);
        $this->db->where('sc.IdSucursal', $this->IdSucursal);
        $this->db->where('u.Estatus !=', "CERROSESION");
        $this->db->where('t.RegEstatus', "A");

        if($this->HoraInicio!='')
        {
            $where='(sc.HoraInicio <= \''.$this->HoraInicio.'\' and sc.HoraFin > \''.$this->HoraInicio.'\' )';
            $this->db->where($where);
        }
        if (!empty($this->Fecha)) {
            $this->db->where('sc.Fecha ', $this->Fecha);
        }
         
         #echo $result = $this->db->get_compiled_select();
         
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function update_ubicacioncerrar()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        //$this->db->set('lat', $this->Lat);
        //$this->db->set('lng', $this->Lng);
        $this->db->set('Estatus', 'CERROSESION');  
        $this->db->update('ubicacionv');

            return true;
        
    }

    public function update_estatus()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Estatus', 'DISPONIBLE');  
        $this->db->update('ubicacionv');

        return true;
        
    }

    public function insert_estatus_lo()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Estatus', 'DISPONIBLE');  
        $this->db->update('ubicacionv');

        return true;
        
    }

   
}
