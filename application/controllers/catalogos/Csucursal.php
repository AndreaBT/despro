
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Csucursal extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Msucursal');
        $this->load->library('UploadFile');
        $this->load->model('Musuarios');
        $this->load->model('Mrol');
        $this->load->model('Mperfil');
        
        $this->load->model('Mcostoskm');
        $this->load->model('Mcostosta');
        $this->load->model('Mmarkup');
        $this->load->model('Mtiposervicio');

        $this->load->model('Mcategoriavehiculo');

        $this->load->model('Mconfiguracion');
        $this->load->model('Mcategoriavehiculo');
        $this->load->model('Mvehiculo');

        $this->load->library('Mail');

       

        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objSucursal = new Msucursal();
        // Filtros
        if($this->get('RegEstatus')=='') {
            $objSucursal ->RegEstatus = 'A';
        }else{
            $objSucursal ->RegEstatus = $this->get('RegEstatus');
        }
        
            
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        if (empty($this->get('IdEmpresa')))
        {
        $objSucursal->IdEmpresa =$IdEmpresa;
         }
        else{
            $objSucursal ->IdEmpresa =$this->get('IdEmpresa');
        }
        $objSucursal->Nombre = $this->get('Nombre');
        // Paginación

        $rows = $objSucursal->get_list();
        $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }

        $objSucursal->Limit=$Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objSucursal->Tamano = $pager->PageSize;
        $objSucursal->Offset = $pager->Offset;

        $data['sucursal'] = $objSucursal->get_list();
        
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function findOne_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objSucursal = new Msucursal();
        $Id = (int) $this->get('IdSucursal');

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objSucursal->IdSucursal = $Id;
        }
        $response =$objSucursal->get_sucursal();
        if ($response['status']) {
            $data['sucursal'] = $response['data'];
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

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $objSucursal = new Msucursal();
        $objSucursal->IdSucursal = $Id;
  
        $response =$objSucursal->get_sucursal();

        if ($response['status']) {

            if ($objSucursal->delete()) {
                
                $oMusuarios = new Musuarios();
                $oMusuarios->IdSucursal=$Id;
                $oMusuarios->FechaMod=date('Y-m-d H:i:s');
                $oMusuarios->Estatus='B';
                $oMusuarios->update_usuariosbaja();

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


    public function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        
        $Id = $this->post('IdSucursal');
        $oMusuarios= new Musuarios();
        $oMusuarios->Candado=trim($this -> post('Usuario'));
        $oMusuarios->IdUsuario=$this -> post('IdUsuario');
        $oMusuarios->Estatus='A';
        $eval= $oMusuarios->exists_username();
           
        $Correo2='';
        if ($eval)
        {
            $Correo2 =trim($this -> post('Usuario'));
        }
        
        if ($Id>0)
        {
            $v = new Valitron\Validator([
                'Nombre' => $this->post('Nombre'),
                'Telefono' => $this->post('Telefono'),
                'Direccion' => $this->post('Direccion'),
                'Correo' => $this->post('Correo'),
                'Ciudad' => $this->post('Ciudad'),
                'Estado' => $this->post('Estado'),
                'CP' => $this->post('CP'),
                'IdEmpresa' => $this->post('IdEmpresa'),
                'Fecha_Fac' => $this->post('Fecha_Fac'),
                'Plazo' => $this->post('Plazo'),
                'PaqueteU' => $this->post('PaqueteU')            
            ]);

            $v->rule('required', [
                'Nombre','Telefono','Direccion','Correo','Ciudad','Estado','CP','IdEmpresa','Fecha_Fac','Plazo','PaqueteU',
            ])->message('El campo {field} es requerido.');
        
            $v->rule('email', 'Correo')->message('Correo electrónico no válido.');
        
        }else
        {
            $v = new Valitron\Validator([
                'Nombre' => $this->post('Nombre'),
                'Telefono' => $this->post('Telefono'),
                'Direccion' => $this->post('Direccion'),
                'Correo' => $this->post('Correo'),
                'Usuario' => $this -> post('Usuario'),
                'NombreU' => $this -> post('NombreU'),
                'ApellidoU' => $this -> post('ApellidoU'),
                'Correo2' => $Correo2,
                'Pass' => $this -> post('Pass'),
                'Password_Confirmacion' => $this -> post('Pass2'),
                'Ciudad' => $this->post('Ciudad'),
                'Estado' => $this->post('Estado'),
                'CP' => $this->post('CP'),
                'IdEmpresa' => $this->post('IdEmpresa'),
                'Fecha_Fac' => $this->post('Fecha_Fac'),
                'Plazo' => $this->post('Plazo'),
                'PaqueteU' => $this->post('PaqueteU')            
            ]);

        $v->rule('required', [
            'Nombre','Telefono','Direccion','Usuario','NombreU','ApellidoU','Pass','Password_Confirmacion','Correo','Ciudad','Estado',
            'CP','IdEmpresa','Fecha_Fac','Plazo','PaqueteU',
            
        ])->message('El campo {field} es requerido.');
        
        $v->rule('email', 'Correo')->message('Correo electrónico no válido.');
        $v->rule('equals','Password_Confirmacion', 'Pass')->message('La contraseña debe ser igual');  
        $v->rule('different','Usuario', 'Correo2')->message('El usuario ya existe');
            
        }

        if ($v->validate()) {

            
            $matriz='Matriz';
            $objSucursal= new Msucursal();
            $objSucursal->IdEmpresa=$this->post('IdEmpresa');
            $val=  $objSucursal->get_sucursal();
            if ($val['status'])
            {
                $matriz='Matriz';
            }
            
            $objSucursal= new Msucursal();
            $objSucursal->IdSucursal = $Id;
            $objSucursal->Nombre = $this->post('Nombre');
            $objSucursal->Telefono = $this->post('Telefono');
            $objSucursal ->Direccion = $this->post('Direccion');
            $objSucursal ->Correo = $this->post('Correo');
            $objSucursal ->Ciudad = $this->post('Ciudad');
            $objSucursal->Estado = $this->post('Estado');
            $objSucursal->CP = $this->post('CP');
            $objSucursal->IdEmpresa= $this->post('IdEmpresa');
            $objSucursal->TipSucursal= $matriz;
            $objSucursal->RegEstatus= "A";
            $objSucursal->Fecha_Fac= $this->post('Fecha_Fac');
            $objSucursal->Plazo= $this->post('Plazo');
            $objSucursal->PaqueteU= $this->post('PaqueteU');
            $objSucursal->Comentario= $this->post('Comentario');
            $objSucursal->FechaMod = date('Y-m-d H:i:s');
          

            if($objSucursal->IdSucursal==0){
                #DAtos mail
                $dataview['Cliente']=$this->post('Nombre');
                $dataview['Usuario']=$this->post('Usuario');
                $dataview['Contrasenia']=$this->post('Pass');
                $dataview['Fecha']=date('d/m/Y H:i');
                $dataview['link']=returnLink();
                $vista=$this->load->view('catalogos/correo/bienvenida.php',$dataview,TRUE);
                
                $oMail=new Mail();
                $oMail->To=$this->post('Correo');
                $oMail->Subject='DESPROSOFT - Bienvenido';
                $oMail->Message=$vista;
               
                #fin datos mail
                

                $id_suc =  $objSucursal->insert();
                if ($id_suc>0){
                    #envio de email
                    $resmail=$oMail->SendEmail();
                     
                    $oMusuarios= new Musuarios();
                    $oMusuarios->Nombre=$this->post('NombreU');
                    $oMusuarios->Apellido=$this->post('ApellidoU');
                    $oMusuarios->IdPerfil2=2;
                    $oMusuarios->Candado=$this->post('Usuario');
                    $oMusuarios->Password=Password::hash($this->post('Pass'));
                    $oMusuarios->Estatus='A';
                    $oMusuarios->IdEmpresa=$this->post('IdEmpresa');
                    $oMusuarios->IdSucursal=$id_suc;
                    $oMusuarios->FechaMod = date('Y-m-d H:i:s');
                    $oMusuarios->Foto2 ='';
                    $oMusuarios->insert();
                    
                    $this->ConfigSucursal($id_suc);

                    //CATEGORÍA VEHÍCULO VIRTUAL 
                        $IdCat = $this -> post('IdCategoria');
    
                        $objCategoriavehiculo= new Mcategoriavehiculo();
                        $objCategoriavehiculo->IdCategoria = $this -> post('IdCategoria');
                        $objCategoriavehiculo->Nombre = "VIRTUAL";
                        $objCategoriavehiculo->RegEstatus = "A";
                        $objCategoriavehiculo->IdSucursal =$id_suc;
                        $objCategoriavehiculo->FechaMod = date('Y-m-d H:i:s');
            
                            $IdCat = $objCategoriavehiculo-> insert();
                            if ($IdCat > 0) {
                                $objCategoriavehiculo->IdCategoria= $IdCat;
                                $response=$objCategoriavehiculo-> get_categoriavehiculo();
                                $data['categoriavehiculo'] = $response['data'];

                                //VEHÍCULO VIRTUAL
                                    $IdVehi = $this -> post('IdVehiculo');

                                    $objVehiculo= new Mvehiculo();
                                    $objVehiculo->IdVehiculo= $this->post('IdVehiculo');
                                    $objVehiculo->Categoria = 'VIRTUAL';
                                    $objVehiculo->Marca ='';
                                    $objVehiculo->Modelo = '';
                                    $objVehiculo->Ano= date('Y');;
                                    $objVehiculo->Placa = '';
                                    $objVehiculo->Numero = 'VIRTUAL';
                                    $objVehiculo->TipoVehiculo = 'Kilometros';
                                    $objVehiculo->CostoAnual= '';
                                    $objVehiculo->IdSucursal = $id_suc;
                                    $objVehiculo->RegEstatus= "A";
                                    $objVehiculo->IdCategoria= $IdCat;
                                    $objVehiculo->Inventario= '';
                                    $objVehiculo->Historial = '';
                                    $objVehiculo->FechaMod=date('Y-m-d H:i:s');
                                    $IdVehi = $objVehiculo-> insert();
                            

                                return $this -> set_response([
                                    'status' => true,
                                    'data' => $data,
                                    'message' => 'Se ha agregado correctamente.',
                                ], REST_Controller:: HTTP_CREATED);
                            } else {
                                return $this -> set_response([
                                    'status' => false,
                                    'message' => 'Error al agregar a la base de datos.',
                                ], REST_Controller:: HTTP_BAD_REQUEST);
                            }
                        
                    
                    $objSucursal->IdSucursal = $id_suc;
                    $response =   $objSucursal->get_Sucursal();
                    $data['sucursal'] = $response['data'];
                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                        'typemsg'=>1,
                    ], REST_Controller::HTTP_CREATED);
                }else{
                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                        'typemsg'=>1,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                if (  $objSucursal->update()) {
                    $response =    $objSucursal->get_sucursal();
                    $data['sucursal'] = $response['data'];
            
                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                        'typemsg'=>1,
                    ], REST_Controller::HTTP_ACCEPTED);
                } else {
            
                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                        'typemsg'=>1,
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            }

        } else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
                'typemsg'=>2,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function ConfigSucursal($IdSucursal)
    {
        $oMconfiguracion= new Mconfiguracion();
        $oMconfiguracion->IdSucursal=$IdSucursal;
        $oMconfiguracion->ZonaHoraria='America/Mexico_City';
        $oMconfiguracion->insert();
        
        //costos km
        $oMcostoskms = new Mcostoskm();
        $oMcostoskms->KMinicial = "0";
        $oMcostoskms->KMfinal = "0";
        $oMcostoskms->CostoKM = "0";
        $oMcostoskms->IdSucursal = $IdSucursal;
        $oMcostoskms->RegEstatus = "A";
        $oMcostoskms->FechaMod = date('Y-m-d H:i:s');
        $oMcostoskms->insert();

        $oMcostoskms = new Mcostoskm();
        $oMcostoskms->KMinicial = "1";
        $oMcostoskms->KMfinal = "5";
        $oMcostoskms->CostoKM = "1.50";
        $oMcostoskms->IdSucursal = $IdSucursal;
        $oMcostoskms->RegEstatus = "A";
        $oMcostoskms->FechaMod = date('Y-m-d H:i:s');
        $oMcostoskms->insert();

        $oMcostoskms = new Mcostoskm();
        $oMcostoskms->KMinicial = "6";
        $oMcostoskms->KMfinal = "20";
        $oMcostoskms->CostoKM = "1";
        $oMcostoskms->IdSucursal = $IdSucursal;
        $oMcostoskms->RegEstatus = "A";
        $oMcostoskms->FechaMod = date('Y-m-d H:i:s');
        $oMcostoskms->insert();

        $oMcostoskms = new Mcostoskm();
        $oMcostoskms->KMinicial = "21";
        $oMcostoskms->KMfinal = "40";
        $oMcostoskms->CostoKM = "0.75";
        $oMcostoskms->IdSucursal = $IdSucursal;
        $oMcostoskms->RegEstatus = "A";
        $oMcostoskms->FechaMod = date('Y-m-d H:i:s');
        $oMcostoskms->insert();

        $oMcostoskms = new Mcostoskm();
        $oMcostoskms->KMinicial = "41";
        $oMcostoskms->KMfinal = "500";
        $oMcostoskms->CostoKM = "0.50";
        $oMcostoskms->IdSucursal = $IdSucursal;
        $oMcostoskms->RegEstatus = "A";
        $oMcostoskms->FechaMod = date('Y-m-d H:i:s');
        $oMcostoskms->insert();

        //fin Costo km

        //costos ta
        $oMcostosta = new Mcostosta();
        $oMcostosta->Concepto = "TECNICO";
        $oMcostosta->Costo= "9";
        $oMcostosta->RegEstatus = "A";
        $oMcostosta->IdSucursal = $IdSucursal;
        $oMcostosta->FechaMod = date('Y-m-d H:i:s');
        $oMcostosta->insert();

        $oMcostosta = new Mcostosta();
        $oMcostosta->Concepto = "TECNICO + AYUDANTE";
        $oMcostosta->Costo= "12";
        $oMcostosta->RegEstatus = "A";
        $oMcostosta->IdSucursal =$IdSucursal;
        $oMcostosta->FechaMod = date('Y-m-d H:i:s');
        $oMcostosta->insert();

        $oMcostosta = new Mcostosta();
        $oMcostosta->Concepto = "MATERIALES MISC. DIA";
        $oMcostosta->Costo= "20";
        $oMcostosta->RegEstatus = "A";
        $oMcostosta->IdSucursal = $IdSucursal;
        $oMcostosta->FechaMod = date('Y-m-d H:i:s');
        $oMcostosta->insert();

        $oMcostosta = new Mcostosta();
        $oMcostosta->Concepto = "FACTOR M.O. T+A";
        $oMcostosta->Costo= "1.30";
        $oMcostosta->RegEstatus = "A";
        $oMcostosta->IdSucursal =$IdSucursal;
        $oMcostosta->FechaMod = date('Y-m-d H:i:s');
        $oMcostosta->insert();

        $oMcostosta = new Mcostosta();
        $oMcostosta->Concepto = "BURDEN RATE";
        $oMcostosta->Costo= "8";
        $oMcostosta->RegEstatus = "A";
        $oMcostosta->IdSucursal = $IdSucursal;
        $oMcostosta->FechaMod = date('Y-m-d H:i:s');
        $oMcostosta->insert();

        //termina

        //markup
        $oMmarkup = new Mmarkup();
        $oMmarkup->Monto_I = "0";
        $oMmarkup->Monto_F = "1000000000";
        $oMmarkup->CostoM = "1";
        $oMmarkup->RegEstatus ="A";
        $oMmarkup->IdSucursal = $IdSucursal;
        $oMmarkup->FechaMod = date('Y-m-d H:i:s');
        $oMmarkup->insert();

        //termina
        
        //Tipos de servicios 

        $Array =array("Servicio","Mantenimiento","Proyecto","Garatia","Tiempo no Productivo","Levantamiento");
        $ArrayC =array("#00B050","#FF7F27","#1190C7","#E4D807","#E80C15","#EA09CD");
        $IdConfigs = array("2","1","3","5","4","6");

        // for ($i=0;$i <= count($Array);$i++)
        // {
            
        //     $objTiposervicio = new Mtiposervicio();
        //     $objTiposervicio->Concepto = $Array[$i];
        //     $objTiposervicio->IdSucursal = $IdSucursal;
        //     $objTiposervicio->RegEstatus =    "A";
        //     $objTiposervicio->GrossM = "40";
        //     $objTiposervicio->Color = $ArrayC[$i];
        //     $objTiposervicio->Ingresos = "0";
        //     $objTiposervicio->IdIcono = $IdConfigs[$i];
        //     $objTiposervicio->Tipo = $IdConfigs[$i];
        //     $objTiposervicio->IdConfigS = $IdConfigs[$i];
        //     $objTiposervicio->FechaMod = date('Y-m-d H:i:s');
        //     $objTiposervicio->insert();

        // }

        $contador = 0;
        foreach ($Array as $key)
        {
            $objTiposervicio = new Mtiposervicio();
            $objTiposervicio->Concepto = $Array[$contador];
            $objTiposervicio->IdSucursal = $IdSucursal;
            $objTiposervicio->RegEstatus =    "A";
            $objTiposervicio->GrossM = "40";
            $objTiposervicio->Color = $ArrayC[$contador];
            $objTiposervicio->Ingresos = "0";
            $objTiposervicio->IdIcono = $IdConfigs[$contador];
            $objTiposervicio->Tipo = $IdConfigs[$contador];
            $objTiposervicio->IdConfigS = $IdConfigs[$contador];
            $objTiposervicio->FechaMod = date('Y-m-d H:i:s');
            $objTiposervicio->insert();

            $contador ++;
        }
    }
}