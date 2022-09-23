<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\DataTables\NotificationDataTable;

class NotificationController extends BaseController
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
        helper(['form', 'url', 'custom', 'common', 'upload']);
    }

    /**************      Notification Functions      ***************/

    public function Notifications()
    {
        $data['session'] = $this->session;
        $data['js_to_load'] = ["full-form-editor.js"];        
        return view('Notification/NotificationView', $data);
    }

    public function NotificationList()
    {
        $mine = new NotificationDataTable();
        $tableName = "( SELECT NOTIFICATION_ID,NOTIFICATION_GUEST_ID,NOTIFICATION_TEXT,NOTIFICATION_DATE_TIME,NOTIFICATION_READ_STATUS,NOTIF_TY_DESC,CONCAT_WS(' ',USR_FROM.USR_FIRST_NAME,USR_FROM.USR_LAST_NAME) AS NOTIFICATION_FROM_NAME,NOTIFICATION_DEPARTMENT,NOTIFICATION_TO_ID,NOTIFICATION_RESERVATION_ID FROM FLXY_NOTIFICATIONS
        INNER JOIN FLXY_NOTIFICATION_TYPE ON NOTIFICATION_TYPE = NOTIF_TY_ID        
        LEFT JOIN FLXY_USERS USR_FROM ON USR_FROM.USR_ID = NOTIFICATION_FROM_ID 
        ) AS NOTIFICATION";
    
        $columns = 'NOTIFICATION_ID,NOTIF_TY_DESC,NOTIFICATION_DEPARTMENT,NOTIFICATION_TO_ID,NOTIFICATION_FROM_NAME,NOTIFICATION_RESERVATION_ID,NOTIFICATION_GUEST_ID,NOTIFICATION_TEXT,NOTIFICATION_DATE_TIME,NOTIFICATION_READ_STATUS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }


    public function insertNotification()
    {
        try {
            $rules = [];
            $sysid = $this->request->getPost('NOTIFICATION_ID');
            $NOTIFICATION_TYPE           = $this->request->getPost('NOTIFICATION_TYPE');
            $NOTIFICATION_DEPARTMENT     = ($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2) ? $this->request->getPost('NOTIFICATION_DEPARTMENT'):'';
            $NOTIFICATION_TO_ID          = ($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2) ? $this->request->getPost('NOTIFICATION_TO_ID'):'';
            $NOTIFICATION_RESERVATION_ID = ($NOTIFICATION_TYPE == 3 || $NOTIFICATION_TYPE == 4) ?$this->request->getPost('NOTIFICATION_RESERVATION_ID'):'';
            $NOTIFICATION_GUEST_ID       = ($NOTIFICATION_TYPE == 3 ) ? $this->request->getPost('NOTIFICATION_GUEST_ID'):'';
            $NOTIFICATION_DATE_TIME      = $this->request->getPost('NOTIFICATION_DATE_TIME');            
            $NOTIFICATION_TEXT           = $this->request->getPost('NOTIFICATION_TEXT');
            $NOTIFICATION_SEND_NOW       = $this->request->getPost('NOTIFICATION_SEND_NOW');
            

            
            if(($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2) && ((!isset($NOTIFICATION_DEPARTMENT) && empty($NOTIFICATION_DEPARTMENT)) && empty($NOTIFICATION_TO_ID))){               
                $rules['NOTIFICATION_DEPARTMENT'] =  ['label' => 'Department/User', 'rules' => 'required'];                
            }
            else if($NOTIFICATION_TYPE == 3 && ($NOTIFICATION_RESERVATION_ID == '' && $NOTIFICATION_GUEST_ID == '')){
                $rules['NOTIFICATION_RESERVATION_ID'] = ['label' => 'Reservation/Guest', 'rules' => 'required'];
            }
            else if($NOTIFICATION_TYPE == 4 && ($NOTIFICATION_RESERVATION_ID == '')){
                $rules['NOTIFICATION_RESERVATION_ID'] = ['label' => 'Reservation', 'rules' => 'required'];
            }
            if($NOTIFICATION_TEXT  == ''){
                $rules['NOTIFICATION_TEXT'] = ['label' => 'Message', 'rules' => 'required'];
            }

            if(!isset($NOTIFICATION_SEND_NOW) && $NOTIFICATION_DATE_TIME == ''){
                $rules['NOTIFICATION_DATE_TIME'] = ['label' => 'Date and Time', 'rules' => 'required'];
            };
             
            if (!empty($rules) && !$this->validate($rules)) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }   

            $data = [
                "NOTIFICATION_TYPE"       => $NOTIFICATION_TYPE,
                "NOTIFICATION_FROM_ID"    => session()->get('USR_ID'),
                "NOTIFICATION_DEPARTMENT" => empty($NOTIFICATION_DEPARTMENT) ? '' : serialize($NOTIFICATION_DEPARTMENT),
                "NOTIFICATION_TO_ID"      => (!empty($NOTIFICATION_DEPARTMENT) || empty($NOTIFICATION_TO_ID)) ? '' : serialize($NOTIFICATION_TO_ID),
                "NOTIFICATION_GUEST_ID"   => (!empty($NOTIFICATION_GUEST_ID)) ? serialize($NOTIFICATION_GUEST_ID):'',
                "NOTIFICATION_RESERVATION_ID" => (!empty($NOTIFICATION_RESERVATION_ID)) ? serialize($NOTIFICATION_RESERVATION_ID):'',
                "NOTIFICATION_TEXT"       => $NOTIFICATION_TEXT,
                "NOTIFICATION_DATE_TIME"  => isset($NOTIFICATION_SEND_NOW) ? date('Y-m-d H:i:s'):$NOTIFICATION_DATE_TIME,
                "NOTIFICATION_READ_STATUS"=> 0,
            ];
           
            $return = !empty($sysid) ? $this->Db->table('FLXY_NOTIFICATIONS')->where('NOTIFICATION_ID', $sysid)->update($data) : $this->Db->table('FLXY_NOTIFICATIONS')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        }catch(\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function editNotification()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC, RT_CL_DIS_SEQ,
                FORMAT(RT_CL_BEGIN_DT, 'dd-MMM-yyyy') as RT_CL_BEGIN_DT, FORMAT(RT_CL_END_DT, 'dd-MMM-yyyy') as RT_CL_END_DT
                FROM FLXY_RATE_CLASS
                WHERE RT_CL_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteNotification()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CLASS')->delete(['RT_CL_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }


    public function notificationTypeList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT NOTIF_TY_ID, NOTIF_TY_DESC
                FROM FLXY_NOTIFICATION_TYPE";

        if ($search != '') {
            $sql .= " WHERE NOTIF_TY_DESC LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '';
        $checked = 'checked="checked"';
        foreach ($response as $row) {
           // $option .= '<option value="' . $row['NOTIF_TY_ID'] . '">' . $row['NOTIF_TY_DESC'] . '</option>';
            
            $option.= '<div class="col-md-2 form-check mb-2" style="float:left;margin-right:10px">
            <input type="radio" id="NOTIFICATION_TYPE_'.$row["NOTIF_TY_ID"].'" name="NOTIFICATION_TYPE" value="'.$row["NOTIF_TY_ID"].'" class="form-check-input" required="" '.$checked.'>
            <label class="form-check-label" for="NOTIFICATION_TYPE_'.$row["NOTIF_TY_ID"].'">'.$row["NOTIF_TY_DESC"].'</label>
            </div>';
            $checked = '';
        }

        return $option;
    }

    public function usersList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT USR_ID, CONCAT_WS(' ',USR_FIRST_NAME,USR_LAST_NAME) AS FULL_NAME
                FROM FLXY_USERS";

        if ($search != '') {
            $sql .= " WHERE USR_FIRST_NAME LIKE '%$search%' AND USR_LAST_NAME LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['USR_ID'] . '">' . $row['FULL_NAME'] . '</option>';
        }

        return $option;
    }

    public function allDepartmentList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT DEPT_ID, DEPT_CODE, DEPT_DESC
                FROM FLXY_DEPARTMENT";

        if ($search != '') {
            $sql .= " WHERE DEPT_DESC LIKE '%$search%'
                    ";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="all">All</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['DEPT_ID'] . '">' . $row['DEPT_CODE'] . ' | ' . $row['DEPT_DESC']  . '</option>';
        }

        return $option;
    }

    public function reservationList()
    {
        $sql = "SELECT RESV_ID, RESV_NO, RESV_STATUS, RESV_RM_TYPE, RESV_ROOM, RESV_ROOM_ID, 
                       (SELECT RM_ID FROM FLXY_ROOM WHERE RM_NO = RESV_ROOM AND RM_TYPE = RESV_RM_TYPE) RM_ID
                FROM FLXY_RESERVATION RESV
                WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested')
                AND RESV_ROOM != ''
                ORDER BY RESV_NO DESC";

        $response = $this->Db->query($sql)->getResultArray();

        $option = '';
        $numResults = count($response);

        for ($i = 0; $i < $numResults; $i++) {

            $room_id = !empty($response[$i]['RESV_ROOM_ID']) ? $response[$i]['RESV_ROOM_ID'] : $response[$i]['RM_ID'];
            
            $option .= '<option value="' . $response[$i]['RESV_ID'] . '"
                                data-room-type="' . $response[$i]['RESV_RM_TYPE'] . '"
                                data-room-no="' . $response[$i]['RESV_ROOM'] . '"
                                data-room-id="' . $room_id . '">' . $response[$i]['RESV_NO'] . ' - ' . $response[$i]['RESV_STATUS'] . '</option>';
        }

        return $option;
    }

    public function getCustomers()
    {
        $sql = "SELECT  CUST_ID, 
                        CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, 
                        CONCAT_WS(' ', CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS,
                        CUST_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC,
                        CUST_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=CUST_STATE AND COUNTRY_CODE=CUST_COUNTRY) CUST_STATE_DESC,
                        CUST_CITY,(SELECT ctname FROM CITY WHERE ID=CUST_CITY) CUST_CITY_DESC,
                        CUST_EMAIL,CUST_MOBILE,CUST_PHONE,CUST_POSTAL_CODE

                FROM FLXY_CUSTOMER
                WHERE CUST_ID IN (  SELECT RESV_NAME AS CUST_ID 
                                    FROM FLXY_RESERVATION
                                        UNION 
                                    SELECT ACCOMP_CUST_ID AS CUST_ID 
                                    FROM FLXY_ACCOMPANY_PROFILE)";
        
        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        $option = '';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['CUST_ID'] . '">' . $row['FULLNAME']  . '</option>';
        }

        return $option;
    }

    public function viewAllNotificationDetails(){
        $notificationType = $this->request->getPost('type');
        $notificationId   = $this->request->getPost('notificationId');

        if($notificationType == 'Departments'){
            $NOTIFICATION_DEPARTMENT = $this->Db->query("SELECT NOTIFICATION_DEPARTMENT FROM FLXY_NOTIFICATIONS WHERE  NOTIFICATION_ID = $notificationId")->getRow()->NOTIFICATION_DEPARTMENT; 

            $department_ids = implode(",",unserialize($NOTIFICATION_DEPARTMENT));  

            $departments = $this->Db->query("SELECT DEPT_DESC FROM FLXY_DEPARTMENT WHERE DEPT_ID IN ($department_ids)")->getResultArray(); 
            $departmentsList = '';
            if(!empty($departments)){
                foreach($departments as $department)
                $departmentsList .= $department['DEPT_DESC'] . ', ';
                echo $departmentsList;
            } 


        }
        else if($notificationType == 'Users'){
            $NOTIFICATION_TO_ID = $this->Db->query("SELECT NOTIFICATION_TO_ID FROM FLXY_NOTIFICATIONS WHERE  NOTIFICATION_ID = $notificationId")->getRow()->NOTIFICATION_TO_ID; 

            $user_ids = implode(",",unserialize($NOTIFICATION_TO_ID));  

            $users = $this->Db->query("SELECT CONCAT_WS(' ', USR_FIRST_NAME, USR_LAST_NAME) AS NAME FROM FLXY_USERS WHERE USR_ID IN ($user_ids)")->getResultArray();  
            $usersList = '';
            if(!empty($users)){
                foreach($users as $user_id)
                $usersList .= $user_id['NAME'] . ', ';
                echo $usersList;
            } 
        }

        else if($notificationType == 'Reservation'){
            $NOTIFICATION_RESERVATION_ID = $this->Db->query("SELECT NOTIFICATION_RESERVATION_ID FROM FLXY_NOTIFICATIONS WHERE  NOTIFICATION_ID = $notificationId")->getRow()->NOTIFICATION_RESERVATION_ID; 

            $reservation_ids = implode(",",unserialize($NOTIFICATION_RESERVATION_ID));  

            $reservations = $this->Db->query("select CONCAT_WS(' ', RESV_NO, RESV_STATUS) AS RESV_NAME FROM FLXY_RESERVATION WHERE RESV_ID IN ($reservation_ids)")->getResultArray();  
            $reservationsList = '';
            if(!empty($reservations)){
                foreach($reservations as $reservation)
                $reservationsList .= $reservation['RESV_NAME'] . ', ';
                echo $reservationsList;
            } 
        }

        else if($notificationType == 'Guests'){
            $NOTIFICATION_GUEST_ID = $this->Db->query("SELECT NOTIFICATION_GUEST_ID FROM FLXY_NOTIFICATIONS WHERE  NOTIFICATION_ID = $notificationId")->getRow()->NOTIFICATION_GUEST_ID; 

            $guest_ids = implode(",",unserialize($NOTIFICATION_GUEST_ID));  

            $guests = $this->Db->query("CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME FROM FLXY_CUSTOMER WHERE CUST_ID in ($guest_ids)")->getResultArray();  
            $guestList = '';
            if(!empty($guests)){
                foreach($guests as $guest_id)
                $guestList .= $guest_id['FULLNAME'] . ', ';
                echo $guestList;
            } 
        }



    }


}