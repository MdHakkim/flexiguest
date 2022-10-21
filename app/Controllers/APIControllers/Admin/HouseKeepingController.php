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
        $user = $this->request->user;
        $user_id = $user['USR_ID'];
        $user_role_id = $user['USR_ROLE_ID'];
        
        $where_condition = '1 = 1';
        if($user_role_id == '3')
            $where_condition = "HKAT_ATTENDANT_ID = $user_id";

        $all_tasks = $this->HKAssignedTask
            ->select(
                "FLXY_HK_ASSIGNED_TASKS.*, 
                HKT_DESCRIPTION as TASK_TITLE,
                RM_NO,
                USR_NAME as ATTENDEE_NAME,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Not Inspected') as NOT_INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Inspected') as INSPECTED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_INSPECTED_STATUS = 'Rejected') as REJECTED_COUNT,

                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_STATUS = 'Completed') as COMPLETED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_STATUS = 'In Progress') as IN_PROGRESS_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_STATUS = 'Partially Completed') as PARTIALLY_COMPLETED_COUNT,
                (select count(*) from FLXY_HK_ASSIGNED_TASK_DETAILS where HKATD_ASSIGNED_TASK_ID = HKAT_ID and HKATD_STATUS = 'Skipped') as SKIPPED_COUNT
                "
            )
            ->join('FLXY_HK_TASKS', 'HKAT_TASK_ID = HKT_ID', 'left')
            ->join('FLXY_ROOM', 'HKAT_ROOM_ID = RM_ID', 'left')
            ->join('FLXY_USERS', 'HKAT_ATTENDANT_ID = USR_ID', 'left')
            ->where($where_condition)
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

    public function markSubtaskCompletedInspected()
    {
        $user = $this->request->user;
        $subtask_ids = $this->request->getVar('subtask_ids');

        foreach ($subtask_ids as $subtask_id) {
            $sub_task = $this->HKAssignedTaskDetail->find($subtask_id);
            if (empty($sub_task))
                return $this->respond(responseJson(404, true, ['msg' => 'No Task found.']));
            
            if($sub_task['HKATD_STATUS'] == 'In Progress')
                return $this->respond(responseJson(202, true, ['msg' => 'Not All tasks are completed.']));
        }

        if ($user['USR_ROLE_ID'] == '3') {
            $data = [
                'HKATD_STATUS' => 'Completed',
                'HKATD_COMPLETION_TIME' => date('Y-m-d H:i:s')
            ];
        } else {
            $data = [
                'HKATD_INSPECTED_STATUS' => 'Inspected',
                'HKATD_INSPECTED_DATETIME' => date('Y-m-d H:i:s')
            ];
        }

        $this->HKAssignedTaskDetail->whereIn('HKATD_ID', $subtask_ids)->set($data)->update();

        return $this->respond(responseJson(200, false, ['msg' => 'Completed successfully.']));
    }

    public function submitTaskNote()
    {
        $rules = [
            'task_id' => ['label' => 'task', 'rules' => 'required'],
            'note' => ['label' => 'note', 'rules' => 'required'],
        ];

        if ($this->request->getFileMultiple('attachments'))
            $rules = array_merge($rules, [
                'attachments.*' => [
                    'label' => 'attachments',
                    'rules' => ['mime_in[attachments,image/png,image/jpg,image/jpeg]', 'max_size[attachments, 2048]']
                ],
            ]);

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data['ATN_USER_ID'] = $user_id = $this->request->user['USR_ID'];
        $data['ATN_ASSIGNED_TASK_ID'] = $this->request->getVar('task_id');
        $data['ATN_NOTE'] = $this->request->getVar('note');

        if (empty($this->HKAssignedTask->find($data['ATN_ASSIGNED_TASK_ID'])))
            return $this->respond(responseJson(404, true, ['msg' => 'No Task found.']));

        $task_note_id = $this->HKAssignedTaskNote->insert($data);

        if ($this->request->getFileMultiple('attachments')) {
            foreach ($this->request->getFileMultiple('attachments') as $file) {
                $file_name = $file->getName();
                $directory = "assets/Uploads/HouseKeepingTasks/";

                $response = documentUpload($file, $file_name, $user_id, $directory);

                if ($response['SUCCESS'] != 200)
                    return $this->respond(responseJson(500, true, ['msg' => "Unable to upload file."]));

                $attachment['ATNA_NOTE_ID'] = $task_note_id;
                $attachment['ATNA_NAME'] = $file_name;
                $attachment['ATNA_TYPE'] = $file->getClientMimeType();
                $attachment['ATNA_URL'] = $directory . $response['RESPONSE']['OUTPUT'];

                $this->HKAssignedTaskNoteAttachment->insert($attachment);
            }
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Note submitted successfully.']));
    }

    public function submitSubtaskNote()
    {
        $rules = [
            'subtask_id' => ['label' => 'task', 'rules' => 'required'],
            'note' => ['label' => 'note', 'rules' => 'required'],
            'status' => ['label' => 'status', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $user = $this->request->user;
        $data['ATDN_USER_ID'] = $user['USR_ID'];
        $data['ATDN_ASSIGNED_TASK_DETAIL_ID'] = $this->request->getVar('subtask_id');
        $data['ATDN_NOTE'] = $this->request->getVar('note');

        $subtask = $this->HKAssignedTaskDetail->find($data['ATDN_ASSIGNED_TASK_DETAIL_ID']);
        if (empty($subtask))
            return $this->respond(responseJson(404, true, ['msg' => 'No Subtask found.']));

        $this->HKAssignedTaskDetailNote->insert($data);

        $status = $this->request->getVar('status');
        if ($user['USR_ROLE_ID'] == '3')
            $subtask['HKATD_STATUS'] = $status;
        else {
            if ($subtask['HKATD_STATUS'] == 'In Progress')
                    return $this->respond(responseJson(202, true, ['msg' => 'This task is not completed yet.']));

            if ($status == 'Inspected') {

                $subtask['HKATD_INSPECTED_STATUS'] = 'Inspected';
                $subtask['HKATD_INSPECTED_DATETIME'] = date('Y-m-d H:i:s');
            } else {
                $subtask['HKATD_INSPECTED_STATUS'] = 'Rejected';
                $subtask['HKATD_STATUS'] = 'In Progress';
                $subtask['HKATD_COMPLETION_TIME'] = null;
            }
        }
        $this->HKAssignedTaskDetail->save($subtask);

        return $this->respond(responseJson(200, false, ['msg' => 'Note submitted successfully.']));
    }
}
