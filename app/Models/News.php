<?php

namespace App\Models;

use CodeIgniter\Model;

class News extends Model
{
    protected $table      = 'FLXY_NEWS';
    protected $primaryKey = 'NS_ID';
    protected $allowedFields = [
        'NS_TITLE',
        'NS_COVER_IMAGE',
        'NS_DESCRIPTION',
        'NS_BODY',
        'NS_CREATED_BY',
        'NS_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'NS_CREATED_AT';
    protected $updatedField  = 'NS_UPDATED_AT';
}
