<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\News;

class PickupPointController extends BaseController
{

    use ResponseTrait;

    private $News;

    public function __construct()
    {
        $this->News = new News();
    }

    public function pickupPoint()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport/pickup_point', $data);
    }
}