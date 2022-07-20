<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\HKAssignedTask;
use App\Models\HKAssignedTaskDetail;
use App\Models\HKAssignedTaskDetailNote;
use App\Models\HKAssignedTaskNote;
use App\Models\HKAssignedTaskNoteAttachment;
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
    private $HKAssignedTaskNote;
    private $HKAssignedTaskNoteAttachment;
    private $HKAssignedTaskDetail;
    private $HKAssignedTaskDetailNote;

    public function __construct()
    {
        $this->Room = new Room();
        $this->HKTask = new HKTask();
        $this->HKSubTask = new HKSubTask();
        $this->HKAssignedTask = new HKAssignedTask();
        $this->HKAssignedTaskNote = new HKAssignedTaskNote();
        $this->HKAssignedTaskNoteAttachment = new HKAssignedTaskNoteAttachment();
        $this->HKAssignedTaskDetail = new HKAssignedTaskDetail();
        $this->HKAssignedTaskDetailNote = new HKAssignedTaskDetailNote();
    }

    public function allTasks()
    {
        $all_tasks = $this->HKAssignedTask
            ->select(
                "FLXY_HK_ASSIGNED_TASKS.*, 
                HKT_DESCRIPTION as TASK_TITLE,
                RM_NO,
                USR_NAME as ATTENDEE_NAME,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Not Inspected') as NOT_INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Inspected') as INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Rejected') as REJECTED_COUNT,
                "
            )
            ->join('FLXY_HK_TASKS', 'HKAT_TASK_ID = HKT_ID', 'left')
            ->join('FLXY_ROOM', 'HKAT_ROOM_ID = RM_ID', 'left')
            ->join('FLXY_USERS', 'HKAT_ATTENDANT_ID = USR_ID', 'left')
            ->findAll();

        return $this->respond(responseJson(200, false, ['msg' => 'All Tasks'], $all_tasks));
    }

    public function taskDetails($task_id)
    {
        $data = $this->HKAssignedTask
            ->select('FLXY_HK_ASSIGNED_TASKS.*, RM_NO')
            ->join('FLXY_ROOM', 'HKAT_ROOM_ID = RM_ID', 'left')
            ->find($task_id);

        $notes = $this->HKAssignedTaskNote
            ->select('FLXY_HK_ASSIGNED_TASK_NOTES.*, USR_NAME')
            ->join('FlXY_USERS', 'ATN_USER_ID = USR_ID', 'left')
            ->where('ATN_ASSIGNED_TASK_ID', $task_id)
            ->findAll();

        foreach ($notes as $index => $note) {
            $attachments = $this->HKAssignedTaskNoteAttachment
                ->where('ATNA_NOTE_ID', $note['ATN_ID'])
                ->findAll();

            foreach ($attachments as $j => $attachment) {
                $name = getOriginalFileName($attachment);
                $url = base_url("assets/Uploads/HouseKeepingTasks/$attachment");

                $attachment_array = explode(".", $attachment);
                $type = getFileType(end($attachment_array));

                $attachments[$j] = ['name' => $name, 'url' => $url, 'type' => $type];
            }

            $notes[$index]['ATTACHMENTS'] = $attachments;
        }

        $data['NOTES'] = $notes;

        $task_details = $this->HKAssignedTaskDetail
            ->select('FLXY_HK_ASSIGNED_TASK_DETAILS.*, HKST_DESCRIPTION')
            ->join('FLXY_HK_SUBTASKS', 'HKATD_SUBTASK_ID = HKST_ID', 'left')
            ->where('HKATD_ASSIGNED_TASK_ID', $task_id)
            ->findAll();

        foreach ($task_details as $index => $task_detail)
            $task_details[$index]['NOTES'] = $this->HKAssignedTaskDetailNote
                ->select('FLXY_HK_ASSIGNED_TASK_DETAIL_NOTES.*, USR_NAME')
                ->join('FlXY_USERS', 'ATDN_USER_ID = USR_ID', 'left')
                ->where('ATDN_ASSIGNED_TASK_DETAIL_ID', $task_detail['HKATD_ID'])
                ->findAll();

        $data['TASK_DETAILS'] = $task_details;

        return $this->respond(responseJson(200, false, ['msg' => 'Task Details'], $data));
    }
}
