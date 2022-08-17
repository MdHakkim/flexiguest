<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'TR_RESERVATION_ID' => ['label' => 'reservation', 'rules' => 'required'],
            'TR_CUSTOMER_ID' => ['label' => 'customer', 'rules' => 'required'],
            'TR_GUEST_NAME' => ['label' => 'guest name', 'rules' => 'required'],
            'TR_TRAVEL_TYPE' => ['label' => 'travel type', 'rules' => 'required'],
            'TR_TRANSPORT_TYPE_ID' => ['label' => 'transport type', 'rules' => 'required'],
            'TR_TRAVEL_PURPOSE' => ['label' => 'travel purpose', 'rules' => 'required'],
            'TR_ADULTS' => ['label' => 'no of adults', 'rules' => 'required'],
            'TR_TOTAL_PASSENGERS' => ['label' => 'total passengers', 'rules' => 'required'],
            'TR_PICKUP_DATE' => ['label' => 'pickup date', 'rules' => 'required'],
            'TR_PICKUP_TIME' => ['label' => 'pickup time', 'rules' => 'required'],
            'TR_PICKUP_POINT_ID' => ['label' => 'pickup point', 'rules' => 'required'],
            'TR_DROPOFF_DATE' => ['label' => 'dropoff date', 'rules' => 'required'],
            'TR_DROPOFF_TIME' => ['label' => 'dropoff time', 'rules' => 'required'],
            'TR_DROPOFF_POINT_ID' => ['label' => 'dropoff point', 'rules' => 'required'],
            'TR_FLIGHT_CARRIER_ID' => ['label' => 'flight carrier', 'rules' => 'required'],
            'TR_STATUS' => ['label' => 'status', 'rules' => 'required'],
            'TR_PAYMENT_METHOD' => ['label' => 'payment method', 'rules' => 'required'],
            'TR_PAYMENT_STATUS' => ['label' => 'payment status', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson("403", true, $this->validator->getErrors()));

        $data = $this->request->getVar();

        foreach ($data as $index => $row) {
            if (empty($row))
                unset($data[$index]);
        }

        if (!empty($id)) {
            $data['TR_UPDATED_BY'] = $user_id;
            $response = $this->TransportRequest->update($id, $data);
            $msg = "Transport request updated successfully";
        } else {
            $data['TR_UPDATED_BY'] = $data['TR_CREATED_BY'] = $user_id;
            $response = $this->TransportRequest->insert($data);
            $msg = "Transport request submitted successfully";
        }

        $result = $response
            ? responseJson(200, false, ['msg' => $msg], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successful']);

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
}
