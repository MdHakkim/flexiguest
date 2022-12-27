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

    public function postOrPaymentValidationRules($data)
    {
        $rules = [
            'RTR_RESERVATION_ID' => ['label' => 'reservation id', 'rules' => 'required'],
            'RTR_TRANSACTION_TYPE' => ['label' => 'transaction type', 'rules' => 'required'],
            'RTR_AMOUNT' => ['label' => 'amount', 'rules' => 'required'],
            'RTR_WINDOW' => ['label' => 'window', 'rules' => 'required|greater_than_equal_to[1]|less_than_equal_to[8]', 'errors' => ['required' => 'Please select a window.']],
        ];

        if ($data['RTR_TRANSACTION_TYPE'] == 'Credited') {
            $rules = array_merge($rules, [
                'RTR_PAYMENT_METHOD_ID' => ['label' => 'payment method', 'rules' => 'required', 'errors' => ['required' => 'Please select a payment method.']],
            ]);

            if (isset($data['payment_method'])) {
                $payment_method = $data['payment_method'];
                $credit_limit = doubleval($payment_method['PYM_CREDIT_LIMIT']);
                $lengths = $payment_method['PYM_CARD_LENGTH'];
                $prefixes = $payment_method['PYM_CARD_PREFIX'];

                $rules['RTR_AMOUNT'] = ['label' => 'amount', 'rules' => "required|greater_than_equal_to[$credit_limit]"];

                if ($payment_method['PYM_TXN_CODE'] != 9000 && $payment_method['PYM_TXN_CODE'] != 9004)
                    $rules = array_merge($rules, [
                        'RTR_CARD_NUMBER' => [
                            'label' => 'card number',
                            'rules' => "required|validateCardNumberLength[$lengths]|validateCardNumberPrefix[$prefixes]",
                            'errors' => [
                                'validateCardNumberLength' => "Card number length should be any of these $lengths.",
                                'validateCardNumberPrefix' => "Card number prefix should be any of these $prefixes.",
                            ]
                        ],
                        'RTR_CARD_EXPIRY' => [
                            'label' => 'card expiry date',
                            'rules' => 'required'
                        ],
                    ]);
            }
        } else
            $rules = array_merge($rules, [
                'RTR_TRANSACTION_CODE_ID' => ['label' => 'transaction code', 'rules' => 'required', 'errors' => ['required' => 'Please select a transaction code.']],
                'RTR_QUANTITY' => ['label' => 'quantity', 'rules' => 'required|greater_than_equal_to[1]'],
            ]);

        return $rules;
    }

    public function encryptCardNumber($card_number)
    {
        $card_length = strlen($card_number);

        $new_number = '';
        foreach (range(1, $card_length - 4) as $i)
            $new_number .= 'X';

        return $new_number . substr($card_number, $card_length - 4, 4);
    }

    public function postOrPayment($user, $data)
    {
        $data['RTR_CREATED_BY'] = $data['RTR_UPDATED_BY'] = $user['USR_ID'];

        if ($data['RTR_TRANSACTION_TYPE'] == 'Credited')
            $data['RTR_AMOUNT'] = -$data['RTR_AMOUNT'];

        if (isset($data['RTR_CARD_NUMBER']))
            $data['RTR_CARD_NUMBER'] = $this->encryptCardNumber($data['RTR_CARD_NUMBER']);

        foreach ($data as $index => $row)
            if (empty($row))
                $data[$index] = null;

        $response = $this->ReservationTransaction->save($data);

        return $response ? responseJson(200, false, ['msg' => 'Transaction posted successfully.']) : responseJson(202, true, ['msg' => 'Unable to post the transaction.']);
    }

    public function reservationTransactions($where_condition = "1 = 1", $with_deleted = false)
    {
        if(!$with_deleted)
            $where_condition = "RTR_DELETED_AT is null and $where_condition";

        return $this->ReservationTransaction
            ->join('FLXY_TRANSACTION_CODE', 'RTR_TRANSACTION_CODE_ID = TR_CD_ID', 'left')
            ->join('FLXY_PAYMENT', 'RTR_PAYMENT_METHOD_ID = PYM_ID', 'left')
            ->where($where_condition)
            ->orderBy('RTR_ID', 'desc')
            ->findAll();
    }

    public function updateReservationTransaction($data)
    {
        return $this->ReservationTransaction->save($data);
    }

    public function deleteWindow($where_condition)
    {
        return $this->ReservationTransaction->set('RTR_WINDOW', 'RTR_WINDOW-1', false)->where($where_condition)->update();
    }
}
