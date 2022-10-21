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
        
    $sql = "SELECT u.USR_NAME, u.USR_ID, u.USR_EMAIL, u.USR_PHONE, u.USR_ROLE, u.USR_ROLE_ID, u.USR_CUST_ID, 
                    b.*,
                    CONCAT_WS(' ', b.CUST_FIRST_NAME, b.CUST_MIDDLE_NAME, b.CUST_LAST_NAME) as NAME,
                    ur.*
                    FROM FLXY_USERS u
                        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = u.USR_CUST_ID WHERE USR_EMAIL=:USR_EMAIL:
                        left join FLXY_USER_ROLE as ur on u.USR_ROLE_ID = ur.ROLE_ID";

    $data = $Db->query($sql,$param)->getRowArray();
    return ["token_info"=> $decodedToken,"table_info"=>  $data ];
    
}

function getSignedJWTForUser($userdata)
{

    $key = getKey();
    $issuedAtTime = time();
    $tokenTimeToLive = 259200;// in seconds - one month
    $nbf = $issuedAtTime;
    
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