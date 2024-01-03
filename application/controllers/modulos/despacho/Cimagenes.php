<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Cimagenes extends REST_Controller
{
    public $RutaQr;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('Mimagenequipo2');
        $this->load->model('Mequipocomentario');
        
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
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
        $v = new Valitron\Validator([
            'IdServicio' => $this -> get('IdServicio')
        ]);

        $v -> rule('required', [
            'IdServicio'
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {
            //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
            $ruta1=base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/';
            $ruta2=returnLinkFotosServicios().$this ->get('IdServicio').'/';
            $Mimagenequipo2= new Mimagenequipo2();
            $Mimagenequipo2->IdServicio=$this -> get('IdServicio');
            $Mimagenequipo2->Validar='Igual';
        
            // Paginaci�n
            $resp =  $Mimagenequipo2->get_listImgServicios();
            $contadorFile = 0;
            foreach ($resp as $element)
            {
                if ($element->Mostrar=='s')
                {
                    $element->Mostrar =true;
                }
                else
                {
                    $element->Mostrar =false;
                }

                if($element->Tipo == 1){
                    $resp[$contadorFile]->FileEquipo= base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/'.$element->Imagen;
                }else{
                    $resp[$contadorFile]->FileEquipo= $ruta2.$element->Imagen;
                }
        
                if(!file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/'.$element->Imagen)){
                    //si no existe la imagen en la ruta se cambia ala ruta 2
                    $ruta1 =$ruta2;
                }

                $contadorFile ++;
            }
            
            $data['imagenes'] =$resp;
            $data['ruta'] = $ruta1;
        
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
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
    
    public function ListEquipo_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
        $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
        $v = new Valitron\Validator([
            'IdServicio' => $this -> get('IdServicio')
        ]);

        $v -> rule('required', [
            'IdServicio'
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {
        //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
            $ruta1=base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/';
            $ruta2=returnLinkFotosServicios().$this ->get('IdServicio').'/';

            $Mequipocomentario = new Mequipocomentario();
            $Mequipocomentario->IdServicio=$this ->get('IdServicio');
            $rowequipos=  $Mequipocomentario->get_list();
            
            $count=0;
            foreach ($rowequipos as $element)
            {
                $Mimagenequipo2= new Mimagenequipo2();
                $Mimagenequipo2->IdServicio=$this ->get('IdServicio');
                $Mimagenequipo2->IdEquipo=$element->IdEquipo;
                
                if ($element->Permitir=='s')
                {
                $element->Permitir =true;
                }
                else
                {
                $element->Permitir =false;  
                }
                // Paginaci�n
                $resp =  $Mimagenequipo2->get_list();

                $contadorFile = 0;
                foreach ($resp as $elem2)
                {
                    if ($elem2->Mostrar=='s')
                    {
                        
                    $elem2->Mostrar =true; 
                    }
                    else
                    {
                        $elem2->Mostrar =false;
                    }

                    if($elem2->Tipo == 1){
                        $resp[$contadorFile]->FileEquipo= base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/'.$elem2->Imagen;
                    }else{
                        $resp[$contadorFile]->FileEquipo= $ruta2.$elem2->Imagen;
                    }

                    if(!file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/'.$elem2->Imagen)){
                        //si no existe la imagen en la ruta se cambia ala ruta 2
                        $ruta1 =$ruta2;
                    }
                    $contadorFile ++;
                }
                $rowequipos[$count]->Imagenes=$resp;
                $count ++;
            }
            
            
            $data['equipos'] =$rowequipos;
            $data['ruta'] =$ruta1;
            $data['ruta2'] =$ruta2;
            $data['ruta1'] = base_url().'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$this -> get('IdServicio').'/';
    
            return $this->set_response([
                'status' => true,
                'data' => $data,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        }
        else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function Add_post() {
        // Valid Token
        
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
            
        $v = new Valitron\Validator([
            'IdServicio' => $this -> post('IdServicio')
        ]);
    
        $v -> rule('required', [
            'IdServicio'
            
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {
            $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
      
            //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
            
            $ListaEquipos= $this->post('equipos');
    
            foreach ($ListaEquipos as $equipos)
            {
                $oMequipocomentario = new Mequipocomentario();
                $oMequipocomentario->Comentario2=$equipos['Comentario2'];
                $oMequipocomentario->Permitir='n';
                if ($equipos['Permitir']==1)
                {
                 $oMequipocomentario->Permitir='s';
                }
                $oMequipocomentario->IdEquipo=$equipos['IdEquipo']; 
                $oMequipocomentario->IdServicio=$this -> post('IdServicio');
                $oMequipocomentario->update();
                
                $Imagenes =$equipos['Imagenes'];
                foreach ($Imagenes as $imagen)
                {
                    $Mimagenequipo2= new Mimagenequipo2();
                    $Mimagenequipo2->IdServicio=$this ->post('IdServicio');
                    $Mimagenequipo2->IdEquipo=$equipos['IdEquipo'];  
                    $Mimagenequipo2->Mostrar='n';  
                    if ($imagen['Mostrar']==1)
                    {
                       $Mimagenequipo2->Mostrar='s';   
                    }              
                    $Mimagenequipo2->Descripcion2=$imagen['Descripcion2'];
                    $Mimagenequipo2->Posicion=$imagen['Posicion'];
                    $Mimagenequipo2->Contador=$imagen['Contador'];  
    
                    if ($imagen['isRotated'] > 0) 
                    {
                        if(file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen'])) {
                            
                            // File 
                            $originFilename = $imagen['Imagen'];
                            $originalPath   = 'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen']; 
                            $rotatePath     = 'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen']; 
                        
                            $degrees = 0;
    
                            switch ($imagen['Posicion']) {
                                case 1 : 
                                    $degrees = 90;
                                    break;
    
                                case 2 : 
                                    $degrees = 180;
                                    break;
    
                                case 3 : 
                                    $degrees = 270;
                                    break;
    
                                case 4 : 
                                    $degrees = 360;
                                    break;
                            }
    
                            if ($degrees > 0) 
                            { 
                                $source = imagecreatefromjpeg($originalPath);
                                
                                // Rotate
                                $rotate = imagerotate($source, $degrees, 0);
                                
                                if(!file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del')){
                                    mkdir('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del', 0700);
                                }
                                    
                                rename($originalPath,'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del/'.$originFilename);
                                
                                imagejpeg($rotate, $rotatePath);
    
    
                                // Liberar la memoria
                                imagedestroy($source);
                                imagedestroy($rotate);
                            }
    
                        }
                    }
    
    
    
    
    
    
    
    
                    $Mimagenequipo2->update();
                }
                
            }
       
                    
          return $this -> set_response([
                'status' => true,
                'data' => '',
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller:: HTTP_CREATED);
          
        }else {
    
                $data['errores'] = $v->errors();
    
                return $this->set_response([
                    'status' => false,
                    'message' => $data,
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
    }

    public function AddImages_post() {
        // Valid Token
        
        if (!$this -> verification -> validToken($this -> input -> request_headers('Authorization'))) {
            return $this -> set_unauthorized_response();
        }
            
        $v = new Valitron\Validator([
            'IdServicio' => $this -> post('IdServicio')
        ]);

        $v -> rule('required', [
            'IdServicio'
            
        ]) -> message('El campo {field} es requerido.');
        
        if ($v -> validate()) {
            $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
            $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
            $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
    
            //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
            //IdUsuario Logueado desde la app seria IdContacto !!!!!
            
            $Imagenes = $this->post('imagenes');
        
            foreach ($Imagenes as $imagen)
            {
                $Mimagenequipo2= new Mimagenequipo2();
                $Mimagenequipo2->IdServicio=$this ->post('IdServicio');  
                $Mimagenequipo2->Mostrar='n';  
                if ($imagen['Mostrar']==1)
                {
                    $Mimagenequipo2->Mostrar='s';   
                }              
                $Mimagenequipo2->Descripcion2=$imagen['Descripcion2'];
                $Mimagenequipo2->Posicion=$imagen['Posicion'];
                $Mimagenequipo2->Contador=$imagen['Contador'];  

                if ($imagen['isRotated'] > 0) 
                {
                    if(file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen'])) {
                        
                        // File 
                        $originFilename = $imagen['Imagen'];
                        $originalPath   = 'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen']; 
                        $rotatePath     = 'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/'.$imagen['Imagen']; 
                    
                        $degrees = 0;

                        switch ($imagen['Posicion']) {
                            case 1 : 
                                $degrees = 90;
                                break;

                            case 2 : 
                                $degrees = 180;
                                break;

                            case 3 : 
                                $degrees = 270;
                                break;

                            case 4 : 
                                $degrees = 360;
                            break;
                        }

                        if ($degrees > 0) 
                        { 
                            $source = imagecreatefromjpeg($originalPath);
                            
                            // Rotate
                            $rotate = imagerotate($source, $degrees, 0);
                            
                            if(!file_exists('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del')){
                                mkdir('assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del', 0700);
                            }
                                
                            rename($originalPath,'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$imagen['IdServicio'].'/del/'.$originFilename);                            
                            imagejpeg($rotate, $rotatePath);

                            // Liberar la memoria
                            imagedestroy($source);
                            imagedestroy($rotate);
                        }

                    }
                }
        
                $Mimagenequipo2->updateImages();
            }
                    
            return $this -> set_response([
                'status' => true,
                'data' => '',
                'message' => 'Se ha agregado correctamente.',
            ], REST_Controller:: HTTP_CREATED);
        }else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }


 public function ListCounts_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        $IdEmpresa=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdEmpresa'];
         $IdUsuario=$this->verification->getTokenData($this->input->request_headers('Authorization'))['uniqueid'];
        
          $v = new Valitron\Validator([
        'IdServicio' => $this -> get('IdServicio')
    ]);

    $v -> rule('required', [
        'IdServicio'
    ]) -> message('El campo {field} es requerido.');
    
    if ($v -> validate()) {
    //IdUsuario LOgueado desde el sistema seria IdUsuario  !!!!
        //IdUsuario Logueado desde la app seria IdContacto !!!!!
        
        $Mimagenequipo2= new Mimagenequipo2();
        $Mimagenequipo2->IdServicio=$this -> get('IdServicio');
        $Mimagenequipo2->Validar='Mayor';
    
        // Paginaci�n
        $observaciones =  $Mimagenequipo2->get_listImgServicios();
        $Mimagenequipo2->Validar='Igual';
        $Imagenes =  $Mimagenequipo2->get_listImgServicios();
     
        $data['Observaciones'] =count($observaciones);
        $data['Imagenes'] =count($Imagenes);

        return $this->set_response([
            'status' => true,
            'data' => $data,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
        
        }
        else {

            $data['errores'] = $v->errors();

            return $this->set_response([
                'status' => false,
                'message' => $data,
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    

  }