<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
      
        // if (session()->get('USR_ROLE_ID') != "1") {
        //     echo 'Access denied';
        //     exit;
        // }
    }
    public function index()
    {
        return view("login/Dashboard");
    }

    public function frontDesk()
    {
        $data['user_id'] = session()->get('USR_ID');
        return view('login/FrontDesk', $data);
    }
}