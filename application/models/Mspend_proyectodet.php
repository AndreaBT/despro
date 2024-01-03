<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mspend_proyectodet extends BaseModel
{
    // Properties
    public $IdProyecto;
    public $Concepto;
    public $Monto;
    public $Porcentaje;
    public $ConceptoDif;
    
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdProyecto = 0;
        $this->Concepto= 0;
        $this->Monto = '';
        $this->Porcentaje = '';
        $this->ConceptoDif = '';
    }

    public function insert($data)
    {
        
        $this->db->insert_batch('spend_proyectodet', $data); 
    
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
    
        return false;
    }

    
    public function delete()
    {
        
        $this->db->where('IdProyecto', $this->IdProyecto);
        if($this->IdProyecto>0){
            $this->db->delete('spend_proyectodet');    
        }
        

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('spend_proyectodet');
        $this->db->where('IdProyecto', $this->IdProyecto);
        if($this->Concepto!=''){
            $this->db->where('Concepto',$this->Concepto);    
        }
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
    
    public function get_montoplan()
    {
        $this->db->select('sum(Monto) as Monto');
        $this->db->from('spend_proyectodet');
        $this->db->where('IdProyecto', $this->IdProyecto);
        if($this->Concepto!=''){
            $this->db->where('Concepto',$this->Concepto);    
        }
        
        if($this->ConceptoDif!=''){
            $this->db->where('Concepto !=',$this->ConceptoDif);    
        }
        
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            $data = $response->row();

            return $data->Monto;
        } else {
            return 0;
        }
    }

    public function get_list()
    {
        $this->db->select('*');
        $this->db->from('spend_proyectodet');
        
        $this->db->where('IdProyecto =', $this->IdProyecto);
        // Filters
        if (!empty($this->Concepto)) {
            $this->db->like('Concepto', $this->Concepto);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

  
 
  
}