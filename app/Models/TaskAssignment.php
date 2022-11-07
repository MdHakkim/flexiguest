<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskAssignment extends Model
{
    protected $table      = 'FLXY_HK_TASKASSIGNMENT_OVERVIEW';
    protected $primaryKey = 'HKTAO_ID';
    protected $allowedFields = [
        'HKTAO_TASK_DATE',
        'HKTAO_TASK_DATE',
        'HKTAO_TASK_CODE',
        'HKTAO_AUTO',
        'HKTAO_TOTAL_SHEETS',
        'HKTAO_TOTAL_CREDIT',
        'HKTAO_CREATED_AT',
        'HKTAO_CREATED_BY',
        'HKTAO_UPDATED_AT',        
        'HKTAO_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'HKTAO_CREATED_AT'; 
    protected $updatedField  = 'HKTAO_UPDATED_AT';
}
