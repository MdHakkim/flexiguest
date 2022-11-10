<?php
namespace App\Validation;

use App\Models\UserModel;

class Userrules
{
    public function validateUser(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user = $model->where('USR_EMAIL', $data['username'])->orWhere('USR_NAME', $data['username'])->first();

        if (!$user) {
            return false;
        }

        return password_verify($data['password'], $user['USR_PASSWORD']);
    }

    public function userActive(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user = null;
        $user = $model->where(['USR_EMAIL'=> $data['username'], 'USR_STATUS'=> 1])->first() ?? $model->Where(['USR_NAME'=> $data['username'], 'USR_STATUS'=> 1])->first(); 
       
        if (!$user) {
            return false;
        }else
        return true;
    

        
    }
}