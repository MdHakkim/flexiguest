<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationRoomAsset extends Model
{
    protected $table      = 'FLXY_RESERVATION_ROOM_ASSETS';
    protected $primaryKey = 'RRA_ID';
    protected $allowedFields = [
        'RRA_RESERVATION_ID',
        'RRA_ROOM_ID',
        'RRA_ROOM_ASSET_ID',
        'RRA_ASSET_ID',
        'RRA_STATUS',
        'RRA_REMARKS',
        'RRA_TRACKING_REMARKS',
        'RRA_CREATED_BY',
        'RRA_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RRA_CREATED_AT';
    protected $updatedField  = 'RRA_UPDATED_AT';
}
