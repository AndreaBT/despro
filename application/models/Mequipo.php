<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mequipo extends BaseModel
{
    // Properties
    public $IdEquipo;
    public $IdCliente;
    public $Ubicacion;
    public $Marca;
    public $Modelo;
    public $Serie;
    public $TipoUnidad;
    public $Ano;
    public $IdSucursal;
    public $RegEstatus;
    public $IdClienteS;
    public $Nequipo;
    public $Status;
    public $FechaMod;
    public $Folio='';
    public $Cliente='';


    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdEquipo = 0;
        $this->IdCliente= 0;
        $this->Ubicacion= '';
        $this->Marca= '';
        $this->Modelo = '';
        $this->Serie = '';
        $this->TippoUnidad = '';
        $this->Ano= '';
        $this->IdSucursal = '';
        $this->RegEstatus = '';
        $this->IdClienteS=0;
        $this->Estatus= '';
        $this->Nequipo= '';
        $this->Status = '';
        $this->FechaMod= '';

    }

    public function insert()
    {

        $this->db->set('Ubicacion', $this->Ubicacion);
        $this->db->set('Marca', $this->Marca);
        $this->db->set('Modelo', $this->Modelo);
        $this->db->set('Serie', $this->Serie);
        $this->db->set('TipoUnidad', $this->TipoUnidad);
        $this->db->set('Ano', $this->Ano);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('RegEstatus', 'A');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('IdClienteS', $this->IdClienteS);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('Estatus', '');
        $this->db->set('Nequipo', $this->Nequipo);
        $this->db->set('Status', 'No Revisado');

        $this->db->insert('equipos');
        return $this->db->insert_id();
    }

    public function update()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->set('Ubicacion', $this->Ubicacion);
        $this->db->set('Marca', $this->Marca);
        $this->db->set('Modelo', $this->Modelo);
        $this->db->set('Serie', $this->Serie);
        $this->db->set('TipoUnidad', $this->TipoUnidad);
        $this->db->set('Ano', $this->Ano);
        $this->db->set('Nequipo', $this->Nequipo);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('equipos');
        //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

       public function update_statusequipo()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);
        $this->db->set('Status', $this->Status);
        $this->db->set('FechaMod', $this->FechaMod);

        $this->db->update('equipos');

        return true;

    }

    public function delete()
    {
        $this->db->where('IdEquipo', $this->IdEquipo);

        $this->db->set('RegEstatus', 'B');
        $this->db->set('FechaMod',$this->FechaMod);
        $this->db->update('equipos');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_equipos()
    {
        $this->db->select('*');
        $this->db->from('equipos');
        $this->db->where('IdEquipo', $this->IdEquipo);

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
        $this->db->select('e.*,tu.Nombre as Unidad,ie.Imagen');
        $this->db->from('equipos e');
        $this->db->join('tipounidad tu','tu.IdTipoU=e.TipoUnidad','inner');
        $this->db->join('iconos_eq  ie','ie.IdIconoEq =tu.IdIconoEq','inner');
        $this->db->where('e.IdSucursal', $this->IdSucursal);

        // Filters
        if (!empty($this->IdClienteS)) {
            $this->db->where('e.IdClienteS', $this->IdClienteS);
        }

        if (!empty($this->Nequipo)) {
            $this->db->like('e.Nequipo', $this->Nequipo);
        }


        if (!empty($this->RegEstatus)) {
            $this->db->where('e.RegEstatus', $this->RegEstatus);
        }


        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    #MONITOREO HISTORIAL DE SERVICIOS

    public function get_list_historial()
    {
        $this->db->select('s.Folio,s.Cliente,s.IdServicio,DATE_FORMAT(s.Fecha_I, "%d-%m-%Y") as Fecha_I,DATE_FORMAT(s.Fecha_F, "%d-%m-%Y") as Fecha_F , s.Observaciones,s.ComentarioFin, s.Materiales, s.Direccion,ts.Concepto as Servicio,cli.Nombre as NomCli,de.Comentario2,de.Comentario,de.IdEquipo');
        $this->db->from('servicio s');
        $this->db->join('equipocomentario de','de.IdServicio =s.IdServicio','inner');//se modifico antes era detalleequipo
        $this->db->join('equipos e','e.IdEquipo=de.IdEquipo','inner');
        $this->db->join('tiposervicio ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->join('clientes cli','s.IdCliente=cli.IdCliente','inner');
        $this->db->join('clientesucursal cs','s.IdClienteS=cs.IdClienteS','inner');
        $this->db->where('s.EstadoS ', 'CERRADA');
        $this->db->where('s.RegEstatus', 'A');
        $this->db->where('de.IdEquipo', $this->IdEquipo);

        // Filters
        if (!empty($this->Folio)) {
            $where =' (s.folio like \'%'.$this->Folio.'%\'   or cli.Nombre like  \'%'.$this->Folio.'%\' or cs.Nombre like \'%'.$this->Folio.'%\')';
            $this->db->where($where);
        }


        $this->db->order_by('s.Fecha_F ','DESC');

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

       public function get_list_historialapp()
    {
        $this->db->select('s.IdServicio, ds.FechaInicio as Fecha_I, ts.Concepto as Servicio, i.Imagen2,s.Folio,s.Cliente,s.Comentario,s.ComentarioFin');
        $this->db->from('servicio s');
        $this->db->join('fechaservicio ds','s.IdServicio=ds.IdServicio','inner');
        $this->db->join('tiposervicio  ts','ts.IdTipoSer=s.Tipo_Serv','inner');
        $this->db->join('iconos i','ts.IdIcono=i.IdIcono','inner');
        $this->db->join('detalleequipo deteq','deteq.IdServicio = s.IdServicio','inner');
        $this->db->where('deteq.IdEquipo', $this->IdEquipo);
        $this->db->where('s.RegEstatus', "A");


        $this->db->group_by('ds.FechaInicio ','DESC');

        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }



}
