<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mserviciosvalores extends BaseModel
{
    // Properties
    public $IdConfigS;
    public $BaseActual;
    public $ComisionA;
    public $IdSucursal;
    public $Anio;
    public $IdTipoSer;

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdConfigS = 0;
        $this->BaseActual = '';
        $this->ComisionA = '';
        $this->IdSucursal='';
        $this->Anio='';
        $this->IdTipoSer=''; 

    }

    public function get_recobery_serviciosvalores()
    {       
        
        $this->db->select('*');
        $this->db->from('serviciosvalores');
        
        if (!empty($this->IdConfigS)){
            $this->db->where('IdConfigS', $this->IdConfigS);
        }

        if (!empty($this->IdSucursal)){
            $this->db->where('IdSucursal', $this->IdSucursal);
        }

        if (!empty($this->Anio)){
            $this->db->where('Anio='.$this->Anio);
        }

        if (!empty($this->IdTipoSer)){
            $this->db->where('IdTipoSer', $this->IdTipoSer);
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
                 'data' => new Mserviciosvalores()
            ];
        }
    }
}
