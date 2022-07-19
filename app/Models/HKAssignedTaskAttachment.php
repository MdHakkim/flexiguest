<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTaskAttachment extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASK_ATTACHMENTS';
    protected $primaryKey = 'HKATA_ID';
    protected $allowedFields = [
        'HKATA_ASSIGNED_TASK_ID',
        'HKATA_NAME',
        'HKATA_TYPE',
        'HKATA_URL',
        'HKATA_CREATED_BY',
        'HKATA_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKATA_CREATED_AT';
    protected $updatedField  = 'HKATA_UPDATED_AT';
}
