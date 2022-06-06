<?php

namespace App\Models;

use CodeIgniter\Model;

class ShuttleRoute extends Model
{
    protected $table      = 'FLXY_SHUTTLE_ROUTE';
    protected $primaryKey = 'FSR_ID';
    protected $allowedFields = [
        'FSR_SHUTTLE_ID',
        'FSR_STAGE_ID',
        'FSR_DURATION_MINS',
        'FSR_ORDER_NO',
        'FSR_CREATE_UID',
        'FSR_UPDATE_UID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'FSR_CREATED_AT';
    protected $updatedField  = 'FSR_UPDATED_AT';
}