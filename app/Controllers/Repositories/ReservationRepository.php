<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ReservationRepository extends BaseController
{
    use ResponseTrait;

    private $Reservation;

    public function __construct()
    {
        $this->Reservation = new Reservation();
    }

    public function reservationById($reservation_id)
    {
        return $this->Reservation
            ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where('RESV_ID', $reservation_id)
            ->first();
    }
}
