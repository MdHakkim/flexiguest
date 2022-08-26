<?php

namespace App\Models;

use CodeIgniter\Model;

class EValletFile extends Model
{
    protected $table      = 'FLXY_EVALLET_FILES';
    protected $primaryKey = 'EVI_ID';
    protected $allowedFields = [
        'EVI_EVALLET_ID',
        'EVI_FILE_URL',
        'EVI_FILE_TYPE',
        'EVI_FILE_NAME',
        'EVI_CREATED_BY',
        'EVI_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'EVI_CREATED_AT';
    protected $updatedField  = 'EVI_UPDATED_AT';
}
