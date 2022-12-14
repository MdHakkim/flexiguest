<?php

namespace App\Controllers;

use App\Controllers\Repositories\RestaurantReservationRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationController extends BaseController
{

    use ResponseTrait;

    private $RestaurantReservationRepository;

    public function __construct()
    {
        $this->RestaurantReservationRepository = new RestaurantReservationRepository();
    }

    public function reservation()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/restaurant/restaurant_reservation', $data);
    }

    public function allReservations()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANT_RESERVATIONS left join FLXY_RESTAURANTS on RR_RESTAURANT_ID = RE_ID left join FLXY_RESTAURANT_ORDERS on RR_ORDER_ID = RO_ID left join FLXY_RESTAURANT_RESERVATION_SLOTS on RR_SLOT_ID = RRS_ID';
        $columns = 'RR_ID,RR_RESTAURANT_ID,RR_ORDER_ID,RR_SLOT_ID,RR_NO_OF_GUESTS,RR_CREATED_AT,RE_RESTAURANT,RO_RESERVATION_ID,RRS_FROM_TIME,RRS_TO_TIME';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }
}