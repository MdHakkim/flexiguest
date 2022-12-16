<?php

namespace App\Controllers;

use App\Controllers\Repositories\RoomTypeRepository;
use CodeIgniter\API\ResponseTrait;

class RoomTypeController extends BaseController
{

    use ResponseTrait;

    private $RoomTypeRepository;

    public function __construct()
    {
        $this->RoomTypeRepository = new RoomTypeRepository();
    }

    public function roomTypes()
    {
        $room_types = $this->RoomTypeRepository->roomTypes();

        return $this->respond(responseJson(200, false, ['msg' => 'Room Types'], $room_types));
    }
}