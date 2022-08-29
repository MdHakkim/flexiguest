<?php

namespace App\Models;

use CodeIgniter\Model;

class EValet extends Model
{
    protected $table      = 'FLXY_EVALET';
    protected $primaryKey = 'EV_ID';
    protected $allowedFields = [
        'EV_DRIVER_ID',
        'EV_RESERVATION_ID',
        'EV_ROOM_ID',
        'EV_GUEST_TYPE',
        'EV_GUEST_NAME',
        'EV_CONTACT_NUMBER',
        'EV_EMAIL',
        'EV_CAR_PLATE_NUMBER',
        'EV_CAR_MAKE',
        'EV_CAR_MODEL',
        'EV_KEYS_COLLECTED',
        'EV_STATUS',
        'EV_PARKING_DETAILS',
        'EV_ASSIGNED_AT',
        'EV_CREATED_BY',
        'EV_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'EV_CREATED_AT';
    protected $updatedField  = 'EV_UPDATED_AT';
}
