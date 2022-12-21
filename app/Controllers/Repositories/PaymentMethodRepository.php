<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\PaymentMethod;
use CodeIgniter\API\ResponseTrait;

class PaymentMethodRepository extends BaseController
{
    use ResponseTrait;

    private $PaymentMethod;

    public function __construct()
    {
        $this->PaymentMethod = new PaymentMethod();
    }

    public function paymentMethods()
    {
        return $this->PaymentMethod->findAll();
    }
}