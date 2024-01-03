<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdetalleestado extends BaseModel
{
    // Properties

    public $IdEstadoF;
    public $Pasado;  
    public $PorcentajePasado;
    public $IdPlanFactura;


    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdEstadoF = '';
        $this->Pasado = 0;
        $this->PorcentajePasado = 0;
        $this->IdPlanFactura = '';
    }


    public function get_recobery_detalleestadofinanciero()
    {

        $this->db->select('*');
        $this->db->from('detalleestadofinanciero');

        if (!empty($this->IdPlanFactura)) {
            $this->db->where('IdPlanFactura', $this->IdPlanFactura);
        }

        if (!empty($this->IdEstadoF)) {
            $this->db->where('IdEstadoF', $this->IdEstadoF);
        }

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
                'status' => false,
                 'data' => new Mdetalleestado()
            ];
        }
    }
}
