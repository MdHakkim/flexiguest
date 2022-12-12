<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RestaurantReservation;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationRepository extends BaseController
{
    use ResponseTrait;

    private $RestaurantReservation;

    public function __construct()
    {
        $this->RestaurantReservation = new RestaurantReservation();
    }

    public function checkReservationConflict($data)
    {
        $check = $this->RestaurantReservation
            ->select('sum(RR_NO_OF_GUESTS) as RESERVED_SEATS')
            ->where('RR_SLOT_ID', $data['RR_SLOT_ID'])
            ->first();

        if(($check['RESERVED_SEATS'] + $data['RR_NO_OF_GUESTS']) > $data['RE_SEATING_CAPACITY'])
            return true;

        return false;
    }

    public function makeReservation($data)
    {
        return $this->RestaurantReservation->save($data);
    }
}
