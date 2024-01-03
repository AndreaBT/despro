<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cdashboradone extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard/uno/Mtrabajador');
        $this->load->model('dashboard/uno/Mservicio');
        $this->load->model('dashboard/uno/Mtiposervicio');
        $this->load->model('dashboard/uno/Mvehiculo');

        $this->load->model('Mrol');
        setTimeZone($this->verification,$this->input);
    }

    public function Calcular_Minutos($hora1, $hora2)
    {

        $separar[1] = explode(':', $hora1);
        $separar[2] = explode(':', $hora2);

        $total_minutos_trasncurridos[1] = ($separar[1][0] * 60) + $separar[1][1];
        $total_minutos_trasncurridos[2] = ($separar[2][0] * 60) + $separar[2][1];
        $total_minutos_trasncurridos = $total_minutos_trasncurridos[1] - $total_minutos_trasncurridos[2];
        if ($total_minutos_trasncurridos <= 59) return ($total_minutos_trasncurridos . '');
        elseif ($total_minutos_trasncurridos > 59) {
            $HORA_TRANSCURRIDA = round($total_minutos_trasncurridos / 60);
            if ($HORA_TRANSCURRIDA <= 9) $HORA_TRANSCURRIDA = '0' . $HORA_TRANSCURRIDA;
            $MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos % 60;
            if ($MINUITOS_TRANSCURRIDOS <= 9) $MINUITOS_TRANSCURRIDOS = '0' . $MINUITOS_TRANSCURRIDOS;
            return ($HORA_TRANSCURRIDA . ':' . $MINUITOS_TRANSCURRIDOS . ' Horas');
        }
    }

    /**
     * HORAS PRODUCTIVAS SEMANALES.
     * OBTENCION DE DATOS DE PRODUCTIVIDAD SEMANAL PARA LA GRAFICA DE HORAS PRODUCTIVAS SEMANALES
     * DEL DASHBOARD DE DESPACHO, GRAFICA DE VELOCIMETROS.
     */
    public function findAll_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal         = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $IdTrabajador       = $this->post('IdTrabajador');
        $HoraTrabajoSemanal = 100;
        $HoraPS             = 0;
        $horasT             = 0;
        $HoraPSmenos        = 0;


        if (!empty($IdTrabajador)) {

            // RECUPERAR AL TRABAJADOR
            $otrabajador = new Mtrabajador();
            $otrabajador->IdSucursal    = $IdSucursal;
            $otrabajador->IdTrabajador  = $IdTrabajador;
            $row = $otrabajador->get_recobery_trabajador();

            //print_r($row['data']->HorasPS);
            //$this->response($row['data']->HorasTS );

            // SE OBTIENE LA CONFIGURACIÓN DE HORAS, SUS HORAS DE TRABAJO CONTRATADO SEMANALMENTE Y SUS HORAS PRODUCTIVAS A LA SEMANA
            $HoraTrabajoSemanal = intval($row['data']->HorasTS); // HORAS DE TRABAJO POR LAS QUE LO CONTRATARON.
            $HoraPS             = intval($row['data']->HorasPS); // HORAS PRODUCTIVAS DE TRABAJO POR SEMANA.

            // OBTENER LOS SERVICIOS EN LOS QUE HAYA TRABAJADO EL TECNICO Y ESTEN CONCLUIDOS.
            $servicio = new Mservicio();
            $servicio->IdTrabajador = $IdTrabajador;
            $servicio->IdSucursal   = $IdSucursal;
            $servicio->EstadoS      = 'CERRADA';
            $servicio->Facturable   = 's';

            // CONFIGURAR EL RANGO DE FECHAS POR EL CUAL SE EFECTURARÁ LA BUSQUEDA DE INFORMACION, SIEMPRE DEBE SER UN RANGO DE 7 DIAS.
            if ($this->post('Fecha_F') != '') {

                $servicio->Fecha_I = date("Y-m-d", strtotime($this->post('Fecha_I')));
                $servicio->Fecha_F = date("Y-m-d", strtotime($this->post('Fecha_F')));
            }

            //echo "FechaInicio:".$servicio->Fecha_I." | ";
            //echo "FechaFin:".$servicio->Fecha_F;

            $rowservicio = $servicio->get_list_serviciograficas(); // OBTENER LISTADO DE SERVICIOS CONCLUIDOS.


            $minutos_iniciales = 0;
            $hora       = 0;
            $minuto     = 0;
            $LisHoras   = '';
            $horaI      = '';
            $horaF      = '';


            foreach ($rowservicio as $elementh) {
                $partirhi = explode(':', $elementh->HoraInicio);
                $partirhf = explode(':', $elementh->HoraFin);

                $horaI = $partirhi[0] . ':' . $partirhi[1];
                $horaF = $partirhf[0] . ':' . $partirhf[1];

                $minutos_iniciales += Calcular_Minutos($horaI, $horaF); // El Resultado nos data en negativo (-960)
            }



            $minutos_iniciales  = $minutos_iniciales * -1; // CONVERTIR A POSITIVO EL VALOR OBTENIDO [-960 -> (960)]
            $minuto             = $minutos_iniciales % 60; // SABER SI EXISTE SOBRANTE DE LA DIVICION DE LA CANTIDAD ENTRE 60
            $hora               = ($minutos_iniciales - ($minutos_iniciales % 60)) / 60; //OBTENER EL NUMERO DE HORAS QUE SE TENGAS DERIVADAS DEL LOS MINUTOS (960 minutos / 60 minutos) = 16hrs

            // VERIFICAR EN CASO DE QUE NO EXISTA VALORES DE TIEMPO
            if ($hora == 0 && $minuto == 0) {
                $LisHoras = 0; // DEVOLVER 0
            } else {
                $LisHoras = $hora . '.' . $minuto; // DEVOLVER LA COMPOSICION DE HORA + MINUTO (16.00)
            }

            $horasT = floatval($LisHoras);  // CONVERTIR A FLOTANTE EL VALOR

            // VERIFICA QUE LAS HORAS PRODUCTIVAS SEMANALES DEL TECNICO NO ESTEN EN O
            if ($HoraPS == '') {
                // SETAR VALORES ESTANDAR
                $HoraPS = 0;
                $HoraTrabajoSemanal = 50;
            }

            $HoraPSmenos = $HoraPS - 5; // RESTAR 5 HORAS AL TOTAL DE HORAS PRODUCTIVAS SEMANALES PARA OBTENER EL LIMITE DE LA FRANJA AMARILLA

        }


        //****************************************** Trabajador 2 comaparativo *********************************************************

        $IdTrabajador2          = $this->post('IdTrabajador2');
        $HoraTrabajoSemanal2    = 100;
        $HoraPS2                = 0;
        $horasT2                = 0;
        $HoraPSmenos2           = 0;

        if (!empty($IdTrabajador2))
        {
             // RECUPERAR AL TRABAJADOR
            $otrabajador = new Mtrabajador();
            $otrabajador->IdSucursal    = $IdSucursal;
            $otrabajador->IdTrabajador  = $IdTrabajador2;
            $row2                       = $otrabajador->get_recobery_trabajador();

            // SE OBTIENE LA CONFIGURACIÓN DE HORAS, SUS HORAS DE TRABAJO CONTRATADO SEMANALMENTE Y SUS HORAS PRODUCTIVAS A LA SEMANA
            $HoraTrabajoSemanal2        = $row2['data']->HorasTS; // HORAS DE TRABAJO POR LAS QUE LO CONTRATARON.
            $HoraPS2                    = $row2['data']->HorasPS; // HORAS PRODUCTIVAS DE TRABAJO POR SEMANA.

            // OBTENER LOS SERVICIOS EN LOS QUE HAYA TRABAJADO EL TECNICO Y ESTEN CONCLUIDOS.
            $servicio = new Mservicio();
            $servicio->IdTrabajador     = $IdTrabajador2;
            $servicio->IdSucursal       = $IdSucursal;
            $servicio->EstadoS          = 'CERRADA';
            $servicio->Facturable       = 's';

            // CONFIGURAR EL RANGO DE FECHAS POR EL CUAL SE EFECTURARÁ LA BUSQUEDA DE INFORMACION, SIEMPRE DEBE SER UN RANGO DE 7 DIAS.
            if ($this->post('Fecha_F') != '') {

                $servicio->Fecha_I = date("Y-m-d", strtotime($this->post('Fecha_I')));
                $servicio->Fecha_F = $this->post('Fecha_F');
            }

            //echo " | FechaInicio2:".$servicio->Fecha_I." | ";
            //echo "FechaFin2:".$servicio->Fecha_F;

            $rowservicio = $servicio->get_list_serviciograficas(); // OBTENER LISTADO DE SERVICIOS CONCLUIDOS.

            $minutos_iniciales = 0;
            $hora       = 0;
            $minuto     = 0;
            $LisHoras   = '';
            $horaI      = '';
            $horaF      = '';

            foreach ($rowservicio as $elementh) {

                $partirhi = explode(':', $elementh->HoraInicio);
                $partirhf = explode(':', $elementh->HoraFin);

                $horaI = $partirhi[0] . ':' . $partirhi[1];
                $horaF = $partirhf[0] . ':' . $partirhf[1];

                $minutos_iniciales += Calcular_Minutos($horaI, $horaF); // El Resultado nos data en negativo (-960)
            }

            $minutos_iniciales  = $minutos_iniciales * -1;  // CONVERTIR A POSITIVO EL VALOR OBTENIDO [-960 -> (960)]
            $minuto             = $minutos_iniciales % 60;  // SABER SI EXISTE SOBRANTE DE LA DIVICION DE LA CANTIDAD ENTRE 60
            $hora               = ($minutos_iniciales - ($minutos_iniciales % 60)) / 60; //OBTENER EL NUMERO DE HORAS QUE SE TENGAS DERIVADAS DEL LOS MINUTOS (960 minutos / 60 minutos) = 16hrs

            // VERIFICAR EN CASO DE QUE NO EXISTA VALORES DE TIEMPO
            if ($hora == 0 && $minuto == 0) {
                $LisHoras = 0;
            } else {
                $LisHoras = $hora . '.' . $minuto; // DEVOLVER LA COMPOSICION DE HORA + MINUTO (16.00)
            }

            $horasT2 = floatval($LisHoras); // CONVERTIR A FLOTANTE EL VALOR

            // VERIFICA QUE LAS HORAS PRODUCTIVAS SEMANALES DEL TECNICO NO ESTEN EN O
            if ($HoraPS == '') {
                // SETAR VALORES ESTANDAR
                $HoraPS = 0;
                $HoraTrabajoSemanal = 50;
            }


            $HoraPSmenos2 = $HoraPS - 5; // RESTAR 5 HORAS AL TOTAL DE HORAS PRODUCTIVAS SEMANALES PARA OBTENER EL LIMITE DE LA FRANJA AMARILLA
        } else {

                $otrabajador                = new Mtrabajador();
                $otrabajador->Rol           = 'Usuario APP';
                $otrabajador->IdSucursal    = $IdSucursal;
                $otrabajador->RegEstatus    = 'A';
                $rowtrabajador              = $otrabajador->get_list_trabajador(true);

                //$numTrabajadores = (count($rowtrabajador) > 0) ? count($rowtrabajador) : 0;

                //print_r($sizeTrabajadores);

                //datos el primer trabajador
                $HoraTrabajoSemanal2    = 0;  //Horas de trabajo contratado por semana.
                $HoraPS2                = 0;  //Horas Productivas semanales.
                $numTrabajadores        = 0;  //Numero de trabajadores.
                $minutos_iniciales      = 0;
                $LisHoras               = '';

                // BUCLE DE LISTADO DE TRABAJADORES
                foreach ($rowtrabajador as $trabajador)
                {
                    $servicio = new Mservicio();
                    $servicio->IdTrabajador = $trabajador->IdTrabajador;
                    $servicio->IdSucursal   = $IdSucursal;
                    $servicio->EstadoS      = 'CERRADA';
                    $servicio->Facturable   = 's';

                    $HoraPS2                += $trabajador->HorasPS;
                    $HoraTrabajoSemanal2    += $trabajador->HorasTS;

                    if ($this->post('Fecha_F') != '') {

                        $servicio->Fecha_I = date("Y-m-d", strtotime($this->post('Fecha_I')));
                        $servicio->Fecha_F = $this->post('Fecha_F');

                    }

                    //echo " | FechaInicioTT:".$servicio->Fecha_I." | ";
                    //echo "FechaFinTT:".$servicio->Fecha_F;

                    $rowservicio = $servicio->get_list_serviciograficas();

                    $hora   = 0;
                    $minuto = 0;
                    $horaI  = '';
                    $horaF  = '';

                    // BUCLE POR REVISION DE SERVICIOS.
                    foreach ($rowservicio as $elementh)
                    {
                        $partirhi   = explode(':', $elementh->HoraInicio);
                        $partirhf   = explode(':', $elementh->HoraFin);

                        $horaI      = $partirhi[0] . ':' . $partirhi[1];
                        $horaF      = $partirhf[0] . ':' . $partirhf[1];

                        $minutos_iniciales += Calcular_Minutos($horaI, $horaF);
                    }

                    $numTrabajadores++;
                }

                //print_r($minutos_iniciales);

                $minutos_iniciales  = $minutos_iniciales * -1;
                $minuto             = $minutos_iniciales % 60;
                $hora               = ($minutos_iniciales - ($minutos_iniciales % 60)) / 60;

                // VERIFICAR EN CASO DE QUE NO EXISTA VALORES DE TIEMPO
                if ($hora == 0 && $minuto == 0) {
                    $LisHoras = 0;
                } else {
                    $LisHoras = $hora . '.' . $minuto;
                }

                if ($HoraPS2 == '') {
                    $HoraPS2 = 0;
                    $HoraTrabajoSemanal2 = 50;

                }else {

                }


                if ( !empty($HoraPS2) ){
                    $HoraPS2               = ($HoraPS2 / $numTrabajadores);
                    $HoraTrabajoSemanal2   = ($HoraTrabajoSemanal2 / $numTrabajadores);

                }else {
                    $HoraPS2               =  0;
                    $HoraTrabajoSemanal2   =  0;
                }


                $HoraPSmenos2 = $HoraPS2 - 5;

                if (floatval($LisHoras) == 0 && $numTrabajadores == 0) {
                    $horasT2 = 0;
                } else {
                    $horasT2 = floatval($LisHoras) / $numTrabajadores;
                }



        }




        $data['Trabajador1'] = array(

            'IdTrabajador'              => $IdTrabajador,           //TRABAJADOR 1
            'HoraTrabajoSemanal'        => $HoraTrabajoSemanal,
            'HoraPS'                    => $HoraPS,                 //HORAS PRODUCTIVAS SEMANLES
            'HoraPSmenos'               => $HoraPSmenos,            //HORAS PRODUCTIVAS SEMANLES (-5)
            'horasT'                    => $horasT,                 //HORAS TRABAJADAS
        );

        $data['Trabajador2'] = array(

            'IdTrabajador'             => $IdTrabajador2,           //TRABAJADOR 2
            'HoraTrabajoSemanal'       => $HoraTrabajoSemanal2,
            'HoraPS'                   => $HoraPS2,                 //HORAS PRODUCTIVAS SEMANLES
            'HoraPSmenos'              => $HoraPSmenos2,            //HORAS PRODUCTIVAS SEMANLES (-5)
            'horasT'                   => $horasT2,                 //HORAS TRABAJADAS
        );

        return $this->set_response([
            'status'    => true,
            'data'      => $data,
            'message'   => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Facturable_get()
    {
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $servicio = new Mservicio();
        $servicio->IdSucursal = $IdSucursal;
        $servicio->RegEstatus = 'A';

        if ($this->get('Fecha_F') != '') {

            $servicio->Fecha_I = date("Y-m-d", strtotime($this->get('Fecha_I')));
            $servicio->Fecha_F = date("Y-m-d", strtotime($this->get('Fecha_F')));
        }

        $rowservicio = $servicio->get_list_serviciograficas();

        //print_r($rowservicio);

        $Facturable = 0;
        $NoFacturable = 0;


        foreach ($rowservicio as $element) {
            $otiposervicio = new Mtiposervicio();
            $otiposervicio->IdTipoSer = $element->Tipo_Serv;
            $row = $otiposervicio->get_recobery_tiposervicio();

            if ($row['data']->Ingresos == 's') { //Factruable
                $Facturable++;
            } else { //No Faturable
                $NoFacturable++;
            }
        }


        if ($Facturable == 0 && $NoFacturable == 0) {
            $Facturable = 100;
        }

        $data['Facturable'] = $Facturable;
        $data['NoFacturable'] = $NoFacturable;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function HorasEquipo_get()
    {

        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $otipserv = new Mtiposervicio();
        $otipserv->IdSucursal = $IdSucursal;
        $rowtips = $otipserv->get_list_tiposervicio();

        $LisHoras = '';
        $ListTipoS = '';
        $data = '';
        $arraydatos = array();
        $category = array();
        $dataset = array();
        $dataseries = array();
        $datacategory = array();
        $datacolors = array();


        foreach ($rowtips as $element) {

            $oservicio = new Mservicio();
            $oservicio->IdSucursal = $IdSucursal;

            $oservicio->Fecha_I = date("Y-m-d", strtotime($this->get('Fecha_I')));
            $oservicio->Fecha_F = date("Y-m-d", strtotime($this->get('Fecha_F')));

            $oservicio->Tipo_Serv = $element->IdTipoSer;
            $rowserv = $oservicio->get_list_servicio();

            // echo $element->IdTipoSer;


            $hora = 0;
            $minuto = 0;

            $minutos_iniciales = 0;

            //print_r($rowserv);
            foreach ($rowserv as $elementh) {

                //print_r($elementh->HoraInicio);

                $partirhi = explode(':', $elementh->HoraInicio);
                $partirhf = explode(':', $elementh->HoraFin);

                $horaI = $partirhi[0] . ':' . $partirhi[1];
                $horaF = $partirhf[0] . ':' . $partirhf[1];

                $minutos_iniciales += Calcular_Minutos($horaI, $horaF);
            }
            //obtener minutos y horas de minutos
            $minutos_iniciales = $minutos_iniciales * -1;
            $minuto = $minutos_iniciales % 60;
            $hora = ($minutos_iniciales - ($minutos_iniciales % 60)) / 60;

            //echo  (double)$hora.'.'.$minuto;
            if ($LisHoras == '') {
                $LisHoras .= (float)$hora . '.' . $minuto;
            } else {
                $LisHoras .= ',' . '' . (float)$hora . '.' . $minuto;
            }

            if ($ListTipoS == '') {

                $ListTipoS .= '\'' . $element->Concepto . '\'';
            } else {
                $ListTipoS .= ',' . '\'' . $element->Concepto . '\'';
            }

            if (count($arraydatos) == 0) {

                $array = array(
                    'value' => (float)$hora . '.' . $minuto,
                    'color'           => $element->Color,
                    'label'           => $element->Concepto
                );

                $arraycat = array();
                $arraycat['label'] = $element->Concepto;

                array_push($arraydatos, $array);
                array_push($category, $arraycat);

                array_push($dataseries, (float)$hora . '.' . $minuto);
                array_push($datacategory, $element->Concepto);
                array_push($datacolors, $element->Color);
            } else {

                $array = array(
                    'value' => (float)$hora . '.' . $minuto,
                    'color'           => $element->Color,
                    'label'           => $element->Concepto
                );

                $arraycat = array();
                $arraycat['label'] = $element->Concepto;

                array_push($arraydatos, $array);
                array_push($category, $arraycat);

                array_push($dataseries, (float)$hora . '.' . $minuto);
                array_push($datacategory, $element->Concepto);
                array_push($datacolors, $element->Color);
            }
        }


        #rspuesta
        return $this->set_response([
            'status' => true,
            'data' => $arraydatos,
            'label' => $category,
            'datacategory' => $datacategory,
            'dataseries' => $dataseries,
            'datacolors' => $datacolors,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function operproductividad_get()
    {
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $Tipo = $this->get('TipoVehiculo');

        $arraydatos = array();
        $category = array();
        $dataset = array();

        $dataseries = array();
        $datacategory = array();



        if ($Tipo == 'Vendidos') {
            $ovehiculo = new Mvehiculo();
            $ovehiculo->IdSucursal = $IdSucursal;
            $rowtipv = $ovehiculo->get_list_vehiculo();


            $LisHoras = '';
            $ListTipoV = '';
            $datacontvehiculo = '';

            $paletteColors = array('#ff8f00', '#5d62b5', '#29c3be', '#ffff00', '#c6ff00', '#f2726f', '#ff5722', '#64dd17', '#0091ea', '#7e57c2', '#1de9b6');

            $contador = 0;
            $contadorColor = 0;

            foreach ($rowtipv as $element) {

                if ($ListTipoV == '') {
                    $ListTipoV .= '\'' . $element->Numero . '\'';
                } else {
                    $ListTipoV .= ',' . '\'' . $element->Numero . '\'';
                }
                $oservicio = new Mservicio();

                $oservicio->Fecha_I = date("Y-m-d", strtotime($this->get('Fecha_I')));
                $oservicio->Fecha_F = date("Y-m-d", strtotime($this->get('Fecha_F')));

                $oservicio->IdVehiculo = $element->IdVehiculo;
                $oservicio->RegEstatus = 'A';
                $oservicio->IdSucursal = $IdSucursal;
                $rowkm = $oservicio->get_list_graficavehiculo();



                $km = 0;
                foreach ($rowkm as $elementkm) {
                    //print_r($elementkm->Distancia.'-');
                    $var = $elementkm->Distancia * 2;
                    $km += $var;
                }

                if ($LisHoras == '') {
                    $LisHoras .= $km;
                } else {
                    $LisHoras .= ',' . $km;
                }

                if ($datacontvehiculo == '') {

                    // $datacontvehiculo.='{
                    //     "label": "'.$element['Numero'].'",
                    //     "value": "'.$km.'",
                    // }';

                    $array = array(
                        'value' => $km,
                        'color' => $paletteColors[$contadorColor],
                        'label' => $element->Numero
                    );

                    $arraycat = array();
                    $arraycat['label'] = $element->Numero;

                    array_push($arraydatos, $array);
                    array_push($category, $arraycat);

                    array_push($dataseries, $km);
                    array_push($datacategory, $element->Numero);
                } else {
                    // $datacontvehiculo.=',{
                    //     "label": "'.$element['Numero'].'",
                    //     "value": "'.$km.'",
                    // }';

                    $array = array(
                        'value' => $km,
                        'color' => $paletteColors[$contadorColor],
                        'label' => $element->Numero
                    );

                    $arraycat = array();
                    $arraycat['label'] = $element->Numero;

                    array_push($arraydatos, $array);
                    array_push($category, $arraycat);

                    array_push($dataseries, $km);
                    array_push($datacategory, $element->Numero);
                }

                $contadorColor++;

                if ($contadorColor >= count($paletteColors)) {
                    $contadorColor = 0;
                }
            }
        }

        if ($Tipo == 'Kilometros') {
            $ovehiculo = new Mvehiculo();
            $ovehiculo->IdSucursal = $IdSucursal;
            $ovehiculo->TipoVehiculo = 'Kilometros';
            $rowtipv = $ovehiculo->get_list_vehiculo();

            $LisHoras = '';
            $ListTipoV = '';
            $datacontvehiculo = '';

            $paletteColors = array('#ff8f00', '#5d62b5', '#29c3be', '#ffff00', '#c6ff00', '#f2726f', '#ff5722', '#64dd17', '#0091ea', '#7e57c2', '#1de9b6');

            $contador = 0;
            $contadorColor = 0;


            foreach ($rowtipv as $element) {

                if ($ListTipoV == '') {
                    $ListTipoV .= '\'' . $element->Numero . '\'';
                } else {
                    $ListTipoV .= ',' . '\'' . $element->Numero . '\'';
                }
                $oservicio = new Mservicio();
                $oservicio->IdVehiculo = $element->IdVehiculo;
                $oservicio->RegEstatus = 'A';
                $oservicio->Fecha_I = date("Y-m-d", strtotime($this->get('Fecha_I')));
                $oservicio->Fecha_F = date("Y-m-d", strtotime($this->get('Fecha_F')));

                $oservicio->IdSucursal = $IdSucursal;
                $rowkm = $oservicio->get_list_graficavehiculo();

                $dinero = 0;
                foreach ($rowkm as $elementkm) {
                    $var = $elementkm->Distancia * $element->CostoAnual;
                    $dinero += $var;
                }

                if ($LisHoras == '') {
                    $LisHoras .= $dinero;
                } else {
                    $LisHoras .= ',' . $dinero;
                }

                if ($datacontvehiculo == '') {
                    // $datacontvehiculo.='{
                    //     "label": "'.$element['Numero'].'",
                    //     "value": "'.$dinero.'",
                    // }';

                    $array = array(
                        'value' => $dinero,
                        'color' => $paletteColors[$contadorColor],
                        'label' => $element->Numero
                    );

                    $arraycat = array();
                    $arraycat['label'] = $element->Numero;

                    array_push($arraydatos, $array);
                    array_push($category, $arraycat);

                    array_push($dataseries, $dinero);
                    array_push($datacategory, $element->Numero);
                } else {
                    // $datacontvehiculo.=',{
                    //     "label": "'.$element['Numero'].'",
                    //     "value": "'.$dinero.'",
                    // }';
                    $array = array(
                        'value' => $dinero,
                        'color' => $paletteColors[$contadorColor],
                        'label' => $element->Numero
                    );

                    $arraycat = array();
                    $arraycat['label'] = $element->Numero;

                    array_push($arraydatos, $array);
                    array_push($category, $arraycat);

                    array_push($dataseries, $dinero);
                    array_push($datacategory, $element->Numero);
                }

                $contadorColor++;

                if ($contadorColor >= count($paletteColors)) {
                    $contadorColor = 0;
                }
            }
        }

        #Mensaje
        return $this->set_response([
            'status' => true,
            'data' => $arraydatos,
            'label' => $category,
            'datacategory' => $datacategory,
            'dataseries' => $dataseries,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
