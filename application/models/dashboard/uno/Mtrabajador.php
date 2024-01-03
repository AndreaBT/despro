<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtrabajador extends BaseModel
{
    // Properties
    public $IdTrabajador;
    public $Nombre;
    public $Telefono;
    public $Profesion; 
    public $Categoria;
    public $CostoHora;
    public $CostoAnual;
    public $IdSucursal;
    public $Usuario;
    public $Pass;
    public $RegEstatus; 
    public $Observaciones;
    public $Perfil;
    public $HorasTS;
    public $HorasPS;
    public $Imagen;
    public $IdCategoria;
    public $IdRol;
    public $IdUsuario;
    public $Correo;
    public $Estatus;
    public $Rol;
    public $Token;
    public $EstadoChat;
    public $IdTipoProceso;
    public $UpdateApp;
    public $GastoAsignado;
    public $IdCajaC;
    public $Inventario;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdTrabajador = '';
        $this->Nombre = '';
        $this->Telefono = '';
        $this->Profesion = '';
        $this->Categoria= '';
        $this->CostoHora = '';
        $this->CostoAnual = '';
        $this->IdSucursal = '';
        $this->Usuario = '';
        $this->Pass = '';
        $this->RegEstatus = '';
        $this->nIni = '';
        $this->nTam = '';
        $this->Observaciones='';
        $this->Perfil='';
        $this->HorasTS='';
        $this->HorasPS='';
        $this->Imagen='';
        $this->IdCategoria=0;
        $this->IdRol=0;
        $this->IdUsuario=0;
        $this->Correo="";
        $this->Rol="";
        $this->Token = '';
        $this->Estatus="";
        $this->EstadoChat='';
        $this->IdTipoProceso='';
        $this->UpdateApp = '';
        $this->GastoAsignado = '';
        $this->IdCajaC = '';
        $this->Inventario = '';
      
    }
    
    public function get_recobery_trabajador()
    {       
        
        $this->db->select('t.*');
        $this->db->from('trabajador t');
        
        
        $this->db->where('t.IdSucursal', $this->IdSucursal); 

        if (!empty($this->IdTrabajador)){
            $this->db->where('t.IdTrabajador ='. $this->IdTrabajador);
        }
        if (!empty($this->IdUsuario)){
            $this->db->where('t.IdUsuario ='. $this->IdUsuario);
        }
        if (!empty($this->RegEstatus)){
            $this->db->where('t.RegEstatus ='. $this->RegEstatus);
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
                 'data' => new Mtrabajador()
            ];
        }
    }

    public function get_list_trabajador($isSecure = false)
    {       
        $select = 't.IdTrabajador, t.Nombre, t.Telefono, t.Profesion, t.Categoria, t.CostoHora, t.CostoAnual, t.IdSucursal, t.Usuario, t.Pass, t.RegEstatus, t.Observaciones, t.Perfil, t.HorasTS, t.HorasPS, t.Imagen, t.IdCategoria, t.IdRol, t.IdUsuario, t.Correo, t.Estatus, t.Token, t.EstadoChat, t.IdTipoProceso, t.UpdateApp, t.GastoAsignado, t.IdCajaC, t.Inventario, t.FechaMod, t.Foto, t.IdPerfil, t.Foto2, r.Nombre as Rol';
        if($isSecure){
            $select = 't.IdTrabajador, t.Nombre, t.Telefono, t.Profesion, t.Categoria, t.CostoHora, t.CostoAnual, t.IdSucursal, t.Usuario, t.RegEstatus, t.Observaciones, t.Perfil, t.HorasTS, t.HorasPS, t.IdCategoria, t.IdRol, t.IdUsuario, t.Correo, t.Estatus, t.EstadoChat, t.IdTipoProceso, t.UpdateApp, t.GastoAsignado, t.IdCajaC, t.Inventario, t.FechaMod, t.Foto, t.IdPerfil, t.Foto2, r.Nombre as Rol';
        }
        
        $this->db->select($select);
        $this->db->from('trabajador t');
        $this->db->join('rol r','t.IdRol=r.IdRol','inner');
        
        
        $this->db->where('t.IdSucursal', $this->IdSucursal); 
       

        if (!empty($this->Nombre)){
            $this->db->like('t.Nombre', $this->Nombre);
        }

        if (!empty($this->Rol)){
            $this->db->where('r.Nombre', $this->Rol);
        }

        if (!empty($this->RegEstatus)){
            $this->db->where('t.RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->Categoria)){
            $this->db->where('t.Perfil', $this->Categoria);
        }

        if (!empty($this->Perfil)){
            $this->db->where('t.Perfil', $this->Perfil);
        }

          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
      
?>