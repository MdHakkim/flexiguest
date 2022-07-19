<?php

namespace App\Models;

use CodeIgniter\Model;

class HKTask extends Model
{
    protected $table      = 'FLXY_HK_TASKS';
    protected $primaryKey = 'HKT_ID';
    protected $allowedFields = [
        'HKT_CODE',
        'HKT_DESCRIPTION',
        'HKT_CREATED_BY',
        'HKT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKT_CREATED_AT';
    protected $updatedField  = 'HKT_UPDATED_AT';
}
