<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\ConciergeOffer;
use App\Models\ConciergeRequest;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
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

    public function conciergeOffers()
    {
        $concierge_offers = $this->ConciergeOffer->where('CO_STATUS', 'enabled')->findAll();
        foreach ($concierge_offers as $index => $concierge_offer) {
            $concierge_offers[$index]['CO_COVER_IMAGE'] = base_url($concierge_offer['CO_COVER_IMAGE']);
        }

        $result = responseJson(200, false, ['msg' => "Cocierge offers list"], $concierge_offers);
        return $this->respond($result);
    }

    public function makeConciergeRequest()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $user_id = $this->request->user['USR_ID'];
        $id = $this->request->getVar('id');

        $min_quantity = $max_quantity = 1;
        $offer_id = $this->request->getVar('CR_OFFER_ID');
        if (!empty($offer_id)) {
            $concierge_offer = $this->ConciergeOffer->where('CO_ID', $offer_id)->first();
            $min_quantity = $concierge_offer['CO_MIN_QUANTITY'] ?? 1;
            $max_quantity = $concierge_offer['CO_MAX_QUANTITY'] ?? 1;
        }

        $rules = [
            'CR_OFFER_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select an offer.'
                ]
            ],
            'CR_QUANTITY' => ['label' => 'Quantity', 'rules' => "required|greater_than[$min_quantity]|less_than[$max_quantity]"],
            'CR_GUEST_NAME' => ['label' => 'Guest Name', 'rules' => 'required'],
            'CR_GUEST_EMAIL' => ['label' => 'Guest Email', 'rules' => 'required|valid_email'],
            'CR_GUEST_PHONE' => ['label' => 'Guest Phone', 'rules' => 'required'],
            'CR_PREFERRED_DATE' => ['label' => 'Preffered Date', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $reservation = $this->Reservation->where('RESV_STATUS', 'Due Pre Check-In')
            ->orWhere('RESV_STATUS', 'Pre Checked-In')
            ->orWhere('RESV_STATUS', 'Checked-In')
            ->orderBy('RESV_ID', 'desc')
            ->first();

        if (empty($reservation))
            return $this->respond(responseJson(200, false, ['msg' => 'Sorry, you can\'t make request without reservation.']));
        
        $offer_id = $this->request->getVar('CR_OFFER_ID');
        $concierge_offer = $this->ConciergeOffer->where('CO_STATUS', 'enabled')->where('CO_ID', $offer_id)->first();
        if(empty($concierge_offer))
            return $this->respond(responseJson(200, false, ['msg' => 'Invalid Offer Selected.']));

        $data = $this->request->getVar();
        unset($data->id);

        $quantity = $data->CR_QUANTITY;

        $data->CR_CUSTOMER_ID = $customer_id;
        $data->CR_RESERVATION_ID = $reservation['RESV_ID'];
        $data->CR_TOTAL_AMOUNT = $quantity * $concierge_offer['CO_OFFER_PRICE'];
        $data->CR_TAX_AMOUNT = $quantity * $concierge_offer['CO_TAX_AMOUNT'];
        $data->CR_NET_AMOUNT = $quantity * $concierge_offer['CO_NET_PRICE'];
        $data->CR_CREATED_BY = $data->CR_UPDATED_BY = $user_id;

        $concierge_request_id = !empty($id)
            ? $this->ConciergeRequest->update($id, $data)
            : $this->ConciergeRequest->insert($data);

        if (!$concierge_request_id)
            return $this->respond(responseJson(500, true, ['msg' => "db insert/update not successful"]));

        if (empty($id))
            $msg = 'Concierge request has been created.';
        else
            $msg = 'Concierge request has been updated.';

        return $this->respond(responseJson(200, false, ['msg' => $msg]));
    }

    public function listConciergeRequests()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $concierge_requests = $this->ConciergeRequest
                                    ->select('FLXY_CONCIERGE_REQUESTS.*, fco.CO_TITLE, fco.CO_DESCRIPTION, fco.CO_VALID_FROM_DATE, fco.CO_VALID_TO_DATE')
                                    ->join('FLXY_CONCIERGE_OFFERS as fco', 'FLXY_CONCIERGE_REQUESTS.CR_OFFER_ID = fco.CO_ID')
                                    ->where('CR_CUSTOMER_ID', $customer_id)
                                    ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Concierge requests list'], $concierge_requests));
    }
}
