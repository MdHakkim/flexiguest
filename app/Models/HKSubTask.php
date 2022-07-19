<?php

namespace App\Models;

use CodeIgniter\Model;

class HKSubTask extends Model
{
    protected $table      = 'FLXY_HK_SUBTASKS';
    protected $primaryKey = 'HKST_ID';
    protected $allowedFields = [
        'HKST_TASK_ID',
        'HKST_DESCRIPTION',
        'HKST_DISPLAY_ORDER',
        'HKST_CREATED_BY',
        'HKST_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKST_CREATED_AT';
    protected $updatedField  = 'HKST_UPDATED_AT';
}
