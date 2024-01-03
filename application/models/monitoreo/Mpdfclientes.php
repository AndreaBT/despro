<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpdfclientes extends BaseModel
{
    // Properties
    public $IdPdf;
    public $IdCliente;
    public $Titulo;
    public $Tipo;
    public $FechaAlta;
    public $IdClienteS;
    public $FechaMod;
    public $RegEstatus;
    public $NombreArchivo;
    public $FileServer;

  
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPdf = 0;
        $this->IdCliente= 0;
        $this->Titulo= '';
        $this->Tipo= '';
        $this->FechaAlta = '';
        $this->IdClienteS = '';
        $this->FechaMod = '';
        $this->RegEstatus = '';
        $this->NombreArchivo = '';
        $this->FileServer = 0;

    }

    public function insert()
    {

        $this->db->set('IdPdf', $this->IdPdf);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('Titulo', $this->Titulo);
        $this->db->set('NombreArchivo', $this->NombreArchivo);      
        $this->db->set('Tipo', $this->Tipo);
        $this->db->set('FechaAlta', $this->FechaAlta);
        //$this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('RegEstatus', $this->RegEstatus);
        $this->db->set('FileServer', 1);


        $this->db->insert('pdfclientes');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdPdf', $this->IdPdf);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('Titulo', $this->Titulo);   
        $this->db->set('Tipo', $this->Tipo);
        $this->db->set('FechaAlta', $this->FechaAlta);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->update('pdfclientes');
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
        //$this->db->set('FechaMod',$this->FechaMod);
        $this->db->update('pdfclientes');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('pdfclientes');
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
        $this->db->select('*, DATE_FORMAT(FechaAlta, "%d-%m-%Y") AS FechaAlta');
        $this->db->from('pdfclientes');
        $this->db->where('IdCliente', $this->IdCliente);
        if (!empty($this->Titulo)) {
            $this->db->like('Titulo', $this->Titulo);
        }

        
        if (!empty($this->RegEstatus)) {
            $this->db->where('RegEstatus', $this->RegEstatus);
        }

        if (!empty($this->IdClienteS)) {
            $this->db->where('IdClienteS', $this->IdClienteS);
        }
        
        if (!empty($this->Tipo)) {
            $this->db->where('Tipo', $this->Tipo);
        }
		#echo $result = $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }
  
}