<?php

namespace App\Models;

use CodeIgniter\Model;

class AppUpdate extends Model
{
    protected $table      = 'app_updates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'cover_image',
        'description',
        'body',
        'created_by',
        'updated_by',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
