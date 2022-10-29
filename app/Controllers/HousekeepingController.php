<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;

class HousekeepingController extends BaseController
{
    public $Db;
    public $request;
    public $session;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'custom', 'common', 'upload']);
    }

   

    /**************      Task Code Functions      ***************/

    public function taskcode(){
        $data['title'] = getMethodName();
        $data['session'] = $this->session;           
        return view('Housekeeping/TaskcodeView', $data);
    }

    public function taskcodeView()
    {
        $mine      = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_HK_TASKS';
        $columns   = 'HKT_ID,HKT_CODE,HKT_DESCRIPTION';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function insertTaskcode()
    {
        try {
            $sysid = $this->request->getPost('HKT_ID');
            $user_id = session()->get('USR_ID');

            $validate = $this->validate([
                'HKT_CODE' => ['label' => 'Task Code', 'rules' => 'required|is_unique[FLXY_HK_TASKS.HKT_CODE,HKT_ID,' . $sysid . ']'],
                'HKT_DESCRIPTION' => ['label' => 'Description', 'rules' => 'required'],         
                
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
                "HKT_CODE" => trim($this->request->getPost('HKT_CODE')),
                "HKT_DESCRIPTION" => trim($this->request->getPost('HKT_DESCRIPTION'))
            ];
            if(empty($sysid)){
                $data["HKT_CREATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKT_CREATED_BY"] = $user_id;               
            }
            else{
                $data["HKT_UPDATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKT_UPDATED_BY"] = $user_id;                
            }

            $return = !empty($sysid) ? $this->Db->table('FLXY_HK_TASKS')->where('HKT_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_TASKS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editTaskcode()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT HKT_ID,HKT_CODE,HKT_DESCRIPTION
                FROM FLXY_HK_TASKS
                WHERE HKT_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTaskcode()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $param = ['SYSID' => $sysid];
            $sql = "SELECT HKST_ID
                FROM FLXY_HK_SUBTASKS
                WHERE HKST_TASK_ID=:SYSID: ";

            $response = $this->Db->query($sql, $param)->getNumRows();
            if($response > 0 )
            {
                $result = $this->responseJson("0");
            }else{
                $return = $this->Db->table('FLXY_HK_TASKS')->delete(['HKT_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            }
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function tasks()
    {
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();  
        
        return view('Housekeeping/TaskView', $data);
    }

    public function tasksView()
    {
        $mine      = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_HK_SUBTASKS INNER JOIN FLXY_HK_TASKS ON HKST_TASK_ID = HKT_ID';
        $columns   = 'HKST_ID,HKT_CODE,HKST_DESCRIPTION';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function taskcodeList(){
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT HKT_ID, HKT_CODE
                FROM FLXY_HK_TASKS";

        if ($search != '') {
            $sql .= " WHERE HKT_CODE LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();
        IF(!empty($response)){
        
            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['HKT_ID'] . '">' . $row['HKT_CODE']  . '</option>';
            }
        }

        echo $option;
    }

    public function insertTask()
    {
        try {
            $sysid = $this->request->getPost('HKST_ID');
            $user_id = session()->get('USR_ID');

            $validate = $this->validate([
                'HKST_DESCRIPTION' => ['label' => 'Task', 'rules' => 'required|is_unique[FLXY_HK_SUBTASKS.HKST_DESCRIPTION,HKST_ID,' . $sysid . ']'],
                'HKST_TASK_ID' => ['label' => 'Task Code', 'rules' => 'required'],         
                
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
                "HKST_TASK_ID" => trim($this->request->getPost('HKST_TASK_ID')),
                "HKST_DESCRIPTION" => trim($this->request->getPost('HKST_DESCRIPTION'))
            ];
            if(empty($sysid)){
                $data["HKST_CREATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKST_CREATED_BY"] = $user_id;               
            }
            else{
                $data["HKST_UPDATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKST_UPDATED_BY"] = $user_id;                
            }

            $return = !empty($sysid) ? $this->Db->table('FLXY_HK_SUBTASKS')->where('HKST_ID', $sysid)->update($data) : $this->Db->table('FLXY_HK_SUBTASKS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editTask()
    {
        $param = ['SYSID' => $this->request->getPost('taskID')];

        $sql = "SELECT HKST_ID,HKST_TASK_ID,HKST_DESCRIPTION
                FROM FLXY_HK_SUBTASKS
                WHERE HKST_ID=:SYSID: ";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteTask()
    {
        $sysid = $this->request->getPost('sysid');

        try {
           
            $param = ['SYSID' => $sysid];
            $sql = "SELECT HKATD_ID
                FROM FLXY_HK_ASSIGNED_TASK_DETAILS
                WHERE HKATD_SUBTASK_ID=:SYSID: ";

            $response = $this->Db->query($sql, $param)->getNumRows();
            if($response > 0 )
            {
                $result = $this->responseJson("0");
            }else{
                $return = $this->Db->table('FLXY_HK_SUBTASKS')->delete(['HKST_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            }


          
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function housekeeping(){
        $data['title'] = getMethodName();
        $data['room_status_list'] = $this->Db->table('FLXY_ROOM_STATUS_MASTER')->select('RM_STATUS_ID,RM_STATUS_CODE,RM_STATUS_COLOR_CLASS')->get()->getResultArray();

        return view('Housekeeping/HKRoomView', $data);
    }

    public function HkRoomView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $TODAYDATE = date('Y-m-d');

        $tableName = "  (SELECT RM_ID,RM_NO,RM_DESC,RM_TYPE,RM_CLASS,RM_FEATURE,RM_FLOOR_PREFERN,
                        ISNULL(SM.RM_STATUS_ID, 2) AS RM_STATUS_ID,ISNULL(SM.RM_STATUS_CODE, 'Dirty') AS RM_STATUS_CODE,
                        ISNULL(RVN.RESV_STATUS, 'Not Reserved') AS RESV_STATUS,
                        (CASE
                            WHEN RVN.RESV_STATUS IN ('Due Pre Check-In','Pre Checked-In','Checked-Out') 
                                 OR RVN.RESV_STATUS IS NULL  THEN 'VAC'
                            ELSE 'OCC'
                         END) AS FO_STATUS                        
                        
                        FROM FLXY_ROOM RM
                        LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                                    FROM FLXY_ROOM_STATUS_LOG
                                    GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
                        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
                        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS

                        LEFT JOIN ( SELECT MAX(RESV_ID) AS RESV_MAX_ID, RESV_ROOM_ID AS RESV_ROOM
                                    FROM FLXY_RESERVATION
                                    WHERE '".$TODAYDATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE
                                    AND RESV_STATUS NOT IN ('Cancelled')
                                    GROUP BY RESV_ROOM_ID ) RESV ON RESV.RESV_ROOM = RM.RM_ID
                        LEFT JOIN FLXY_RESERVATION RVN ON RVN.RESV_ID = RESV.RESV_MAX_ID
                        ) ROOM_STATS";
                        
        $columns = 'RM_ID,RM_NO,RM_DESC,RM_TYPE,RM_CLASS,RM_FEATURE,RM_STATUS_ID,RM_STATUS_CODE,RM_FLOOR_PREFERN,RESV_STATUS,FO_STATUS';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function showFeaturesDesc()
    {
        $comma_list = $this->request->getPost('comma_list');
        echo getFeaturesDesc($comma_list);
    }

    public function updateRoomStatus()
    {
        try {
            $roomId = $this->request->getPost('roomId');
            $new_status = $this->request->getPost('new_status');
            $user_id = session()->get('USR_ID');

            $logdata = ["RM_STAT_ROOM_ID" => $roomId, "RM_STAT_ROOM_STATUS" => $new_status, 
                        "RM_STAT_UPDATED_BY" => $user_id, "RM_STAT_UPDATED"=> date("Y-m-d H:i:s")];
                        
            $return = $this->Db->table('FLXY_ROOM_STATUS_LOG')->insert($logdata);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

}