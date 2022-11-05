<?php

namespace App\Models;

use CodeIgniter\Model;

class LaundryAmenitiesOrderDetail extends Model
{
    protected $table      = 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS';
    protected $primaryKey = 'LAOD_ID';
    protected $allowedFields = [
        'LAOD_ATTENDANT_ID',
        'LAOD_ORDER_ID',
        'LAOD_PRODUCT_ID',
        'LAOD_QUANTITY',
        'LAOD_AMOUNT',
        'LAOD_DELIVERY_STATUS', // 'New','Processing','Delivered','Acknowledged','Cancelled'
        'LAOD_EXPIRY_DATETIME',
        'LAOD_IS_REQUEST_ESCALATED',
        'LAOD_ASSIGNED_AT',
        'LAOD_CREATED_BY',
        'LAOD_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'LAOD_CREATED_AT';
    protected $updatedField  = 'LAOD_UPDATED_AT';
}
