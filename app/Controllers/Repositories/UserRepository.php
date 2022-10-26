<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\UserDevice;
use App\Models\UserModel;

class UserRepository extends BaseController
{
    private $User;
    private $UserDevice;

    public function __construct()
    {
        $this->User = new UserModel();
        $this->UserDevice = new UserDevice();
    }

    public function userByDepartment($department_ids)
    {
        return $this->User->whereIn('USR_DEPARTMENT', $department_ids)->findAll();
    }

    public function userById($user_id)
    {
        return $this->User->find($user_id);
    }

    public function getUserIdsByCustomerIds($customer_id)
    {
        $users = $this->User->whereIn('USR_CUST_ID', $customer_id)->findAll();
        $user_ids = [];
        foreach($users as $user) {
            $user_ids[] = $user['USR_ID'];
        }
        
        return $user_ids;
    }

    public function storeUserDevice($user_id, $registration_id)
    {
        $where_condition = "UD_REGISTRATION_ID = '$registration_id'";
        $this->removeUserDevice($where_condition);

        return $this->UserDevice->insert([
            'UD_USER_ID' => $user_id,
            'UD_REGISTRATION_ID' => $registration_id,
            'UD_CREATED_BY' => $user_id,
            'UD_UPDATED_BY' => $user_id
        ]);
    }

    public function getRegistrationIds($user_ids)
    {
        $registration_ids = [];

        $devices = $this->UserDevice->whereIn('UD_USER_ID', $user_ids)->findAll();
        foreach($devices as $device) {
            $registration_ids[] = $device['UD_REGISTRATION_ID'];
        }

        return $registration_ids;
    }

    public function removeUserDevice($where_condition)
    {
        return $this->UserDevice->where($where_condition)->delete();
    }
}
