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

    public function __construct()
    {
        $this->Reservation = new Reservation();
        $this->ConciergeOffer = new ConciergeOffer();
        $this->ConciergeRequest = new ConciergeRequest();
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
}