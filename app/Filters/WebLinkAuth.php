<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class WebLinkAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null){
        try {
            $key = getenv('JWT_KEY_WEBLINK');
            $request = \Config\Services::request();
            $uri = $request->uri;
            JWT::$leeway = 60;
            $token = $uri->getSegment(3);
            $decoded = JWT::decode($token,new Key($key, 'HS256'));
            if($decoded){
                return true;
            }else{
                return redirect()->route('autherropage');
            }
        } catch (ExpiredException $ex) {
            return $e->getMessage();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}