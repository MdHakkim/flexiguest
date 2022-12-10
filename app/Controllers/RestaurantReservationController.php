<?php

namespace App\Controllers;

use App\Controllers\Repositories\RestaurantReservationRepository;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationController extends BaseController
{

    use ResponseTrait;

    private $RestaurantReservationRepository;

    public function __construct()
    {
        $this->RestaurantReservationRepository = new RestaurantReservationRepository();
    }
}