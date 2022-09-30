<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantOrderDetail extends Model
{
    protected $table      = 'FLXY_RESTAURANT_ORDER_DETAILS';
    protected $primaryKey = 'ROD_ID';
    protected $allowedFields = [
        'ROD_ORDER_ID',
        'ROD_MENU_ITEM_ID',
        'ROD_QUANTITY',
        'ROD_AMOUNT',
        'ROD_CREATED_BY',
        'ROD_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'ROD_CREATED_AT';
    protected $updatedField  = 'ROD_UPDATED_AT';
}
