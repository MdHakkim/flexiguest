<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTaskDetailNote extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASK_DETAIL_NOTES';
    protected $primaryKey = 'ATDN_ID';
    protected $allowedFields = [
        'ATDN_ASSIGNED_TASK_DETAIL_ID',
        'ATDN_USER_ID',
        'ATDN_NOTE',
        'ATDN_CREATED_BY',
        'ATDN_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'ATDN_CREATED_AT';
    protected $updatedField  = 'ATDN_UPDATED_AT';
}
