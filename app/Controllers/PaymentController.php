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
        $user = $this->request->user;
        $amount = $this->request->getVar('amount');

        $result = $this->PaymentRepository->createPaymentIntent($user, $amount);
        return $this->respond($result);
    }

    public function webhook()
    {
        return $this->response->setStatusCode(200);
        // return $this->respond(200);
        $this->PaymentRepository->webhook();
    }
}