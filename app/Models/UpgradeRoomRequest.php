<?php

namespace App\Models;

use CodeIgniter\Model;

class UpgradeRoomRequest extends Model
{
    protected $table      = 'FLXY_UPGRADE_ROOM_REQUESTS';
    protected $primaryKey = 'URR_ID';
    protected $allowedFields = [
        'URR_CUSTOMER_ID',
        'URR_RESERVATION_ID',
        'URR_ROOM_TYPE_ID',
        'URR_STATUS',
        'URR_CREATED_BY',
        'URR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'URR_CREATED_AT';
    protected $updatedField  = 'URR_UPDATED_AT';
}
