<?php

namespace App\Models;

use CodeIgniter\Model;

class News extends Model
{
    protected $table      = 'news';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'cover_image',
        'description',
        'body',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
