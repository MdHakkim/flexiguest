<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\DropoffPoint;
use App\Models\FlightCarrier;
use App\Models\PickupPoint;
use App\Models\Reservation;
use App\Models\TransportRequest;
use App\Models\TransportType;
use CodeIgniter\API\ResponseTrait;

class TransportRequestController extends BaseController
{
    use ResponseTrait;

    private $Reservation;
    private $PickupPoint;
    private $DropoffPoint;
    private $FlightCarrier;
    private $TransportType;
    private $TransportRequest;

    public function __construct()
    {
        $this->Reservation = new Reservation();
        $this->PickupPoint = new PickupPoint();
        $this->DropoffPoint = new DropoffPoint();
        $this->FlightCarrier = new FlightCarrier();
        $this->TransportType = new TransportType();
        $this->TransportRequest = new TransportRequest();
    }

    public function lookupApi()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

        $data['RESERVATIONS'] = $this->Reservation->select('RESV_ID, RESV_ROOM, RM_ID')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where('RESV_NAME', $customer_id)
            ->where('RESV_STATUS !=', 'Checked-Out-Requested')
            ->where('RESV_STATUS !=', 'Checked-Out')
            ->where('RESV_ROOM !=', '')
            ->findAll();

        $data['PICKUP_POINTS'] = $this->PickupPoint->select('PP_ID as id, PP_POINT as label')->orderBy('PP_SEQUENCE')->findAll();
        $data['DROPOFF_POINTS'] = $this->DropoffPoint->select('DP_ID as id, DP_POINT as label')->orderBy('DP_SEQUENCE')->findAll();
        $data['FLIGHT_CARRIERS'] = $this->FlightCarrier->select('FC_ID, FC_FLIGHT_CARRIER, FC_FLIGHT_CODE')->orderBy('FC_SEQUENCE')->findAll();
        $data['TRANSPORT_TYPES'] = $this->TransportType->select('TT_ID as id, TT_LABEL as label')->orderBy('TT_DISPLAY_SEQUENCE')->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'lookup API'], $data));
    }

    public function createRequest()
    {
        $user_id = $this->request->user['USR_ID'];
        $customer_id = $this->request->user['USR_CUST_ID'];

        $validate = $this->validate([
            'TR_RESERVATION_ID' => ['label' => 'reservation', 'rules' => 'required', 'errors' => ['required' => 'Please select a reservation.']],
            'TR_ROOM_ID' => ['label' => 'room', 'rules' => 'required'],
            'TR_GUEST_NAME' => ['label' => 'guest name', 'rules' => 'required'],
            'TR_TRAVEL_TYPE' => ['label' => 'travel type', 'rules' => 'required'],
            'TR_TRAVEL_PURPOSE' => ['label' => 'travel purpose', 'rules' => 'Please select a travel purpose.'],
            'TR_TRANSPORT_TYPE_ID' => ['label' => 'transport type', 'rules' => 'required', 'errors' => ['required' => 'Please select a transport type.']],
            'TR_TRAVEL_DATE' => ['label' => 'travel date', 'rules' => 'required'],
            'TR_TRAVEL_TIME' => ['label' => 'travel time', 'rules' => 'required'],
            'TR_ADULTS' => ['label' => 'adult', 'rules' => 'required|greater_than[0]'],
            'TR_TOTAL_PASSENGERS' => ['label' => 'total passenger', 'rules' => 'required|greater_than[0]'],
            'TR_PICKUP_POINT_ID' => ['label' => 'pickup point', 'rules' => 'required', 'errors' => ['required' => 'Please select a pickup point.']],
            'TR_DROPOFF_POINT_ID' => ['label' => 'dropoff point', 'rules' => 'required', 'errors' => ['required' => 'Please select a dropoff point.']],
            'TR_FLIGHT_CARRIER_ID' => ['label' => 'flight carrier', 'rules' => 'required', 'errors' => ['required' => 'Please select a flight carrier.']],
            'TR_PAYMENT_METHOD' => ['label' => 'payment method', 'rules' => 'required', 'errors' => ['required' => 'Please select a payment method.']], 
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        // $already_exist = $this->TransportRequest->where('TR_CUSTOMER_ID', $customer_id)->where("TR_STATUS = 'New' or TR_STATUS = 'In Progress'")->findAll();
        // if($already_exist)
        //     return $this->respond(responseJson(200, false, ['msg' => 'You have already requested a ride.']));

        $data = $this->request->getVar();
        $data->TR_CUSTOMER_ID = $customer_id;
        $data->TR_CREATED_BY = $user_id;
        $data->TR_UPDATED_BY = $user_id;

        $this->TransportRequest->insert($data);
        return $this->respond(responseJson(200, false, ['msg' => 'Transport request submitted successfully.']));
    }

    public function allRequests()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $all_requests = $this->TransportRequest
            ->select('FLXY_TRANSPORT_REQUESTS.*, pp.PP_POINT, dp.DP_POINT')
            ->join('FLXY_PICKUP_POINTS as pp', 'FLXY_TRANSPORT_REQUESTS.TR_PICKUP_POINT_ID = pp.PP_ID', 'left')
            ->join('FLXY_DROPOFF_POINTS as dp', 'FLXY_TRANSPORT_REQUESTS.TR_DROPOFF_POINT_ID = dp.DP_ID', 'left')
            ->where('TR_CUSTOMER_ID', $customer_id)
            ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'All requests'], $all_requests));
    }
}
