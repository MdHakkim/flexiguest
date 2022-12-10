<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RestaurantReservationSlot;
use CodeIgniter\API\ResponseTrait;

class RestaurantReservationSlotRepository extends BaseController
{
    use ResponseTrait;

    private $RestaurantReservationSlot;

    public function __construct()
    {
        $this->RestaurantReservationSlot = new RestaurantReservationSlot();
    }

    public function validationRules($data)
    {
        $rules = [
            'RRS_FROM_TIME' => ['label' => 'From time', 'rules' => 'required'],
            'RRS_TO_TIME' => ['label' => 'To time', 'rules' => 'required'],
        ];

        return $rules;
    }

    public function checkSlotConflict($data)
    {
        return $this->RestaurantReservationSlot
            ->where("('{$data['RRS_FROM_TIME']}' between RRS_FROM_TIME and RRS_TO_TIME) OR ('{$data['RRS_TO_TIME']}' between RRS_FROM_TIME and RRS_TO_TIME)")
            ->first();
    }

    public function storeReservationSlot($user, $data)
    {
        $check = $this->checkSlotConflict($data);
        if(!empty($check))
            return responseJson(202, true, ['msg' => "Conflict with a reservation slot from {$check['RRS_FROM_TIME']} to {$check['RRS_TO_TIME']}"]);

        if (empty($data['RRS_ID'])) {
            unset($data['RRS_ID']);
            $data['RRS_CREATED_BY'] = $data['RRS_UPDATED_BY'] = $user['USR_ID'];
        } else
            $data['RRS_UPDATED_BY'] = $user['USR_ID'];

        $response = $this->RestaurantReservationSlot->save($data);

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        return responseJson(200, false, ['msg' => 'Reservation Slot create/updated successfully.']);
    }

    public function reservationSlotById($id)
    {
        return $this->RestaurantReservationSlot->find($id);
    }

    public function deleteReservationSlot($id)
    {
        return $this->RestaurantReservationSlot->delete($id);
    }

    public function reservationSlots()
    {
        return $this->RestaurantReservationSlot->orderBy('RRS_DISPLAY_SEQUENCE', 'asc')->findAll();
    }
}
