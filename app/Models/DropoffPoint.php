<?php

namespace App\Models;

use CodeIgniter\Model;

class DropoffPoint extends Model
{
    protected $table = 'FLXY_DROPOFF_POINTS';
    protected $primaryKey = 'DP_ID';
    protected $allowedFields = [
        'DP_POINT',
        'DP_SEQUENCE',
        'DP_CREATED_BY',
        'DP_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'DP_CREATED_AT';
    protected $updatedField  = 'DP_UPDATED_AT';
}
