<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationTrail extends Model
{
    protected $table      = 'FLXY_NOTIFICATION_TRAIL';
    protected $primaryKey = 'NOTIF_TRAIL_ID';
    protected $allowedFields = [
        'NOTIF_TRAIL_NOTIFICATION_ID',
        'NOTIF_TRAIL_DEPARTMENT',
        'NOTIF_TRAIL_USER',
        'NOTIF_TRAIL_RESERVATION',
        'NOTIF_TRAIL_GUEST',
        'NOTIF_TRAIL_READ_STATUS',
        'NOTIF_TRAIL_DATETIME',
        'NOTIFICATION_TRAIL_SEND',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
