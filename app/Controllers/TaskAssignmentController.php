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

 
    public function attendeeList()
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
                'attendant_id' => ['label' => 'Attendant', 'rules' => 'required|taskSheetExists[HKAT_TASK_ID,HKAT_TASK_SHEET_ID]', 'errors' => ['taskSheetExists' => 'Task sheet for the attendee is already assigned to this date']],               
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
        $tableName = "(SELECT HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC FROM FLXY_HK_TASK_ASSIGNED_ROOMS INNER JOIN FLXY_ROOM ON RM_ID = HKARM_ROOM_ID INNER JOIN FLXY_HK_ASSIGNED_TASK_DETAILS ON HKARM_ROOM_ID = HKATD_ROOM_ID GROUP BY HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS,RM_NO,RM_DESC) AS OUTPUT";
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
            $cond = "HKAT_TASK_SHEET_ID = '".trim($HKARM_TASK_SHEET_ID)."'";
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

        $sql = "SELECT RM_ID, RM_NO, RM_DESC, RM_STATUS_COLOR_CLASS,RM_STATUS_CODE FROM FLXY_ROOM LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
        FROM FLXY_ROOM_STATUS_LOG
        GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS  WHERE 1 = 1"; 
      
        $response = $this->Db->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Room</option>';
            foreach($response as $row){
              
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

    public function printTaskSheet(){          
                    
        $dompdf = new \Dompdf\Dompdf(); 
        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);
        $options->setDefaultFont('Courier');
        $dompdf = new \Dompdf\Dompdf($options);

        $sql = "SELECT CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, CUST_EMAIL, cname, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NO, RESV_RATE FROM FLXY_RESERVATION LEFT JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID LEFT JOIN COUNTRY ON iso2 = CUST_COUNTRY WHERE RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
        $response = $this->Db->query($sql)->getResultArray();

        if(!empty($response)){
            foreach ($response as $row) {
                $data = ['CUST_NAME'=> $row['FULLNAME'], 'CUST_COUNTRY'=> $row['cname'], 'RESV_ARRIVAL_DT'=> $row['RESV_ARRIVAL_DT'], 'RESV_DEPARTURE'=> $row['RESV_DEPARTURE'], 'RESV_NO'=> $row['RESV_NO'], 'RESV_RATE'=> $row['RESV_RATE']];
                $RESV_ARRIVAL_DT = $row['RESV_ARRIVAL_DT'];
                $RESV_DEPARTURE = $row['RESV_DEPARTURE'];
                $RESV_RATE = $row['RESV_RATE'];
                $CUST_EMAIL = $row['CUST_EMAIL'];
            } 
        }

        $sql = "SELECT * FROM FLXY_FIXED_CHARGES INNER JOIN FLXY_TRANSACTION_CODE ON TR_CD_ID = FIXD_CHRG_TRNCODE WHERE FIXD_CHRG_RESV_ID = ".$_SESSION['PROFORMA_RESV_ID'];  
        $fixedChargesResponse = $this->Db->query($sql)->getResultArray();

        $RESV_ARRIVAL_DATE    = strtotime($RESV_ARRIVAL_DT);
        $RESV_DEPARTURE_DATE  = strtotime($RESV_DEPARTURE); 
        $datediff = $RESV_DEPARTURE_DATE - $RESV_ARRIVAL_DATE;
        $RESERV_DAYS = round($datediff / (60 * 60 * 24));
        $VAT = 0.05;
        $TABLE_CONTENTS = '';
        $FIXED_CONTENTS = '';             
        $fixed_rows = 0;
        $DEFAULT_MODE = 20;
        $DEFAULT_ROWS = 0;
        $ROOM_CHARGE_TOTAL = 0;
        $VAT_TOTAL = 0;
        $j = 0;
        $fixedChargesAMOUNT = $fixedChargesVAT = $fixedChargesTotal = $fixedChargesVATTotal = $TOTAL = $TOTALVAT = 0;
        
        for($i = 1; $i <= $RESERV_DAYS; $i++ ){
            $ROOM_CHARGE_TOTAL += $RESV_RATE;
            $VAT_TOTAL += ($RESV_RATE * $VAT);
            $DEFAULT_VAT = $RESV_RATE * $VAT;
            $DEFAULT_PAGE_BREAK = '<tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>';
            $sCurrentDate = gmdate("d-m-Y", strtotime("+$i day", $RESV_ARRIVAL_DATE)); 
            $CurrentDate  = strtotime($sCurrentDate); 
            $sCurrentDay  = gmdate("w", strtotime("+{$i} day", $RESV_ARRIVAL_DATE));
            $sCurrentD    = gmdate("d", strtotime("+{$i} day", $RESV_ARRIVAL_DATE)); 
            $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
                    <td class="text-center" >'.$sCurrentDate.'</td>
                    <td class="text-center">Room Charge </td>
                    <td class="text-center"></td>
                    <td class="text-left" >'.round($RESV_RATE,2).' </td>
                    <td class="text-left">0.00</td>
                </tr>';
            $DEFAULT_ROWS++;
            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
            }   

            $TABLE_CONTENTS.= '
            <tr class="mt-5 mb-5">
                <td class="text-center">'.$sCurrentDate.'</td>
                <td class="text-center">VAT 5%</td>
                <td class="text-center"></td>
                <td class="text-left">'.round($DEFAULT_VAT).' </td>
                <td class="text-left">0.00</td>
            </tr>';
            $DEFAULT_ROWS++;

            if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
            } 

            ////////// Fixed Charges //////////////               
            if(!empty($fixedChargesResponse)){
                foreach($fixedChargesResponse as $fixedCharges) {
                     
                    $FIXD_CHRG_BEGIN_DATE =gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_BEGIN_DATE'])) );
                    $FIXD_CHRG_END_DATE = gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_END_DATE'])));
                    $FIXD_CHRG_FREQUENCY = $fixedCharges['FIXD_CHRG_FREQUENCY'];


                    $FIXD_CHRG_BEGIN_DATE = strtotime($FIXD_CHRG_BEGIN_DATE);
                    $FIXD_CHRG_END_DATE = strtotime($FIXD_CHRG_END_DATE);

                    if($FIXD_CHRG_FREQUENCY == 1 && $CurrentDate == $FIXD_CHRG_BEGIN_DATE){
                        $ONCE = 1;
                        $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        $fixedChargesTotal += $fixedChargesAMOUNT; 
                        $fixedChargesVATTotal += $fixedChargesVAT;

                        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        <td class="text-center">'.$sCurrentDate.'</td>
                        <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        <td class="text-center"></td>
                        <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        <td width="10%;" class="text-left">0.00</td>
                        </tr>';

                        $DEFAULT_ROWS++;
                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        }   

                        $TABLE_CONTENTS.= '
                        <tr class="mt-5 mb-5">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">VAT 5%</td>
                            <td class="text-center"></td>
                            
                            <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                        </tr>';
                        $DEFAULT_ROWS++;

                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        } 
                    }

                   
                     if($FIXD_CHRG_FREQUENCY == 2 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE)){
                       
                        $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        $fixedChargesTotal += $fixedChargesAMOUNT; 
                        $fixedChargesVATTotal += $fixedChargesVAT;

                        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        <td class="text-center">'.$sCurrentDate.'</td>
                        <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        <td class="text-center"></td>
                        <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        <td width="10%;" class="text-left">0.00</td>
                        </tr>';

                        $DEFAULT_ROWS++;
                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        }   

                        $TABLE_CONTENTS.= '
                        <tr class="mt-5 mb-5">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">VAT 5%</td>
                            <td class="text-center"></td>
                            
                            <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                        </tr>';
                        $DEFAULT_ROWS++;

                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        } 
                    } 
                    
                    
                    else if($FIXD_CHRG_FREQUENCY == 3 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDay == $fixedCharges['FIXD_CHRG_WEEKLY'])){
                        $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        $fixedChargesTotal += $fixedChargesAMOUNT; 
                        $fixedChargesVATTotal += $fixedChargesVAT;

                        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        <td class="text-center">'.$sCurrentDate.'</td>
                        <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        <td class="text-center"></td>
                        <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        <td width="10%;" class="text-left">0.00</td>
                        </tr>';

                        $DEFAULT_ROWS++;
                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        }   

                        $TABLE_CONTENTS.= '
                        <tr class="mt-5 mb-5">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">VAT 5%</td>
                            <td class="text-center"></td>
                            
                            <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                        </tr>';
                        $DEFAULT_ROWS++;

                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        } 
                    }   

                    else if($FIXD_CHRG_FREQUENCY == 4 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentD == $fixedCharges['FIXD_CHRG_MONTHLY'])){
                        $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        $fixedChargesTotal += $fixedChargesAMOUNT; 
                        $fixedChargesVATTotal += $fixedChargesVAT;

                        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        <td class="text-center">'.$sCurrentDate.'</td>
                        <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        <td class="text-center"></td>
                        <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        <td width="10%;" class="text-left">0.00</td>
                        </tr>';

                        $DEFAULT_ROWS++;
                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        }   

                        $TABLE_CONTENTS.= '
                        <tr class="mt-5 mb-5">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">VAT 5%</td>
                            <td class="text-center"></td>
                            
                            <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                        </tr>';
                        $DEFAULT_ROWS++;

                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        } 
                    }  

        
                    else if($FIXD_CHRG_FREQUENCY == 6 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && (date('d-m',$sCurrentDate) == date('d-m',$fixedCharges['FIXD_CHRG_YEARLY']))){
                        $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                        $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                        $fixedChargesTotal += $fixedChargesAMOUNT; 
                        $fixedChargesVATTotal += $fixedChargesVAT;

                        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5 ">
                        <td class="text-center">'.$sCurrentDate.'</td>
                        <td class="text-center">'.$fixedCharges['TR_CD_DESC'].' </td>
                        <td class="text-center"></td>
                        <td width="10%;" class="text-left">'.round($fixedChargesAMOUNT,2).' </td>
                        <td width="10%;" class="text-left">0.00</td>
                        </tr>';

                        $DEFAULT_ROWS++;
                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
                        }   

                        $TABLE_CONTENTS.= '
                        <tr class="mt-5 mb-5">
                            <td class="text-center">'.$sCurrentDate.'</td>
                            <td class="text-center">VAT 5%</td>
                            <td class="text-center"></td>
                            
                            <td width="10%;" class="text-left">'.round($fixedChargesVAT,2).' </td>
                            <td width="10%;" class="text-left">0.00</td>
                        </tr>';
                        $DEFAULT_ROWS++;

                        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                        
                            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK;   
                        } 
                    }  


                }
               //exit;
            }
                 
        }


        $TOTAL = $ROOM_CHARGE_TOTAL + $fixedChargesTotal;
        $TOTALVAT = $VAT_TOTAL + $fixedChargesVATTotal;

        ////////////// Footer //////////////////

        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td colspan="5"><hr></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }   

        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td class="text-center"></td>
        <td></td>
        <td>Total</td>
        <td width="10%;" class="text-center">'.round($TOTAL,2).' </td>
        <td width="10%;" class="text-center">0.00</td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        } 
        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td colspan="5"><hr></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }   

        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td class="text-center"></td>
        <td></td>
        <td>Balance</td>
        <td class="text-center">'.round($TOTAL,2).' </td>
        <td class="text-center">0.00</td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }  
        
        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td class="text-center"></td>
        <td></td>
        <td>VAT Incl. Amount</td>
        <td class="text-center">'.round($TOTAL,2).' </td>
        <td class="text-center">0.00</td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }  
        
        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td class="text-center"></td>
        <td></td>
        <td>5 % VAT</td>
        <td class="text-center">'.round($TOTALVAT,2).' </td>
        <td class="text-center">0.00</td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }  

        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td colspan="5"><hr></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }   
        
        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td class="text-center"></td>
        <td></td>
        <td class="pt-20">Guest Signature</td>
        <td class="text-center"></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }   

        $TABLE_CONTENTS.= '<tr class="mt-5 mb-5">
        <td colspan="5"></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        }   
        $TABLE_CONTENTS.= '<tr class="mt-20 mb-5" >
        <td class="text-center"></td>
        <td></td>
        <td class="mt-20 mb-5 pt-20">Guest Email : '.$CUST_EMAIL.'</td>
        <td class="text-center"></td>
        </tr>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        } 
        $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20" ><p>'.$_SESSION['FOLIO_TXT_ONE'].'
       </p></div>';
        $DEFAULT_ROWS++;
        if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
            $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
        } 

        $TABLE_CONTENTS.= '<div class="mt-5 mb-5 pl-20"><p>'.$_SESSION['FOLIO_TXT_TWO'].'</p></div>';
         $DEFAULT_ROWS++;
         if($DEFAULT_ROWS % $DEFAULT_MODE == 0){                       
             $TABLE_CONTENTS.= $DEFAULT_PAGE_BREAK; 
         } 
        
        $data['CHARGES'] = $TABLE_CONTENTS; 
        $dompdf->loadHtml(view('TaskAssignment/TaskSheet',$data));
        $dompdf->render();           
        $canvas = $dompdf->getCanvas();
        $canvas->page_text(18, 780, "{PAGE_NUM} / {PAGE_COUNT}", '', 6, array(0,0,0));
        $dompdf->stream("TaskSheet_".$_SESSION['PROFORMA_RESV_ID'].".pdf", array("Attachment" => 0));
       
    }

}