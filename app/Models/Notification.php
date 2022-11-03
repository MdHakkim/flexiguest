<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification extends Model
{
    protected $table      = 'FLXY_NOTIFICATIONS';
    protected $primaryKey = 'NOTIFICATION_ID';
    protected $allowedFields = [
        'NOTIFICATION_TYPE',
        'NOTIFICATION_FROM_ID',
        'NOTIFICATION_DEPARTMENT',
        'NOTIFICATION_TO_ID',
        'NOTIFICATION_RESERVATION_ID',
        'NOTIFICATION_GUEST_ID',
        'NOTIFICATION_URL',
        'NOTIFICATION_TEXT',
        'NOTIFICATION_DATE_TIME',
        'NOTIFICATION_READ_STATUS',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = '';
    // protected $updatedField  = '';
}
