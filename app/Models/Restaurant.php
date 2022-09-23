<?php

namespace App\Models;

use CodeIgniter\Model;

class Restaurant extends Model
{
    protected $table      = 'FLXY_RESTAURANTS';
    protected $primaryKey = 'RE_ID';
    protected $allowedFields = [
        'RE_RESTAURANT',
        'RE_CREATED_BY',
        'RE_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RE_CREATED_AT';
    protected $updatedField  = 'RE_UPDATED_AT';
}
