<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cequipos extends REST_Controller
{
    public $RutaQr;
    public $RutaEquipo;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mequipo');
        $this->load->model('Mtipounidad');
        $this->load->library('Ciqrcode');

        setTimeZone($this->verification,$this->input);

        $this->RutaQr='assets/files/qr_equipo/';
        $this->RutaEquipo='assets/files/iconos_eq/';
    }

    public function findAll_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objEquipos = new Mequipo();
        $objEquipos->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $objEquipos->RegEstatus= $this->get('RegEstatus');
        $objEquipos->IdClienteS = $this->get('IdClienteS');
        $objEquipos->Nequipo = $this->get('Nombre');

        // Paginación
        $rows =  $objEquipos->get_list();

        $Entrada=10;
        if ($this->get('Entrada')!='')
        {
            $Entrada =$this->get('Entrada');
        }
            $objEquipos->Limit=$Entrada;


        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objEquipos->Tamano = $pager->PageSize;
        $objEquipos->Offset = $pager->Offset;
        $rows=$objEquipos->get_list();


        $contador=0;
        foreach($rows as $element){
            $rows[$contador]->RutaQr=base_url().$this->RutaQr.$element->IdEquipo.'.png';
            $rows[$contador]->ImgSvg = base_url().$this->RutaEquipo.$element->Imagen;
            $contador ++;
        }

        $data['equipos'] =$rows;
        $data['pagination']= $pager;

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'RutaEquipo'=>base_url().$this->RutaEquipo,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Delete_delete($Id)
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $objEquipos = new Mequipo();
        $objEquipos->RegEstatus='B';

        $objEquipos->IdEquipo = $Id;

        $response =   $objEquipos->get_equipos();

        if ($response['status']) {
             $objEquipos->FechaMod=date('Y-m-d H:i:s');
            if ($objEquipos->delete()) {

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
        $objEquipos= new Mequipo();

        $Id = (int) $this->get('IdEquipo');


        if (empty($Id)) {

            return $this->set_response([
                'status' => false,
                'message' => 'Parámetros no recibidos.',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $objEquipos->IdEquipo = $Id;
        }
        $response =   $objEquipos->get_equipos();
        if ($response['status']) {
            $data['Equipo'] = $response['data'];
            $qrImage=$this->RutaQr.$Id.'.png';
            if(file_exists($qrImage)){
                $qrImage=base_url().$qrImage;
            }
            $data['RutaQr'] =$qrImage;

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
            'IdCliente' => $this -> post('IdCliente'),
            'Ubicacion' => $this -> post('Ubicacion'),
            'Marca' => $this -> post('Marca'),
            'Modelo' => $this -> post('Modelo'),

            'Serie' => $this -> post('Serie'),
            'TipoUnidad' => $this -> post('TipoUnidad'),
            'Ano' => $this -> post('Ano'),
            'IdClienteS' => $this -> post('IdClienteS'),
            'Nequipo' => $this -> post('Nequipo'),

        ]);

        $v -> rule('required', [
            'IdCliente' ,
            'Ubicacion',
            'Marca',
            'Modelo',

            'Serie',
            'TipoUnidad',
            'Ano',
            'IdClienteS',
            'Nequipo',
        ]) -> message('El campo {field} es requerido.');

        $v->rule('lengthMax',[
            'Nequipo'
        ],22)->message('El Nombre del Equipo no debe ser mayor a 15 caracteres');

        if ($v -> validate()) {

            $Id = (int)$this -> post('IdEquipo');




            $objEquipos = new Mequipo();
            $objEquipos->IdEquipo=$Id;
            $objEquipos->IdCliente = $this->post('IdCliente');
            $objEquipos->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $objEquipos->Ubicacion = $this->post('Ubicacion');
            $objEquipos->Marca = $this->post('Marca');
            $objEquipos->Modelo = $this->post('Modelo');
            $objEquipos->Serie = $this->post('Serie');
            $objEquipos->TipoUnidad = $this->post('TipoUnidad');
            $objEquipos->Ano= $this->post('Ano');
            $objEquipos->RegEstatus= "A";
            $objEquipos->IdClienteS = $this->post('IdClienteS');
            $objEquipos->Nequipo= $this->post('Nequipo');
            $objEquipos->FechaMod = date('Y-m-d H:i:s');

            if ($Id==0) {

                $Id = $objEquipos-> insert();
                if ($Id > 0) {
                    $objEquipos->IdEquipo = $Id;
                    $response = $objEquipos-> get_equipos();
                    $data['equipos'] = $response['data'];

                    $this->generate_qr($Id);

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

                if ($objEquipos-> update()) {
                    $this->generate_qr($objEquipos->IdEquipo);
                    $response = $objEquipos -> get_equipos();
                    $data['equipos'] = $response['data'];

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

    public  function generate_qr($Id){
        $ruta=$this->RutaQr."$Id.png";
        if(!file_exists($ruta)){
            //hacemos configuraciones
            $params['data'] = $Id;
            $params['level'] = 'H';
            $params['size'] = 10;

            //decimos el directorio a guardar el codigo qr, en este
            //caso una carpeta en la raíz llamada qr_code
            $params['savename'] =$ruta;
            //generamos el código qr
            $this->ciqrcode->generate($params);
        }
    }

    public function Historial_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));

        $objEquipos = new Mequipo();
        $objEquipos->IdSucursal =$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $objEquipos->Folio=$this->get('Nombre');
        $objEquipos->IdEquipo = $this->get('IdEquipo');

        // Paginación
        $rows =  $objEquipos->get_list_historial();
		$Entrada = 10;
		if ($this->get('Entrada') != '') {
			$Entrada = $this->get('Entrada');
		}
		
		$objEquipos->Limit = $Entrada;
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $Entrada);

        $objEquipos->Tamano = $pager->PageSize;
        $objEquipos->Offset = $pager->Offset;

        $rows = $objEquipos->get_list_historial();


        return $this->set_response([
            'status' => true,
            'historial' => $rows,
            'pagination' => $pager,
            'RutaEquipo'=>base_url().$this->RutaEquipo,
            'RutaQr'=>base_url().$this->RutaQr,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
