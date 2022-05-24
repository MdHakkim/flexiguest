<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppUpdate;
use CodeIgniter\API\ResponseTrait;

class AppUpdateController extends BaseController
{
    use ResponseTrait;

    private $AppUpdate;

    public function __construct()
    {
        $this->AppUpdate = new AppUpdate();
    }

    public function appUpdate()
    {
        $app_updates = $this->AppUpdate->orderBy('id', 'desc')->findAll();
        
        foreach($app_updates as $index => $item){
            $app_updates[$index]['cover_image'] = base_url($item['cover_image']);
        }

        $result = responseJson(200, false, 'App updates list', $app_updates);
        return $this->respond($result);
    }
}