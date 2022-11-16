<?php

namespace App\Models;

use CodeIgniter\Model;

class Shuttle extends Model
{
    protected $table      = 'FLXY_SHUTTLE';
    protected $primaryKey = 'SHUTL_ID';
    protected $allowedFields = [
        'SHUTL_NAME',
        'SHUTL_DESCRIPTION',
        'SHUTL_START_AT',
        'SHUTL_END_AT',
        'SHUTL_FROM',
        'SHUTL_TO',
        'SHUTL_ROUTE',
        'SHUTL_NEXT',
        'SHUTL_ROUTE_IMG',
        'SHUTL_CREATE_UID',
        'SHUTL_UPDATE_UID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'SHUTL_CREATE_DT';
    protected $updatedField  = 'SHUTL_UPDATE_DT';
}
