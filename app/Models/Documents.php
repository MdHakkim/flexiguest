<?php

namespace App\Models;

use CodeIgniter\Model;

class Documents extends Model
{
    protected $table      = 'FLXY_DOCUMENTS';
    protected $primaryKey = 'DOC_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'DOC_CREATE_DT';
    protected $updatedField  = 'DOC_UPDATE_DT';
}
