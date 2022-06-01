<?php

namespace App\Models;

use CodeIgniter\Model;

class ConciergeRequest extends Model
{
    protected $table      = 'FLXY_CONCIERGE_REQUESTS';
    protected $primaryKey = 'CR_ID';
    protected $allowedFields = [
        'CR_OFFER_ID',
        'CR_QUANTITY',
        'CR_REMARKS',
        'CR_GUEST_NAME',
        'CR_GUEST_EMAIL',
        'CR_GUEST_PHONE',
        'CR_GUEST_ROOM_ID',
        'CR_TOTAL_AMOUNT',
        'CR_TAX_AMOUNT',
        'CR_NET_AMOUNT',
        'CR_STATUS',
        'CR_CREATED_BY',
        'CR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CR_CREATED_AT';
    protected $updatedField  = 'CR_UPDATED_AT';
}
