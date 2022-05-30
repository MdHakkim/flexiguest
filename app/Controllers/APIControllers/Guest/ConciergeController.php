<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\ConciergeOffer;
use CodeIgniter\API\ResponseTrait;

class ConciergeController extends BaseController
{
    use ResponseTrait;

    private $ConciergeOffer;

    public function __construct()
    {
        $this->ConciergeOffer = new ConciergeOffer();
    }

    public function conciergeOffers()
    {
        $concierge_offers = $this->ConciergeOffer->where('status', 'enabled')->findAll();
        
        $result = responseJson(200, false, "Cocierge offers list", $concierge_offers);
        return $this->respond($result);
    }

}