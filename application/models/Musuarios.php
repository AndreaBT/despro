<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Musuarios extends BaseModel
{
    // Properties
    public $IdUsuario;
    public $IdPerfil;
    public $Nombre;
    public $Apellido;
    public $Candado;
    public $Seguridad;
    public $Estatus;
    public $IdEmpresa;
    public $IdSucursal;
    public $IdCliente;
    public $Foto;
    public $ColorFondo;
	public $Password;
    public $FechaMod;
    public $IdPerfil2;
    public $Foto2;
	public $Version;

    public $PerfilUser;
    public $PerfilUse;

    public function __construct()
    {
        parent::__construct();

        // Init Properties
        $this->IdUsuario = 0;
        $this->IdPerfil = 0;
        $this->Nombre = '';
        $this->Apellido = '';
        $this->Candado = '';
        $this->Seguridad = '';
        $this->Estatus = '';
        $this->IdEmpresa = '';
        $this->IdSucursal = '';
        $this->IdCliente = '';
        $this->Foto = '';
        $this->ColorFondo = '';
        $this->FechaMod = '';
        $this->IdPerfil2 = '';
        $this->Foto2 = '';
        $this->Password = '';
        $this->PerfilUser = '';
        $this->PerfilUse = '';
		$this->Version = '';



    }
     public function insert()
    {
        //se busca el rol anterior
        $CI =& get_instance();
        $CI->load->model("Mrol");
        $CI->load->model("Mperfil");

        $Mperfil= new Mperfil();
        $Mperfil->IdPerfil=$this->IdPerfil2;
       $res= $Mperfil->get_recovery();
       $IdRol=$this->IdPerfil2;
       if ($res['status'])
       {
            $oMrol = new Mrol();
            $oMrol->Nombre=$res['data']->Nombre;
            $oMrol->IdSucursal=$this->IdSucursal;
           $rs2= $oMrol->get_recovery();
            if ($rs2['status'])
            {
                $IdRol =$rs2['data']->IdRol;
            }
       }
        //Fin busqueda de rol*****
        $this->db->set('IdPerfil', $IdRol);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Apellido', $this->Apellido);
        $this->db->set('Candado', $this->Candado);
        $this->db->set('Seguridad', $this->Seguridad);
        $this->db->set('IdEmpresa', $this->IdEmpresa);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('Foto', '');
        $this->db->set('Estatus', 'A');
        $this->db->set('ColorFondo', '');
        $this->db->set('Password', $this->Password);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('IdPerfil2', $this->IdPerfil2);
        $this->db->set('Foto2', $this->Foto2);
        $this->db->set('Version', 4);
        $this->db->insert('usuario');

        return $this->db->insert_id();
    }

    public function insert_usuariomonitoreo()
    {
        $this->db->set('IdPerfil', $this->IdPerfil);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Apellido', $this->Apellido);
        $this->db->set('Candado', $this->Candado);
        $this->db->set('Password', $this->Password);
        $this->db->set('IdEmpresa', $this->IdEmpresa);
        $this->db->set('IdSucursal', $this->IdSucursal);
        $this->db->set('IdCliente', $this->IdCliente);
        $this->db->set('Foto', $this->Foto);
        $this->db->set('Estatus', 'A');
        $this->db->set('ColorFondo', $this->ColorFondo);
        $this->db->set('FechaMod', $this->FechaMod);
		$this->db->set('Version', 4);
        $this->db->insert('usuario');

        return $this->db->insert_id();
    }

    public function update_usuariomonitoreo()
    {
        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Apellido', $this->Apellido);
        $this->db->set('Candado', $this->Candado);
        $this->db->set('FechaMod', $this->FechaMod);
        if($this->Password!=''){
            $this->db->set('Password', $this->Password);
        }

        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePassw_trabajador(){

        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Password', $this->Password);
        $this->db->set('Estatus', 'A');

        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

      public function update_usertrab()
    {

        //se busca el rol anterior
        $CI =& get_instance();
        $CI->load->model("Mrol");
        $CI->load->model("Mperfil");

        $Mperfil= new Mperfil();
        $Mperfil->IdPerfil=$this->IdPerfil2;
       $res= $Mperfil->get_recovery();
       $IdRol=$this->IdPerfil2;
       if ($res['status'])
       {
            $oMrol = new Mrol();
            $oMrol->Nombre=$res['data']->Nombre;
            $oMrol->IdSucursal=$this->IdSucursal;
           $rs2= $oMrol->get_recovery();
            if ($rs2['status'])
            {
                $IdRol =$rs2['data']->IdRol;
            }
       }
        //Fin busqueda de rol*****
        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('IdPerfil', $IdRol);
        $this->db->set('Nombre', $this->Nombre);
        $this->db->set('Candado', $this->Candado);
        $this->db->set('Foto', '');
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('IdPerfil2', $this->IdPerfil2);
        $this->db->set('Foto2', $this->Foto2);
        $this->db->set('Password', $this->Password);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

       public function update_credencial()
    {

        $this->db->where('IdUsuario', $this->IdUsuario);
        if (!empty($this->Apellido))
        {
        $this->db->set('Apellido', $this->Apellido);
        }
          if (!empty($this->Nombre))
        {
        $this->db->set('Nombre', $this->Nombre);
        }
        $this->db->set('Candado', $this->Candado);
        $this->db->set('FechaMod', $this->FechaMod);
          if (!empty($this->Password))
        {
        $this->db->set('Password', $this->Password);
        }
        $this->db->set('Foto2', $this->Foto2);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

      public function update_usuariosbaja()
    {
        if (!empty($this->IdEmpresa))
        {
        $this->db->where('IdEmpresa', $this->IdEmpresa);
        }
         if (!empty($this->IdSucursal))
        {
        $this->db->where('IdSucursal', $this->IdSucursal);
        }
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Estatus', $this->Estatus);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_password()
    {

        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('Contrasenia', $this->Contrasenia);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

     public function update_Foto()
    {

        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('Foto2', $this->Foto2);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->set('Estatus', 'B');
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_usuario()
    {
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo,Foto2,IdPerfil2,Version');
        $this->db->from('usuario');
        $this->db->where('IdUsuario', $this->IdUsuario);

        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }

    public function get_usuario_Correo()
    {
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo,Foto2');
        $this->db->from('usuario');
        $this->db->where('Candado', $this->Candado);
        $this->db->where('Estatus', $this->Estatus);

        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            $data = $response->row();

            return [
                'status' => true,
                'data' => $data
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }


    public function get_list()
    {
        $this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo,Foto2,Version');
        $this->db->from('usuario');
        $this->db->where('Estatus', "A");

         $this->db->where('IdSucursal', $this->IdSucursal);

         if (!empty($this->IdPerfil)) {

            if (!empty($this->IdPerfil2))
            {
            $where=' (`IdPerfil` = '.$this->IdPerfil.'  OR `IdPerfil2` = '.$this->IdPerfil2.') ';
            $this->db->where($where);
            }
            else
            {
               $this->db->where('IdRol', $this->IdPerfil);
            }
        }

        if (!empty($this->Nombre)) {
            $this->db->like('Nombre', $this->Nombre);
        }


        if (!empty($this->IdCliente)) {
            $this->db->where('IdCliente', $this->IdCliente);
        }



        //Pagination
        $this->set_pagination();

        $response = $this->db->get();
        return $response->result();
    }

    public function exists_username()
    {
        $this->db->select('IdUsuario,Candado,Estatus');
        $this->db->from('usuario');
        $this->db->where('Candado', $this->Candado);
        $this->db->where('Estatus', "A");

        if ($this->IdUsuario !='')
        {
            $this->db->where_not_in('IdUsuario', $this->IdUsuario);
        }
         //echo $this->db->get_compiled_select();
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_usuario_login()
    {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('Candado', $this->Usuario);
        $this->db->where('Estatus', 'A');

        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        if ($response->num_rows() > 0) {

            $data = $response->row();

            $orol = new Mrol();
            $orol->IdRol=$data->IdPerfil;
           $objrol= $orol->get_recovery();
        $PerfilUse="";
           if ($objrol['status'])
           {
            $PerfilUse =$objrol['data']->Nombre;
           }else{
               $IDVal=$data->IdPerfil2;
               if ($IDVal ==4)
                {
                    $PerfilUse ='Usuario APP';
                }
           }

           if ($PerfilUse!='Usuario APP')
           {
           // echo  $data->Password;
            //echo $this->Contrasenia;
            if (Password::verify($this->Contrasenia, $data->Password)) {

                if(Password::rehash($data->Password)){

                }

                $data->Seguridad = '';
                return [
                    'status' => true,
                    'data' => $data
                ];
            }
            else if ($this->get_usuario_loginAntiguo())
            {
               $data->Seguridad = '';
                return [
                    'status' => true,
                    'data' => $data
                ];
            }
             else {

                return [
                    'status' => false,
                    'message' => 'Usuario o Contraseña Incorrecta.'
                ];
            }
        }else{
            return [
                'status' => false,
                'message' => 'El perfil no tiene acceso al sistema'
            ];
        }
        } else {

            return [
                'status' => false,
                'message' => 'Usuario o Contraseña Incorrecta.'
            ];
        }
    }

    public function get_usuario_loginApp() {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('Candado', $this->Usuario);
        $this->db->where('Estatus', 'A');

        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();

        if ($response->num_rows() > 0) {
			$data = $response->row();

			$orol 			= new Mrol();
			$orol->IdRol 	= $data->IdPerfil;
           	$objrol			= $orol->get_recovery();

			$PerfilUse = "";

           if ($objrol['status']) {
			   $PerfilUse = $objrol['data']->Nombre;

           }else{
               $IDVal = $data->IdPerfil2;
               if ($IDVal == 4) {
                    $PerfilUse ='Usuario APP';
			   }
           }

           if ($PerfilUse == 'Usuario APP') {

			   if (Password::verify($this->Contrasenia, $data->Password)) {
				   /*if(Password::rehash($data->Password)){}*/
				   $data->Seguridad = '';
				   return [
					   'status' => true,
					   'data' => $data
				   ];

			   } else if ($this->get_usuario_loginAntiguo()) {

				   //$this->resetInternalPassword($data->IdUsuario, $this->Contrasenia);
				   $data->Seguridad = '';
				   $data->Password  = '';

				   return [
					   'status' => true,
					   'data' => $data
				   ];

			   } else {
				   return [
					   'status' => false,
					   'message' => 'Usuario o Contraseña Incorrecta CODE_034.'
				   ];
			   }

		   }else{
			   return [
				   'status' => false,
				   'message' => 'El perfil no tiene acceso al sistema'
			   ];
		   }
        } else {

            return [
                'status' => false,
                'message' => 'Usuario o Contraseña Incorrecta CODE_033.'
            ];
        }
    }

       public function get_usuario_loginAntiguo()
    {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('Candado', $this->Usuario);
        $pass='password(\''. $this->Contrasenia.'\')';
        $this->db->where('Seguridad',$pass,false);
        $this->db->where('Estatus', 'A');

        #echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        if ($response->num_rows() > 0) {
               return true;
        } else {
            return false;
        }
    }


	private function resetInternalPassword($idusuario = 0, $password = '') {

		$this->db->set('Password',  Password::hash($password) )
			->set('Seguridad','')
			->set('FechaMod', date('Y-m-d h:i:s'))
			->where('IdUsuario', $idusuario)
			->update('usuario');

		if ($this->db->affected_rows() > 0) {
			return true;

		} else {
			return false;
		}
	}


     public function get_usuario_root()
    {
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->where('Estatus', 'A');

      //echo $result = $this->db->get_compiled_select();
        $response = $this->db->get();
        if ($response->num_rows() > 0) {

            $data = $response->row();

           $data->Seguridad = '';
            return [
                'status' => true,
                'data' => $data
            ];

        } else {

            return [
                'status' => false,
                'message' => 'Usuario incorrecto.'
            ];
        }
    }
    public function get_listusers()
    {
        $this->db->select('IdUsuario,Nombre,Apellidos,Contrasenia,Usuario,Correo,Telefono,Nacimiento,Foto,Firma,IdPuesto,IdJefe,IdModulo,IdPerfil,IdSucursal,Suspendido,FechaReg,FechaMod,RegEstatus');
        $this->db->from('usuarios');
        $this->db->where('IdPerfil', $this->IdPerfil);
        $result = $this->db->get();
        return $result->result();
    }
     public function get_listgerentespordirector()
    {
        $this->db->select('IdUsuario,Nombre,Apellidos,Contrasenia,Usuario,Correo,Telefono,Nacimiento,Foto,Firma,IdPuesto,IdJefe,IdModulo,IdPerfil,IdSucursal,Suspendido,FechaReg,FechaMod,RegEstatus');
        $this->db->from('usuarios');
        if($this->IdJefe != ''){
            $this->db->where('IdJefe',$this->IdJefe);
        }
        if($this->IdUsuario != ''){
            $this->db->where('IdJefe',$this->IdUsuario);
        }
        $result = $this->db->get();
        return $result->result();
    }

    public function resset_password()
    {

        $this->db->where('IdUsuario', $this->IdUsuario);
        $this->db->set('Password', $this->Password);
        $this->db->set('FechaMod', $this->FechaMod);
        $this->db->update('usuario');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


	public function getChatListUsers()
	{
		$this->db->select('IdUsuario,IdPerfil,Nombre,Apellido,Candado,Estatus,IdEmpresa,IdSucursal,IdCliente,Foto,ColorFondo,Foto2,Version');
		$this->db->from('usuario');
		$this->db->where('Estatus', "A");

		$this->db->where('IdSucursal', $this->IdSucursal);

		if (!empty($this->IdPerfil)) {

			$this->db->where_in('IdPerfil', $this->IdPerfil);
		}

		if (!empty($this->Nombre)) {
			$this->db->like('Nombre', $this->Nombre);
		}


		if (!empty($this->IdCliente)) {
			$this->db->where('IdCliente', $this->IdCliente);
		}



		//Pagination
		$this->set_pagination();

		$response = $this->db->get();
		return $response->result();
	}
}
