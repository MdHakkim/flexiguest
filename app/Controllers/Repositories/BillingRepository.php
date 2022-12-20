<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\ReservationTransaction;
use CodeIgniter\API\ResponseTrait;

class BillingRepository extends BaseController
{
    use ResponseTrait;

    private $ReservationTransaction;

    public function __construct()
    {
        $this->ReservationTransaction = new ReservationTransaction();
    }

    public function postTransactionValidationRules($data)
    {
        $rules = [
            'RTR_RESERVATION_ID' => ['label' => 'reservation id', 'rules' => 'required'],
            'RTR_TRANSACTION_CODE_ID' => ['label' => 'transaction code', 'rules' => 'required', 'errors' => ['required' => 'Please select a transaction code.']],
            // 'RTR_TRANSACTION_TYPE' => ['label' => 'transaction type', 'rules' => 'required'],
            'RTR_AMOUNT' => ['label' => 'amount', 'rules' => 'required'],
            'RTR_QUANTITY' => ['label' => 'quantity', 'rules' => 'required'],
            // 'RTR_CHECK_NO' => ['label' => 'Check No', 'rules' => 'required'],
            'RTR_WINDOW' => ['label' => 'window', 'rules' => 'required', 'errors' => ['required' => 'Please select a window.']],
            // 'RTR_SUPPLEMENT' => ['label' => 'supplement', 'rules' => 'required', 'errors' => ['required' => 'Please select a window.']],
            // 'RTR_REFERENCE' => ['label' => 'reference', 'rules' => 'required', 'errors' => ['required' => 'Please select a window.']],
        ];

        return $rules;
    }

    public function postTransaction($user, $data)
    {
        $data['RTR_CREATED_BY'] = $data['RTR_UPDATED_BY'] = $user['USR_ID'];
        $response = $this->ReservationTransaction->save($data);

        return $response ? responseJson(200, false, ['msg' => 'Transaction posted successfully.']) : responseJson(202, true, ['msg' => 'Unable to post the transaction.']);
    }

    public function reservationTransactions($where_condition)
    {
        return $this->ReservationTransaction
            ->join('FLXY_TRANSACTION_CODE', 'RTR_TRANSACTION_CODE_ID = TR_CD_ID', 'left')
            ->where($where_condition)
            ->findAll();
    }
}
