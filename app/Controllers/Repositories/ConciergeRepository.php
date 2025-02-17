<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\EmailLibrary;
use App\Models\ConciergeOffer;
use App\Models\ConciergeRequest;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ConciergeRepository extends BaseController
{
    use ResponseTrait;

    private $Reservation;
    private $ConciergeOffer;
    private $ConciergeRequest;

    public function __construct()
    {
        $this->Reservation = new Reservation();
        $this->ConciergeOffer = new ConciergeOffer();
        $this->ConciergeRequest = new ConciergeRequest();
    }

    public function validationRules($data, $min_quantity, $max_quantity)
    {
        $rules = [
            'CR_OFFER_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select an offer.'
                ]
            ],
            'CR_QUANTITY' => ['label' => 'Quantity', 'rules' => "required|greater_than_equal_to[$min_quantity]|less_than_equal_to[$max_quantity]"],
            'CR_GUEST_NAME' => ['label' => 'Guest name', 'rules' => 'required'],
            'CR_GUEST_EMAIL' => ['label' => 'Guest email', 'rules' => 'required|valid_email'],
            'CR_GUEST_PHONE' => ['label' => 'Guest phone', 'rules' => 'required'],
            'CR_PREFERRED_DATE' => ['label' => 'Preferred Date', 'rules' => 'required'],
            'CR_PREFERRED_TIME' => ['label' => 'Preferred Time', 'rules' => 'required'],
            'CR_PAYMENT_METHOD' => [
                'label' => 'payment method',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a payment method.'
                ]
            ]
        ];

        if (isWeb()) {
            $rules = array_merge($rules, [
                'CR_RESERVATION_ID' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Please select a reservation.'
                    ]
                ],
                'CR_CUSTOMER_ID' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Guest field is required. Please select a reservation.'
                    ]
                ],
                'CR_TOTAL_AMOUNT' => ['label' => 'Total amount', 'rules' => 'required'],
                'CR_TAX_AMOUNT' => ['label' => 'Tax amount', 'rules' => 'required'],
                'CR_NET_AMOUNT' => ['label' => 'Net amount', 'rules' => 'required'],
                'CR_STATUS' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Please select a status.'
                    ]
                ],
                'CR_PAYMENT_STATUS' => [
                    'label' => 'payment status',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Please select a payment status.'
                    ]
                ],
            ]);
        }

        return $rules;
    }

    public function getConciergeOffer($where_condition)
    {
        return $this->ConciergeOffer->where($where_condition)->first();
    }

    public function getConciergeRequest($where_condition)
    {
        return $this->ConciergeRequest
            ->select('*, 
                co.cname as COUNTRY_NAME,
                st.sname as STATE_NAME,
                ci.ctname as CITY_NAME')
            ->join('FLXY_RESERVATION', 'CR_RESERVATION_ID = RESV_ID', 'left')
            ->join('FLXY_CUSTOMER', 'CR_CUSTOMER_ID = CUST_ID', 'left')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->join('FLXY_CONCIERGE_OFFERS', 'CR_OFFER_ID = CO_ID', 'left')
            ->join('COUNTRY as co', 'CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'CUST_CITY = ci.id', 'left')
            ->where($where_condition)
            ->first();
    }

    public function getConciergeRequests($where_condition)
    {
        return $this->ConciergeRequest->where($where_condition)->findAll();
    }

    public function createOrUpdateConciergeRequest($user, $data, $concierge_offer)
    {
        $user_id = $user['USR_ID'];

        $id = $data['id'] ?? null;
        unset($data['id']);

        $quantity = $data['CR_QUANTITY'];
        $data['CR_CUSTOMER_ID'] = $data['CR_CUSTOMER_ID'] ?? $user['USR_CUST_ID'];
        $data['CR_TOTAL_AMOUNT'] = $quantity * $concierge_offer['CO_OFFER_PRICE'];
        $data['CR_TAX_AMOUNT'] = $quantity * $concierge_offer['CO_TAX_AMOUNT'];
        $data['CR_NET_AMOUNT'] = $quantity * $concierge_offer['CO_NET_PRICE'];

        if (empty($id)) {
            $data['CR_CREATED_BY'] = $data['CR_UPDATED_BY'] = $user_id;
            $concierge_request_id = $this->ConciergeRequest->insert($data);

            $this->sendConciergeRequestEmail([
                'concierge_offer' => $concierge_offer,
                'concierge_request' => $data,
            ]);

            $msg = 'Concierge request has been created.';
            $this->generateConciergeInvoice($concierge_request_id);
        } else {
            $data['CR_UPDATED_BY'] = $user_id;
            $this->ConciergeRequest->update($id, $data);

            $msg = 'Concierge request has been updated.';
        }

        if (empty($id) && !isWeb() && $data['CR_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = [
                'amount' => $data['CR_TOTAL_AMOUNT'],
                'model' => 'FLXY_CONCIERGE_REQUESTS',
                'model_id' => $concierge_request_id,
                'reservation_id' => $data['CR_RESERVATION_ID'],
            ];
        }

        return responseJson(200, false, ['msg' => $msg], $data);
    }

    public function updateConciergeRequestById($data)
    {
        return $this->ConciergeRequest->save($data);
    }

    public function sendConciergeRequestEmail($data)
    {
        $data['from_email'] = 'notifications@farnek.com';
        $data['from_name'] = 'FlexiGuest | Hitek';

        $data['to_email'] = $data['concierge_offer']['CO_PROVIDER_EMAIL'];
        $data['to_name'] = $data['concierge_offer']['CO_PROVIDER_TITLE'];

        $data['subject'] = 'Alert! Concierge Request.';
        $data['html'] = view('EmailTemplates/concierge_request_template', $data);

        $email_library = new EmailLibrary();
        $email_library->commonEmail($data);
    }

    public function generateConciergeInvoice($concierge_request_id, $transaction_id = null)
    {
        $concierge_request = $this->getConciergeRequest("CR_ID = $concierge_request_id");
        if (empty($concierge_request))
            return null;

        $concierge_request['transaction_id'] = $transaction_id;

        $view = 'Templates/concierge_invoice_template';
        if (empty($transaction_id))
            $file_name = "assets/invoices/concierge-invoices/CR{$concierge_request['CR_ID']}-Invoice.pdf";
        else
            $file_name = "assets/receipts/concierge-receipts/CR{$concierge_request['CR_ID']}-Receipt.pdf";

        generateInvoice($file_name, $view, ['data' => $concierge_request]);
        return $file_name;
    }

    public function conciergeRevenue()
    {
        return $this->ConciergeRequest
            ->select('sum(CR_TOTAL_AMOUNT) as revenue')
            ->where("CR_PAYMENT_STATUS = 'Paid'")
            ->first()['revenue'];
    }
}
