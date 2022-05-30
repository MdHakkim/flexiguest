<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataTables\ConciergeOffersDataTable;
use App\Models\ConciergeOffer;
use App\Models\Currency;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
{
    use ResponseTrait;

    private $Currency;
    private $ConciergeOffer;

    public function __construct()
    {
        $this->Currency = new Currency();
        $this->ConciergeOffer = new ConciergeOffer();
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
        $mine = new ConciergeOffersDataTable();
        $mine->generate_DatatTable();
        exit;
    }

    public function storeConciergeOffer()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'title' => ['required'],
            'description' => ['required'],
            'valid_from_date' => [ 'label' => 'From date', 'rules' => 'required'],
            'valid_to_date' => [ 'label' => 'To date', 'rules' => 'required'],
            'actual_price' => ['label' => 'Actual price', 'rules' => 'required'],
            'offer_price' => ['label' => 'Offer price', 'rules' => 'required'],
            'currency_id' => [
                'label' => 'Currency', 
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a currency.'
                ]
            ],
        ];

        if (empty($id) || $this->request->getFile('cover_image'))
            $rules = array_merge($rules, [
                'cover_image' => [
                    'label' => 'Cover Image',
                    'rules' => ['uploaded[cover_image]', 'mime_in[cover_image,image/png,image/jpg,image/jpeg]', 'max_size[cover_image,2048]']
                ],
            ]);

        if ($this->request->getFile('provider_logo'))
            $rules = array_merge($rules, [
                'provider_logo' => [
                    'label' => 'provider logo',
                    'rules' => ['mime_in[provider_logo,image/png,image/jpg,image/jpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]', 'max_size[provider_logo, 2048]']
                ],
            ]);

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $result = responseJson("-402", $errors);

            return $this->respond($result);
        }

        $data = $this->request->getPost();
        
        if ($this->request->getFile('cover_image')) {
            $image = $this->request->getFile('cover_image');
            $image_name = $image->getName();
            $directory = "assets/Uploads/concierge_offer/cover_image/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "cover image not uploaded"));

            $data['cover_image'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        if ($this->request->getFile('provider_logo')) {
            $image = $this->request->getFile('provider_logo');
            $image_name = $image->getName();
            $directory = "assets/Uploads/concierge_offer/provider_logo/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return $this->respond(responseJson("500", true, "provider logo not uploaded"));

            $data['provider_logo'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        $concierge_offer_id = !empty($id)
            ? $this->ConciergeOffer->update($id, $data)
            : $this->ConciergeOffer->insert($data);
        
        if (!$concierge_offer_id)
            return $this->respond(responseJson("-444", false, "db insert/update not successful", $concierge_offer_id));
        
        if(empty($id))
            $msg = 'Concierge Offer has been created.';
        else
            $msg = 'Concierge Offer has been updated.';

        return $this->respond(responseJson("200", false, $msg));
    }

    public function editConciergeOffer()
    {
        $id = $this->request->getPost('id');

        $concierge_offer = $this->ConciergeOffer->where('id', $id)->first();
        return $this->respond($concierge_offer);
    }

    public function deleteConciergeOffer()
    {
        $id = $this->request->getPost('id');

        $response = $this->ConciergeOffer->delete($id);
        if($response)
            return $this->respond(responseJson("200", false, "Concierge offer deleted successfully", $response));

        return $this->respond(responseJson("-402", "Concierge offer not deleted"));
    }

    public function changeConciergeOfferStatus()
    {
        $id = $this->request->getPost('id');
        $data['status'] = $this->request->getPost('status');
        
        $response = $this->ConciergeOffer->update($id, $data);
        if($response)
            return $this->respond(responseJson("200", false, "Concierge status updated successfully", $response));

        return $this->respond(responseJson("-402", "Not able to update status"));
    }

}
