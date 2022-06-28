<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Noauth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('isLoggedIn')) {

			if (session()->get('USR_ROLE') == "1") {
				return redirect()->to(base_url('admin'));
			}

			if (session()->get('USR_ROLE') == "3") {
				return redirect()->to(base_url('editor'));
			}
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}