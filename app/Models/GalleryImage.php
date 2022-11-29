<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryImage extends Model
{
    protected $table      = 'FLXY_GALLERY_IMAGES';
    protected $primaryKey = 'GI_ID';
    protected $allowedFields = [
        'GI_IMAGE',
        'GI_SEQUENCE',
        'GI_CREATED_BY',
        'GI_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'GI_CREATED_AT';
    protected $updatedField  = 'GI_UPDATED_AT';
}
