<?php

namespace App\Controllers;
use App\Libraries\ServerSideDataTable;
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
        $data['js_to_load'] = array("TaskAssignmentFormWizardNumbered.js");
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript']     = blockLoader_javascript();
        return view('TaskAssignment/TaskAssignmentView1', $data);
    }

    public function TaskAssignmentView()
    {

        $UserID = session()->get('USR_ID');
        $mine = new ServerSideDataTable();
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

        $columns = 'HKTAO_ID,HKTAO_TASK_DATE,HKT_CODE,HKT_DESCRIPTION,HKATO_AUTO,HKATO_TOTAL_SHEETS,HKATO_TOTAL_CREDIT,HKATO_TOTAL_ROOMS,HKATO_CREATED_AT,USR_FIRST_NAME,USR_LAST_NAME';
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
                'HKATO_TASK_CODE' => ['label' => 'Task Code', 'rules' => 'required|Taskexists[HKTAO_TASK_DATE,HKATO_TASK_CODE,' . $sysid . ']', 'errors' => ['Taskexists' => 'Task exists in this date']],
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
                "HKTAO_TASK_DATE" => trim($this->request->getPost('HKTAO_TASK_DATE')),
                "HKATO_TASK_CODE"  => trim($this->request->getPost('HKATO_TASK_CODE')),
                "HKATO_AUTO"       => trim($this->request->getPost('HKATO_AUTO')),
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
        $mine = new ServerSideDataTable();
        $tableName = "FLXY_HK_ASSIGNED_TASKS INNER JOIN FLXY_USERS ON HKAT_ATTENDANT_ID = USR_ID";
        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_ID']) && $data['HKTAO_ID'] != ''){            
            $init_cond['HKAT_TASK_ID = '] = "'".$data['HKTAO_ID']."'";
        }
       // echo  $init_cond['HKAT_TASK_ID = '];exit;

        $columns = "HKAT_ID,HKAT_TASK_ID,USR_FIRST_NAME,USR_LAST_NAME,HKAT_TASK_SHEET_ID,HKAT_ATTENDANT_ID,HKAT_CREDITS,HKAT_INSTRUCTIONS";
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
        else 
        echo $sheetno;
        
        
    }

    public function insertTaskAssignmentSheet()
    {
        try {
            $user_id = session()->get('USR_ID');
            $HKAT_TASK_ID            = $this->request->getPost('task_id');
            $HKAT_TASK_SHEET_ID      = $this->request->getPost('tasksheet_no');
            $HKAT_ATTENDANT_ID       = $this->request->getPost('attendant_id');
            $HKAT_SHEET_INSTRUCTIONS = $this->request->getPost('instructions');

            $validate = $this->validate([
                'attendant_id' => ['label' => 'Attendant', 'rules' => 'required']               
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
                "HKAT_ROOM_ID"        => 0
            ];
            
            $return = !empty($sysid) ? $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->where('HKAT_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->insert($data);

            $SHEET_NO = $this->getLastSheetNo($HKAT_TASK_ID, 1);
            $this->Db->table('FLXY_HK_TASKASSIGNMENT_OVERVIEW')->where('HKTAO_ID', $HKAT_TASK_ID)->update(['HKATO_TOTAL_SHEETS'=>(--$SHEET_NO)]);

            $result = $return ? $this->responseJson("1", "0", $return, $response = $SHEET_NO) : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    

    public function deleteTaskAssignmentSheet()
    {
        $tasksheet_id = $this->request->getPost('tasksheet_id');
        $task_id      = $this->request->getPost('task_id');

        try {

            $param = ['SYSID' => $task_id];
            $sql = "SELECT HKAT_TASK_SHEET_ID
                FROM FLXY_HK_ASSIGNED_TASKS
                WHERE HKAT_ID=:SYSID: ";

            $response = $this->Db->query($sql, $param)->getNumRows();

            $sql1 = "SELECT HKAT_ROOM_TASK_SHEET_ID
                FROM FLXY_HK_ASSIGNED_ROOMS
                WHERE HKAT_ROOM_TASK_ID= '$task_id' AND HKAT_ROOM_TASK_SHEET_ID = '$tasksheet_id' ";

            $response1 = $this->Db->query($sql, $param)->getNumRows();
            
            if ($response > 0 || $response1 > 0) {
                $result = $this->responseJson("0");
            } else {
                $return = $this->Db->table('FLXY_HK_ASSIGNED_TASKS')->delete(['HKAT_ID' => $tasksheet_id]);
                $SHEET_NO = $this->getLastSheetNo($task_id, 1);
                $result = $return ? $this->responseJson("1", "0", $return, $response = $SHEET_NO) : $this->responseJson("-402", "Record not deleted");
            }



            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function showTaskAssignedRooms(){
        $UserID = session()->get('USR_ID');
        $mine = new ServerSideDataTable();
        $tableName = "FLXY_HK_TASK_ASSIGNED_ROOMS";
        $init_cond = [];

        $data = $this->request->getPost();
        if(isset($data['HKTAO_ID']) && $data['HKTAO_ID'] != ''){            
            $init_cond['HKARM_TASK_ID = '] = "'".$data['HKTAO_ID']."'";
        }

        $columns = "HKARM_ID,HKARM_TASK_ID,HKARM_TASK_SHEET_ID,HKARM_ROOM_ID,HKARM_CREDITS,HKARM_INSTRUCTIONS";
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
            $HKAT_TASK_ID         = $this->request->getPost('HKAT_TASK_ID');
            $HKARM_TASK_SHEET_ID  = $this->request->getPost('HKARM_TASK_SHEET_ID');
            $HKARM_ROOM_ID        = $this->request->getPost('HKARM_ROOM_ID');
            $HKARM_CREDITS        = $this->request->getPost('HKARM_CREDITS');
            $HKARM_INSTRUCTIONS   = $this->request->getPost('HKARM_INSTRUCTIONS');

            $validate = $this->validate([
                'HKARM_TASK_SHEET_ID' => ['label' => 'Task Sheet', 'rules' => 'required'],
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
                $option.= '<option value="'.$row['RM_NO'].'" data-room-id="'.$row['RM_ID'].'"  data-icon="bx bxl-instagram">'.$row['RM_NO'].' - '.$row['RM_STATUS_CODE'].'</option>';
            }
        }
        else
            $option='<option value="">No Rooms</option>';

        echo $option;
    }
}