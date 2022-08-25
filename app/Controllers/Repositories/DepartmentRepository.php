<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Department;

class DepartmentRepository extends BaseController
{
    private $Department;

    public function __construct()
    {
        $this->Department = new Department();
    }

    public function allDepartments()
    {
        return $this->Department->findAll();
    }
}