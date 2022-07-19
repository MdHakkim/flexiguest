<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Customer;
use App\Models\HKAssignedTask;
use App\Models\HKAssignedTaskAttachment;
use App\Models\HKAssignedTaskDetail;
use App\Models\HKSubTask;
use App\Models\HKTask;
use App\Models\Room;
use CodeIgniter\API\ResponseTrait;

class HouseKeepingController extends BaseController
{
    use ResponseTrait;

    private $Room;
    private $HKTask;
    private $HKSubTask;
    private $HKAssignedTask;
    private $HKAssignedTaskAttachment;
    private $HKAssignedTaskDetail;

    public function __construct()
    {
        $this->Room = new Room();
        $this->HKTask = new HKTask();
        $this->HKSubTask = new HKSubTask();
        $this->HKAssignedTask = new HKAssignedTask();
        $this->HKAssignedTaskAttachment = new HKAssignedTaskAttachment();
        $this->HKAssignedTaskDetail = new HKAssignedTaskDetail();
    }

    public function allTasks()
    {
        $all_tasks = $this->HKAssignedTask
            ->select("FLXY_HK_ASSIGNED_TASKS.*, 
                HKT_DESCRIPTION as TASK_TITLE,
                RM_NO,
                USR_NAME as ATTENDEE_NAME,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Not Inspected') as NOT_INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Inspected') as INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Rejected') as REJECTED_COUNT,
                "
                )
            ->join('FLXY_HK_TASKS', 'HKAT_TASK_ID = HKT_ID')
            ->join('FLXY_ROOM', 'HKAT_ROOM_ID = RM_ID')
            ->join('FLXY_USERS', 'HKAT_ATTENDANT_ID = USR_ID')
            ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'All Tasks'], $all_tasks));
    }
}
