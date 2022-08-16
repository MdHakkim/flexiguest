<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Maintenance;
use App\Models\MaintenanceRequestComment;
use App\Models\Room;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class MaintenanceController extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $Maintenance;
    private $Room;
    private $User;
    private $MaintenanceRequestComment;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Maintenance = new Maintenance();
        $this->Room = new Room();
        $this->User = new UserModel();
        $this->MaintenanceRequestComment = new MaintenanceRequestComment();
    }

    public function maintenanceList()
    {
        $user = $this->request->user;

        $where_condition = '1 = 1';
        if($user['USR_ROLE'] == 'attendee')
            $where_condition = "MAINT_ATTENDANT_ID = {$user['USR_ID']}";

        $maintenace_list = $this->Maintenance
            ->select("FLXY_MAINTENANCE.*, fmc.MAINT_CATEGORY as MAINT_CATEGORY_TEXT, fmsc.MAINT_SUBCATEGORY as MAINT_SUBCATEGORY,
            (case when MAINT_ATTENDANT_ID is not null then USR_NAME else null end) as ATTENDEE_NAME")
            ->join('FLXY_MAINTENANCE_CATEGORY as fmc', 'FLXY_MAINTENANCE.MAINT_CATEGORY = fmc.MAINT_CAT_ID', 'left')
            ->join('FLXY_MAINTENANCE_SUBCATEGORY as fmsc', 'FLXY_MAINTENANCE.MAINT_SUB_CATEGORY = fmsc.MAINT_SUBCAT_ID', 'left')
            ->join('FLXY_USERS', 'MAINT_ATTENDANT_ID = USR_ID', 'left')
            ->where($where_condition)
            ->orderBy('FLXY_MAINTENANCE.MAINT_ID', 'desc')
            ->findAll();

        foreach ($maintenace_list as $i => $maintenance_request) {

            $attachments = [];
            if ($maintenance_request['MAINT_ATTACHMENT']) {
                $attachments = explode(",", $maintenance_request['MAINT_ATTACHMENT']);

                foreach ($attachments as $j => $attachment) {
                    $name = getOriginalFileName($attachment);
                    $url = base_url("assets/Uploads/Maintenance/$attachment");

                    $attachment_array = explode(".", $attachment);
                    $type = getFileType(end($attachment_array));

                    $attachments[$j] = ['name' => $name, 'url' => $url, 'type' => $type];
                }
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

    public function createUpdateMaintenanceRequest()
    {
        $user = $this->request->user;
        $user_id = $user['USR_ID'];

        $rules = [
            'type' => 'required',
            'category' => 'required',
            'roomNo' => 'required',
            'reservationId' => 'required',
            'customerId' => 'required',
            'status' => 'required',
        ];

        if (!empty($this->request->getVar('type')) && $this->request->getVar('type') == 'maintenance')
            $rules['subCategory'] = 'required';

        $validate = $this->validate($rules);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $fileArry = $this->request->getFileMultiple('attachment');
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if (!$file->isValid()) {
                    return $this->respond(responseJson(500, true, ['msg' => "Please upload valid file. This file '{$file->getClientName()}' is not valid"]));
                }
            }
        }

        $fileNames = '';
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = newFileName($file, $user_id);

                    $file->move(ROOTPATH . 'assets/Uploads/Maintenance', $newName);
                    $comma = '';

                    if (isset($fileArry[$key + 1]) && $fileArry[$key + 1]->isValid()) {
                        $comma = ',';
                    }

                    if ($newName)
                        $fileNames .= $newName . $comma;
                }
            }
        }

        $request_id = $this->request->getVar('requestId');
        $data = [
            "CUST_NAME" => $this->request->getVar("customerId"),
            "MAINT_TYPE" => $this->request->getVar("type"),
            "MAINT_CATEGORY" => $this->request->getVar("category"),
            "MAINT_SUB_CATEGORY" => $this->request->getVar("subCategory"),
            "MAINT_DETAILS" => $this->request->getVar("details"),
            "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getVar("preferredDate"))),
            "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getVar("preferredTime"))),
            "MAINT_ATTACHMENT" => $fileNames,
            "MAINT_ROOM_NO" => $this->request->getVar("roomNo"),
            "MAINT_RESV_ID" => $this->request->getVar("reservationId"),
            "MAINT_STATUS" => $this->request->getVar("status"),
            "MAINT_COMMENT" => $this->request->getVar("comment"),
            "MAINT_CREATE_DT" => date("Y-m-d H:i:s"),
            "MAINT_UPDATE_DT" => date("Y-m-d H:i:s"),
            "MAINT_CREATE_UID" => $user_id,
            "MAINT_UPDATE_UID" => $user_id
        ];

        if (empty($request_id)) {
            $ins = $this->DB->table('FLXY_MAINTENANCE')->insert($data);
        } else {
            if (empty($fileNames))
                unset($data['MAINT_ATTACHMENT']);

            unset($data['MAINT_CREATE_DT']);
            unset($data['MAINT_CREATE_UID']);

            $ins = $this->DB->table('FLXY_MAINTENANCE')->where('MAINT_ID', $request_id)->update($data);
        }

        if ($ins)
            $result = responseJson(200, false, ["msg" => "Maintenance request created/updated successfully."]);
        else
            $result = responseJson(500, true, ["msg" => "insert/update Failed"]);

        return $this->respond($result);
    }

    public function assignTask()
    {
        $user_id = $this->request->user['USR_ID'];
        $maintenance_request_id = $this->request->getVar('maintenance_request_id');
        $attendant_id = $this->request->getVar('attendant_id');

        $maintenance_request = $this->Maintenance->find($maintenance_request_id);
        if (empty($maintenance_request))
            return $this->respond(responseJson(404, true, ['msg' => 'Invalid maintenance request.']));

        $attendant = $this->User->find($attendant_id);
        if (empty($attendant))
            return $this->respond(responseJson(404, true, ['msg' => 'Invalid member.']));

        $maintenance_request['MAINT_ATTENDANT_ID'] = $attendant_id;
        $maintenance_request['MAINT_ASSIGNED_AT'] = date('Y-m-d H:i:s');
        $maintenance_request['MAINT_STATUS'] = 'Assigned';
        $maintenance_request['MAINT_UPDATE_UID'] = $user_id;
        $this->Maintenance->save($maintenance_request);

        return $this->respond(responseJson(200, false, ['msg' => 'Task is assigned successfully.']));
    }

    public function updateStatus()
    {
        $user_id = $this->request->user['USR_ID'];
        $maintenance_request_id = $this->request->getVar('maintenance_request_id');
        $status = $this->request->getVar('status');

        $maintenance_request = $this->Maintenance->find($maintenance_request_id);
        if (empty($maintenance_request))
            return $this->respond(responseJson(404, true, ['msg' => 'Invalid maintenance request.']));


        $maintenance_request['MAINT_STATUS'] = $status;
        $maintenance_request['MAINT_UPDATE_UID'] = $user_id;
        $this->Maintenance->save($maintenance_request);

        return $this->respond(responseJson(200, false, ['msg' => 'Status updated']));
    }

    public function addComment()
    {
        $user_id = $this->request->user['USR_ID'];
        $maintenance_request_id = $this->request->getVar('maintenance_request_id');
        $comment = $this->request->getVar('comment');

        $maintenance_request = $this->Maintenance->find($maintenance_request_id);
        if (empty($maintenance_request))
            return $this->respond(responseJson(404, true, ['msg' => 'Invalid maintenance request.']));

        $data = [
            'MRC_MAINTENANCE_REQUEST_ID' => $maintenance_request_id,
            'MRC_USER_ID' => $user_id,
            'MRC_COMMENT' => $comment,
            'MRC_CREATED_BY' => $user_id,
            'MRC_UPDATED_BY' => $user_id,
        ];
        $this->MaintenanceRequestComment->insert($data);

        return $this->respond(responseJson(200, false, ['msg' => 'Comment added.']));
    }

    public function getComments()
    {
        $maintenance_request_id = $this->request->getVar('maintenance_request_id');

        $comments = $this->MaintenanceRequestComment
            ->select('FLXY_MAINTENANCE_REQUEST_COMMENTS.*, USR_NAME')
            ->join('FLXY_USERS', 'MRC_USER_ID = USR_ID', 'left')
            ->where('MRC_MAINTENANCE_REQUEST_ID', $maintenance_request_id)
            ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'comments'], $comments));
    }
}
