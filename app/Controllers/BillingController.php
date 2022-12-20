<?php

namespace App\Controllers;

use App\Controllers\Repositories\BillingRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\TransactionCodeRepository;
use CodeIgniter\API\ResponseTrait;

class BillingController extends BaseController
{

    use ResponseTrait;

    private $BillingRepository;
    private $ReservationRepository;
    private $TransactionCodeRepository;

    public function __construct()
    {
        $this->BillingRepository = new BillingRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->TransactionCodeRepository = new TransactionCodeRepository();
    }

    public function billing()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['reservation_id'] = $this->request->getVar('reservation_id');
        $data['confirm_password'] = session('confirm_password') ?? false;
        // unset($_SESSION['confirm_password']);

        $data['reservation'] = $this->ReservationRepository->reservationById($data['reservation_id']);
        $data['transaction_codes'] = $this->TransactionCodeRepository->transactionCodes();

        return view('frontend/accounts/billing', $data);
    }

    public function postTransaction()
    {
        $user = session('user');

        $data = $this->request->getPost();
        $data['RTR_TRANSACTION_TYPE'] = 'Credited';

        if (!$this->validate($this->BillingRepository->postTransactionValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->BillingRepository->postTransaction($user, $data);
        return $this->respond($result);
    }

    public function loadWindowsData()
    {
        $reservation_id = $this->request->getVar('reservation_id');

        $response = $this->BillingRepository->reservationTransactions("RTR_RESERVATION_ID = $reservation_id");
        return $this->respond(responseJson(200, false, ['msg' => 'transactions'], $response));
    }
}
