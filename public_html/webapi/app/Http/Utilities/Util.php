<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Utilities;
 class Util{
    
    public static function enviarNotificacion($usuarios, $object){
        
        $arrayAndroid = Array();
        foreach ($usuarios as $u){
            
            if($u->device_type == 1){
                
                $arrayAndroid[] = $u->token;
                
                
            }elseif($u->device_type == 2){
                
                Util::enviarIos($u->token, $object);
                
            }
            
            if(count($arrayAndroid)>0){
                Util::enviarAndroid($arrayAndroid, $object);
            }
            
        }
        
        
    }
    
    public static function enviarNotificacionIndividual($token,$device, $object){
        
        $arrayAndroid = Array();
            
        if($device == 1){

            $arrayAndroid[] = $token;


        }elseif($device == 2){

            Util::enviarIos($token, $object);

        }

        if(count($arrayAndroid)>0){
            
            Util::enviarAndroid($arrayAndroid, $object);
            
        }
            
        
        
    }
    
    public static function enviarAndroid($usuarios, $object){
        
        $apiKey = 'AIzaSyBPNVPRSrEXIQzCn5wYfOsE_mTAWdLwrhg'; 
        $headers = array('Content-Type:application/json',"Authorization:key=$apiKey");

        $payload = array('title' => 'Tu Domicilio',
            'message' => utf8_encode($object["data"]["message"]),
            'msgcnt' => '1',
            'data'=> json_encode($object["data"]),
            'flag'=>$object["data"]["flag"]
            );

        $data = array(
            'data' => $payload,
            'registration_ids' => $usuarios
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);
        
    }
    
    public static function enviarIos($deviceToken, $object){
        
        $passphrase = 'qaz123';
        $message = $object["data"]["message"];
        //echo $_SERVER['DOCUMENT_ROOT'];
        
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushApp.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        $uriSocket = 'ssl://gateway.sandbox.push.apple.com:2195'; //DEVELOPER
        //$uriSocket = 'ssl://gateway.push.apple.com:2195';//RELASE
        
        $fp = stream_socket_client($uriSocket, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        
        $body['aps'] = array(
                'alert' => $message,
                'sound' => 'default'
                );
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result){
                //echo 'Message not delivered' . PHP_EOL;
        }else{
                //echo 'Message successfully delivered' . PHP_EOL;
        }
        // Close the connection to the server
        fclose($fp);
        
    }
    
}

