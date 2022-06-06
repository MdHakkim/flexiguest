<?php

namespace App\Models;

use CodeIgniter\Model;

class GuidelineFile extends Model
{
    protected $table      = 'FLXY_GUIDELINE_FILES';
    protected $primaryKey = 'GLF_ID';
    protected $allowedFields = [
        'GLF_GUIDELINE_ID',
        'GLF_FILE_URL',
        'GLF_FILE_TYPE',
        'GLF_FILE_NAME',
        'GLF_CREATED_BY',
        'GLF_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'GLF_CREATED_AT';
    protected $updatedField  = 'GLF_UPDATED_AT';
}
