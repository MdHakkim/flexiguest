<?php

namespace App\Models;

use CodeIgniter\Model;

class ShuttleRoute extends Model
{
    protected $table      = 'FLXY_SHUTTLE_ROUTE';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'shuttle_id',
        'stage_id',
        'duration_mins',
        'order_no',
        'create_uid',
        'update_uid',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
