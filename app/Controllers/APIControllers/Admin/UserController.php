<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Department;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;

    private $Department;

    public function __construct()
    {
        $this->Department = new Department();
    }

    public function userDepartments()
    {
        $departments = $this->Department->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Departments list'], $departments));
    }
}