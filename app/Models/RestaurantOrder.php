<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantOrder extends Model
{
    protected $table      = 'FLXY_RESTAURANT_ORDERS';
    protected $primaryKey = 'RO_ID';
    protected $allowedFields = [
        'RO_RESERVATION_ID',
        'RO_ROOM_ID',
        'RO_CUSTOMER_ID',
        'RO_ATTENDANT_ID',
        'RO_TOTAL_PAYABLE',
        'RO_PAYMENT_METHOD', // Pay at Reception, Samsung Pay, Credit/Debit card
        'RO_PAYMENT_STATUS', // UnPaid, Paid
        'RO_DELIVERY_STATUS', // New, Processing, Delivered, Cancelled
        'RO_CREATED_BY',
        'RO_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RO_CREATED_AT';
    protected $updatedField  = 'RO_UPDATED_AT';
}
