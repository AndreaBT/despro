<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mtiposervicio extends BaseModel
{
    // Properties
    public $IdTipoSer;
    public $Concepto;
    public $IdSucursal;
    public $RegEstatus;
    public $GrossM;
    public $Color;
    public $Ingresos;
    public $IdIcono;
    public $Tipo;
    public $IdConfigS;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdTipoSer = '';
        $this->Concepto = '';
        $this->IdSucursal='';
        $this->RegEstatus='';
        $this->GrossM='';
        $this->Color='';
        $this->Ingresos='';
        $this->IdIcono='';
        $this->Tipo='';
        $this->IdConfigS='';
      
    }
    
    public function get_recobery_tiposervicio()
    {       
        
        
        $this->db->select('*');
        $this->db->from('tiposervicio');
        
        if (!empty($this->RegEstatus)){
            $this->db->where('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdTipoSer)){
            $this->db->where('IdTipoSer', $this->IdTipoSer);
        }

        if (!empty($this->Concepto)){
            $Consulta = 'having Concepto like \'%'.$this->Concepto.'%\'';
            $this->db->where($Consulta);
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
                 'data' => new Mtiposervicio()
            ];
        }
    }


    public function get_list_tiposervicio(){

        $this->db->select('*');
        $this->db->from('tiposervicio');
        $this->db->where('IdSucursal', $this->IdSucursal); 
        $this->db->where('RegEstatus', 'A'); 

       
        if (!empty($this->Concepto)){
            $Consulta = 'having Concepto like \'%'.$this->Concepto.'%\'';
            $this->db->where($Consulta);
        }

        if (!empty($this->Ingresos)){
            $this->db->where('Ingresos', $this->Ingresos);
        }

        if (!empty($this->IdConfigS)){
            $this->db->where('IdConfigS', $this->IdConfigS);
        }

          
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
        //echo $this->db->get_compiled_select();
    }
}
