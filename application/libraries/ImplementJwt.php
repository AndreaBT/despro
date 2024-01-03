<?php
require APPPATH .'libraries/JWT.php';

class ImplementJwt
{
    PRIVATE $key="suscribe_my_channel";

    public function GenerarToken($data)
    {
        $jwt=JWT::encode($data,$this->key);

        return $jwt;
    }

    public function DecodeToken($token)
    {
        $decode =JWT::decode($token,$this->key,array('HS256'));
        $decodedata=(array)$decode;
        return $decodedata;
    }
}
?>