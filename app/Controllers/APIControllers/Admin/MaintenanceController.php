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

        foreach($maintenace_list as $i => $maintenance_request){
            $attachments = explode(",", $maintenance_request['MAINT_ATTACHMENT']);

            foreach($attachments as $j => $attachment){
                $name = $attachment;
                $url = base_url("assets/Uploads/Maintenance/$attachment");

                $attachment_array = explode(".", $attachment);
                $type = end($attachment_array);

                $attachments[$j] = ['name' => $name, 'url' => $url, 'type' => $type];
            }

            $maintenace_list[$i]['MAINT_ATTACHMENT'] = $attachments;
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Maintenance List'], $maintenace_list));
    }
}