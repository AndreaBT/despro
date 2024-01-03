<?php

/**
 *
 * Library Push
 *
 * 
 * @package		CodeIgniter
 * @category    Library
 * @author      Fady Toloza <desarrollo7@lugarcreativo.mx>
 *
 */

class Pushapp
{

    public function send_notification($message,$Titulo,$key,$IdServicio,$Tipo)
    {
        
	   $tokens=$key;
       $url = 'https://fcm.googleapis.com/fcm/send';
       $fields = array(
			 'to' => $tokens,
             'notification' => array(
                    "title" => $Titulo, 
                    "body" => $message,
 					"click_action" => 'com.lugarcreativo.desprosoftv2_TARGET_NOTIFICATION',
                     "icon" => "name_of_icon",
                     "vibrate"   => "true",
                     "sound"     => 'RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION'
                     ),
			 'data' => array (
                //"IdServicio"    => $IdServicio, 
                "Tipo"          => $Tipo
			 ),
			 //'data' => $message
			);
		$headers = array(
			'Authorization:key =AAAAWH7_lys:APA91bFBm4Ew72J1BUkJvULy31LDw1OoCwo322JWcdo8UxburFNfnyp-pGVaJNaR7FqItamGvP1S3HGcXyUDLUtEFwtqi2c2urcF-qs7UhA9t9vL1kVVtrPakERqlr20ThvTCZ6fktzO',
			'Content-Type: application/json'
		);

	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
    }


}

