<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\ServerSideDataTable;
use App\Models\DropoffPoint;
use App\Models\FlightCarrier;
use App\Models\PickupPoint;
use App\Models\Reservation;
use App\Models\TransportRequest;
use App\Models\TransportType;

class TransportRequestRepository extends BaseController
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

    public function validationRules($data)
    {
        $rules = [
            'TR_RESERVATION_ID' => ['label' => 'reservation', 'rules' => 'required', 'errors' => ['required' => 'Please select a reservation.']],
            'TR_ROOM_ID' => ['label' => 'room', 'rules' => 'required'],
            'TR_GUEST_NAME' => ['label' => 'guest name', 'rules' => 'required'],
            'TR_TRAVEL_TYPE' => ['label' => 'travel type', 'rules' => 'required'],
            'TR_TRAVEL_PURPOSE' => ['label' => 'travel purpose', 'rules' => 'required', 'errors' => ['required' => 'Please select a travel purpose.']],
            'TR_TRANSPORT_TYPE_ID' => ['label' => 'transport type', 'rules' => 'required', 'errors' => ['required' => 'Please select a transport type.']],
            'TR_ADULTS' => ['label' => 'adult', 'rules' => 'required|greater_than[0]'],
            'TR_TOTAL_PASSENGERS' => ['label' => 'total passenger', 'rules' => 'required|greater_than[0]'],
            'TR_PICKUP_TIME' => ['label' => 'pickup time', 'rules' => 'required'],
            'TR_PICKUP_DATE' => [
                'label' => 'pickup date',
                'rules' => 'required|afterNow[TR_PICKUP_DATE,TR_PICKUP_TIME]',
                'errors' => [
                    'afterNow' => 'Pickup date & time should be after current date & time.'
                ]
            ],
            'TR_PICKUP_POINT_ID' => ['label' => 'pickup point', 'rules' => 'required', 'errors' => ['required' => 'Please select a pickup point.']],
            'TR_FLIGHT_CARRIER_ID' => ['label' => 'flight carrier', 'rules' => 'required', 'errors' => ['required' => 'Please select a flight carrier.']],
            'TR_PAYMENT_METHOD' => ['label' => 'payment method', 'rules' => 'required', 'errors' => ['required' => 'Please select a payment method.']],
        ];

        if (!empty($data['TR_DROPOFF_POINT_ID']) || !empty($data['TR_DROPOFF_DATE']) || !empty($data['TR_DROPOFF_TIME'])) {
            $rules = array_merge($rules, [
                'TR_DROPOFF_TIME' => ['label' => 'dropoff time', 'rules' => 'required'],
                'TR_DROPOFF_DATE' => [
                    'label' => 'dropoff date',
                    'rules' => 'required|afterDateTime[TR_PICKUP_DATE,TR_PICKUP_TIME,TR_DROPOFF_DATE,TR_DROPOFF_TIME]',
                    'errors' => [
                        'afterDateTime' => 'Dropoff date & time should be after pickup date & time.'
                    ]
                ],
                'TR_DROPOFF_POINT_ID' => ['label' => 'dropoff point', 'rules' => 'required', 'errors' => ['required' => 'Please select a dropoff point.']],
            ]);
        }

        if (isWeb()) {
            $rules = array_merge($rules, [
                'TR_CUSTOMER_ID' => ['label' => 'customer', 'rules' => 'required'],
                'TR_STATUS' => ['label' => 'status', 'rules' => 'required'],
                'TR_PAYMENT_STATUS' => ['label' => 'payment status', 'rules' => 'required'],
            ]);
        }

        return $rules;
    }

    public function transportTypeById($id)
    {
        return $this->TransportType->find($id);
    }

    public function createOrUpdateRequest($user, $data)
    {
        $user_id = $user['USR_ID'];
        $data['TR_CUSTOMER_ID'] = $data['TR_CUSTOMER_ID'] ?? $user['USR_CUST_ID'];

        $transport_type = $this->transportTypeById($data['TR_TRANSPORT_TYPE_ID'] ?? null);
        if (empty($transport_type))
            return responseJson(404, true, ['msg' => 'Invalid transport type'], $data);

        $data['TR_TOTAL_AMOUNT'] = $transport_type['TT_PRICE'];

        if (!empty($image = $data['TR_GUEST_IMAGE'])) {
            $image_name = $image->getName();
            $directory = "assets/Uploads/transport_requests/guest_images/";
            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => "Guest image not uploaded"]);

            $data['TR_GUEST_IMAGE'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        foreach ($data as $index => $row) {
            if (empty($row))
                unset($data[$index]);
        }

        if (empty($data['id'])) {
            $data['TR_UPDATED_BY'] = $data['TR_CREATED_BY'] = $user_id;
            $request_id = $this->TransportRequest->insert($data);
            $msg = "Transport request submitted successfully";
        } else {
            $data['TR_UPDATED_BY'] = $user_id;
            $this->TransportRequest->update($data['id'], $data);
            $msg = "Transport request updated successfully";
        }

        if (!isWeb() && empty($data['id']) && $data['TR_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = [
                'amount' => $data['TR_TOTAL_AMOUNT'],
                'model' => 'FLXY_TRANSPORT_REQUESTS',
                'model_id' => $request_id,
                'reservation_id' => $data['TR_RESERVATION_ID'],
            ];
        }

        return responseJson(200, false, ['msg' => $msg], $data);
    }

    public function checkExistingRequest($user, $data)
    {
        $customer_id = $data['TR_CUSTOMER_ID'] ?? $user['USR_CUST_ID'];
        return $this->TransportRequest->where('TR_CUSTOMER_ID', $customer_id)->where("TR_STATUS = 'New' or TR_STATUS = 'In Progress'")->findAll();
    }

    public function updateTransportRequestById($data)
    {
        return $this->TransportRequest->save($data);
    }

    public function getTransportRequest($where_condition)
    {
        return $this->TransportRequest->where($where_condition)->first();
    }

    public function getTransportRequests($where_condition)
    {
        return $this->TransportRequest->where($where_condition)->findAll();
    }
}
