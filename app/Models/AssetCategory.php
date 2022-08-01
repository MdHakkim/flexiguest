<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetCategory extends Model
{
    protected $table      = 'FLXY_ASSET_CATEGORIES';
    protected $primaryKey = 'AC_ID';
    protected $allowedFields = [
        'AC_CATEGORY',
        'AC_CREATED_BY',
        'AC_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'AC_CREATED_AT';
    protected $updatedField  = 'AC_UPDATED_AT';
}
