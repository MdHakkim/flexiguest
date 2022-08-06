<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceRequestComment extends Model
{
    protected $table      = 'FLXY_MAINTENANCE_REQUEST_COMMENTS';
    protected $primaryKey = 'MRC_ID';
    protected $allowedFields = [
        'MRC_MAINTENANCE_REQUEST_ID',
        'MRC_USER_ID',
        'MRC_COMMENT',
        'MRC_CREATED_BY',
        'MRC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'MRC_CREATED_AT';
    protected $updatedField  = 'MRC_UPDATED_AT';
}
