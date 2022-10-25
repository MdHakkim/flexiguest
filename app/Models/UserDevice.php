<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDevice extends Model
{
    protected $table      = 'FLXY_USER_DEVICES';
    protected $primaryKey = 'UD_ID';
    protected $allowedFields = [
        'UD_USER_ID',
        'UD_REGISTRATION_ID',
        'UD_CREATED_BY',
        'UD_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'UD_CREATED_AT';
    protected $updatedField  = 'UD_UPDATED_AT';
}
