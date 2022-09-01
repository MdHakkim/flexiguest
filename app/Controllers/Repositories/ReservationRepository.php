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
            ->select("FLXY_RESERVATION.*, FLXY_CUSTOMER.*, FLXY_ROOM.*, concat(CUST_FIRST_NAME, ' ', CUST_LAST_NAME) as CUST_NAME")
            ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where('RESV_ID', $reservation_id)
            ->first();
    }

    public function allReservations($where_condition = '1 = 1')
    {
        // $where_condition = "RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In'";

        return $this->Reservation
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as rm', 'FLXY_RESERVATION.RESV_ROOM = rm.RM_NO', 'left')
            ->where($where_condition)
            ->findAll();
    }

    public function reservationsOfCustomer($customer_id, $where_condition)
    {
        return $this->Reservation
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where('RESV_NAME', $customer_id)
            ->where($where_condition)
            ->findAll();
    }
}
