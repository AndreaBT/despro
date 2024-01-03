<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cmail extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Musuarios');
        $this->load->library('UploadFile');

        setTimeZone($this->verification,$this->input);
    }

    public function Send_post()
    { 
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $v = new Valitron\Validator([
            'Para' => $this->post('Para'),
            'Asunto' => $this->post('Asunto'),
            'Mensaje' => $this->post('Mensaje'),
            'Sucursal' => $this->post('IdSucursal')
        ]);

        $v->rule('required', [
            'Para',
            'Asunto',
            'Mensaje',
            'Sucursal'
        ])->message('El campo {field} es requerido.');

        if ($v->validate()) {

            if((int)$this->post('Firma') == 1){
                $token = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        
                $objUsuarios = new Musuarios();

                if (empty($token['uniqueid'])) {

                    return $this->set_unauthorized_response();
                } else {

                    $objUsuarios->IdUsuario = $token['uniqueid'];
                }
                
                $response = $objUsuarios->get_usuario();

                $data['firma'] = 1;
                $data['urlFirma'] = base_url() . $response['data']->Firma;
            }else{
                $data['firma'] = 0;
            }

            $para = $this->post('Para');
            $asunto = $this->post('Asunto');
            $menjase = $this->post('Mensaje');
            
            $data['mensaje']= $menjase;

            $message = $this->load->view('catalogos/correo/correo.php', $data, TRUE);

            $route = 'assets/files/mails/'.$this->post('IdSucursal').'/';
            $files = $this->uploadfile->savefile($route, 'File', '', '*', UploadFile::MULTIPLE);

            $cc = str_replace(array("\t","\n"), "", $this->post('CC'));
            $arrayCC = json_decode($cc); // Array Con Copia

            $bcc = str_replace(array("\t","\n"), "", $this->post('CCO'));
            $arrayBCC = json_decode($bcc); // Array Con Copia Oculta

            $response = Mail::SendEmail($para, $asunto, $message, $arrayCC, $arrayBCC, $files);

            return $this->set_response([
                'status' => true,
                'message' => $response,
            ], REST_Controller::HTTP_OK);

        }else{
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }   
    }
}