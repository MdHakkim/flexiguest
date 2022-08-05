<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Department;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;

    private $Department;
    private $User;

    public function __construct()
    {
        $this->Department = new Department();
        $this->User = new UserModel();
    }

    public function userDepartments()
    {
        $departments = $this->Department->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Departments list'], $departments));
    }

    public function getUserByDepartment()
    {
        $department_id = $this->request->getVar('department_id');
        $users = $this->User->where('USR_DEPARTMENT', $department_id)->findAll();

        return $this->respond(responseJson(2000, false, ['msg' => 'Users list'], $users));
    }

    
}