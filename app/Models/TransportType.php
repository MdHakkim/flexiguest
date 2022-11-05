<?php

namespace App\Models;

use CodeIgniter\Model;

class TransportType extends Model
{
    protected $table      = 'FLXY_TRANSPORT_TYPES';
    protected $primaryKey = 'TT_ID';
    protected $allowedFields = [
        'TT_TRANSPORT_CODE',
        'TT_LABEL',
        'TT_DESCRIPTION',
        'TT_PRICE',
        'TT_DISPLAY_SEQUENCE',
        'TT_CREATED_BY',
        'TT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'TT_CREATED_AT';
    protected $updatedField  = 'TT_UPDATED_AT';
}
