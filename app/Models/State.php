<?php

namespace App\Models;

use CodeIgniter\Model;

class State extends Model
{
    protected $table      = 'STATE';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'sname',
        'country_id',
        'country_code',
        'country_name',
        'state_code',
        'type',
        'latitude',
        'longitude',
    ];

    protected $useAutoIncrement = true;
}
