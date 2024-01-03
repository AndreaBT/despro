<?php
class Password {

    const HASH = PASSWORD_DEFAULT;
    const COST = 12;

    public static function hash($password) {
        return password_hash($password, self::HASH, ['cost' => 
        self::COST]);
    }

    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function rehash($passwordHash){
        if(password_needs_rehash($passwordHash, self::HASH, ['cost' => self::COST])){
            return true;
        }else{
            return false;
        }
    }

    public static function generate($longitud){

        $password = '';
        $caracteres = array('x','y','z','a','E','D','e','f','l','O','N','s','t','u','v','w','o','p','q','*','V','U','m','b','g',
        'Z','Y','!','.','#','X','W','$','+','h','T','S','R','i','j','k','c','d','n','P','C','B','J','M','r','I','H','G','F',
        'A','Q','L','K');

        for ($i = 1; $i <= $longitud; $i++) {
            $caracterAleatoria = rand(0, 90); 
            $numeroAletorio = rand(0,9);

            if($caracterAleatoria < count($caracteres)){
                $password .= $caracteres[$i];
            }else{
                $password .= $numeroAletorio;
            }
        }

        return $password;
    }
}