<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mestadofupdate extends BaseModel
{
    // Properties
    public $IdSucursal;
    public $Anio;
    public $Descripcion;
    public $Mes;
    public $Mes2;

    public $Monto;
    public $IdConfigServ;
    public $IdTipoServ;
      

    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdSucursal = 0;
        $this->Anio = '';
        $this->Descripcion = '';
        $this->Mes = '';
        $this->Monto = '';
        $this->IdConfigServ = '';
        $this->IdTipoServ = '';
      
    }

    public function get_recobery_estadofupdate()
    {
        $this->db->select('*');
        $this->db->from('estadofupdate');

        if(!empty($this->IdSucursal)){
            $this->db->where('IdSucursal', $this->IdSucursal);
        }
        if(!empty($this->IdConfigServ)){
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if($this->IdTipoServ){
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        else
        {
            $this->db->where('IdTipoServ!=0');
        }
        if(!empty($this->Mes)){
            $this->db->where('Mes', $this->Mes);
        }
        if(!empty($this->Descripcion)){
            $this->db->where('Descripcion', $this->Descripcion);
        }
        if(!empty($this->Anio)){
            $this->db->where('Anio', $this->Anio);
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
                'status' => false,
                'data' => new Mestadofupdate()
            ];
        }   
    }
     
    public function get_list_estadofupdate()
    {
        $this->db->select('*');
        $this->db->from('estadofupdate');
        $this->db->where('IdSucursal', $this->IdSucursal);
       
        if (!empty($this->Descripcion)){
            $this->db->where('Descripcion', $this->Descripcion);
        }
        if (!empty($this->Anio)){
            $this->db->where('Anio='.$this->Anio);
        }
        if (!empty($this->Mes)){
            $and= 'Mes between ' . $this->Mes . ' and ' . $this->Mes2;
            $this->db->where($and);
        }
        if ($this->IdConfigServ != ''){
            $this->db->where('IdConfigServ', $this->IdConfigServ);
        }
        if ($this->IdTipoServ != ''){
            $this->db->where('IdTipoServ', $this->IdTipoServ);
        }
        else
        {
            $this->db->where('IdTipoServ !=0');
        }

        #echo $this->db->get_compiled_select();
        //Pagination
        $this->set_pagination();
        $response = $this->db->get();
        return $response->result();
    }
}
?>