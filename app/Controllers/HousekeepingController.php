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

    public function taskcode()
    {
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
            if (empty($sysid)) {
                $data["HKT_CREATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKT_CREATED_BY"] = $user_id;
            } else {
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
            if ($response > 0) {
                $result = $this->responseJson("0");
            } else {
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
        $init_cond = [];
        $tableName = 'FLXY_HK_SUBTASKS INNER JOIN FLXY_HK_TASKS ON HKST_TASK_ID = HKT_ID';
        $columns   = 'HKST_ID,HKT_CODE,HKST_DESCRIPTION';
        if ($this->request->getPost('HKT_ID') != '')
            $init_cond = array("HKST_TASK_ID = " => $this->request->getPost('HKT_ID'));
            $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function allTaskcodeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT HKT_ID, HKT_CODE, HKT_DESCRIPTION
                FROM FLXY_HK_TASKS";

        if ($search != '') {
            $sql .= " WHERE HKT_CODE LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();
        if (!empty($response)) {

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['HKT_ID'] . '">' . $row['HKT_CODE']  . ' - ' . $row['HKT_DESCRIPTION'] . '</option>';
            }
        }

        echo $option;
    }


    public function taskcodeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT HKT_ID, HKT_CODE, HKT_DESCRIPTION
                FROM FLXY_HK_TASKS  WHERE HKT_ID IN (SELECT HKST_TASK_ID FROM FLXY_HK_SUBTASKS) ";

        if ($search != '') {
            $sql .= " WHERE HKT_CODE LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();
        if (!empty($response)) {

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['HKT_ID'] . '">' . $row['HKT_CODE']  . ' - ' . $row['HKT_DESCRIPTION'] . '</option>';
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
            if (empty($sysid)) {
                $data["HKST_CREATED_AT"] = date("Y-m-d H:i:s A");
                $data["HKST_CREATED_BY"] = $user_id;
            } else {
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
        $param = ['SYSID' => $this->request->getPost('sysid')];

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
            if ($response > 0) {
                $result = $this->responseJson("0");
            } else {
                $return = $this->Db->table('FLXY_HK_SUBTASKS')->delete(['HKST_ID' => $sysid]);
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            }



            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function housekeeping()
    {
        $data['title'] = 'Housekeeping - Room Status';
        $data['room_status_list'] = $this->Db->table('FLXY_ROOM_STATUS_MASTER')->select('RM_STATUS_ID,RM_STATUS_CODE,RM_STATUS_COLOR_CLASS')->get()->getResultArray();
        $data['room_class_list'] = $this->Db->table('FLXY_ROOM_CLASS')->select('RM_CL_ID,RM_CL_CODE,RM_CL_DESC')->get()->getResultArray();
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();

        return view('Housekeeping/HKRoomView', $data);
    }

    public function HkRoomView()
    {
        $init_cond = [];
        $TODAYDATE = date('Y-m-d');

        $search_keys = [
            'S_RM_STATUS_ID', 'S_FO_STATUS', 'S_RM_ID', 'RM_FLOOR_PREFERN',
            'S_RM_CLASS', 'S_RM_TYPES', 'S_RM_FEATURES', 'S_RESV_STATUS', 'S_SRV_STATUS'
        ];

        $init_cond = $resv_cond = array();

        if ($search_keys != NULL) {
            foreach ($search_keys as $search_key) {
                if (null !== $this->request->getPost($search_key) && !empty($this->request->getPost($search_key))) {
                    $value = $this->request->getPost($search_key);

                    switch ($search_key) {
                        case 'S_RM_TYPES':
                            $init_cond["RM_TYPE_REF_ID IN"] = "(" . implode(",", $value) . ")";
                            break;

                        case 'S_RESV_STATUS':
                            foreach ($value as $resv_status) {
                                switch ($resv_status) {
                                    case 'Arrivals':
                                        $resv_cond[] = "( RESV_ARRIVAL_DT = '" . $TODAYDATE . "' 
                                                             AND RESV_STATUS IN ('Due Pre Check-In','Pre Checked-In')) ";
                                        break;
                                    case 'Arrived':
                                        $resv_cond[] = "( RESV_ARRIVAL_DT = '" . $TODAYDATE . "' 
                                                             AND RESV_STATUS IN ('Checked-In')) ";
                                        break;
                                    case 'Due Out':
                                        $resv_cond[] = "( RESV_DEPARTURE  = '" . $TODAYDATE . "' 
                                                             AND RESV_STATUS IN ('Checked-In','Check-Out-Requested')) ";
                                        break;
                                    case 'Departed':
                                        $resv_cond[] = "( RESV_DEPARTURE  = '" . $TODAYDATE . "' 
                                                             AND RESV_STATUS IN ('Checked-Out')) ";
                                        break;
                                    case 'Stayover':
                                        $resv_cond[] = "( RESV_DEPARTURE  > '" . $TODAYDATE . "' 
                                                             AND RESV_STATUS IN ('Checked-In')) ";
                                        break;
                                    case 'Day Use':
                                        $resv_cond[] = "( RESV_ARRIVAL_DT = '" . $TODAYDATE . "' 
                                                             AND RESV_ARRIVAL_DT = RESV_DEPARTURE) ";
                                        break;
                                    case 'Not Reserved':
                                        $resv_cond[] = "( RESV_STATUS IN ('Not Reserved')) ";
                                        break;
                                }
                            }

                            $init_cond[implode(" OR ", $resv_cond)] = "";
                            break;

                        case 'S_RM_FEATURES':
                            $init_cond["CONCAT(',', RM_FEATURE, ',') LIKE '%," . str_replace(",", ",%' AND CONCAT(',', RM_FEATURE, ',') LIKE '%,", implode(",", $value)) . ",%'"] = "";
                            break;

                        case 'S_RM_ID':
                        case 'S_RM_STATUS_ID':
                        case 'S_FO_STATUS':
                            $init_cond["" . ltrim($search_key, "S_") . " IN"] = "('" . implode("','", $value) . "')";
                            break;

                        case 'S_SRV_STATUS':
                            $init_cond["RM_GUEST_SERVICE_STATUS IN"] = "(" . implode(",", $value) . ")";
                            break;

                        default:
                            $init_cond["" . ltrim($search_key, "S_") . " = "] = "'$value'";
                            break;
                    }
                }
            }
        }

        $mine = new ServerSideDataTable(); // loads and creates instance

        $tableName = "  (SELECT RM_ID,RM_NO,RM_DESC,RM_TYPE,RM_TYPE_REF_ID,RM_CLASS,RM_FEATURE,RM_FLOOR_PREFERN,RM_MAX_OCCUPANCY,
                        RM_SMOKING_PREFERN,RM_SQUARE_UNITS,RM_PHONE_NO,RVN.RESV_ID,RVN.RESV_ARRIVAL_DT,RVN.RESV_DEPARTURE,
                        ISNULL(SM.RM_STATUS_ID, 2) AS RM_STATUS_ID,ISNULL(SM.RM_STATUS_CODE, 'Dirty') AS RM_STATUS_CODE,
                        ISNULL(RVN.RESV_STATUS, 'Not Reserved') AS RESV_STATUS,RM_GUEST_SERVICE_STATUS,
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
                                    WHERE '" . $TODAYDATE . "' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE
                                    AND RESV_STATUS NOT IN ('Cancelled')
                                    GROUP BY RESV_ROOM_ID ) RESV ON RESV.RESV_ROOM = RM.RM_ID
                        LEFT JOIN FLXY_RESERVATION RVN ON RVN.RESV_ID = RESV.RESV_MAX_ID
                        ) ROOM_STATS";

        $columns = 'RM_ID,RM_NO,RM_DESC,RM_TYPE,RM_TYPE_REF_ID,RM_CLASS,RM_FEATURE,RM_STATUS_ID,RM_STATUS_CODE,RM_GUEST_SERVICE_STATUS,RM_FLOOR_PREFERN,RESV_ID,RESV_STATUS,FO_STATUS,RM_FLOOR_PREFERN,RM_MAX_OCCUPANCY,RM_SMOKING_PREFERN,RM_SQUARE_UNITS,RM_PHONE_NO';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function showFeaturesDesc()
    {
        $comma_list = $this->request->getPost('comma_list');
        echo getFeaturesDesc($comma_list);
    }

    public function showRoomStatus()
    {
        $param = ['SYSID' => $this->request->getPost('roomId')];

        $sql = "SELECT RM_MAX_LOG_ID, RL.RM_STAT_ROOM_ID, RM_STAT_ROOM_STATUS, RM_STATUS_CODE 
                FROM (  SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                        FROM FLXY_ROOM_STATUS_LOG
                        GROUP BY RM_STAT_ROOM_ID
                        HAVING RM_STAT_ROOM_ID = :SYSID:) RM_STAT_LOG
                LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                        
                LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS";

        $response = $this->Db->query($sql, $param)->getRowArray();
        $response = !$response ? ['RM_STAT_ROOM_ID' => $param['SYSID'], 'RM_STAT_ROOM_STATUS' => 2, 'RM_STATUS_CODE' => 'Dirty'] : $response;

        echo json_encode($response);
    }

    public function updateRoomStatus()
    {
        try {
            $roomId = $this->request->getPost('roomId');
            $new_status = $this->request->getPost('new_status');
            $user_id = session()->get('USR_ID');

            $logdata = [
                "RM_STAT_ROOM_ID" => $roomId, "RM_STAT_ROOM_STATUS" => $new_status,
                "RM_STAT_UPDATED_BY" => $user_id, "RM_STAT_UPDATED" => date("Y-m-d H:i:s")
            ];

            $return = $this->Db->table('FLXY_ROOM_STATUS_LOG')->insert($logdata);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function bulkUpdateRoomStatus()
    {
        $user_id = session()->get('USR_ID');

        try {
            // echo json_encode(print_r($_POST));
            // exit;

            $selectRoomsBy = $this->request->getPost('selectRoomsBy');
            $statusToChange = $this->request->getPost('statusToChange');

            $rooms_to_log = $rooms_to_update = [];
            $ins = NULL;

            $roomIds = $this->request->getPost('roomIds');

            if (!empty($roomIds)) {

                //Select Rooms in the list not already having the new status
                $sql = "SELECT RM_ID, RM_STAT_ROOM_STATUS
                        FROM FLXY_ROOM RM
                        LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                                    FROM FLXY_ROOM_STATUS_LOG
                                    GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
                        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID 
                        WHERE RM_ID IN ($roomIds) 
                        AND (RM_STAT_ROOM_STATUS != $statusToChange OR RM_STAT_ROOM_STATUS IS NULL)";

                $rooms_to_update = $this->Db->query($sql)->getResultArray();
            }


            if ($rooms_to_update != NULL) {
                foreach ($rooms_to_update as $room_to_update) {
                    $rooms_to_log[] = [
                        'RM_STAT_ROOM_ID' => $room_to_update['RM_ID'],
                        'RM_STAT_ROOM_STATUS' => $statusToChange,
                        'RM_STAT_UPDATED_BY' => $user_id,
                        'RM_STAT_UPDATED' => date('Y-m-d H:i:s')
                    ];
                }

                // Insert all rooms new statuses to log
                $ins = $this->Db->table('FLXY_ROOM_STATUS_LOG')->insertBatch($rooms_to_log);
            }

            $result = $this->responseJson("1", "0", $ins, count($rooms_to_update));
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function updateServiceStatus()
    {
        try {
            $roomId = $this->request->getPost('roomId');
            $new_status = $this->request->getPost('new_status');
            $user_id = session()->get('USR_ID');

            $logdata = [
                "RM_GUEST_SERVICE_STATUS" => $new_status, "RM_UPDATED_UID" => $user_id, "RM_UPDATED_DT" => date("d-M-Y")
            ];

            $return = $this->Db->table('FLXY_ROOM')->where('RM_ID', $roomId)->update($logdata);

            echo json_encode($this->responseJson("1", "0", $return, $response = ''));
        } catch (\Exception $e) {
            echo json_encode($this->responseJson("-444", "db insert not successful", $return));
        }
    }

    public function showRoomServiceStatus()
    {
        $param = ['SYSID' => $this->request->getPost('roomId')];

        $sql = "SELECT RM_ID, RM_GUEST_SERVICE_STATUS 
                FROM FLXY_ROOM 
                WHERE RM_ID = :SYSID:";

        $response = $this->Db->query($sql, $param)->getRowArray();
        $response = !$response ? ['RM_ID' => $param['SYSID'], 'RM_GUEST_SERVICE_STATUS' => 0] : $response;

        echo json_encode($response);
    }

    public function roomHistory($rmId = 0)
    {
        $data['title'] = 'Housekeeping - Room History';
        $data['room_id'] = $rmId;
        //Check if RESV_ID exists in Customer table
        if($data['room_id'] && !checkValueinTable('RM_ID', $data['room_id'], 'FLXY_ROOM'))
            return redirect()->to(base_url('housekeeping/room-history')); 

        $data['room_status_list'] = $this->Db->table('FLXY_ROOM_STATUS_MASTER')->select('RM_STATUS_ID,RM_STATUS_CODE,RM_STATUS_COLOR_CLASS')->get()->getResultArray();
        $data['room_class_list'] = $this->Db->table('FLXY_ROOM_CLASS')->select('RM_CL_ID,RM_CL_CODE,RM_CL_DESC')->get()->getResultArray();
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();

        return view('Housekeeping/RoomHistory', $data);
    }

    public function roomHistoryView()
    {
        $init_cond = [];
        $TODAYDATE = date('Y-m-d');

        $search_keys = [
            'S_RM_ID', 'S_RM_CLASS', 'S_RM_TYPE_REF_ID', 'S_DEPARTURE_FROM'
        ];

        $init_cond = array("RM_ID = " => '0');

        if ($search_keys != NULL) {
            foreach ($search_keys as $search_key) {
                if (null !== $this->request->getPost($search_key) && !empty($this->request->getPost($search_key))) {
                    $value = $this->request->getPost($search_key);

                    switch ($search_key) {
                        case 'S_DEPARTURE_FROM':
                            $init_cond["RESV_DEPARTURE >= "] = "'$value'";
                            break;

                        default:
                            $init_cond["" . ltrim($search_key, "S_") . " = "] = "'$value'";
                            break;
                    }
                }
            }
        }

        $mine = new ServerSideDataTable(); // loads and creates instance

        $tableName = '  FLXY_RESERVATION
                        LEFT JOIN FLXY_CUSTOMER C ON C.CUST_ID = FLXY_RESERVATION.RESV_NAME
                        LEFT JOIN FLXY_ROOM RM ON (RM.RM_ID = FLXY_RESERVATION.RESV_ROOM_ID)
                        LEFT JOIN FLXY_ROOM_TYPE RT ON (RT.RM_TY_ID = FLXY_RESERVATION.RESV_RM_TYPE_ID)
                        LEFT JOIN ('.showTotalRevenueQuery().') REV_TOT ON REV_TOT.RESERV_ID = FLXY_RESERVATION.RESV_ID';
        $columns = 'RESV_ID|RESV_NO|RESV_ROOM_ID|RM_ID|RM_NO|FORMAT(RESV_ARRIVAL_DT,\'dd-MMM-yyyy\')RESV_ARRIVAL_DT|RESV_NIGHT|FORMAT(RESV_DEPARTURE,\'dd-MMM-yyyy\')RESV_DEPARTURE|RESV_RM_TYPE|RM_TYPE_REF_ID|RESV_ROOM|RM_TY_DESC|RESV_NAME|CUST_ID|RESV_RATE_CODE|RESV_RATE|CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME)CUST_FULL_NAME|CONCAT_WS(\' / \', RESV_ADULTS, RESV_CHILDREN)RESV_PERSONS|RESV_ADULTS|RESV_CHILDREN|ISNULL(TOTAL_REVENUE, \'0.00\')TOTAL_REVENUE';
        $mine->generate_DatatTable($tableName, $columns, $init_cond,'|');
        exit;
    }
}
