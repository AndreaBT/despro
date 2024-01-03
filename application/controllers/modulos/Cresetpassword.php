<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

use Valitron\Validator;

class Cresetpassword extends REST_Controller
{
    public $RutaFile;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Mail');
        $this->load->model('Musuarios');
        $this->load->model('MtblResetPass');
    }

  
    //Fin de nueva lista 
    public  function ComprobarUsuario_post() {
        // Valid Token
        $Correo=trim($this->post('Correo'));
        
        $v = new Valitron\Validator([
            'Correo' => $this->post('Correo'),
        ]);
    
        $v -> rule('required', ['Correo']) -> message('El campo  es requerido.');

        //$v->rule('equals', 'LlavePass', 'ConfirmarPassword')->message('Las contraseñas no coinciden');
        $v->rule('email', 'Correo')->message('El correo no es válido.');

        if ($v -> validate())
        {  
            $oMusuarios=new Musuarios();
            $oMusuarios->Candado=$Correo;
            $oMusuarios->Estatus='A';
            $response=$oMusuarios->get_usuario_Correo();
            
            if(!$response['status']){
                 return $this->set_response([
                    'status' => true,
                    'typemsj' => 1,
                    'message' => 'El usuario con el correo '.$Correo .' no existe en el sistema',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $IdUsuario=$response['data']->IdUsuario;
            
            $datamail['Nombre']=$response['data']->Nombre;
            $datamail['IdUsuario']=$IdUsuario;
            $datamail['Correo']=$Correo;
            
            // GENERAMOS LA CADENA DEL ENLACE
            $cadena = $IdUsuario.$Correo.rand(1,9999999).date('Y-m-d');
            $token = sha1($cadena);
            //'http://192.168.0.107:8080/'
            $Base ='http://192.168.1.166:8080/';
            $enlace = $Base.'#/FormConfirm/'.sha1($IdUsuario).'/'.$token;
            
            $datamail['enlace']=$enlace;
            
            $Id = 0;
    
            $obj = new MtblResetPass();
            $obj->IdReset 	= $Id;
			$obj->IdUsuario = trim($IdUsuario);
	        $obj->Candado 	= trim($Correo);
	        $obj->token 	= trim($token);
	        $obj->fechaReg 	= date('Y-m-d H:i:s');
	        $obj->email		= trim($Correo);
            $obj->RegEstatus= "A";
           
            $Mensage = $this->load->view('catalogos/correo/recuperaPassword.php',$datamail,TRUE);
            $omail=new Mail();
            $omail->To=$Correo;
            $omail->Subject='Desprosoft - Recuperación de contraseña';
            $omail->Message=$Mensage;
            
            
           $newId = $obj->insert();
           if($newId>0){
                $result=$omail->SendEmail();
                return $this->set_response([
                    'status' => true,
                    'typemsj' => 1,
                    'message' => 'Información enviada',
                ], REST_Controller::HTTP_OK);
           }else{
                return $this->set_response([
                    'status' => false,
                    'typemsj' => 1,
                    'message' => 'Su informacion no pudo ser guardada',
                ], REST_Controller::HTTP_BAD_REQUEST);
           }
		
        }else{
            $data['errores'] = $v->errors();
            return $this->set_response([
                'status' => false,
                'typemsj' => 2,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    
    public function ValidarTkn_get()
    {
        $IdUsuario = trim($this->get('IdUser'));
        $token = trim($this->get('tkn'));
        
        $datosObjeto = new MtblResetPass();
        $datosObjeto->token = $token;
        $response = $datosObjeto->get_tblResetPass_token();

        //echo sha1(5);

        if($response['status'])
		{
            // COMPROBAMOS QUE EL USUARIO DEL TOKEN ES IGUAL AL DE LA BASE
            if(sha1($response['data']->IdUsuario) == $IdUsuario)
            {
                $data['tblResetPass'] = $response['data'];
                return $this -> set_response([
                    'status' => true,
                    'data' => $data,
                    'typemsj' => 1,
                    'message' => 'Usuario Valido.',
                ], REST_Controller:: HTTP_CREATED);
            }
            else
            {
                return $this->set_response([
                    'status' => false,
                    'typemsj' => 1,
                    'message' => 'El usuario no coincide.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
        else
        {
            return $this->set_response([
                'status' => false,
                'typemsj' => 1,
                'message' => 'El Token no es valido.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function UpdatePassword_post()
    {
        $IdUsuario = trim($this->post('IdUsuario'));
        $token = trim($this->post('token'));

        $v = new Valitron\Validator([
            'password' => trim($this->post('password')),
            'confirmpassword' =>trim($this->post('confirmpassword')),
        ]);
    
        $v->rule('required',['password','confirmpassword'])->message('El campo  es requerido.');
        $v->rule('equals', 'password', 'confirmpassword')->message('Las contraseñas no coinciden');

        if($v->validate())
        {
            $datosObjeto = new MtblResetPass();
            $datosObjeto->token = $token;
            $response = $datosObjeto->get_tblResetPass_token();

            if($response['status'])
		    {
                // COMPROBAMOS QUE EL USUARIO DEL TOKEN ES IGUAL AL DE LA BASE
                if(sha1($response['data']->IdUsuario) == $this->post('user'))
                {
                    // SI ES IGUAL ENTONCES ACTUALIZAMOS LA CONTRASEÑA Y ELIMINAMOS EL TOKEN Y 
                    // CUALQUIER OTRO TOKEN DEL MISMO NOMBRE DE USUARIO
                 
                    $usuario = new Musuarios();
                    $usuario->IdUsuario = $IdUsuario;
                    $usuario->Password = Password::hash(trim($this->post('password')));
                    $usuario->FechaMod = date('Y-m-d H:i:s');
                    

                    if($usuario->resset_password())
                    {
                        $reset = new MtblResetPass();
                        $reset->IdReset = $this->post('IdReset');
                        $reset->delete();

                        $reset->Candado = $response['data']->Candado;
                        $reset->deleteUsersname();

                        $data['tblResetPass'] = $response['data'];
                        return $this -> set_response([
                            'status' => true,
                            'data' => $data,
                            'typemsj' => 1,
                            'message' => 'Se ha Actulizado sus credenciales correctamente.',
                        ], REST_Controller:: HTTP_CREATED);
                    }
                    else
                    {
                        return $this -> set_response([
                            'status' => false,
                            'message' => 'Error al actualizar sus credenciales en la base de datos.',
                            'typemsj' => 1,
                        ], REST_Controller:: HTTP_BAD_REQUEST);
                    }
                }
                else
                {
                    return $this->set_response([
                        'status' => false,
                        'typemsj' => 1,
                        'message' => 'El usuario no coincide.',
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
            else
            {
                return $this->set_response([
                    'status' => false,
                    'typemsj' => 1,
                    'message' => 'El Token no es valido.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
        else
        {
            $data['errores'] = $v->errors();
    
            return $this->set_response([
                'status' => false,
                'typemsj' => 2,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}