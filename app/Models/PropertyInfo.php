<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyInfo extends Model
{
    protected $table      = 'FLXY_PROPERTY_INFO';
    protected $primaryKey = 'PI_ID';
    protected $allowedFields = [
        'PI_NAME',
        'PI_TYPE',
        'PI_URL',
        'PI_CREATED_BY',
        'PI_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PI_CREATED_AT';
    protected $updatedField  = 'PI_UPDATED_AT';
}
