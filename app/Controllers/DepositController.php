<?php

namespace App\Controllers;

use App\Controllers\Repositories\PaymentMethodRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\ReservationTypeRepository;
use CodeIgniter\API\ResponseTrait;

class DepositController extends BaseController
{

    use ResponseTrait;

    private $PaymentMethodRepository;
    private $ReservationTypeRepository;
    private $ReservationRepository;

    public function __construct()
    {
        $this->PaymentMethodRepository = new PaymentMethodRepository();
        $this->ReservationTypeRepository = new ReservationTypeRepository();
        $this->ReservationRepository = new ReservationRepository();
    }

    public function deposit()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['reservation_id'] = $this->request->getVar('reservation_id');
        $data['confirm_password'] = session('confirm_password') ?? false;
        // unset($_SESSION['confirm_password']);

        $data['reservation'] = $this->ReservationRepository->reservationById($data['reservation_id']);
        $data['payment_methods'] = $this->PaymentMethodRepository->paymentMethods();
        $data['reservation_types'] = $this->ReservationTypeRepository->reservationTypes();

        return view('frontend/accounts/deposit', $data);
    }

}