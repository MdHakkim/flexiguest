<?php

namespace App\Models;

use CodeIgniter\Model;

class RestaurantTable extends Model
{
    protected $table      = 'FLXY_RESTAURANT_TABLES';
    protected $primaryKey = 'RT_ID';
    protected $allowedFields = [
        'RT_RESTAURANT_ID',
        'RT_TABLE_NO',
        'RT_NO_OF_SEATS',
        'RT_CREATED_BY',
        'RT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RT_CREATED_AT';
    protected $updatedField  = 'RT_UPDATED_AT';
}
