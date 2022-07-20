<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTaskNote extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASK_NOTES';
    protected $primaryKey = 'ATN_ID';
    protected $allowedFields = [
        'ATN_ASSIGNED_TASK_ID',
        'ATN_USER_ID',
        'ATN_NOTE',
        'ATN_CREATED_BY',
        'ATN_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'ATN_CREATED_AT';
    protected $updatedField  = 'ATN_UPDATED_AT';
}
