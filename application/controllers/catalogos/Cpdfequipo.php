<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cpdfequipo extends REST_Controller
{
    public $RutaPdf;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mpdfequipo');
        $this->load->library('UploadFile');
        $this->RutaPdf='assets/files/pdf_equipo/';
        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];

        $oMpdfequipo = new Mpdfequipo();
        $oMpdfequipo->IdEquipo=$this->get('IdEquipo');
        $oMpdfequipo->Titulo=$this->get('Nombre');
        $oMpdfequipo->RegEstatus='A';
        // Paginación
        $rows =  $oMpdfequipo->get_list();
        
                $Entrada=10;
            if ($this->get('Entrada')!='')
            {
               $Entrada =$this->get('Entrada');
            }
             $oMpdfequipo->Limit=$Entrada;


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $oMpdfequipo->Tamano = $pager->PageSize;
        $oMpdfequipo->Offset = $pager->Offset;
        $rows=$oMpdfequipo->get_list();
        

        $data['pdfequipo'] =$rows;
        $data['UrlFile'] =base_url().$this->RutaPdf.$IdEmpresa.'/'.$IdSucursal.'/';
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
        $oMpdfequipo = new Mpdfequipo();
        $oMpdfequipo->RegEstatus='B';
       
        $oMpdfequipo->IdPdf = $Id;
  
        $response =   $oMpdfequipo->get_recovery();

        if ($response['status']) {
             $oMpdfequipo->FechaMod=date('Y-m-d H:i:s');
            if ($oMpdfequipo->delete()) {

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


    public function Recovery_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $oMpdfequipo = new Mpdfequipo();

        $Id = (int) $this->get('IdPdf');
     

        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $oMpdfequipo->IdPdf = $Id;
        }
        $response =   $oMpdfequipo->get_recovery();
        if ($response['status']) {
            $data['PdfEquipo'] = $response['data'];
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

    public function Add_post() {
        // Valid Token
        
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
        
        $Id = (int)$this -> post('IdPdf');
        $NombreArchivo=$this -> post('NombreArchivo');
        if($NombreArchivo==''){
            if(isset($_FILES['File'])){
                $NombreArchivo=$_FILES['File']['name'];    
            }
            
        }
            
        $v = new Valitron\Validator([
            'NombreArchivo' => $NombreArchivo,
            'Titulo' => $this -> post('Titulo'),

        ]);

        $v -> rule('required', [
            'NombreArchivo' ,
            'Titulo',
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            
            
    
            $oMpdfequipo = new Mpdfequipo();
            $oMpdfequipo->IdPdf=$Id;
            $oMpdfequipo->IdEquipo= $this->post('IdEquipo');
            $oMpdfequipo->Titulo = $this->post('Titulo');
            
            $oMpdfequipo->FechaAlta = date('Y-m-d H:i:s');
            $oMpdfequipo->RegEstatus = 'A';
            $oMpdfequipo->FechaMod = date('Y-m-d H:i:s');
            
            $UploadFile=new UploadFile();
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
            
            $RutaPrincipal=$this->RutaPdf.$IdEmpresa.'/';
            if (!is_dir($RutaPrincipal)) {
                mkdir($RutaPrincipal); //Directory does not exist, so lets create it.
            }
            
            $route =$RutaPrincipal.$IdSucursal.'/';
            
            $files = $this->uploadfile->savefile($route, 'File',$this->post('NombreArchivo'), '*', UploadFile::SINGLE);
            $oMpdfequipo->NombreArchivo=$files;
            
            if ($Id==0) {
                
                $Id = $oMpdfequipo-> insert();
                if ($Id > 0) {
                    
                    
                    $oMpdfequipo->IdPdf = $Id;
                    $response = $oMpdfequipo-> get_recovery();
                    $data['pdfequipos'] = $response['data'];
                    
                    
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
            } else {
            
                if ($oMpdfequipo-> update()) {
                
                    $response = $oMpdfequipo -> get_recovery();
                    $data['pdfequipos'] = $response['data'];

                    return $this -> set_response([
                        'status' => true,
                        'data' => $data,
                        'message' => 'Se ha actualizado correctamente.',
                    ], REST_Controller:: HTTP_ACCEPTED);
                } else {

                    return $this -> set_response([
                        'status' => false,
                        'message' => 'Error al actualizar los datos de la base de datos.',
                    ], REST_Controller:: HTTP_BAD_REQUEST);
                }
            }
        }else {
            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}