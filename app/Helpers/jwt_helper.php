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

    if (is_null($authHeader)) { //JWT is absent

        $result = responseJson(500,true,'Missing or invalid JWT in request',[]);
        echo json_encode($result);die;
    }
    
    $authHeader = $authHeader->getValue();
    return $authHeader;
        
}

function validateJWTFromRequest(string $encodedToken)
{
   
    $key = getKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    
    $Db = \Config\Database::connect();
    $param =['USR_EMAIL'=>$decodedToken->data->USR_EMAIL];
        
    $sql = "SELECT u.USR_NAME,u.USR_ID,u.USR_EMAIL,u.USR_PHONE,u.USR_ROLE,u.USR_CUST_ID, a.RESV_ID,a.RESV_NO,a.RESV_NAME,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_USERS u
            LEFT JOIN FLXY_RESERVATION a ON a.RESV_NAME = u.USR_CUST_ID
            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = u.USR_CUST_ID
            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM WHERE USR_EMAIL=:USR_EMAIL:";
    $data = $Db->query($sql,$param)->getRowArray();
    return ["token_info"=> $decodedToken,"table_info"=>  $data ];
    
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