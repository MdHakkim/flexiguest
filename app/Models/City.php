<?php

namespace App\Models;

use CodeIgniter\Model;

class City extends Model
{
    protected $table      = 'CITY';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ctname',
        'state_id',
        'state_code',
        'state_name',
        'country_id',
        'country_code',
        'country_name',
        'latitude',
        'longitude',
        'wikiDataId',
    ];

    protected $useAutoIncrement = true;
}
