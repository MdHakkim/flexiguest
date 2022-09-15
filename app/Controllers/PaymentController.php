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
        $data = $this->request->getVar();
        $user = $this->request->user;

        $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        return $this->respond($result);
    }

    public function webhook()
    {
        $data = (array) $this->request->getVar();

        // $this->PaymentRepository->webhook($data);
        $this->PaymentRepository->localWebhook($data);
    }
}