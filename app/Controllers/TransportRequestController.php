<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\PaymentRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\TransportRequestRepository;
use App\Libraries\ServerSideDataTable;
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

    private $TransportRequestRepository;
    private $ReservationRepository;
    private $PaymentRepository;
    private $Reservation;
    private $PickupPoint;
    private $DropoffPoint;
    private $FlightCarrier;
    private $TransportType;
    private $TransportRequest;

    public function __construct()
    {
        $this->TransportRequestRepository = new TransportRequestRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->PaymentRepository = new PaymentRepository();
        $this->Reservation = new Reservation();
        $this->PickupPoint = new PickupPoint();
        $this->DropoffPoint = new DropoffPoint();
        $this->FlightCarrier = new FlightCarrier();
        $this->TransportType = new TransportType();
        $this->TransportRequest = new TransportRequest();
    }

    public function transportRequest()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['pickup_points'] = $this->PickupPoint->orderBy('PP_SEQUENCE')->findAll();
        $data['dropoff_points'] = $this->DropoffPoint->orderBy('DP_SEQUENCE')->findAll();
        $data['flight_carriers'] = $this->FlightCarrier->orderBy('FC_SEQUENCE')->findAll();
        $data['transport_types'] = $this->TransportType->orderBy('TT_DISPLAY_SEQUENCE')->findAll();

        $data['reservations'] = $this->Reservation
            ->select('FLXY_RESERVATION.RESV_ID, FLXY_RESERVATION.RESV_ROOM, rm.RM_ID, fc.CUST_ID, fc.CUST_FIRST_NAME, fc.CUST_MIDDLE_NAME, fc.CUST_LAST_NAME')
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as rm', 'FLXY_RESERVATION.RESV_ROOM = rm.RM_NO', 'left')
            ->where('RESV_STATUS', 'Due Pre Check-In')
            ->orWhere('RESV_STATUS', 'Pre Checked-In')
            ->orWhere('RESV_STATUS', 'Checked-In')
            ->findAll();

        return view('frontend/transport/transport_request', $data);
    }

    public function allTransportRequests()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_TRANSPORT_REQUESTS 
                        left join FLXY_ROOM on TR_ROOM_ID = RM_ID
                        left join FLXY_TRANSPORT_TYPES on TR_TRANSPORT_TYPE_ID = TT_ID';

        $columns = 'TR_ID,TR_RESERVATION_ID,TR_ROOM_ID,TR_CUSTOMER_ID,TR_GUEST_NAME,TR_TRAVEL_TYPE,TR_TRANSPORT_TYPE_ID,TR_TRAVEL_PURPOSE,TR_PICKUP_DATE,TR_PICKUP_TIME,TR_DROPOFF_DATE,TR_DROPOFF_TIME,TR_STATUS,TR_PAYMENT_STATUS,TR_CREATED_AT,RM_NO,TT_LABEL';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = $this->request->user ?? session('user');

        if (!$this->validate($this->TransportRequestRepository->validationRules()))
            return $this->respond(responseJson("403", true, $this->validator->getErrors()));

        $data = json_decode(json_encode($this->request->getVar()), true);

        // $already_exist = $this->TransportRequestRepository->checkExistingRequest($user, $data);
        // if (!empty($already_exist)) {
        //     $msg = isWeb() ? 'There is an incomplete ride request for this customer.' : 'You have already requested a ride.';
        //     return $this->respond(responseJson(200, false, ['msg' => $msg]));
        // }

        $result = $this->TransportRequestRepository->createOrUpdateRequest($user, $data);
        if (!isWeb() && empty($data['id']) && $result['SUCCESS'] == 200 && $data['TR_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = $result['RESPONSE']['OUTPUT'];
            $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        }

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $transport_request = $this->TransportRequest->where('TR_ID', $id)->first();

        if ($transport_request)
            return $this->respond($transport_request);

        return $this->respond(responseJson(404, true, ['msg' => "Transport request not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->TransportRequest->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Transport request deleted successfully'], $return)
            : responseJson(500, true, ['msg' => "record not deleted"]);

        return $this->respond($result);
    }

    // API
    public function lookupApi()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

        $where_condition = "RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In'";
        $data['RESERVATIONS'] = $this->ReservationRepository->reservationsOfCustomer($customer_id, $where_condition);

        $data['PICKUP_POINTS'] = $this->PickupPoint->select('PP_ID as id, PP_POINT as label')->orderBy('PP_SEQUENCE')->findAll();
        $data['DROPOFF_POINTS'] = $this->DropoffPoint->select('DP_ID as id, DP_POINT as label')->orderBy('DP_SEQUENCE')->findAll();
        $data['FLIGHT_CARRIERS'] = $this->FlightCarrier->select('FC_ID, FC_FLIGHT_CARRIER, FC_FLIGHT_CODE')->orderBy('FC_SEQUENCE')->findAll();
        $data['TRANSPORT_TYPES'] = $this->TransportType->select('TT_ID as id, TT_LABEL as label')->orderBy('TT_DISPLAY_SEQUENCE')->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'lookup API'], $data));
    }

    // public function createRequest()
    // {
    //     $user_id = $this->request->user['USR_ID'];
    //     $customer_id = $this->request->user['USR_CUST_ID'];

    //     $validate = $this->validate([
    //         'TR_RESERVATION_ID' => ['label' => 'reservation', 'rules' => 'required', 'errors' => ['required' => 'Please select a reservation.']],
    //         'TR_ROOM_ID' => ['label' => 'room', 'rules' => 'required'],
    //         'TR_GUEST_NAME' => ['label' => 'guest name', 'rules' => 'required'],
    //         'TR_TRAVEL_TYPE' => ['label' => 'travel type', 'rules' => 'required'],
    //         'TR_TRAVEL_PURPOSE' => ['label' => 'travel purpose', 'rules' => 'required', 'errors' => ['required' => 'Please select a travel purpose.']],
    //         'TR_TRANSPORT_TYPE_ID' => ['label' => 'transport type', 'rules' => 'required', 'errors' => ['required' => 'Please select a transport type.']],
    //         'TR_ADULTS' => ['label' => 'adult', 'rules' => 'required|greater_than[0]'],
    //         'TR_TOTAL_PASSENGERS' => ['label' => 'total passenger', 'rules' => 'required|greater_than[0]'],
    //         'TR_PICKUP_DATE' => ['label' => 'pickup date', 'rules' => 'required'],
    //         'TR_PICKUP_TIME' => ['label' => 'pickup time', 'rules' => 'required'],
    //         'TR_PICKUP_POINT_ID' => ['label' => 'pickup point', 'rules' => 'required', 'errors' => ['required' => 'Please select a pickup point.']],
    //         'TR_DROPOFF_DATE' => ['label' => 'dropoff date', 'rules' => 'required'],
    //         'TR_DROPOFF_TIME' => ['label' => 'dropoff time', 'rules' => 'required'],
    //         'TR_DROPOFF_POINT_ID' => ['label' => 'dropoff point', 'rules' => 'required', 'errors' => ['required' => 'Please select a dropoff point.']],
    //         'TR_FLIGHT_CARRIER_ID' => ['label' => 'flight carrier', 'rules' => 'required', 'errors' => ['required' => 'Please select a flight carrier.']],
    //         'TR_PAYMENT_METHOD' => ['label' => 'payment method', 'rules' => 'required', 'errors' => ['required' => 'Please select a payment method.']],
    //     ]);

    //     if (!$validate) {
    //         $validate = $this->validator->getErrors();
    //         $result = responseJson(403, true, $validate);

    //         return $this->respond($result);
    //     }

    //     $data = json_decode(json_encode($this->request->getVar()), true);
    //     $data['TR_CUSTOMER_ID'] = $customer_id;
    //     $data['TR_CREATED_BY'] = $data['TR_UPDATED_BY'] = $user_id;

    //     $this->TransportRequest->insert($data);
    //     return $this->respond(responseJson(200, false, ['msg' => 'Transport request submitted successfully.']));
    // }

    public function allRequests()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $all_requests = $this->TransportRequest
            ->select('FLXY_TRANSPORT_REQUESTS.*, pp.PP_POINT, dp.DP_POINT, RM_NO, TT_LABEL, TT_MAX_PRICE, FC_FLIGHT_CARRIER')
            ->join('FLXY_PICKUP_POINTS as pp', 'FLXY_TRANSPORT_REQUESTS.TR_PICKUP_POINT_ID = pp.PP_ID', 'left')
            ->join('FLXY_DROPOFF_POINTS as dp', 'FLXY_TRANSPORT_REQUESTS.TR_DROPOFF_POINT_ID = dp.DP_ID', 'left')
            ->join('FLXY_TRANSPORT_TYPES', 'TR_TRANSPORT_TYPE_ID = TT_ID', 'left')
            ->join('FLXY_FLIGHT_CARRIERS', 'TR_FLIGHT_CARRIER_ID = FC_ID', 'left')
            ->join('FLXY_ROOM', 'TR_ROOM_ID = RM_ID', 'left')
            ->where('TR_CUSTOMER_ID', $customer_id)
            ->orderBy('TR_ID', 'desc')
            ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'All requests'], $all_requests));
    }
}
