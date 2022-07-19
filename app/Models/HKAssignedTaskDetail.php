<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTaskDetail extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASK_DETAILS';
    protected $primaryKey = 'HKATD_ID';
    protected $allowedFields = [
        'HKATD_ASSIGNED_TASK_ID',
        'HKATD_SUBTASK_ID',
        'HKATD_COMPLETION_TIME',
        'HKATD_STATUS',
        'HKATD_INSPECTED_BY',
        'HKATD_INSPECTED_STATUS',
        'HKATD_INSPECTED_DATETIME',
        'HKATD_CREATED_BY',
        'HKATD_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKATD_CREATED_AT';
    protected $updatedField  = 'HKATD_UPDATED_AT';
}
