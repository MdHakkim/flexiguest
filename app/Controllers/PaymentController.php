<?php

namespace App\Controllers;

use App\Controllers\Repositories\PaymentRepository;
use CodeIgniter\API\ResponseTrait;

class PaymentController extends BaseController
{

    use ResponseTrait;

    private $PaymentRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
    }

    public function createPaymentIntent()
    {
        $amount = $this->request->getVar('amount');

        $result = $this->PaymentRepository->createPaymentIntent($amount);
        return $this->respond($result);
    }
}