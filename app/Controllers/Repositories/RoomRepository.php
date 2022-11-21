<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Room;
use CodeIgniter\API\ResponseTrait;

class RoomRepository extends BaseController
{
    use ResponseTrait;

    private $Room;

    public function __construct()
    {
        $this->Room = new Room();
    }

    public function allRooms()
    {
        return $this->Room->findAll();
    }
}