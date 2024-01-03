<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mimagencajachica extends BaseModel
{
    // Properties
    public $IdConcepto;
    public $Imagen;
    public $IdTrabajador;
    public $Actualizado;
   
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdConcepto= 0;
        $this->Imagen = '';
        $this->IdTrabajador = '';
        $this->Actualizado = '';
    }

    public function insert()
    {
    
        $this->db->set('IdConcepto', $this->IdConcepto);  
        $this->db->set('Imagen2', $this->Imagen2);
        $this->db->set('IdTrabajador', $this->IdTrabajador);
        $this->db->set('Actualizado', 's');
        $this->db->insert('imagencajachica');
        return $this->db->insert_id();
    }  
    
     public function update()
    {
    
        $this->db->where('IdConcepto', $this->IdConcepto);  
        $this->db->set('Actualizado', 's');
        $this->db->update('imagencajachica');
        return true;
    }    

    public function get_list($tipo=1)
    {
        if ($tipo==1){
        $this->db->select('*');
        }
        else
        {
             $this->db->select('IdConcepto,Imagen2,IdTrabajador');  
        }
        $this->db->from('imagencajachica');
        $this->db->where('IdConcepto', $this->IdConcepto);
        
        if ($this->Actualizado!='')
        {
         
           $this->db->where('Actualizado =',null );  
        }
        # echo $result = $this->db->get_compiled_select();
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
    
  
    
}