<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MtblResetPass extends BaseModel
{
    // Properties
    public $IdReset;
    public $IdUsuario;
    public $Candado;
    public $token;
    public $fechaReg;
	public $email;
	public $RegEstatus;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdReset 		= 0;
		$this->IdUsuario 	= '';
		$this->Candado 		= '';
		$this->token 		= '';
		$this->fechaReg 	= '';
		$this->email		= '';
		$this->RegEstatus 	= '';
    }

    public function insert()
    {
		$this->db->set('IdUsuario', $this->IdUsuario);
	    $this->db->set('Candado', $this->Candado);
	    $this->db->set('token', $this->token);
	    $this->db->set('fechaReg', $this->fechaReg);
	    $this->db->set('email', $this->email);
	    
        $this->db->set('RegEstatus', 'A');
        $this->db->insert('tblresetpass');
        return $this->db->insert_id();
    }

    public function update()
    {
		$this->db->where('IdReset',$this->IdReset);
        $this->db->set('IdUsuario', $this->IdUsuario);
	    $this->db->set('Candado', $this->Candado);
	    $this->db->set('token', $this->token);
	    $this->db->set('fechaReg', $this->fechaReg);
	    $this->db->set('email', $this->email);
	    
        $this->db->update('tblresetpass');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
		$this->db->where('IdReset',$this->IdReset);
        $this->db->delete('tblresetpass');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	public function deleteUsersname()
	{
        $this->db->where('Candado',$this->Candado);
        $this->db->delete('tblresetpass');
        return true;
	}

    public function get_recovery()
    {
		$this->db->select('IdReset,IdUsuario,Candado,token,fechaReg,email,RegEstatus');
        $this->db->from('tblresetpass');
        $this->db->where('IdReset',$this->IdReset);
		
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
	
	public function get_tblResetPass_token()
	{
		$this->db->select('IdReset,IdUsuario,Candado,token,fechaReg,email,RegEstatus');
        $this->db->from('tblresetpass');
        $this->db->where('token',$this->token);
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
		$this->db->select('IdReset,IdUsuario,Candado,token,fechaReg,email,RegEstatus');
        $this->db->from('tblresetpass');
        
        if(!empty($this->token)){
            $this->db->where('token',$this->token);
		}
		if(!empty($this->RegEstatus)){
            $this->db->where('RegEstatus',$this->RegEstatus);
		}
		
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
     public function get_exist()
    {
        $this->db->select('IdReset,IdUsuario,Candado,token,fechaReg,email,RegEstatus');
        $this->db->from('tblresetpass');
        $this->db->where('IdReset', $this->IdReset);
        $this->db->where('Candado', $this->Candado);
		 $this->db->where('token', $this->token);
        $this->db->where('RegEstatus','A');
        if( $this->IdReset>0){
            $this->db->where('IdReset !=', $this->IdReset);    
        }
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
?>