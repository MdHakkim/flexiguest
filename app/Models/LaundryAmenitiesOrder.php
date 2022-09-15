<?php

namespace App\Models;

use CodeIgniter\Model;

class LaundryAmenitiesOrder extends Model
{
    protected $table      = 'FLXY_LAUNDRY_AMENITIES_ORDERS';
    protected $primaryKey = 'LAO_ID';
    protected $allowedFields = [
        'LAO_RESERVATION_ID',
        'LAO_CUSTOMER_ID',
        'LAO_ROOM_ID',
        'LAO_TOTAL_PAYABLE',
        'LAO_PAYMENT_METHOD', // Pay at Reception, Samsung Pay, Credit/Debit card
        'LAO_PAYMENT_STATUS', // UnPaid, Paid, Payment Initiated
        'LAO_CREATED_BY',   
        'LAO_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'LAO_CREATED_AT';
    protected $updatedField  = 'LAO_UPDATED_AT';
}
