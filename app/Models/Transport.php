<?php

namespace App\Models;

use CodeIgniter\Model;

class Transport extends Model
{
    protected $table      = 'FLXY_TRANSPORT';
    protected $primaryKey = 'TR_ID';
    protected $allowedFields = [
        'TR_TRANSPORT_CODE',
        'TR_LABEL',
        'TR_DESCRIPTION',
        'TR_PHONE',
        'TR_DISTANCE',
        'TR_DISTANCE_UNIT',
        'TR_MIN_PRICE',
        'TR_MAX_PRICE',
        'TR_COMMENTS',
        'TR_DISPLAY_SEQUENCE',
        'TR_CREATED_BY',
        'TR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'TR_CREATED_AT';
    protected $updatedField  = 'TR_UPDATED_AT';
}
