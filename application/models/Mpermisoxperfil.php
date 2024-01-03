<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpermisoxperfil extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $IdPermiso;
    public $IdPerfil;
    public $Clave;
    
    //para buscar
    public $Rol;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete = 0;
        $this->IdPermiso = 0;
        $this->IdPerfil = 0;
        $this->Clave = '';
        
        $this->Rol='';
    }

    public function get_list()
    {
        $listseguridad=[];
        if($this->Clave=='Autorizado'){//validacion momentanea para autorizar si no tiene clave una seccion
            $Permiso=true;
            $listseguridad['Save']=$Permiso;
            $listseguridad['Edit']=$Permiso;
            $listseguridad['Delete']=$Permiso;
            return $listseguridad;
        }

        $this->db->select('pxm.*,p.Clave');
        $this->db->from('menuxpermiso pxm');
        $this->db->where('m.Clave', $this->Clave);
        $this->db->join('permisos p','p.IdPermiso=pxm.IdPermiso','inner');
        $this->db->join('menus m','m.IdPaquete=pxm.IdPaquete','inner');
        $response = $this->db->get();
        $row= $response->result();
        
        
        if(count($row)>0){
            foreach($row as $element){
                $this->db->select('pxp.*,p.Nombre as Permiso');
                $this->db->from('permisoxperfil pxp');
                $this->db->where('pxp.IdPaquete',$element->IdPaquete);
                $this->db->where('pxp.IdPerfil', $this->IdPerfil);
                $this->db->where('pxp.IdPermiso', $element->IdPermiso);
                $this->db->join('permisos p','p.IdPermiso=pxp.IdPermiso','inner');
                $response = $this->db->get();
                
                $Permiso=count($response->result());
                if($this->Rol=='Administrador'){
                    $Permiso=true;   
                }
            
                $listseguridad[$element->Clave]=$Permiso;
            } 
        }else{
            $Permiso=false;
            if($this->Rol=='Administrador' || $this->Rol==''){
                $Permiso=true;
            }
            $listseguridad['Save']=$Permiso;
            $listseguridad['Edit']=$Permiso;
            $listseguridad['Delete']=$Permiso;
            $listseguridad['ConfigPuestos']=$Permiso;
            $listseguridad['ConfigPermisos']=$Permiso;
            
        }

        
        return $listseguridad;
    }

    public function insert()
    {
		$this->db->set('IdPerfil', $this->IdPerfil);
        $this->db->set('IdPaquete', $this->IdPaquete);
        $this->db->set('IdPermiso', $this->IdPermiso);
        
        $this->db->insert('permisoxperfil');
        return $this->db->insert_id();
    }

    public function delete()
    {
        $this->db->where('IdPaquete', $this->IdPaquete);
        $this->db->where('IdPerfil', $this->IdPerfil);

        if (!empty($this->IdPermiso)) {
            $this->db->where('IdPermiso', $this->IdPermiso);
        }
        $this->db->delete('permisoxperfil');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_listPermisosxPuesto()
    {
        $this->db->select('IdPaquete,IdPermiso,IdPerfil');
        $this->db->from('permisoxperfil');
        
        if (!empty($this->IdPaquete)) {
            $this->db->where('IdPaquete', $this->IdPaquete);
        }
        if (!empty($this->IdPerfil)) {
            $this->db->where('IdPerfil', $this->IdPerfil);
        }
        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function get_listMenusSegurity()
    {
        $this->db->select('IdPaquete,Nombre,Tipo,Asociado,Clave');
        $this->db->from('menus');
        $this->db->where('RegEstatus','A');

        $response = $this->db->get();
        $row = $response->result();
        
        $listseguridad=[];
        if(count($row)>0)
        {
            foreach($row as $element)
            {
                $this->db->select('IdPaquete,IdPerfil');
                $this->db->from('permisoxmenu');
                $this->db->where('IdPaquete',$element->IdPaquete);
                $this->db->where('IdPerfil', $this->IdPerfil);
                $response = $this->db->get();
                
                $Permiso = count($response->result());
                if($this->Rol=='Administrador'){
                    $Permiso = true;   
                }
            
                $listseguridad[$element->Clave] = $Permiso;
            }
        }
        else
        {
            $Permiso = false;
            if($this->Rol == 'Administrador'){
                $Permiso = true;   
            }
            $listseguridad['MSeguridad'] = $Permiso;
            $listseguridad['SMDepartamento'] = $Permiso;
            $listseguridad['MAdmin'] = $Permiso;
            $listseguridad['MDashboard'] = $Permiso;
        }

        
        return $listseguridad;
    }

}
