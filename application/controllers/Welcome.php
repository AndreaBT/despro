<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function mensaje_get()
    {
        $this->response([
            'status' => REST_Controller::HTTP_OK,
            'data' => 'Hola, ¿cómo estás?',
            'message' => 'Success'], REST_Controller::HTTP_OK);
    }

    public function mensaje_post()
    {
        if (empty($this->post('Nombre')) && empty($this->post('Apellido'))) {
            $message = '';

            if (empty($this->post('Nombre'))) {
                $message = 'El campo nombre es obligatorio. ';
            }

            if (empty($this->post('Apellido'))) {
                $message .= 'El campo apellido es obligatorio. ';
            }

            return $this->set_response([
                'status' => REST_Controller::HTTP_BAD_REQUEST,
                'error' => 'Error de validación.',
                'message' => $message,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }else{
			return $this->response([
				'status' => REST_Controller::HTTP_CREATED,
				'data'   => 'Bienvenido ' . $this->post('Nombre') . ' ' . $this->post('Apellido'),
				'message'=> 'Success'
				 ], REST_Controller::HTTP_CREATED);
		}

    }

    public function Form()
    {
        $this->load->model('Mproyectos');

        $objProyectos = new Mproyectos();
        $objProyectos->IdSucursal = 1;

        $response = $objProyectos->get_list();

        $data['lista'] = $response;

        $this->load->view('catalogos/formularios/form', $data);
    }
}
