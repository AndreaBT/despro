<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script

use phpDocumentor\Reflection\Types\Integer;
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cservicio extends REST_Controller
{
    public $RutaFile='assets/files/temporal/';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Mservicio');
        $this->load->model('Mhoraslaborales');
        $this->load->model('Mtrabajador');
        $this->load->model('Mvehiculo');
        $this->load->model('Mfechaservicio');
        $this->load->model('Mvehiculoservicio');
        $this->load->model('Mclientesucursal');
        $this->load->model('Mnumcontrato');

        $this->load->model('Mfolio');
        $this->load->model('Mrol');

        $this->load->library('Mail');


        $this->load->model('Mempresa');
        $this->load->model('Msucursal');
        $this->load->model('Mequipocomentario');
        $this->load->model('Mimagenequipo2');
        $this->load->model('Mspend_ordenc');
        $this->load->model('Mspend_proyecto');
        $this->load->model('Mclientes');
        $this->load->model('Mtiposervicio');
        $this->load->model('Mfirmas');
        $this->load->model('despacho/Mdetallemensaje');
        $this->load->library('Pushapp');

        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objServicio = new Mservicio();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->Folio=$this->get('Nombre');
        $objServicio->IdTrabajador=$this->get('IdTrabajador');
        $objServicio->Tipo_Serv=$this->get('IdTipoServicio');
        $objServicio->EstadoS=$this->get('EstatusS');
        $objServicio->Fecha_I=dateformato($this->get('FechaI'));
        $objServicio->Fecha_F=dateformato($this->get('FechaF'));
        $objServicio->RegEstatus='A';

        // Paginación
        $rows = $objServicio->get_list();
         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }

        $objServicio->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objServicio->Tamano = $pager->PageSize;
        $objServicio->Offset = $pager->Offset;

        $data['servicio'] =$objServicio->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $objServicio = new Mservicio();
        $objServicio ->IdServicio = $Id;

        $response =$objServicio->get_servicio();

        if ($response['status']) {

            if ($objServicio ->delete()) {

                return $this->set_response([
                    'status' => true,
                    'message' => 'Se ha eliminado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al eliminar la información.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            return $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function findOne_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objServicio  = new Mservicio();

        $Id = (int) $this->get('IdServicio');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objServicio->IdServicio = $Id;
        }


        $response =$objServicio->get_servicio();

        $Mclientesucursal=new Mclientesucursal();
        $Mclientesucursal->IdClienteS=$response['data']->IdClienteS;
        $resclisucursal=$Mclientesucursal->get_cliente();

        if ($response['status']) {

            $valores= $this->CalcularValores($Id,$response['data']->Distancia,$response['data']->Velocidad);
            
            

            $response['data']->ManoObraT=$valores['CostoMO'];
            $response['data']->BurdenTotal=$valores['Burden'];
            $response['data']->CostoV=$valores['CostoVH'];

            $data['servicio'] = $response['data'];
            
            $data['clientesuc'] = $resclisucursal['data'];
            $data['valores'] = $valores;
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function CalcularVal_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objServicio  = new Mservicio();
        

        $Id = (int) $this->get('IdServicio');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $Distancia=$this->get('Distancia');
        $Velocidad=$this->get('Velocidad');
        if($Distancia=='')
        {
           $Distancia=0;
        }

        if($Velocidad=='')
        {
           $Velocidad=0;
        }

        $valores= $this->CalcularValores($Id,$Distancia,$Velocidad);

        $data['valores'] = $valores;
        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function CalcularValores($Id,$Distancia,$Velocidad)
    {
        $oMfechaservicio = new Mfechaservicio();
        $oMfechaservicio->IdServicio = $Id;
        $row = $oMfechaservicio->get_list();

        $BurdenTotal = 0;
        $CostoManoObra = 0;

        foreach ($row as $elemento)
        {
            $LisHoras = 0;
            $minutos_iniciales = 0;

            //Correcto
            $HoraInit = substr($elemento->HoraInicio, 0, -3);
            
            $HoraFin = substr($elemento->HoraFin, 0, -3);
            

            if ($HoraFin == '00:00') {
                $HoraFin = '24:00';
            }


            $minutos_iniciales += Calcular_Minutos($HoraInit, $HoraFin);
            
            
            if ($minutos_iniciales < 0) {
                $minutos_iniciales = $minutos_iniciales * -1;
            }
            $minuto = ($minutos_iniciales)/60;
            
            $hora = ($minutos_iniciales - ($minutos_iniciales % 100)) / 60;
            

            if ($hora == 0 && $minuto == 0) {
                $LisHoras = 0;
            } else {
                $LisHoras = $minuto;
            }
            $oMtrabajador = new Mtrabajador();
            $oMtrabajador->IdTrabajador = $elemento->IdTrabajador;
            $datoemp = $oMtrabajador->get_trabajador();
            $BurdenT =  ($LisHoras  * $datoemp['data']->CostoAnual);
            $CostoManoO = ($LisHoras * $datoemp['data']->CostoHora);
            
           


            $Distancia = $Distancia;
            $Velocidad = $Velocidad;

            if ($Distancia  == '' || $Distancia == '0') {
                $Distancia = 0;
            }

            if ($Velocidad == '' || $Velocidad == '0') {
                $Velocidad = 1;
            }

            //para sacar el costo de traslado es distancia entre velocidad x 2 x el costo hora trabajador
            $CostoTraslado = (($Distancia * 2) / $Velocidad);

            //NUEVO CALCULO DE MANO DE OBRA - SEGUN LUIS ANGEL
                //NUEVA FORMULA -> (Num horas * Mano Obra de Trabajador) + (Traslado * Mano de Obra)
                    $ManoDeObraReal= ($CostoTraslado * $datoemp['data']->CostoHora);  
                    // var_dump($ManoDeObraReal);
                    $BurdenReal= ($CostoTraslado * $datoemp['data']->CostoAnual);   

            $BurdenTotal += intval($BurdenT)  + intval($BurdenReal);
            $CostoManoObra += intval($CostoManoO) + intval($ManoDeObraReal);
        }

        //vehiculos

        $oMvehiculoservicio = new Mvehiculoservicio();
        $oMvehiculoservicio->IdServicio = $Id;
        $rowv = $oMvehiculoservicio->get_list();
        $CostoVehiculo = 0;

        foreach ($rowv as $element) {

            $ovehiculo = new Mvehiculo();
            $ovehiculo->IdVehiculo = $element->IdVehiculo;
            $data = $ovehiculo->get_vehiculo();

            $CostoVehiculo += ($Distancia * 2) * $data['data']->CostoAnual;
            
        }

        $valores['Burden'] = $BurdenTotal;
        $valores['CostoMO'] = $CostoManoObra;
        $valores['CostoVH'] = $CostoVehiculo;

        return $valores;
    }

    public function ValidacionSave_post()
    {
        $paso = $this -> post('Paso');
        if ($paso==1)
        {
            $v = new Valitron\Validator([
                'Distancia'=> $this->post('Distancia'),
                'Direccion'=> $this->post('Direccion'),
                'Cliente'=> $this->post('IdCliente'),
                'Sucursal_cliente'=> $this->post('IdClienteS'),
                'Estado_servicio' => $this -> post('EstadoS'),
                'TipoServ' => $this -> post('Tipo_Serv'),
                'EstadoS'=> $this->post('EstadoS'),
                'Velocidad'=> $this->post('Velocidad')
            ]);

            $v -> rule('required', [
                'Distancia',
                'Direccion',
                'Cliente',
                'Sucursal_cliente',
                'EstadoS',
                'TipoServ',
                'Estado_servicio',
                'Velocidad',
            ]) -> message('El campo {field} es requerido.');

            $v -> rule('numeric', [
                'Distancia',
                'Velocidad',
            ]) -> message('El campo {field} no tiene un formato de numero valido.');
        }

        if ($paso==2)
        {
            $v = new Valitron\Validator([
                'Estado_servicio' => $this -> post('EstadoS'),
                'TipoServ' => $this -> post('Tipo_Serv'),
                'EstadoS'=> $this->post('EstadoS'),
                'Fecha_Inicio'=> $this->post('Fecha_I'),
                'Fecha_Fin' => $this->post('Fecha_F')
            ]);

            $v -> rule('required', [
                'EstadoS',
                'TipoServ',
                'Estado_servicio',
                'Fecha_Inicio',
                'Fecha_Fin'
            ]) -> message('El campo {field} es requerido.');

            $v -> rule('date', [
                'Fecha_Inicio',
                'Fecha_Fin',
            ]) -> message('El campo {field} no es una fecha valida.');
        }

        if ($paso==3)
        {
            $v = new Valitron\Validator([
                'Estado_servicio' => $this -> post('EstadoS'),
                'TipoServ' => $this -> post('Tipo_Serv'),
                'EstadoS'=> $this->post('EstadoS')
                ]);

            $v -> rule('required', [
                'EstadoS',
                'TipoServ',
                'Estado_servicio'

            ]) -> message('El campo {field} es requerido.');
        }

        if ($paso==4)
        {
            $v = new Valitron\Validator([
                'Estado_servicio' => $this -> post('EstadoS'),
                'TipoServ' => $this -> post('Tipo_Serv'),
                'EstadoS'=> $this->post('EstadoS'),
                'Tareas' => $this->post('Observaciones'),
            ]);

            $v -> rule('required', [
                'EstadoS',
                'TipoServ',
                'Estado_servicio',
                'Tareas'
            ]) -> message('El campo {field} es requerido.');
        }

        if (!$v -> validate()) {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function Validacion()
    {
        $Id = $this -> post('IdServicio');
        if ($Id>0)
        {
            $v = new Valitron\Validator([
                'TipoServ' => $this -> post('Tipo_Serv'),
                'Estado_servicio' => $this -> post('EstadoS'),
                'Fecha_Inicio'=> $this->post('Fecha_I'),
                'Fecha_Fin' => $this->post('Fecha_F'),
                'Distancia'=> $this->post('Distancia'),
                'Tareas' => $this->post('Observaciones'),
                'Direccion'=> $this->post('Direccion'),
                'Cliente'=> $this->post('IdCliente'),
                'Sucursal_cliente'=> $this->post('IdClienteS'),
                'EstadoS'=> $this->post('EstadoS'),
                'Velocidad'=> $this->post('Velocidad'),
                'Trabajadores'=> $this->post('Trabajadores')
            ]);

            $v -> rule('required', [
                'TipoServ',
                'Estado_servicio',
                'Fecha_Inicio',
                'Fecha_Fin',
                'Distancia',
                'Tareas',
                'Direccion',
                'Cliente',
                'Sucursal_cliente',
                'EstadoS',
                'Velocidad'
            ]) -> message('El campo {field} es requerido.');
        }
        else
        {
            $v = new Valitron\Validator([

                'TipoServ' => $this -> post('Tipo_Serv'),
                'Fecha_Inicio'=> $this->post('Fecha_I'),
                'Fecha_Fin' => $this->post('Fecha_F'),
                'Distancia'=> $this->post('Distancia'),
                'Tareas' => $this->post('Observaciones'),
                'Direccion'=> $this->post('Direccion'),
                'Cliente'=> $this->post('IdCliente'),
                'Sucursal_cliente'=> $this->post('IdClienteS'),
                'Velocidad'=> $this->post('Velocidad'),
                'Trabajadores'=> $this->post('Trabajadores')
            ]);

            $v -> rule('required', [
                'TipoServ',
                'Fecha_Inicio',
                'Fecha_Fin',
                'Distancia',
                'Tareas',
                'Direccion',
                'Cliente',
                'Sucursal_cliente',
                'Velocidad'
            ]) -> message('El campo {field} es requerido.');
        }
        return $v;
    }

    public function Add_post()
    {
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $Id = $this -> post('IdServicio');
        $validacion='';

        $v = $this->Validacion();
        $v -> rule('array', [
            'Trabajadores'
        ]) -> message('El campo {field} debe ser un arreglo.');

        $v -> rule('date', [
            'Fecha_Inicio',
            'Fecha_Fin',
        ]) -> message('El campo {field} no es una fecha valida.');

        $v -> rule('numeric', [
            'Distancia',
            'Velocidad',
        ]) -> message('El campo {field} no tiene un formato de numero valido.');


        if ($v -> validate())
        {
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
            $IdUsuario= $this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];

            $objServicio  = new Mservicio();
            $objServicio ->IdServicio= $Id;
            $objServicio ->Cliente = $this->post('Cliente');
            $objServicio ->Econtacto = $this->post('Econtacto');
            $objServicio ->Distancia= str_replace(',','',$this->post('Distancia'));
            $objServicio ->Velocidad= str_replace(',','',$this->post('Velocidad'));
            $objServicio ->Contacto= $this->post('Contacto');
            $objServicio ->Tipo_Serv = $this->post('Tipo_Serv');//id del tipo de servicio
            $objServicio ->Personal = $this->post('Personal');//guardar aqui al responsable
            $objServicio ->vehiculo='';
            $objServicio ->Hora_I = '';
            $objServicio ->Hora_F= '';

            $objServicio ->Observaciones= $this->post('Observaciones');
            $objServicio ->Materiales = $this->post('Materiales');
            $objServicio ->IdSucursal = $IdSucursal;
            $objServicio ->RegEstatus="A";
            $objServicio ->Direccion= $this->post('Direccion');
            $objServicio ->IdVehiculo = 0;
            $objServicio ->IdCliente= $this->post('IdCliente');
            $objServicio ->IdClienteS= $this->post('IdClienteS');

            $objServicio ->EquiposD= str_replace(',','',$this->post('EquiposD'));
            $objServicio ->MaterialesD= str_replace(',','',$this->post('MaterialesD'));
            $objServicio ->ViaticosD= str_replace(',','',$this->post('ViaticosD'));
            $objServicio ->ContratistasD =str_replace(',','', $this->post('ContratistasD'));
            $objServicio ->BurdenTotal= str_replace(',','',$this->post('BurdenTotal'));
            $objServicio ->ManoObraT= str_replace(',','',$this->post('ManoObraT'));
            $objServicio ->CostoV= str_replace(',','',$this->post('CostoV'));
            $objServicio ->ComentarioFin = $this->post('ComentarioFin');
            $objServicio ->IdContrato= $this->post('IdContrato');
            $objServicio ->NumContrato= $this->post('NumContrato');
            $objServicio ->Factura= $this->post('Factura');
            $objServicio->FechaMod = date('Y-m-d H:i:s');

            $oMnumcontrato= new Mnumcontrato();
            $oMnumcontrato->IdContrato=$this->post('IdContrato');
            $datacontrato= $oMnumcontrato->get_recovery();

            if ($datacontrato['status'])
            {
                $objServicio ->IdContrato= $datacontrato['data']->IdContrato;
                $objServicio ->NumContrato= $datacontrato['data']->NumeroC;
            }

            $DetalleArreglo = $this->post('Trabajadores');
            $DetalleVehiculo = $this->post('Vehiculos');
            $FechasServicios = $this->post('FechasHoras');

            if($objServicio->IdServicio == 0)
            {
                $Folio=  get_Folio($IdSucursal,'DESPACHO');
                $IdFolio= $Folio['IdFolio'];
                $Numero= $Folio['Numero'];
                $NumeroAntiguo= $Folio['NumeroAntiguo'];
                $objServicio ->Folio= $Folio['Folio'];
                $objServicio->EstadoS='ABIERTA';

                $Id=0;
                $KeyReferences=0;
                $FechaInicioFin='';

                if ($Numero>$NumeroAntiguo)
                {
                    if (count($FechasServicios)>0)
                    {
                        if (count($FechasServicios)>1)
                        {
                            $contad= count($FechasServicios)-1;
                            $FechaInicioFin = $FechasServicios[0]['Fecha'].'-'.$FechasServicios[$contad]['Fecha'];
                        }
                        else
                        {
                            $FechaInicioFin = $FechasServicios[0]['Fecha'] ;
                        }
                    }

                    foreach ( $FechasServicios as $Fechas)
                    {
                        $objServicio->Fecha_I=dateformato($Fechas['Fecha']);
                        $objServicio->Fecha_F=dateformato($Fechas['Fecha']);
                        $objServicio->FechaInicioFin=$FechaInicioFin;
                        $objServicio->KeyReferences=$KeyReferences;
                        $Id =  $objServicio->insert();

                        if ($KeyReferences==0)
                        {
                            $KeyReferences=$Id;
                        }

                        foreach ($DetalleArreglo as $elemento)
                        {
                            $IdTrabajador= $elemento['IdTrabajador'];
                            //insert Trabajadores
                            if ($Id > 0) {
                                $oMfechaservicio= new Mfechaservicio();
                                $oMfechaservicio->IdServicio=$Id;
                                $oMfechaservicio->FechaInicio=dateformato($Fechas['Fecha']);
                                $oMfechaservicio->HoraInicio=$Fechas['HoraI'];
                                $oMfechaservicio->HoraFin=$Fechas['HoraF'];
                                $oMfechaservicio->IdTrabajador=$IdTrabajador;
                                $oMfechaservicio->Comentario='ABIERTA';
                                $oMfechaservicio->insert();


                                $oMtokenuser=new Mtrabajador();
                                $oMtokenuser->IdTrabajador=$IdTrabajador;
                                $oMtokenuser->get_TrabajadorTkn();
                                $TokenU=$oMtokenuser->Token;

                                if($TokenU != ''){
                                    //$oPushapp=new Pushapp();
                                    //$resultnoti=$oPushapp->send_notification("Tiene una nuevo servicio \n ".date('d-m-Y H:i:s'),'Folio :#'.$Folio,$TokenU,$Id,'Servicio');
                                }
                            }
                        }

                        //Insert Vehiculos
                        if ($Id > 0) {
                            foreach ($DetalleVehiculo as $elemento)
                            {
                                $IdVehiculo= $elemento['IdVehiculo'];
                                $oMvehiculoservicio= new Mvehiculoservicio();
                                $oMvehiculoservicio->IdServicio=$Id;
                                $oMvehiculoservicio->FechaInicio=dateformato($Fechas['Fecha']);
                                $oMvehiculoservicio->HoraInicio=$Fechas['HoraI'];
                                $oMvehiculoservicio->HoraFin=$Fechas['HoraF'];
                                $oMvehiculoservicio->IdVehiculo=$IdVehiculo;
                                $oMvehiculoservicio->insert();
                            }
                        }
                        //Para notificar al Tecnico
                    }
                }

                if ($Id > 0) {

                    //Validar que se guarde el costo Vehiculo y Mano de Obra
                    $valores= $this->CalcularValores($Id,$objServicio->Distancia,$objServicio->Velocidad);

                    $ObjValores = new Mservicio();
                    $ObjValores ->IdServicio=$Id;
                    $ObjValores ->ManoObraT=$valores['CostoMO'];
                    $ObjValores ->CostoV=$valores['CostoVH'];
                    $ObjValores ->BurdenTotal=$valores['Burden'];
                    $ObjValores ->UpdateValores();

                    get_update_folio($IdFolio,$Numero);

                    $resmail =  $this->sendmailservices($KeyReferences,$IdEmpresa,$IdSucursal);

                        return $this -> set_response([
                            'status' => true,
                            'message' => 'Se ha agregado correctamente.',
                            'Mail' => $resmail
                        ], REST_Controller:: HTTP_CREATED);
                } else {
                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }

            }
            else // ACTUALIZAR
            {
                $objServicio ->EstadoS= $this->post('EstadoS');
                $objServicio->Fecha_I=dateformato($this->post('Fecha_I'));
                $objServicio->Fecha_F=dateformato($this->post('Fecha_F'));

                $valores= $this->CalcularValores($objServicio->IdServicio,$objServicio->Distancia,$objServicio->Velocidad);

                $objServicio ->ManoObraT=$valores['CostoMO'];
                $objServicio ->CostoV=$valores['CostoVH'];
                $objServicio ->BurdenTotal=$valores['Burden'];

                if ($objServicio-> update()) {

					$oMfechaservicio = new Mfechaservicio();
					$oMfechaservicio->IdServicio = $objServicio->IdServicio;
					$oMfechaservicio->delete();

					$oMvehiculoservicio = new Mvehiculoservicio();
					$oMvehiculoservicio->IdServicio = $objServicio->IdServicio;
					$oMvehiculoservicio->delete();

					foreach ($FechasServicios as $Fechas) {

						foreach ($DetalleArreglo as $elemento) {
							$IdTrabajador = $elemento['IdTrabajador'];
							//insert Trabajadores
							$oMfechaservicio = new Mfechaservicio();
							$oMfechaservicio->IdServicio = $objServicio->IdServicio;
							$oMfechaservicio->FechaInicio = dateformato($Fechas['Fecha']);
							$oMfechaservicio->HoraInicio = $Fechas['HoraI'];
							$oMfechaservicio->HoraFin = $Fechas['HoraF'];
							$oMfechaservicio->IdTrabajador = $IdTrabajador;
							$oMfechaservicio->Comentario = 'ABIERTA';
							$oMfechaservicio->insert();
						}

						//Insert Vehiculos
						foreach ($DetalleVehiculo as $elemento) {
							$IdVehiculo = $elemento['IdVehiculo'];
							$oMvehiculoservicio = new Mvehiculoservicio();
							$oMvehiculoservicio->IdServicio = $objServicio->IdServicio;
							$oMvehiculoservicio->FechaInicio = dateformato($Fechas['Fecha']);
							$oMvehiculoservicio->HoraInicio = $Fechas['HoraI'];
							$oMvehiculoservicio->HoraFin = $Fechas['HoraF'];
							$oMvehiculoservicio->IdVehiculo = $IdVehiculo;
							$oMvehiculoservicio->insert();
						}
					}

					#Insertamos orden de compra
					$BurdenTotal = str_replace(',', '', $this->post('BurdenTotal'));
					$ManoObraT = str_replace(',', '', $this->post('ManoObraT'));
					$CostoV = str_replace(',', '', $this->post('CostoV'));
					#se crea orden de compra en spen plan
					if ($this->post('EstadoS') == 'CERRADA') {
						$this->add_oc_spendplan($IdSucursal, $IdUsuario, $objServicio->IdServicio, $this->post('IdContrato'), $ManoObraT, $CostoV, $BurdenTotal);
					}

					$response = $objServicio->get_servicio();
					$data['servicio'] = $response['data'];

					$resmail = 'unSended';

					if ( $this->post('Enviar') != false ) {
						$resp =  $this->sendmailservices($this->post('LLave'),$IdEmpresa,$IdSucursal);
						$resmail = 'sended';
						///echo 'Enviando mail...';
					}

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'Mail' => $resmail,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                }
                else
                {

                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }
        else
        {
            $data['errores'] = $v->errors();

            return $this->set_response([
            'status' => false,
            'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function sendmailservices($Id,$IdEmpresa,$IdSucursal)
    {
        $resmail ='';
        //Lista de trabajadores
        $oMfechaservicio  = new Mfechaservicio();
        $oMfechaservicio ->IdServicio= $Id;
        $rowtrab= $oMfechaservicio->get_listtrabajador();

        $objServicio =new Mservicio();
        $objServicio->IdServicio=$Id;
        $dataservicio=  $objServicio->get_servicio();

        $oMempresa= new Mempresa();
        $oMempresa->IdEmpresa=$IdEmpresa;
        $dataempresa= $oMempresa->get_empresa();

        $oMsucursal= new Msucursal();
        $oMsucursal->IdSucursal=$IdSucursal;
        $datasucursal= $oMsucursal->get_sucursal();


        $ObjCliente= new Mclientes();
        $ObjCliente->IdCliente=$dataservicio['data']->IdCliente;
        $datacliente= $ObjCliente->get_clientes();
        $NombreCliente = '';
        if($datacliente['status'])
        {
            $NombreCliente = $datacliente['data']->Nombre;
        }

        $ObjClienteSuc= new Mclientesucursal();
        $ObjClienteSuc->IdClienteS=$dataservicio['data']->IdClienteS;
        $dataclienteSuc= $ObjClienteSuc->get_cliente();
        $NombreClienteSuc = '';
        if($datacliente['status'])
        {
            $NombreClienteSuc = $dataclienteSuc['data']->Nombre;
        }

        $ObjTiposer= new Mtiposervicio();
        $ObjTiposer->IdTipoSer=$dataservicio['data']->Tipo_Serv;
        $datatiposerv= $ObjTiposer->get_tiposervicio();
        $TipoServicio = '';

        if($datacliente['status'])
        {
            $TipoServicio = $datatiposerv['data']->Concepto;
        }


        $oMfechaservicio=new Mfechaservicio();
        $oMfechaservicio->IdServicio=$Id;
        $responsefecha=$oMfechaservicio->get_recovery();

        $dataview['Fecha']=$responsefecha['data'];
        $dataview['empresa']=$dataempresa;
        $dataview['Trabajadores']=$rowtrab;
        $dataview['datos']=$dataservicio;
        $dataview['NombreCliente']=$NombreCliente;
        $dataview['NombreClienteSuc']=$NombreClienteSuc;
        $dataview['TipoServicio']=$TipoServicio;

        $dataview['RutaTrab']=base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';

        $objempresa = new Mempresa();
        $objempresa->IdEmpresa = $IdEmpresa;
        $dataEmpresa = $objempresa->get_empresa();

        $Normal = 1;

        if($dataEmpresa['status'])
        {
            if($dataEmpresa['data']->Logo != '')
            {
                $Logo = base_url().'assets/files/logo_empresa/'.$dataEmpresa['data']->Logo;
            }else{
                $Logo = $dataEmpresa['data']->Imagen;
                $Normal = 2;
            }
        }

        if($Logo == '')
        {
            $Logo = 'https://lugarcreativo.mx/email/logo_des.gif';
        }

        $dataview['Logo']=$Logo;
        $dataview['Normal']=$Normal;

        $contador = 0;

		$contador = 0;
		$existPhoto = false;

		foreach($rowtrab as $element)
		{
			$rowtrab[$contador]->Responsable = false;

			if($dataservicio['data']->Personal == $element->IdTrabajador)
			{
				$rowtrab[$contador]->Responsable = true;
			}

			if($element->Foto2 != '')
			{
				$rowtrab[$contador]->FotoNormal = 1;
				$rowtrab[$contador]->FotoT = base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/'.$element->Foto2;
				$existPhoto = true;
			}
			else
			{

				$rowtrab[$contador]->FotoNormal = 2;
				$rowtrab[$contador]->FotoT = $element->Imagen;
				//$isImgBase = true;
			}
			//echo $_SERVER['DOCUMENT_ROOT'].' || ';
			//'/var/www/vhosts/desprosoft.online/httpdocs/'
			///var_dump($rowtrab[$contador]->FotoT);

			//if(!file_exists($rowtrab[$contador]->FotoT))
			if(!$existPhoto)
			{
				$rowtrab[$contador]->FotoT = 'https://desprosoft.online/services/assets/foto.png';
			}


			$contador ++;
		}

        //$vista=$this->load->view('catalogos/correo/servicio.php',$dataview,TRUE);
        $vista=$this->load->view('catalogos/correo/ProgramService.php',$dataview,TRUE);

        if ($this->post('Enviar'))
        {
            $correos = array();
            $Para= $this->post('Para');
            foreach ($Para as $mail)
            {
                array_push($correos,$mail['text']);
            }
            //$Ruta1 = $this->CrearPdfMail($Id);
            $Files = array();
            //array_push($Files,$Ruta1);

            if (count($correos)>0)
            {
                $oMail=new Mail();
                $oMail->To=$correos;
                $oMail->Subject= $dataEmpresa['data']->Nombre.' - Orden de servicio';
                $oMail->Message=$vista;
                $oMail->files=$Files;
                $resmail=$oMail->SendEmail();
                //unlink($Ruta1);
            }
        }

        return $resmail;
    }

    /*
        Funcion para agregar orden de compra del spend plan
    */
    function add_oc_spendplan($IdSucursal,$IdUsuario,$IdServicio,$IdContrato,$ManoObra,$CostoVehiculo,$Burden){
        //Mspend_ordenc
        //Mspend_proyecto
        $arrayconceptos=array("Mano de Obra","Vehículos","Burden");
        $arraymontos=array($ManoObra,$CostoVehiculo,$Burden);

        #servicio
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $resservicio=$oMservicio->get_servicio();
        if($resservicio['status']){
            $FolioServicio=$resservicio['data']->Folio;

            if($IdContrato>0){
                #Contratos
                $oMnumcontrato=new Mnumcontrato();
                $oMnumcontrato->IdContrato=$IdContrato;
                $responsecontrato=$oMnumcontrato->get_recovery();

                if($responsecontrato['status']){

                    $IdProyecto=$responsecontrato['data']->IdProyectoSpend;

                    //Eliminamos todos los proyectos
                    $Mspend_ordenc=new Mspend_ordenc();
                    $Mspend_ordenc->IdServicio=$IdServicio;
                    $Mspend_ordenc->IdProyecto=$IdProyecto;
                    $Mspend_ordenc->deletexservicio();

                    #proyecto spendpaln
                    $Mspend_proyecto=new Mspend_proyecto();
                    $Mspend_proyecto->IdProyecto=$IdProyecto;
                    $response=$Mspend_proyecto->get_recovery();
                    if($response['status']){
                        $IdProyecto=$response['data']->IdProyecto;
                        $contador=0;
                        foreach($arrayconceptos as $element){
                            $Monto=$arraymontos[$contador];
                            $Mspend_ordenc=new Mspend_ordenc();
                            $Mspend_ordenc->IdServicio=$IdServicio;
                            $Mspend_ordenc->IdProyecto=$IdProyecto;
                            $Mspend_ordenc->IdSucursal=$IdSucursal;
                            $Mspend_ordenc->IdUsuario=$IdUsuario;
                            $Mspend_ordenc->FechaReg=date('Y-m-d H:i:s');
                            $Mspend_ordenc->FechaMod=date('Y-m-d H:i:s');
                            $Mspend_ordenc->RegEstatus='A';
                            $Mspend_ordenc->Concepto=$element;
                            $Mspend_ordenc->Monto=$Monto;
                            $Mspend_ordenc->Descripcion=$FolioServicio;
                            $Mspend_ordenc->FolioArchivo=$FolioServicio;
                            $responseoc=$Mspend_ordenc->get_recoveryxservicio();
                            if($responseoc['status']){#modifica
                                $Mspend_ordenc->IdOrdenCompra=$responseoc['data']->IdOrdenCompra;
                                $Mspend_ordenc->update();
                            }else {#Inserta
                                $Mspend_ordenc->insert();
                            }
                            $contador ++;
                        }
                    }
                }
            }else{//si no hay contrato eliminamos donde IdServicio >0
                //Eliminamos todos los proyectos
                $Mspend_ordenc=new Mspend_ordenc();
                $Mspend_ordenc->IdServicio=$IdServicio;
                $Mspend_ordenc->deletexservicio();
            }
        }
    }

    public function Despacho_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa']; //27;

        setTimeZoneEmpresa($IdSucursal);
        $FechaBusqueda = $this->get('FechaBusqueda');

        if($FechaBusqueda=='')
        {
            $FechaBusqueda = date('Y-m-d');
        }

        // OBTENEMOS LAS HORAS LABORALES DE CADA SUCURSAL
        $objHoraslaborales = new Mhoraslaborales();
        $TokenSuc = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal']; //41;
        $objHoraslaborales->IdSucursal = $TokenSuc;
        $response = $objHoraslaborales->get_horaslaborales();

        // OBTENEMOS LA HORA INICIAL Y LA FINAL
        $Hora_I = explode(':',$response['data']->Hora_I);
        $Hora_F = $response['data']->Hora_F;
        $horaactual = date('H:i');

        $HoraI = $Hora_I[0];
        $HoraMin = $Hora_I[1];

        //PARA BUSQUEDA DE SERVICIOS
        $arrHorasReales = array();
        //VISTA
        $arrhoradata = array();

        ##Este arreglo busca por 24 hroas
        array_push($arrHorasReales,$response['data']->Hora_I);
        $classhora = $this->get_class_hora($response['data']->Hora_I,$horaactual);


        $arrhora = array("hora"=>$response['data']->Hora_I,'class'=>$classhora);
        array_push($arrhoradata,$arrhora);

        $HoraFinal = $Hora_F;
        for($i=0;$i<=90;$i++)
        {
            if($HoraMin==30){
                $HoraI ++;
                $HoraMin='00';
            }
            else{
                $HoraMin ='30';
            }
            $HoraFinal = $HoraI.':'.$HoraMin;
            $HoraFinalReal = $HoraI.':'.$HoraMin;

            if($HoraFinal>=24){
                //$HoraFinal='00:00';
                $HoraFinal='24:00';
            }

            $classhora = $this->get_class_hora($HoraFinal,$horaactual);
            $arrhora = array("hora"=>$HoraFinal,'class'=>$classhora);
            array_push($arrhoradata,$arrhora);
            array_push($arrHorasReales,$HoraFinalReal);

            if($HoraFinal==$Hora_F){
                break;
            }

            if($HoraFinal=='24:00'){
                break;
            }
        }

        // OBTENEMOS TODOS LOS SERVICIOS DEL
        $oMservicio = new Mservicio();
        $oMservicio->IdSucursal = $IdSucursal;
        $listagant = $oMservicio->get_listGetgant($arrHorasReales,$FechaBusqueda);

        /* echo '<pre>';
        print_r($listagant[2]);
        echo '</pre>'; */

        $arrayServicios = [];
        foreach($listagant as $element)
        {
            $IdTrabajador = $element->IdTrabajador;
            $IdUser = $element->IdUsuario;
            $Trabajador = $element->Nombre;
            $Foto2 = $element->Foto2;
            $arraytrabajos = [];
            $contadorTrabajos = 0;//cuanto trabajos de 30 minutos hara el tecnico

            //buscar si tiene mensajes no vistos
            $message = new Mdetallemensaje();
            $message->IdContacto = $element->IdUsuario;
            $valuemessage = $message->countmessage();

            $messages = 0;

            if($valuemessage['status']){
                $messages = intval($valuemessage['data']->messages);
            }
            //fin buscar si tiene mensajes no vistos

            $bndContOne = false;
            $ContadorItem=0;

            /* echo '<pre>';
            print_r($arrHorasReales);
            echo '</pre>'; */

            $arrayAnt = array(
                'pintar' => '',
                'color' => '',
                'opacidad' => '',
                'IdServicio' => 0,
                'Folio' => '',
                'Cliente' => '',
                'Sucursal' => '',
                'IdServicioItem' => '',
                'EstatusItem'=> '',
                'HoraInicioItem' => '',
                'HoraFinItem' => '',
            );

            foreach($arrHorasReales as $ehora)
            {
                //echo $ehora.'<br>';
                //echo '----<br>';
                $HoraBusqueda = $ehora.':00';
                $IdServicioItem = 'item'.$ContadorItem;
                $HoraInicioItem = 'HoraInicio'.$ContadorItem;
                $HoraFinItem = 'HoraFin'.$ContadorItem;
                $ColorItem = 'Color'.$ContadorItem;
                $EstatusItem ='EstadoS'.$ContadorItem;
                $ClienteItem ='Cliente'.$ContadorItem;
                $SucursalItem ='Sucursal'.$ContadorItem;
                $FolioItem = 'Folio'.$ContadorItem;

                /* echo 'Actual '.$HoraBusqueda.'<br>';
                echo 'inicial '.$element->$HoraInicioItem.'<br>';
                echo 'final '.$element->$HoraFinItem.'<br>';
                echo 'anterior '.$arrayAnt['HoraFinItem'].'<br>'; */

                $IdServicio = 0;
                $class = '';
                $opacidad = '';
                $Pintar = false;
                $color = '';

                if($element->$IdServicioItem>0)
                {
                    $Pintar = true;
                }
                // USAMOS LAS HORAS PARA DETERMINAR QUE CLASE SE PINTA
                $HoraIteracion = strtotime($HoraBusqueda);
                $HoraIni = strtotime($element->$HoraInicioItem);
                $HoraFin = strtotime($element->$HoraFinItem);


                $IdServAnt = $arrayAnt['IdServicio'];
                $HoraFinAnt = strtotime($arrayAnt['HoraFinItem']);
                $existAnt = 'NO';

                if($IdServAnt>0)
                {
                    if($IdServAnt!=$element->$IdServicioItem || $HoraBusqueda == $Hora_F.':00')
                    {
                        //echo 'entraaa here';
                        if($HoraIteracion == $HoraFinAnt)
                        {
                            $existAnt = 'SI';
                            //echo 'cierra<br>';
                        }
                    }
                }

                // PINTA INICIO DIV
                if($HoraIteracion == $HoraIni)
                {
                    $class='barra-02';
                    //echo 'abre<br>';
                }
                // PINTA EL CUERPO DEL DIV
                else if($HoraIteracion>$HoraIni && $HoraIteracion<$HoraFin)
                {
                    $class='barra-04';
                    //echo 'cuerpo<br>';
                }


                $IdServicio = $element-> $IdServicioItem;
                $color = 'background-color:'. $element->$ColorItem;
                if($element-> $EstatusItem =='REALIZADA' || $element-> $EstatusItem =='CERRADA'){
                    $opacidad='opacity: 0.4; ';
                }

                // SE LLENA CON EL ITEM ACTUAL
                $arrayreg = array(
                    'pintar'    => $Pintar,
                    'color'     => $opacidad.$color,
                    'class'     => $class,
                    'IdServicio'=> $IdServicio,
                    'Folio'     => $element->$FolioItem,
                    'Cliente'   => $element->$ClienteItem,
                    'Sucursal'  => $element->$SucursalItem,
                    'Hora'      => $HoraBusqueda,
                );


                $bndPosicion = 0;
                $arrTds = array();
                // SI EXISTE EL ANTERIOR ENTONCES EL ACTUAL SE PONDRA EN LA POSICION 1 DEL ARREGLO
                if($existAnt == 'SI')
                {
                    $bndPosicion = 1;

                    $arrayreg2 = array(
                        'pintar'    => true,
                        'color'     => $arrayAnt['opacidad'].$arrayAnt['color'],
                        'class'     => 'barra-03',
                        'IdServicio'=> $IdServAnt,
                        'Folio'     => $arrayAnt['Folio'],
                        'Cliente'   => $arrayAnt['Cliente'],
                        'Sucursal'  => $arrayAnt['Sucursal']
                    );
                    $arrTds['items'][0] = $arrayreg2;
                    //echo 'entra 2<br>';
                }

                //echo 'entra 1<br>';
                $arrTds['items'][$bndPosicion] = $arrayreg;
                array_push($arraytrabajos,$arrTds);


                /*if($contadorTrabajos>0){
                    $contadorTrabajos --;//
                }
                */
                // LLENAMOS EL ARRAY ANTERIOR CON EL DATA ACTUAL PARA COMPARAR EN LA SIGUIENTE VUELTA
                $arrayAnt['pintar'] = $Pintar;
                $arrayAnt['color'] = $color;
                $arrayAnt['opacidad'] = $opacidad;
                $arrayAnt['IdServicio'] = $IdServicio;
                $arrayAnt['Folio'] = $element->$FolioItem;
                $arrayAnt['Cliente'] = $element->$ClienteItem;
                $arrayAnt['Sucursal'] = $element->$SucursalItem;
                $arrayAnt['IdServicioItem'] =  $element->$IdServicioItem;
                $arrayAnt['EstatusItem'] =  $element->$EstatusItem;
                $arrayAnt['HoraInicioItem'] = $element->$HoraInicioItem;
                $arrayAnt['HoraFinItem'] = $element->$HoraFinItem;
                $ContadorItem ++;
            }


            /* echo '<pre>';
            print_r($arraytrabajos);
            echo '</pre>'; */

            $arrayServicios[] = array(
                'IdUser'    => $IdUser,
                'IdTra'     => $IdTrabajador,
                'Trabajador'=> $Trabajador,
                'Foto'      => $Foto2,
                'Servicios' => $arraytrabajos,
                'message'   => $messages
            );
            //break; //eliminar
        }


        $data['servicios'] = $arrayServicios;
        $data['horaactual'] = $horaactual;
        $data['horaslaborales'] = $arrhoradata;
        $data['ruta'] = base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';
        //$data['despacho'] =$rows;


        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function return_counttrabajos($HI,$HF)
    {
        //devuelve cuantos horas de 30 min hara el tecnico
        $Hora_I = explode(':',$HI);
        $Hora_F = explode(':',$HF);
        $Hora_F2=$Hora_F[0].':'.$Hora_F[1];

        $HoraI=$Hora_I[0];
        $HoraMin=$Hora_I[1];
        $HoraFinal=$Hora_F;

        $contador=1;
        $HoraFinal='';

        for($i=0;$i<=70;$i++){
            if(strtotime($HoraFinal)>=strtotime($Hora_F2)){
                break;
            }else{
                $contador ++;
            }

            if($HoraMin==30){
                $HoraI ++;
                $HoraMin='00';
            }else{
                $HoraMin ='30';
            }
            $HoraIConvert=$HoraI;
            if($HoraI<10 && $HoraI>0){
                $HoraIConvert='0'.$HoraI;
            }

            $HoraFinal=$HoraIConvert.':'.$HoraMin;

            if($HoraFinal>=24){
                //echo '('.$HoraFinal.'<---->'.$Hora_F2.')<br/>';
                //$HoraFinal='00:00';
            }
        }

        return $contador;
    }

    public function get_class_hora($hora,$horaactual)
    {
        //devuelve classe de la hora actual (indica la fleccha en las horas en la tabla)

        $Hora_I=explode(':',$hora);

        $HoraI=$Hora_I[0];
        $HoraMin=$Hora_I[1];

        if($HoraMin==30){
            $HoraI ++;
            $HoraMin='00';
        }else{
            $HoraMin ='30';
        }

        if($HoraI<10){
            $HoraI=$HoraI;
        }

        $HoraFinal=$HoraI.':'.$HoraMin;

        if($HoraFinal>=24){
            $HoraFinal='00:00';
        }
        $class='';

         $horaactual = strtotime($horaactual);
         $hora = strtotime($hora);
         $HoraFinal = strtotime($HoraFinal);

        if($horaactual>=$hora && $horaactual<$HoraFinal){
            $class='bg-table';
        }

        //echo "( $horaactual>=$hora   Y   $horaactual<$HoraFinal ==$class) ";

        return $class;
    }

    #region obtner fechas
    public function fechasDinamicas_get()
    {
        //devuelve classe de la hora actual (indica la fleccha en las horas en la tabla)
        // Valid Token
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Fecha_Inicio'=> $this->get('FechaI'),
            'Fecha_Fin' => $this->get('FechaF')
        ]);

        $v -> rule('required', [
            'Fecha_Inicio',
            'Fecha_Fin'

        ])-> message('El campo {field} es requerido.');

        $v -> rule('date', [
            'Fecha_Inicio',
            'Fecha_Fin',
        ]) -> message('El campo {field} no es una fecha valida.');

        if ($v -> validate())
        {
            $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            setTimeZoneEmpresa($IdSucursal);

            $FServicio= array ([
                'Fecha'=>'',
                'HoraI'=>'',
                'HoraF'=>'']
            );

            /* $FechaI = date('Y-m-d', strtotime($this->get('FechaI')));
            $FechaF = date('Y-m-d', strtotime($this->get('FechaF'))); */

            // SE REEMPLAZO LA ESTRUCTURA DE FECHAS DE ARRIBA POR Q ARROJABA MAL EL DIA
            $RecibeFI = $this->get('FechaI');
            $RecibeFF = $this->get('FechaF');

            $F1 = new DateTime($RecibeFI);
            $FechaI = $F1->format('Y-m-d');

            $F2 = new DateTime($RecibeFF);
            $FechaF = $F2->format('Y-m-d');


            $FechaI2 = new DateTime($FechaI);
            $FechaF2 = new DateTime($FechaF);

            $Numero = $FechaI2->diff($FechaF2)->format("%r%a");
            $ArregloFechas = array();
            $AHoraI = array("6","7");
            $AhoraFin = array("6","7");

            if ($Numero>=0)
            {
                $FechaSer='';
                $HoraIS = '';
                $HoraFS = '';

                if (!empty($this->get('IdServicio')))
                {
                    //Este solo busca un servcio si se desea buscar mas de un servicio se debera de  enviar la conulta por cada fecha
                    //y la busqueda se hara por fechsaservicios y por fecha
                    $oMservicio = new Mservicio();
                    $oMservicio->IdServicio=$this->get('IdServicio');
                    $respuesta= $oMservicio->get_recoveryDespachoOcupado();

                    if ($respuesta['status'])
                    {
                        $FechaSer =$respuesta['data']->FechaInicio;
                        $HoraIS= substr($respuesta['data']->HoraInicio,0, -3);
                        $HoraFS= substr($respuesta['data']->HoraFin,0, -3);
                    }
                }

                for($var=0; $var<=$Numero; $var++)
                {
                    $month = date("m",strtotime($FechaI));
                    $monthNum  = $month;
                    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                    $monthName = strftime('%B', $dateObj->getTimestamp());
                    $NuevaFecha = strtotime ('+'.$var.'day',strtotime ($FechaI)) ;
                    $NuevaFecha = date ('Y-m-d',$NuevaFecha);

                    //Se obtienen las fechas por dia
                    $HoraActual = date("H");
                    $MinutoActual = date("i");

                    $objHoraslaborales = new Mhoraslaborales();
                    $objHoraslaborales->IdSucursal = $IdSucursal;
                    $response = $objHoraslaborales->get_horaslaborales();

                    $Hora_I = explode(':',$response['data']->Hora_I);
                    $Hora_F = $response['data']->Hora_F;

                    $HoraI = $Hora_I[0];
                    $HoraMin = $Hora_I[1];

                    $arrHoras = array();
                    if($this->get('IdServicio')>0 || $NuevaFecha != date('Y-m-d'))
                    {
                        //Este es para la primera hora
                        if(intval($HoraI)<10)
                        {
                            if(intval($HoraI) == 0){
                                $Hora2 = $HoraI.':'.$HoraMin;
                            }else{
                                $Hora2 = '0'.$HoraI.':'.$HoraMin;
                            }
                        }
                        else
                        {
                           $Hora2 = $HoraI.':'.$HoraMin;
                        }
                        array_push($arrHoras,$Hora2);
                    }

                    //print_r($arrHoras);

                    $HoraFinal = $Hora_F;
                    for($i=0; $i<=90; $i++)
                    {
                        //Este for es para el resto de horas
                        if($HoraMin==30){
                            $HoraI++;
                            $HoraMin = '00';
                        }else{
                            $HoraMin ='30';
                        }

                        if (intval($HoraI)<10)
                        {

                           $HoraI ='0'.intval($HoraI);
                        }
                        $HoraFinal = $HoraI.':'.$HoraMin;

                        if($HoraFinal>=24){
                            //$HoraFinal='23:59';
                            //$HoraFinal='00:00';
                            $HoraFinal='24:00';
                        }

                        if(intval($HoraI) >= $HoraActual)
                        {
                            // SI LA HORA ES IGUAL A LA ACTUAL Y EL MINUTO ES MAYOR A LA ACTUAL SE PINTA EN EL COMBO
                            if(intval($HoraI) == $HoraActual && intval($HoraMin) > $MinutoActual)
                            {
                                array_push($arrHoras,$HoraFinal);
                            }

                            // SI LA HORA ES MAYOR A LA ACTUAL SE PINTA EN EL COMBO
                            if(intval($HoraI) > $HoraActual)
                            {
                                array_push($arrHoras,$HoraFinal);
                            }
                            else if($NuevaFecha != date('Y-m-d'))
                            {
                                // SI LA FECHA ES DISTINTA A LA ACTUAL SE PINTAN TODAS LAS HORAS
                                array_push($arrHoras,$HoraFinal);
                            }
                            else if($this->get('IdServicio')>0)
                            {
                                array_push($arrHoras,$HoraFinal);
                            }
                        }
                        else if($this->get('IdServicio')>0 || $NuevaFecha != date('Y-m-d'))
                        {
                            // SINO COMPRUEBA QUE SE ESTA EDITANDO PARA QUE PERMITA MAS HORAS
                            array_push($arrHoras,$HoraFinal);
                        }

                        if($HoraFinal==$Hora_F){
                            break;
                        }
                    }

                    $NFecha= array (
                        'Fecha' => $NuevaFecha,
                        'HoraI' => $HoraIS,
                        'HoraF' => $HoraFS,
                        'Mes'   => substr($monthName, 0, 3),
                        'Dia'   => substr($NuevaFecha, -2, 2),
                        'horaslaborales' => $arrHoras,
                    );
                    array_push($ArregloFechas,$NFecha);
                }
            }

            $data['Fechas']=$ArregloFechas;
            $data['FServicio']=$FServicio;

            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    #endregion
    #region trabajadoresdisponibles

    public function DisponiblesAll_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        // TRAEMOS LA DATA DEL ROL USUARIO APP DE LA SUCURSAL EN TURNO
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        setTimeZoneEmpresa($IdSucursal);

        $IdServicio = $this->post('IdServicio');
        $oMrol = new Mrol();
        $oMrol->Nombre = 'Usuario APP';
        $oMrol->IdSucursal = $IdSucursal;
        $orol=$oMrol->get_recovery();

        // OBTENERMOS EL LISTADO DE LOS TRABAJADORES DE ESA SUCURSAL CON EL ROL DE USUARIO APP
        $oMtrabajador= new Mtrabajador();
        //$oMtrabajador->RegEstatus = 'A';
        $oMtrabajador->IdSucursal = $IdSucursal;
        $oMtrabajador->IdRol = $orol['data']->IdRol;
        $oMtrabajador->IdPerfil = 4;
        $listrabajadores = $oMtrabajador->get_list();

        // OBTENEMOS EL LISTADO DE LOS VEHICULOS DE ESA SUCURSAL
        $RowVehiculos=array();
        $oMvehiculo = new Mvehiculo();
        $oMvehiculo->RegEstatus = 'A';
        $oMvehiculo->IdSucursal = $IdSucursal;
        $rowve = $oMvehiculo->get_list();
        $rowve2 = $oMvehiculo->get_list2();

        foreach ($rowve2 as $vehi ) {
            array_push($RowVehiculos,$vehi);
        }

        $ListaFechas = $this->post('Fechas');
        $RowTrabajadores = array();
        $RowTrabajadorOcupado = array();
        $CountTrab = 0;
        $CountTrabOcupado = 0;

        // ITERAMOS A LOS TRABAJADORES
        foreach($listrabajadores as $trab)
        {
            $RowFechas = array();
            $RowFechasOcupadas = array();
            $Estatus = false;

            foreach($ListaFechas as $element)
            {
                 $HoraInit = $element['HoraI'];
                 $HoraFinat = $element['HoraF'];

                if($HoraInit=='00:00'){
                    $HoraInit='24:00';
                }

                if($HoraFinat=='00:00'){
                    $HoraFinat='24:00';
                }

                // UBUCAMOS EN LOS SERVICIOS SI EL TRABAJADOR ESTA OCUPADO EN ESA FECHA Y HORA
                $oMservicio = new Mservicio();
                $oMservicio->IdTrabajador = $trab->IdTrabajador;
                $oMservicio->Hora_F = $HoraFinat;
                $oMservicio->Hora_I = $HoraInit;
                $oMservicio->Fecha_I = date("Y-m-d", strtotime($element['Fecha']));
                $respuesta = $oMservicio->get_recoveryDespachoOcupado();

                //si es diferente de true esta disponible el tecnico
                if (!$respuesta['status'])
                {
                    $element['Fecha']=dateformatomx($element['Fecha']);
                    array_push($RowFechas,$element);
                    $CountF=true;
                    //echo 'entra 1<br>';
                }
                else
                {
                    //esta ocupado
                    if ($IdServicio>0 && $respuesta['Total']==1 && $IdServicio==$respuesta['data']->IdServicio)
                    {
                        if($respuesta['data']->EstadoS=='CERRADA')
                        {
                            //si id servicio es mayor a 0 es que esta editando y se muestra el ocupado
                            $element['Fecha']=dateformatomx($element['Fecha']);
                            array_push($RowFechasOcupadas,$element);
                        }
                        else
                        {
                            if($trab->RegEstatus != 'B')
                            {
                                //si id servicio es mayor a 0 es que esta editando y se muestra el ocupado
                                $element['Fecha']=dateformatomx($element['Fecha']);
                                array_push($RowFechasOcupadas,$element);
                            }
                        }
                    }
                    $CountF=false;
                }
            }

            if ($CountF)
            {
                if($trab->RegEstatus != 'B')
                {
                    //si es igual a true todos los dias esta disponible y aparece como disponible
                    array_push($RowTrabajadores,$trab);
                    $RowTrabajadores[$CountTrab]->FechasDisponibles=$RowFechas;
                    $RowTrabajadores[$CountTrab]->Encargado=false;
                    $CountTrab ++;
                }
            }

            if (count($RowFechasOcupadas)>0)
            {
                array_push($RowTrabajadorOcupado,$trab);
                $RowTrabajadorOcupado[$CountTrabOcupado]->FechasOcupadas=$RowFechasOcupadas;
                $RowTrabajadorOcupado[$CountTrabOcupado]->Encargado=false;

                $CountTrabOcupado ++;
            }
        }

        
        $CountVehiculos=0;
        $RowFechas=array();
        $RowFechasOcupadas=array();
        $RowVehiculoOcupado=array();
        $IdVehiculoSel='';

        //$rowve trae el get list de Mvehiculo
        foreach ($rowve as $vehiculo )
        {
            $RowFechas=array();
            $RowFechasOcupadas=array();
            $Estatus=false;
            foreach ($ListaFechas as $element)
            {
                 $HoraInit =$element['HoraI'];
                 $HoraFinat =$element['HoraF'];
                if ($HoraInit=='00:00')
                {
                    $HoraInit='24:00';
                }

                if ($HoraFinat=='00:00')
                {
                    $HoraFinat='24:00';
                }

                //El IdVehiculo de Mservicios se iguala al que trae el get list de Mvehiculo
                $oMservicio = new Mservicio();
                $oMservicio->IdVehiculo= $vehiculo->IdVehiculo;
                $oMservicio->IdServicio= $IdServicio;
                $oMservicio->Hora_F=$HoraFinat;
                $oMservicio->Hora_I= $HoraInit;
                $oMservicio->Fecha_I=date("Y-m-d", strtotime($element['Fecha']));
                $respuesta= $oMservicio->get_recoveryVehiculoDespachoOcupado();

                //si es diferente de true esta disponible el vehículo
                $element['Fecha']=dateformatomx($element['Fecha']);
                if (!$respuesta['status'])
                {
                    array_push($RowFechas,$element);
                    $Estatus=true;

                    array_push($RowVehiculos,$vehiculo);
                   
                    $RowVehiculos[$CountVehiculos]->FechasDisponibles=$RowFechas;//AQUÍ ENTRA EL ARRAY DE FECHAS DISPONIBLES. 
                    $CountVehiculos ++;
                }
                else
                {
                    if ($IdServicio>0 && $respuesta['Total']==1 && $IdServicio==$respuesta['data']->IdServicio)
                    {
                        if($respuesta['data']->EstadoS=='CERRADA')
                        {
                            //si id servicio es mayor a 0 es que esta editando y se muestra el ocupado
                            array_push($RowFechasOcupadas,$element);
                            array_push($RowFechas,$element);
                            $IdVehiculoSel = $respuesta['data']->IdVehiculo;
                        }
                        else
                        {
                            if($vehiculo->RegVehiculo !='B')
                            {
                                //si id servicio es mayor a 0 es que esta editando y se muestra el ocupado
                                array_push($RowFechasOcupadas,$element);
                                array_push($RowFechas,$element);
                                $IdVehiculoSel = $respuesta['data']->IdVehiculo;
                            }
                        }
                    }else{
                       
                    }

                    $Estatus=false;
                }
                //$CountF ++;
            }
            // if ($Estatus)
            // {
            //     //Si el RegEstatus es Distinito a B entonces "inserta" lo que trae $vehiculos  en $RowVehiculos
            //     if($vehiculo->RegVehiculo !='B')
            //     {
            //         array_push($RowVehiculos,$vehiculo);
                   
            //         $RowVehiculos[$CountVehiculos]->FechasDisponibles=$RowFechas;//AQUÍ ENTRA EL ARRAY DE FECHAS DISPONIBLES. 
            //         $CountVehiculos ++;
            //     }
            // }
            
            //Si el contador de $RowFechasOcupadas es mayor a 0 entonces "inserta" lo que trae $vehiculos  en $RowVehiculoOcupado
            if (count($RowFechasOcupadas)>0)
            {
                //Solo puede haber un vehiulo por eso esta en 0
                array_push($RowVehiculoOcupado,$vehiculo);
                $RowVehiculoOcupado[0]->FechasOcupadas=$RowFechasOcupadas;
                
            }
        }

       

        $data['Vehiculos']=$RowVehiculos;
        $data['Trabajadores']=$RowTrabajadores;
        $data['TrabajadorOcupado']=$RowTrabajadorOcupado;
        $data['VehiculosOCupado']=$RowVehiculoOcupado;
        $data['IdVehiculo']=$IdVehiculoSel;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    #endregion

    #region Calendario
    #se usa en despacho calendario
    public function listcalendario_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];

        setTimeZoneEmpresa($IdSucursal);

        $IdClienteS=$this->get('IdClienteS');

        $objServicio = new Mservicio();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->IdCliente=$IdCliente;
        $objServicio->IdClienteS=$IdClienteS;

        $rows = $objServicio->get_listcalendar();
        $data['calendar'] =$rows;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    #endregion

      #region Servicios terminados
    public function listServFin_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

       $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


        $FechaBusqueda=$this->get('FechaBusqueda');

        if ($FechaBusqueda=='')
        {
            $FechaBusqueda = date('Y-m-d');
        }

        $objServicio = new Mservicio();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->Fecha_I = $FechaBusqueda;
        $rows = $objServicio->get_listServiciosTerminados();
        $data['finalizados'] =$rows;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
    #endregion

    #region Obtienes los trabajadores del servicio

    public function TrabajadoresxServ_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $oMvehiculoservicio= new Mvehiculoservicio();
        $oMvehiculoservicio->IdServicio=$this->get('IdServicio');
        $rowv= $oMvehiculoservicio->get_list();

        $objServicio = new Mservicio();
        $objServicio ->IdServicio = $this->get('IdServicio');
        $rows = $objServicio->list_trabajadorxservicio();

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $ruta=base_url().'assets/files/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';

        return $this->set_response([
            'status' => true,
            'rows' => $rows,
            'rowsv' => $rowv,
            'ruta' => $ruta,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function SendFiles_post()
    {
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $Mempresa = new Mempresa();
        $Mempresa->IdEmpresa=$IdEmpresa;
        $oempresa = $Mempresa->get_empresa();


        $De=$this->post('De');
        $Mensaje=$this->post('Mensaje');
        $Para=$this->post('Para');
        $IdServicio=$this->post('IdServicio');
        $correos = array();

        foreach ($Para as $mail)
        {
            array_push($correos,$mail['text']);
        }

        $objServicio = new Mservicio();
        $objServicio->IdServicio=$IdServicio;
        $oservicio = $objServicio->get_servicio();

        $Asunto = 'Reporte de servicio';

        if($oservicio['status']){
            $Asunto = $oempresa['data']->Nombre.' Reporte de servicio '.$oservicio['data']->Client.' , Folio: '.$oservicio['data']->Folio;
        }

        $oMail=new Mail();
        $oMail->Empresa=$oempresa['data']->Nombre;
        $oMail->To=$correos;
        $oMail->Subject= $Asunto; //'Servicios';
        $oMail->Message=$Mensaje;
        $oMail->From=$De;
        $Ruta1 = $this->CrearPdfServicio($IdServicio);  // OK 29092021
        $Files = array();
        array_push($Files,$Ruta1);

        if ($this->post('Img')==true)
        {
            $Ruta2 =$this->CrearPdfEvidencia($IdServicio); // OK 29092021
            array_push($Files,$Ruta2);
        }

        $oMail->files=$Files;
        $resmail=$oMail->SendEmail();

        foreach($Files as $file)
        {
            unlink($file);
        }

        return $this->set_response([
            'status' => true,
            'rows' => $resmail,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function CrearPdfServicio($IdServicio)
    {
        $this->load->library('reports/Servicio');

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        if($IdServicio<=0){
            echo 'error';
            return false;
        }
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();

        if($responsedes['status']){
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Folio']=$responsedes['data']->Folio;
            $dataResp['Titulo']='Reporte de Servicios';


            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();

            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();

            $oMequipocomentario=new Mequipocomentario();
            $oMequipocomentario->IdServicio=$IdServicio;
            $row=$oMequipocomentario->get_list();

            $oMfirmas= new Mfirmas();
            $oMfirmas->IdServicio=$IdServicio;
            $datafirmas= $oMfirmas->get_recovery();

            $trajoimagen = ($datafirmas['status']) ? true : false;

            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();


            $dataResp['servicio']           = $responsedes['data'];
            $dataResp['sucursal']           = $responseclisuc['data'];
            $dataResp['fechaservicio']      = $responsefecha['data'];
            $dataResp['equipocomentario']   = $row;
            $dataResp['firma']              = $datafirmas['data'];
            $dataResp['personal']           = $datapersonal['data'];
			$dataResp['statusimg']          = $trajoimagen;

            /*$pdf=new Servicio("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            //Header
            $pdf->SetMargins(5,20,5);
            $pdf->dasto_servicioG();
            $pdf->contenido();*/

            /**
             *
             * Este Documento se utiliza tanto en Cdespacho como en Cservicio
             * ya que es posible enviar este documento por correo electronico.
             *
             */


            $pdf = new Servicio("P",'mm','LETTER',true,'UTF-8');
			$pdf->setDatos($dataResp);
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Desprosoft');
			$pdf->SetTitle('Reporte de Servicio');


			//Activar o Desactivar la cabecera y/o Pie de pagina
			$pdf->setPrintHeader(true);
			$pdf->setPrintFooter(true);

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(10, 30, 10);   # esto modifica si  hay margenes o no $left, $top, $right=-1, $keepmargins=false
			#$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  # margen de donde empieza el texto
			#$pdf->SetFooterMargin(25);              # margen de donde empieza el footer

			// set auto page breaks
			$pdf->SetAutoPageBreak(true,30); # Punto de quiebre para el final de la hoja

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->contenido();


            $Evidencia= "Servicio.pdf";
            $pdf->Output(FCPATH.$this->RutaFile.$Evidencia,'F');
            return  $this->RutaFile.$Evidencia;
        }
    }

    public function CrearPdfEvidencia($IdServicio)
    {
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $this->load->library('reports/ServicioEvidencia');
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();

        if($responsedes['status']){
            #obtener datos del token **datos obligtorios
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Titulo']='Reporte de Servicios';
            $dataResp['Folio']=$responsedes['data']->Folio;

            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();

            $oMfechaservicio=new Mfechaservicio();
            $oMfechaservicio->IdServicio=$IdServicio;
            $responsefecha=$oMfechaservicio->get_recovery();

            $obPersonal= new Mtrabajador();
            $obPersonal->IdTrabajador=$responsefecha['data']->IdTrabajador;
            $datapersonal= $obPersonal->get_trabajador();

            /*$oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->IdEquipo="";
            $oMimagenequipo2->Mostrar ="s";
            $row=$oMimagenequipo2->get_listImgEquip();*/

             /* $pdf=new ServicioEvidencia("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->AddPage();
            $pdf->SetMargins(5,20,5);
            $pdf->dasto_servicioG();
            $pdf->contenido();*/

            $oMimagenequipo2=new Mimagenequipo2();
            $oMimagenequipo2->IdServicio=$IdServicio;
            $oMimagenequipo2->Mostrar ="s";
            $row2=$oMimagenequipo2->get_list2();

            $dataResp['servicio']       = $responsedes['data'];
            $dataResp['sucursal']       = $responseclisuc['data'];
            $dataResp['fechaservicio']  = $responsefecha['data'];
            $dataResp['row']            = $this->ListFullEquipo($IdSucursal,$IdEmpresa,$IdServicio);
            $dataResp['row2']           = $row2;
            $dataResp['personal']		= $datapersonal['data'];


            /**
             *
             * Este Documento se utiliza tanto en Cdespacho como en Cservicio
             * ya que es posible enviar este documento por correo electronico.
             *
             */

            $pdf = new ServicioEvidencia("P",'mm','LETTER',true,'UTF-8');
			$pdf->setDatos($dataResp);
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Desprosoft');
			$pdf->SetTitle('Reporte de Servicio');

			//Activar o Desactivar la cabecera y/o Pie de pagina
			$pdf->setPrintHeader(true);
			$pdf->setPrintFooter(true);

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(10, 35, 10);   # esto modifica si  hay margenes o no $left, $top, $right=-1, $keepmargins=false
			#$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  # margen de donde empieza el texto
			//$pdf->SetFooterMargin(15);              # margen de donde empieza el footer

			// set auto page breaks
			$pdf->SetAutoPageBreak(true,10); # Punto de quiebre para el final de la hoja

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// ---------------------------------------------------------
			$pdf->contenido();

            $Evidencia= "Evidencia.pdf";
            $pdf->Output(FCPATH.$this->RutaFile.$Evidencia,'F');
            return  $this->RutaFile.$Evidencia;
        }
    }

    /**
     * @funtion  ListFullEquipo()
     * Esta funcion es la encargada de generar la estructura de arreglo bidimecional
     * para el reporte de evidencias fotograficas
     *
     */

    public function ListFullEquipo($IdSucursal = 0, $IdEmpresa = 0, $IdServicio = 0)
    {

    	//IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
		//IdUsuario Logueado desde la app seria IdContacto !!!!!

		$ruta1 = base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$IdServicio.'/';
		$ruta2 = returnLinkFotosServicios().$IdServicio.'/';

		$Mequipocomentario = new Mequipocomentario();
		$Mequipocomentario->IdServicio = $IdServicio;
		$Mequipocomentario->Permitir= 's';
		$rowequipos=  $Mequipocomentario->get_list();

		$count=0;
		foreach ($rowequipos as $element) {

			$Mimagenequipo2 			= new Mimagenequipo2();
			$Mimagenequipo2->IdServicio = $IdServicio;
			$Mimagenequipo2->IdEquipo   = $element->IdEquipo;
			$Mimagenequipo2->Mostrar 	= "s";
			$resp =  $Mimagenequipo2->get_list();

			$contadorFile = 0;
			foreach ($resp as $elem2) {

				if ($elem2->Mostrar == 's') {
					$elem2->Mostrar =true;

				} else {
					$elem2->Mostrar =false;
				}

				if($elem2->Tipo == 1) {
					$resp[$contadorFile]->FileEquipo= 'https://desprosoft.online/services/assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$IdServicio.'/'.$elem2->Imagen;

				}else {
					$resp[$contadorFile]->FileEquipo= $ruta2.$elem2->Imagen;
				}

				if(!file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$IdServicio.'/'.$elem2->Imagen)){
					//si no existe la imagen en la ruta se cambia ala ruta 2
					$ruta1 =$ruta2;
				}
				$contadorFile ++;
			}

			$rowequipos[$count]->Imagenes=$resp;
			$count ++;
		}

		$data['equipos'] 	= $rowequipos;
		$data['ruta'] 		= $ruta1;
		$data['ruta2'] 		= $ruta2;
		$data['ruta1'] 		= base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$IdServicio.'/';

		return $data['equipos'];

	}


    public function CrearPdfMail($IdServicio)
    {
        $this->load->library('reports/ServicioEmail');

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $oMservicio=new Mservicio();
        $oMservicio->IdServicio=$IdServicio;
        $responsedes=$oMservicio->get_servicio();

        if($responsedes['status'])
        {
            #obtener datos del token
            $dataResp['IdEmpresa']=$IdEmpresa;
            $dataResp['IdSucursal']=$IdSucursal;
            $dataResp['Folio']=$responsedes['data']->Folio;
            $dataResp['Titulo']='Reporte de Servicios';

            $oMclientesucursal=new Mclientesucursal();
            $oMclientesucursal->IdClienteS=$responsedes['data']->IdClienteS;
            $responseclisuc=$oMclientesucursal->get_cliente();

            $oMservicio=new Mservicio();
            $oMservicio->IdServicio=$IdServicio;
            $rowserv=$oMservicio->list_serviciosreferences();

            $ListaFechas=array();
            foreach ($rowserv as $element)
            {
                $oMfechaservicio =new Mfechaservicio();
                $oMfechaservicio->IdServicio =$element->IdServicio;
                $responsefecha = $oMfechaservicio->get_recovery();
                if ($responsefecha['status'])
                {
                    array_push($ListaFechas,$responsefecha['data']);
                }
            }

            $oMfirmas= new Mfirmas();
            $oMfirmas->IdServicio=$IdServicio;
            $datafirmas= $oMfirmas->get_recovery();

            $dataResp['servicio']=$responsedes['data'];
            $dataResp['sucursal']=$responseclisuc['data'];
            $dataResp['fechaservicio']=$ListaFechas;
            $dataResp['firma']=$datafirmas['data'];

            $pdf=new ServicioEmail("P",'mm','Letter');
            $pdf->setDatos($dataResp);
            $pdf->SetAutoPageBreak(true,15);
            $pdf->AddPage();
            //Header

            $pdf->SetMargins(5,20,5);
            //$pdf->dasto_servicioG();
            $pdf->contenido();
             $Evidencia= "Orden de Servicio".date('His').".pdf";

            $pdf->Output(FCPATH.$this->RutaFile.$Evidencia,'F');
            return  $this->RutaFile.$Evidencia;
        }
    }

    public function EjemploNotify_post()
    {
		//$Titulo="Nueva promoción";
		//$message='Promoción';
		//$Token='eMC4yS9k15E:APA91bEqPNVacShWPEDA-EtzbQhGNoe2_yBq-1D0MxXnU0keIJQbI9HagUn4-GTjYypEAsrN_5swFsfPhTo0cmuY51c1UoGybHeO28HQSCWa_fM2b-4Yw4Xwffw3LtjtVn1oAdnKWvdU';
		//$resultnoti = NotificationOne($Titulo,$message,$Token);
		//$this->response($resultnoti);
    }

    #se usa en despacho calendario
    public function listcalendarioday_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $FechaBusqueda = $this->get('FechaBusqueda');

        if($FechaBusqueda=='')
        {
            $FechaBusqueda = date('Y-m-d');
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdCliente= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdCliente'];

        setTimeZoneEmpresa($IdSucursal);

        $IdClienteS=$this->get('IdClienteS');

        $objServicio = new Mservicio();
        $objServicio ->IdSucursal = $IdSucursal;
        $objServicio->IdCliente=$IdCliente;
        $objServicio->IdCliente=$IdCliente;
        $objServicio->FechaBusqueda=$FechaBusqueda;

        $rows = $objServicio->get_listcalendar();
        $data['calendar'] =$rows;
        $data['Fecha'] =$FechaBusqueda;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
?>
