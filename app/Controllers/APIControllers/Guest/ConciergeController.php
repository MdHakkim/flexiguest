<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\ConciergeOffer;
use App\Models\ConciergeRequest;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
{
    use ResponseTrait;

    private $ConciergeOffer;
    private $ConciergeRequest;

    public function __construct()
    {
        $this->ConciergeOffer = new ConciergeOffer();
        $this->ConciergeRequest = new ConciergeRequest();
    }

    public function conciergeOffers()
    {
        $concierge_offers = $this->ConciergeOffer->where('CO_STATUS', 'enabled')->findAll();
        foreach($concierge_offers as $index => $concierge_offer){
            $concierge_offers[$index]['CO_COVER_IMAGE'] = base_url($concierge_offer['CO_COVER_IMAGE']);
        }

        $result = responseJson(200, false, "Cocierge offers list", $concierge_offers);
        return $this->respond($result);
    }

    public function makeConciergeRequest()
    {
        $id = $this->request->getVar('id');

        $min_quantity = $max_quantity = 1;
        $offer_id = $this->request->getVar('CR_OFFER_ID');
        if(!empty($offer_id)){
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
            'CR_GUEST_NAME' => ['label' => 'Guest name', 'rules' => 'required'],
            'CR_GUEST_EMAIL' => ['label' => 'Guest email', 'rules' => 'required|valid_email'],
            'CR_GUEST_PHONE' => ['label' => 'Guest phone', 'rules' => 'required'],
            'CR_GUEST_ROOM_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a room.'
                ]
            ],
            'CR_TOTAL_AMOUNT' => ['label' => 'Total amount', 'rules' => 'required'],
            'CR_TAX_AMOUNT' => ['label' => 'Tax amount', 'rules' => 'required'],
            'CR_NET_AMOUNT' => ['label' => 'Net amount', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = $this->request->getVar();
        unset($data->id);

        $concierge_request_id = !empty($id)
            ? $this->ConciergeRequest->update($id, $data)
            : $this->ConciergeRequest->insert($data);

        if (!$concierge_request_id)
            return $this->respond(responseJson(500, true, "db insert/update not successful"));

        if (empty($id))
            $msg = 'Concierge request has been created.';
        else
            $msg = 'Concierge request has been updated.';

        return $this->respond(responseJson(200, false, ['msg' => $msg]));
    }
}
