<?php

namespace App\Models;

use CodeIgniter\Model;

class FlightCarrier extends Model
{
    protected $table = 'FLXY_FLIGHT_CARRIERS';
    protected $primaryKey = 'FC_ID';
    protected $allowedFields = [
        'FC_FLIGHT_CARRIER',
        'FC_FLIGHT_CODE',
        'FC_SEQUENCE',
        'FC_CREATED_BY',
        'FC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'FC_CREATED_AT';
    protected $updatedField  = 'FC_UPDATED_AT';
}
