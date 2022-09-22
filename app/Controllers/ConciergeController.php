<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\PaymentRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Libraries\DataTables\ConciergeOfferDataTable;
use App\Libraries\DataTables\ConciergeRequestDataTable;
use App\Models\ConciergeOffer;
use App\Models\ConciergeRequest;
use App\Models\Currency;
use App\Models\Reservation;
use App\Models\Room;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
{
    use ResponseTrait;

    private $ConciergeRepository;
    private $ReservationRepository;
    private $PaymentRepository;
    private $Currency;
    private $ConciergeOffer;
    private $ConciergeRequest;
    private $Room;
    private $Reservation;

    public function __construct()
    {
        $this->ConciergeRepository = new ConciergeRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->PaymentRepository = new PaymentRepository();
        $this->Currency = new Currency();
        $this->ConciergeOffer = new ConciergeOffer();
        $this->ConciergeRequest = new ConciergeRequest();
        $this->Room = new Room();
        $this->Reservation = new Reservation();
    }

    public function conciergeOffer()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();
        $data['currencies'] = $this->Currency->findAll();

        return view('frontend/concierge/concierge_offer', $data);
    }

    public function allConciergeOffers()
    {
        $mine = new ConciergeOfferDataTable();
        $mine->generate_DatatTable();
        exit;
    }

    public function storeConciergeOffer()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'CO_TITLE' => ['label' => 'Title', 'rules' => 'required'],
            'CO_DESCRIPTION' => ['label' => 'Description', 'rules' => 'required'],
            'CO_VALID_FROM_DATE' => ['label' => 'From date', 'rules' => 'required'],
            'CO_VALID_TO_DATE' => ['label' => 'To date', 'rules' => 'required'],
            'CO_PROVIDER_TITLE' => ['label' => 'Provider title', 'rules' => 'required'],
            'CO_PROVIDER_EMAIL' => ['label' => 'Provider email', 'rules' => 'required|valid_email'],
            'CO_CURRENCY_ID' => [
                'label' => 'Currency',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a currency.'
                ]
            ],
            'CO_ACTUAL_PRICE' => ['label' => 'Actual price', 'rules' => 'required'],
            'CO_OFFER_PRICE' => ['label' => 'Offer price', 'rules' => 'required'],
            'CO_MIN_QUANTITY' => ['label' => 'Min Quantity', 'rules' => 'required|greater_than_equal_to[1]'],
            'CO_MAX_QUANTITY' => ['label' => 'Max Quantity', 'rules' => 'required|greater_than_equal_to[1]'],
            'CO_MIN_AGE' => ['label' => 'Min Age', 'rules' => 'required'],
            'CO_MAX_AGE' => ['label' => 'Max Age', 'rules' => 'required'],
            'CO_TAX_RATE' => ['label' => 'Tax rate', 'rules' => 'required'],
            'CO_TAX_AMOUNT' => ['label' => 'Tax amount', 'rules' => 'required'],
            'CO_NET_PRICE' => ['label' => 'Net price', 'rules' => 'required'],
        ];

        if (empty($id) || $this->request->getFile('CO_COVER_IMAGE'))
            $rules = array_merge($rules, [
                'CO_COVER_IMAGE' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[CO_COVER_IMAGE]', 'mime_in[CO_COVER_IMAGE,image/png,image/jpg,image/jpeg]', 'max_size[CO_COVER_IMAGE,2048]']
                ],
            ]);

        if ($this->request->getFile('CO_PROVIDER_LOGO'))
            $rules = array_merge($rules, [
                'CO_PROVIDER_LOGO' => [
                    'label' => 'provider logo',
                    'rules' => ['mime_in[CO_PROVIDER_LOGO,image/png,image/jpg,image/jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]', 'max_size[CO_PROVIDER_LOGO, 2048]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson("-402", $errors);

            return $this->respond($result);
        }

        $data = $this->request->getPost();

        if ($this->request->getFile('CO_COVER_IMAGE')) {
            $image = $this->request->getFile('CO_COVER_IMAGE');
            $image_name = $image->getName();
            $directory = "assets/Uploads/concierge_offer/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "cover image not uploaded"));

            $data['CO_COVER_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        if ($this->request->getFile('CO_PROVIDER_LOGO')) {
            $image = $this->request->getFile('CO_PROVIDER_LOGO');
            $image_name = $image->getName();
            $directory = "assets/Uploads/concierge_offer/provider_logo/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "provider logo not uploaded"));

            $data['CO_PROVIDER_LOGO'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $concierge_offer_id = !empty($id)
            ? $this->ConciergeOffer->update($id, $data)
            : $this->ConciergeOffer->insert($data);

        if (!$concierge_offer_id)
            return $this->respond(responseJson("-444", false, "db insert/update not successful", $concierge_offer_id));

        if (empty($id))
            $msg = 'Concierge Offer has been created.';
        else
            $msg = 'Concierge Offer has been updated.';

        return $this->respond(responseJson("200", false, $msg));
    }

    public function editConciergeOffer()
    {
        $id = $this->request->getPost('id');

        $concierge_offer = $this->ConciergeOffer->where('CO_ID', $id)->first();
        return $this->respond($concierge_offer);
    }

    public function deleteConciergeOffer()
    {
        $id = $this->request->getPost('id');

        $response = $this->ConciergeOffer->delete($id);
        if ($response)
            return $this->respond(responseJson("200", false, "Concierge offer deleted successfully", $response));

        return $this->respond(responseJson("-402", "Concierge offer not deleted"));
    }

    public function changeConciergeOfferStatus()
    {
        $id = $this->request->getPost('id');
        $data['CO_STATUS'] = $this->request->getPost('status');

        $response = $this->ConciergeOffer->update($id, $data);
        if ($response)
            return $this->respond(responseJson("200", false, "Concierge status updated successfully", $response));

        return $this->respond(responseJson("-402", "Not able to update status"));
    }

    public function conciergeRequest()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();
        $data['reservations'] = $this->Reservation
            ->select('FLXY_RESERVATION.RESV_ID, fc.CUST_ID, fc.CUST_FIRST_NAME, fc.CUST_MIDDLE_NAME, fc.CUST_LAST_NAME')
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID')
            ->where('RESV_STATUS', 'Due Pre Check-In')
            ->orWhere('RESV_STATUS', 'Pre Checked-In')
            ->orWhere('RESV_STATUS', 'Checked-In')
            ->findAll();

        $data['concierge_offers'] = $this->ConciergeOffer->where('CO_STATUS', 'enabled')->findAll();

        return view('frontend/concierge/concierge_request', $data);
    }

    public function allConciergeRequests()
    {
        $mine = new ConciergeRequestDataTable();
        $mine->generate_DatatTable();
        exit;
    }

    public function storeConciergeRequest()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

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
            'CR_QUANTITY' => ['label' => 'Quantity', 'rules' => "required|greater_than_equal_to[$min_quantity]|less_than_equal_to[$max_quantity]"],
            'CR_GUEST_NAME' => ['label' => 'Guest name', 'rules' => 'required'],
            'CR_GUEST_EMAIL' => ['label' => 'Guest email', 'rules' => 'required|valid_email'],
            'CR_GUEST_PHONE' => ['label' => 'Guest phone', 'rules' => 'required'],
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
            'CR_PREFERRED_DATE' => [
                'label' => 'Preferred Date',
                'rules' => 'required'
            ],
            'CR_PREFERRED_TIME' => [
                'label' => 'Preferred Time',
                'rules' => 'required'
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson("-402", $errors);

            return $this->respond($result);
        }

        $data = $this->request->getPost();

        $concierge_request_id = !empty($id)
            ? $this->ConciergeRequest->update($id, $data)
            : $this->ConciergeRequest->insert($data);

        if (!$concierge_request_id)
            return $this->respond(responseJson("-444", false, "db insert/update not successful", $concierge_request_id));

        if (empty($id)) {
            $this->ConciergeRepository->sendConciergeRequestEmail([
                'concierge_offer' => $concierge_offer,
                'concierge_request' => $data,
            ]);

            $msg = 'Concierge request has been created.';
        } else
            $msg = 'Concierge request has been updated.';

        return $this->respond(responseJson("200", false, $msg));
    }

    public function editConciergeRequest()
    {
        $id = $this->request->getPost('id');

        $concierge_request = $this->ConciergeRequest->where('CR_ID', $id)->first();
        return $this->respond($concierge_request);
    }

    public function deleteConciergeRequest()
    {
        $id = $this->request->getPost('id');

        $response = $this->ConciergeRequest->delete($id);
        if ($response)
            return $this->respond(responseJson("200", false, "Concierge request deleted successfully", $response));

        return $this->respond(responseJson("-402", "Concierge request not deleted"));
    }

    // API Functions
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
        $user = $this->request->user;

        $data = json_decode(json_encode($this->request->getVar()), true);

        if (!isWeb()) {
            $current_reservations = $this->ReservationRepository->currentReservations();
            if (empty($current_reservations))
                return $this->respond(responseJson(200, false, ['msg' => 'Sorry, you can\'t make request without reservation.']));
            $data['CR_RESERVATION_ID'] = $current_reservations[0]['RESV_ID'];
        }

        $offer_id = $this->request->getVar('CR_OFFER_ID');
        $concierge_offer = $this->ConciergeRepository->getConciergeOffer("CO_STATUS = 'enabled' and CO_ID = $offer_id");
        if (empty($concierge_offer))
            return $this->respond(responseJson(200, false, ['msg' => 'Invalid Offer Selected.']));

        $min_quantity = $concierge_offer['CO_MIN_QUANTITY'] ?? 1;
        $max_quantity = $concierge_offer['CO_MAX_QUANTITY'] ?? 1;
        if (!$this->validate($this->ConciergeRepository->validationRules($data, $min_quantity, $max_quantity)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->ConciergeRepository->createOrUpdateConciergeRequest($user, $data, $concierge_offer);
        if (!isWeb() && $result['SUCCESS'] == 200 && $data['CR_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = $result['RESPONSE']['OUTPUT'];
            $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        }

        return $this->respond($result);
    }

    public function listConciergeRequests()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $concierge_requests = $this->ConciergeRequest
            ->select('FLXY_CONCIERGE_REQUESTS.*, fco.CO_TITLE, fco.CO_DESCRIPTION, fco.CO_VALID_FROM_DATE, fco.CO_VALID_TO_DATE, fco.CO_COVER_IMAGE')
            ->join('FLXY_CONCIERGE_OFFERS as fco', 'FLXY_CONCIERGE_REQUESTS.CR_OFFER_ID = fco.CO_ID')
            ->where('CR_CUSTOMER_ID', $customer_id)
            ->findAll();

        foreach ($concierge_requests as $index => $request) {
            $concierge_requests[$index]['CO_COVER_IMAGE'] = base_url($request['CO_COVER_IMAGE']);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Concierge requests list'], $concierge_requests));
    }
}
