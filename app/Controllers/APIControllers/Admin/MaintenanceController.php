<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Maintenance;
use CodeIgniter\API\ResponseTrait;

class MaintenanceController extends BaseController
{
    use ResponseTrait;

    private $Maintenance;

    public function __construct()
    {
        $this->Maintenance = new Maintenance();
    }

    public function maintenanceList()
    {
        $maintenace_list = $this->Maintenance->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Maintenance List'], $maintenace_list));
    }
}