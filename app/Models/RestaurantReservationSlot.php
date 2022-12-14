<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantReservationSlot extends Model
{
    protected $table      = 'FLXY_RESTAURANT_RESERVATION_SLOTS';
    protected $primaryKey = 'RRS_ID';
    protected $allowedFields = [
        'RRS_FROM_TIME',
        'RRS_TO_TIME',
        'RRS_DISPLAY_SEQUENCE',
        'RRS_CREATED_BY',
        'RRS_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RRS_CREATED_AT';
    protected $updatedField  = 'RRS_UPDATED_AT';
}
