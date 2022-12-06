<?php

namespace App\Controllers;
use App\Libraries\DataTables\TaskAssignmentDataTable;
use CodeIgniter\API\ResponseTrait;

class TaskAssignmentController extends BaseController
{
    use ResponseTrait;
    public $Db;
    public $request;
    public $session;
    
    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'custom', 'common']);

        
    }

    /**************      TaskAssignment Functions      ***************/

    public function TaskAssignment()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;       
        $UserID = session()->get('USR_ID');  
        $data['task_status_attendant_list'] = $this->Db->table('FLXY_TASK_STATUS_MASTER')->select('STATUS_ID,STATUS_CODE,STATUS_COLOR_CLASS')->where('STATUS_USER_ROLE','3')->get()->getResultArray();
        $data['task_status_supervisor_list'] = $this->Db->table('FLXY_TASK_STATUS_MASTER')->select('STATUS_ID,STATUS_CODE,STATUS_COLOR_CLASS')->where('STATUS_USER_ROLE','5')->get()->getResultArray();
        $data['js_to_load'] = array("TaskAssignmentFormWizardNumbered.js");
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript']     = blockLoader_javascript();
        return view('TaskAssignment/TaskAssignmentView', $data);
    }

    public function TaskAssignmentView()
    {

        $UserID = session()->get('USR_ID');
        $mine = new TaskAssignmentDataTable();
        $tableName = "FLXY_HK_TASKASSIGNMENT_OVERVIEW INNER JOIN FLXY_HK_TASKS ON HKATO_TASK_CODE = HKT_ID INNER JOIN FLXY_USERS ON USR_ID = HKATO_CREATED_BY";
        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_TASK_DATE']) && $data['HKTAO_TASK_DATE'] != ''){
            $HKTAO_TASK_DATE = date('Y-m-d',strtotime($data['HKTAO_TASK_DATE']));
            $init_cond['HKTAO_TASK_DATE ='] = "'".$HKTAO_TASK_DATE."'";
        }

        if(isset($data['HKATO_TASK_CODE_SEARCH']) && $data['HKATO_TASK_CODE_SEARCH'] != '') {
            $HKATO_TASK_CODE_SEARCH = $data['HKATO_TASK_CODE_SEARCH'];
            $init_cond['HKATO_TASK_CODE = '] = "'".$HKATO_TASK_CODE_SEARCH."'";
        }
 
           
        if(isset($data['HKATO_CREATED_BY']) && $data['HKATO_CREATED_BY'] != ''){
           $HKATO_CREATED_BY = $data['HKATO_CREATED_BY'];
           $init_cond['HKATO_CREATED_BY = ' ] = "'".$HKATO_CREATED_BY."'";           
        }      

        $columns = 'HKTAO_ID,HKTAO_TASK_DATE,HKT_ID,HKT_CODE,HKT_DESCRIPTION,HKATO_AUTO,HKATO_TOTAL_SHEETS,HKATO_TOTAL_CREDIT,HKATO_TOTAL_ROOMS,HKATO_CREATED_AT,USR_FIRST_NAME,USR_LAST_NAME';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }


    public function insertTaskAssignment()
    {
        try {
            $sysid = $this->request->getPost('HKTAO_ID');
            $user_id = session()->get('USR_ID');

            $validate = $this->validate([
                'HKTAO_TASK_DATE' => ['label' => 'Date', 'rules' => 'required'],
                'HKATO_TASK_CODE' => ['label' => 'Task Code', 'rules' => 'required|Taskexists[HKTAO_TASK_DATE,HKATO_TASK_CODE,' . $sysid . ']', 'errors' => ['Taskexists' => 'Task exists on this date']],
            ]);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $data = [
                "HKTAO_TASK_DATE"    => trim($this->request->getPost('HKTAO_TASK_DATE')),
                "HKATO_TASK_CODE"    => trim($this->request->getPost('HKATO_TASK_CODE')),
                "HKATO_AUTO"         => trim($this->request->getPost('HKATO_AUTO')),
                "HKATO_TOTAL_SHEETS" => 0,
                "HKATO_TOTAL_CREDIT" => 0,         
                
            ];

            if (empty($sysid)) {
                $data["HKATO_CREATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKATO_CREATED_BY"] = $user_id;
            } else {
                $data["HKATO_UPDATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKATO_UPDATED_BY"] = $user_id;
            }

            $return = !empty($sysid) ? $this->Db->table('FLXY_HK_TASKASSIGNMENT_OVERVIEW')->where('HKTAO_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_TASKASSIGNMENT_OVERVIEW')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editTaskAssignment()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT HKTAO_ID,HKTAO_TASK_DATE,HKATO_AUTO
                FROM FLXY_HK_TASKASSIGNMENT_OVERVIEW
                WHERE HKTAO_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTaskAssignment()
    {
        $sysid = $this->request->getPost('sysid');

        try {

            $param = ['SYSID' => $sysid];
            $sql = "SELECT HKATD_ID
                FROM FLXY_HK_ASSIGNED_TASK_DETAILS
                WHERE HKATD_ASSIGNED_TASK_ID=:SYSID: ";

            $response = $this->Db->query($sql, $param)->getNumRows();

            $sql1 = "SELECT HKAT_ID
                FROM FLXY_HK_ASSIGNED_TASKS
                WHERE HKAT_TASK_ID=:SYSID: ";

            $response1 = $this->Db->query($sql, $param)->getNumRows();
            
            if ($response > 0 || $response1 > 0) {
                $result = $this->responseJson("0");
            } else {
                $return = $this->Db->table('FLXY_HK_TASKASSIGNMENT_OVERVIEW')->delete(['HKTAO_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            }



            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

 
    public function attendantList()
       {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $UserID = session()->get('USR_ID');

        $sql = "SELECT USR_ID, CONCAT_WS(' ',USR_FIRST_NAME,USR_LAST_NAME) AS FULL_NAME
                FROM FLXY_USERS WHERE USR_ROLE_ID = '3'";

        if ($search != '') {
            $sql .= " WHERE USR_FIRST_NAME LIKE '%$search%' AND USR_LAST_NAME LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['USR_ID'] . '">' . $row['FULL_NAME'] . '</option>';
            }
        }

        return $option;
    }

    public function showTaskSheets(){

        $UserID = session()->get('USR_ID');
        $mine = new  TaskAssignmentDataTable();
        $tableName = "(SELECT HKAT_ID,HKAT_TASK_ID,USR_FIRST_NAME,USR_LAST_NAME,HKAT_TASK_SHEET_ID,HKAT_ATTENDANT_ID, HKAT_CREDITS,HKAT_INSTRUCTIONS,
		STATS, STATUSCODE, INSP_STATS, SUPER_STATUSCODE, COMPLETION_TIME

        FROM FLXY_HK_ASSIGNED_TASKS INNER JOIN FLXY_USERS ON HKAT_ATTENDANT_ID = USR_ID 
        
        LEFT JOIN (SELECT [HKATD_ASSIGNED_TASK_ID], STRING_AGG([HKATD_STATUS_ID], ',') AS STATS, STRING_AGG(STATUS_CODE, ',') AS STATUSCODE 
		
		FROM (
        
        SELECT [HKATD_ASSIGNED_TASK_ID], [HKATD_STATUS_ID], STATUS_CODE FROM [FLXY_HK_ASSIGNED_TASK_DETAILS] 
		
		INNER JOIN FLXY_TASK_STATUS_MASTER ON STATUS_ID = HKATD_STATUS_ID GROUP BY HKATD_ASSIGNED_TASK_ID, HKATD_STATUS_ID, STATUS_CODE)
        
        TSKDET GROUP BY HKATD_ASSIGNED_TASK_ID)
		
		TASK_PROG_NUM ON TASK_PROG_NUM.HKATD_ASSIGNED_TASK_ID = HKAT_ID 


		LEFT JOIN (SELECT [HKATD_ASSIGNED_TASK_ID], STRING_AGG([HKATD_INSPECTED_STATUS_ID], ',') AS INSP_STATS, STRING_AGG(STATUS_CODE, ',') AS SUPER_STATUSCODE 
		
		FROM (
        
        SELECT [HKATD_ASSIGNED_TASK_ID], [HKATD_INSPECTED_STATUS_ID], STATUS_CODE FROM [FLXY_HK_ASSIGNED_TASK_DETAILS] 
		
		INNER JOIN FLXY_TASK_STATUS_MASTER ON STATUS_ID = HKATD_INSPECTED_STATUS_ID 
		
		GROUP BY HKATD_ASSIGNED_TASK_ID, HKATD_INSPECTED_STATUS_ID, STATUS_CODE)
        
        TSKDET GROUP BY HKATD_ASSIGNED_TASK_ID)

		
		SUPERTASK_PROG_NUM ON SUPERTASK_PROG_NUM.HKATD_ASSIGNED_TASK_ID = HKAT_ID


		
		LEFT JOIN (SELECT [HKATD_ASSIGNED_TASK_ID],COMPLETION_TIME
		
		FROM (
        
        SELECT [HKATD_ASSIGNED_TASK_ID],MAX(HKATD_COMPLETION_TIME) AS COMPLETION_TIME FROM [FLXY_HK_ASSIGNED_TASK_DETAILS] 
		
		GROUP BY HKATD_ASSIGNED_TASK_ID)
        
        TSKDET GROUP BY HKATD_ASSIGNED_TASK_ID,COMPLETION_TIME)
		
		SUPERTASK_PROG_TIME ON SUPERTASK_PROG_TIME.HKATD_ASSIGNED_TASK_ID = HKAT_ID

        
        WHERE 1=1 
        
       ) as output";

        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_ID']) && $data['HKTAO_ID'] != ''){            
            $init_cond['HKAT_TASK_ID = '] = "'".$data['HKTAO_ID']."'";
        }
       // echo  $init_cond['HKAT_TASK_ID = '];exit;

        $columns = "HKAT_ID,HKAT_TASK_ID,USR_FIRST_NAME,USR_LAST_NAME,HKAT_TASK_SHEET_ID,HKAT_ATTENDANT_ID,HKAT_CREDITS,HKAT_INSTRUCTIONS,STATS,STATUSCODE,INSP_STATS,SUPER_STATUSCODE,COMPLETION_TIME";
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;

    }

    public function getLastSheetNo($HKAT_TASK_ID = 0, $output = 0){
        $sheetno = 1;
        $HKAT_TASK_ID = ($HKAT_TASK_ID > 0) ? $HKAT_TASK_ID : $this->request->getPost('HKAT_TASK_ID');
       
        $sql = "SELECT TOP 1 HKAT_TASK_SHEET_ID
                FROM FLXY_HK_ASSIGNED_TASKS WHERE HKAT_TASK_ID = '$HKAT_TASK_ID' ORDER BY HKAT_TASK_SHEET_ID DESC";

        $response = $this->Db->query($sql)->getResultArray();
        if(!empty($response[0])){
            $sheetno =  $response[0]['HKAT_TASK_SHEET_ID'];   
            if($output == 1)
            return ++$sheetno; 
            else
            echo ++$sheetno;          
        }
        else if($output == 1)
        return $sheetno; 
        else
        echo $sheetno;  
        
        
    }

    public function insertTaskAssignmentSheet()
    {
        try {
            $user_id                 = session()->get('USR_ID');
            $TASK_ID                 = $this->request->getPost('task_id');
            $HKAT_TASK_ID            = $this->request->getPost('overview_id');
            $HKAT_TASK_SHEET_ID      = $this->request->getPost('tasksheet_no');
            $HKAT_ATTENDANT_ID       = $this->request->getPost('attendant_id');
            $HKAT_TASK_DATE          = $this->request->getPost('task_date');
            $HKAT_SHEET_INSTRUCTIONS = $this->request->getPost('instructions');

            $validate = $this->validate([
                'attendant_id' => ['label' => 'Attendant', 'rules' => 'required|taskSheetExists[HKAT_TASK_ID,HKAT_TASK_SHEET_ID]', 'errors' => ['taskSheetExists' => 'Task sheet for the attendant is already assigned to this date']],               
            ]);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
            $data = [
                "HKAT_TASK_ID"        => trim($HKAT_TASK_ID),
                "HKAT_TASK_SHEET_ID"  => trim($HKAT_TASK_SHEET_ID),
                "HKAT_ATTENDANT_ID"   => trim($HKAT_ATTENDANT_ID),
                "HKAT_CREDITS"        => 0,
                "HKAT_INSTRUCTIONS"   => trim($HKAT_SHEET_INSTRUCTIONS), 
                "HKAT_CREATED_AT"     => date("Y-m-d H:i:s A"),
                "HKAT_UPDATED_BY"     => $user_id,
                
            ];   
            
                $sql = "SELECT HKST_ID
                FROM FLXY_HK_SUBTASKS WHERE HKST_TASK_ID = '$TASK_ID' ORDER BY HKST_ID asc";

                $response = $this->Db->query($sql)->getResultArray();
                if(!empty($response)){               
                
                $return = !empty($sysid) ? $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->where('HKAT_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->insert($data);
                
                $HKAT_ID = $this->Db->insertID();           
                
                $SHEET_NO_OVERVIEW = $SHEET_NO = $this->getLastSheetNo($HKAT_TASK_ID, 1);
                $this->Db->table('FLXY_HK_TASKASSIGNMENT_OVERVIEW')->where('HKTAO_ID', $HKAT_TASK_ID)->update(['HKATO_TOTAL_SHEETS'=>(--$SHEET_NO_OVERVIEW)]);
                $result = $return ? $this->responseJson("1", "0", $return, $response = $SHEET_NO) : $this->responseJson("-444", "db insert not successful", $return);
            }
            else{
                $result = $this->responseJson("-1", "The task has no subtasks");
            }


            
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    

    public function deleteTaskAssignmentSheet()
    {
        $tasksheet_id = $this->request->getPost('tasksheet_id');
        $task_id      = $this->request->getPost('task_id');
        $sys_id       = $this->request->getPost('sys_id');

        try {

            $param = ['SYSID' => $sys_id];
            $sql = "SELECT HKAT_TASK_SHEET_ID
                FROM FLXY_HK_ASSIGNED_TASKS
                WHERE HKAT_ID=:SYSID: ";

            $response = $this->Db->query($sql, $param)->getNumRows();

            $sql1 = "DELETE 
                FROM FLXY_HK_TASK_ASSIGNED_ROOMS
                WHERE HKARM_TASK_ID= '$sys_id' AND HKARM_TASK_SHEET_ID = '$tasksheet_id'";

            $response1 = $this->Db->query($sql1);

            $sql2 = "DELETE 
                FROM FLXY_HK_ASSIGNED_TASK_DETAILS
                WHERE HKATD_ASSIGNED_TASK_ID= '$sys_id' ";

            $response2 = $this->Db->query($sql2);           
            
           
            $return = $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->delete(['HKAT_ID' => $sys_id]);
            $SHEET_NO = $this->getLastSheetNo($task_id, 1);
            $result = $return ? $this->responseJson("1", "0", $return, $response = $SHEET_NO) : $this->responseJson("-402", "Record not deleted");

            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function showTaskAssignedRooms(){
        $UserID = session()->get('USR_ID');
        $mine   = new  TaskAssignmentDataTable();
        $tableName = "FLXY_HK_TASK_ASSIGNED_ROOMS INNER JOIN FLXY_ROOM ON RM_ID = HKARM_ROOM_ID";
        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_ID']) && $data['HKTAO_ID'] != ''){            
            $init_cond['HKARM_TASK_ID = '] = "'".$data['HKTAO_ID']."'";
        }
        if(isset($data['HKARM_TASK_SHEET_ID']) && $data['HKARM_TASK_SHEET_ID'] != ''){            
            $init_cond['HKARM_TASK_SHEET_ID = '] = "'".$data['HKARM_TASK_SHEET_ID']."'";
        }

        $columns = "HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC";
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function viewTaskAssignedRooms(){
        $UserID = session()->get('USR_ID');
        $mine   = new  TaskAssignmentDataTable();
        $tableName = "(SELECT HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKATD_ASSIGNED_TASK_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC,MAX(HKATD_COMPLETION_TIME) as HKATD_COMPLETION_TIME,CONCAT_WS(' ', USR_FIRST_NAME,USR_LAST_NAME) AS INSPECTED_NAME,MAX(HKATD_INSPECTED_DATETIME) as HKATD_INSPECTED_DATETIME  FROM FLXY_HK_TASK_ASSIGNED_ROOMS INNER JOIN FLXY_ROOM ON RM_ID = HKARM_ROOM_ID LEFT JOIN FLXY_HK_ASSIGNED_TASK_DETAILS ON HKARM_ROOM_ID = HKATD_ROOM_ID LEFT JOIN FLXY_USERS ON HKATD_INSPECTED_BY = USR_ID GROUP BY HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKATD_ASSIGNED_TASK_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC,HKATD_INSPECTED_DATETIME,CONCAT_WS(' ', USR_FIRST_NAME,USR_LAST_NAME)) AS OUTPUT";
        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_ID']) && $data['HKTAO_ID'] != ''){            
            $init_cond['HKARM_TASK_ID = '] = "'".$data['HKTAO_ID']."'";
        }
        if(isset($data['HKARM_TASK_SHEET_ID']) && $data['HKARM_TASK_SHEET_ID'] != ''){            
            $init_cond['HKARM_TASK_SHEET_ID = '] = "'".$data['HKARM_TASK_SHEET_ID']."'";
        }

        $columns = "HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKATD_ASSIGNED_TASK_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC,HKATD_COMPLETION_TIME,INSPECTED_NAME,HKATD_INSPECTED_DATETIME";
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function taskSheetList(){
        $task_id = $this->request->getPost("task_id");

        $sql = "SELECT HKAT_TASK_SHEET_ID FROM FLXY_HK_ASSIGNED_TASKS WHERE 1 = 1"; 
        
     
        if(!empty($task_id))
            $sql .= " AND HKAT_TASK_ID IN (".$task_id.")";

        $response = $this->Db->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Sheet</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['HKAT_TASK_SHEET_ID'].'">'.$row['HKAT_TASK_SHEET_ID'].'</option>';
            }
        }
        else
            $option='<option value="">No Sheets</option>';

        echo $option;
    }


    public function insertTaskAssignmentRoom()
    {
        try {
            $user_id = session()->get('USR_ID');
            $TASK_ID              = $this->request->getPost('TASK_ID');
            $HKAT_TASK_ID         = $this->request->getPost('HKAT_TASK_ID');
            $HKARM_TASK_SHEET_ID  = $this->request->getPost('HKARM_TASK_SHEET_ID');
            $HKARM_ROOM_ID        = $this->request->getPost('HKARM_ROOM_ID');
            $HKARM_CREDITS        = $this->request->getPost('HKARM_CREDITS');
            $HKARM_INSTRUCTIONS   = $this->request->getPost('HKARM_INSTRUCTIONS');

            $validate = $this->validate([                
                'HKARM_ROOM_ID' => ['label' => 'Room', 'rules' => 'required']               
            ]);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
            $data = [
                "HKARM_TASK_ID"        => trim($HKAT_TASK_ID),
                "HKARM_TASK_SHEET_ID"  => trim($HKARM_TASK_SHEET_ID),
                "HKARM_ROOM_ID"        => trim($HKARM_ROOM_ID),
                "HKARM_CREDITS"        => $HKARM_CREDITS, 
                "HKARM_INSTRUCTIONS"   => trim($HKARM_INSTRUCTIONS),
                "HKARM_CREATED_AT"     => date("Y-m-d H:i:s A"),
                "HKARM_CREATED_BY"     => $user_id,
            ];


            //// get Task Assigned id 
            $cond = "HKAT_TASK_SHEET_ID = '".trim($HKARM_TASK_SHEET_ID)."' AND HKAT_TASK_ID = '".trim($HKAT_TASK_ID)."'";
            $HKATD_ASSIGNED_TASK_ID = getValueFromTable('HKAT_ID',$cond,'FLXY_HK_ASSIGNED_TASKS');

            $sql = "SELECT HKST_ID
            FROM FLXY_HK_SUBTASKS WHERE HKST_TASK_ID = '$TASK_ID' ORDER BY HKST_ID asc";

            $response = $this->Db->query($sql)->getResultArray();
            if(!empty($response)){                
                foreach($response as $row){
                    $task_details = [
                        "HKATD_ASSIGNED_TASK_ID"  => $HKATD_ASSIGNED_TASK_ID,
                        "HKATD_ROOM_ID"           => trim($HKARM_ROOM_ID),
                        "HKATD_SUBTASK_ID"        => trim($row['HKST_ID']),
                        "HKATD_CREATED_AT"        => date("Y-m-d H:i:s A"),
                        "HKATD_CREATED_BY"        => $user_id,                        
                    ];

                    $this->Db->table('FLXY_HK_ASSIGNED_TASK_DETAILS')->insert($task_details);                  

                }
            }
            
            $return = !empty($sysid) ? $this->Db->table('FLXY_HK_TASK_ASSIGNED_ROOMS')->where('HKARM_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_TASK_ASSIGNED_ROOMS')->insert($data);

            $this->totalCredits($HKAT_TASK_ID);
            $this->totalRooms($HKAT_TASK_ID);

            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    

    public function deleteTaskAssignmentRoom()
    {
        $taskroom_id = $this->request->getPost('HKARM_ID');
        $task_id = $this->request->getPost('HKAT_TASK_ID');

        try {
                $return = $this->Db->table('FLXY_HK_TASK_ASSIGNED_ROOMS')->delete(['HKARM_ID' => $taskroom_id]);             
                $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-402", "Record not deleted");
                $this->totalCredits($task_id);
                echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function totalCredits($HKAT_TASK_ID){
        $sql= "UPDATE FLXY_HK_TASKASSIGNMENT_OVERVIEW SET HKATO_TOTAL_CREDIT = (select sum(HKARM_CREDITS) as total from FLXY_HK_TASK_ASSIGNED_ROOMS  WHERE HKARM_TASK_ID = '$HKAT_TASK_ID' group by HKARM_TASK_ID) WHERE HKTAO_ID = '$HKAT_TASK_ID'";
        $update = $this->Db->query($sql);
    }

    public function totalRooms($HKAT_TASK_ID){
        $sql= "UPDATE FLXY_HK_TASKASSIGNMENT_OVERVIEW SET HKATO_TOTAL_ROOMS = (select COUNT(HKARM_ID) as total from FLXY_HK_TASK_ASSIGNED_ROOMS  WHERE HKARM_TASK_ID = '$HKAT_TASK_ID' group by HKARM_TASK_ID) WHERE HKTAO_ID = '$HKAT_TASK_ID'";
        $update = $this->Db->query($sql);
    }
    
    public function taskRoomList(){

        $sql = "SELECT RM_ID, RM_NO, RM_DESC, RM_STATUS_COLOR_CLASS,RM_STATUS_CODE,RM_STATUS_ID FROM FLXY_ROOM LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
        FROM FLXY_ROOM_STATUS_LOG
        GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS WHERE 1=1"; 
      
        $response = $this->Db->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Room</option>';
            foreach($response as $row){   
                if($row['RM_STATUS_ID'] == 1 || $row['RM_STATUS_ID'] == 3 )     
                 continue;      
                $RM_STATUS_CODE = (NULL == $row['RM_STATUS_CODE']) ? 'Dirty' :$row['RM_STATUS_CODE'];
                $option.= '<option value="'.$row['RM_ID'].'" data-room-id="'.$row['RM_ID'].'"  data-icon="bx bxl-instagram">'.$row['RM_NO'].' - '.$RM_STATUS_CODE.'</option>';
            }
        }
        else
            $option='<option value="">No Rooms</option>';

        echo $option;
    }

    public function updateTaskStatus()
    {
        try {

            $role = $this->request->getPost('role');
            $taskId = $this->request->getPost('taskId');
            $new_status = $this->request->getPost('new_status');
            $user_id = session()->get('USR_ID');
            $HKATD_COMPLETION_TIME = null;
            if($role == 3)
            {   
                    
                 $HKATD_COMPLETION_TIME = ($new_status == 2) ? date("Y-m-d H:i:s"): '';                

                $logdata = [
                    "HKATD_STATUS_ID" => $new_status,
                    "HKATD_UPDATED_BY" => $user_id, "HKATD_UPDATED_AT" => date("Y-m-d H:i:s"), "HKATD_COMPLETION_TIME" => $HKATD_COMPLETION_TIME
                ];
            }
            else if($role == 5){
                $logdata = [
                    "HKATD_INSPECTED_STATUS_ID" => $new_status, "HKATD_INSPECTED_BY" => $user_id,
                    "HKATD_UPDATED_BY" => $user_id, "HKATD_UPDATED_AT" => date("Y-m-d H:i:s"),"HKATD_INSPECTED_DATETIME"=> date("Y-m-d H:i:s")
                ];
            }

            $return = $this->Db->table('FLXY_HK_ASSIGNED_TASK_DETAILS')->where(['HKATD_ASSIGNED_TASK_ID'=>$taskId])->update($logdata);

            echo json_encode($this->responseJson("1", "0", $return, $response = $HKATD_COMPLETION_TIME));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful"));
        }
    }


    public function setTaskSheet(){
        $_SESSION['task_overview_id']    = $this->request->getPost('task_overview_id');
        $_SESSION['task_overview_date']  = $this->request->getPost('task_overview_date');
        $_SESSION['task_assigned_id']       = $this->request->getPost('task_assigned_id');       
        echo '1';       
    }

    public function printTaskSheet(){          
                    
        $dompdf  = new \Dompdf\Dompdf(); 
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $options->setDefaultFont('Courier');
        $dompdf  = new \Dompdf\Dompdf($options);

        $task_overview_date = $_SESSION["task_overview_date"];
        $task_assigned_id = $_SESSION["task_assigned_id"];


        $header_sql = "SELECT HKTAO_TASK_DATE,HKT_CODE,HKAT_TASK_SHEET_ID,CONCAT_WS(' ', USR_FIRST_NAME,USR_LAST_NAME) AS ATTENDANT_NAME,USR_ID, HKAT_INSTRUCTIONS

        FROM FLXY_HK_TASKASSIGNMENT_OVERVIEW INNER JOIN FLXY_HK_ASSIGNED_TASKS ON HKAT_TASK_ID = HKTAO_ID 
                LEFT JOIN FLXY_HK_TASKS ON HKT_ID = HKATO_TASK_CODE
                LEFT JOIN FLXY_USERS ON HKAT_ATTENDANT_ID = USR_ID WHERE HKAT_ID ='$task_assigned_id'";

        $header_response = $this->Db->query($header_sql)->getResultArray();        

        if(!empty($header_response)){
            foreach ($header_response as $row) {
                $data = ['HKTAO_TASK_DATE'=> $row['HKTAO_TASK_DATE'], 'HKT_CODE'=> $row['HKT_CODE'], 'HKAT_TASK_SHEET_ID'=> $row['HKAT_TASK_SHEET_ID'], 'ATTENDANT_NAME'=> $row['ATTENDANT_NAME'], 'HKAT_INSTRUCTIONS'=> $row['HKAT_INSTRUCTIONS'],'USR_ID'=> $row['USR_ID']];
            } 
        }


        $content_sql = "SELECT RM_NO,RM_TYPE,RVN.RESV_ID,FULLNAME,RVN.RESV_ARRIVAL_DT,RVN.RESV_DEPARTURE,
        ISNULL(SM.RM_STATUS_ID, 2) AS RM_STATUS_ID,ISNULL(SM.RM_STATUS_CODE, 'Dirty') AS RM_STATUS_CODE,
        ISNULL(RVN.RESV_STATUS, 'Not Reserved') AS RESV_STATUS,
        (CASE
            WHEN RVN.RESV_STATUS IN ('Due Pre Check-In','Pre Checked-In','Checked-Out') 
                 OR RVN.RESV_STATUS IS NULL  THEN 'VAC'
            ELSE 'OCC'
         END) AS FO_STATUS                        
        
        FROM FLXY_ROOM RM

        LEFT JOIN FLXY_HK_ASSIGNED_TASK_DETAILS ON HKATD_ROOM_ID = RM_ID 


        LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                    FROM FLXY_ROOM_STATUS_LOG
                    GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS

        LEFT JOIN ( SELECT MAX(RESV_ID) AS RESV_MAX_ID, RESV_ROOM_ID AS RESV_ROOM, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME
                    FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID
                    WHERE RESV_ARRIVAL_DT = '$task_overview_date' 
                    AND RESV_STATUS NOT IN ('Cancelled')
                    GROUP BY RESV_ROOM_ID,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) ) RESV ON RESV.RESV_ROOM = RM.RM_ID
        LEFT JOIN FLXY_RESERVATION RVN ON RVN.RESV_ID = RESV.RESV_MAX_ID

        WHERE HKATD_ASSIGNED_TASK_ID ='$task_assigned_id'

        GROUP BY HKATD_ROOM_ID,RM_NO,RM_TYPE,RVN.RESV_ID,FULLNAME,RVN.RESV_ARRIVAL_DT,RVN.RESV_DEPARTURE,RESV_STATUS,RM_STATUS_ID,RM_STATUS_CODE";

        $content_response = $this->Db->query($content_sql)->getResultArray();
        $content_responseCount = $this->Db->query($content_sql)->getNumRows();       
      
        $NO_OF_ROOMS = $content_responseCount;
        $TABLE_CONTENTS = '';
        $FIXED_CONTENTS = '';             
        $fixed_rows = 0;
        $DEFAULT_MODE = 20;
        $DEFAULT_ROWS = 0;
        $j = 0;
        
        if(!empty($content_response)){
            foreach ($content_response as $row) {
                $RESV_STATUS = ($row['RESV_STATUS'] == 'Not Reserved')? '' : $row['RESV_STATUS'];
                $DEFAULT_PAGE_BREAK = '<tr></tr><div style="margin-top:220px;margin-bottom:5px; page-break-after:always"></div></tr>';
            
                $TABLE_CONTENTS.= '<tr >
                <th width="14%;" style="text-align:center" class="text-center">'.$row['RM_NO'].'</td>
                <th width="10%;" style="text-align:center" class="text-center">'.$row['RM_TYPE'].'</td>
                <th width="15%;" style="text-align:center" class="text-center">'.$row['RM_STATUS_CODE'].'</td>
                <th width="10%;" style="text-align:center" class="text-center">'.$row['FO_STATUS'].'</td>
                <th width="15%;" style="text-align:center" class="text-center">'.$RESV_STATUS.'</td>
                <th width="10%;" style="text-align:center" class="text-center">'.$row['FULLNAME'].'</td>
                <th width="15%;" style="text-align:center" class="text-center">'.$row['RESV_ARRIVAL_DT'].'</td>
                <th width="10%;" style="text-align:center" class="text-center">'.$row['RESV_DEPARTURE'].'</td>                        
                    </tr>';
                $DEFAULT_ROWS++;
                if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                    $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                }  
            }
        }
      

       
        $data['CONTENT'] = $TABLE_CONTENTS;         
        $dompdf->loadHtml(view('TaskAssignment/TaskSheet',$data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();           
        $canvas = $dompdf->getCanvas();
        $canvas->page_text(18, 780, "{PAGE_NUM} / {PAGE_COUNT}", '', 6, array(0,0,0));
        
        $dompdf->stream("TaskSheet_".$data['HKAT_TASK_SHEET_ID']."_".$data['HKTAO_TASK_DATE'].".pdf", array("Attachment" => 0));
       
    }


    public function getTaskComments(){   
        $comments  = null; 
        $HKAT_TASK_ASSIGNED_ID  = $this->request->getPost('HKAT_TASK_ASSIGNED_ID');
        $HKAT_ROOM_ID           = $this->request->getPost('HKAT_ROOM_ID');  

        $sql = "SELECT ATN_NOTE,ATN_CREATED_AT,CONCAT_WS(' ', USR_FIRST_NAME,USR_LAST_NAME) AS USER_NAME
        FROM FLXY_HK_ASSIGNED_TASK_NOTES LEFT JOIN FLXY_USERS ON ATN_USER_ID = USR_ID WHERE ATN_ASSIGNED_TASK_ID ='$HKAT_TASK_ASSIGNED_ID' AND ATN_ROOM_ID = '$HKAT_ROOM_ID'";
        
        $comments = $this->Db->query($sql)->getResultArray();
        return $this->respond(responseJson(200, false, ['msg' => 'comments'], $comments));
    }

}