<?php

namespace App\Models;

use CodeIgniter\Model;

class Alert extends Model
{
    protected $table      = 'FLXY_ALERTS';
    protected $primaryKey = 'AL_ID';
    protected $allowedFields = [
        'AL_DEPARTMENT_IDS',
        'AL_USER_IDS',
        'AL_MESSAGE',
        'AL_CREATED_BY',
        'AL_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'AL_CREATED_AT';
    protected $updatedField  = 'AL_UPDATED_AT';
}
