
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cempresa extends REST_Controller
{

    public $RutaFoto;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mempresa');
        $this->load->model('Musuarios');
        $this->load->model('Msucursal');
        $this->load->model('Mconfiguracion');

        $this->load->library('UploadFile');
        $this->RutaFoto='assets/files/logo_empresa/';

        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objEmpresa = new Mempresa();

        if (empty($this->get('root')))
        {
            $objEmpresa->IdEmpresa = $array['uniqueid'];
        }



        // Filtros

        if (empty($this->get('RegEstatus'))) {

            $objEmpresa->RegEstatus = 'A';
        } else {

            $objEmpresa->RegEstatus = $this->get('RegEstatus');
        }


        $objEmpresa->Nombre = $this->get('Nombre');
        $objEmpresa->RFC = $this->get('RFC');
        $objEmpresa->Direccion = $this->get('Direccion');
        $objEmpresa->Telefono = $this->get('Telefono');
        $objEmpresa->Correo = $this->get('Correo');
        $objEmpresa->Ciudad = $this->get('Ciudad');
        $objEmpresa->Pais = $this->get('Pais');
        $objEmpresa->Estado = $this->get('Estado');
        $objEmpresa->CP = $this->get('CP');
        $objEmpresa->RegEstatus= $this->get('RegEstatus');
        $objEmpresa->Imagen= $this->get('Imagen');

        // Paginación

        $rows = $objEmpresa->get_list();

         $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $objEmpresa->Limit=$Entrada;

        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objEmpresa->Tamano = $pager->PageSize;
        $objEmpresa->Offset = $pager->Offset;

        $data['empresa'] = $objEmpresa->get_list();
        $data['pagination']= $pager;
        $data['headers']= array('Nombre','RFC','Direccion','Teléfono','Correo','Ciudad','Pais','Desprosoft','Acciones');

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
        $objEmpresa = new Mempresa();
        $Id = (int) $this->get('IdEmpresa');

        if ($Id<=0) {
            $objEmpresa->IdEmpresa = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
            $Id= $objEmpresa->IdEmpresa;
        } else {

            $objEmpresa->IdEmpresa = $Id;
        }

        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $oMconfiguracion=new Mconfiguracion();
        $oMconfiguracion->RegEstatus='A';
        $oMconfiguracion->IdSucursal=$IdSucursal;
        $responseconfig=$oMconfiguracion->get_concepto();
        $ZonaHoraria='';
        if($responseconfig['status']){
           $ZonaHoraria= $responseconfig['data']->ZonaHoraria;
        }

        $response = $objEmpresa->get_empresa();
        if ($response['status']) {
            $data['RutaFile'] = base_url().$this->RutaFoto;
            $data['empresa'] = $response['data'];
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'ZonaHoraria' => $ZonaHoraria,
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

        $objEmpresa = new Mempresa();
        $objEmpresa->IdEmpresa = $Id;

        $response = $objEmpresa->get_empresa();

        if ($response['status']) {

            if ($objEmpresa->delete()) {


                $oMusuarios = new Musuarios();
                $oMusuarios->IdEmpresa=$Id;
                $oMusuarios->FechaMod=date('Y-m-d H:i:s');
                $oMusuarios->Estatus='B';
                $oMusuarios->update_usuariosbaja();

                $oMsucursal = new Msucursal();
                $oMsucursal->IdEmpresa=$Id;
                $oMsucursal->delete_all();


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

        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $ZonaHoraria='N/A';
        if($IdSucursal>0){
            $ZonaHoraria=trim($this->post('ZonaHoraria'));
        }

        $v = new Valitron\Validator([
            'Nombre' => $this->post('Nombre'),
            'RFC' => $this->post('RFC'),
            'Direccion' => $this->post('Direccion'),
            'Telefono' => $this->post('Telefono'),
            'Correo' => $this->post('Correo'),
            'Ciudad' => $this->post('Ciudad'),
            'Pais' => $this->post('Pais'),
            'Estado' => $this->post('Estado'),
            'CP' => $this->post('CP'),
            'Imagen' => $this->post('Imagen'),
            'Type' => $this->post('Type'),
            'CotMant' => $this->post('CotMant'),
            'CotServ' => $this->post('CotServ'),
            'ZonaHoraria' => $ZonaHoraria,
        ]);

        $v->rule('required', [
            'Nombre','RFC','Direccion','Telefono','Correo','Ciudad','Pais','Estado','CP','ZonaHoraria'
        ])->message('El campo } es requerido.');

        $v->rule('email', 'Correo')->message('Correo electrónico no válido.');

        if ($v->validate()) {
            $Id = $this->post('IdEmpresa');
            $route = $this->RutaFoto;
            $files = '';

            if(!empty($_FILES['File']['name'])) {
                $files = $this->uploadfile->savefile($route, 'File',$this->post('NombreFoto'), '*', UploadFile::SINGLE);
            }

            $objEmpresa = new Mempresa();
            $objEmpresa->Nombre = $this->post('Nombre');
            $objEmpresa->RFC = $this->post('RFC');
            $objEmpresa->Direccion = $this->post('Direccion');
            $objEmpresa->Telefono = $this->post('Telefono');
            $objEmpresa->Correo = $this->post('Correo');
            $objEmpresa->Ciudad= $this->post('Ciudad');
            $objEmpresa->Pais = $this->post('Pais');
            $objEmpresa->Estado= $this->post('Estado');
            $objEmpresa->CP = $this->post('CP');
            $objEmpresa->RegEstatus ='A';
            $objEmpresa->Imagen = $this->post('Imagen');
            $objEmpresa->Type = $this->post('Type');
            $objEmpresa->CotMant = $this->post('CotMant');
            $objEmpresa->CotServ = $this->post('CotServ');
            $objEmpresa->FechaMod = date('Y-m-d H:i:s');
            $objEmpresa->Logo = $files;

            //Si el IdSucursal es mayor a 0 se crea la zona horaria
            $this->create_zonahoraria($IdSucursal,$ZonaHoraria);

            if (empty($Id)) {
                 $IdEmpresa = $objEmpresa->insert();
                if ( $IdEmpresa>0)
                {

                    $objEmpresa->IdEmpresa=$IdEmpresa;
                    $response =   $objEmpresa->get_empresa();
                    $data['empresa'] = $response['data'];

                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha agregado correctamente.',
                    ], REST_Controller::HTTP_CREATED);
                }
                else
                {
                     return $this->set_response([
                    'status' => false,
                    'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $objEmpresa->IdEmpresa=$Id;

                 if ($objEmpresa->update())
                 {
                    $objEmpresa->IdEmpresa=$Id;
                    $response =   $objEmpresa->get_empresa();
                    $data['empresa'] = $response['data'];

                    return $this->set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha modificado correctamente.',
                    ], REST_Controller::HTTP_ACCEPTED);
                 }
                 else
                 {
                    return $this->set_response([
                        'status' => false,
                        'message' => 'Error al agregar a la base de datos.',
                    ], REST_Controller::HTTP_BAD_REQUEST);
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


    function create_zonahoraria($IdSucursal,$ZonaHoraria){

        if($IdSucursal>0){
            $oMconfiguracion=new Mconfiguracion();
            $oMconfiguracion->IdSucursal=$IdSucursal;
            $oMconfiguracion->RegEstatus='A';
            $oMconfiguracion->ZonaHoraria=$ZonaHoraria;
            $response=$oMconfiguracion->get_concepto();

            if($response['status']){
                $oMconfiguracion->update();
            }else{
                $oMconfiguracion->insert();
            }
        }
    }
}
