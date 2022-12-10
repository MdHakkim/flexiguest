<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RestaurantReservationSlot;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationRepository extends BaseController
{
    use ResponseTrait;

    private $RestaurantReservationSlot;

    public function __construct()
    {
        $this->RestaurantReservationSlot = new RestaurantReservationSlot();
    }

    public function checkReservationConflict($data)
    {
        $check = $this->RestaurantReservationSlot
            ->select('sum(RR_NO_OF_GUESTS) as RESERVED_SEATS')
            ->where('RR_SLOT_ID', $data['RR_SLOT_ID'])
            ->where('RR_NO_OF_GUESTS', )
            ->first();

        if(($check['RESERVED_SEATS'] + $data['RR_NO_OF_GUESTS']) > $data['RE_SEATING_CAPACITY'])
            return false;

        return true;
    }

    public function makeReservation($data)
    {
        return $this->RestaurantReservationSlot->save($data);
    }
}
