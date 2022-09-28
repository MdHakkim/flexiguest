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
            $NOTIFI = [];
            $sysid = $this->request->getPost('NOTIFICATION_ID');
            $NOTIFICATION_TYPE           = $this->request->getPost('NOTIFICATION_TYPE');
            $NOTIFICATION_DEPARTMENT     = ($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2 || $NOTIFICATION_TYPE == 4) ? $this->request->getPost('NOTIFICATION_DEPARTMENT'):'';
            $NOTIFICATION_TO_ID          = ($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2 || $NOTIFICATION_TYPE == 4 ) ? $this->request->getPost('NOTIFICATION_TO_ID'):'';
            $NOTIFICATION_RESERVATION_ID = ($NOTIFICATION_TYPE == 3 || $NOTIFICATION_TYPE == 4) ?$this->request->getPost('NOTIFICATION_RESERVATION_ID'):'';
            $NOTIFICATION_GUEST_ID       = ($NOTIFICATION_TYPE == 3 ) ? $this->request->getPost('NOTIFICATION_GUEST_ID'):'';
            $NOTIFICATION_DATE_TIME      = $this->request->getPost('NOTIFICATION_DATE_TIME');            
            $NOTIFICATION_TEXT           = $this->request->getPost('NOTIFICATION_TEXT');
            $NOTIFICATION_SEND_NOW       = $this->request->getPost('NOTIFICATION_SEND_NOW');

            $NOTIFICATION_OLD_TYPE       = $this->request->getPost('NOTIFICATION_OLD_TYPE');
            

            
            if(($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2) && ((!isset($NOTIFICATION_DEPARTMENT) && empty($NOTIFICATION_DEPARTMENT)) && empty($NOTIFICATION_TO_ID))){               
                $rules['NOTIFICATION_DEPARTMENT'] =  ['label' => 'Department/User', 'rules' => 'required'];                
            }
            else if($NOTIFICATION_TYPE == 3 && ($NOTIFICATION_RESERVATION_ID == '' && $NOTIFICATION_GUEST_ID == '')){
                $rules['NOTIFICATION_RESERVATION_ID'] = ['label' => 'Reservation/Guest', 'rules' => 'required'];
            }
            else if($NOTIFICATION_TYPE == 4 && ($NOTIFICATION_RESERVATION_ID == '' || ((!isset($NOTIFICATION_DEPARTMENT) && empty($NOTIFICATION_DEPARTMENT)) && empty($NOTIFICATION_TO_ID)))){
                $rules['NOTIFICATION_RESERVATION_ID'] = ['label' => 'Reservation', 'rules' => 'required'];
                $rules['NOTIFICATION_DEPARTMENT'] = ['label' => 'Department/User', 'rules' => 'required'];
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
                "NOTIFICATION_DEPARTMENT" => empty($NOTIFICATION_DEPARTMENT) ? '' : json_encode($NOTIFICATION_DEPARTMENT),
                "NOTIFICATION_TO_ID"      => (!empty($NOTIFICATION_DEPARTMENT) || empty($NOTIFICATION_TO_ID)) ? '' : json_encode($NOTIFICATION_TO_ID),
                "NOTIFICATION_GUEST_ID"   => (!empty($NOTIFICATION_GUEST_ID)) ? json_encode($NOTIFICATION_GUEST_ID):'',
                "NOTIFICATION_RESERVATION_ID" => (!empty($NOTIFICATION_RESERVATION_ID)) ? json_encode($NOTIFICATION_RESERVATION_ID):'',
                "NOTIFICATION_TEXT"       => $NOTIFICATION_TEXT,
                "NOTIFICATION_DATE_TIME"  => isset($NOTIFICATION_SEND_NOW) ? date('Y-m-d H:i:s'):$NOTIFICATION_DATE_TIME,
                "NOTIFICATION_READ_STATUS"=> 0,
            ];  
            

            $NOTIFICATION_DATE_TIME = isset($NOTIFICATION_SEND_NOW) ? date('Y-m-d H:i:s'):$NOTIFICATION_DATE_TIME;
            //print_r($data);exit;
           
            $return = !empty($sysid) ? $this->Db->table('FLXY_NOTIFICATIONS')->where('NOTIFICATION_ID', $sysid)->update($data) : $this->Db->table('FLXY_NOTIFICATIONS')->insert($data);

            $RSV_TRACE_NOTIFICATION_ID =  empty($sysid) ? $this->Db->insertID():$sysid;

            if(!empty($sysid) && ($NOTIFICATION_OLD_TYPE == 4 && $NOTIFICATION_TYPE != 4)){
                $this->Db->table('FLXY_RESERVATION_TRACES')->delete(['RSV_TRACE_NOTIFICATION_ID'=>$RSV_TRACE_NOTIFICATION_ID]); 
            }

            if($NOTIFICATION_TYPE == 4){
                
                if(!empty($NOTIFICATION_RESERVATION_ID)){
                    $valueExists = checkValueinTable('RSV_TRACE_NOTIFICATION_ID', $RSV_TRACE_NOTIFICATION_ID, 'FLXY_RESERVATION_TRACES');
                    if($valueExists)
                    $this->Db->table('FLXY_RESERVATION_TRACES')->delete(['RSV_TRACE_NOTIFICATION_ID'=>$RSV_TRACE_NOTIFICATION_ID]); 

                    for($i=0; $i<count($NOTIFICATION_RESERVATION_ID); $i++){ 
                        $RESVDATA['RSV_ID']               = $NOTIFICATION_RESERVATION_ID[$i];
                        $RESVDATA['RSV_TRACE_DATE']       = date('Y-m-d',strtotime($NOTIFICATION_DATE_TIME));
                        $RESVDATA['RSV_TRACE_TIME']       = date('H:i:s',strtotime($NOTIFICATION_DATE_TIME));
                        $RESVDATA['RSV_TRACE_DEPARTMENT'] = json_encode($NOTIFICATION_DEPARTMENT);
                        $RESVDATA['RSV_TRACE_TEXT']       = $NOTIFICATION_TEXT;
                        $RESVDATA['RSV_TRACE_ENTERED_BY'] = session()->get('USR_ID');
                        $RESVDATA['RSV_TRACE_STATUS']     = 1; 
                        $RESVDATA['RSV_TRACE_NOTIFICATION_ID'] = $RSV_TRACE_NOTIFICATION_ID;
                                            
                        $return1 = $this->Db->table('FLXY_RESERVATION_TRACES')->insert($RESVDATA);                       
                    }
                }
                
            }

            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);

            // Send Notification 

            if($NOTIFICATION_TYPE == 1 || $NOTIFICATION_TYPE == 2 || $NOTIFICATION_TYPE == 4 )
            {

                if($NOTIFICATION_TYPE == 4 ){
                    $NOTIFI['NOTIF_TRAIL_RESERVATION'] = json_encode($NOTIFICATION_RESERVATION_ID);
                }


                if((isset($NOTIFICATION_TO_ID) && !empty($NOTIFICATION_TO_ID))){
                    
                    for($j=0; $j<count($NOTIFICATION_TO_ID);$j++){
                        
                        $NOTIFI['NOTIF_TRAIL_DEPARTMENT']      = '';
                        $NOTIFI['NOTIF_TRAIL_USER']            = $NOTIFICATION_TO_ID[$j];
                        $NOTIFI['NOTIF_TRAIL_NOTIFICATION_ID'] = $RSV_TRACE_NOTIFICATION_ID;
                        $NOTIFI['NOTIF_TRAIL_READ_STATUS']     = 0;
                        $NOTIFI['NOTIF_TRAIL_DATETIME']        = $NOTIFICATION_DATE_TIME;
                        $return1 = $this->Db->table('FLXY_NOTIFICATION_TRIAL')->insert($NOTIFI);
                        
                    }    
                }
                else if((isset($NOTIFICATION_DEPARTMENT) && !empty($NOTIFICATION_DEPARTMENT))){
                    for($j=0; $j<count($NOTIFICATION_DEPARTMENT);$j++){                       
                        $NOTIFI['NOTIF_TRAIL_DEPARTMENT']      = $NOTIFICATION_DEPARTMENT[$j];
                        $DEPARTMENT_USERS = $this->getDepartmentUsers($NOTIFICATION_DEPARTMENT[$j]);
                        foreach($DEPARTMENT_USERS as $USERS){
                            $NOTIFI['NOTIF_TRAIL_USER'] = $USERS['USR_ID'];
                            $NOTIFI['NOTIF_TRAIL_NOTIFICATION_ID'] = $RSV_TRACE_NOTIFICATION_ID;
                            $NOTIFI['NOTIF_TRAIL_READ_STATUS']     = 0;
                            $NOTIFI['NOTIF_TRAIL_DATETIME']        = $NOTIFICATION_DATE_TIME;
                            $return1 = $this->Db->table('FLXY_NOTIFICATION_TRIAL')->insert($NOTIFI);
                        }                       
                    }   

                } 

            }
            else if($NOTIFICATION_TYPE == 3 ){
                if(isset($NOTIFICATION_GUEST_ID) && !empty($NOTIFICATION_GUEST_ID)){
                    for($j=0; $j<count($NOTIFICATION_GUEST_ID);$j++){
                        $NOTIFI['NOTIF_TRAIL_GUEST'] = $NOTIFICATION_GUEST_ID[$j];
                        $NOTIFI['NOTIF_TRAIL_NOTIFICATION_ID'] = $RSV_TRACE_NOTIFICATION_ID;
                        $NOTIFI['NOTIF_TRAIL_READ_STATUS']     = 0;
                        $NOTIFI['NOTIF_TRAIL_DATETIME']        = $NOTIFICATION_DATE_TIME;
                        $return1 = $this->Db->table('FLXY_NOTIFICATION_TRIAL')->insert($NOTIFI);
                    }
                } 
                else if(isset($NOTIFICATION_RESERVATION_ID) && !empty($NOTIFICATION_RESERVATION_ID)){
                    for($j=0; $j<count($NOTIFICATION_RESERVATION_ID);$j++){
                        $NOTIFI['NOTIF_TRAIL_RESERVATION']      = $NOTIFICATION_RESERVATION_ID[$j];
                        $RESERVATION_USERS = $this->getReservationUsers($NOTIFICATION_RESERVATION_ID[$j]);
                        foreach($RESERVATION_USERS as $USERS){
                            $NOTIFI['NOTIF_TRAIL_GUEST'] = $USERS['CUST_ID'];
                            $NOTIFI['NOTIF_TRAIL_NOTIFICATION_ID'] = $RSV_TRACE_NOTIFICATION_ID;
                            $NOTIFI['NOTIF_TRAIL_READ_STATUS']     = 0;
                            $NOTIFI['NOTIF_TRAIL_DATETIME']        = $NOTIFICATION_DATE_TIME;
                            $return1 = $this->Db->table('FLXY_NOTIFICATION_TRIAL')->insert($NOTIFI);
                        }
                    }                      

                }           
            }

            else if($NOTIFICATION_TYPE == 4){

                
            }






            echo json_encode($result);
        }catch(\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function getDepartmentUsers($department){
        $param = ['SYSID' => $department];
        $sql = "SELECT USR_ID
        FROM FLXY_USERS
        WHERE USR_STATUS = '1' AND USR_DEPARTMENT=:SYSID:";
        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function getReservationUsers($reservation){
        $param = ['SYSID' => $reservation];
        $sql = "SELECT  CUST_ID
                FROM FLXY_CUSTOMER
                WHERE CUST_ID IN (  SELECT RESV_NAME AS CUST_ID 
                            FROM FLXY_RESERVATION WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested') AND RESV_ID =:SYSID:
                                UNION 
                            SELECT ACCOMP_CUST_ID AS CUST_ID 
                            FROM FLXY_ACCOMPANY_PROFILE INNER JOIN FLXY_RESERVATION ON ACCOMP_REF_RESV_ID = RESV_ID WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested') AND ACCOMP_REF_RESV_ID =:SYSID:)";
                        
        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function editNotification()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT NOTIFICATION_ID,NOTIFICATION_TYPE,NOTIFICATION_DEPARTMENT,NOTIFICATION_GUEST_ID,NOTIFICATION_TO_ID,NOTIFICATION_RESERVATION_ID,NOTIFICATION_TEXT,FORMAT(NOTIFICATION_DATE_TIME, 'yyyy-MM-dd H:mm:ss') as NOTIFICATION_DATE_TIME
                FROM FLXY_NOTIFICATIONS
                WHERE NOTIFICATION_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteNotification()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_NOTIFICATIONS')->delete(['NOTIFICATION_ID' => $sysid]);
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
                        CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME 
                FROM FLXY_CUSTOMER
                WHERE CUST_ID IN (  SELECT RESV_NAME AS CUST_ID 
                                    FROM FLXY_RESERVATION WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested')
                                        UNION 
                                    SELECT ACCOMP_CUST_ID AS CUST_ID 
                                    FROM FLXY_ACCOMPANY_PROFILE INNER JOIN FLXY_RESERVATION ON ACCOMP_REF_RESV_ID = RESV_ID WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested'))";
        
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

            $department_ids = implode(",",json_decode($NOTIFICATION_DEPARTMENT));  

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

            $user_ids = implode(",",json_decode($NOTIFICATION_TO_ID));  

            $users = $this->Db->query("SELECT CONCAT_WS(' ', USR_FIRST_NAME, USR_LAST_NAME) AS NAME FROM FLXY_USERS WHERE USR_ID IN ($user_ids)")->getResultArray();  
            $usersList = '';
            if(!empty($users)){
                foreach($users as $user_id)
                $usersList .= $user_id['NAME'] . ', ';
                echo $usersList;
            } 
        }

        else if($notificationType == 'Reservations'){
            $NOTIFICATION_RESERVATION_ID = $this->Db->query("SELECT NOTIFICATION_RESERVATION_ID FROM FLXY_NOTIFICATIONS WHERE  NOTIFICATION_ID = $notificationId")->getRow()->NOTIFICATION_RESERVATION_ID; 

            $reservation_ids = implode(",",json_decode($NOTIFICATION_RESERVATION_ID));  

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

            $guest_ids = implode(",",json_decode($NOTIFICATION_GUEST_ID));  

            $guests = $this->Db->query("select CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME FROM FLXY_CUSTOMER WHERE CUST_ID in ($guest_ids)")->getResultArray();  
            $guestList = '';
            if(!empty($guests)){
                foreach($guests as $guest_id)
                $guestList .= $guest_id['FULLNAME'] . ', ';
                echo $guestList;
            } 
        }
    }

    public function guestByReservation()
    {
        $result = NULL;
        $reservation_ids = implode(",",$this->request->getPost('reservation_ids'));
        try {

            $sql = "SELECT  CUST_ID, 
                CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME 
                FROM FLXY_CUSTOMER
                WHERE CUST_ID IN (  SELECT RESV_NAME AS CUST_ID 
                            FROM FLXY_RESERVATION WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested') AND RESV_ID IN ($reservation_ids)
                                UNION 
                            SELECT ACCOMP_CUST_ID AS CUST_ID 
                            FROM FLXY_ACCOMPANY_PROFILE INNER JOIN FLXY_RESERVATION ON ACCOMP_REF_RESV_ID = RESV_ID WHERE RESV_STATUS IN ('Checked-In','Checked-Out-Requested') AND ACCOMP_REF_RESV_ID IN ($reservation_ids))";
                        
            $response = $this->Db->query($sql)->getResultArray();
            echo json_encode($response);        
       
        }catch(\Exception $e) {
            return $e->getMessage();
        }       
    }

    public  function guestsReservation($reservation_ids){
        
    }


}