<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpdfequipo extends BaseModel
{
    // Properties
    public $IdPdf;
    public $IdEquipo;
    public $Titulo;
    public $NombreArchivo;
    public $FechaAlta;
    public $RegEstatus;
    public $FechaMod;
  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPdf = 0;
        $this->IdEquipo= 0;
        $this->Titulo= '';
        $this->NombreArchivo= '';
        $this->FechaAlta = '';
        $this->RegEstatus = '';
        $this->FechaMod = '';
       
    }

    public function insert()
    {

        $this->db->set('IdEquipo', $this->IdEquipo);
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('NombreArchivo', $this->NombreArchivo);   
        $this->db->set('FechaAlta', $this->FechaAlta);
        $this->db->set('RegEstatus', 'A');
        $this->db->set('FechaMod',$this->FechaMod);      

        $this->db->insert('pdfequipo');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdPdf', $this->IdPdf);
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('NombreArchivo', $this->NombreArchivo);  
        $this->db->set('FechaMod',$this->FechaMod); 
        
        $this->db->update('pdfequipo');
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdPdf', $this->IdPdf);
        
        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod',$this->FechaMod);
        
        $this->db->update('pdfequipo');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('pdfequipo');
        $this->db->where('IdPdf', $this->IdPdf);

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
        $this->db->select('*');
        $this->db->from('pdfequipo');
        $this->db->where('IdEquipo', $this->IdEquipo);

        // Filters
        if (!empty($this->Titulo)) {
            $this->db->like('Titulo', $this->Titulo);
        }

        if (!empty($this->NombreArchivo)) {
            $this->db->where('NombreArchivo', $this->NombreArchivo);
        }
        
        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

}