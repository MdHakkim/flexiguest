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

            $decoded = validateJWTFromRequest($token);

            if (isset($decoded['table_info']['USR_ROLE_ID'])) {
                $role_name = $decoded['table_info']['ROLE_NAME'];
                $request->user = $user = $decoded['table_info'];
                $url = uri_string();

                if (isset($arguments) && (in_array($role_name, $arguments)) && ($role_name == 'Guest' || str_contains($url, "/notification/"))) // For Guests only
                    return $request;
                else if (hasPermission($user, $url))
                    return $request;
            }

            $result = responseJson(403, true, "You don't have permission to access.", []);
            echo json_encode($result);
            die;
        } catch (\Exception $ex) {
            $result = responseJson(403, true, "You don't have permission to access.", []);
            echo json_encode($result);
            die;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
