<?php

namespace App\Models;

use CodeIgniter\Model;

class AppUpdate extends Model
{
    protected $table      = 'FLXY_APP_UPDATES';
    protected $primaryKey = 'AU_ID';
    protected $allowedFields = [
        'AU_TITLE',
        'AU_COVER_IMAGE',
        'AU_DESCRIPTION',
        'AU_BODY',
        'AU_CREATED_BY',
        'AU_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'AU_CREATED_AT';
    protected $updatedField  = 'AU_UPDATED_AT';
}
