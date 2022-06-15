<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;

class ReservationController extends BaseController
{

    use ResponseTrait;

    private $Reservation;

    public function __construct()
    {
        $this->Reservation = new Reservation();
    }

    public function getReservationDetails()
    {
        $reservation_id = $this->request->getvar('reservation_id');

        $reservation = $this->Reservation
                            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
                            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
                            ->where('RESV_ID', $reservation_id)
                            ->first();

        $output['room_details'] = <<<EOD
            <tr>
                <td>{$reservation['RM_NO']}</td>
                <td>{$reservation['RM_DESC']}</td>
                <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                <td>{$reservation['RESV_DEPARTURE']}</td>
            </tr>
        EOD;

        $output['nightly_rate_details'] = <<<EOD
            <tr>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
            </tr>
        EOD;

        $output['reservation_arrival_details'] = <<<EOD
            <tr class="active-tr" onclick="changeReservationId(this, {$reservation['RESV_ID']})">
                <td>{$reservation['CUST_FIRST_NAME']} {$reservation['CUST_LAST_NAME']}</td>
                <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                <td>{$reservation['RESV_DEPARTURE']}</td>
                <td>{$reservation['RESV_STATUS']}</td>
                <td>{$reservation['RESV_ADULTS']}</td>
                <td>{$reservation['RESV_CHILDREN']}</td>
                <td>{$reservation['RESV_RATE_CODE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
            </tr>
        EOD;

        return $this->respond(responseJson(200, false, ['msg' => 'reservation details'], $output));
    }
}
