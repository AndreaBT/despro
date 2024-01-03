<?php
//defined("BASEPATH") or die("El acceso al script no está permitido");
defined('BASEPATH') or exit('No direct script access allowed');

class Verification
{

    public function __construct()
    {
        $this->objOfJwt = new ImplementJwt();
    }

    public function LoginToken($userid, $rol, $rolId, $time, $IdSucursal, $IdEmpresa,$IdCliente,$Perfil='',$Zona)
    {
        $datosbanco['uniqueid']     = $userid;
        $datosbanco['role']         = $rol;
        $datosbanco['roleId']       = $rolId;
        $datosbanco['timeStamp']    = $time;
        $datosbanco['IdSucursal']   = $IdSucursal;
        $datosbanco['IdEmpresa']    = $IdEmpresa;
        $datosbanco['IdCliente']    = $IdCliente;
        $datosbanco['Perfil']       = $Perfil;
        $datosbanco['ZonaHoraria']  = $Zona;
        $datosbanco['status']       = true;
        $jwtToken = $this->objOfJwt->GenerarToken($datosbanco);

        return $jwtToken;
    }
    public function getTokenData($Autorizacion)
    {

        $received_Token = $Autorizacion;
        try {
            $jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']);
            return ($jwtData);
        } catch (Exception $e) {
            return array("status" => false, "message" => $e->getMessage());
        }
    }

    public function validToken($Authotization)
    {
        $Token = $Authotization;
        $jwtToken = $this->getTokenData($Token);

        if ($jwtToken['status'] == false) {
            return false;
        }else{
            return true;
        }
    }
}
