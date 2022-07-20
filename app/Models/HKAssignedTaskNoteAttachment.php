<?php

namespace App\Models;

use CodeIgniter\Model;

class HKAssignedTaskNoteAttachment extends Model
{
    protected $table      = 'FLXY_HK_ASSIGNED_TASK_NOTE_ATTACHMENTS';
    protected $primaryKey = 'ATNA_ID';
    protected $allowedFields = [
        'ATNA_NOTE_ID',
        'ATNA_NAME',
        'ATNA_TYPE',
        'ATNA_URL',
        'ATNA_CREATED_BY',
        'ATNA_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'ATNA_CREATED_AT';
    protected $updatedField  = 'ATNA_UPDATED_AT';
}
