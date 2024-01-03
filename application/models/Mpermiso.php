<?php

class Mpermiso extends BaseModel {
	public $IdPermiso;
	public $Nombre;
	public $Clave;
	public $RegEstatus;
	
	/*paginacion*/
	public $Tamano;
	public $Pagina;
    public $Offset;
	
	public function __construct() {
        parent::__construct();
		$this->IdPermiso    = 0;
		$this->Nombre  = '';
		$this->Clave        = '';
		$this->RegEstatus   = '';
		$this->Tamano       = 0;
        $this->Pagina       = 0;
        $this->Offset       = 0;
	}
	
	public function set_insert() {
        $this->db->set('Nombre', $this->Nombre);
	    $this->db->set('Clave', $this->Clave);
        $this->db->set('RegEstatus', 'A');
        $this->db->insert('permiso');
        return $this->db->insert_id();
	}

    public function update() {
        $this->db->where('IdPermiso', $this->IdPermiso);
        $this->db->set('Nombre', $this->Nombre);
	    $this->db->set('Clave',       $this->Clave);
        $this->db->update('permiso');
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
	
	public function set_update() {
        $this->db->where('IdPermiso', $this->IdPermiso);
        $this->db->set('Nombre', $this->Nombre);
	    $this->db->set('Clave',       $this->Clave);
        $this->db->update('permiso');
        return true;
	}
	
	public function set_delete() {
        $this->db->where('IdPermiso',$this->IdPermiso);
        $this->db->set('RegEstatus', 'B');
        $this->db->update('permiso');
    
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
	}
	
	public function get_recovery_permiso() {
        $this->db->select('IdPermiso,Nombre,Clave,RegEstatus');
        $this->db->from('permiso');
        $this->db->where('IdPermiso',$this->IdPermiso);
        $response = $this->db->get();

        //echo $result = $this->db->get_compiled_select();
        if ($response->num_rows() > 0) {
            $data = $response->row();
            return [ 'status' => true, 'data' => $data ];
        } 
	}
	
	public function get_list_permiso() {
        $this->db->select('IdPermiso,Nombre,Clave,RegEstatus');
        //Traer todos $this->db->select('*');
        $this->db->from('permiso');
        
        if($this->Nombre != ''){
            $this->db->like('Nombre ',$this->Nombre);
        }

        if($this->RegEstatus != ''){
            $this->db->where('RegEstatus',$this->RegEstatus);
		}   
        
        //echo $result = $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $result = $this->db->get();
        return $result->result(); 

	}
}
?>
