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

        if (isset($data['RTR_PAYMENT_METHOD_ID']))
            $data['payment_method'] = $this->PaymentMethodRepository->paymentMethodById($data['RTR_PAYMENT_METHOD_ID']);

        if (!$this->validate($this->BillingRepository->postOrPaymentValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        if (isset($data['RESV_RESRV_TYPE']))
            $reservation_type = $data['RESV_RESRV_TYPE'];

        unset($data['payment_method']);
        unset($data['RESV_RESRV_TYPE']);

        $result = $this->BillingRepository->postOrPayment($user, $data);
        if ($result['SUCCESS'] == 200 && isset($reservation_type))
            $this->ReservationRepository->updateReservation(['RESV_RESRV_TYPE' => $reservation_type], "RESV_ID = {$data['RTR_RESERVATION_ID']}");

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

        if ($data['RTR_WINDOW'] < 1 || $data['RTR_WINDOW'] > 8)
            return $this->respond(responseJson(403, true, ['msg' => 'Invalid window selected.']));

        $response = $this->BillingRepository->updateReservationTransaction($data);
        $result = $response ? responseJson(200, false, ['msg' => 'Transaction moved successfully.']) : responseJson(202, true, ['msg' => 'Unable to move the transaction.']);

        return $this->respond($result);
    }

    public function deleteWindow()
    {
        $reservation_id = $this->request->getPost('reservation_id');
        $window_number = $this->request->getPost('window_number');

        if ($reservation_id && $window_number) {
            $where_condition = "RTR_RESERVATION_ID = $reservation_id and RTR_WINDOW = $window_number";
            $transactions = $this->BillingRepository->reservationTransactions($where_condition);
            if (!empty($transactions))
                return $this->respond(responseJson(202, true, ['msg' => 'This window is not empty.']));

            $where_condition = "RTR_RESERVATION_ID = $reservation_id and RTR_WINDOW > $window_number";
            $this->BillingRepository->deleteWindow($where_condition);

            return $this->respond(responseJson(200, false, ['msg' => 'Window deleted successfully.']));
        }
    }

    public function deleteTransaction()
    {
        $user = session('user');
        $transaction_id = $this->request->getVar('RTR_ID');

        if (!empty($transaction_id))
            $this->BillingRepository->updateReservationTransaction([
                'RTR_ID' => $transaction_id,
                'RTR_DELETED_AT' => date('Y-m-d H:i:s'),
                'RTR_DELETED_BY' => $user['USR_ID']
            ]);

        return $this->respond(responseJson(200, false, ['msg' => 'Transaction deleted successfully.']));
    }
}
