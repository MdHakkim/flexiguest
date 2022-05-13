<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class ProfileController extends BaseController
{
    use ResponseTrait;

    private $Users;

    public function __construct()
    {
        $this->Users = new UserModel();
    }

    public function getProfileData()
    {
        $user = $this->request->user;
        
        $result = responseJson(200, false, "Admin profile data", $user);
        return $this->respond($result);
    }

}