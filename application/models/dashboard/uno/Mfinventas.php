<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mfinventas extends BaseModel
{
    // Properties
    public $IdProyectVent;
    public $IdVendedor;
    public $IdTipoServ;
    public $UnoT;
    public $DosT;
    public $TresT;
    public $CuatroT;
    public $Comision;
    public $BaseContrato;
    public $ValorPromedio;
    public $PorcentajeB;
    public $Anio;
    public $IdSucursal;

    public $UnoP;
    public $DosP;
    public $TresP;
    public $CuatroP;
    public $TotalAnual;

    public $TotalUno;
    public $TotalDos;
    public $TotalTres;
    public $TotalCuatro;




    public function __construct()
    {
        parent::__construct();
        // Init Properties
        $this->IdProyectVent = 0;
        $this->IdVendedor = 0;
        $this->IdTipoServ = 0;
        $this->IdSucursal = 0;
        $this->UnoT = 0;
        $this->DosT = 0;
        $this->TresT = 0;
        $this->CuatroT = 0;
        $this->Comision = 0;
        $this->BaseContrato = 0;
        $this->ValorPromedio = 0;
        $this->PorcentajeB = 0;
        $this->Anio = '';
        $this->UnoP = 0;
        $this->DosP = 0;
        $this->TresP = 0;
        $this->CuatroP = 0;
        $this->TotalAnual = 0;
    }

    public function get_recovery_finventasporcentajeope()
    {

        $this->db->select('sum(fv.UnoT) as TotalUno,sum(fv.DosT) as TotalDos,sum(fv.TresT) as TotalTres,sum(fv.CuatroT) as TotalCuatro');
        $this->db->from('finventas fv');
        $this->db->join('trabajador t', 'fv.IdVendedor=t.IdTrabajador', 'inner');

        $where = 't.RegEstatus="A" and (t.Perfil="VENDEDOR" or t.Perfil="Gerente de Ventas")';
        $this->db->where($where);


        $element = 'fv.Anio=\'' . $this->Anio . '\'';
        $this->db->where($element);

        $this->db->where('fv.IdSucursal', $this->IdSucursal);


        if ($this->IdTipoServ != '') {

            $element1 = 'fv.IdConfigS=' . $this->IdTipoServ . '';
            $this->db->where($element1);
        }

        if ($this->IdVendedor != '') {

            $element2 = 't.IdTrabajador=' . $this->IdVendedor . '';
            $this->db->where($element2);
        }

        #echo $result = $this->db->get_compiled_select();

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
                'data' => new Mfinventas()
            ];
        }
    }
}
