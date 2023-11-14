<?php
require_once('./config.php');

class AuthHelper{

    public function getAuthHeaders(){
        $header = "";
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $header =$_SERVER['HTTP_AUTHORIZATION'];
        }
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
            $header =$_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        return $header;
    }

    public function createToken($payload){
        $header = array(
            'alg'=> 'HS256',
            'typ' => 'JWT'
        );

        $header = $this->base64url_encode(json_encode($header));
        $payload = $this->base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256',"$header.$payload",JWT_KEY,true);
        $signature = $this->base64url_encode($signature);

        $token = "$header.$payload.$signature";

        return $token;
    }

    public function verifyToken($token){
        $token = explode(".",$token);
        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $new_signature = hash_hmac('SHA256',"$header.$payload",JWT_KEY,true);
        $new_signature = $this->base64url_encode($new_signature);

        if($signature != $new_signature){
            return false;
        }

        $payload = json_decode(base64_decode($payload));

        return $payload;
    }

    public function currentUser(){
        $auth = $this->getAuthHeaders();
        $auth = explode(" ",$auth);

        if($auth[0] != "Bearer"){
            return false;
        }
        return $this->verifyToken($auth[1]);
    }

    private function base64url_encode($data){
        return rtrim(strtr(base64_encode($data),'+/','-_'),"=");
    }


}