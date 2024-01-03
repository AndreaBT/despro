<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mubicaciont extends BaseModel
{
    // Properties
    public $IdTrabajador;
    public $IdSucursal;
    public $Lat;
    public $Lng ;
    public $Estatus;
    public $IdServicio;
  

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdTrabajador = 0;
        $this->IdSucursal = 0;
        $this->Lat = '';
        $this->Lng = '';
        $this->Estatus = '';
        $this->IdServicio = '';
    }

    public function insert_ubicacion()
    {
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('lat', $this->Lat);
        $this->db->set('lng', $this->Lng);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->insert('ubicacion');

        return $this->db->insert_id();
    }
    public function update_ubicacion()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('lat', $this->Lat);
        $this->db->set('lng', $this->Lng);
        //$this->db->set('FechaMod', $this->FechaMod);  
        $this->db->update('ubicacion');

            return true;
        
    }
    
    public function update_ubicacionactual()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('Estatus', $this->Estatus);
        //$this->db->set('FechaMod', $this->FechaMod);  
        $this->db->update('ubicacion');

            return true;
        
    }

    public function get_usuario()
    {
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo');
        $this->db->from('usuario');
        $this->db->where('IdUsuario', $this->IdUsuario);

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

    public function get_ubicacionexist()
    {
        $this->db->select('*');
        $this->db->from('ubicacion');
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
        $this->db->select('s.IdServicio,s.Folio,s.Cliente, ts.Concepto,DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as Fecha_I,DATE_FORMAT(s.Fecha_F, "%d-%m-%Y") as Fecha_F,fs.HoraInicio,fs.HoraFin ,u.*,t.Nombre,t.Telefono,t.Estatus,t.Foto2');
        $this->db->from('ubicacion u');
        $this->db->join('servicio s', 's.IdServicio=u.IdServicio', 'left');
        $this->db->join('trabajador t', 'u.IdTrabajador=t.IdTrabajador', 'inner');
        $this->db->join('tiposervicio ts', 'ts.IdTipoSer=s.Tipo_Serv', 'left');
        $this->db->join('fechaservicio fs', 'fs.IdServicio=s.IdServicio', 'left');
        
         $this->db->where('u.IdSucursal', $this->IdSucursal);
         $this->db->where('u.Estatus !=', "CERROSESION");
         $this->db->where('t.RegEstatus', "A");
         
        # echo $result = $this->db->get_compiled_select();
         
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
        $this->db->update('ubicacion');

            return true;
        
    }

    public function update_estatus()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Estatus', 'DISPONIBLE');  
        $this->db->update('ubicacion');

        return true;
        
    }

    public function insert_estatus_lo()
    {
        $this->db->where('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Estatus', 'DISPONIBLE');  
        $this->db->update('ubicacion');

        return true;
        
    }

   
}
