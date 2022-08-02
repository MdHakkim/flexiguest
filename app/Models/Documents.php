<?php

namespace App\Models;

use CodeIgniter\Model;

class Documents extends Model
{
    protected $table      = 'FLXY_DOCUMENTS';
    protected $primaryKey = 'DOC_ID';
    protected $allowedFields = [
        'DOC_CUST_ID',
        'DOC_FILE_PATH',
        'DOC_FILE_TYPE',
        'DOC_FILE_DESC',
        'DOC_IS_VERIFY',
        'DOC_CREATE_UID',
        'DOC_UPDATE_UID',
        'DOC_RESV_ID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'DOC_CREATE_DT';
    protected $updatedField  = 'DOC_UPDATE_DT';
}
