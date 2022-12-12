<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomStatusView extends Model
{
    protected $table      = 'FLXY_ROOM_STATUS_VIEW';
    protected $primaryKey = 'RM_ID';
    protected $allowedFields = [
        'RM_TYPE_REF_ID',
        'RM_NO',
        'RM_CLASS',
        'RM_DESC',
        'RM_TYPE',
        'RM_FEATURE',
        'RM_PUBLIC_RATE_CODE',
        'RM_PUBLIC_RATE_AMOUNT',
        'RM_MAX_OCCUPANCY',
        'RM_DISP_SEQ',
        'RM_FLOOR_PREFERN',
        'RM_SMOKING_PREFERN',
        'RM_PHONE_NO',
        'RM_SQUARE_UNITS',
        'RM_MEASUREMENT',
        'RM_HOUSKP_DY_SECTION',
        'RM_HOUSKP_EV_SECTION',
        'RM_GUEST_SERVICE_STATUS',
        'RM_STAYOVER_CR',
        'RM_DEPARTURE_CR',
        'RM_CREATED_UID',
        'RM_CREATED_DT',
        'RM_UPDATED_UID',
        'RM_UPDATED_DT',
        'RM_COMP_CODE',
        'ROOM_STATUS_ID',
        'ROOM_STATUS_CODE'
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
