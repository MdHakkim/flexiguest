<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Room;
use App\Models\RoomStatusLog;
use CodeIgniter\API\ResponseTrait;

class RoomRepository extends BaseController
{
    use ResponseTrait;

    private $Room;
    private $RoomStatusLog;

    public function __construct()
    {
        $this->Room = new Room();
        $this->RoomStatusLog = new RoomStatusLog();
    }

    public function allRooms()
    {
        return $this->Room->findAll();
    }

    public function updateRoomStatus($user, $room_id, $status_id)
    {
        return $this->RoomStatusLog->save([
            'RM_STAT_ROOM_ID' => $room_id,
            'RM_STAT_ROOM_STATUS' => $status_id,
            'RM_STAT_UPDATED_BY' => $user['USR_ID'],
            'RM_STAT_UPDATED' => date('Y-m-d H:i:s')
        ]);
    }
}
