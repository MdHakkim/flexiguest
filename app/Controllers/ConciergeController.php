<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataTables\ConciergeOfferDataTable;
use App\Libraries\DataTables\ConciergeRequestDataTable;
use App\Models\ConciergeOffer;
use App\Models\ConciergeRequest;
use App\Models\Currency;
use App\Models\Room;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
{
    use ResponseTrait;

    private $Currency;
    private $ConciergeOffer;
    private $ConciergeRequest;
    private $Room;

    public function __construct()
    {
        $this->Currency = new Currency();
        $this->ConciergeOffer = new ConciergeOffer();
        $this->ConciergeRequest = new ConciergeRequest();
        $this->Room = new Room();
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
            'CO_PROVIDER_EMAIL' => ['label' => 'Provider email', 'rules' => 'required'],
            'CO_ACTUAL_PRICE' => ['label' => 'Actual price', 'rules' => 'required'],
            'CO_OFFER_PRICE' => ['label' => 'Offer price', 'rules' => 'required'],
            'CO_CURRENCY_ID' => [
                'label' => 'Currency',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a currency.'
                ]
            ],
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
        $data['rooms'] = $this->Room->findAll();
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

        $rules = [
            'CR_OFFER_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select an offer.'
                ]
            ],
            'CR_QUANTITY' => ['label' => 'Quantity', 'rules' => 'required|greater_than_equal_to[1]'],
            'CR_GUEST_NAME' => ['label' => 'Guest name', 'rules' => 'required'],
            'CR_GUEST_EMAIL' => ['label' => 'Guest email', 'rules' => 'required'],
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
            'CR_STATUS' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a status.'
                ]
            ],
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

        if (empty($id))
            $msg = 'Concierge request has been created.';
        else
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
}
