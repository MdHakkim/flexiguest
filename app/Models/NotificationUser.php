<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationUser extends Model
{
    protected $table      = 'FLXY_NOTIFICATION_USERS';
    protected $primaryKey = 'NU_ID';
    protected $allowedFields = [
        'NU_USER_ID',
        'NU_NOTIFICATION_ID',
        'NU_READ_STATUS',
        'NU_CREATED_BY',
        'NU_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'NU_CREATED_AT';
    protected $updatedField  = 'NU_UPDATED_AT';
}
