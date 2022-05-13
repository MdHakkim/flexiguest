<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Customer;
use CodeIgniter\API\ResponseTrait;

class CustomerController extends BaseController
{
    use ResponseTrait;

    private $Customer;

    public function __construct()
    {
        $this->Customer = new Customer();
    }

}