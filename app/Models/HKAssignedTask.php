<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTask extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASKS';
    protected $primaryKey = 'HKAT_ID';
    protected $allowedFields = [
        'HKAT_TASK_SHEET_ID',
        'HKAT_TASK_ID', // task overview table ID
        'HKAT_ATTENDANT_ID',
        'HKAT_CREDITS',
        'HKAT_INSTRUCTIONS',
        'HKAT_PRIORITY',
        'HKAT_CREATED_BY',
        'HKAT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKAT_CREATED_AT';
    protected $updatedField  = 'HKAT_UPDATED_AT';
}
