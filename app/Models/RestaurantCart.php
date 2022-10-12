<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantCart extends Model
{
    protected $table      = 'FLXY_RESTAURANT_CART';
    protected $primaryKey = 'RC_ID';
    protected $allowedFields = [
        'RC_CUSTOMER_ID',
        'RC_MENU_ITEM_ID',
        'RC_QUANTITY',
        'RC_AMOUNT',
        'RC_CREATED_BY',
        'RC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RC_CREATED_AT';
    protected $updatedField  = 'RC_UPDATED_AT';
}
