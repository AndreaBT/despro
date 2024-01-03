<?php
defined('BASEPATH') or exit('No direct script access allowed'); //No se permite el acceso directo al script
use Restserver\Libraries\REST_Controller;
use SebastianBergmann\Environment\Console;

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';
class Cmenus extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mmenus');
        $this->load->model('Mpaquetexpermiso');
        $this->load->model('Mpermisoxpaquete');
        $this->load->model('Mpermisoxperfil');
        $this->load->model('Mperfil');
        $this->load->model('Mrol');

        setTimeZone($this->verification,$this->input);
    }

    public function List_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $array = $this->verification->getTokenData($this->input->request_headers('Authorization'));
        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
        
        $obj = new Mmenus();
        $obj->IdSucursal = $IdSucursal;
        $obj->RegEstatus = 'A';
        $obj->Nombre = $this->get('TxtBusqueda');

        $obj->Tipo = 'Menu';
        // Paginación
        $rows = $obj->get_list();
        
        $obj->Limit=$this->get('Entrada');
        $pager = Pager::get_pager(count($rows),$this->get('pag'), $this->get('Entrada'));

        $obj->Tamano = $pager->PageSize;
        $obj->Offset = $pager->Offset;
        $rows = $obj->get_list();
        

        // OBTENEMOS EL NOMBRE DEL PERFIL PARA PODER ENCONTRAR EL ID DEL ROL
        $Mperfil = new Mperfil();
        $Mperfil->IdPerfil = $this->get('IdPerfil');
        $res = $Mperfil->get_recovery();
        $IdPerfil = $this->get('IdPerfil');
  
      
        // SI DEVUELVE DATOS DEL PERFIL ENCONTRAMOS EL ID DEL ROL PARA ENCONTRAR LOS PERMISOS ASIGNADOS
        if($res['status'])
        {
            $oMrol = new Mrol();
            $oMrol->Nombre = $res['data']->Nombre;
            $oMrol->IdSucursal = $IdSucursal;
            $rs2 = $oMrol->get_recovery();

            if($rs2['status'])
            {
                $IdPerfil = $rs2['data']->IdRol;
            }
        }

        // $oMpermisoxperfil=new Mpermisoxperfil();
        // $oMpermisoxperfil->IdPaquete=0;
        // $oMpermisoxperfil->IdPerfil=$this->verification->getTokenData($this->input->request_headers('Authorization'))['IdPerfil'];
        // $oMpermisoxperfil->Rol=$this->verification->getTokenData($this->input->request_headers('Authorization'))['Perfil'];
        // $rowseguridad=$oMpermisoxperfil->get_list();

        $band = 0;
        foreach ($rows as $element)
        {
            //$this->response($IdRol);
            $obj = new Mpermisoxpaquete();
            $obj->IdPaquete = $element->IdPaquete;
            $obj->IdSucursal = $IdSucursal;
            $obj->IdPerfil = $IdPerfil;
            $exist = $obj->get_exist();

            $rows[$band]->Estatus =$exist;
            // if($exist){
            //     $rows[$band]->Estatus = true;
            // }
             
            $band++;
        }

        return $this->set_response([
            'status' => true,
            'menus' => $rows,
            'pagination' => $pager,
            //'segurity' => $rowseguridad,
            'typemsj'=>1,
            'IdRol'=>$IdPerfil,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function Permisoxpaquete_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }
        $IdSucursal = $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mperfil= new Mperfil();
        $Mperfil->IdPerfil=$this->get('IdPerfil');
        $res= $Mperfil->get_recovery();
        $IdRol=$this->get('IdPerfil');

        
        if ($res['status'])
        {
                $oMrol = new Mrol();
                $oMrol->Nombre=$res['data']->Nombre;
                $oMrol->IdSucursal=$IdSucursal;
                $rs2= $oMrol->get_recovery();

                //$this->response($rs2);
                if ($rs2['status'])
                {
                    $IdRol =$rs2['data']->IdRol;
                }
        }

        //$this->response($IdRol);
        
        $obj = new Mpermisoxpaquete();
        $obj->IdPaquete = $this->get('IdPaquete');
        $obj->IdSucursal = $IdSucursal;
        $obj->IdPerfil = $IdRol;
        $exist = $obj->get_exist();
        
        return $this->set_response([
            'status' => true,
            'exist' => $exist,
            'typemsj'=>1,
            'message' => 'Success',
            'toekns' => $this->verification->getTokenData($this->input->request_headers('Authorization'))
        ], REST_Controller::HTTP_OK);

    }

    // FUNCION PARA TRAER TODOS LOS SUBMENUS Y APARTADOS DE UN MENU, ASI COMO SU ESTATUS EN PERMISOXMENU
    public function getSubMenuApartado_get() {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $obj = new Mmenus();
        $obj->RegEstatus = 'A';
        $obj->Tipo = 'SubMenu';
        $obj->Asociado = $this->get('IdPaquete');
        $rows = $obj->get_list();

        $band = 0;
        foreach($rows as $elemento) {
            // SUBMENUS DEL MENU Y SI EXISTE EN PERMISOXMENU - ESTATUS DEL BTN ACTIVO
            $activo = false;
            $objPxM = new Mpermisoxpaquete();
            $objPxM->IdPaquete = $elemento->IdPaquete;
            $objPxM->IdPerfil = $this->get('IdPerfil');

            $btnStyle1 = 'btn btn-success btn-action';
            $btnTexto1 = 'Activar';
            $btnIcono1 = 'fa fa-lock';
            $btnActiv1 = 1;

            if($objPxM->get_exist()>0){
                $activo = true;
                $btnStyle1 = 'btn btn-danger btn-action';
                $btnTexto1 = 'Desactivar';
                $btnIcono1 = 'fa fa-ban';
                $btnActiv1 = 0;
            }

            $rows[$band]->btnArray = array('btnStyle'=>$btnStyle1,'btnTexto'=>$btnTexto1,'btnIcono'=>$btnIcono1,'btnActivo'=>$btnActiv1,'Existe'=>$activo);

            // APARTADO DEL SUBMENU Y SI EXISTE EN PERMISOXMENU - ESTATUS DEL BTN ACTIVO
            $apartdo = new Mmenus();
            $apartdo->RegEstatus = 'A';
            $apartdo->Tipo = 'Apartado';
            $apartdo->Asociado = $elemento->IdPaquete;
            $rowAp = $apartdo->get_list();

            if(count($rowAp)>0) {

                $band2 = 0;

                foreach ($rowAp as $key) {
                    $activo2 = false;
                    $objPxM2 = new Mpermisoxpaquete();
                    $objPxM2->IdPaquete = $key->IdPaquete;
                    $objPxM2->IdPerfil = $this->get('IdPerfil');

                    $btnStyle2 = 'btn btn-success btn-action';
                    $btnTexto2 = 'Activar';
                    $btnIcono2 = 'fa fa-lock';
                    $btnActiv2 = 1;

                    if($objPxM2->get_exist()>0){
                        $activo2 = true;
                        $btnStyle2 = 'btn btn-danger btn-action';
                        $btnTexto2 = 'Desactivar';
                        $btnIcono2 = 'fa fa-ban';
                        $btnActiv2 = 0;
                    }

                    $rowAp[$band2]->btnArray = array('btnStyle'=>$btnStyle2,'btnTexto'=>$btnTexto2,'btnIcono'=>$btnIcono2,'btnActivo'=>$btnActiv2,'Existe'=>$activo2);
                    array_push($rows,$key);
                }
            }
            $band++;
        }
        /*
        echo '<pre>';
        print_r($rows);
        echo '</pre>';
        */

        return $this->set_response([
            'status' => true,
            'menus' => $rows,
            'typemsj'=>1,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    public function AddPermisoxmenu_post(){

        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];
       

        $Mperfil= new Mperfil();
        $Mperfil->IdPerfil=$this->post('IdPerfil');
        $res= $Mperfil->get_recovery();
        $IdPerfil=$this->post('IdPerfil');

       if ($res['status'])
       {
            $oMrol = new Mrol();
            $oMrol->Nombre=$res['data']->Nombre;
            $oMrol->IdSucursal=$IdSucursal;
            $rs2= $oMrol->get_recovery();
            if ($rs2['status'])
            {
                $IdPerfil =$rs2['data']->IdRol;
            }
       }

        $OMpaquetexsucursal = new Mpermisoxpaquete();
        $OMpaquetexsucursal->IdSucursal=$IdSucursal;
        $OMpaquetexsucursal->IdPerfil=$IdPerfil;
        $OMpaquetexsucursal->delete();
        
        

        // $PaquetesArr = array();
        // $PaqueteJS   = str_replace(array('\t','\n','\"'), "", $this->post('Paquetes'));
        // $PaquetesArr = json_decode($PaqueteJS);
        $paquetes = $this-> post('ListaMenus');

        //$this->response($PaquetesArr);
       
        foreach ($paquetes As $element) {
            if ( $element['Estatus']==true) {
                $OMpaquetexsucursal = new Mpermisoxpaquete(); 
                $OMpaquetexsucursal->IdSucursal = $IdSucursal;
                $OMpaquetexsucursal->IdPerfil   = $IdPerfil;
                $OMpaquetexsucursal->IdPaquete  = $element['IdPaquete'];
                $OMpaquetexsucursal->insert(); 
            }
        }

        // foreach ($PaquetesArr as $elemtn) {
            
        //     if ($elemtn->Estatus==true) {
        //         $OMpaquetexsucursal = new Mpermisoxpaquete();
        //         $OMpaquetexsucursal->IdSucursal=$IdSucursal;
        //         $OMpaquetexsucursal->IdPerfil=$IdPerfil;
        //         $OMpaquetexsucursal->IdPaquete=$elemtn->IdPaquete;
        //         $OMpaquetexsucursal->insert();
        //     }
          
        // }

        return $this->set_response([
            'status' => true,
            'exist' => 1,
            'IdPerfil' => $IdPerfil,
            'typemsj'=>1,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }

    // FUNCION PARA AGREGAR O ELIMINAR EL MENU Y PUESTO EN PERMISOXMENU PD: SIRVE PARA MENU Y SUBMENU
    public function AddPermisoxmenuOriginal_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $IdSucursal= $this->verification->getTokenData($this->input->request_headers('Authorization'))['IdSucursal'];

        $Mperfil= new Mperfil();
        $Mperfil->IdPerfil=$this->get('IdPerfil');
        $res= $Mperfil->get_recovery();
        $IdPerfil=$this->get('IdPerfil');

        if($res['status'])
        {
            $oMrol = new Mrol();
            $oMrol->Nombre=$res['data']->Nombre;
            $oMrol->IdSucursal=$IdSucursal;
            $rs2= $oMrol->get_recovery();
            if ($rs2['status'])
            {
                $IdPerfil =$rs2['data']->IdRol;
            }
        }

        $obj = new Mpermisoxpaquete();
        $obj->IdPaquete = $this->get('IdPaquete');
        $obj->IdPerfil = $IdPerfil;
        $obj->IdSucursal = $IdSucursal;

        if($this->get('BtnAccion')>0)
        {
            $obj->insert();

            if($obj->get_exist()>0)
            {
                return $this->set_response([
                    'status' => true,
                    'exist' => 1,
                    'typemsj'=>1,
                    'message' => 'Success',
                ], REST_Controller::HTTP_OK);
            }
            else
            {
                return $this -> set_response([
                    'status' => false,
                    'exist' => 0,
                    'typemsj' => 1,
                    'message' => 'Error al insertar los datos de la base de datos.',
                ], REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
        else
        {
            $obj->delete();
            $this->deletePermisosSubMenu($this->get('IdPaquete'),$IdPerfil,$this->get('Tipo'),$IdSucursal);

            return $this->set_response([
                'status' => true,
                'exist' => 1,
                'typemsj'=>1,
                'message' => 'Success',
            ], REST_Controller::HTTP_OK);
        }
    }


    // PARA ELIMINAR LOS PERMISOS Y/O SUBEMUS DEPENDIENDO DE LA OPCION
    public function deletePermisosSubMenu($IdPaquete,$IdPerfil,$Tipo,$IdSucursal) {
        if($Tipo == 'SubMenu')
        {
            $delete = new Mpermisoxperfil();
            $delete->IdPaquete  = $IdPaquete;
            $delete->IdPerfil   = $IdPerfil;
            $delete->IdSucursal = $IdSucursal;
            $delete->delete();
        }
        else
        {
            // PRIMERO OBTENEMOS TODOS LOS SUBMENUS DEL MENU
            $subMenus = new Mmenus();
            $subMenus->Asociado = $IdPaquete;
            $subMenus->IdSucursal = $IdSucursal;
            $subMenus->Tipo = 'SubMenu';
            $subMenus->RegEstatus = 'A';
            $rowSM = $subMenus->get_list();

            if(count($rowSM)>0)
            {
                foreach($rowSM as $elemento)
                {
                    // ELIMINAMOS EL SUBMENU DE PERMISOXMENU
                    $objSM = new Mpermisoxpaquete();
                    $objSM->IdPaquete = $elemento->IdPaquete;
                    $objSM->IdSucursal = $IdSucursal;
                    $objSM->IdPerfil = $IdPerfil;
                    $objSM->delete();

                    // ELIMINAMOS TODOS LOS PERMISOS DE ESE SUBMENU
                    $delete = new Mpermisoxperfil();
                    $delete->IdPaquete = $elemento->IdPaquete;
                    $delete->IdSucursal = $IdSucursal;
                    $delete->IdPerfil = $IdPerfil;
                    $delete->delete();

                    // BUSCAMOS TODOS LOS APARTADOS DEL SUBMENU Y ELIMINAMOS SUS PERMISOS TAMBIEN
                    $apartado = new Mmenus();
                    $apartado->Asociado = $elemento->IdPaquete;
                    $apartado->IdSucursal = $IdSucursal;
                    $apartado->Tipo = 'Apartado';
                    $apartado->RegEstatus = 'A';
                    $rowAp = $apartado->get_list();

                    foreach($rowAp as $item)
                    {
                        // ELIMINAMOS EL APARTADO DE PERMISOXMENU
                        $objAP = new Mpermisoxpaquete();
                        $objAP->IdSucursal = $IdSucursal;
                        $objAP->IdPaquete = $item->IdPaquete;
                        $objAP->IdPerfil = $IdPerfil;
                        $objAP->delete();

                        // ELIMINAMOS LOS PERMISOS DEL APARTADO
                        $deleteAp = new Mpermisoxperfil();
                        $deleteAp->IdPaquete = $item->IdPaquete;
                        $deleteAp->IdPaquete = $item->IdPaquete;
                        $deleteAp->IdPerfil = $IdPerfil;
                        $deleteAp->delete();
                    }
                }
            }
            else
            {
                $delete = new Mpermisoxperfil();
                $delete->IdPaquete = $IdPaquete;
                $delete->IdPerfil = $IdPerfil;
                $delete->delete();
            }
        }
    }

    // FUNCION PARA TRAER TODOS LOS MENUXPERMISOS Y LOS PERMISOS X PUESTO
    public function showPermisosxMenu_get()
    {
        // Valid Token
        if (!$this->verification->validToken($this->input->request_headers('Authorization'))) {
            return $this->set_unauthorized_response();
        }

        $obj = new Mpaquetexpermiso();
        $obj->IdPaquete = $this->get('IdPaquete');
        $rows = $obj->get_listPermisosxPaquete();

        $pxm = new Mpermisoxperfil();
        $pxm->IdPaquete = $this->get('IdPaquete');
        $pxm->IdPuesto = $this->get('IdPuesto');
        $rowPxM = $pxm->get_listPermisosxPuesto();

        return $this->set_response([
            'status' => true,
            'menuxpermiso' => $rows,
            'permisosxpuesto' => $rowPxM,
            'typemsj'=>1,
            'message' => 'Success',
        ], REST_Controller::HTTP_OK);
    }
}
?>