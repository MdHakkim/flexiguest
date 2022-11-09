<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationTrace extends Model
{
    protected $table      = 'FLXY_RESERVATION_TRACES';
    protected $primaryKey = 'RSV_TRACE_ID';
    protected $allowedFields = [
        'RSV_ID',
        'RSV_TRACE_DATE',
        'RSV_TRACE_TIME',
        'RSV_TRACE_DEPARTMENT',
        'RSV_TRACE_TEXT',
        'RSV_TRACE_ENTERED_BY',
        'RSV_TRACE_RESOLVED_BY',
        'RSV_TRACE_RESOLVED_ON',
        'RSV_TRACE_STATUS',
        'RSV_TRACE_RESOLVED_TIME',
        'RSV_TRACE_NOTIFICATION_ID',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
