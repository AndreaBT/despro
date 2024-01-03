<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cusuarios extends REST_Controller
{
    public $RutaFoto;

    public function __construct()
    {
        $this->RutaFoto='assets/files/foto_trabajador/';
        $this->RutaFotoE='assets/files/logo_empresa/';

        parent::__construct();

        $this->load->model('Msucursal');
        $this->load->model('Musuarios');
        $this->load->model('Mclientes');
        $this->load->model('Mempresa');
        $this->load->model('Mtrabajador');
        $this->load->model('Mrol');
        $this->load->model('Mperfil');
        $this->load->model('Mpaquetexsucursal');
        $this->load->model('Mpaquete');
        $this->load->model('Mconfiguracion');

        $this->load->library('UploadFile');

        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objUsuarios = new Musuarios();
        // Filtro
        if($this->get('RegEstatus')==''){
            $objUsuarios->Estatus = 'A';
        } else {
            $objUsuarios->Estatus = $this->get('RegEstatus');
        }

        $IdCliente = (int) $this->get('IdCliente');
        if(!empty($IdCliente)){
            $objUsuarios->IdCliente=$IdCliente;
        }


        $objUsuarios->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];


        $objUsuarios->Nombre = $this->get('Nombre');

        // Paginación

        $rows = $objUsuarios->get_list();

		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$objUsuarios->Limit = $Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objUsuarios->Tamano = $pager->PageSize;
        $objUsuarios->Offset = $pager->Offset;

        $data['usuarios'] = $objUsuarios->get_list();
        $data['pagination']= $pager;
        $data['headers']= ["Nombre","Correo","Acción"];

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

     public function ListuserSucursal_get(){
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objUsuarios = new Musuarios();
		$objUsuarios->IdSucursal 	= $this->get('IdSucursal');
		$objUsuarios->Nombre		= $this->get('Nombre');

        // Filtro
        if($this->get('RegEstatus')==''){
            $objUsuarios->Estatus = 'A';
        } else {
            $objUsuarios->Estatus = $this->get('RegEstatus');
        }

        // Paginación
        $rows = $objUsuarios->get_list();

		$Entrada = '';
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}

		$objUsuarios->Limit = $Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objUsuarios->Tamano = $pager->PageSize;
        $objUsuarios->Offset = $pager->Offset;

        $data['usuarios'] = $objUsuarios->get_list();
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $objUsuarios = new Musuarios();

        $Id = (int) $this->get('IdUsuario');
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objUsuarios->IdUsuario = $Id;
        }

        $data['UrlFoto'] =base_url().$this->RutaFoto.$IdEmpresa.'/'.$IdSucursal.'/';

        $response = $objUsuarios->get_usuario();
        if ($response['status']) {
            $data['Usuario'] = $response['data'];
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

    public function Add_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Apellidos' => $this->post('Apellidos'),
            'Usuario' => $this->post('Usuario'),
            'Correo' => $this->post('Correo'),
            'Contraseña' => $this->post('Contrasenia'),
            'Confirmar_Contraseña' => $this->post('ConfirmContrasenia'),
            'Fecha_de_Nacimiento' => $this->post('Nacimiento'),
            'Puesto' => $this->post('IdPuesto'),
            'Departamento' => $this->post('IdModulo'),
            'Sucursal' => $this->post('IdSucursal'),
        ]);

        $v->rule('required', [
            'Nombre',
            'Apellidos',
            'Usuario',
            'Correo',
            'Contraseña',
            'Confirmar_Contraseña',
            'Fecha_de_Nacimiento',
            'Puesto',
            'Sucursal',
            'Departamento'
        ])->message('El campo {field} es requerido.');

        $v->rule('email', 'Correo')->message('Correo electrónico no válido.');
        $v->rule('equals', 'Contraseña', 'Confirmar_Contraseña')->message('Las contraseñas no coinciden');
        $v->rule('date', 'Nacimiento')->message('El campo {field} no es una fecha válida.');

        if ($v->validate()) {

            $Id = $this->post('IdUsuario');

            $objPuestos = new Mpuestos();
            $objPuestos->IdPuesto = $this->post('IdPuesto');
            $info = $objPuestos->get_PerfilByIdPuesto();

            $objUsuarios = new Musuarios();
            $objUsuarios->Nombre = $this->post('Nombre');
            $objUsuarios->Apellidos = $this->post('Apellidos');
            $objUsuarios->Usuario = $this->post('Usuario');
            $objUsuarios->Correo = $this->post('Correo');
            $objUsuarios->Contrasenia = Password::hash($this->post('ConfirmContrasenia'));
            $objUsuarios->Telefono = $this->post('Telefono');
            $objUsuarios->Nacimiento = date('Y-m-d', strtotime($this->post('Nacimiento')));
            $objUsuarios->Suspendido = $this->post('Suspendido');
            $objUsuarios->IdPuesto = $this->post('IdPuesto');
            $objUsuarios->IdModulo = $this->post('IdModulo');
            $objUsuarios->IdPerfil = (int)$info->IdPerfil;
            $objUsuarios->IdJefe = $this->post('IdJefe');
            $objUsuarios->IdSucursal = $this->post('IdSucursal');
            $objUsuarios->FechaReg = date('Y-m-d H:i:s');
            $objUsuarios->FechaMod = date('Y-m-d H:i:s');
            $objUsuarios->RegEstatus = 'A';

            $fotoDefault = '';
            if(empty($_FILES['File'])){

                if(empty($this->post('Foto'))){
                    $fotoDefault = 'assets/files/usuarios/user.svg';
                }else{
                    $fotoDefault = $this->post('Foto');
                }
            }

            $ruta = 'assets/files/usuarios/'.$this->post('IdSucursal').'/';
            $objUsuarios->Foto = $this->uploadfile->savefile($ruta,'File', $fotoDefault, '*', UploadFile::SINGLE);

            $rutaFirma = 'assets/files/firmas/'.$this->post('IdSucursal').'/';
            $objUsuarios->Firma = $this->uploadfile->savefile($rutaFirma, 'FileFirma',  $this->post('Firma'), '*', UploadFile::SINGLE);

            if ($objUsuarios->exists_username()) {

                return $this->set_response([
                    'status' => false,
                    'message' => 'El nombre de usuario ya existe.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

            $Id = $objUsuarios->insert();

            if ($Id > 0) {

                if ((int)$info->IdPerfil == 3 || (int)$info->IdPerfil == 4 || (int)$info->IdPerfil == 5)
                {
                    $json = str_replace(array("\t","\n"), "",  $this->post('ListaProyectos'));
                    $data = json_decode($json);
                    foreach ( $data as $IdProyecto)
                    {
                        $Musuarioproyectos = new Musuarioproyectos();
                        $Musuarioproyectos->IdUsuario=$Id;
                        $Musuarioproyectos->IdProyecto=$IdProyecto;
                        $Musuarioproyectos->insert();
                    }
                }

                if($objUsuarios->IdPerfil == 2){

                    $user['correo']= $objUsuarios->Correo;
                    $user['password']= $this->post('ConfirmContrasenia');

                    $message = $this->load->view('catalogos/correo/bienvenida.php', $user,TRUE);

                    Mail::SendEmail($objUsuarios->Correo, 'Bienvenido a nuestra plataforma', $message);

                }else{

                    if($this->post('enviarEmail') == true){

                        $user['correo']= $objUsuarios->Correo;
                        $user['password']= $this->post('ConfirmContrasenia');

                        $message = $this->load->view('catalogos/correo/bienvenida.php', $user,TRUE);

                        Mail::SendEmail($objUsuarios->Correo, 'Bienvenido a nuestra plataforma', $message);
                    }

                }

                $objUsuarios->IdUsuario = $Id;
                $response = $objUsuarios->get_usuario();
                $data['usuario'] = $response['data'];

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller::HTTP_CREATED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al agregar a la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function Update_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Apellidos' => $this->post('Apellidos'),
            'Usuario' => $this->post('Usuario'),
            'Correo' => $this->post('Correo'),
            'Nacimiento' => $this->post('Nacimiento'),
            'Departamento' => $this->post('IdModulo'),
            'Puesto' => $this->post('IdPuesto'),
            'Sucursal' => $this->post('IdSucursal')
        ]);

        $v->rule('required', [
            'Nombre',
            'Apellidos',
            'Usuario',
            'Correo',
            'Nacimiento',
            'Puesto',
            'Sucursal',
            'Departamento'
        ])->message('El campo {field} es requerido.');

        $v->rule('email', 'Correo')->message('Correo electrónico no válido.');
        $v->rule('date', 'Nacimiento')->message('El campo {field} no es una fecha válida.');

        if ($v->validate()) {

            $Id = $this->post('IdUsuario');

            $objPuestos = new Mpuestos();
            $objPuestos->IdPuesto = $this->post('IdPuesto');
            $info = $objPuestos->get_PerfilByIdPuesto();

            $objUsuarios = new Musuarios();
            $objUsuarios->Nombre = $this->post('Nombre');
            $objUsuarios->Apellidos = $this->post('Apellidos');
            $objUsuarios->Usuario = $this->post('Usuario');
            $objUsuarios->Correo = $this->post('Correo');
            $objUsuarios->Telefono = $this->post('Telefono');
            $objUsuarios->Nacimiento = date('Y-m-d', strtotime($this->post('Nacimiento')));
            $objUsuarios->Suspendido = $this->post('Suspendido');
            $objUsuarios->IdPuesto = $this->post('IdPuesto');
            $objUsuarios->IdJefe = $this->post('IdJefe');
            $objUsuarios->IdModulo = $this->post('IdModulo');
            $objUsuarios->IdPerfil = (int)$info->IdPerfil;
            $objUsuarios->IdSucursal = $this->post('IdSucursal');
            $objUsuarios->FechaMod = date('Y-m-d H:i:s');

            $fotoDefault = '';
            if(empty($_FILES['File'])){

                if(empty($this->post('Foto'))){
                    $fotoDefault = 'assets/files/usuarios/user.svg';
                }else{
                    $fotoDefault = $this->post('Foto');
                }
            }

            $ruta = 'assets/files/usuarios/'.$this->post('IdSucursal').'/';
            $objUsuarios->Foto = $this->uploadfile->savefile($ruta,'File', $fotoDefault, '*', UploadFile::SINGLE);

            $route = 'assets/files/firmas/'.$this->post('IdSucursal').'/';
            $objUsuarios->Firma = $this->uploadfile->savefile($route, 'FileFirma',  $this->post('Firma'), '*', UploadFile::SINGLE);

            $objUsuarios->IdUsuario = (int) $Id;

            if ($objUsuarios->exists_username()) {

                return $this->set_response([
                    'status' => false,
                    'message' => 'El nombre de usuario ya existe.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

            if ($objUsuarios->update()) {

                if ((int)$info->IdPerfil == 3 || (int)$info->IdPerfil == 4 || (int)$info->IdPerfil == 5)
                {
                    $Musuarioproyectos = new Musuarioproyectos();
                    $Musuarioproyectos->IdUsuario = (int) $Id;
                    $Musuarioproyectos->delete();

                    $json = str_replace(array("\t","\n"), "",  $this->post('ListaProyectos'));
                    $data = json_decode($json);

                    if (is_array($data) || is_object($data))
                    {
                        foreach ($data as $IdProyecto)
                        {
                            $Musuarioproyectos->IdUsuario = (int) $Id;
                            $Musuarioproyectos->IdProyecto = $IdProyecto;
                            $Musuarioproyectos->insert();
                        }
                    }
                }

                $response = $objUsuarios->get_usuario();
                $data['usuario'] = $response['data'];

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha actualizado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function UpdateProfile_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];


          $oMusuarios= new Musuarios();
            $oMusuarios->Candado=trim($this -> post('Candado'));
            $oMusuarios->IdUsuario=$this -> post('IdUsuario');
            $oMusuarios->Estatus='A';
           $eval= $oMusuarios->exists_username();

           $Correo2='';
           if ($eval)
           {
            $Correo2 =trim($this -> post('Candado'));
           }

        if (!empty($this->post('Seguridad')) || !empty($this->post('Seguridad2')))
        {

        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Apellido' => $this->post('Apellido'),
            'Candado' => trim($this->post('Candado')),
            'Correo2' => $Correo2,
            'IdUsuario' => $this->post('IdUsuario'),
            'Seguridad' => $this->post('Seguridad'),
            'Confirmacion' => $this->post('Seguridad2')
        ]);

        $v->rule('required', [
            'Nombre',
            'Apellido',
            'Candado',
            'IdUsuario',
            'Seguridad',
            'Confirmacion'
        ])->message('El campo {field} es requerido.');

        if ($IdSucursal>0 && $IdEmpresa>0)
        {
        $v->rule('email', 'Candado')->message('Correo electrónico no válido.');
        }
        $v->rule('equals','Seguridad', 'Confirmacion')->message('La contraseña debe ser igual');
        $v->rule('different','Candado', 'Correo2')->message('El usuario ya existe');
        }
        else
        {
             $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Apellido' => $this->post('Apellido'),
            'Candado' => trim($this->post('Candado')),
            'Correo2' => $Correo2,
            'IdUsuario' => $this->post('IdUsuario'),
        ]);

        $v->rule('required', [
            'Nombre',
            'Apellido',
            'Candado',
            'IdUsuario'
        ])->message('El campo {field} es requerido.');
        if ($IdSucursal>0 && $IdEmpresa>0)
        {
        $v->rule('email', 'Candado')->message('Correo electrónico no válido.');
        }
        $v->rule('different','Candado', 'Correo2')->message('El usuario ya existe');
        }

        if($v->validate()){
             $RutaPrincipal=$this->RutaFoto.$IdEmpresa.'/';
        if (!is_dir($RutaPrincipal)) {
            mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
        }

            $objUsuarios = new Musuarios();

            $dataToken = $this->verification->getTokenData($this->input->request_headers('Authorization'));

            if (empty($dataToken['uniqueid'])) {

                return $this->set_unauthorized_response();
            } else {

                $objUsuarios->IdUsuario = $dataToken['uniqueid'];
            }

            $response = $objUsuarios->get_usuario();

            $route =$RutaPrincipal.$IdSucursal.'/';
            $files = $this->uploadfile->savefile($route, 'File',$this->post('NombreFoto'), '*', UploadFile::SINGLE);

            $objUsuarios->Nombre = $this->post('Nombre');
            $objUsuarios->Apellido = $this->post('Apellido');
            $objUsuarios->Candado = trim($this->post('Candado'));
            if (!empty($this->post('Seguridad')))
            {
            $objUsuarios->Password = Password::hash($this->post('Seguridad'));
            }
            $objUsuarios->FechaMod = date('Y-m-d H:i:s');
            $objUsuarios->Foto2=$files;

            if ($objUsuarios->update_credencial()) {

                $response = $objUsuarios->get_usuario();
                $data['usuario'] = $response['data'];

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha actualizado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

        }else{

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);

        }

    }

    public function SetPassword_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Usuario' => $this->post('IdUsuario'),
            'Contraseña' => $this->post('Contrasenia'),
            'Confirmar_Contraseña' => $this->post('ConfirmContrasenia'),
        ]);

        $v->rule('required', [
            'Usuario',
            'Contraseña',
            'Confirmar_Contraseña'
        ])->message('El campo {field} es requerido.');

        $v->rule('equals', 'Contraseña', 'Confirmar_Contraseña')->message('Las contraseñas no coinciden');

        if ($v->validate()) {

            $Id = $this->post('IdUsuario');

            $objUsuarios = new Musuarios();
            $objUsuarios->Contrasenia = Password::hash($this->post('ConfirmContrasenia'));
            $objUsuarios->FechaMod = date('Y-m-d H:i:s');

            $objUsuarios->IdUsuario = $Id;

            if ($objUsuarios->set_password()) {

                $response = $objUsuarios->get_usuario();
                $data['usuario'] = $response['data'];

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Se ha actualizado correctamente.',
                ], REST_Controller::HTTP_ACCEPTED);
            } else {

                return $this->set_response([
                    'status' => false,
                    'message' => 'Error al actualizar los datos de la base de datos.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $objUsuarios = new Musuarios();
        $objUsuarios->IdUsuario = $Id;
        $objUsuarios->FechaMod = date('Y-m-d H:i:s');

        $response = $objUsuarios->get_usuario();

        if ($response['status']) {

            if ($objUsuarios->delete()) {

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
    /*
    public function Login_post()
    {
        $v = new Valitron\Validator([
            'Usuario' => $this->post('Usuario'),
            'Contraseña' => $this->post('Contrasenia'),
        ]);

        $v->rule('required', [
            'Usuario',
            'Contraseña',
        ])->message('El campo {field} es requerido.');

        if($v->validate())
        {
            $objUsuarios = new Musuarios();
            $objUsuarios->Usuario = $this->post('Usuario');
            $objUsuarios->Contrasenia = $this->post('Contrasenia');

            $response = $objUsuarios->get_usuario_login();

            if($response['status'])
            {
                #Dato del perfil
                $Perfil='';
                $Perfil=$this->getNamePerfil($response['data']);
                #Fin datos perfil

                // OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
                $IdSuc = $response['data']->IdSucursal;

                $oMconfiguracion = new Mconfiguracion();
                $oMconfiguracion->RegEstatus = 'A';
                $oMconfiguracion->IdSucursal = $IdSuc;
                $datConfig = $oMconfiguracion->get_concepto();

                $Zona = 'America/Mexico_City';
                if($datConfig['status']){
                    $Zona = $datConfig['data']->ZonaHoraria;
                }

                $IdCliente=$response['data']->IdCliente;
                $jwtToken = $this->verification->LoginToken($response['data']->IdUsuario,$response['data']->Nombre,$response['data']->IdPerfil,date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa,$IdCliente,$Perfil,$Zona);

                $rescli['data']=null;
                if($response['data']->IdCliente>0){
                    $oMclientes=new Mclientes();
                    $oMclientes->IdCliente=$IdCliente;
                    $rescli=$oMclientes->get_clientes();
                }

                $oMempresa= new Mempresa();
                $oMempresa->IdEmpresa=$response['data']->IdEmpresa;
                $dataEmpresa= $oMempresa->get_empresa();

                if($response['data']->Foto !='' && empty($response['data']->Foto2))
                {
                    $nombre = date("YmdHis").quitarCaracteres($response['data']->Nombre);
                    $ruta='assets/files/foto_trabajador';
                    $rutafinal = CrearRutaEmpSuc($ruta,$response['data']->IdEmpresa,$response['data']->IdSucursal,'');

                    if ($rutafinal=='')
                    {//si ya existe la ruta
                        $rutafinal =$ruta.'/'.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal;
                    }

                    $nombre = Base64ToPathFix($response['data']->Foto,$nombre,$rutafinal);//convierte el base 64 en archivo imagen

                    $objUsuarios->Foto2 = $nombre;
                    $objUsuarios->IdUsuario = $response['data']->IdUsuario;
                    $objUsuarios->FechaMod= date("Y-m-d H:i:s");
                    $objUsuarios->update_Foto();
                    $response['data']->Foto2=$nombre;
                    //Actualizamos al trabajdor
                    $oMtrabajador = new Mtrabajador();
                    $oMtrabajador->IdUsuario=$response['data']->IdUsuario;
                    $datatrab= $oMtrabajador->get_trabajadoruser();

                    if($datatrab['status'])
                    {
                        if($datatrab['data']->Foto2=='')
                        {
                            $oMtrabajador->IdTrabajador=$datatrab['data']->IdTrabajador;
                            $oMtrabajador->Foto2 =$nombre;
                            $oMtrabajador->FechaMod = date("Y-m-d H:i:s");
                            $oMtrabajador->update_foto();
                        }
                    }
                }

                $opaquetexsucursal=new Mpaquetexsucursal();
                $opaquetexsucursal->IdSucursal=$response['data']->IdSucursal;
                $rowpaquete=$opaquetexsucursal->get_list();

                $arrayPaquete=array();
                foreach($rowpaquete as $itempaquete)
                {
                    $oMpaquete=new Mpaquete();
                    $oMpaquete->IdSucursal=$response['data']->IdSucursal;
                    $oMpaquete->Tipo='SubMenu';
                    $oMpaquete->Asociado=$itempaquete->IdPaquete;
                    $rowpaquetesub=$oMpaquete->get_list();
                    foreach($rowpaquetesub as $itemsub){
                        $arrayPaquete[]=array(
                            'IdPaquete'=>$itemsub->IdPaquete,
                            'Paquete'=>trim($itemsub->Nombre),
                        );
                    }
                }

                $response['data']->Perfil=$Perfil;
                $response['data']->listaPaquetes=$arrayPaquete;
                $data['Zona'] = $Zona;
                $data['usuario'] = $response['data'];
                $data['token'] = $jwtToken;
                $data['ruta'] = base_url().$this->RutaFoto.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal.'/';
                $data['cliente'] = $rescli['data'];

                if($dataEmpresa['status'])
                {
                    //actualziamos el logo de la empresa
                    if ($dataEmpresa['data']->Imagen !='' && $dataEmpresa['data']->Logo =='' )
                    {
                        $nombre = quitarCaracteres($dataEmpresa['data']->Nombre);
                        $rutafinal='assets/files/logo_empresa';

                        $nombre= Base64ToPathFix($dataEmpresa['data']->Imagen,$nombre,$rutafinal);//convierte el base 64 en archivo imagen

                        $oMempresa->IdEmpresa=$dataEmpresa['data']->IdEmpresa;
                        $oMempresa->Logo=$nombre;
                        $oMempresa->FechaMod= date("Y-m-d H:i:s");
                        $oMempresa->update_logo();
                        $dataEmpresa['data']->Logo=$nombre;
                    }

                    $data['empresa'] = $dataEmpresa['data'];
                }
                else
                {
                    $data['empresa'] = '';
                }
                $data['rutaE'] = base_url().$this->RutaFotoE;

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Logueado',
                ], REST_Controller::HTTP_OK);
            }
            else
            {
                return $this->set_response([
                    'status' => false,
                    'message' => $response['message'],
                ], REST_Controller::HTTP_BAD_REQUEST);
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
    } */

    private function getNamePerfil($objUsuario){
        $Perfil='';

        if($objUsuario->IdPerfil2>0){#Buscamos en la nueva tabla
            $oMperfil=new Mperfil();
            $oMperfil->IdPerfil=$objUsuario->IdPerfil2;
            $responsePerfil=$oMperfil->get_recovery();
            if($responsePerfil['status']){
                $Perfil=$responsePerfil['data']->Busqueda;
            }
        }

        if($Perfil==''){#Buscamos en la tabala antigua
            if($objUsuario->IdPerfil>0){
                $oMrol=new Mrol();
                $oMrol->IdRol=$objUsuario->IdPerfil;
                $responsePerfil=$oMrol->get_recovery();
                if($responsePerfil['status']){
                    $Perfil=$responsePerfil['data']->Nombre;
                }
            }
        }

        return $Perfil;
    }

    /* public function LoginRoot_post()
    {
        $v = new Valitron\Validator([
            'IdUsuario' => $this->post('IdUsuario')
        ]);

        $v->rule('required', [
            'IdUsuario'
        ])->message('El campo {field} es requerido.');

        if($v->validate()){

            $objUsuarios = new Musuarios();
            $objUsuarios->IdUsuario = $this->post('IdUsuario');
            $response = $objUsuarios->get_usuario_root();

            if($response['status']){
                #Dato del perfil
                $Perfil='';
                $Perfil=$this->getNamePerfil($response['data']);
                #Fin datos perfil

                // OBTENEMOS LA ZONA HORARIA CONFIGURADA O ESTABLECEMOS UNA POR DEFAULT
                $IdSuc = $response['data']->IdSucursal;

                $oMconfiguracion = new Mconfiguracion();
                $oMconfiguracion->RegEstatus = 'A';
                $oMconfiguracion->IdSucursal = $IdSuc;
                $datConfig = $oMconfiguracion->get_concepto();

                $Zona = 'America/Mexico_City';
                if($datConfig['status']){
                    $Zona = $datConfig['data']->ZonaHoraria;
                }

                $IdCliente=$response['data']->IdCliente;
                $jwtToken = $this->verification->LoginToken($response['data']->IdUsuario,$response['data']->Nombre,$response['data']->IdPerfil,date("Y-m-d h:i:s"), $response['data']->IdSucursal,  $response['data']->IdEmpresa,$IdCliente,$Perfil,$Zona);

                $rescli['data']=null;
                if($response['data']->IdCliente>0){
                    $oMclientes=new Mclientes();
                    $oMclientes->IdCliente=$IdCliente;
                    $rescli=$oMclientes->get_clientes();
                }

                //Cambios de lista de paquetes
                $opaquetexsucursal=new Mpaquetexsucursal();
                $opaquetexsucursal->IdSucursal=$response['data']->IdSucursal;
                $rowpaquete=$opaquetexsucursal->get_list();

                $arrayPaquete=array();
                foreach($rowpaquete as $itempaquete){
                    $oMpaquete=new Mpaquete();
                    $oMpaquete->IdSucursal=$response['data']->IdSucursal;
                    $oMpaquete->Tipo='SubMenu';
                    $oMpaquete->Asociado=$itempaquete->IdPaquete;
                    $rowpaquetesub=$oMpaquete->get_list();
                    foreach($rowpaquetesub as $itemsub){
                        $arrayPaquete[]=array(
                            'IdPaquete'=>$itemsub->IdPaquete,
                            'Paquete'=>trim($itemsub->Nombre),
                        );
                    }
                }
                //Fin de lista de paquetes
                $response['data']->listaPaquetes=$arrayPaquete;

                $oMempresa= new Mempresa();
                $oMempresa->IdEmpresa=$response['data']->IdEmpresa;
                $dataEmpresa= $oMempresa->get_empresa();

                $response['data']->Perfil=$Perfil;
                $data['Zona'] = $Zona;
                $data['usuario'] = $response['data'];
                $data['token'] = $jwtToken;
                $data['ruta'] = base_url().$this->RutaFoto.$response['data']->IdEmpresa.'/'.$response['data']->IdSucursal.'/';
                $data['cliente'] = $rescli['data'];
                if ($dataEmpresa['status'])
                {
                    $data['empresa'] = $dataEmpresa['data'];
                }
                else
                {
                   $data['empresa'] = '';
                }
                $data['rutaE'] = base_url().$this->RutaFotoE;

                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'message' => 'Logueado',
                ], REST_Controller::HTTP_OK);

            }else{

                return $this->set_response([
                    'status' => false,
                    'message' => $response['message'],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }

        }
        else{

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    } */


    public function findByToken_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $data = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objUsuarios = new Musuarios();

        if (empty($data['uniqueid'])) {

            return $this->set_unauthorized_response();
        } else {

            $objUsuarios->IdUsuario = $data['uniqueid'];
        }

        $response = $objUsuarios->get_usuario();

        if ($response['status']) {

            $data['usuario'] = $response['data'];
            $data['ruta'] = base_url();

            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {

            return $this->set_unauthorized_response();
        }
    }

    public function GeneratePassword_get(){

        $password = Password::generate(8);

        return $this->set_response([
            'status' => true,
            'password' => $password,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function UserPerfil_get()
    {
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $data = $this->verification->getTokenData($this->input->request_headers('Authorization'));

       $obUsuariosPerfil= new Musuarios();
       $obUsuariosPerfil->IdPerfil=$this->get('IdPerfil');
       $Ok['usuarios'] = $obUsuariosPerfil->get_listusers();
       $this->response($Ok);
    }

    public function AddUsuMonitoreo_post()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $Id = $this->post('IdUsuario');

        $val='123';
        if($Id==0){
            $val=$this->post('Seguridad');
        }

            $objUsuarios = new Musuarios();
            $objUsuarios->IdUsuario=$Id;
            $objUsuarios->Candado=$this->post('Candado');

            $Candado2='';
         if ($objUsuarios->exists_username()) {
                $Candado2 =$this->post('Candado');
            }


        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'Apellido' => $this->post('Apellido'),
            'Candado' => $this->post('Candado'),
            'Candado2' => $Candado2,
            'Seguridad' => $val,
        ]);

        $v->rule('required', [
            'Nombre',
            'Apellido',
            'Candado',
            'Seguridad',
        ])->message('El campo {field} es requerido.');

        $v->rule('email', 'Candado')->message('Correo electrónico no válido.');

         $v->rule('different','Candado', 'Candado2')->message('El usuario ya existe');
        //$v->rule('equals', 'Contraseña', 'Confirmar_Contraseña')->message('Las contraseñas no coinciden');
        //$v->rule('date', 'Nacimiento')->message('El campo {field} no es una fecha válida.');

        if ($v->validate())
        {
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

            $objUsuarios = new Musuarios();
            $objUsuarios->IdUsuario=$Id;
            $objUsuarios->IdPerfil=0;
            $objUsuarios->Nombre = $this->post('Nombre');
            $objUsuarios->Apellido = $this->post('Apellido');

            $objUsuarios->Candado = $this->post('Candado');
            if (!empty($this->post('Seguridad')))
            {
                $objUsuarios->Password = Password::hash($this->post('Seguridad'));
            }
            $objUsuarios->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objUsuarios->IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
            $objUsuarios->IdCliente=$this->post('IdCliente');
            $objUsuarios->Foto='';
            $objUsuarios->ColorFondo='';
            $objUsuarios->FechaMod = date('Y-m-d H:i:s');


            if ($Id > 0) {
                $objUsuarios->update_usuariomonitoreo();

                $response = $objUsuarios->get_usuario();

                $data['usuario'] = $response['data'];
                $resmail=  $this->sendmailservices($response['data'],$IdEmpresa,$IdSucursal,$this->post('Seguridad'));
                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'resmail'=>$resmail,
                    'message' => 'Se ha actualizado correctamente.',
                ], REST_Controller::HTTP_CREATED);
            } else {
                $objUsuarios->IdUsuario=$objUsuarios->insert_usuariomonitoreo();

                $response = $objUsuarios->get_usuario();
                $data['usuario'] = $response['data'];
                $resmail =  $this->sendmailservices($response['data'],$IdEmpresa,$IdSucursal,$this->post('Seguridad'));
                return $this->set_response([
                    'status' => true,
                    'data' => $data,
                    'resmail'=>$resmail,
                    'message' => 'Se ha agregado correctamente.',
                ], REST_Controller::HTTP_CREATED);
            }
        } else {

            $data['errores'] = $v->errors();
            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function sendmailservices($data,$IdEmpresa,$IdSucursal,$password)
    {
        $resmail ='';
        //Lista de trabajadores

        $oMempresa= new Mempresa();
        $oMempresa->IdEmpresa=$IdEmpresa;
        $dataempresa= $oMempresa->get_empresa();

        $oMsucursal= new Msucursal();
        $oMsucursal->IdSucursal=$IdSucursal;
        $datasucursal= $oMsucursal->get_sucursal();


        $dataview['empresa']=$dataempresa;
        $dataview['datos']=$data;
        $dataview['pass']=$password;
        $dataview['RutaTrab']=base_url().'assets/foto_trabajador/'.$IdEmpresa.'/'.$IdSucursal.'/';
        $dataview['RutaLogo']=base_url().'assets/logo_empresa/';
        $dataview['link']=returnLink();
        $dataview['Tipo']=2;

        $vista=$this->load->view('catalogos/correo/user.php',$dataview,TRUE);


        $oMail=new Mail();
        $oMail->To=$this->post('Candado');
        $oMail->Subject='Bienvenido al sistema';
        $oMail->Message=$vista;
        $resmail=$oMail->SendEmail();

        return $resmail;
    }
}
?>
