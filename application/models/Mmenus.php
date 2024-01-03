<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmenus extends BaseModel
{
    // Properties
    public $IdPaquete;
    public $IdSucursal;
    public $Nombre;
    public $Tipo;
    public $Asociado;
    public $Clave;
    public $ReqPermiso;
    public $Orden;
    public $RegEstatus;
    
    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdPaquete = 0;
        $this->IdSucursal = 0;
        $this->Nombre= '';
        $this->Tipo= '';
        $this->Asociado= '';
        $this->Clave= '';
        $this->ReqPermiso= '';
        $this->Orden = '';
        $this->RegEstatus='';
    }

    public function get_list()
    {
        $this->db->select('p.*, pp.IdPaquete as Tiene');
        $this->db->from('paquetexsucursal ps');
        $this->db->join('paquete p','ps.IdPaquete=p.IdPaquete','inner');
        $this->db->join('permisoxpaquete pp','ps.IdPaquete = pp.IdPaquete','left');

        
        $this->db->where('ps.IdSucursal', $this->IdSucursal);

        if (!empty($this->Nombre)) {
            $where=' ( p.clave like \'%'.$this->Nombre.'%\' or p.Nombre like \'%'.$this->Nombre.'%\' )';
            $this->db->where($where);
        }
        if (!empty($this->Tipo)) {
            $this->db->where('p.Tipo', $this->Tipo);
        }
        if (!empty($this->RegEstatus)) {
            $this->db->where('p.RegEstatus', $this->RegEstatus);
        }
        if (!empty($this->Asociado)) {
            $this->db->where('p.Asociado', $this->Asociado);
        }

        $this->db->order_by('p.Orden');
        $this->db->group_by('p.IdPaquete');

        
        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    /*
    $this->db->from('paquetexsucursal');
    $this->db->where('IdPaquete', $this->IdPaquete);
    $this->db->where('IdSucursal', $this->IdSucursal);
    */

    public function get_recovery()
    {
        $this->db->select('*');
        $this->db->from('paquete');
 
        if($this->IdMenu!=''){
            $this->db->where('IdPaquete', $this->IdPaquete);
        }
        
        if($this->RegEstatus!=''){
            $this->db->where('RegEstatus', $this->RegEstatus);
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
                'status' => false
            ];
        }
    }

}