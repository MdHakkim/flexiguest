<?php

namespace App\Models;

use CodeIgniter\Model;

class Asset extends Model
{
    protected $table      = 'FLXY_ASSETS';
    protected $primaryKey = 'AS_ID';
    protected $allowedFields = [
        'AS_ASSET_CATEGORY_ID',
        'AS_ASSET',
        'AS_PRICE',
        'AS_CREATED_BY',
        'AS_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'AS_CREATED_AT';
    protected $updatedField  = 'AS_UPDATED_AT';
}
