<?php

use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getKey()
{
    return "FlexiGuest123456789@";
}

function getJWTFromRequest(): string
{

    $request = \Config\Services::request();

    $authHeader = $request->getHeader("Authorization");
    $authHeader = $authHeader->getValue();

    if (is_null($authHeader)) { //JWT is absent

        $result = responseJson(500,true,'Missing or invalid JWT in request',[]);
        echo json_encode($result);die;
    }
    return  $authHeader;
        
}

function validateJWTFromRequest(string $encodedToken)
{
   
    $key = getKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    
    $Db = \Config\Database::connect();
    $userdata = $Db->table('FLXY_USERS')->select('USR_NAME,USR_ID,USR_EMAIL,USR_PHONE,USR_ROLE,USR_CUST_ID')->where('USR_EMAIL',$decodedToken->data->USR_EMAIL)->get()->getRow();
    
    return ["token_info"=> $decodedToken,"table_info"=> $userdata];
    
}

function getSignedJWTForUser($userdata)
{

    $key = getKey();
    $issuedAtTime = time();
    $tokenTimeToLive = 259200;
    
    $nbf = $issuedAtTime + 10;
    
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    
    $payload = [
        'iss' => "The_claim",
        'aud' => "The_Aud",
        'iat' => $issuedAtTime,
        'nbf' => $nbf,
        'exp' => $tokenExpiration,
        "data" => $userdata,
    ];

    return JWT::encode($payload, $key,'HS256');
                         
}

?>