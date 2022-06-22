<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Maintenance;
use App\Models\Room;
use CodeIgniter\API\ResponseTrait;

class MaintenanceController extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $Maintenance;
    private $Room;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Maintenance = new Maintenance();
        $this->Room = new Room();
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

    public function getRoomList()
    {
        $rooms = $this->Room->select('RM_NO, RM_DESC')->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'Rooms list'], $rooms));
    }

    public function reservationOfRoom($room_no)
    {
        $sql = "SELECT concat(b.CUST_FIRST_NAME,' ',b.CUST_MIDDLE_NAME,' ',b.CUST_LAST_NAME) NAME, 
                    b.CUST_ID, 
                    a.RESV_ID, 
                    concat(a.RESV_ID, '-', b.CUST_FIRST_NAME,' ', b.CUST_MIDDLE_NAME,' ', b.CUST_LAST_NAME, '-', b.CUST_ID) as DD_STR 
                    FROM FLXY_RESERVATION a
                    LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME WHERE a.RESV_ROOM =:RESV_ROOM: AND a.RESV_STATUS = 'Checked-In'";

        $param = ['RESV_ROOM' => $room_no];
        $response = $this->DB->query($sql, $param)->getResultArray();
        
        return $this->respond(responseJson(200, true, ['msg' => 'Reservation detail'], $response));
    }
}