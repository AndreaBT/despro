<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cequiposqr extends REST_Controller
{
	public $RutaQr;
	public $RutaEquipo;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mequipo');
		$this->load->library('Ciqrcode');


		$this->RutaQr = 'assets/files/qr_equipo/';
		$this->RutaEquipo = 'assets/files/iconos_eq/';
	}

	private function generate_qr($Id){
		//$ruta = $this->RutaQr."$Id.png";
		//$ruta = $_SERVER['DOCUMENT_ROOT'].'/desprosoft4/'.$Imagen;

		$ruta = $_SERVER['DOCUMENT_ROOT'].'services/'.$this->RutaQr."$Id.png";

		if(!file_exists($ruta)) {
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


	public function conseguirArchivo_get() {

		$idequipo = $this->get('IdEquipo');
		$objEquipos = new Mequipo();
		$objEquipos->IdEquipo=$idequipo;
		$res= $objEquipos->get_equipos();

		if ($res['status']) {

			$ruta = $_SERVER['DOCUMENT_ROOT'].'services/'.$this->RutaQr."$idequipo.png";

			if(!file_exists($ruta)) {
				$this->generate_qr($idequipo);
			}

			$data = $res['data'];
			$fileUrl = base_url() .$this->RutaQr.$data->IdEquipo.'.png';
			//$output = basename($archivoObtenido -> Archivo, '.' . $archivoObtenido -> Extension) . '.' . $archivoObtenido -> Extension;

			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename="' . $data->IdEquipo.'.png' . '"');

			readfile($fileUrl);
		}
	}

}
