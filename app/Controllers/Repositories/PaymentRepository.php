<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\PaymentTransaction;
use CodeIgniter\API\ResponseTrait;

class PaymentRepository extends BaseController
{
    use ResponseTrait;

    private $PaymentTransaction;

    public function __construct()
    {
        $this->PaymentTransaction = new PaymentTransaction();
    }

    public function createUpdateTransaction($data)
    {
        return $this->PaymentTransaction->save($data);
    }
}