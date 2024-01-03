
<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Choraslaborales extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mhoraslaborales');
        setTimeZone($this->verification,$this->input);
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objHoraslaborales = new Mhoraslaborales();
        $objHoraslaborales->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
       
        // Paginación
        $rows =  $objHoraslaborales->get_list();
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));
        $objHoraslaborales->Tamano = $pager->PageSize;
        $objHoraslaborales->Offset = $pager->Offset;

        $data['horaslaborales'] =  $objHoraslaborales->get_list();
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
        $objHoraslaborales = new Mhoraslaborales();
        $objHoraslaborales->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];    
        $response =$objHoraslaborales->get_horaslaborales();

         
        if ($response['status']) {
            $data['horaslaborales'] = $response['data'];
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

        $v = new Valitron\Validator([
            'Hora_I' => $this -> post('Hora_I'),
            'Hora_F' => $this -> post('Hora_F'),

        ]);

        $v -> rule('required', [
            'Hora_I',
            'Hora_F',
        ]) -> message('El campo {field} es requerido.');

        if ($v -> validate()) {

            $Id = $this -> post('IdHorasL');

            $objHoraslaborales = new Mhoraslaborales();
            $objHoraslaborales ->IdHorasL = $this -> post('IdHorasL');
            $objHoraslaborales ->Hora_I = $this->post('Hora_I');
            $objHoraslaborales ->Hora_F = $this->post('Hora_F');
            $objHoraslaborales ->IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objHoraslaborales ->Intervalo = '30:00';
            $objHoraslaborales->FechaMod=date('Y-m-d H:i:s');

            if ($objHoraslaborales ->IdHorasL == 0) {

                $Id = $objHoraslaborales -> insert();
                if ($Id > 0) {
                    $objHoraslaborales -> IdHorasL = $Id;
                    $response = $objHoraslaborales -> get_horaslaborales();
                    $data['horaslaborales'] = $response['data'];
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
                if ($objHoraslaborales -> update()) {
                    $response = $objHoraslaborales -> get_horaslaborales();
                    $data['horaslaborales'] = $response['data'];

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
        }else{
                    
            $this->set_response([
                'status' => false,
                'message' => 'No encontrado.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function ListHoras_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objHoraslaborales = new Mhoraslaborales();
        $objHoraslaborales->IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $response=$objHoraslaborales->get_horaslaborales();
         
        $Hora_I=explode(':',$response['data']->Hora_I);
        $Hora_F=$response['data']->Hora_F;
        
        
        $HoraI=$Hora_I[0];
        $HoraMin=$Hora_I[1];
        
        $arrHoras=array();
        array_push($arrHoras,$response['data']->Hora_I);
        $HoraFinal=$Hora_F;
        for($i=0;$i<=90;$i++){
       
            
            if($HoraMin==30){
                $HoraI ++;  
                $HoraMin='00';  
            }else{
                $HoraMin ='30';
            }
            $HoraFinal=$HoraI.':'.$HoraMin;
            
            if($HoraFinal>=24){
                $HoraFinal='00:00';
            }
           
           array_push($arrHoras,$HoraFinal);
            if($HoraFinal==$Hora_F){ 
                break;
            }
        }
         $data['horaslaborales'] =$arrHoras;
        
        if ($response['status']) {
            $data['horaslaborales'] =$arrHoras;
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
    
    public function ListaHoras_get()
    {
        $arrHoras=array();
        $HoraMin='00';
        $HoraI='00';
        array_push($arrHoras,'00:00');
        //$Hora_F='00:00';
        $Hora_F='24:00';
        //$Hora_F='23:59';
        for($i=0;$i<=90;$i++){    
            if($HoraMin==30){
                $HoraI ++;  
                $HoraMin='00';  
            }else{
                $HoraMin ='30';
            }
            $HoraFinal=$HoraI.':'.$HoraMin;
            
            if($HoraFinal>=24){
                $HoraFinal='24:00';
            }
           
           array_push($arrHoras,$HoraFinal);
            if($HoraFinal==$Hora_F){ 
                break;
            }
        }
         $data['horaslaborales'] =$arrHoras;
         
         return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}