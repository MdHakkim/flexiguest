<?php

namespace App\Models;

use CodeIgniter\Model;

class BrandingLogo extends Model
{
    protected $table      = 'FLXY_BRANDING_LOGO';
    protected $primaryKey = 'BL_ID';
    protected $allowedFields = [
        'BL_URL',
        'BL_CREATED_BY',
        'BL_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'BL_CREATED_AT';
    protected $updatedField  = 'BL_UPDATED_AT';
}
