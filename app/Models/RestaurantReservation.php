<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantReservation extends Model
{
    protected $table      = 'FLXY_RESTAURANT_RESERVATIONS';
    protected $primaryKey = 'RR_ID';
    protected $allowedFields = [
        'RR_RESTAURANT_ID',
        'RR_ORDER_ID',
        'RR_SLOT_ID',
        'RR_NO_OF_GUESTS',
        'RR_CREATED_BY',
        'RR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RR_CREATED_AT';
    protected $updatedField  = 'RR_UPDATED_AT';
}
