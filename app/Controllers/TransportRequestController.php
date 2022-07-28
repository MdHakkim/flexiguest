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

        return view('frontend/transport/transport_request', $data);
    }

    public function allTransportRequests()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_TRANSPORT_REQUESTS 
                        left join FLXY_ROOM on TR_ROOM_ID = RM_ID
                        left join FLXY_TRANSPORT_TYPES on TR_TRANSPORT_TYPE_ID = TT_ID';

        $columns = 'TR_ID,TR_RESERVATION_ID,TR_ROOM_ID,TR_CUSTOMER_ID,TR_GUEST_NAME,TR_TRANSPORT_TYPE_ID,TR_TRAVEL_DATE,TR_TRAVEL_TIME,TR_STATUS,TR_PAYMENT_STATUS,TR_CREATED_AT,RM_NO,TT_LABEL';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }
    
}
