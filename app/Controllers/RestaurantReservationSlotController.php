<?php

namespace App\Controllers;

use App\Controllers\Repositories\RestaurantReservationSlotRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationSlotController extends BaseController
{

    use ResponseTrait;

    private $RestaurantReservationSlotRepository;

    public function __construct()
    {
        $this->RestaurantReservationSlotRepository = new RestaurantReservationSlotRepository();
    }

    public function reservationSlot()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/restaurant/reservation_slot', $data);
    }

    public function allReservationSlots()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANT_RESERVATION_SLOTS';
        $columns = 'RRS_ID,RRS_FROM_TIME,RRS_TO_TIME,RRS_DISPLAY_SEQUENCE,RRS_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = session('user');

        $data = $this->request->getPost();

        if (!$this->validate($this->RestaurantReservationSlotRepository->validationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->RestaurantReservationSlotRepository->storeReservationSlot($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');
        $reservation_slot = $this->RestaurantReservationSlotRepository->reservationSlotById($id);

        if ($reservation_slot)
            return $this->respond($reservation_slot);

        return $this->respond(responseJson(404, true, ['msg' => "Restaurant table not found"]));
    }

    public function delete()
    {
        $reservation_slot_id = $this->request->getPost('id');

        $result = $this->RestaurantReservationSlotRepository->deleteReservationSlot($reservation_slot_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Reservation Slot deleted successfully."])
            : responseJson(500, true, ['msg' => "Reservation Slot not deleted"]);

        return $this->respond($result);
    }
}