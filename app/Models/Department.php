<?php

namespace App\Models;

use CodeIgniter\Model;

class Department extends Model
{
    protected $table      = 'FLXY_DEPARTMENT';
    protected $primaryKey = 'DEPT_ID';
    protected $allowedFields = [
        'DEPT_CODE',
        'DEPT_DESC',
        'DEPT_DIS_SEQ',
        'DEPT_STATUS',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'DOC_CREATE_DT';
    // protected $updatedField  = 'DOC_UPDATE_DT';
}
