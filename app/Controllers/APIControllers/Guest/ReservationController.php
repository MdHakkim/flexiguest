<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ReservationRepository;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ReservationController extends BaseController
{
    use ResponseTrait;

    private $ReservationRepository;
    private $Reservation;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->Reservation = new Reservation();
    }

    public function reservationsOfCustomer()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

        $where_condition = "RESV_NAME = $customer_id AND (RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In')";
        $result = $this->ReservationRepository->reservationsOfCustomer($where_condition);

        return $this->respond(responseJson(200, false, ['msg' => 'reservations'], $result));
    }
}
