<?php

class Mpanel extends BaseModel {
	public $IdPanel;
	public $Nombre;
	public $Tipo;
	public $Asociado;
    public $ReqPermiso;
    public $Clave;
    public $LinkJs;
    public $Lugar;
    public $Icono;
    public $RegEstatus;
	
	/*paginacion*/
	public $Tamano;
	public $Pagina;
    public $Offset;
	
	public function __construct() {
        parent::__construct();
		$this->IdPanel      = 0;
		$this->Nombre       = '';
		$this->Tipo         = '';
		$this->Asociado     = '';
        $this->ReqPermiso   = '';
        $this->Clave        = '';
        $this->LinkJs       = '';
        $this->Lugar        = '';
        $this->Icono        = '';
        $this->RegEstatus   = '';
		$this->Tamano       = 0;
        $this->Pagina       = 0;
        $this->Offset       = 0;
	}
	
	public function set_insert() {
        $this->db->set('Nombre',    $this->Nombre);
	    $this->db->set('Tipo',      $this->Tipo);
        $this->db->set('Asociado',  $this->Asociado);
        $this->db->set('ReqPermiso',$this->ReqPermiso);
        $this->db->set('Clave',     $this->Clave);
        $this->db->set('LinkJs',    $this->LinkJs);
        $this->db->set('Lugar',     $this->Lugar);
        $this->db->set('Icono',     $this->Icono);
        $this->db->set('RegEstatus', 'A');
        $this->db->insert('panel');
        return $this->db->insert_id();
	}

    public function update() {
        $this->db->where('IdPanel', $this->IdPanel);
        $this->db->set('Nombre',    $this->Nombre);
	    $this->db->set('Tipo',      $this->Tipo);
        $this->db->set('Asociado',  $this->Asociado);
        $this->db->set('ReqPermiso',$this->ReqPermiso);
        $this->db->set('Clave',     $this->Clave);
        $this->db->set('LinkJs',    $this->LinkJs);
        $this->db->set('Lugar',     $this->Lugar);
        $this->db->set('Icono',     $this->Icono);
        $this->db->update('panel');
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
	
	public function set_update() {
        $this->db->where('IdPanel', $this->IdPanel);
        $this->db->set('Nombre',    $this->Nombre);
	    $this->db->set('Tipo',      $this->Tipo);
        $this->db->set('Asociado',  $this->Asociado);
        $this->db->set('ReqPermiso',$this->ReqPermiso);
        $this->db->set('Clave',     $this->Clave);
        $this->db->set('LinkJs',    $this->LinkJs);
        $this->db->set('Lugar',     $this->Lugar);
        $this->db->set('Icono',     $this->Icono);
        $this->db->update('panel');
        return true;
	}
	
	public function set_delete() {
        $this->db->where('IdPanel',$this->IdPanel);
        $this->db->set('RegEstatus', 'B');
        $this->db->update('panel');
    
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
	
	public function get_recovery_panel() {
        $this->db->select('IdPanel,
        Nombre,
        Tipo,
        Asociado,
        ReqPermiso,
        Clave,
        LinkJs,
        Lugar,
        Icono'
        );
        $this->db->from('panel');
        $this->db->where('IdPanel',$this->IdPanel);
        $response = $this->db->get();

        //echo $result = $this->db->get_compiled_select();
        if ($response->num_rows() > 0) {
            $data = $response->row();
            return [ 'status' => true, 'data' => $data ];
        } 
	}
	
	public function get_list_panel() {
        $this->db->select('IdPanel,
        Nombre,
        Tipo,
        Asociado,
        ReqPermiso,
        Clave,
        LinkJs,
        Lugar,
        Icono');
        //Traer todos $this->db->select('*');
        $this->db->from('panel');
        
        if($this->Nombre != ''){
            $this->db->like('Nombre ',$this->Nombre);
        }   
        
        //echo $result = $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $result = $this->db->get();
        return $result->result(); 

	}
}
?>
