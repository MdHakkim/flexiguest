<?php

namespace App\Models;

use CodeIgniter\Model;

class Maintenance extends Model
{
    protected $table      = 'FLXY_MAINTENANCE';
    protected $primaryKey = 'MAINT_ID';
    protected $allowedFields = [
        'CUST_NAME',
        'MAINT_ATTENDANT_ID',
        'MAINT_RESV_ID',
        'MAINT_ROOM_NO',
        'MAINT_TYPE',
        'MAINT_CATEGORY',
        'MAINT_SUB_CATEGORY',
        'MAINT_DETAILS',
        'MAINT_PREFERRED_DT',
        'MAINT_PREFERRED_TIME',
        'MAINT_ATTACHMENT',
        'MAINT_ACKNOWEDGE_TIME',
        'MAINT_STATUS', // New, Assigned, In Progress, Completed, Acknowledged, Rejected
        'MAINT_CREATE_UID',
        'MAINT_UPDATE_UID',
        'MAINT_ASSIGNED_AT',
        'MAINT_COMPLETED_AT'
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'MAINT_CREATE_DT';
    protected $updatedField  = 'MAINT_UPDATE_DT';
}
