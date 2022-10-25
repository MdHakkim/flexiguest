<?php

namespace App\Libraries;

class CurlRequestLibrary
{
    protected $Client;

    public function __construct()
    {
        $this->Client = \Config\Services::curlrequest();
    }

    public function makeRequest($data)
    {
        try {
            $response = $this->Client->request($data['method'], $data['url'], $data['body']);
            return $response->getBody();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
