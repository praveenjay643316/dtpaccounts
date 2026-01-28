<?php
require("../../library/firebase/vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JWTFunction
{

    public function CreateHeader($data = array())
    {
        //print_r($data);die;
        $key  = $this->app_key;
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+60 minutes')->getTimestamp();      // Add 6 minutes
        $domainName = $this->domain_name;
        $username   = $data['user_name'];
        $json_data = json_encode($data['response_data']);
        // print_r($json_data); die;

        $payload = [
            'iat'  => $date->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $domainName,                       // Issuer
            // 'nbf'  => $date->getTimestamp(),         // Not before
            'exp'  => $expire_at,                           // Expire
            'userName' => $username,
            'signature' => $this->createSign($json_data, $key),                     // User name
        ];

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key, 'HS256');
        header('Content-Type: application/json');
        header('Authorization: Bearer ' . $jwt);
        echo $json_data;
        exit;
    }

    public function VerifyJWT($jwt = null, $data = null)
    {
        $key  = $this->app_key;
        JWT::$leeway = 180; // $leeway in seconds
        $token = JWT::decode($jwt, new Key($key, 'HS256')); //print_r($token);exit;
        $now = new DateTimeImmutable();
        $serverName = $this->domain_name;

        if (
            $token->iss !== $serverName ||
            // $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp()
        ) {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

        $decoded_array = (array) $token;
        $verify_sign = $this->verifySign($data, $decoded_array['signature'], $key);
       

        $response_data = array();
        if (!$verify_sign) {
            $response_data['STATUS'] = 'OK';
            $response_data['RESPONSE'] = 'FAIL';
            $response_data['MESSAGE'] = "Signature not verified";
        } else {
            $response_data['STATUS'] = 'OK';
            $response_data['RESPONSE'] = 'SUCCESS';
        }

        return $response_data;
    }

    public function createSign($data, $password = null)
    {
        return base64_encode(hash_hmac('sha256', $data, $password));
    }

    public function verifySign($data, $sign, $password = null)
    {
        
        return base64_decode($sign) == hash_hmac('sha256', $data, $password);
        //Testing mode
        //return base64_decode($sign) == hash_hmac('sha256', json_encode(json_decode($data,true)), $password);
    }
}