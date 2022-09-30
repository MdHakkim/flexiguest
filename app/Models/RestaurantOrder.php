<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantOrder extends Model
{
    protected $table      = 'FLXY_RESTAURANT_ORDERS';
    protected $primaryKey = 'RO_ID';
    protected $allowedFields = [
        'RO_CUSTOMER_ID',
        'RO_TOTAL_PAYABLE',
        'RO_PAYMENT_METHOD',
        'RO_PAYMENT_STATUS',
        'RO_DELIVERY_STATUS',
        'RO_CREATED_BY',
        'RO_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RO_CREATED_AT';
    protected $updatedField  = 'RO_UPDATED_AT';
}
