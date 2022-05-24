<?php

namespace App\Models;

use CodeIgniter\Model;

class GuidelineFile extends Model
{
    protected $table      = 'guideline_files';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'guideline_id',
        'file_url',
        'file_type',
        'file_name',
        'created_by',
        'updated_by',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
