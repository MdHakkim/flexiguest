<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuCategory extends Model
{
    protected $table      = 'FLXY_MENU_CATEGORIES';
    protected $primaryKey = 'MC_ID';
    protected $allowedFields = [
        'MC_CATEGORY',
        'MC_RESTAURANT_ID',
        'MC_CREATED_BY',
        'MC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'MC_CREATED_AT';
    protected $updatedField  = 'MC_UPDATED_AT';
}
