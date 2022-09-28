<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItem extends Model
{
    protected $table      = 'FLXY_MENU_ITEMS';
    protected $primaryKey = 'MI_ID';
    protected $allowedFields = [
        'MI_RESTAURANT_ID',
        'MI_MENU_CATEGORY_ID',
        'MI_MEAL_TYPE_ID',
        'MI_ITEM',
        'MI_IMAGE_URL',
        'MI_PRICE',
        'MI_IS_AVAILABLE',
        'MI_SEQUENCE',
        'MI_DESCRIPTION',
        'MI_CREATED_BY',
        'MI_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'MI_CREATED_AT';
    protected $updatedField  = 'MI_UPDATED_AT';
}
