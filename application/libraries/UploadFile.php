<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UploadFile
{
    const SINGLE = 0;
    const MULTIPLE = 1;

    public function __construct()
    { }

    public function upload($ruta, $Tipo = 0, $Formatos = '*', $Input = '', $Anterior = '')
    {
        // Tipo 0 = Uno, 1 = Multiple

        if (!is_dir($ruta)) {
            mkdir($ruta); //Directory does not exist, so lets create it.
        }

        if ($Tipo == 0) {
            $config['upload_path'] = $ruta;
            $config['allowed_types'] = $Formatos;
            $config['max_size'] = 50000;
            $config['overwrite'] = TRUE;

            if(empty($_FILES[$Input])){
                
                if(empty($Anterior)){
                    return 'assets/files/usuarios/user.svg';
                }else{
                    return $Anterior;
                }
            }

            $nombre = $_FILES[$Input]['name']; //comentar si se quiere el nombre original
            if ($Anterior != 'assets/files/usuarios/user.svg') {
                if (file_exists($Anterior)) {
                    unlink($Anterior);
                }
            }

            $type = pathinfo($nombre, PATHINFO_EXTENSION);
            $Nom = date("YmdHis") . quitarCaracteres(substr($nombre, 0, strrpos($nombre, "."))) . '.' . $type;
            $ci = &get_instance();
            $ci->load->library('upload', $config);
            $ci->upload->initialize($config);
            $_FILES['file']['name'] = $Nom; //aqui es el nombre verdadero
            $_FILES['file']['type'] = $_FILES[$Input]['type'];
            $_FILES['file']['tmp_name'] = $_FILES[$Input]['tmp_name'];
            $_FILES['file']['error'] = $_FILES[$Input]['error'];
            $_FILES['file']['size'] = $_FILES[$Input]['size'];

            if ($ci->upload->do_upload('file')) {
                return $ruta . $Nom;
            } else {
                return $Anterior;
            }

        } else if ($Tipo == 1) { }
    }

    /**
     * Permite guardar archivos
     *
     * @param string $route
     * @param string $formats
     * @param string $files
     * @param string $previous
     * @param int $type
     * @return array
     */
    public function savefile($route, $input, $previous, $formats = '*', $type = self::SINGLE)
    {

        if (!is_dir($route)) {
            mkdir($route); //Directory does not exist, so lets create it.
        }

        $config['upload_path'] = $route;
        $config['allowed_types'] = $formats;
        $config['max_size'] = 50000;
        $config['overwrite'] = TRUE;

        if ($type == self::SINGLE) {
            

            if(empty($_FILES[$input])){
                return $previous;
            }

            if ($previous != 'user.svg' && $previous !='') {
                if (file_exists($route.$previous)) {
                    unlink($route.$previous);
                }
            }

            $filename = $_FILES[$input]['name']; //comentar si se quiere el nombre original

            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $newName = date("YmdHis") . quitarCaracteres(substr($filename, 0, strrpos($filename, "."))) . '.' . $extension;

            $ci = &get_instance();
            $ci->load->library('upload', $config);
            $ci->upload->initialize($config);
            
            $_FILES['file']['name'] = $newName; //aqui es el nombre verdadero
            $_FILES['file']['type'] = $_FILES[$input]['type'];
            $_FILES['file']['tmp_name'] = $_FILES[$input]['tmp_name'];
            $_FILES['file']['error'] = $_FILES[$input]['error'];
            $_FILES['file']['size'] = $_FILES[$input]['size'];

            if ($ci->upload->do_upload('file')) {
                return $newName;
            } else {
                return $previous;
            }

        } else if ($type == self::MULTIPLE) { 

            /*if(empty($_FILES[$input])){
                return $previous;
            }

            if ($previous != 'assets/files/usuarios/user.svg') {
                if (file_exists($previous)) {
                    unlink($previous);
                }
            }*/

            

            $data = array();

            if(!empty($_FILES[$input]['name'])){

                $count = count($_FILES[$input]['name']);
    
                for($i = 0; $i < $count; $i++){
                
                    if(!empty($_FILES[$input]['name'][$i])){
                
                        $filename = $_FILES[$input]['name'][$i]; 

                        $extension = pathinfo($filename, PATHINFO_EXTENSION);
                        $newName = date("YmdHis") . quitarCaracteres(substr($filename, 0, strrpos($filename, "."))) . '.' . $extension;
            
                        $ci = &get_instance();
                        $ci->load->library('upload', $config);
                        $ci->upload->initialize($config);
                        
                        $_FILES['file']['name'] = $newName; 
                        $_FILES['file']['type'] = $_FILES[$input]['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES[$input]['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES[$input]['error'][$i];
                        $_FILES['file']['size'] = $_FILES[$input]['size'][$i];
            
                        $ci->load->library('upload', $config); 
                
                        if($ci->upload->do_upload('file')){
                            
                            $newdata = array(
                            'Name'=>$newName,
                            'Type'=>$extension
                            );
                            

                            array_push($data, $newdata);
                        }
                    }
            
                }

            }

            //$data['up'] = $up;
            //$data['down'] = $down;

            return $data;

        }

    }
}
