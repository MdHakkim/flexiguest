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
        $validate = $this->validate([
            'estimatedTimeOfArrival' => 'required',
            'signature' =>  [
                'uploaded[signature]',

                'max_size[signature,50000]',
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }
    }
}
