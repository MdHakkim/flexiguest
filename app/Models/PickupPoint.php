<?php

namespace App\Models;

use CodeIgniter\Model;

class PickupPoint extends Model
{
    protected $table = 'FLXY_PICKUP_POINTS';
    protected $primaryKey = 'PP_ID';
    protected $allowedFields = [
        'PP_POINT',
        'PP_SEQUENCE',
        'PP_CREATED_BY',
        'PP_UPDATED_BY'
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PP_CREATED_AT';
    protected $updatedField  = 'PP_UPDATED_AT';
}
