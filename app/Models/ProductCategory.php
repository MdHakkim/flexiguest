<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategory extends Model
{
    protected $table      = 'FLXY_PRODUCT_CATEGORIES';
    protected $primaryKey = 'PC_ID';
    protected $allowedFields = [
        'PC_CATEGORY',
        'PC_CREATED_BY',
        'PC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PC_CREATED_AT';
    protected $updatedField  = 'PC_UPDATED_AT';
}
