<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mconfigporcensubtipof extends BaseModel
{
    public $IdConfigS;
    public $IdTipoS;
    public $IdVendedor;
    public $Porcentaje;
    public $Anio;
 
  
    
    public function __construct()
    {
        $this->IdConfigS = 0;
        $this->IdTipoS = 0;
        $this->IdVendedor = 0;
        $this->Porcentaje = '';
        $this->Anio = '';
        
        
    }
    public function set_insert_configporcensubtipo()
    {
       /*  $con = new connection();
        $sql = 'INSERT INTO configporcensubtipo (IdConfigS,IdTipoS,IdVendedor,Porcentaje,Anio)values('.$this->IdConfigS.','.$this->IdTipoS.',\''.$this->IdVendedor.'\',\''.$this->Porcentaje.'\',\''.$this->Anio.'\')';
        $this->IdTipoSer= $con->execute_order($sql); */
    }
    
    public function set_delete_configporcensubtipo()
    {
        /* $con = new connection();
        $sql = 'delete from configporcensubtipo  WHERE IdVendedor='.$this->IdVendedor.' and Anio=\''.$this->Anio.'\'';
        $con->execute_order($sql); */
    } 
    
   
    public function get_recobery_configporcensubtipo()
    {
	   
        $this->db->select('*');
        $this->db->from('configporcensubtipo');
        //$this->db->where('IdSucursal', $this->IdSucursal);

        if($this->IdConfigS!=''){
            $this->db->where('IdConfigS',$this->IdConfigS);
        }
        if($this->IdVendedor!=''){
            $this->db->where('IdVendedor',$this->IdVendedor);
        }
        
        if($this->IdTipoS!=''){
            $this->db->where('IdTipoS',$this->IdTipoS);
        }
        
        if($this->Anio!=''){
            $this->db->where('Anio',$this->Anio);
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
                 'data' => new Mconfigporcensubtipof()
            ];
        }
    }
  
  public function get_list_configporcensubtipo()
  {
	  /* $arr = array();
	  $con = new connection();
	  $sql = 'SELECT * FROM configporcensubtipo ';
      $bnd=false;
      $and='';
      $limit='';
      $bnddel=false;
      
      if($this->IdConfigS!='')
      {
        $and.=' IdConfigS like\'%'.$this->IdConfigS.'%\' and';
        $bnd=true;
      }

      if($this->IdTipoS!='')
      {
        $and.=' IdTipoS=\''.$this->IdTipoS.'\' and';
        $bnd=true;
      }
      if($this->IdVendedor!='')
      {
        $and.=' IdVendedor=\''.$this->IdVendedor.'\' and';
        $bnd=true;
      }

      if($this->nIni >= 0 && $this->nTam >0)
	  {
		  $limit.=' limit '.$this->nIni.','.$this->nTam;
                
      }
      
      if($bnd==true)
      {
        $sql.=' where '. substr($and,0,-3).$limit;
      }
      else
      {
        $sql.=$limit;
      }
   
	  $rel = $con->execute_order($sql);
	  
	  while($row = $rel->fetch_assoc())
      {
            $reg = array();
            
            $reg['IdConfigS'] = $row['IdConfigS'];
            $reg['IdVendedor'] = $row['IdVendedor'];
            $reg['IdTipoS'] = $row['IdTipoS'];
            $reg['Porcentaje'] = $row['Porcentaje'];
            $reg['Anio'] = $row['Anio'];  
            
        array_push($arr,$reg);
      }
	  return $arr; */
  }
  

  
}
?>