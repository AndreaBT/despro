<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;
use SebastianBergmann\Environment\Console;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cgraficas extends REST_Controller
{
    public $RutaPdf;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('finanzas/Mconceptooperacion');
        $this->load->model('finanzas/Mporcentajeoperacion');
        $this->load->model('finanzas/Mestadofinanciero');
        $this->load->model('Mconfigservicio');
        $this->load->model('Mtiposervicio');
        $this->load->model('Mservicio');
        $this->load->model('Mrol');
        $this->load->library('UploadFile');
        $this->load->library('EstadoFinanciero');
        $this->load->model('estadosf/Mplanfactura');
        $this->load->model('estadosf/Mserviciosf');
        $this->load->model('estadosf/Mestadosfinancieros');
        $this->load->model('estadosf/Mdetalleestado');
        $this->load->model('estadosf/Mcostoga');
        $this->load->model('estadosf/Mactualcostoga');
        $this->load->model('estadosf/Mgastosdirectos');
        $this->load->model('estadosf/Mactualventas');
        $this->load->model('estadosf/Mcostodeptoventas');
        $this->load->model('estadosf/Mactualoperaciones');
        $this->load->model('estadosf/Mestadofupdate');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mcostofinanciero');
        $this->load->model('estadosf/Mactualcostof');
        $this->load->model('estadosf/Mporcentajeoperacion');
        $this->load->model('estadosf/Mcostovehope');
        $this->load->model('estadosf/Mactualcostove');
         
         
        setTimeZone($this->verification,$this->input);
    }
    

    public function getplanvsact_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $IdConfigS =$this->get('IdConfigS');
        $IdSubtipoServ =$this->get('IdTipoServ');
        $Anio =$this->get('Anio');
       
        $Type=$this->get('Tipo');
        if (empty($Type))
        {
          $Type=1;  
        }
        
        //$Meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $Meses=array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sept","Oct","Nov","Dic");
        $Planes=array();
        $Real=array();
        $Lismes=array();
        
        for ($i=1 ; $i < count($Meses) +1 ; $i++)
        {
            $oEstadoFinanciero= new EstadoFinanciero();
            $row= $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ,$i,"","","",$Type,$IdEmpresa);
    
            if (count($row['resultados']['row'])>0)
            {
                $data1['value'] =$row['resultados']['row'][0]->PlanMes;
                $data2['value'] =$row['resultados']['row'][0]->ActualMes;
                $data3['label'] =$Meses[$i-1];
                array_push($Planes,$data1);
                array_push($Real,$data2);
                array_push($Lismes,$data3);
            }
        }
       
        $data['Plan'] =$Planes;
        $data['Real'] =$Real;
        $data['Meses'] =$Lismes;
   
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function getfacact_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $IdConfigS =$this->get('IdConfigS');
        $IdSubtipoServ =$this->get('IdTipoServ');
        $Anio =$this->get('Anio');
        $Mes =$this->get('Mes');
       
         $Type=$this->get('Tipo');
        if (empty($Type))
        {
          $Type=1;  
        }
        if (empty($Mes))
        {
            $Mes=date('m');
        }

      
        $Planes=array();
        $Servicios=array("Mantenimiento","Servicios","Proyecto");
        $Colores=array("#2b908f","#90ee7e","#f45b5b");
        
        for ($i=1 ;$i<count ($Servicios)+1;$i++)
        {   
            $oEstadoFinanciero= new EstadoFinanciero();
            $row= $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal,$Anio,$i,$IdSubtipoServ,$Mes,"","","",$Type,$IdEmpresa);
            
            if (count($row['resultados']['row'])>0)
            {
               
                $data2['label'] =$Servicios[$i-1];
                $data2['value'] =$row['resultados']['row'][0]->ActualAnio;
                $data2['color'] = $Colores[$i-1];
                array_push($Planes,$data2);
            }
        }
       
        $data['Plan'] =$Planes;
      
   
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function getGrossProf_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $IdConfigS =$this->get('IdConfigS');
        $IdSubtipoServ =$this->get('IdTipoServ');
        $Anio =$this->get('Anio');
        $Mes =$this->get('Mes');
       
        $Type=$this->get('Tipo');
        if (empty($Type))
        {
          $Type=1;  
        }
        if (empty($Mes))
        {
            $Mes=date('m');
        }

        $Mantenimiento=array();
        $Services=array();
        $Proyectos=array();
        $Servicios=array("Mantenimiento","Servicios","Proyecto");
       
        $cont=0;
        for ($i=1 ;$i<count ($Servicios)+1;$i++)
        {
            $oEstadoFinanciero= new EstadoFinanciero();
            $row= $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal,$Anio,$i,$IdSubtipoServ,$Mes,"","","",$Type,$IdEmpresa);
            
            if (count($row['resultados']['row'])>0)
            {
                $valor=$row['resultados']['DiesGp'];
                if ($valor <0)
                {
                    $valor=0;
                }
                $valor2= 100 - $valor;

                if ($i==1)
                {
                    $data2['label'] =$Servicios[$cont] ;
                    $data2['value'] =$valor;
                    $data2['color'] ='#2b908f';
                    array_push($Mantenimiento,$data2);
                    $data3['label'] ="";
                    $data3['value'] =$valor2;
                    $data3['color'] ='#90ee7e';
                    array_push($Mantenimiento,$data3);
                }
                if ($i==2)
                {
                    $data2['label'] =$Servicios[$cont];
                    $data2['value'] =$valor;
                    $data2['color'] ='#f45b5b';
                    array_push($Services,$data2);
                    $data3['label'] ="";
                    $data3['value'] =$valor2;
                    $data3['color'] ='#90ee7e';
                    array_push($Services,$data3);
                }
                if ($i==3)
                {
                    $data2['label'] =$Servicios[$cont];
                    $data2['value'] =$valor;
                    $data2['color'] ='#7798BF';
                    array_push($Proyectos,$data2);
                    $data3['label'] ="";
                    $data3['value'] =$valor2;
                    $data3['color'] ='#90ee7e';
                    array_push($Proyectos,$data3);
                }
            }

            $cont ++;
        }
       
        $data['Mantenimiento'] =$Mantenimiento;
        $data['Servicios'] =$Services;
        $data['Proyectos'] =$Proyectos;
   
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function factxtiposerv_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $Anio =$this->get('Anio');
        $Mes =$this->get('Mes');
    
        $Type=$this->get('Tipo');
        if (empty($Type))
        {
            $Type=1;  
        }
        if (empty($Mes))
        {
            $Mes=date('m');
        }
  
        $Planes=array();
        $Servicios=array("Mantenimiento","Servicios","Proyecto");
   
        for ($i=1 ;$i<count ($Servicios)+1;$i++)
        {
            $oEstadoFinanciero= new EstadoFinanciero();
            $row= $oEstadoFinanciero->GetEstadoFinanciero($IdSucursal,$Anio,$i,'',$Mes,"","","",$Type,$IdEmpresa);
        
            if (count($row['resultados']['row'])>0)
            {
                $data2['label'] =$Servicios[$i-1];
                $data2['value'] =$row['resultados']['row'][0]->ActualMes;
                array_push($Planes,$data2);
            }
        }
   
        return $this->set_response([
            'status' => true,
            'data' => $Planes,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }


    public function horasfact_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        /*
        $month_end = strtotime('last day of February', time());
        echo date('Y-m-d', $month_end);*/

        $oMrol= new Mrol();
        $oMrol->Nombre='Usuario APP';
        $oMrol->IdSucursal=$IdSucursal;
        $orol=$oMrol->get_recovery();

        $oMtrabajador=new Mtrabajador();
        $oMtrabajador->IdSucursal=$IdSucursal;
        $oMtrabajador->RegEstatus='A';
        $oMtrabajador->IdRol=$orol['data']->IdRol;
        $oMtrabajador->IdPerfil=4;
        $rowtrabajador=$oMtrabajador->get_list();


        $Anio =$this->get('Anio');
        $Mes =$this->get('Mes');

        if (empty($Mes))
        {
            $Mes=date('m');
        }
        if ($Mes<10)
        {
            $Mes="0".$Mes;
        }

        $AnioCompleto=$Anio.'-'.$Mes.'-01';
        $AnioCompleto2=$Anio.'-'.'12'.'-28';

        $oMservicio = new Mservicio();
        $oMservicio->IdSucursal=$IdSucursal;
        $oMservicio->Fecha_I=$AnioCompleto;
        $oMservicio->Fecha_F=$AnioCompleto2;
        $oMservicio->EstadoS='REALIZADA';
        $row= $oMservicio->get_list_ServxHoras();
        //$this->response($row);
    
        $Facturadas=0;
        $NoFacturadas=0;
    
        foreach ($row as $element)
        {
            if($element->Ingresos=='s'){//Facturables
                    $Facturadas+=$element->Value;   
            }else{
                    $NoFacturadas += $element->Value;
            }
        }

        $HorasFacXPersona =0;
        if (count($rowtrabajador)>0)
        {
            $HorasFacXPersona = $Facturadas / count($rowtrabajador);
        }
        else
        {
            $HorasFacXPersona=0;    
        }
    
        $data['Facturadas']=number_format($Facturadas, 1, '.', '');;
        $data['NoFacturadas']= number_format($NoFacturadas, 1, '.', '');
        $data['FacXPersona']=number_format($HorasFacXPersona, 1, '.', '');

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function porcentajecostos_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

      
        $Anio =$this->get('Anio');
        $Mes =$this->get('Mes');
        $Type=2;  

        if (empty($Mes))
        {
            $Mes=date('m');
        }

      
        $Planes=array();
        $Servicios=array("Mantenimiento","Servicios","Proyecto");
       
        $oEstadoFinanciero= new EstadoFinanciero();
        $row= $oEstadoFinanciero->GetEstadoFinancieroTodos($IdSucursal,$Anio,$Mes,"","","",$Type,$IdEmpresa);
      
         $data['DiesTGA']=$row['resultados']['DiesTGA'];
         $data ['DiesTDV']=$row['resultados']['DiesTDV'];
         $data ['GrossP']=$row['resultados']['DiesGp'];
       
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    ////////////////////nuevo para operation proffit gráfica 

    public function graficaopf_get()
    {
        // Valid Token
        // if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
           // return $this->set_unauthorized_response();
        //}
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


        $this->response($IdSucursal);
        $palbra="hola";
        var_dump($palbra);
        /*$Anio=2021;
        $Mes=8;
        $value2= $this->getDataTodos($Anio,$Mes);
        var_dump ($value2);

        //este sí sirve para las gráficas
        $data['rowconfig'] =  $value['config'];*/

        //$arrayMeses= array(1,2,3,4,5,6,7,8,9,10,11,12);

        //foreach ($arrayMeses as $respuesta){
            //$respuesta= $value2;
        // }
        
        // echo '<pre>';
        // print_r($value);
        // echo '</pre>';

       return $this->set_response([
           'status' => true,
           'data' => '',
           'message' => 'Success',
       ], REST_Controller::HTTP_OK);
    }


    //CONVERTIR EN FUNCIÓN
    public function getDataTodos($anio,$mes)
    {
        // Valid Token
        //if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
           // return $this->set_unauthorized_response();
        //}

        $arraydatos = array();
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $this->response($IdSucursal);
        $Anio =$anio;
        $AnioActual = $anio;
        $Mes = $mes;
        $IdCliente = 0;
        $IdClienteS = "";
        $IdContrato =0;


        //Insertar el plan
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();
        $arrayplanfact = array("Facturacion", "Materiales", "Equipos", "Mano de Obra", "Vehiculos", "Contratistas", "Viaticos", "Burden");

        if (count($rowPlanFactura) == 0)
        {
            foreach ($arrayplanfact as $Descripcion) {
                $oplanfactura = new Mplanfactura();
                $oplanfactura->IdSucursal = $IdSucursal;
                $oplanfactura->Descripcion = $Descripcion;
                $oplanfactura->set_insert_planfactura();
            }
        }

        //aqui no
        $oplanfactura = new Mplanfactura();
        $oplanfactura->IdSucursal = $IdSucursal;
        $rowPlanFactura = $oplanfactura->get_list_planfactura();

        //print_r($rowPlanFactura);
        //************CALCULOS***********
        //************CALCULOS*******
        //************CALCULOS************

        $Trimestre = 0;
        if ($Mes < 3) {
            $Trimestre = 1;
        } else if ($Mes < 6) {
            $Trimestre = 2;
        } else if ($Mes < 9) {
            $Trimestre = 3;
        } else {
            $Trimestre = 4;
        }

        $MesActual = date("n");

        //Total de enero hasta el mes actual del anio
        //Este es el mes seleccionado del combo
        $Mes = $Mes + 1;

        if ($Mes < 10) {
            $Mes = '0' . $Mes;
        }

        //Traer total de servicios de enero hasta el mes actual vehiculo mano de obra y burden toal.
        //Aqui se debe hacer un bettwen

        $BurdenTotalAnioActual = 0;
        $ManoObraTAnioActual = 0;
        $CostoVAnioActual = 0;

        $EquiposDAno = 0;
        $MaterialesDAnio = 0;
        $ViaticosDAnio = 0;
        $ContratistasDAnio = 0;

        for ($i = 1; $i < 6; $i++)
        {
            $oservicio = new Mserviciosf();
            $oservicio->Fecha_F = $Anio . '-' . $Mes . '-31';
            $oservicio->Fecha_I = $Anio . '-' . '01-' . '01';
            $oservicio->RegEstatus = 'A';
            $oservicio->IdSucursal = $IdSucursal;

            if ($IdClienteS > 0) {
                $oservicio->IdClienteS = $IdClienteS;
            }

            if ($IdCliente > 0) {
                $oservicio->IdCliente = $IdCliente;
            }

            if ($IdContrato > 0) {
                $oservicio->IdContrato = $IdContrato;
            }

            $oservicio->Tipo_Serv = $i;
            $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

            foreach ($rowmesserv as $elementfin)
            {
                if ($elementfin->BurdenTotal != '')
                {
                    $BurdenTotalAnioActual += $elementfin->BurdenTotal;
                    $ManoObraTAnioActual += $elementfin->ManoObraT;
                    $CostoVAnioActual += $elementfin->CostoV;
                    $EquiposDAno += $elementfin->EquiposD;
                    $MaterialesDAnio += $elementfin->MaterialesD;
                    $ViaticosDAnio += $elementfin->ViaticosD;
                    $ContratistasDAnio += $elementfin->ContratistasD;
                }
            }
        }

        //buscamos el monte de facturacion de enero hasta el mes actual o seleccionado
        $TotalFact = 0;

        for ($i = 1; $i < 6; $i++) {
            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = '01';
            $oestadofinanciero->Mes2 = $Mes;
            $oestadofinanciero->IdCliente = $IdCliente;
            $oestadofinanciero->IdClienteS = $IdClienteS;
            $oestadofinanciero->IdContrato = $IdContrato;
            $rowfacturaanio = $oestadofinanciero->get_list_estadofinanciero();
            
            foreach ($rowfacturaanio as $element) {
                $TotalFact += $element->Facturacion;
            }
        }
        $TotalFact = $TotalFact;

        //****BUSQUEDA DE ESTADOFINANCIERO EN LA BASE DE DATOS

        $MontoFactActual = 0;
        $FactPasAcom = 0;
        $MaterialPasAcom = 0;
        $EquiposPasAcom = 0;
        $ManoOPasAcom = 0;
        $VehiculoPasAcom = 0;
        $ContratistaPasAcom = 0;
        $ViaticosPasAcom = 0;
        $BurdenPasAcom = 0;

        for ($i = 1; $i < 6; $i++) {

            $oestadofinanciero = new Mestadosfinancieros();
            $oestadofinanciero->IdConfigS = $i;
            $oestadofinanciero->IdSucursal = $IdSucursal;
            $oestadofinanciero->Anio = $Anio;
            $oestadofinanciero->Mes = $Mes;
            $oestadofinanciero->Mes2 = $Mes;
            $oestadofinanciero->IdTipoServ = "";
            $oestadofinanciero->IdCliente = $IdCliente;
            $oestadofinanciero->IdClienteS = $IdClienteS;
            $oestadofinanciero->IdContrato = $IdContrato;
            $rowmontoestado = $oestadofinanciero->get_list_estadofinanciero();
            
            //Este de aca no trae nada
            foreach ($rowmontoestado as $elemento) {
                $MontoFactActual += $elemento->Facturacion;
            }

            $countfac = 0;
            foreach ($rowPlanFactura as $element) {

                $odetalleestadofinanciero = new Mdetalleestado();
                $odetalleestadofinanciero->IdEstadoF = $oestadofinanciero->IdEstadoF;
                $odetalleestadofinanciero->IdPlanFactura = $element->IdPlanFactura;
                $recoverydetallef = $odetalleestadofinanciero->get_recobery_detalleestadofinanciero();

                if ($countfac == 0) {
                    $FactPasAcom += $recoverydetallef['data']->Pasado;
                }
                $countfac++;

                if ($element->Descripcion == 'Mano de Obra') {
                    $ManoOPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Vehiculos') {
                    $VehiculoPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Burden') {
                    $BurdenPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Materiales') {
                    $MaterialPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Equipos') {
                    $EquiposPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Contratistas') {
                    $ContratistaPasAcom += $recoverydetallef['data']->Pasado;
                }
                if ($element->Descripcion == 'Viaticos') {
                    $ViaticosPasAcom += $recoverydetallef['data']->Pasado;
                }
            }
        }

        //$op = 'Guardar';

        if ($oestadofinanciero->IdEstadoF > 0) {
            //$op = 'Modificar';
        }

        //****COSTOS G&A

        $TotalAnioPasadoGA = 0;
        $TotalPlanAcomGA = 0;
        $TotalActualAcomGA = 0;
        $TotalPlanMesGA = 0;
        $TotalActualMesGA = 0;

        $ocostoga = new Mcostoga();
        $ocostoga->Anio = $Anio;
        $ocostoga->IdSucursal = $IdSucursal;
        $rowcostodepto = $ocostoga->get_list_costoga();

        if (count($rowcostodepto) > 0)
        {
            foreach ($rowcostodepto as $element)
            {
                $Plan = 0;
                //para el año actual
                $PlanAnual = 0;
                $valorprim = $element->PrimerT / 3;
                $valorseug = $element->SegundoT / 3;
                $valorter = $element->TercerT / 3;
                $valorcuatro = $element->CuartoT / 3;

                $count = 1;

                for ($i = 1; $i <= 12; $i++)
                {
                    if ($Trimestre == 1 && $i < 4) {
                        $Plan += $valorprim;
                        break;
                    }
                    if ($Trimestre == 2 && $i > 3 && $i < 7) {
                        $Plan += $valorseug;
                        break;
                    }
                    if ($Trimestre == 3 && $i > 6 && $i < 10) {
                        $Plan += $valorter;
                        break;
                    }
                    if ($Trimestre == 4 && $i > 9) {
                        $Plan += $valorcuatro;
                        break;
                    }

                    $count++;
                }

                $count = 1;
                for ($i = 1; $i <= 12; $i++) {
                    if ($i < 4) {
                        $PlanAnual += $valorprim;
                    } else if ($i < 7) {
                        $PlanAnual += $valorseug;
                    } else if ($i < 10) {
                        $PlanAnual += $valorter;
                    } else {
                        $PlanAnual += $valorcuatro;
                    }

                    if ($count == $Mes) {
                        break;
                    }
                    $count++;
                }

                $MontoAnualActual = 0;
                for ($z = 0; $z < $Mes; $z++) {
                    $oactualcostoga = new Mactualcostoga();
                    $oactualcostoga->IdCostoGA = $element->IdCostoGA;
                    //MES + 1
                    $oactualcostoga->Mes = $z + 1;
                    $oactualcostoga->IdSucursal = $IdSucursal;
                    $oactualcostoga->Anio = $Anio;
                    $recoveryoactualcostoga1 = $oactualcostoga->get_recobery_actualcostoga();
                    $MontoAnualActual += $recoveryoactualcostoga1['data']->MontoMes;

                }

                $oactualcostoga = new Mactualcostoga();
                $oactualcostoga->IdCostoGA = $element->IdCostoGA;

                $oactualcostoga->Mes = $Mes;
                $oactualcostoga->IdSucursal = $IdSucursal;
                $oactualcostoga->Anio = $Anio;
                $recoveryoactualcostoga2 = $oactualcostoga->get_recobery_actualcostoga();

              
                $valorN = $recoveryoactualcostoga2['data']->MontoMes;
                if (round($valorN) == 0) {
                    $montmes = '';
                } else {
                    $montmes = round($valorN);
                }
                if ($element->AnioAnterior != '') {
                    $TotalAnioPasadoGA += round($element->AnioAnterior);
                }
               
                $TotalPlanAcomGA += round($PlanAnual);
                $TotalActualAcomGA += round($MontoAnualActual);
                $TotalPlanMesGA += round($Plan);
                $TotalActualMesGA += round($montmes);
            }
        }

        //FIN COSTOS GA
        $ogastosdirectos = new Mgastosdirectos();
        $ogastosdirectos->IdSucursal = $IdSucursal;
        $ogastosdirectos->Tipo = "1";
        $ogastosdirectos->Anio = $Anio;
        $row = $ogastosdirectos->get_list_gastosdirectos();

        $ogastosdirectos = new Mgastosdirectos();
        $ogastosdirectos->IdSucursal = $IdSucursal;
        $ogastosdirectos->Tipo = "2";
        $ogastosdirectos->Anio = $Anio;
        $rowind = $ogastosdirectos->get_list_gastosdirectos();


        $VentasPasado = 0;
        $VentasPlanAnioA = 0;
        $VentasActualAnioA = 0;
        $VentasPlanMes = 0;
        $VentasActualMes = 0;
        //directos

        foreach ($row as $element)
        {
            //para el mes actual
            $Plan = 0;

            //para el año actual
            $PlanAnual = 0;
            $valorprim = $element->UnoT / 3;
            $valorseug = $element->DosT / 3;
            $valorter = $element->TresT / 3;
            $valorcuatro = $element->CuatroT / 3;
            $count = 1;

            for ($i = 1; $i <= 12; $i++)
            {
                if ($Trimestre == 1 && $i < 4) {
                    $Plan += $valorprim;
                    break;
                }
                if ($Trimestre == 2 && $i > 3 && $i < 7) {
                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

            $count = 1;
            for ($i = 1; $i <= 12; $i++)
            {
                if ($i < 4) {
                    $PlanAnual += $valorprim;
                } else if ($i < 7) {
                    $PlanAnual += $valorseug;
                } else if ($i < 10) {
                    $PlanAnual += $valorter;
                } else {
                    $PlanAnual += $valorcuatro;
                }

                if ($count == $Mes) {

                    break;
                }

                $count++;
            }


            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++)
            {
                $oactualventas = new Mactualventas();
                $oactualventas->IdGasto = $element->IdGasto;
                 //MES + 1
                $oactualventas->Mes = $z + 1;
                $oactualventas->Anio = $Anio;
                $oactualventas->IdSucursal = $IdSucursal;
                $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
                $MontoAnualActual += $recoveryoactualventas['data']->MontoMes;
            }

            $oactualventas = new Mactualventas();
            $oactualventas->IdGasto = $element->IdGasto;
            $oactualventas->Mes = $Mes;
            $oactualventas->Anio = $Anio;
            $oactualventas->IdSucursal = $IdSucursal;
            $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
            $montval = round($recoveryoactualventas['data']->MontoMes);

            if (round($recoveryoactualventas['data']->MontoMes) == 0) {
                $montval = '';
            }
            if (round($element->MontoAnterior) != '' || round($element->MontoAnterior) != '0') {
                $VentasPasado += round($element->MontoAnterior);
            }
            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $VentasPlanAnioA += round($PlanAnual);
                // echo $VentasPlanAnioA.'Directos1';
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VentasActualAnioA += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $VentasPlanMes += round($Plan);
            }
            if (round($montval) != '' || round($montval) != '0') {
                $VentasActualMes += round($montval);
            }
        }

        //GASTOS INDIRECTOS ****
        foreach ($rowind as $element)
        {
            $Plan = 0;
            $PlanAnual = 0;
            $valorprim = $element->UnoT / 3;
            $valorseug = $element->DosT / 3;
            $valorter = $element->TresT / 3;
            $valorcuatro = $element->CuatroT / 3;
            $count = 1;

            for ($i = 1; $i <= 12; $i++)
            {
                if ($Trimestre == 1 && $i < 4) {
                    $Plan += $valorprim;
                    break;
                }
                if ($Trimestre == 2 && $i > 3 && $i < 7) {
                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

            $count = 1;
            $PlanAnual = 0;
            for ($i = 1; $i <= 12; $i++)
            {
                if ($i < 4) {
                    $PlanAnual += $valorprim;
                } else if ($i < 7) {

                    $PlanAnual += $valorseug;
                } else if ($i < 10) {
                    $PlanAnual += $valorter;
                } else {
                    $PlanAnual += $valorcuatro;
                }

                if ($count == $Mes) {
                    break;
                }
                $count++;
            }


            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++)
            {
                $oactualventas = new Mactualventas();
                $oactualventas->IdGasto = $element->IdGasto;
                 //MES + 1
                $oactualventas->Mes = $z + 1;
                $oactualventas->Anio = $Anio;
                $oactualventas->IdSucursal = $IdSucursal;
                $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
                $MontoAnualActual += $recoveryoactualventas['data']->MontoMes;
            }

            $oactualventas = new Mactualventas();
            $oactualventas->IdGasto = $element->IdGasto;
            $oactualventas->Mes = $Mes;
            $oactualventas->Anio = $Anio;
            $oactualventas->IdSucursal = $IdSucursal;
            $recoveryoactualventas = $oactualventas->get_recobery_actualventas();
            $montval = round($recoveryoactualventas['data']->MontoMes);

            if (round($recoveryoactualventas['data']->MontoMes) == 0) {
                $montval = '';
            }
            if (round($element->MontoAnterior) != '' || round($element->MontoAnterior) != '0') {
                $VentasPasado += round($element->MontoAnterior);
            }
            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $VentasPlanAnioA += round($PlanAnual);
                // echo $PlanAnual;
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VentasActualAnioA += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $VentasPlanMes += round($Plan);
            }
            if (round($montval) != '' || round($montval) != '0') {
                $VentasActualMes += round($montval);
            }
        }


        //****Costos
        $OpPasado = 0;
        $OpPlanAnio = 0;
        $OpActualAnio = 0;
        $OpPlanMes = 0;
        $OpActualMes = 0;

        //** COSTO DEPTO OPERACIONES
        $ocostodeptoventa = new Mcostodeptoventas();
        $ocostodeptoventa->Anio = $Anio;
        $ocostodeptoventa->IdSucursal = $IdSucursal;
        $rowcostodepto = $ocostodeptoventa->get_list_costodeptoventa();
        //print_r($rowcostodepto);
        foreach ($rowcostodepto as $element) {
            $Plan = 0;

            //para el a?o actual
            $PlanAnual = 0;
            $valorprim = $element->PrimerT / 3;
            $valorseug = $element->SegundoT / 3;
            $valorter = $element->TercerT / 3;
            $valorcuatro = $element->CuartoT / 3;

            $count = 1;
            for ($i = 1; $i <= 12; $i++)
            {
                if ($Trimestre == 1 && $i < 4) {
                    $Plan += $valorprim;
                    break;
                }
                if ($Trimestre == 2 && $i > 3 && $i < 7) {
                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

            $count = 1;
            for ($i = 1; $i <= 12; $i++) {
                if ($i < 4) {
                    $PlanAnual += $valorprim;
                } else if ($i < 7) {
                    $PlanAnual += $valorseug;
                } else if ($i < 10) {
                    $PlanAnual += $valorter;
                } else {
                    $PlanAnual += $valorcuatro;
                }

                if ($count == $Mes) {
                    break;
                }
                $count++;
            }

            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++)
            {
                $oactualoperaciones = new Mactualoperaciones();
                $oactualoperaciones->IdCostoDeptoVenta = $element->IdCostoDeptoVenta;
                 //MES + 1
                $oactualoperaciones->Mes = $z + 1;
                $oactualoperaciones->Anio = $Anio;
                $recoveryoactualoperaciones = $oactualoperaciones->get_recobery_actualoperaciones();
                $MontoAnualActual += $recoveryoactualoperaciones['data']->MontoMes;
            }

            $oactualoperaciones = new Mactualoperaciones();
            $oactualoperaciones->IdCostoDeptoVenta = $element->IdCostoDeptoVenta;
            $oactualoperaciones->Mes = $Mes;
            $oactualoperaciones->Anio = $Anio;
            $recoveryoactualoperaciones = $oactualoperaciones->get_recobery_actualoperaciones();

            $montac = round($recoveryoactualoperaciones['data']->MontoMes);
            if (round($recoveryoactualoperaciones['data']->MontoMes) == 0) {
                $montac = '';
            }
            if (round($element->AnioAnterior) != '' || round($element->AnioAnterior) != '0') {
                $OpPasado += round($element->AnioAnterior);
            }
            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $OpPlanAnio += round($PlanAnual);
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $OpActualAnio += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $OpPlanMes += round($Plan);
            }
            if (round($montac) != '' || round($montac) != '0') {
                $OpActualMes += round($montac);
            }
        }

        //**fIN COSTO OPERACIONES
        //**SACAR PORCENTAJES
        //Total mesual de vehiculo mano de obra y burden toal.

        $BurdenTotal = 0;
        $ManoObraT = 0;
        $CostoV = 0;
        $EquiposD = 0;
        $MaterialesD = 0;
        $ViaticosD = 0;
        $ContratistasD = 0;
        $TotalMontFact = 0;

        for ($i = 1; $i < 6; $i++)
        {
            $oservicio = new Mserviciosf();
            $oservicio->Fecha_F = $Anio . '-' . $Mes;
            $oservicio->RegEstatus = 'A';
            $oservicio->IdSucursal = $IdSucursal;
            $oservicio->Tipo_Serv = $i;

            if ($IdClienteS > 0) {
                $oservicio->IdClienteS = $IdClienteS;
            }
            if ($IdCliente > 0) {
                $oservicio->IdCliente = $IdCliente;
            }
            if ($IdContrato > 0) {
                $oservicio->IdContrato = $IdContrato;
            }

            $rowmesserv = $oservicio->get_list_servicioFinancieroAnioBurdenMano2();

            foreach ($rowmesserv as $elementfin)
            {
                if ($elementfin->BurdenTotal != '')
                {
                    $BurdenTotal += $elementfin->BurdenTotal;
                    $ManoObraT += $elementfin->ManoObraT;
                    $CostoV += $elementfin->CostoV;
                    $EquiposD += $elementfin->EquiposD;
                    $MaterialesD += $elementfin->MaterialesD;
                    $ViaticosD += $elementfin->ViaticosD;
                    $ContratistasD += $elementfin->ContratistasD;
                }
            }
        }

        //Fin
        //**
        //aqui recorremos los uodate
        if ($IdCliente == 0 && $IdClienteS == 0)
        {
            for ($i = 1; $i < 6; $i++) {
                //Este es nuevo EstadoUpdateClass
                $oestadofupdate = new Mestadofupdate();
                $oestadofupdate->IdSucursal = $IdSucursal;
                $oestadofupdate->Mes = intval($Mes);
                $oestadofupdate->Mes2 = intval($Mes);
                $oestadofupdate->Anio = $Anio;
                $oestadofupdate->IdConfigServ = $i;
                $rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

                foreach ($rowEstadoUpdate as $element) {
                    if ($element->Descripcion == 'Facturacion') {
                        //$TotalFact +=$element->Monto;
                        $MontoFactActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Burden') {
                        $BurdenTotal += $element->Monto;
                    }
                    if ($element->Descripcion == 'Mano de Obra') {
                        $ManoObraT += $element->Monto;
                    }
                    if ($element->Descripcion == 'Vehiculos') {
                        $CostoV += $element->Monto;
                    }
                    if ($element->Descripcion == 'Equipos') {
                        $EquiposD += $element->Monto;
                    }
                    if ($element->Descripcion == 'Materiales') {
                        $MaterialesD += $element->Monto;
                    }
                    if ($element->Descripcion == 'Viaticos') {
                        $ViaticosD += $element->Monto;
                    }
                    if ($element->Descripcion == 'Contratistas') {
                        $ContratistasD += $element->Monto;
                    }
                }

                //FIN ESTADO UPDATE CLASS
            }

            //aqui recorremos los uodate de enero hasta el mes actual;
            for ($i = 1; $i < 6; $i++)
            {
                //Este es nuevo EstadoUpdateClass
                $oestadofupdate = new Mestadofupdate();
                $oestadofupdate->IdSucursal = $IdSucursal;
                $oestadofupdate->Mes = intval('01');
                $oestadofupdate->Mes2 = intval($Mes);
                $oestadofupdate->Anio = $Anio;
                $oestadofupdate->IdConfigServ = $i;
                $rowEstadoUpdate = $oestadofupdate->get_list_estadofupdate();

                //print_r($rowEstadoUpdate);

                foreach ($rowEstadoUpdate as $element) {
                    if ($element->Descripcion == 'Facturacion') {
                        $TotalFact += $element->Monto;
                    }
                    if ($element->Descripcion == 'Burden') {
                        $BurdenTotalAnioActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Mano de Obra') {
                        $ManoObraTAnioActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Vehiculos') {
                        $CostoVAnioActual += $element->Monto;
                    }
                    if ($element->Descripcion == 'Equipos') {
                        $EquiposDAno += $element->Monto;
                    }
                    if ($element->Descripcion == 'Materiales') {
                        $MaterialesDAnio += $element->Monto;
                    }
                    if ($element->Descripcion == 'Viaticos') {
                        $ViaticosDAnio += $element->Monto;
                    }
                    if ($element->Descripcion == 'Contratistas') {
                        $ContratistasDAnio += $element->Monto;
                    }
                }

                //FIN ESTADO UPDATE CLASS
            }
        }

        //Varianza burden

        $vb1 = 0;
        $vb2 = 0;
        $vb3 = 0;
        $vb4 = 0;
        $vb5 = 0;
        $vb6 = 0;
        $vb7 = 0;
        $vb8 = 0;
        $vb9 = 0;
        $vb10 = 0;

        //Varianza Vehiculo
        $vv1 = 0;
        $vv2 = 0;
        $vv3 = 0;
        $vv4 = 0;
        $vv5 = 0;
        $vv6 = 0;
        $vv7 = 0;
        $vv8 = 0;
        $vv9 = 0;
        $vv10 = 0;

        $VpPasado = 0;
        $VpPlanAnio = 0;
        $VpActualAnio = 0;
        $VpPlanMes = 0;
        $VpActualMes = 0;
        //** COSTO DEPTO OPERACIONES

        $ocostovehope = new Mcostovehope();
        $ocostovehope->Anio = $Anio;
        $ocostovehope->IdSucursal = $IdSucursal;
        $rowcostove = $ocostovehope->get_list_costovehope();

        //print_r($rowcostove);
        foreach ($rowcostove as $element)
        {
            $Plan = 0;
            //para el a?o actual
            $PlanAnual = 0;
            $valorprim = $element->PrimerT / 3;
            $valorseug = $element->SegundoT / 3;
            $valorter = $element->TercerT / 3;
            $valorcuatro = $element->CuartoT / 3;

            $count = 1;

            for ($i = 1; $i <= 12; $i++)
            {
                if ($Trimestre == 1 && $i < 4) {
                    $Plan += $valorprim;
                    break;
                }
                if ($Trimestre == 2 && $i > 3 && $i < 7) {
                    $Plan += $valorseug;
                    break;
                }
                if ($Trimestre == 3 && $i > 6 && $i < 10) {
                    $Plan += $valorter;
                    break;
                }
                if ($Trimestre == 4 && $i > 9) {
                    $Plan += $valorcuatro;
                    break;
                }

                $count++;
            }

            $count = 1;
            for ($i = 1; $i <= 12; $i++) {
                if ($i < 4) {
                    $PlanAnual += $valorprim;
                } else if ($i < 7) {
                    $PlanAnual += $valorseug;
                } else if ($i < 10) {
                    $PlanAnual += $valorter;
                } else {
                    $PlanAnual += $valorcuatro;
                }

                if ($count == $Mes) {
                    break;
                }
                $count++;
            }

            $MontoAnualActual = 0;
            for ($z = 0; $z < $Mes; $z++)
            {
                $oactualcostove = new Mactualcostove();
                $oactualcostove->IdCostoVehOpe = $element->IdCostoVehOpe;
                 //MES + 1
                $oactualcostove->Mes = $z + 1;
                $oactualcostove->Anio = $Anio;
                $oactualcostove->IdSucursal = $IdSucursal;
                $recoveryoactualcostove = $oactualcostove->get_recobery_actualcostove();

                $MontoAnualActual += $recoveryoactualcostove['data']->MontoMes;
            }

            $oactualcostove = new Mactualcostove();
            $oactualcostove->IdCostoVehOpe = $element->IdCostoVehOpe;
            $oactualcostove->Mes = $Mes;
            $oactualcostove->Anio = $Anio;
            $oactualcostove->IdSucursal = $IdSucursal;
            $recoveryoactualcostove = $oactualcostove->get_recobery_actualcostove();
            $montac = round($recoveryoactualcostove['data']->MontoMes);

            if (round($recoveryoactualcostove['data']->MontoMes) == 0) {
                $montac = '';
            }
            if (round($element->AnioAnterior) != '' || round($element->AnioAnterior) != '0') {
                $VpPasado += round($element->AnioAnterior);
            }
            if (round($PlanAnual) != '' || round($PlanAnual) != '0') {
                $VpPlanAnio += round($PlanAnual);
            }
            if (round($MontoAnualActual) != '' || round($MontoAnualActual) != '0') {
                $VpActualAnio += round($MontoAnualActual);
            }
            if (round($Plan) != '' || round($Plan) != '0') {
                $VpPlanMes += round($Plan);
            }
            if (round($montac) != '' || round($montac) != '0') {
                $VpActualMes += round($montac);
            }
        }

        ///////////////////////////////////////
        //Pasados Acomulados
        //*********************** COSTOS FINANCIEROS ************************///
        $CostoFinAnioAnt = 0;
        $CostoFinTrim1 = 0;
        $CostoFinTrim2 = 0;
        $CostoFinTrim3 = 0;
        $CostoFinTrim4 = 0;

        $ocostofinanciero = new Mcostofinanciero();
        $ocostofinanciero->Anio = $AnioActual;
        $ocostofinanciero->IdSucursal = $IdSucursal;
        $ocostofinanciero->Tipo = 'TOTAL INTERESES Y GASTOS';
        $recoveryocostofinanciero = $ocostofinanciero->get_recobery_costofinanciero_plangral();

        if ($recoveryocostofinanciero['data']->AnioAnterior != '') {
            $CostoFinAnioAnt += $recoveryocostofinanciero['data']->AnioAnterior;
        }
        if ($recoveryocostofinanciero['data']->PrimerT != '') {
            $CostoFinTrim1 += $recoveryocostofinanciero['data']->PrimerT;
        }
        if ($recoveryocostofinanciero['data']->SegundoT != '') {
            $CostoFinTrim2 += $recoveryocostofinanciero['data']->SegundoT;
        }
        if ($recoveryocostofinanciero['data']->TercerT != '') {
            $CostoFinTrim3 += $recoveryocostofinanciero['data']->TercerT;
        }
        if ($recoveryocostofinanciero['data']->CuartoT != '') {
            $CostoFinTrim4 += $recoveryocostofinanciero['data']->CuartoT;
        }

        $ocostofinanciero = new Mcostofinanciero();
        $ocostofinanciero->Anio = $AnioActual;
        $ocostofinanciero->IdSucursal = $IdSucursal;
        $ocostofinanciero->Tipo = 'TOTAL OTROS INGRESOS/GASTOS';
        $recoveryocostofinanciero = $ocostofinanciero->get_recobery_costofinanciero_plangral();

        if ($recoveryocostofinanciero['data']->AnioAnterior != '') {
            $CostoFinAnioAnt -= $recoveryocostofinanciero['data']->AnioAnterior;
        }
        if ($recoveryocostofinanciero['data']->PrimerT != '') {
            $CostoFinTrim1 -= $recoveryocostofinanciero['data']->PrimerT;
        }
        if ($recoveryocostofinanciero['data']->SegundoT != '') {
            $CostoFinTrim2 -= $recoveryocostofinanciero['data']->SegundoT;
        }
        if ($recoveryocostofinanciero['data']->TercerT != '') {
            $CostoFinTrim3 -= $recoveryocostofinanciero['data']->TercerT;
        }
        if ($recoveryocostofinanciero['data']->CuartoT != '') {
            $CostoFinTrim4 -= $recoveryocostofinanciero['data']->CuartoT;
        }


        $valorprimcf = $CostoFinTrim1 / 3;
        $valorseugcf = $CostoFinTrim2 / 3;
        $valortercf = $CostoFinTrim3 / 3;
        $valorcuatrocf = $CostoFinTrim4 / 3;

        $AnioPasadoCF = $CostoFinAnioAnt;
        $PlanAnioCF = 0;
        // $ActualAnioCF=0;
        $PlanMesCF = $CostoFinTrim1 + $CostoFinTrim2 + $CostoFinTrim3 + $CostoFinTrim4;
        //$ActualMesCF=0;

        $count = 1;
        for ($i = 1; $i <= 12; $i++) {
            if ($i < 4) {
                $PlanAnioCF += $valorprimcf;
                $PlanMesCF = $valorprimcf;
            } else if ($i < 7) {
                $PlanAnioCF += $valorseugcf;
                $PlanMesCF = $valorseugcf;
            } else if ($i < 10) {
                $PlanAnioCF += $valortercf;
                $PlanMesCF = $valortercf;
            } else {
                $PlanAnioCF += $valorcuatrocf;
                $PlanMesCF = $valorcuatrocf;
            }

            if ($count == $Mes) {
                break;
            }
            $count++;
        }
        //********Costos financieros actual
        $MontoMesCF = 0;
        $MontoAnioCF = 0;
        $oactualcostof = new Mactualcostof();
        $oactualcostof->IdSucursal = $IdSucursal;
        $oactualcostof->Anio = $AnioActual;
        $oactualcostof->Mes = $Mes;
        $oactualcostof->Tipo = 1;
        $oactualcostof->Type = 'TOTAL INTERESES Y GASTOS';
        $recoveryoactualcostof = $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoMesCF = $recoveryoactualcostof['data']->MontoMes;
        }
        $oactualcostof->Tipo = 1;
        $oactualcostof->Type = 'TOTAL OTROS INGRESOS/GASTOS';
        $recoveryoactualcostof = $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoMesCF -= $recoveryoactualcostof['data']->MontoMes;
        }

        $oactualcostof = new Mactualcostof();
        $oactualcostof->IdSucursal = $IdSucursal;
        $oactualcostof->Anio = $AnioActual;
        $oactualcostof->Mes = $Mes;
        $oactualcostof->Tipo = 2;
        $oactualcostof->Type = 'TOTAL INTERESES Y GASTOS';
        $recoveryoactualcostof =  $oactualcostof->get_recobery_actualcostofrptgral2();

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoAnioCF = $recoveryoactualcostof['data']->MontoMes;
        }

        $oactualcostof->Type = 'TOTAL OTROS INGRESOS/GASTOS';
        $oactualcostof->Tipo = 2;
        $recoveryoactualcostof =$oactualcostof->get_recobery_actualcostofrptgral2();

        //print_r($recoveryoactualcostof);

        if ($recoveryoactualcostof['data']->MontoMes != '') {
            $MontoAnioCF -= $recoveryoactualcostof['data']->MontoMes;
        }

        if(count($rowPlanFactura))
        {
            $con=0;
            $countfac=0;
            $PlanAnualGral=1;
            $PlanMesGral=1;
            $PorcenAnio=0;
            $PorcenMes=0;

            foreach($rowPlanFactura as $element)
            {
                $PrimerTT=0;
                $SegundoTT=0;
                $TercerTT=0;
                $CuartoTT=0;
                $AnoAnteriorMont=0;

                $oporcentajeoperacion=new Mporcentajeoperacion();
                $oporcentajeoperacion->IdSucursal=$IdSucursal;
                $oporcentajeoperacion->Descripcion=$element->Descripcion;
                $oporcentajeoperacion->Anio=$Anio;
                $oporcentajeoperacion->IdSubtipoServ=0;
                $recoveryoporcentajeoperacion = $oporcentajeoperacion->get_recobery_porcentajeoperacionEstadoFinanciero2();

                $PrimerTT =$recoveryoporcentajeoperacion['data']->PrimerT;
                $SegundoTT =$recoveryoporcentajeoperacion['data']->SegundoT;
                $TercerTT =$recoveryoporcentajeoperacion['data']->TercerT;
                $CuartoTT =$recoveryoporcentajeoperacion['data']->CuartoT;
                $AnoAnteriorMont = $recoveryoporcentajeoperacion['data']->AnioAnterior;

                //aqui abajo se divide entre tres por que esta por trimestre
                //Plan Mensual actual
                $Plan=0;

                if($Trimestre==1)
                {
                    $Plan=$PrimerTT/3;
                }
                if($Trimestre==2){
                    $Plan=$SegundoTT/3;
                }
                if ($Trimestre==3){
                    $Plan=$TercerTT/3;
                }
                if($Trimestre==4){
                    $Plan=$CuartoTT/3;
                }

                //para el año actual Plan
                $PlanAnual=0;
                $valorprim= $PrimerTT/3;
                $valorseug= $SegundoTT/3;
                $valorter= $TercerTT/3;
                $valorcuatro=$CuartoTT/3;
                $count=1;

                for ($i=1; $i <=12 ;$i++ )
                {
                    if ($i <4){
                        $PlanAnual +=$valorprim;
                    }
                    else if($i <7){
                        $PlanAnual +=$valorseug;
                    }
                    else if($i <10 ){
                        $PlanAnual +=$valorter;
                    }
                    else
                    {
                        $PlanAnual +=$valorcuatro;
                    }

                    if($count==$Mes){
                        break;
                    }
                    $count++;
                }

                //Dependiendo de el nombre se pinta colocar en la columna  para mano de obra costo vehiculo y burden total
                $ColocarPorcenMes=0;
                $Colocar=0;
                $ColocarPasado=0;
                $ColocarAnioActual=0;
                $readonlyactual='';
                $clasmonto='features-input-dis ';
                $readonlmonto ='readonly="true"';
                $AnoAnteriorPorcent=0;

                if($countfac==0)
                {
                    //colocamos la facturacion almacenada en la base de datos
                    $Colocar = $MontoFactActual;
                    $ColocarAnioActual=$TotalFact;
                    //$readonlmonto='';
                    $clasmonto='features-input-dis';
                    $ColocarPasado= $FactPasAcom;

                    if ($PlanAnual==0){
                    }
                    else{
                        $PlanAnualGral=$PlanAnual;
                    }

                    if($Plan==0)
                    {
                    }
                    else
                    {
                        $PlanMesGral=$Plan;
                    }

                    $PorcenAnio=100;
                    $PorcenMes=100;
                    //$oporcentajeoperacion->get_recobery_porcentajeoperacion();
                    //$AnoAnteriorMont = 16666;
                }

                $countfac ++;
                if($element->Descripcion=='Mano de Obra')
                {
                    $Colocar = $ManoObraT;//paa el mes actual
                    $ColocarAnioActual=$ManoObraTAnioActual;//para el mes 1 al mes actual
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $ManoOPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;

                    //$oporcentajeoperacion->get_recobery_porcentajeoperacion();
                    //Varianza burden
                }

                if($element->Descripcion==utf8_encode('Vehiculos'))
                {
                    $Colocar =$CostoV;
                    $ColocarAnioActual=$CostoVAnioActual;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $VehiculoPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;

                    $vv1=$VpPasado-$AnoAnteriorMont;
                    $vv2=0;
                    $vv3= 0;//Se cambia a 0, cambio mencionado por Luis Angel -->  $VpPlanAnio-$PlanAnual;
                    $vv4=0;
                    $vv5=$VpActualAnio-$ColocarAnioActual;
                    $vv6=0;
                    $vv7= 0;//Se cambia a 0, cambio mencionado por Luis Angel --> $VpPlanMes-$Plan;
                    $vv8=0;
                    $vv9=$VpActualMes- $Colocar;
                    $vv10=0;
                }
                if($element->Descripcion=='Burden')
                {
                    $Colocar =$BurdenTotal;
                    $ColocarAnioActual=$BurdenTotalAnioActual;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $BurdenPasAcom ;

                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;

                    //Varianza burden
                    $vb1=$OpPasado-$AnoAnteriorMont;
                    $vb2=0;
                    $vb3=0;//Se cambia a 0, cambio mencionado por Luis Angel --> $OpPlanAnio-$PlanAnual;
                    $vb4=0;
                    $vb5=$OpActualAnio-$ColocarAnioActual;
                    $vb6=0;
                    $vb7=0;//Se cambia a 0, cambio mencionado por Luis Angel --> $OpPlanMes-$Plan;
                    $vb8=0;
                    $vb9=$OpActualMes- $Colocar;
                    $vb10=0;
                }
                if($element->Descripcion=='Materiales')
                {
                    $Colocar =$MaterialesD;
                    $ColocarAnioActual=$MaterialesDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $MaterialPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                if($element->Descripcion=='Equipos')
                {
                    $Colocar =$EquiposD;
                    $ColocarAnioActual=$EquiposDAno;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado= $EquiposPasAcom;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                if($element->Descripcion=='Contratistas')
                {
                    $Colocar =$ContratistasD;
                    $ColocarAnioActual=$ContratistasDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $ContratistaPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                if($element->Descripcion==utf8_encode('Viaticos'))
                {
                    $Colocar =$ViaticosD;
                    $ColocarAnioActual=$ViaticosDAnio;
                    $readonlyactual='readonly="true"';
                    $ColocarPasado = $ViaticosPasAcom ;
                    $PorcenAnio=($PlanAnual * 100)/$PlanAnualGral;
                    $PorcenMes=($Plan * 100)/$PlanMesGral;
                }
                //fin
                //PASADO BASE DE DATOS DETALLEESTADO FINANCIERO

                // $obje = array(

                // 'MontoAnioAnterior' => $AnoAnteriorMont
                // // 'PorcentajeAnioAnterior' => 0,
                // // 'PlanAnual' => $PlanAnual,
                // // 'PorcenAnio' => $PorcenAnio,
                // // 'MontoAnioAnterior' => $AnoAnteriorMont,
                // // 'MontoAnioAnterior' => $AnoAnteriorMont,

                // );

                // array_push($arraydatos,$obje);

                    //PorcentajeAnioPlan
                $array = array(
                    'IdPlanFactura'         => $element->IdPlanFactura,
                    'IdPorcentajeOperacion' => $oporcentajeoperacion->IdPorcentajeOperacion,
                    'Descripcion'           => $element->Descripcion,
                    'AnioAnteriorMonto'    => number_format($AnoAnteriorMont,0,'.',''),
                    'AnioAnteriorPorcen'    => number_format(0,1,'.',''),//number_format($AnoAnteriorPorcent,1,'.',''),

                    'AnioActualPlan'        => number_format($PlanAnual,0,'.',''),
                    'AnioActualPlanPorcent' => $PorcenAnio,
                    'AnioActualMonto'       => number_format($ColocarAnioActual,0,'.',''),
                    'AnioActualPorcen'      => round(0),

                    'MesActualPlan'         => number_format($Plan,0,'.',''),
                    'MesActualPlanPorcen'   => number_format($PorcenMes,1,'.',''),
                    'MesActualMonto'        => number_format($Colocar,0,'.',''),
                    'MesActualPorcen'       => 0,
                );

                array_push($arraydatos,$array);
            }

            $arraydatos2 = array(
                'TotalCostoOper'        => '',
                'COAnioAnteriorMonto'   => number_format(0,2,'.',''),
                'COAnioAnteriorPorcen'  => 0,
                'COAnioActualPlan'      => number_format(0,2,'.',''),
                'COAnioActualPlanPorcen'=> number_format(0,2,'.',''),
                'COAnioActualMonto'     => number_format(0,2,'.',''),
                'COAnioActualPorcen'    => number_format(0,2,'.',''),
                'COMesActualPlan'       => number_format(0,2,'.',''),
                'COMesActualPlanPorcen' => 0,
                'COMesActualMonto'      => number_format(0,2,'.',''),
                'COMesActualPorcen'     => 0,
            
                'GROSSPROFIT'           => '',
                'GPAnioAnteriorMonto'   => 0,
                'GPAnioAnteriorPorcen'  => 0,
                'GPAnioActualPlan'      => 0,
                'GPAnioActualPlanPorcen'=> 0,
                'GPAnioActualMonto'     => 0,
                'GPAnioActualPorcen'    => 0,
                'GPMesActualPlan'       => 0,
                'GPMesActualPlanPorcen' => 0,
                'GPMesActualMonto'      => 0,
                'GPMesActualPorcen'     => 0,

                /*COSTOS G & A */
                'CostosG&A' => '',
                'ga1' => $TotalAnioPasadoGA,
                'ga2' => 0,
                'ga3' => $TotalPlanAcomGA,
                'ga4' => 0,
                'ga5' => $TotalActualAcomGA,
                'ga6' => 0,
                'ga7' => $TotalPlanMesGA,
                'ga8' => 0,
                'ga9' => $TotalActualMesGA,
                'ga10' => 0,

                /* COSTOS Depto ventas directas e indirectas*/
                'CostosDeptoVenta' => 0,
                'cv1' => $VentasPasado,
                'cv2' => 0,
                'cv3' => $VentasPlanAnioA,
                'cv4' => 0,
                'cv5' => $VentasActualAnioA,
                'cv6' => 0,
                'cv7' => $VentasPlanMes,
                'cv8' => 0,
                'cv9' => $VentasActualMes,
                'cv10' => 0,

                /*COSTOS Varianza burden */
                'VarianzaBurden' => '',
                'vb1' => number_format($vb1,0,'.',''),
                'vb2' => number_format($vb2,0,'.',''),
                'vb3' => number_format($vb3,0,'.',''),
                'vb4' => 0,
                'vb5' => number_format($vb5,0,'.',''),
                'vb6' => 0,
                'vb7' => number_format($vb7,0,'.',''),
                'vb8' => number_format($vb8,0,'.',''),
                'vb9' => number_format($vb9,0,'.',''),
                'vb10' => number_format($vb10,0,'.',''),

                /*COSTOS Varianza Vehiculo */
                'VarianzaVehiculo' => '',
                'vv1' => number_format($vv1,0,'.',''),
                'vv2' => number_format($vv2,0,'.',''),
                'vv3' => number_format($vv3,0,'.',''),
                'vv4' => 0,
                'vv5' => number_format($vv5,0,'.',''),
                'vv6' => 0,
                'vv7' => number_format($vv7,0,'.',''),
                'vv8' => number_format($vv8,0,'.',''),
                'vv9' => number_format($vv9,0,'.',''),
                'vv10' => number_format($vv10,0,'.',''),

                /* Costo financiero*/
                'IngresosyEgresos' => '',
                'ie1' => number_format($AnioPasadoCF,0,'.',''),
                'ie2' => number_format(0,0,'.',''),
                'ie3' => number_format($PlanAnioCF,0,'.',''),
                'ie4' => 0,
                'ie5' => number_format($MontoAnioCF,0,'.',''),
                'ie6' => 0,
                'ie7' => number_format($PlanMesCF,0,'.',''),
                'ie8' => number_format(0,0,'.',''),
                'ie9' => number_format($MontoMesCF,0,'.',''),
                'ie10' => number_format(0,0,'.',''),

                /* Net Profit */

                'NETPROFIT' => '',
                'np1' =>  0,
                'np2' =>  0,
                'np3' =>  0,
                'np4' =>  0,
                'np5' =>  0,
                'np6' =>  0,
                'np7' =>  0,
                'np8' =>  0,
                'np9' =>  0,
                'np10' =>  0,
            );
        }

        $value = $this->Calculos_EstaosF($arraydatos,$arraydatos2);

        //este no sirve para las gráficas
        //$data['row'] =  $value['row'];
        //este sí sirve para las gráficas
        return $value['config'];
    }

    function Calculos_EstaosF($arraydatos,$arraydatos2)
    {
        $AnioAnteriorMontoTot = $arraydatos[0]['AnioAnteriorMonto'];
        $AnioActualPlanTot = $arraydatos[0]['AnioActualPlan'];
        $AnioActualMontoTot = $arraydatos[0]['AnioActualMonto'];

        $MesActualMontoTot = $arraydatos[0]['MesActualMonto'];
        $MesActualMontoTot2 = $arraydatos[0]['MesActualMonto'];
        $MesActualMonto = $arraydatos[0]['MesActualMonto'];

        
        //si es 0 que se divida entre 1
        if ($MesActualMontoTot == '0' || $MesActualMontoTot == '') {
            $MesActualMontoTot = 1;
        }
    
        if ($AnioAnteriorMontoTot == '0' || $AnioAnteriorMontoTot == '') {
            $AnioAnteriorMontoTot = '';
        }
    
        $varAnioAnteriorMonto = 0;
        $varAnioAnteriorPorcen = 0;

        $varAnioActualPlan = 0;
        $varAnioActualPlanPorcen = 0;
        $varAnioActualMonto = 0;
        $varAnioActualPorcen = 0;

        $varMesActualPlan = 0;
        $varMesActualPlanPorcen = 0;
        $varMesActualMonto = 0;
        $varMesActualPorcen = 0;
    
        $arraydatos[0]['AnioAnteriorPorcen'] = 100;
        $arraydatos[0]['AnioActualPlanPorcent'] = 100;
        $arraydatos[0]['AnioActualPorcen'] = 100;   
        $arraydatos[0]['MesActualPorcen'] = 100;

        $valorAnioActualPlan = $arraydatos[0]['AnioActualPlan'];
    
        //****calculos***
        $contador = 0;
        foreach($arraydatos as $element)
        {
            if($contador > 0)
            {
                // HACEMOS CALCULOS PARA OBTENER LOS PORCENTAJES, LOS CUALES SE HACEN DEL MONTO DE ITERACION*100 ENTRE EL MONTO DEL PRIMER CONCEPTO EL CUAL ES FACTURACION
                if($element['MesActualMonto'] == 0 || is_numeric(($element['MesActualMonto'] * 100)/$MesActualMontoTot) == false)
                {
                    $evalua1 = 0;
                }else{
                    $evalua1 = (($element['MesActualMonto'] * 100) / $MesActualMontoTot);
                }
                

                //POCENTAJE DEL AÑO PASADO
                if($element['AnioAnteriorMonto'] == 0 || is_numeric(($element['AnioAnteriorMonto'] * 100)/$AnioAnteriorMontoTot) == false)
                {
                    $evalua2 = 0;
                }else{
                    $evalua2 = (($element['AnioAnteriorMonto'] * 100) / $AnioAnteriorMontoTot);
                }


                //if($element['AnioActualPorcen'] > 0){
                /* if($element['AnioActualMonto'] > 0){
                    $element['AnioActualPorcen'] = (($element['AnioActualMonto'] * 100) / $AnioActualMontoTot);
                } */
                
                // PORCENTAJE DEL AÑO ACTUAL MONTO
                if($AnioActualMontoTot == 0 || $element['AnioActualMonto'] == 0 || is_numeric(($element['AnioActualMonto'] * 100)/$AnioActualMontoTot) == false)
                {
                    $evalua3 = 0;
                }else{
                    $evalua3 = (($element['AnioActualMonto'] * 100) / $AnioActualMontoTot);
                }

                
                //PORCENTAJE DEL AÑO ACTUAL PLAN
				if($element['AnioActualPlan'] > 0)
				{
					$ValorPorcenActual = number_format((($element['AnioActualPlan'] * 100) / $valorAnioActualPlan),1,'.','');
				}else{
					$ValorPorcenActual = 0;
				}
                
        
                if (is_numeric($ValorPorcenActual) == false) {
                    $evalua4 = 0;
                } else {
                    $evalua4 = $ValorPorcenActual;
                }

                $element['AnioAnteriorPorcen'] = number_format($evalua2,1,'.','');
                $element['AnioActualPlanPorcent'] = number_format($evalua4,1,'.','');
                $element['AnioActualPorcen'] = number_format($evalua3,1,'.','');
                $element['MesActualPorcen'] = number_format($evalua1,1,'.','');

            
                $varAnioAnteriorMonto+= number_format($element['AnioAnteriorMonto'], 2, '.', '');
                $varAnioAnteriorPorcen+= number_format($element['AnioAnteriorPorcen'], 1, '.', '');
                $varAnioActualPlan+= number_format($element['AnioActualPlan'], 2, '.', '');
                $varAnioActualPlanPorcen+= number_format($element['AnioActualPlanPorcent'], 1, '.', '');
                $varAnioActualMonto+= number_format($element['AnioActualMonto'], 2, '.', '');
                $varAnioActualPorcen+= number_format($element['AnioActualPorcen'], 1, '.', '');
                $varMesActualPlan+= number_format($element['MesActualPlan'], 2, '.', '');
                $varMesActualPlanPorcen+= number_format($element['MesActualPlanPorcen'], 1, '.', '');
                $varMesActualMonto+= number_format($element['MesActualMonto'], 2, '.', '');
                $varMesActualPorcen+= number_format($element['MesActualPorcen'], 1, '.', '');


                $arraydatos[$contador]['AnioAnteriorPorcen'] = number_format($element['AnioAnteriorPorcen'], 1, '.', '');
                $arraydatos[$contador]['AnioActualPorcen'] = number_format($element['AnioActualPorcen'], 1, '.', '');
                $arraydatos[$contador]['AnioActualPlanPorcent'] = number_format($element['AnioActualPlanPorcent'], 1, '.', '');
                
                if($MesActualMontoTot2 > 0){
                    $arraydatos[$contador]['MesActualPorcen'] = number_format($element['MesActualPorcen'], 1, '.', '');
                }else{
                    $arraydatos[$contador]['MesActualPorcen'] = 0;
                }
            }
        
            $contador ++;
        }
        // FIN FOREACH CONCEPTOS


		if($AnioAnteriorMontoTot > 0 || $varAnioAnteriorMonto > 0){
			$GPAnioAnteriorMonto = $AnioAnteriorMontoTot - $varAnioAnteriorMonto;
		}else{
			$GPAnioAnteriorMonto = 0;
		}

        $GPAnioAnteriorPorcen = 100 - $varAnioAnteriorPorcen;
        
        
        if(is_numeric($varAnioAnteriorMonto) == false || $varAnioAnteriorMonto == 0) {
            $evalua5 = 0;
        }else {
            $evalua5 = $varAnioAnteriorMonto;
        }
      
    
        if(is_numeric($GPAnioAnteriorMonto) == false || $GPAnioAnteriorMonto == 0) {
            $evalua6 = 0;
        }else {
            $evalua6 = $GPAnioAnteriorMonto;
        }    
    
        $GPAnioAnteriorPorcen = $GPAnioAnteriorPorcen;
        if ($GPAnioAnteriorPorcen >= 100) {
            $GPAnioAnteriorPorcen = 100;
        }
    
        // AÑO ANTERIOR CO Y GP
        $arraydatos2['COAnioAnteriorMonto'] = number_format($evalua5, 2, '.', '');
        $arraydatos2['GPAnioAnteriorMonto'] = number_format($evalua6, 2, '.', '');
        $arraydatos2['COAnioAnteriorPorcen'] = number_format($varAnioAnteriorPorcen, 1, '.', '');
        $arraydatos2['GPAnioAnteriorPorcen'] = number_format($GPAnioAnteriorPorcen, 1, '.', '');
    
        $evalua7 = $varAnioActualPlan;
        if (is_numeric($varAnioActualPlan) == false) {
            $evalua7 = 0;
        }
    
        // AÑO ACTUAL PLAN Y MONTO
        $arraydatos2['COAnioActualPlan'] = number_format($evalua7,2,'.','');
        $arraydatos2['COAnioActualPlanPorcen'] = number_format($varAnioActualPlanPorcen,1,'.','');
        $arraydatos2['COAnioActualMonto'] = number_format($varAnioActualMonto,2,'.','');
        $arraydatos2['COAnioActualPorcen'] = number_format($varAnioActualPorcen,1,'.','');

        // MES ACTUAL PLAN Y MONTO
        $arraydatos2['COMesActualPlan'] = number_format($varMesActualPlan,2,'.','');
    
        $COMesActualPlanPorcen = $varMesActualPlanPorcen;
    
        if ($COMesActualPlanPorcen >= 100) {
            $COMesActualPlanPorcen = 100;
        }
        //echo $varMesActualMonto.'//';
        $arraydatos2['COMesActualPlanPorcen'] =  number_format($COMesActualPlanPorcen,1,'.','');
        $arraydatos2['COMesActualMonto'] =  number_format($varMesActualMonto,2,'.','');
    
        $COMesActualPorcen = $varMesActualPorcen;
        if ($COMesActualPorcen >= 100) {
            $COMesActualPorcen = 100;
        }
        $arraydatos2['COMesActualPorcen'] =  number_format($COMesActualPorcen,1,'.','');

        ////////////GROSS PROFIT////////////////

        // AÑO ACTUAL PLAN ===
        $evalua8 = ($AnioActualPlanTot - $varAnioActualPlan);
    
        $GPAnioActualPlanPorcen = (100 - $varAnioActualPlanPorcen);
        if ($GPAnioActualPlanPorcen >= 100) {
            $GPAnioActualPlanPorcen = 100;
        }
    
        $arraydatos2['GPAnioActualPlan'] = number_format($evalua8, 2, '.', '');
        $arraydatos2['GPAnioActualPlanPorcen'] = number_format($GPAnioActualPlanPorcen, 1, '.', '');
    
        // AÑO ACTUAL MONTO ===
        $GPAnioActualMonto = ($AnioActualMontoTot - $varAnioActualMonto);
        if (is_numeric($GPAnioActualMonto) == false) {
            $GPAnioActualMonto = 0;
        }

        $GPAnioActualPorcen = (100 - $varAnioActualPorcen);
    
        if ($GPAnioActualPorcen < 0) {
            $GPAnioActualPorcen = 0;
        }
        $arraydatos2['GPAnioActualMonto'] = number_format($GPAnioActualMonto, 2, '.', '');
        $arraydatos2['GPAnioActualPorcen'] = number_format($GPAnioActualPorcen, 1, '.', '');


        // MES ACTUAL PLAN ===
        $evalua9 = ($arraydatos[0]['MesActualPlan'] - $varMesActualPlan);
        $evalua10 = (100 - $varMesActualPlanPorcen);
        $arraydatos2['GPMesActualPlan'] = number_format($evalua9, 2, '.', '');
        $arraydatos2['GPMesActualPlanPorcen'] = number_format($evalua10, 1, '.', '');

        // MES ACTUAL MONTO ===
        if (is_numeric(($MesActualMonto - $varMesActualMonto)) == false) {
            $GPMesActualMonto = 0;
        }
        else
        {
            $GPMesActualMonto = ($MesActualMonto - $varMesActualMonto);
        }

        $arraydatos2['GPMesActualMonto'] = number_format($GPMesActualMonto,2, '.', '');
    
        $GrossPPAM = (100 - $varMesActualPorcen);
        if ($GrossPPAM >= 100) {
            $GrossPPAM = 100;
        }
    
        if ($GrossPPAM < 0) {
            $evalua11 = 0;
        } else {
            $evalua11 = $GrossPPAM;
        }

        $arraydatos2['GPMesActualPorcen'] = number_format($evalua11,1, '.', '');

        ////////////////////////////////////////////////
        #Calcular procentaje de G&A
        if($arraydatos2['ga1'] > 0 || $arraydatos[0]['AnioAnteriorMonto'])
        {
            $valueg2 = (($arraydatos2['ga1']*100)/$arraydatos[0]['AnioAnteriorMonto']);
        }else{
            $valueg2 = 0;
        }

        if($arraydatos2['ga3'] > 0 || $arraydatos[0]['AnioActualPlan'])
        {
            $valueg4 = (($arraydatos2['ga3']*100)/$arraydatos[0]['AnioActualPlan']);
        }else{
            $valueg4 = 0;
        }

        if($arraydatos2['ga5'] > 0 & $arraydatos[0]['AnioActualMonto'] > 0)
        {
            $valueg6 = (($arraydatos2['ga5']*100)/$arraydatos[0]['AnioActualMonto']);
        }else{
            $valueg6 = 0;
        }

        if($arraydatos2['ga7'] > 0 || $arraydatos[0]['MesActualPlan'])
        {
            $valueg8 = (($arraydatos2['ga7']*100)/$arraydatos[0]['MesActualPlan']);
        }else{
            $valueg8 = 0;
        }

        $arraydatos2['ga2'] = number_format($valueg2, 1, '.', '');
        $arraydatos2['ga4'] = number_format($valueg4, 1, '.', '');
        $arraydatos2['ga6'] = number_format($valueg6, 1, '.', '');
        $arraydatos2['ga8'] = number_format($valueg8, 1, '.', '');

        if($arraydatos[0]['AnioActualMonto'] > 0)
        {
            $arraydatos2['COAnioActualPorcen'] = ($arraydatos2['COAnioActualMonto']*100/$arraydatos[0]['AnioActualMonto']);
        }

        //////////////////////////////////////////////////
        #Calcular procentaje de Costos Depto. Vent
        
        if($arraydatos2['cv1'] > 0 || $arraydatos[0]['AnioAnteriorMonto'])
        {
            $valuec2 = (($arraydatos2['cv1']*100)/$arraydatos[0]['AnioAnteriorMonto']);
        }else{
            $valuec2 = 0;
        }

        if($arraydatos2['cv3'] > 0 || $arraydatos[0]['AnioActualPlan'])
        {
            $valuec4 = (($arraydatos2['cv3']*100)/$arraydatos[0]['AnioActualPlan']);
        }else{
            $valuec4 = 0;
        }

        if($arraydatos2['cv5'] > 0 & $arraydatos[0]['AnioActualMonto'] > 0)
        {
            $valuec6 = (($arraydatos2['cv5']*100)/$arraydatos[0]['AnioActualMonto']);
        }else{
            $valuec6 = 0;
        }

        if($arraydatos2['cv7'] > 0 || $arraydatos[0]['MesActualPlan'])
        {
            $valuec8 = (($arraydatos2['cv7']*100)/$arraydatos[0]['MesActualPlan']);
        }else{
            $valuec8 = 0;
        }

        $arraydatos2['cv2'] = number_format($valuec2, 1, '.', '');
        $arraydatos2['cv4'] = number_format($valuec4, 1, '.', '');
        $arraydatos2['cv6'] = number_format($valuec6, 1, '.', '');
        $arraydatos2['cv8'] = number_format($valuec8, 1, '.', '');

        ///////////////////////////////////////
        #Calcular procentaje de Costos financieros

        if($arraydatos2['ie1'] > 0 || $arraydatos[0]['AnioAnteriorMonto'])
        {
            $valuei2 = (($arraydatos2['ie1']*100)/$arraydatos[0]['AnioAnteriorMonto']);
        }else{
            $valuei2 = 0;
        }

        if($arraydatos2['ie3'] > 0 || $arraydatos[0]['AnioActualPlan'])
        {
            $valuei4 = (($arraydatos2['ie3']*100)/$arraydatos[0]['AnioActualPlan']);
        }else{
            $valuei4 = 0;
        }

        if($arraydatos2['ie5'] > 0 || $arraydatos[0]['AnioActualMonto'])
        {
            $valuei6 = (($arraydatos2['ie5']*100)/$arraydatos[0]['AnioActualMonto']);
        }else{
            $valuei6 = 0;
        }

        if($arraydatos2['ie7'] > 0 || $arraydatos[0]['MesActualPlan'])
        {
            $valuei8 = (($arraydatos2['ie7']*100)/$arraydatos[0]['MesActualPlan']);
        }else{
            $valuei8 = 0;
        }

        $arraydatos2['ie2'] = number_format($valuei2, 1, '.', '');
        $arraydatos2['ie4'] = number_format($valuei4, 1, '.', '');
        $arraydatos2['ie6'] = number_format($valuei6, 1, '.', '');
        $arraydatos2['ie8'] = number_format($valuei8, 1, '.', '');

        ///////////////////////////////////////
        #Calcular procentaje de Varianza Burden

        if($arraydatos2['vb1'] > 0 || $arraydatos[0]['AnioAnteriorMonto'])
        {
            $valuevb2 = (($arraydatos2['vb1']*100)/$arraydatos[0]['AnioAnteriorMonto']);
        }else{
            $valuevb2 = 0;
        }

        if($arraydatos2['vb3'] > 0 || $arraydatos[0]['AnioActualPlan'])
        {
            $valuevb4 = (($arraydatos2['vb3']*100)/$arraydatos[0]['AnioActualPlan']);
        }else{
            $valuevb4 = 0;
        }

        if($arraydatos2['vb5'] > 0 || $arraydatos[0]['AnioActualMonto'])
        {
            $valuevb6 = (($arraydatos2['vb5']*100)/$arraydatos[0]['AnioActualMonto']);
        }else{
            $valuevb6 = 0;
        }

        if($arraydatos2['vb7'] > 0 || $arraydatos[0]['MesActualPlan'])
        {
            $valuevb8 = (($arraydatos2['vb7']*100)/$arraydatos[0]['MesActualPlan']);
        }else{
            $valuevb8 = 0;
        }

        $arraydatos2['vb2'] = number_format($valuevb2, 1, '.', '');
        $arraydatos2['vb4'] = number_format($valuevb4, 1, '.', '');
        $arraydatos2['vb6'] = number_format($valuevb6, 1, '.', '');
        $arraydatos2['vb8'] = number_format($valuevb8, 1, '.', '');

        /////////////////////////////////
        #Calcular procentaje de Varianza Vehiculo

        if($arraydatos2['vv1'] > 0 || $arraydatos[0]['AnioAnteriorMonto'])
        {
            $valuevv2 = (($arraydatos2['vv1']*100)/$arraydatos[0]['AnioAnteriorMonto']);
        }else{
            $valuevv2 = 0;
        }

        if($arraydatos2['vv3'] > 0 || $arraydatos[0]['AnioActualPlan'])
        {
            $valuevv4 = (($arraydatos2['vv3']*100)/$arraydatos[0]['AnioActualPlan']);
        }else{
            $valuevv4 = 0;
        }

        if($arraydatos2['vv5'] > 0 || $arraydatos[0]['AnioActualMonto'])
        {
            $valuevv6 = (($arraydatos2['vv5']*100)/$arraydatos[0]['AnioActualMonto']);
        }else{
            $valuevv6 = 0;
        }

        if($arraydatos2['vv7'] > 0 || $arraydatos[0]['MesActualPlan'])
        {
            $valuevv8 = (($arraydatos2['vv7']*100)/$arraydatos[0]['MesActualPlan']);
        }else{
            $valuevv8 = 0;
        }

        $arraydatos2['vv2'] = number_format($valuevv2, 1, '.', '');
        $arraydatos2['vv4'] = number_format($valuevv4, 1, '.', '');
        $arraydatos2['vv6'] = number_format($valuevv6, 1, '.', '');
        $arraydatos2['vv8'] = number_format($valuevv8, 1, '.', '');

        // PARA LOS PORCENTAJES DEL MES ACTUAL
        if($arraydatos[0]['MesActualMonto'] > 0)
        {
            $valuega10 = (($arraydatos2['ga9']*100)/$arraydatos[0]['MesActualMonto']);
            $valuecv10 = (($arraydatos2['cv9']*100)/$arraydatos[0]['MesActualMonto']);
            $valueie10 = (($arraydatos2['ie9']*100)/$arraydatos[0]['MesActualMonto']);
            $valuevb10 = (($arraydatos2['vb9']*100)/$arraydatos[0]['MesActualMonto']);
            $valuevv10 = (($arraydatos2['vv9']*100)/$arraydatos[0]['MesActualMonto']);
        }
        else
        {
            $valuega10 =  0;
            $valuecv10 =  0;
            $valueie10 =  0;
            $valuevb10 =  0;
            $valuevv10 =  0;
        }

        $arraydatos2['ga10'] = number_format($valuega10, 1, '.', '');
        $arraydatos2['cv10'] = number_format($valuecv10, 1, '.', '');
        $arraydatos2['ie10'] = number_format($valueie10, 1, '.', '');
        $arraydatos2['vb10'] = number_format($valuevb10, 1, '.', '');
        $arraydatos2['vv10'] = number_format($valuevv10, 1, '.', '');


        
        $value = $this->NetProfit($arraydatos,$arraydatos2);

        $dataCalculos['row'] = $arraydatos;
        $dataCalculos['config'] = $value;

        return $dataCalculos;
    }

    public function NetProfit($arraydatos,$arraydatos2)
    {
        $gp1 = $arraydatos2['GPAnioAnteriorMonto'];
        $gp2 = $arraydatos2['GPAnioAnteriorPorcen'];
        $gp3 = $arraydatos2['GPAnioActualPlan'];
        $gp4 = $arraydatos2['GPAnioActualPlanPorcen'];
        $gp5 = $arraydatos2['GPAnioActualMonto'];
        $gp6 = $arraydatos2['GPAnioActualPorcen'];
        $gp7 = $arraydatos2['GPMesActualPlan'];
        $gp8 = $arraydatos2['GPMesActualPlanPorcen'];
        $gp9 = $arraydatos2['GPMesActualMonto'];
        $gp10 = $arraydatos2['GPMesActualPorcen'];

        //costos ga
        $ga1 = $arraydatos2['ga1'];
        $ga3 = $arraydatos2['ga3'];
        $ga5 = $arraydatos2['ga5'];
        $ga7 = $arraydatos2['ga7'];
        $ga9 = $arraydatos2['ga9'];
        
        //costos  ventas
        $cv1 = $arraydatos2['cv1'];
        $cv3 = $arraydatos2['cv3'];
        $cv5 = $arraydatos2['cv5'];
        $cv7 = $arraydatos2['cv7'];
        $cv9 = $arraydatos2['cv9'];
        
        //Varianza burden
        $co1 = $arraydatos2['vb1'];
        $co3 = $arraydatos2['vb3'];
        $co5 = $arraydatos2['vb5'];
        $co7 = $arraydatos2['vb7'];
        $co9 = $arraydatos2['vb9'];

        //Varianza Vehiculo
        $vv1 = $arraydatos2['vv1'];
        $vv3 = $arraydatos2['vv3'];
        $vv5 = $arraydatos2['vv5'];
        $vv7 = $arraydatos2['vv7'];
        $vv9 = $arraydatos2['vv9'];

        //Ingresos y egresos
        $vv1 = $arraydatos2['vv1'];
        $vv3 = $arraydatos2['vv3'];
        $vv5 = $arraydatos2['vv5'];
        $vv7 = $arraydatos2['vv7'];
        $vv9 = $arraydatos2['vv9'];

        $ie1 = $arraydatos2['ie1'];
        $ie3 = $arraydatos2['ie3'];
        $ie5 = $arraydatos2['ie5'];
        $ie7 = $arraydatos2['ie7'];
        $ie9 = $arraydatos2['ie9'];

        //gross profit - costo g&a - costo depto venta-costo operacion - varianza vehiculo + gastos e ingresos
        //Costo vehiculo operacion no se resta

        $np1 = $gp1 - $ga1 - $cv1 - $co1 - $vv1 + $ie1;
        $np2 = 0;
        $np3 = $gp3 - $ga3 - $cv3 - $co3 - $vv3 + $ie3;
        $np4 = 0;
        $np5 = $gp5 - $ga5 - $cv5 - $co5 - $vv5 + $ie5;
        $np6 = 0;
        $np7 = $gp7 - $ga7 - $cv7 - $co7 - $vv7 + $ie7;
        $np8 = 0;
        $np9 = $gp9 - $ga9 - $cv9 - $co9 - $vv9 + $ie9;
        $np10 = 0;

        if(is_numeric($np1) == false) {
            $np1 = 0;
        } else {
            $np1 = $np1;
        }
        if(is_numeric($np2) == false){
            $np2 = 0;
        } else {
            $np2 = $np2;
        }

        if(is_numeric($np3) == false) {

            $np3 = 0;
        } else {
            $np3 = $np3;
        }
        if(is_numeric($np4) == false) {
            $np4 = 0;
        } else {
            $np4 = $np4;
        }
        if(is_numeric($np5) == false) {
            $np5 = 0;
        } else {
            $np5 = $np5;
        }
        if(is_numeric($np6) == false) {
            $np6 = 0;
        } else {
            $np6 = $np6;
        }
        if(is_numeric($np7) == false) {
            $np7 = 0;
        } else {
            $np7 = $np7;
        }
        if(is_numeric($np8) == false) {
            $np8 = 0;
        } else {
            $np8 = $np8;
        }
        if(is_numeric($np9) == false) {
            $np9 = 0;
        } else {
            $np9 = $np9;
        }
        if(is_numeric($np10) == false) {
            $np10 = 0;
        } else {
            $np10 = $np10;
        }

        $arraydatos2['np1'] = number_format($np1, 2, '.', '');
        $arraydatos2['np2'] = number_format($np2, 1, '.', '');
        $arraydatos2['np3'] = number_format($np3, 2, '.', '');
        $arraydatos2['np4'] = number_format($np4, 1, '.', '');
        $arraydatos2['np5'] = number_format($np5, 2, '.', '');
        $arraydatos2['np6'] = number_format($np6, 1, '.', '');
        $arraydatos2['np7'] = number_format($np7, 2, '.', '');
        $arraydatos2['np8'] = number_format($np8, 1, '.', '');
        $arraydatos2['np9'] = number_format($np9, 2, '.', '');
        $arraydatos2['np10'] =  number_format($np10, 1, '.', '');

        //Porcentajes Finales
        $u5 = 0;
        $d5 = 0;
        $t5 = 0;
        $c5 = 0;
        $cc5 = 0;

        if($arraydatos[0]['AnioAnteriorMonto'] != 0 ){
            $u5 = (($arraydatos2['np1'] * 100) / $arraydatos[0]['AnioAnteriorMonto']);
        }

        if($arraydatos[0]['AnioActualPlan']!= 0){
            $d5 = (($arraydatos2['np3'] * 100) / $arraydatos[0]['AnioActualPlan']);
        }

        if($arraydatos[0]['AnioActualMonto']!= 0){
            $t5 = (($arraydatos2['np5'] * 100) / $arraydatos[0]['AnioActualMonto']);
        }

        if($arraydatos[0]['MesActualPlan']!= 0){

            $c5 = (($arraydatos2['np7'] * 100) / $arraydatos[0]['MesActualPlan']);
        }

        if($arraydatos[0]['MesActualMonto']!= 0){

            $cc5 = (($arraydatos2['np9'] * 100) / $arraydatos[0]['MesActualMonto']);
        }

        $arraydatos2['np2'] = number_format($u5, 1, '.', '');
        $arraydatos2['np4'] = number_format($d5, 1, '.', '');
        $arraydatos2['np6'] = number_format($t5, 1, '.', '');
        $arraydatos2['np8'] = number_format($c5, 1, '.', '');
        $arraydatos2['np10'] = number_format($cc5, 1, '.', '');
        
        return $arraydatos2;
    }
   
}