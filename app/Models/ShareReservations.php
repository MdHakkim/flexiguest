<?php

namespace App\Models;

use CodeIgniter\Model;

class ShareReservations extends Model
{
    protected $table = 'FLXY_SHARE_RESERVATIONS';
    protected $primaryKey = 'FSR_ID';
    protected $allowedFields = [
        'FSR_RESERVATION_ID',
        'FSR_OTHER_RESERVATION_ID',
        'FSR_CREATED_BY',
        'FSR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'FSR_CREATED_AT';
    protected $updatedField  = 'FSR_UPDATED_AT';
}
