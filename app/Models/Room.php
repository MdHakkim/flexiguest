<?php

namespace App\Models;

use CodeIgniter\Model;

class Room extends Model
{
    protected $table      = 'FLXY_ROOM';
    protected $primaryKey = 'RM_ID';
    // protected $allowedFields = [
    // ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RM_CREATED_DT';
    protected $updatedField  = 'RM_UPDATED_DT';
}
