<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconfigservicio extends BaseModel
{
    // Properties
    public $IdConfigS;
    public $Nombre;
    public $Porcentaje;
    public $Porcentaje2;
    public $RegEstatus;
    public $Facturable;
    
    public $BaseActual;
    public $ComisionA;


      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdConfigS = 0;
        $this->Nombre = 0;
        $this->Porcentaje = '';
        $this->Porcentaje2='';
        $this->RegEstatus='';
        $this->Facturable='';

      
    }

    public function get_recobery_configservicio()
    {       
        
        $this->db->select('*');
        $this->db->from('configservicio');
        
        if (!empty($this->RegEstatus)){
            $this->db->where('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdConfigS)){
            $this->db->where('IdConfigS', $this->IdConfigS);
        }

        if (!empty($this->Nombre)){
            $this->db->where('Nombre', $this->Nombre);
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
                 'data' => new Mconfigservicio()
            ];
        }
    }

    public function get_list_configservicio()
    {       
        
        $this->db->select('*');
        $this->db->from('configservicio');
        
        $this->db->where('RegEstatus', 'A');

        if ($this->Nombre!=''){
            $and =' Nombre like\'%'.$this->Nombre.'%\'';
            $this->db->where($and);
        }

        if ($this->Facturable!=''){
            $and =' Facturable like\'%'.$this->Facturable.'%\'';
            $this->db->where($and);
        }

        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }


}
