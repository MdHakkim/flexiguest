<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserRepository extends BaseController
{
    private $User;

    public function __construct()
    {
        $this->User = new UserModel();
    }

    public function userByDepartment($department_ids)
    {
        return $this->User->whereIn('USR_DEPARTMENT', $department_ids)->findAll();
    }

    public function userById($user_id)
    {
        return $this->User->find($user_id);
    }
}