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
            ->select("FLXY_RESERVATION.*, fc.*, fr.*
                        concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_LAST_NAME) as CUST_NAME,
                        co.cname as COUNTRY_NAME,
                        st.sname as STATE_NAME,
                        ci.ctname as CITY_NAME")
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('COUNTRY as co', 'fc.CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'fc.CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'fc.CUST_CITY = ci.id', 'left')
            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
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

    public function currentReservations()
    {
        return $this->Reservation
            ->where('RESV_STATUS', 'Due Pre Check-In')
            ->orWhere('RESV_STATUS', 'Pre Checked-In')
            ->orWhere('RESV_STATUS', 'Checked-In')
            ->orderBy('RESV_ID', 'desc')
            ->findAll();
    }
}
