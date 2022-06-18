<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;
use App\Models\ShareReservations;

class ReservationController extends BaseController
{

    use ResponseTrait;

    private $DB;
    private $Reservation;
    private $ShareReservations;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Reservation = new Reservation();
        $this->ShareReservations = new ShareReservations();
    }

    public function getReservationDetails()
    {
        $reservation_id = $this->request->getvar('reservation_id');
        $reservation_ids[] = $reservation_id;

        $share_reservations = $this->ShareReservations->where('FSR_RESERVATION_ID', $reservation_id)->findColumn('FSR_OTHER_RESERVATION_ID');
        if ($share_reservations)
            $reservation_ids = array_merge($reservation_ids, $share_reservations);

        $reservations = $this->Reservation
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
            ->whereIn('RESV_ID', $reservation_ids)
            ->findAll();

        $output['room_details'] = $output['nightly_rate_details'] = $output['reservation_arrival_details'] = '';

        foreach ($reservations as $reservation) {
            if (empty($output['room_details'])) {
                $output['room_details'] = <<<EOD
                    <tr>
                        <td>{$reservation['RM_NO']}</td>
                        <td>{$reservation['RM_DESC']}</td>
                        <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                        <td>{$reservation['RESV_DEPARTURE']}</td>
                    </tr>
                EOD;
            }

            $output['nightly_rate_details'] .= <<<EOD
                <tr>
                    <td>{$reservation['RESV_RATE']}</td>
                    <td>{$reservation['RESV_RATE']}</td>
                    <td>{$reservation['RESV_RATE']}</td>
                    <td>{$reservation['RESV_RATE']}</td>
                </tr>
            EOD;

            $class_name = '';
            if (empty($output['reservation_arrival_details']))
                $class_name = 'active-tr';

            $output['reservation_arrival_details'] .= <<<EOD
                <tr class="$class_name" onclick="changeReservationId(this, {$reservation['RESV_ID']})">
                    <input type="hidden" name="share_reservations[]" value="{$reservation['RESV_ID']}"/>
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
        }

        return $this->respond(responseJson(200, false, ['msg' => 'reservation details'], $output));
    }

    public function sharesCreateReservation()
    {
        $user_id = session()->get('USR_ID');

        $validate = $this->validate([
            'CUST_ID' => ['label' => 'Guest', 'rules' => 'required'],
            'RESV_ADULTS' => ['label' => 'Adult', 'rules' => 'required|greater_than[0]'],
            'RESV_CHILDREN' => ['label' => 'Children', 'rules' => 'required|greater_than_equal_to[0]'],
            'RESV_RESRV_TYPE' => ['label' => 'Reservation Type', 'rules' => 'required'],
            'RESV_PAYMENT_TYPE' => ['label' => 'Payment Type', 'rules' => 'required'],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();

            $result = $this->responseJson(403, true, $validate);
            return $this->respond($result);
        }

        $other_reservation_id = $this->request->getVar('other_reservation_id');
        $other_reservation = $this->Reservation->find($other_reservation_id);

        $data = [
            'RESV_ARRIVAL_DT' => $other_reservation['RESV_ARRIVAL_DT'],
            'RESV_NIGHT' => $other_reservation['RESV_NIGHT'],
            'RESV_ADULTS' => $this->request->getVar('RESV_ADULTS'),
            'RESV_CHILDREN' => $this->request->getVar('RESV_CHILDREN'),
            'RESV_DEPARTURE' => $other_reservation['RESV_DEPARTURE'],
            'RESV_NO_F_ROOM' => $other_reservation['RESV_NO_F_ROOM'],
            'RESV_NAME' => $this->request->getVar('CUST_ID'),
            'RESV_RATE_CODE' => $other_reservation['RESV_RATE_CODE'],
            'RESV_STATUS' => $other_reservation['RESV_STATUS'],
            'RESV_RM_TYPE' => $other_reservation['RESV_RM_TYPE'],
            'RESV_ROOM' => $other_reservation['RESV_ROOM'],
            'RESV_RATE' => $other_reservation['RESV_RATE'],
            'RESV_RESRV_TYPE' => $this->request->getVar('RESV_RESRV_TYPE'),
            'RESV_ORIGIN' => $other_reservation['RESV_ORIGIN'],
            'RESV_PAYMENT_TYPE' => $this->request->getVar('RESV_PAYMENT_TYPE'),

            'RESV_CREATE_UID' => $user_id,
            'RESV_UPDATE_UID' => $user_id,
        ];

        $reservation_id = $this->Reservation->insert($data);

        if (!$reservation_id)
            return $this->respond(responseJson(500, false, ['msg' => 'Something went wrong!']));


        $reservation = $this->Reservation
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
            ->where('RESV_ID', $reservation_id)
            ->first();

        $output['nightly_rate_details'] = <<<EOD
            <tr>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
                <td>{$reservation['RESV_RATE']}</td>
            </tr>
        EOD;

        $output['reservation_arrival_details'] = <<<EOD
            <tr onclick="changeReservationId(this, {$reservation['RESV_ID']})">
                <input type="hidden" name="share_reservations[]" value="{$reservation['RESV_ID']}"/>
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

        $result = responseJson(200, false, ['msg' => 'reservation created'], $output);
        return $this->respond($result);
    }

    public function searchReservation()
    {
        $reservation_id = $this->request->getVar('RESV_ID'); // when searching for single reservation using RESV_ID (pass only this param)

        // for reservation search filter
        $current_reservation_id = $this->request->getVar('current_reservation_id');
        $first_name = $this->request->getVar('CUST_FIRST_NAME');
        $last_name = $this->request->getVar('CUST_LAST_NAME');
        $room_no = $this->request->getVar('RESV_ROOM');

        $params = [
            'room_no' => $room_no, 'first_name' => $first_name, 'last_name' => $last_name,
            'reservation_id' => $reservation_id, 'current_reservation_id' => $current_reservation_id
        ];
        $reservations = $this->DB->query(
            "select fr.*, fc.CUST_TITLE, fc.CUST_FIRST_NAME, fc.CUST_LAST_NAME from FLXY_RESERVATION fr
                left join FLXY_CUSTOMER fc on fr.RESV_NAME = fc.CUST_ID 
                where fr.RESV_ID != :current_reservation_id:
                    and (fr.RESV_ROOM = :room_no: 
                    or fr.RESV_ID = :reservation_id:
                    or fr.RESV_NAME in (select CUST_ID from FLXY_CUSTOMER where CUST_FIRST_NAME = :first_name: or CUST_LAST_NAME = :last_name:))",
            $params
        )->getResultArray();

        $output['reservations'] = $reservations;
        $output['searched_reservations'] = $output['nightly_rate_details'] = $output['reservation_arrival_details'] = '';
        foreach ($reservations as $reservation) {
            if ($reservation_id) { // when reservation is selected from search reservation popup

                $output['nightly_rate_details'] = <<<EOD
                    <tr>
                        <td>{$reservation['RESV_RATE']}</td>
                        <td>{$reservation['RESV_RATE']}</td>
                        <td>{$reservation['RESV_RATE']}</td>
                        <td>{$reservation['RESV_RATE']}</td>
                    </tr>
                EOD;

                $output['reservation_arrival_details'] .= <<<EOD
                    <tr onclick="changeReservationId(this, {$reservation['RESV_ID']})">
                        <input type="hidden" name="share_reservations[]" value="{$reservation['RESV_ID']}"/>
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
            } else {
                $output['searched_reservations'] .= <<<EOD
                    <tr class="select-reservation" data_sysid="{$reservation['RESV_ID']}">
                        <td class="editReserWindow" data_sysid="{$reservation['RESV_ID']}">
                            <i class="fa-solid fa-user-pen"></i>
                        </td>
                        <td class="select">{$reservation['CUST_FIRST_NAME']} {$reservation['CUST_LAST_NAME']}</td>
                        <td class="select">{$reservation['RESV_STATUS']}</td>
                        <td class="select">{$reservation['RESV_ARRIVAL_DT']}</td>
                        <td class="select">{$reservation['RESV_DEPARTURE']}</td>
                        <td class="select">{$reservation['RESV_RATE_CODE']}</td>
                        <td class="select">{$reservation['RESV_RM_TYPE']}</td>
                        <td class="select">{$reservation['RESV_ROOM']}</td>
                    </tr>
                EOD;
            }
        }

        if (empty($output['searched_reservations']) && empty($reservation_id)) {
            $output['html'] = <<<EOD
                <tr>
                    <li>No Record Found!</li>
                </tr>
            EOD;
        }


        return $this->respond(responseJson(200, false, ['msg' => 'reservations'], $output));
    }

    public function addShareReservations()
    {
        $user_id = session()->get('USR_ID');

        $reservation_ids = $this->request->getVar('reservation_ids');
        $this->ShareReservations->whereIn('FSR_RESERVATION_ID', $reservation_ids)->delete();

        foreach ($reservation_ids as $reservation_id) {
            foreach ($reservation_ids as $rid) {
                if ($reservation_id != $rid) {
                    $data = [
                        'FSR_RESERVATION_ID' => $reservation_id,
                        'FSR_OTHER_RESERVATION_ID' => $rid,
                        'FSR_CREATED_BY' => $user_id,
                        'FSR_UPDATED_BY' => $user_id,
                    ];
                    $this->ShareReservations->insert($data);
                }
            }
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Shared successfully.']));
    }

    public function breakShareReservation()
    {
        $reservation_id = $this->request->getVar('reservation_id');

        $this->ShareReservations->where('FSR_RESERVATION_ID', $reservation_id)->orWhere('FSR_OTHER_RESERVATION_ID', $reservation_id)->delete();

        return $this->respond(responseJson(200, false, ['msg' => 'Shared reservation removed successfully.']));
    }
}
