<?php

namespace App\Controllers;

use App\Controllers\Repositories\BillingRepository;
use App\Controllers\Repositories\PaymentMethodRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\TransactionCodeRepository;
use CodeIgniter\API\ResponseTrait;

class BillingController extends BaseController
{

    use ResponseTrait;

    private $BillingRepository;
    private $ReservationRepository;
    private $TransactionCodeRepository;
    private $PaymentMethodRepository;

    public function __construct()
    {
        $this->BillingRepository = new BillingRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->TransactionCodeRepository = new TransactionCodeRepository();
        $this->PaymentMethodRepository = new PaymentMethodRepository();
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
        $data['payment_methods'] = $this->PaymentMethodRepository->paymentMethods();

        return view('frontend/accounts/billing', $data);
    }

    public function postOrPayment()
    {
        $user = session('user');

        $data = $this->request->getPost();

        if (!$this->validate($this->BillingRepository->postOrPaymentValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->BillingRepository->postOrPayment($user, $data);
        return $this->respond($result);
    }

    public function loadWindowsData()
    {
        $reservation_id = $this->request->getVar('reservation_id');

        $response = $this->BillingRepository->reservationTransactions("RTR_RESERVATION_ID = $reservation_id");
        return $this->respond(responseJson(200, false, ['msg' => 'transactions'], $response));
    }

    public function moveTransaction()
    {
        $data = $this->request->getPost();
        $data = [
            'RTR_ID' => $data['RTR_ID'],
            'RTR_WINDOW' => $data['RTR_WINDOW']
        ];

        if($data['RTR_WINDOW'] < 1 || $data['RTR_WINDOW'] > 8)
            return $this->respond(responseJson(403, true, ['msg' => 'Invalid window selected.']));

        $response = $this->BillingRepository->updateReservationTransaction($data);
        $result = $response ? responseJson(200, false, ['msg' => 'Transaction moved successfully.']) : responseJson(202, true, ['msg' => 'Unable to move the transaction.']);
        
        return $this->respond($result);
    }
}
