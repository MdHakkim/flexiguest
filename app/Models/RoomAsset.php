<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomAsset extends Model
{
    protected $table      = 'FLXY_ROOM_ASSETS';
    protected $primaryKey = 'RA_ID';
    protected $allowedFields = [
        'RA_ROOM_ID',
        'RA_ASSET_ID',
        'RA_QUANTITY',
        'RA_CREATED_BY',
        'RA_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RA_CREATED_AT';
    protected $updatedField  = 'RA_UPDATED_AT';
}
