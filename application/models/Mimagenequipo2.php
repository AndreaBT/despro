<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mimagenequipo2 extends BaseModel
{
    // Properties
    public $IdEquipo;
    public $Fecha;
    public $Descripcion;
    public $IdServicio;
    public $Imagen;
    public $Contador;
    public $Mostrar;
    public $Descripcion2;
    public $Posicion;
    public $Validar;
    public $Tipo;


    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdEquipo = 0;
        $this->Fecha = 0;
        $this->Descripcion = '';
        $this->IdServicio = '';
        $this->Imagen = '';
        $this->Contador = '';
        $this->Mostrar = '';
        $this->Descripcion2 = '';
        $this->Posicion = 0;
        $this->Tipo = 0;

    }
    
       public function insert()
    {
        $this->db->set('IdEquipo', $this->IdEquipo);
        $this->db->set('Imagen', $this->Imagen);
        $this->db->set('Fecha', $this->Fecha);
        $this->db->set('Descripcion', $this->Descripcion);
        $this->db->set('IdServicio', $this->IdServicio);
        $this->db->set('Mostrar', $this->Mostrar);
        $this->db->set('Contador', $this->Contador);   
        $this->db->set('Descripcion2', '');    
        $this->db->set('Posicion', 0);
        $this->db->set('Tipo', 1); 
        $this->db->insert('imagenequipo2');   
        return true;       
    }
    
      public function update()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->where('Contador', $this->Contador);
        $this->db->set('Descripcion2', $this->Descripcion2);
        $this->db->set('Mostrar', $this->Mostrar);
        $this->db->set('Posicion', $this->Posicion); 
        $this->db->update('imagenequipo2');   
        return true;       
    }
    
      public function updateImages()
    {
        $this->db->where('IdServicio', $this->IdServicio);
        $this->db->where('Contador', $this->Contador);
        $this->db->set('Mostrar', $this->Mostrar); 
        $this->db->set('Descripcion2', $this->Descripcion2); 
        $this->db->set('Posicion', $this->Posicion);   
        $this->db->update('imagenequipo2');   
        return true;       
    }

    public function get_list()
    {
        $this->db->select('ie.*,e.Nequipo as Equipo,e.Status');
        $this->db->from('imagenequipo2 ie');
        $this->db->join('equipos e','e.IdEquipo=ie.IdEquipo','left');
        
        $this->db->where('ie.IdServicio', $this->IdServicio);

       
            if (!empty($this->IdEquipo)) {
                $this->db->where('ie.IdEquipo', $this->IdEquipo);
            }
          

        if (!empty($this->Mostrar)) {
            $this->db->where('ie.Mostrar', $this->Mostrar);
        }

        //Pagination
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_listImgEquip()
    {
        $this->db->select('ie.*,e.Nequipo as Equipo,e.Status');
        $this->db->from('imagenequipo2 ie');
        $this->db->join('equipos e','e.IdEquipo=ie.IdEquipo','join');
        
        $this->db->where('ie.IdServicio', $this->IdServicio);

       
            if (!empty($this->IdEquipo)) {
                $this->db->where('ie.IdEquipo', $this->IdEquipo);
            }
          

        if (!empty($this->Mostrar)) {
            $this->db->where('ie.Mostrar', $this->Mostrar);
        }

        //Pagination
        $this->set_pagination();
        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

    public function get_list2()
    {
        $this->db->select('ie.*');
        $this->db->from('imagenequipo2 ie');
        
        $this->db->where('ie.IdServicio', $this->IdServicio);
        $this->db->where('ie.IdEquipo',"0");
        if (!empty($this->Mostrar)) {
            $this->db->where('ie.Mostrar', $this->Mostrar);
        }

        //Pagination
        $this->set_pagination();
       # echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

   
    
      public function get_listImgServicios()
    {
        $this->db->select('*');
        $this->db->from('imagenequipo2 ie');
        $this->db->where('ie.IdServicio', $this->IdServicio);
        
        if ($this->Validar=='Igual')
        {
            $this->db->where('ie.IdEquipo =', 0);    
        }
        else if ($this->Validar=='Mayor')
        {
            $this->db->where('ie.IdEquipo >', 0); 
        }
        //Pagination
        $this->set_pagination();
        //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        return $response->result();
    }

   
}
