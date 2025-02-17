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
        
    $sql = "SELECT u.USR_NAME, u.USR_ID, u.USR_EMAIL, u.USR_PHONE, u.USR_ROLE_ID, u.USR_CUST_ID, 
                    ROLE_NAME,
                    b.*,
                    CONCAT_WS(' ', b.CUST_FIRST_NAME, b.CUST_MIDDLE_NAME, b.CUST_LAST_NAME) as NAME,
                    ur.*, UD_REGISTRATION_ID
                    FROM FLXY_USERS u
                        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = u.USR_CUST_ID
                        left join FLXY_USER_ROLE as ur on u.USR_ROLE_ID = ur.ROLE_ID
                        left join FLXY_USER_DEVICES on u.USR_ID = UD_USER_ID
                        WHERE u.USR_EMAIL=:USR_EMAIL:";

    $data = $Db->query($sql,$param)->getRowArray();

    $decodedToken->UD_REGISTRATION_ID = $data['UD_REGISTRATION_ID'];
    return ["token_info"=> $decodedToken,"table_info"=>  $data ];
}

function getSignedJWTForUser($userdata)
{

    $key = getKey();
    $issuedAtTime = time();
    $tokenTimeToLive = 2592000;// in seconds - one month
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

function hasPermission($user, $url, $arguments)
{
    $DB = \Config\Database::connect();

    // $sql = "select * from FLXY_MENU where MENU_URL like '$url'";
    // $data = $DB->query($sql)->getResultArray();
    // if(empty($data) && in_array($user['ROLE_NAME'], $arguments)) // if menu url is not in DB then match Role from Routes with User's Role
    //     return true;

    $sql = "select * from FlXY_USERS 
                inner join FLXY_USER_ROLE_PERMISSION on USR_ROLE_ID = ROLE_ID
                inner join FLXY_MENU on ROLE_MENU_ID = MENU_ID
                    where USR_ID = {$user['USR_ID']} and MENU_URL like '$url'";
    $data = $DB->query($sql)->getResultArray();

    return (boolean) $data;
}

?>