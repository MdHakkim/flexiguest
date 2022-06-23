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
        $app_updates = $this->AppUpdate->orderBy('AU_ID', 'desc')->findAll();
        
        foreach($app_updates as $index => $item){
            $app_updates[$index]['AU_COVER_IMAGE'] = base_url($item['AU_COVER_IMAGE']);
        }

        $result = responseJson(200, false, 'App updates list', $app_updates);
        return $this->respond($result);
    }
}