<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
helper('responsejson');

class AuthAPI implements FilterInterface
{
    use ResponseTrait;
    
        
    // checking JWT Token valid or not
    public function before(RequestInterface $request, $arguments = null)
    {
            $token = null;    
            $authHeader = session()->get('USR_TOKEN');

            try {

                helper('jwt');
                
                $token = getJWTFromRequest($authHeader);
                $decoded  = validateJWTFromRequest($token);
               
                if(!empty($decoded)){

                    return $request;
                
                }
                 
                 
            }catch (Exception $ex) {
                 
                
                $result = ["status" =>401,
                    "error" => true,
                    "message"=>'User Unauthorized',
                    "data"=> []];
                echo json_encode($result);die;
            }

            
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}