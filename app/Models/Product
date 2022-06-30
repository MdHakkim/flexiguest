<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table      = 'FLXY_PRODUCTS';
    protected $primaryKey = 'PR_ID';
    protected $allowedFields = [
        'PR_CATEGORY_ID',
        'PR_IMAGE',
        'PR_NAME',
        'PR_QUANTITY',
        'PR_CREATED_BY',
        'PR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PR_CREATED_AT';
    protected $updatedField  = 'PR_UPDATED_AT';
}
