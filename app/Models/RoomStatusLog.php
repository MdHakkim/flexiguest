<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomStatusLog extends Model
{
    protected $table      = 'FLXY_ROOM_STATUS_LOG';
    protected $primaryKey = 'RM_STAT_LOG_ID';
    protected $allowedFields = [
        'RM_STAT_ROOM_ID',
        'RM_STAT_ROOM_STATUS',
        'RM_STAT_UPDATED_BY',
        'RM_STAT_UPDATED',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
