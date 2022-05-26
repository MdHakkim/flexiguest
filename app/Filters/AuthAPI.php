<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

helper('responsejson');

class AuthAPI implements FilterInterface
{
    // checking JWT Token valid or not
    public function before(RequestInterface $request, $arguments = null)
    {
        try {

            helper('jwt');

            $token = explode(" ", getJWTFromRequest())[1];

            $decoded  = validateJWTFromRequest($token);

            if (!empty($decoded)) {

                if (isset($arguments[0]) && isset($decoded['token_info']->data->USR_ROLE) && $arguments[0] == $decoded['token_info']->data->USR_ROLE) {

                    $request->user = $decoded['table_info'];
                    return $request;
                }
            }

            $result = responseJson(401, true, "User Unauthorized", []);
            echo json_encode($result);
            die;
        } catch (\Exception $ex) {
            $result = responseJson(401, true, "User Unauthorized", []);
            echo json_encode($result);
            die;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
