<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomType extends Model
{
    protected $table      = 'FLXY_ROOM_TYPE';
    protected $primaryKey = 'RM_TY_ID';
    protected $allowedFields = [
        'RM_TY_ROOM_CLASS',
        'RM_TY_CODE',
        'RM_TY_DESC',
        'RM_CL_ID',
        'RM_TY_FEATURE',
        'RM_TY_TOTAL_ROOM',
        'RM_TY_DISP_SEQ',
        'RM_TY_PUBLIC_RATE_CODE',
        'RM_TY_DEFUL_OCCUPANCY',
        'RM_TY_MAX_OCCUPANCY',
        'RM_TY_MAX_ADULTS',
        'RM_TY_MAX_CHILDREN',
        'RM_TY_PSEUDO_RM',
        'RM_TY_HOUSEKEEPING',
        'RM_TY_MIN_OCCUPANCY',
        'RM_TY_SEND_T_INTERF',
        'RM_TY_PUBLIC_RATE_AMT',
        'RM_TY_ACTIVE_DT',
        'RM_TY_CREATE_UID',
        'RM_TY_UPDATED_UID',
        'RM_TY_COMP_CODE',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RM_TY_CREATE_DT';
    protected $updatedField  = 'RM_TY_UPDATED_DT';
}
