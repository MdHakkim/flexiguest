<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;
use App\Models\ShareReservations;
use App\Libraries\ServerSideDataTable;
use App\Libraries\DataTables\TraceDataTable;
use PhpParser\Node\Stmt\Else_;

use function PHPSTORM_META\map;

class ReservationController extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $Reservation;
    private $ShareReservations;

    public function __construct()
    {
        $this->DB    = \Config\Database::connect();
        $this->pager = \Config\Services::pager();
        $this->uri   = \Config\Services::uri();
        $this->Reservation = new Reservation();
        $this->ShareReservations = new ShareReservations();
        $this->session = \Config\Services::session();
        helper(['form', 'common', 'custom']);
    }

    public function getReservationDetails()
    {
        $reservation_id = $this->request->getvar('reservation_id');
        $reservation_ids[] = $reservation_id;

        $share_reservations = $this->ShareReservations->where('FSR_RESERVATION_ID', $reservation_id)->findColumn('FSR_OTHER_RESERVATION_ID');
        if ($share_reservations)
            $reservation_ids = array_merge($reservation_ids, $share_reservations);

        $order_by_condition = "case";
        foreach ($reservation_ids as $index => $rid) {
            $order_by_condition .= " when FLXY_RESERVATION.RESV_ID = $rid then $index";

            if ($index == count($reservation_ids) - 1) {
                $i = $index + 1;
                $order_by_condition .= " else {$i} end";
            }
        }

        $reservations = $this->Reservation
            ->select("*, ($order_by_condition) as order_no")
            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
            ->whereIn('RESV_ID', $reservation_ids)
            ->orderBy('order_no', 'asc')
            ->findAll();

        $output['room_details'] = $output['nightly_rate_details'] = $output['reservation_arrival_details'] = '';

        $total_rate = 0;
        foreach ($reservations as $reservation) {
            $total_rate += $reservation['RESV_RATE'];

            $class_name = '';
            if (empty($output['reservation_arrival_details']))
                $class_name = 'active-tr';

            $output['reservation_arrival_details'] .= <<<EOD
                <tr class="$class_name" onclick="changeReservationId(this, {$reservation['RESV_ID']})">
                    <input type="hidden" name="share_reservations[]" value="{$reservation['RESV_ID']}"/>
                    <td>{$reservation['CUST_FIRST_NAME']} {$reservation['CUST_LAST_NAME']}</td>
                    <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                    <td>{$reservation['RESV_DEPARTURE']}</td>
                    <td>{$reservation['RESV_STATUS']}</td>
                    <td>{$reservation['RESV_ADULTS']}</td>
                    <td>{$reservation['RESV_CHILDREN']}</td>
                    <td>{$reservation['RESV_RATE_CODE']}</td>
                    <td>{$reservation['RESV_RATE']}</td>
                </tr>
            EOD;
        }

        $output['room_details'] = <<<EOD
            <tr>
                <td>{$reservation['RM_NO']}</td>
                <td>{$reservation['RESV_RM_TYPE']}</td>
                <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                <td>{$reservation['RESV_DEPARTURE']}</td>
            </tr>
        EOD;

        $output['nightly_rate_details'] = <<<EOD
            <tr>
                <td>{$total_rate}</td>
                <td>{$reservation['RESV_SHARE_RATE']}</td>
                <td>{$reservation['RESV_ARRIVAL_DT']}</td>
                <td>{$reservation['RESV_DEPARTURE']}</td>
            </tr>
        EOD;

        return $this->respond(responseJson(200, false, ['msg' => 'reservation details'], $output));
    }

    public function sharesCreateReservation()
    {
        $user_id = session()->get('USR_ID');

        $validate = $this->validate([
            'CUST_ID' => ['label' => 'Guest', 'rules' => 'required'],
            'RESV_ADULTS' => ['label' => 'Adult', 'rules' => 'required|greater_than[0]'],
            'RESV_CHILDREN' => ['label' => 'Children', 'rules' => 'required|greater_than_equal_to[0]'],
            'RESV_RESRV_TYPE' => ['label' => 'Reservation Type', 'rules' => 'required'],
            'RESV_PAYMENT_TYPE' => ['label' => 'Payment Type', 'rules' => 'required'],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();

            $result = $this->responseJson(403, true, $validate);
            return $this->respond($result);
        }

        $other_reservation_id = $this->request->getVar('other_reservation_id');
        $other_reservation = $this->Reservation->find($other_reservation_id);

        $data = [
            'RESV_ARRIVAL_DT' => $other_reservation['RESV_ARRIVAL_DT'],
            'RESV_NIGHT' => $other_reservation['RESV_NIGHT'],
            'RESV_ADULTS' => $this->request->getVar('RESV_ADULTS'),
            'RESV_CHILDREN' => $this->request->getVar('RESV_CHILDREN'),
            'RESV_DEPARTURE' => $other_reservation['RESV_DEPARTURE'],
            'RESV_NO_F_ROOM' => $other_reservation['RESV_NO_F_ROOM'],
            'RESV_NAME' => $this->request->getVar('CUST_ID'),
            'RESV_RATE_CODE' => $other_reservation['RESV_RATE_CODE'],
            'RESV_STATUS' => $other_reservation['RESV_STATUS'],
            'RESV_RM_TYPE' => $other_reservation['RESV_RM_TYPE'],
            'RESV_ROOM' => $other_reservation['RESV_ROOM'],
            'RESV_RATE' => $other_reservation['RESV_RATE'],
            'RESV_RESRV_TYPE' => $this->request->getVar('RESV_RESRV_TYPE'),
            'RESV_ORIGIN' => $other_reservation['RESV_ORIGIN'],
            'RESV_PAYMENT_TYPE' => $this->request->getVar('RESV_PAYMENT_TYPE'),

            'RESV_CREATE_UID' => $user_id,
            'RESV_UPDATE_UID' => $user_id,
        ];

        $reservation_id = $this->Reservation->insert($data);

        if (!$reservation_id)
            return $this->respond(responseJson(500, false, ['msg' => 'Something went wrong!']));
        else // Add Log for New Reservation
        {
            $log_action_desc = '';
            foreach($data as $dkey => $dvalue)
            {
                    // Save changes in log description if data is not empty
                    if(!empty(trim($dvalue)))
                    {
                        $log_action_desc .= "<b>".str_replace('RESV_', '', $dkey) . ": </b> '" . $dvalue ."'<br/>";
                    }
            }
            addActivityLog(1, 10, $reservation_id, $log_action_desc);
        }

        $this->Reservation->update($reservation_id, ['RESV_NO' => "RES$reservation_id"]);

        $result = responseJson(200, false, ['msg' => 'reservation created'], $reservation_id);
        return $this->respond($result);
    }

    public function searchReservation()
    {
        $reservation_id = $this->request->getVar('RESV_ID'); // when searching for single reservation using RESV_ID (pass only this param)

        // for reservation search filter
        $current_reservation_id = $this->request->getVar('current_reservation_id');
        $first_name = $this->request->getVar('CUST_FIRST_NAME');
        $last_name = $this->request->getVar('CUST_LAST_NAME');
        $room_no = $this->request->getVar('RESV_ROOM');

        $params = [];
        $params['current_reservation_id'] = $current_reservation_id;
        $where_condition = '';

        if(!empty($room_no)) {
            $where_condition .= " and fr.RESV_ROOM = :room_no:";
            $params['room_no'] = $room_no;
        }

        if(!empty($reservation_id)) {
            $where_condition .= " and fr.RESV_ID = :reservation_id:";
            $params['reservation_id'] = $reservation_id;
        }

        if(!empty($first_name)) {
            $where_condition .= " and fr.RESV_NAME in (select CUST_ID from FLXY_CUSTOMER where CUST_FIRST_NAME like :first_name:)";
            $params['first_name'] = "%$first_name%";
        }

        if(!empty($last_name)) {
            $where_condition .= " and fr.RESV_NAME in (select CUST_ID from FLXY_CUSTOMER where CUST_LAST_NAME like :last_name:)";
            $params['last_name'] = "%$last_name%";
        }

        $reservations = $this->DB->query(
            "select fr.*, fc.CUST_TITLE, fc.CUST_FIRST_NAME, fc.CUST_LAST_NAME from FLXY_RESERVATION fr
                left join FLXY_CUSTOMER fc on fr.RESV_NAME = fc.CUST_ID 
                where fr.RESV_ID != :current_reservation_id:$where_condition",
            $params
        )->getResultArray();

        $output['reservations'] = $reservations;
        $output['searched_reservations'] = '';
        foreach ($reservations as $reservation) {
            $output['searched_reservations'] .= <<<EOD
                <tr class="select-reservation" data_sysid="{$reservation['RESV_ID']}">
                    <td class="editReserWindow" data_sysid="{$reservation['RESV_ID']}">
                        <i class="fa-solid fa-user-pen"></i>
                    </td>
                    <td class="select">{$reservation['CUST_FIRST_NAME']} {$reservation['CUST_LAST_NAME']}</td>
                    <td class="select">{$reservation['RESV_STATUS']}</td>
                    <td class="select">{$reservation['RESV_ARRIVAL_DT']}</td>
                    <td class="select">{$reservation['RESV_DEPARTURE']}</td>
                    <td class="select">{$reservation['RESV_RATE_CODE']}</td>
                    <td class="select">{$reservation['RESV_RM_TYPE']}</td>
                    <td class="select">{$reservation['RESV_ROOM']}</td>
                </tr>
            EOD;
        }

        if (empty($output['searched_reservations']) && empty($reservation_id)) {
            $output['html'] = <<<EOD
                <tr>
                    <li>No Record Found!</li>
                </tr>
            EOD;
        }


        return $this->respond(responseJson(200, false, ['msg' => 'reservations'], $output));
    }

    public function addShareReservations()
    {
        $user_id = session()->get('USR_ID');

        $reservation_ids = $this->request->getVar('reservation_ids');

        if (count($reservation_ids) <= 1)
            return $this->respond(responseJson(202, false, ['msg' => 'No reservation added to share']));

        if ($this->ShareReservations->where('FSR_RESERVATION_ID', $reservation_ids[0])->where('FSR_OTHER_RESERVATION_ID', end($reservation_ids))->findAll())
            return $this->respond(responseJson(202, false, ['msg' => 'This reservation is already added']));

        $this->ShareReservations->whereIn('FSR_RESERVATION_ID', $reservation_ids)->delete();

        $main_reservation = $this->Reservation->find($reservation_ids[0]);
        $main_reservation['RESV_SHARE_RATE'] = 'full';
        $this->Reservation->save($main_reservation);

        foreach ($reservation_ids as $reservation_id) {

            if ($reservation_id != $main_reservation['RESV_ID']) { // make room details / dates same like main reservation for each reservation
                $data = [
                    'RESV_RATE_CODE' => $main_reservation['RESV_RATE_CODE'],
                    'RESV_ROOM_CLASS' => $main_reservation['RESV_ROOM_CLASS'],
                    'RESV_RM_TYPE' => $main_reservation['RESV_RM_TYPE'],
                    'RESV_ROOM' => $main_reservation['RESV_ROOM'],
                    'RESV_RATE' => $main_reservation['RESV_RATE'],
                    'RESV_RTC' => $main_reservation['RESV_RTC'],
                    'RESV_ARRIVAL_DT' => $main_reservation['RESV_ARRIVAL_DT'],
                    'RESV_DEPARTURE' => $main_reservation['RESV_DEPARTURE'],
                    'RESV_SHARE_RATE' => $main_reservation['RESV_SHARE_RATE'],
                ];

                $this->Reservation->where('RESV_ID', $reservation_id)->set($data)->update();
            }

            foreach ($reservation_ids as $rid) {
                if ($reservation_id != $rid) {
                    $data = [
                        'FSR_RESERVATION_ID' => $reservation_id,
                        'FSR_OTHER_RESERVATION_ID' => $rid,
                        'FSR_CREATED_BY' => $user_id,
                        'FSR_UPDATED_BY' => $user_id,
                    ];

                    $this->ShareReservations->insert($data);
                }
            }
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Shared successfully.']));
    }

    public function breakShareReservation()
    {
        $main_reservation_id = $this->request->getVar('main_reservation_id');
        $selected_reservation_id = $this->request->getVar('selected_reservation_id');

        if($main_reservation_id == $selected_reservation_id)
            return $this->respond(responseJson(202, false, ['msg' => 'Can\'t remove main reservation.']));

        $this->changeShareRate();

        $this->ShareReservations->where('FSR_RESERVATION_ID', $selected_reservation_id)->orWhere('FSR_OTHER_RESERVATION_ID', $selected_reservation_id)->delete();
        $this->Reservation->where('RESV_ID', $selected_reservation_id)->set(['RESV_ROOM' => null, 'RESV_SHARE_RATE' => null, 'RESV_RATE' => null])->update();

        if(!$this->ShareReservations->where('FSR_RESERVATION_ID', $selected_reservation_id)->orWhere('FSR_OTHER_RESERVATION_ID', $selected_reservation_id)->findAll()){
            $this->Reservation->update($main_reservation_id, ['RESV_SHARE_RATE' => null]);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Shared reservation removed successfully.']));
    }

    public function changeShareRate()
    {
        $selected_reservation_id = $this->request->getVar('selected_reservation_id');
        $reservation_ids = $this->request->getVar('reservation_ids');
        $share_rate = $this->request->getVar('share_rate');

        $reservation_ids_str = implode(",", $reservation_ids);

        $total_rate = $this->DB->query("select sum(convert(float, RESV_RATE)) as total_rate, RESV_SHARE_RATE from FLXY_RESERVATION where RESV_ID in ($reservation_ids_str) group by RESV_SHARE_RATE")->getResultArray();
        
        $current_share_rate = $total_rate[0]['RESV_SHARE_RATE'];
        $total_rate = $total_rate[0]['total_rate'];
        
        if ($current_share_rate == 'full') {
            $total_rate = $total_rate / count($reservation_ids);
        }

        if ($share_rate == 'entire') {
            foreach ($reservation_ids as $reservation_id) {
                $this->Reservation->update($reservation_id, ['RESV_RATE' => '0', 'RESV_SHARE_RATE' => 'entire']);
            }

            $this->Reservation->update($selected_reservation_id, ['RESV_RATE' => $total_rate]);
        } else if ($share_rate == 'split') {
            foreach ($reservation_ids as $reservation_id) {
                $this->Reservation->update($reservation_id, ['RESV_RATE' => ($total_rate / count($reservation_ids)), 'RESV_SHARE_RATE' => 'split']);
            }
        } else if ($share_rate == 'full') {
            foreach ($reservation_ids as $reservation_id) {
                $this->Reservation->update($reservation_id, ['RESV_RATE' => $total_rate, 'RESV_SHARE_RATE' => 'full']);
            }
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Share rate updated successfully!']));
    }



    //// Registration Card Single and Batch

    public function registerCards(){
        
        $roomClassLists = $this->roomClassLists();
        $membershipLists = $this->membershipLists();
        $rateCodeLists = $this->rateCodeLists();
        $vipCodeLists = $this->vipCodeLists();
    
        $data = [
            'roomClassLists' => $roomClassLists,
            'membershipLists' => $membershipLists,
            'rateCodeLists' => $rateCodeLists,
            'vipCodeLists' => $vipCodeLists,
            'title'        => 'Guest Registration Cards'
        ];

        
        return view('Reservation/RegistrationCardView', $data);
    }

    public function registerCardDataExists(){
        
        
        $sql = "SELECT RESV_ARRIVAL_DT, RESV_ROOM, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, RESV_NO, RESV_RATE, RESV_RM_TYPE, RESV_NAME, (SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY) RESV_COMPANY_DESC, CUST_FIRST_NAME, CUST_LAST_NAME, CUST_MOBILE, CUST_EMAIL, (SELECT ctname FROM CITY WHERE id=CUST_CITY) CUST_CITY_DESC, (SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC, (SELECT cname FROM COUNTRY WHERE ISO2=CUST_NATIONALITY) CUST_NATIONALITY_DESC, CONCAT(CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS, CUST_DOB, CUST_DOC_TYPE, CUST_DOC_NUMBER FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON FLXY_RESERVATION.RESV_NAME = FLXY_CUSTOMER.CUST_ID";
       
        if ($_SESSION['ARRIVAL_DATE'] != '') {
            $ARRIVAL_DATE = $_SESSION['ARRIVAL_DATE'];
            $sql .= " WHERE RESV_ARRIVAL_DT LIKE '%$ARRIVAL_DATE%' ";                   
        }

        if($_SESSION['ETA_FROM_TIME'] != '' && $_SESSION['ETA_TO_TIME'] !='')        
            $sql .= " AND (RESV_ETA BETWEEN '".$_SESSION['ETA_FROM_TIME']."' AND '".$_SESSION['ETA_TO_TIME']."')";
        
        else if($_SESSION['ETA_FROM_TIME'] != '')         
            $sql .= " AND  RESV_ETA >= '".$_SESSION['ETA_FROM_TIME']."'";  

        else if($_SESSION['ETA_TO_TIME'] != '')         
            $sql .= " AND RESV_ETA <= '".$_SESSION['ETA_TO_TIME']."'"; 

        if($_SESSION['RESV_FROM_NAME'] != '')         
            $sql .= " AND CUST_FIRST_NAME LIKE '%'".$_SESSION['RESV_FROM_NAME']."'%'"; 

        if($_SESSION['ROOM_CLASS'] != '')         
            $sql .= " AND RESV_ROOM_CLASS = '".$_SESSION['ROOM_CLASS']."'"; 
        
        if($_SESSION['RATE_CODE'] != '')         
            $sql .= " AND RESV_RATE_CODE = '".$_SESSION['RATE_CODE']."'";
        
        if($_SESSION['MEM_TYPE'] != '')         
            $sql .= " AND RESV_MEMBER_TY = '".$_SESSION['MEM_TYPE']."'";

          // echo $sql;

        $response['sql'] = $sql;
      
        $response['data'] = $this->DB->query($sql)->getResultArray(); 

        $response['count'] = $this->DB->query($sql)->getNumRows();
       //print_r($response);
        return $response;

    }   


    public function registerCardPrint(){
        $response = $this->registerCardDataExists();        
        $data['title'] = getMethodName();
        $data['js_to_load'] = "app-invoice-print.js";
        $data['response'] = $response['data'];
        return view('Reservation/RegisterCard',$data);

    }
    public function registerCardPreview(){
        $response = $this->registerCardDataExists();  
        $data['title'] = getMethodName(); 
        $data['response'] = $response['data'];
        return view('Reservation/RegisterCardPreview',$data);

    }

    public function registerCardSaveDetails(){
        $_SESSION['ARRIVAL_DATE']  = date("Y-m-d",strtotime($this->request->getPost('ARRIVAL_DATE')));
        $_SESSION['ETA_FROM_TIME'] = $this->request->getPost('ETA_FROM_TIME');
        $_SESSION['ETA_TO_TIME']   = $this->request->getPost('ETA_TO_TIME');
        $_SESSION['RESV_INDIV']    = $this->request->getPost('RESV_INDIV');
        $_SESSION['RESV_BLOCK']    = $this->request->getPost('RESV_BLOCK');
        $_SESSION['RESV_FROM_NAME']= $this->request->getPost('RESV_FROM_NAME');
        $_SESSION['RESV_TO_NAME']  = $this->request->getPost('RESV_TO_NAME');
        $_SESSION['ROOM_CLASS']    = $this->request->getPost('ROOM_CLASS');
        $_SESSION['RATE_CODE']     = $this->request->getPost('RATE_CODE');
        $_SESSION['MEM_TYPE']      = $this->request->getPost('MEM_TYPE');
        $_SESSION['VIP_CODE']      = $this->request->getPost('VIP_CODE');
        $_SESSION['IN_HOUSE_GUESTS'] = $this->request->getPost('IN_HOUSE_GUESTS');  
        $response =  $this->registerCardDataExists();
        $responseCount =   $response['count']; 
        echo json_encode($responseCount);
    }

    public function roomClassLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT RM_CL_ID, RM_CL_CODE, RM_CL_DESC
                    FROM FLXY_ROOM_CLASS";

            if ($search != '') {
                $sql .= " WHERE RM_CL_CODE LIKE '%$search%'
                        OR RM_CL_DESC LIKE '%$search%'";
            }

            $response = $this->DB->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RM_CL_ID'] . '">' . $row['RM_CL_CODE'] . ' | ' . $row['RM_CL_DESC'] . '</option>';
            }

            return $option;
        }
        
    public function membershipLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT MEM_ID, MEM_CODE, MEM_DESC
                    FROM FLXY_MEMBERSHIP";

            if ($search != '') {
                $sql .= " WHERE MEM_CODE LIKE '%$search%'
                        OR MEM_DESC LIKE '%$search%'";
            }

            $response = $this->DB->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['MEM_ID'] . '">' . $row['MEM_CODE'] . ' | ' . $row['MEM_DESC'] . '</option>';
            }

            return $option;
        }

    public function rateCodeLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT RT_CD_ID, RT_CD_CODE, RT_CD_DESC
                    FROM FLXY_RATE_CODE";

            if ($search != '') {
                $sql .= " WHERE RT_CD_CODE LIKE '%$search%'
                        OR RT_CD_DESC LIKE '%$search%'";
            }

            $response = $this->DB->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CD_ID'] . '">' . $row['RT_CD_CODE'] . ' | ' . $row['RT_CD_DESC'] . '</option>';
            }

            return $option;
        }

    public function vipCodeLists()
        {
            $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

            $sql = "SELECT VIP_ID, VIP_DESC
                    FROM FLXY_VIP";

            if ($search != '') {
                $sql .= " WHERE VIP_DESC LIKE '%$search%'
                        ";
            }

            $response = $this->DB->query($sql)->getResultArray();

            $option = '<option value="">Choose an Option</option>';
            foreach ($response as $row) {
                $option .= '<option value="' . $row['VIP_ID'] . '">' . $row['VIP_DESC']  . '</option>';
            }

            return $option;
    }


    public function singleReservRegCards(){
        $reservationID = $this->request->getPost('reservID');         
        $_SESSION['RESV_ID'] = $reservationID;
        $response = $this->registerCardData();
        $data['count'] = $response['count'];
        echo json_encode($data);

    }

    public function singleReservRegCardPrint(){

        $response = $this->registerCardData();  
        $data['title'] = getMethodName(); 
        $data['response'] = $response['response'];
        return view('Reservation/RegisterCard',$data);
    }

    public function registerCardData(){        
        $sql = "SELECT RESV_ARRIVAL_DT, RESV_ROOM, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, RESV_NO, 
                        RESV_RATE, RESV_RM_TYPE, RESV_NAME, 
                        CUST_FIRST_NAME, CUST_LAST_NAME, CUST_MOBILE, CUST_EMAIL, CUST_DOB, CUST_DOC_TYPE, CUST_DOC_NUMBER,
                        CONCAT(CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS, 
                        fd.DOC_FILE_PATH as SIGNATURE,
                        (SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY) RESV_COMPANY_DESC, 
                        (SELECT ctname FROM CITY WHERE id=CUST_CITY) CUST_CITY_DESC, 
                        (SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC, 
                        (SELECT cname FROM COUNTRY WHERE ISO2=CUST_NATIONALITY) CUST_NATIONALITY_DESC
                        FROM FLXY_RESERVATION 
                            INNER JOIN FLXY_CUSTOMER ON FLXY_RESERVATION.RESV_NAME = FLXY_CUSTOMER.CUST_ID 
                            left join FLXY_DOCUMENTS as fd on FLXY_RESERVATION.RESV_ID = fd.DOC_RESV_ID and fd.DOC_FILE_TYPE = 'SIGN'
                            WHERE RESV_ID = ".$_SESSION['RESV_ID'];
        $data['response'] = $this->DB->query($sql)->getResultArray(); 
        $data['count'] = $this->DB->query($sql)->getNumRows();
        return $data;
    }


    
    public function RateClassList(){ 
        $option = '';

        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT RT_CL_ID, RT_CL_CODE, RT_CL_DESC
                FROM FLXY_RATE_CLASS";

        if ($search != '') {
            $sql .= " WHERE RT_CL_CODE LIKE '%$search%'
                    OR RT_CL_DESC LIKE '%$search%'";
        }

        $response = $this->DB->query($sql)->getResultArray();

        $option = '<option value="">Select</option>';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CL_ID'] . '">' . $row['RT_CL_CODE'] . ' | ' . $row['RT_CL_DESC'] . '</option>';
            }
        }
        return $option;
    }


    public function RateCategory(){ 
        $option = '';      
        $rate_class_id = $this->request->getPost('rate_class_id');

        $sql = "SELECT RT_CT_ID, RT_CT_CODE, RT_CT_DESC
                FROM FLXY_RATE_CATEGORY WHERE 1 = 1"; 
                
        if(!empty(trim($rate_class_id)))
            $sql .= " AND RT_CL_ID = ".$rate_class_id;           

        $response = $this->DB->query($sql)->getResultArray();

        $option = '<option value="">Select</option>';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CT_ID'] . '">' . $row['RT_CT_CODE'] . ' | ' . $row['RT_CT_DESC'] . '</option>';
            }
        }
        return $option;
    }

    public function RateCodes(){ 
        $option = '';          
        $rate_category_id = $this->request->getPost('rate_category_id');
        $rate_class_id = $this->request->getPost('rate_class_id');

        $sql = "SELECT RT_CD_ID, RT_CD_CODE, RT_CD_DESC, RT_CD_ROOM_TYPES
                FROM FLXY_RATE_CODE WHERE 1 = 1";

        if(!empty(trim($rate_category_id)))
            $sql .= " AND RT_CT_ID = ".$rate_category_id;

        if(!empty(trim($rate_class_id)))
            $sql .= " AND RT_CT_ID IN ( SELECT RT_CT_ID 
                                        FROM FLXY_RATE_CATEGORY 
                                        WHERE RT_CL_ID = ".$rate_class_id.")";        

        $response = $this->DB->query($sql)->getResultArray();

        $option = '<option value="">Select</option>';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CD_ID'] . '" data-rc-roomtypes="' . $row['RT_CD_ROOM_TYPES'] . '">' . $row['RT_CD_CODE'] . ' | ' . $row['RT_CD_DESC'] . '</option>';
            }
        }
        return $option;
    }

    public function getPackageList(){ 
        $option = '';                    

        $sql = "SELECT PKG_CD_ID, PKG_CD_CODE, PKG_CD_DESC
                FROM FLXY_PACKAGE_CODE";           

        $response = $this->DB->query($sql)->getResultArray();

        $option = '<option value="">Select</option>';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['PKG_CD_ID'] . '">' . $row['PKG_CD_CODE'] . ' | ' . $row['PKG_CD_DESC'] . '</option>';
            }
        }
        return $option;
    }

    public function getPackageDetails(){ 
        $option = '';  
        $package_id = $this->request->getPost('package_id');                 

        $sql = "SELECT PKG_CD_ID, PO_RH_DESC, CLC_RL_DESC
                FROM FLXY_PACKAGE_CODE INNER JOIN FLXY_POSTING_RHYTHM ON FLXY_POSTING_RHYTHM.PO_RH_ID =  FLXY_PACKAGE_CODE.PO_RH_ID INNER JOIN FLXY_CALCULATION_RULE ON FLXY_PACKAGE_CODE.CLC_RL_ID=FLXY_CALCULATION_RULE.CLC_RL_ID WHERE PKG_CD_ID = ".$package_id;           

        $response = $this->DB->query($sql)->getResultArray();
       
        if(!empty($response)){
            foreach ($response as $row) {
                $option = ['PO_RH_DESC' => $row['PO_RH_DESC'], 'CLC_RL_DESC' => $row['CLC_RL_DESC']];
            } 
        }
        echo json_encode($option);
    }

            
public function updatePackageDetails()
{
    try {
        $PCKG_ID              = $this->request->getPost('PCKG_ID');
        $RSV_PCKG_ID          = $this->request->getPost('RSV_PCKG_ID');
        $PCKG_RESV_ID         = $this->request->getPost('PCKG_RESV_ID');
        $RSV_PCKG_BEGIN_DATE  = $this->request->getPost('RSV_PCKG_BEGIN_DATE');
        $RSV_PCKG_END_DATE    = $this->request->getPost('RSV_PCKG_END_DATE');
        $RESVSTART_DATE       = $this->request->getPost('RESVSTART_DATE');
        $RESVEND_DATE         = $this->request->getPost('RESVEND_DATE');
        $POSTING_RHYTHM       = $this->request->getPost('RSV_PCKG_POST_RYTHM');
        $CALCULATION_RULE     = $this->request->getPost('RSV_PCKG_CALC_RULE');        
        $RSV_PCKG_QTY         = $this->request->getPost('RSV_PCKG_QTY');

        if($PCKG_RESV_ID == '') 
            $PCKG_RESV_ID = 0;
       
        $data = [
            "RSV_ID" => $PCKG_RESV_ID,
            "PCKG_ID" => trim($PCKG_ID),
            "RSV_PCKG_QTY" => trim($RSV_PCKG_QTY),
            "RSV_PCKG_BEGIN_DATE" => date('Y-m-d',(strtotime($RSV_PCKG_BEGIN_DATE))),
            "RSV_PCKG_END_DATE" => date('Y-m-d',(strtotime($RSV_PCKG_END_DATE))),
            "RSV_PCKG_STATUS" => 1,
            "RSV_PCKG_POST_RYTHM" => $POSTING_RHYTHM,
            "RSV_PCKG_CALC_RULE" => $CALCULATION_RULE,
            "RSV_PCKG_SESSION_ID" => session_id()
        ];

        $rules = [  'PCKG_ID' => ['label' => 'Package', 'rules' => 'required|packageAvailableCheck[RSV_PCKG_BEGIN_DATE]',
                    'errors' =>['packageAvailableCheck' => 'Package is not availbale in this time period']],
                    'RSV_PCKG_QTY' => ['label' => 'Quantity', 'rules' => 'required'],
                    'RSV_PCKG_BEGIN_DATE' => ['label' => 'Start Date', 'rules' => 'required|checkPackageStartDate[RSV_PCKG_BEGIN_DATE]|packageDateOverlapCheck[RSV_PCKG_BEGIN_DATE]', 'errors' => ['checkPackageStartDate' => 'Start Date should be between the reservation dates ','packageDateOverlapCheck' => 'Start Date of package overlaps with an existing package. Change the date']],                       
                    'RSV_PCKG_END_DATE' => ['label' => 'End Date', 'rules' => 'required|checkPackageEndDate[RSV_PCKG_END_DATE]|packageDateOverlapCheck[RSV_PCKG_END_DATE]|compareDate[RSV_PCKG_END_DATE]', 'errors' => ['checkPackageEndDate' => 'End Date should be between the reservation dates ','packageDateOverlapCheck' => 'End Date of package overlaps with an existing package. Change the date','compareDate' => 'The End Date should be after Begin Date']]                  
                    
                 ];                  

        $validate = $this->validate($rules);
        
        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result["SUCCESS"] = "-402";
            $result[]["ERROR"] = $validate;
            $result = $this->responseJson("-402", $validate);
            echo json_encode($result);
            exit;
        }           

       $return = !empty($RSV_PCKG_ID) ? $this->DB->table('FLXY_RESERVATION_PACKAGES')->where('RSV_PCKG_ID', $RSV_PCKG_ID)->update($data) : $this->DB->table('FLXY_RESERVATION_PACKAGES')->insert($data);
        

        $result = $return ? $this->responseJson("1", "0", $return, !empty($RSV_PCKG_ID) ? $RSV_PCKG_ID : $this->DB->insertID()) : $this->responseJson("-444", "DB insert not successful", $return);

       
       
        if(!$return)
            $this->session->setFlashdata('error', 'There has been an error. Please try again.');
        else
        {
            //if(empty($PCKG_ID))
                //$this->session->setFlashdata('success', 'The new package has been added.');
        }
        echo json_encode($result);

    } catch (\Exception $e) {
        return $e->getMessage();
    }
}


public function showPackages()
    { 
        $mine = new ServerSideDataTable(); // loads and creates instance

        //Reservation ID 
        $RESV_ID = $this->request->getPost('RESV_ID');
        $session_id = session_id();

        if($RESV_ID > 0)
        $init_cond = array("RSV_ID = " => $RESV_ID); 
        else
        $init_cond = array("RSV_PCKG_SESSION_ID LIKE " => "'".$session_id."'", "RSV_ID = " => 0); 
        
        $tableName = 'FLXY_RESERVATION_PACKAGES INNER JOIN FLXY_PACKAGE_CODE ON FLXY_RESERVATION_PACKAGES.PCKG_ID = FLXY_PACKAGE_CODE.PKG_CD_ID ';
        $columns = 'RSV_PCKG_ID,PKG_CD_CODE,PKG_CD_DESC,PKG_CD_SHORT_DESC,RSV_PCKG_QTY,RSV_PCKG_POST_RYTHM,RSV_PCKG_CALC_RULE,RSV_PCKG_BEGIN_DATE,RSV_PCKG_END_DATE';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);        
        
        exit;
    }

    public function showPackageDetails()
    {
        $packageDetailsList = $this->getSinglePackageDetails($this->request->getPost('packageID'));
        echo json_encode($packageDetailsList);
    }

    public function getSinglePackageDetails($packageID = 0)
    {
        $param = ['SYSID' => $packageID];

        $sql = "SELECT *           
                FROM dbo.FLXY_RESERVATION_PACKAGES
                WHERE RSV_PCKG_ID=:SYSID:";       

        $response = $this->DB->query($sql, $param)->getResultArray();
        return $response;
    }


    public function deletePackageDetail()
    {
        $RSV_PCKG_ID = $this->request->getPost('RSV_PCKG_ID');

        try {
            $return = $this->DB->table('FLXY_RESERVATION_PACKAGES')->delete(['RSV_PCKG_ID' => $RSV_PCKG_ID]); 
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function showSinglePackageDetails()
    {

        $output    = '';
        $flag = $out = 0;
        $packageID = $this->request->getPost('packageID');

        $sql = "SELECT RSV_PCKG_BEGIN_DATE, RSV_PCKG_END_DATE, PCKG_ID, RSV_PCKG_QTY       
                FROM FLXY_RESERVATION_PACKAGES 
                WHERE RSV_PCKG_ID=".$packageID;  

        $response = $this->DB->query($sql)->getResultArray();
        foreach ($response as $row) {
            $RSV_PCKG_BEGIN_DATE    = $row['RSV_PCKG_BEGIN_DATE'];
            $RSV_PCKG_END_DATE      = $row['RSV_PCKG_END_DATE'];
            $PCKG_ID                = $row['PCKG_ID'];
            $RSV_PCKG_QTY           = $row['RSV_PCKG_QTY'];
        }

       
        $RSV_PCKG_BEGIN_DATE1    = strtotime($RSV_PCKG_BEGIN_DATE);
        $RSV_PCKG_END_DATE1      = strtotime($RSV_PCKG_END_DATE); 
        $datediff = $RSV_PCKG_END_DATE1 - $RSV_PCKG_BEGIN_DATE1;
        if($RSV_PCKG_END_DATE1 == $RSV_PCKG_BEGIN_DATE1)
        $RESERV_DAYS = 1;
        else
        $RESERV_DAYS = round($datediff / (60 * 60 * 24));

        $ACTUAL_RSV_PCKG_END_DATE  = date("Y-m-d", strtotime("-1 day", strtotime($RSV_PCKG_END_DATE)));  

        $Posting_Rhythm = 0;
        $Posting_Rhythm = $this->DB->query("SELECT PO_RH_ID       
        FROM FLXY_PACKAGE_CODE 
        WHERE PKG_CD_ID =".$PCKG_ID)->getRow()->PO_RH_ID;
   
        for($i = 1; $i <= $RESERV_DAYS; $i++ ){
           
            $sql = "SELECT  PKG_CD_DT_PRICE       
                FROM FLXY_PACKAGE_CODE_DETAIL 
                WHERE ('$RSV_PCKG_BEGIN_DATE' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND ('$RSV_PCKG_END_DATE' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND PKG_CD_ID=".$PCKG_ID;                  
              
            $response = $this->DB->query($sql)->getResultArray();

            foreach ($response as $row) {
                $PKG_CD_DT_PRICE    = $row['PKG_CD_DT_PRICE'];
            }
            $PKG_CD_DT_PRICE =  number_format($PKG_CD_DT_PRICE,2, '.', '');
            $RSV_PCKG_TOTAL = number_format(($RSV_PCKG_QTY * $PKG_CD_DT_PRICE),2, '.', '');
           

            /////////// Posting_Rhythm /////////////
            if( ($Posting_Rhythm == 5 && ($RSV_PCKG_BEGIN_DATE == $RSV_PCKG_END_DATE)) || $Posting_Rhythm == 1 || $Posting_Rhythm == 2) 
            {   
                 $out = 1;   
                 
            }            
            /////////// else /////////////
            else{
                $out = 0; 

            }  
            if($out == 1){
                $output.= '<tr>                   
                <td class="select">'.$RSV_PCKG_BEGIN_DATE.'</td>
                <td class="select">'.$PKG_CD_DT_PRICE.'</td>
                <td class="select">'.$RSV_PCKG_TOTAL.'</td>                    
                </tr>';
            }
           

            $RSV_PCKG_BEGIN_DATE  = date("Y-m-d", strtotime("+1 day", strtotime($RSV_PCKG_BEGIN_DATE))); 

            if( $Posting_Rhythm == 2)
            break;
                
        }

        echo $output;
    }


    public function rateInfoDetails()
    {

        $output    = '';
        $RESV_RATE_TOTAL = $STAY_TOTAL = $DEPOSIT =  $FIXED_CHARGES = 0 ;
        $resvID = $this->request->getPost('resvID');

        $sql = "SELECT RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_RATE, RESV_RATE_CODE     
                FROM FLXY_RESERVATION 
                WHERE RESV_ID=".$resvID;  
        $response = $this->DB->query($sql)->getResultArray();
        foreach ($response as $row) {
            $RESV_ARRIVAL_DT    = $row['RESV_ARRIVAL_DT'];
            $RESV_DEPARTURE     = $row['RESV_DEPARTURE'];
            $RESV_RATE_CODE     = $row['RESV_RATE_CODE'];            
            $RESV_RATE          = $row['RESV_RATE'];
        }

        $RESV_ARRIVAL_DT1    = strtotime($RESV_ARRIVAL_DT);
        $RESV_DEPARTURE1     = strtotime($RESV_DEPARTURE); 
        $datediff            = $RESV_DEPARTURE1 - $RESV_ARRIVAL_DT1;
        if($RESV_DEPARTURE1 == $RESV_ARRIVAL_DT1)
        $RESERV_DAYS = 1;
        else
        $RESERV_DAYS = round($datediff / (60 * 60 * 24)); 
        $VAT = 0.05;   
        //echo $RESERV_DAYS;

       
   
        for($i = 1; $i <= $RESERV_DAYS; $i++ ){              
                
                
                /////Packages ////////////////
                $PCK_RATE = $this->getPackageRate($resvID,$RESV_ARRIVAL_DT,$i,$RESERV_DAYS);
                $generates = ($RESV_RATE+$PCK_RATE) * $VAT;
                $total = ($RESV_RATE + $PCK_RATE) + $generates;

                $RESV_RATE_TOTAL += $RESV_RATE;
                $STAY_TOTAL += $total;

                /////Fixed Charges //////////////
                $FIXED_CHARGES += $this->getFixedCharges($resvID,$RESV_ARRIVAL_DT,$i);


                $output.= '<tr>                   
                <td class="select">'.$RESV_ARRIVAL_DT.'</td>
                <td class="select">'.$RESV_RATE_CODE.'</td>
                <td class="select">'.number_format(($RESV_RATE),2, '.', '').'</td> 
                <td class="select">'.number_format(($PCK_RATE),2, '.', '').'</td>
                <td class="select">'.number_format(($RESV_RATE+$PCK_RATE),2, '.', '').'</td> 
                <td class="select">'.number_format(($generates),2, '.', '').'</td> 
                <td class="select">'.number_format(($total),2, '.', '').'</td>                   
                </tr>';            

                $RESV_ARRIVAL_DT  = date("Y-m-d", strtotime("+{$i} day", $RESV_ARRIVAL_DT1)); 

        }

        $TOTAL_COST_OF_STAY = ($STAY_TOTAL + $FIXED_CHARGES);

        $total_output = '<tr><td></td><td>Stay Total</td><td>AED '.number_format(($STAY_TOTAL),2, '.', '').'</td></tr>
       
        <tr><td></td><td>Fixed Charges</td><td>AED '.number_format(($FIXED_CHARGES),2, '.', '').'</td></tr>
        <tr><td></td><td>Total Cost of Stay</td><td>AED '.number_format(($TOTAL_COST_OF_STAY),2, '.', '').'</td></tr>
        <tr><td></td><td>Deposit</td><td>AED '.number_format(($DEPOSIT),2, '.', '').'</td></tr>
        <tr><td></td><td>Outstanding Stay Total</td><td>AED '.number_format((($TOTAL_COST_OF_STAY - $DEPOSIT)),2, '.', '').'</td></tr>';
        $data = ['output' => $output, 'total_output' => $total_output];

        echo json_encode($data);
    }


    function getPackageRate($resvID,$date,$day, $RESERV_DAYS){
        // package id and quantity, reserv start date end date,

        $PCK_TOTAL = $flag  = 0;
        $sql = "SELECT PO_RH_ID,PKG_CD_DT_PRICE,RSV_PCKG_QTY    
        FROM FLXY_RESERVATION_PACKAGES INNER JOIN FLXY_PACKAGE_CODE ON  PKG_CD_ID = PCKG_ID INNER JOIN FLXY_PACKAGE_CODE_DETAIL ON FLXY_PACKAGE_CODE_DETAIL.PKG_CD_ID = FLXY_RESERVATION_PACKAGES.PCKG_ID
        WHERE PKG_CD_DT_STATUS = '1' AND ('$date' BETWEEN PKG_CD_START_DT AND PKG_CD_END_DT) AND RSV_ID = ".$resvID; 
        $response = $this->DB->query($sql)->getResultArray();
        if(!empty($response)){
            foreach ($response as $row) {
                if($row['PO_RH_ID'] == 1){
                    $PCK_TOTAL += ($row['RSV_PCKG_QTY'] * $row['PKG_CD_DT_PRICE']);
                }  
                else if($row['PO_RH_ID'] == 2 && $day == 1) {
                    $PCK_TOTAL += ($row['RSV_PCKG_QTY'] * $row['PKG_CD_DT_PRICE']);
                   
                }  
                
                else if($row['PO_RH_ID'] == 5 && $day == $RESERV_DAYS) {
                    $PCK_TOTAL += ($row['RSV_PCKG_QTY'] * $row['PKG_CD_DT_PRICE']);
                    
                }  
            }
        }
       
        return number_format(($PCK_TOTAL),2, '.', '');       

    }


    function getFixedCharges($resvID,$RESV_ARRIVAL_DT,$day){
        $VAT = 0.05; $fixedChargesTotal = $fixedChargesVATTotal = 0;
        $RESV_ARRIVAL_DATE = strtotime($RESV_ARRIVAL_DT);
        $sCurrentDate = gmdate("d-m-Y", strtotime("+$day day", $RESV_ARRIVAL_DATE)); 
        $CurrentDate  = strtotime($sCurrentDate); 
        $sCurrentDay  = gmdate("w", strtotime("+{$day} day", $RESV_ARRIVAL_DATE));
        $sCurrentD    = gmdate("d", strtotime("+{$day} day", $RESV_ARRIVAL_DATE)); 

        $sql = "SELECT * FROM FLXY_FIXED_CHARGES WHERE FIXD_CHRG_RESV_ID = ".$resvID;  
        $fixedChargesResponse = $this->DB->query($sql)->getResultArray();
        if(!empty($fixedChargesResponse)){
            foreach($fixedChargesResponse as $fixedCharges) {
                $FIXD_CHRG_BEGIN_DATE =gmdate("d-m-Y", strtotime("+1 day", strtotime($fixedCharges['FIXD_CHRG_BEGIN_DATE'])));
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
                        }
                        else if($FIXD_CHRG_FREQUENCY == 2 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE)){
                           
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;
                        }
                        else if($FIXD_CHRG_FREQUENCY == 3 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentDay == $fixedCharges['FIXD_CHRG_WEEKLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;
                        }
                        else if($FIXD_CHRG_FREQUENCY == 4 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && ($sCurrentD == $fixedCharges['FIXD_CHRG_MONTHLY'])){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;
                        }
                        else if($FIXD_CHRG_FREQUENCY == 6 && ($CurrentDate >= $FIXD_CHRG_BEGIN_DATE && $CurrentDate <= $FIXD_CHRG_END_DATE) && (date('d-m',$sCurrentDate) == date('d-m',$fixedCharges['FIXD_CHRG_YEARLY']))){
                            $fixedChargesAMOUNT = $fixedCharges['FIXD_CHRG_AMT'] * $fixedCharges['FIXD_CHRG_QTY'];
                            $fixedChargesVAT = $fixedChargesAMOUNT * $VAT;
                            $fixedChargesTotal += $fixedChargesAMOUNT; 
                            $fixedChargesVATTotal += $fixedChargesVAT;
                        }
            }

            return ($fixedChargesTotal + $fixedChargesVATTotal);
        }
                 
    }


    //////////////////// TRACES ///////////////


    public function showTraces()
    { 
        $mine = new TraceDataTable(); // loads and creates instance
        //Reservation ID 
        $RESV_ID = $this->request->getPost('TRACE_RESV_ID');
        $session_id = session_id();
        $init_cond = '';

        if($RESV_ID > 0)
        $init_cond = array("RSV_ID = " => $RESV_ID, "RSV_TRACE_STATUS = " => 1);   
        
        $tableName = '( SELECT  RSV_TRACE_ID,RSV_ID,RSV_TRACE_DATE,RSV_TRACE_TIME,RSV_TRACE_DEPARTMENT,UE.USR_FIRST_NAME AS UE_FIRST_NAME,UE.USR_LAST_NAME AS UE_LAST_NAME,UR.USR_FIRST_NAME AS UR_FIRST_NAME,UR.USR_LAST_NAME AS UR_LAST_NAME,RSV_TRACE_RESOLVED_BY,RSV_TRACE_RESOLVED_ON,RSV_TRACE_RESOLVED_TIME,RSV_TRACE_STATUS FROM
                        FLXY_RESERVATION_TRACES INNER JOIN FLXY_USERS UE ON UE.USR_ID = RSV_TRACE_ENTERED_BY LEFT JOIN FLXY_USERS UR ON UR.USR_ID = RSV_TRACE_RESOLVED_BY) TRACE_LIST';

        $columns = 'RSV_TRACE_ID,RSV_ID,RSV_TRACE_DATE,RSV_TRACE_DEPARTMENT,RSV_TRACE_TIME,UE_FIRST_NAME,UE_LAST_NAME,UR_FIRST_NAME,UR_LAST_NAME,RSV_TRACE_RESOLVED_BY,RSV_TRACE_RESOLVED_ON,RSV_TRACE_RESOLVED_TIME,RSV_TRACE_STATUS';
        
        $mine->generate_DatatTable($tableName, $columns, $init_cond);  
        
        exit;
    }

    public function showTraceDetails()
    {
        $traceDetails = $this->getTraceDetails($this->request->getPost('RSV_TRACE_ID'));
        echo json_encode($traceDetails);
    }

    public function getTraceDetails($RSV_TRACE_ID = 0)
    {
        $param = ['SYSID' => $RSV_TRACE_ID];
        $sql = "SELECT *           
                FROM dbo.FLXY_RESERVATION_TRACES
                WHERE RSV_TRACE_ID=:SYSID:";       

        $response = $this->DB->query($sql, $param)->getResultArray();
        return $response;
    }

    public function updateTraces()
    {
        try {
            $RSV_TRACE_ID           =  $this->request->getPost('RSV_TRACE_ID');
            $RSV_ID                 =  $this->request->getPost('TRACE_RESV_ID');
            $RSV_TRACE_DATE         =  date('Y-m-d',strtotime($this->request->getPost('RSV_TRACE_DATE')));
            $RSV_TRACE_TIME         =  $this->request->getPost('RSV_TRACE_TIME');
            $RSV_TRACE_DEPT_NOTIFI  =  $this->request->getPost('RSV_TRACE_DEPARTMENT'); 
            $RSV_TRACE_DEPARTMENT   =  json_encode($this->request->getPost('RSV_TRACE_DEPARTMENT')); 
            $RSV_TRACE_TEXT         =  $this->request->getPost('RSV_TRACE_TEXT');   
            $RSV_TRACE_NOTIFICATION_ID =  $this->request->getPost('RSV_TRACE_NOTIFICATION_ID');             

            $data = [
                "RSV_ID"                  => $RSV_ID,
                "RSV_TRACE_DEPARTMENT"    => $RSV_TRACE_DEPARTMENT,
                "RSV_TRACE_DATE"          => $RSV_TRACE_DATE,
                "RSV_TRACE_TIME"          => $RSV_TRACE_TIME,
                "RSV_TRACE_ENTERED_BY"    => session()->get('USR_ID'),
                "RSV_TRACE_RESOLVED_BY"   => 0,
                "RSV_TRACE_TEXT"          => $RSV_TRACE_TEXT,
                "RSV_TRACE_STATUS"        => '1'                              
                ];

            $rules = [  'RSV_TRACE_DEPARTMENT' => ['label' => 'Department code', 'rules' => 'required'],
                        'RSV_TRACE_DATE' => ['label' => 'Date', 'rules' => 'required|checkReservationTraceDate[RSV_TRACE_DATE]', 'errors' => ['checkReservationTraceDate' => 'Trace Date should be between the Reservation dates']],
                        'RSV_TRACE_TIME' => ['label' => 'Time', 'rules' => 'required'],
                        'RSV_TRACE_TEXT' => ['label' => 'Trace Text', 'rules' => 'required']                             
                     ];          

                   

            $validate = $this->validate($rules);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
           
            $return = !empty($RSV_TRACE_ID) ? $this->DB->table('FLXY_RESERVATION_TRACES')->where('RSV_TRACE_ID', $RSV_TRACE_ID)->update($data) : $this->DB->table('FLXY_RESERVATION_TRACES')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, !empty($RSV_TRACE_ID) ? $RSV_TRACE_ID : $this->DB->insertID()) : $this->responseJson("-444", "db insert not successful", $return);
            $RSV_TRACE_ID = empty($RSV_TRACE_ID) ? $this->DB->insertID(): $RSV_TRACE_ID;

            ////////  Notifications /////////////////

            $data = [
                "NOTIFICATION_TYPE"       => 4,
                "NOTIFICATION_FROM_ID"    => session()->get('USR_ID'),
                "NOTIFICATION_DEPARTMENT" => $RSV_TRACE_DEPARTMENT,
                "NOTIFICATION_TO_ID"      => '',
                "NOTIFICATION_GUEST_ID"   => '',
                "NOTIFICATION_URL"        => '',                
                "NOTIFICATION_RESERVATION_ID" => json_encode(array($RSV_ID)),
                "NOTIFICATION_TEXT"       => $RSV_TRACE_TEXT,
                "NOTIFICATION_DATE_TIME"  => $RSV_TRACE_DATE.' '.$RSV_TRACE_TIME,
                "NOTIFICATION_READ_STATUS"=> 0,
            ];           
           
            $NOTIFICATION    =  (!empty($RSV_TRACE_NOTIFICATION_ID)) ? $this->DB->table('FLXY_NOTIFICATIONS')->where('NOTIFICATION_ID', $RSV_TRACE_NOTIFICATION_ID)->update($data) : $this->DB->table('FLXY_NOTIFICATIONS')->insert($data);    

            if(empty($RSV_TRACE_NOTIFICATION_ID)){
                $NOTIFICATION_ID =  $this->DB->insertID(); 
                $this->DB->table('FLXY_RESERVATION_TRACES')->where('RSV_TRACE_ID', $RSV_TRACE_ID)->update(['RSV_TRACE_NOTIFICATION_ID'=>$NOTIFICATION_ID]);
            }
            else{
                $this->DB->table('FLXY_NOTIFICATION_TRAIL')->delete(['NOTIF_TRAIL_NOTIFICATION_ID'=>$RSV_TRACE_NOTIFICATION_ID]); 
                $NOTIFICATION_ID = $RSV_TRACE_NOTIFICATION_ID;
            }
          

            if((isset($RSV_TRACE_DEPT_NOTIFI) && !empty($RSV_TRACE_DEPT_NOTIFI))){
                for($j=0; $j<count($RSV_TRACE_DEPT_NOTIFI);$j++){                       
                    $NOTIFI['NOTIF_TRAIL_DEPARTMENT']      = $RSV_TRACE_DEPT_NOTIFI[$j];
                    $DEPARTMENT_USERS = $this->getDepartmentUsers($RSV_TRACE_DEPT_NOTIFI[$j]);
                    foreach($DEPARTMENT_USERS as $USERS){
                        $NOTIFI['NOTIF_TRAIL_USER']            = $USERS['USR_ID'];
                        $NOTIFI['NOTIF_TRAIL_NOTIFICATION_ID'] = $NOTIFICATION_ID;
                        $NOTIFI['NOTIF_TRAIL_READ_STATUS']     = 0;
                        $NOTIFI['NOTIF_TRAIL_DATETIME']        = $RSV_TRACE_DATE.' '.$RSV_TRACE_TIME;
                        $return1 = $this->DB->table('FLXY_NOTIFICATION_TRAIL')->insert($NOTIFI);
                    }                       
                } 
            } 

            ////////  End Notifications /////////////////

            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
               
                  
            }
            echo json_encode($result);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function deleteTraces()
    {
        $RSV_TRACE_ID = $this->request->getPost('TRACE_ID');

        try {
            $return = $this->DB->table('FLXY_RESERVATION_TRACES')->delete(['RSV_TRACE_ID' => $RSV_TRACE_ID]); 
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function resolveTraces()
    {
        $RSV_TRACE_ID = $this->request->getPost('TRACE_ID');
        $RESOLVE = $this->request->getPost('resolve');
        $user_id = session()->get('USR_ID');
        if($RESOLVE == 2)
        $data = ['RSV_TRACE_RESOLVED_BY'=> '', 'RSV_TRACE_RESOLVED_ON' => '' , 'RSV_TRACE_RESOLVED_TIME' => ''];
        else
        $data = ['RSV_TRACE_RESOLVED_BY'=> $user_id, 'RSV_TRACE_RESOLVED_ON' => date('Y-m-d'), 'RSV_TRACE_RESOLVED_TIME' => date('H:i:s A') ];
        try {
            $return = $this->DB->table('FLXY_RESERVATION_TRACES')->where('RSV_TRACE_ID', $RSV_TRACE_ID)->update($data);            
            echo json_encode($return);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public function getDepartmentUsers($department){
        $param = ['SYSID' => $department];
        $sql = "SELECT USR_ID
        FROM FLXY_USERS
        WHERE USR_STATUS = '1' AND USR_DEPARTMENT=:SYSID:";
        $response = $this->DB->query($sql, $param)->getResultArray();
        return $response;
    }


    public function roomPlan()
    {
        $data = $roomPlanSearch = [];
        $data['css_to_load'] = array("RoomPlan/FullCalendar/Core/main.min.css", "RoomPlan/FullCalendar/Timeline/main.min.css", "RoomPlan/FullCalendar/ResourceTimeline/main.min.css");
        $data['js_to_load']  = array("RoomPlan/FullCalendar/Core/main.min.js","RoomPlan/FullCalendar/Interaction/main.min.js", "RoomPlan/FullCalendar/Timeline/main.min.js", "RoomPlan/FullCalendar/ResourceCommon/main.min.js","RoomPlan/FullCalendar/ResourceTimeline/main.min.js");
        $data['toggleButton_javascript']    = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript']     = blockLoader_javascript();
        $perPage = 20;
        $start = 0; 
        $segments = $this->uri->getSegments();
        $page = empty($segments[1]) ? 1 : $segments[1]; 
        $offset = ( $page == 1 ) ?  0: ($page - 1) * $perPage;  
        
        $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);    
        $segments = explode('/', $urlArray);      
        $numSegments = end($segments) == 'roomPlan' ? count($segments) : count($segments) - 1;          

        $clear = $this->request->getPost('SEARCH_CLEAR');
        if($clear == 1){
            $this->session->remove('roomPlanSearch');
        }
            
        $roomPlanSearch = $this->session->get('roomPlanSearch');

        $data['SEARCH_DATE']             = ($this->request->getPost('SEARCH_DATE') == '' && $page > 1)? ((isset($roomPlanSearch['SEARCH_DATE']) && $roomPlanSearch['SEARCH_DATE'] !='') ? $roomPlanSearch['SEARCH_DATE']:'') : $this->request->getPost('SEARCH_DATE');            

        $data['SEARCH_ROOM_TYPE']        = ($this->request->getPost('SEARCH_ROOM_TYPE') == '' && $page > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_TYPE']) && $roomPlanSearch['SEARCH_ROOM_TYPE'] != '') ? $roomPlanSearch['SEARCH_ROOM_TYPE'] :'') : $this->request->getPost('SEARCH_ROOM_TYPE');

        $data['SEARCH_ROOM_CLASS']       = ($this->request->getPost('SEARCH_ROOM_CLASS') == '' && $page > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_CLASS']) && $roomPlanSearch['SEARCH_ROOM_CLASS'] != '') ? $roomPlanSearch['SEARCH_ROOM_CLASS'] :'') : $this->request->getPost('SEARCH_ROOM_CLASS');

        $data['SEARCH_ROOM']             =  ($this->request->getPost('SEARCH_ROOM') == '' && $page > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM']) && $roomPlanSearch['SEARCH_ROOM'] != '') ? $roomPlanSearch['SEARCH_ROOM'] :'') : $this->request->getPost('SEARCH_ROOM');

        $data['SEARCH_ROOM_STATUS']      = ($this->request->getPost('SEARCH_ROOM_STATUS') == '' && $page > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_STATUS']) && $roomPlanSearch['SEARCH_ROOM_STATUS'] != '') ? $roomPlanSearch['SEARCH_ROOM_STATUS'] : '') :$this->request->getPost('SEARCH_ROOM_STATUS');
        
        $data['SEARCH_ROOM_FLOOR']       = ($this->request->getPost('SEARCH_ROOM_FLOOR') == '' && $page > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_FLOOR']) && $roomPlanSearch['SEARCH_ROOM_FLOOR'] != '') ? $roomPlanSearch['SEARCH_ROOM_FLOOR'] :'') : $this->request->getPost('SEARCH_ROOM_FLOOR');   

        $data['SEARCH_ASSIGNED_ROOMS']       = (NULL == $this->request->getPost('SEARCH_ASSIGNED_ROOMS') && $page > 1 ) ? ((isset($roomPlanSearch['SEARCH_ASSIGNED_ROOMS']) && $roomPlanSearch['SEARCH_ASSIGNED_ROOMS'] != '') ? $roomPlanSearch['SEARCH_ASSIGNED_ROOMS'] :'') : $this->request->getPost('SEARCH_ASSIGNED_ROOMS'); 

        $data['SEARCH_UNASSIGNED_ROOMS']       = (NULL == $this->request->getPost('SEARCH_UNASSIGNED_ROOMS') && $page > 1) ? ((isset($roomPlanSearch['SEARCH_UNASSIGNED_ROOMS']) && $roomPlanSearch['SEARCH_UNASSIGNED_ROOMS'] != '') ? $roomPlanSearch['SEARCH_UNASSIGNED_ROOMS'] :'') : $this->request->getPost('SEARCH_UNASSIGNED_ROOMS');    
        
        $this->session->set('roomPlanSearch', $data); 
        $roomPlanSearch = $this->session->get('roomPlanSearch');           

        $data['RoomReservations']   = $this->getReservations(); 
        $response                   = $this->roomplanResources($offset, $perPage);
        $data['RoomResources']      = $response['response'];
        $totalResources             = $response['responseCount'];
        $data['pager_links']        = $this->pager->makeLinks($page, $perPage, $totalResources,'custom_pagination_full',$numSegments);
        $data['RoomOOS']            = $this->getRoomOOS(); 
        $data['title']              = 'Room Plan';
        
        return view('Reservation/RoomPlan', $data);

    }


    public function ItemResources(){
        $response = NULL;
        $sql = "SELECT ITM_ID, ITM_CODE, ITM_NAME         
                FROM FLXY_ITEM WHERE ITM_STATUS = '1' ORDER BY ITM_ID ASC";       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0)
        $response = $this->DB->query($sql)->getResultArray();
        return $response;
    }

    public function ItemCalendar(){
        $response = NULL;
        
        $sql = "SELECT ITM_ID, ITM_QTY_IN_STOCK         
                FROM FLXY_ITEM ORDER BY ITM_ID ASC";       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->DB->query($sql)->getResultArray();
            $j=0;
            foreach($response as $row){
                $ITM_BEGIN_DATE    = strtotime('2022-01-01');
                $ITM_END_DATE      = strtotime('2032-12-31'); 
                $DATEDIFF = $ITM_END_DATE - $ITM_BEGIN_DATE;
                $AVAILABLE_DAYS = round($DATEDIFF / (60 * 60 * 24));
                $ITM_DLY_QTY = 0;
                $ITEM_RESERVED = 0;
                for($i = 1; $i <= ($AVAILABLE_DAYS+1); $i++ ){
                    $sCurrentDate = gmdate("Y-m-d", strtotime("+$i day", $ITM_BEGIN_DATE)); 
                    $CurrentDate = strtotime($sCurrentDate); 
                    $items[$j][$i]['ITM_ID'] = $row['ITM_ID'];
                    //$ITM_DLY_QTY = $this->checkItemDailyInventory($row['ITM_ID'],$sCurrentDate );
                    $ITEM_RESERVED = $this->checkItemReserved($row['ITM_ID'],$sCurrentDate );

                    /////// Item Total quantity and Item Available quantity are depends on the Items table and Daily inventory table


                    // if($ITM_DLY_QTY > 0){
                    //     $ITM_REMAINING_STOCK = $ITM_DLY_QTY - $ITEM_RESERVED;
                    //     $ITM_QTY_IN_STOCK    = $ITM_DLY_QTY;

                    // }
                    // else{
                        $ITM_REMAINING_STOCK = $row['ITM_QTY_IN_STOCK'] - $ITEM_RESERVED;
                        $ITM_QTY_IN_STOCK    = $row['ITM_QTY_IN_STOCK'];
                    //}
                                    
                    $items[$j][$i]['ITM_REMAINING_STOCK'] = $ITM_REMAINING_STOCK;
                    $items[$j][$i]['ITM_QTY_IN_STOCK']    = $ITM_QTY_IN_STOCK;
                    $items[$j][$i]['START'] = $sCurrentDate;
                    $items[$j][$i]['END'] = $sCurrentDate;
                }
                $j++;

            }
        }
    return $items;
    }

    public function checkItemReserved($item_id, $sCurrentDate){
        $response = NULL;
        $total_qty = 0;
        $sql = "SELECT RSV_ITM_QTY FROM FLXY_RESERVATION_ITEM WHERE RSV_ITM_ID = '$item_id' AND '$sCurrentDate' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE";                 
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0) {
            $response = $this->DB->query($sql)->getResultArray(); 
            foreach($response as $resp){
                $total_qty += $resp['RSV_ITM_QTY'];
            }
        }
        return $total_qty;   
    }

   
    public function roomplanResources($PAGE, $TOTAL)
    { 
        
        $cond = $where = $join = '';
        $clear = $this->request->getPost('SEARCH_CLEAR');

        $roomPlanSearch = $this->session->get('roomPlanSearch');

         $SEARCH_DATE  = (date("Y-m-d",strtotime($this->request->getPost('SEARCH_DATE'))) == '')? ((isset($roomPlanSearch['SEARCH_DATE']) && $roomPlanSearch['SEARCH_DATE'] !='') ? $roomPlanSearch['SEARCH_DATE']:'') : date("Y-m-d",strtotime($this->request->getPost('SEARCH_DATE')));        
        
        if((isset($clear) && $clear == '0') || $SEARCH_DATE != ''){ 

            $SEARCH_ROOM_TYPE        = ($this->request->getPost('SEARCH_ROOM_TYPE') == '' && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_TYPE']) && $roomPlanSearch['SEARCH_ROOM_TYPE'] != '') ? $roomPlanSearch['SEARCH_ROOM_TYPE'] :'') : $this->request->getPost('SEARCH_ROOM_TYPE');

            $SEARCH_ROOM_CLASS      = ($this->request->getPost('SEARCH_ROOM_CLASS') == '' && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_CLASS']) && $roomPlanSearch['SEARCH_ROOM_CLASS'] != '') ? $roomPlanSearch['SEARCH_ROOM_CLASS'] :'') : $this->request->getPost('SEARCH_ROOM_CLASS');

            $SEARCH_ROOM            =  ($this->request->getPost('SEARCH_ROOM') == '' && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM']) && $roomPlanSearch['SEARCH_ROOM'] != '') ? $roomPlanSearch['SEARCH_ROOM'] :'') : $this->request->getPost('SEARCH_ROOM');

            $SEARCH_ROOM_STATUS      = ($this->request->getPost('SEARCH_ROOM_STATUS') == '' && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_STATUS']) && $roomPlanSearch['SEARCH_ROOM_STATUS'] != '') ? $roomPlanSearch['SEARCH_ROOM_STATUS'] : '') :$this->request->getPost('SEARCH_ROOM_STATUS');
            
            $SEARCH_ROOM_FLOOR       = ($this->request->getPost('SEARCH_ROOM_FLOOR') == '' && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_ROOM_FLOOR']) && $roomPlanSearch['SEARCH_ROOM_FLOOR'] != '') ? $roomPlanSearch['SEARCH_ROOM_FLOOR'] :'') : $this->request->getPost('SEARCH_ROOM_FLOOR');

            $SEARCH_ASSIGNED_ROOMS       = (NULL == $this->request->getPost('SEARCH_ASSIGNED_ROOMS') && $PAGE > 1 ) ? ((isset($roomPlanSearch['SEARCH_ASSIGNED_ROOMS']) && $roomPlanSearch['SEARCH_ASSIGNED_ROOMS'] != '') ? $roomPlanSearch['SEARCH_ASSIGNED_ROOMS'] :'') : $this->request->getPost('SEARCH_ASSIGNED_ROOMS'); 

            $SEARCH_UNASSIGNED_ROOMS       = (NULL == $this->request->getPost('SEARCH_UNASSIGNED_ROOMS') && $PAGE > 1) ? ((isset($roomPlanSearch['SEARCH_UNASSIGNED_ROOMS']) && $roomPlanSearch['SEARCH_UNASSIGNED_ROOMS'] != '') ? $roomPlanSearch['SEARCH_UNASSIGNED_ROOMS'] :'') : $this->request->getPost('SEARCH_UNASSIGNED_ROOMS'); 
    
            
            
        $cond .= " WHERE 1=1 ";

        if($SEARCH_ROOM_TYPE != '' || $SEARCH_ROOM != '' || $SEARCH_ROOM_STATUS != '' || $SEARCH_ROOM_FLOOR != '')
        {            
            $cond .= ($SEARCH_ROOM_TYPE != '') ?" AND RM_TYPE_REF_ID = '".$SEARCH_ROOM_TYPE."'":'';
            $cond .= ($SEARCH_ROOM_STATUS != '')?" AND RM_STATUS_ID = '".$SEARCH_ROOM_STATUS."'":'';
            $cond .= ($SEARCH_ROOM != '')?" AND RM_ID = '".$SEARCH_ROOM."'":'';
            $cond .= ($SEARCH_ROOM_FLOOR != '')?" AND RM.RM_FL_ID = '".$SEARCH_ROOM_FLOOR."'":'';
        } 
       

        if((isset($SEARCH_ASSIGNED_ROOMS) && ($SEARCH_ASSIGNED_ROOMS == 'on' || $SEARCH_ASSIGNED_ROOMS == 'off')) && (isset($SEARCH_UNASSIGNED_ROOMS) && ($SEARCH_UNASSIGNED_ROOMS == 'on' || $SEARCH_ASSIGNED_ROOMS == 'off')) ){
            
        }
        else if(isset($SEARCH_ASSIGNED_ROOMS) && $SEARCH_ASSIGNED_ROOMS == 'on'){
                
                $join = "LEFT JOIN FLXY_RESERVATION ON RESV_ROOM_ID = RM_ID"; 
                $where = ($SEARCH_DATE != '')?" AND ('".$SEARCH_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE)":"";
        } 
        else if(isset($SEARCH_UNASSIGNED_ROOMS) && $SEARCH_UNASSIGNED_ROOMS == 'on'){  
            $where .= " AND RM_ID NOT IN (SELECT RM_ID  FROM FLXY_ROOM
            INNER JOIN FLXY_RESERVATION ON RESV_ROOM_ID = RM_ID WHERE 1=1  AND ('".$SEARCH_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE))";
        }            
        
      } 
    
        $data['responseCount'] = 0;
        $data['response'] = NULL;

        $sql1 = "SELECT RM_ID, RM_NO, RM_TYPE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
         FROM FLXY_ROOM 
         LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
        
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

        LEFT JOIN FLXY_ROOM_FLOOR RM ON RM.RM_FL_CODE = RM_FLOOR_PREFERN 
         ".$join.$cond.$where."

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC OFFSET $PAGE ROWS FETCH NEXT $TOTAL ROWS ONLY";   


        $sql2 = "SELECT RM_ID, RM_NO, RM_TYPE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
         FROM FLXY_ROOM 
         LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
        
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

        LEFT JOIN FLXY_ROOM_FLOOR RM ON RM.RM_FL_CODE = RM_FLOOR_PREFERN 
         ".$join.$cond.$where."

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC"; 

        $data['responseCount'] = $this->DB->query($sql2)->getNumRows();

        if($data['responseCount'] > 0)
        $data['response'] = $this->DB->query($sql1)->getResultArray();
        return $data;
    }

    public function roomplanResourceStatus($firstStatus, $secondStatus)
    {
        $data = $response = NULL;
        $sql = "SELECT RM_ID, RM_NO, RM_TYPE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
        FROM FLXY_ROOM 
        INNER JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG WHERE (RM_STAT_ROOM_STATUS = '$firstStatus' OR RM_STAT_ROOM_STATUS = '$secondStatus')
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        INNER JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
        
        INNER JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC";       
        $responseCount = $this->DB->query($sql)->getNumRows();
        
        return $responseCount;
    }



    public function getReservations(){
        // $clear = $this->request->getPost('SEARCH_CLEAR');
        // $cond = '';
        // $SEARCH_ASSIGNED_ROOMS   = $this->request->getPost('SEARCH_ASSIGNED_ROOMS');
        // $SEARCH_UNASSIGNED_ROOMS = $this->request->getPost('SEARCH_UNASSIGNED_ROOMS');
        // if(isset($clear) && $clear == '0'){
        //     $SEARCH_DATE             =  date("Y-m-d",strtotime($this->request->getPost('SEARCH_DATE')));
                    
        //     $SEARCH_DATE_WEEK        = date("Y-m-d",strtotime($SEARCH_DATE."+7 day"));
        //     $SEARCH_ROOM_TYPE        = $this->request->getPost('SEARCH_ROOM_TYPE');           
            
        //     $cond .= ($SEARCH_DATE != date("Y-m-d",time()))?" AND '".$SEARCH_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE":" AND RESV_ARRIVAL_DT >= '".date('Y-m-d',time())."'";

        //     $cond .= ($SEARCH_ROOM_TYPE != '')?" AND RM_TYPE_REF_ID = '".$SEARCH_ROOM_TYPE."'":'';

        //     if($SEARCH_ASSIGNED_ROOMS != '' && $SEARCH_UNASSIGNED_ROOMS != '') {}
        //     else{
        //         $cond .= ($SEARCH_ASSIGNED_ROOMS != '')?" AND (RESV_ROOM_ID != '0' OR RESV_ROOM_ID != NULL)":'';
        //         $cond .= ($SEARCH_UNASSIGNED_ROOMS != '')?" AND (RESV_ROOM_ID = '0' OR RESV_ROOM_ID = NULL OR RESV_ROOM_ID = '')":'';
        //             if($SEARCH_ASSIGNED_ROOMS !='' && $SEARCH_DATE == '' ){
        //                 $cond .= " AND RESV_ARRIVAL_DT >= '".date('Y-m-d',time())."'";
        //             }
        //     }
        // }

        $response = NULL;
        $sql = "SELECT RESV_ID, RESV_ARRIVAL_DT, RESV_NIGHT, RESV_DEPARTURE, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, RESV_ROOM, RESV_STATUS, RM_ID FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID LEFT JOIN FLXY_ROOM ON RM_ID = RESV_ROOM_ID WHERE RESV_STATUS != 'Cancelled' ORDER BY RM_ID ASC";    
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->DB->query($sql)->getResultArray();           
        }
        return $response;
    }

    public function getRoomOOS(){
        $response = NULL;
        $sql = "SELECT OOOS_ID, ROOMS, STATUS_FROM_DATE, STATUS_TO_DATE, ROOM_STATUS, CONCAT_WS(' - ', RM_STATUS_CODE, RM_STATUS_CHANGE_CODE) AS REASON FROM FLXY_ROOM_OOOS INNER JOIN FLXY_ROOM_STATUS_MASTER ON RM_STATUS_ID = ROOM_STATUS INNER JOIN FLXY_ROOM_STATUS_CHANGE_REASON ON ROOM_CHANGE_REASON = RM_STATUS_CHANGE_ID ";       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->DB->query($sql)->getResultArray();           
        }
      return $response;
    }

    public function getAllReservations($RESV_ID){
        $response = NULL;
        $sql = "SELECT RESV_ID, RESV_ARRIVAL_DT, RESV_NIGHT, RESV_DEPARTURE, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, RESV_ROOM, RESV_STATUS, RM_ID,RESV_ROOM_ID FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID INNER JOIN FLXY_ROOM ON RM_ID = RESV_ROOM_ID WHERE RESV_STATUS != 'Cancelled' AND RESV_ID !=".$RESV_ID;       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->DB->query($sql)->getResultArray();           
        }
        return $response;
    }   


    public function updateRoomPlan(){
        $RESV_ID   = $this->request->getPost('RESV_ID');
        $START     = $this->request->getPost('START');
        $END       = $this->request->getPost('END');
        $OLD_ROOM  = $this->request->getPost('OLD_ROOM');
        $NEW_ROOM  = $this->request->getPost('NEW_ROOM');
        $OLD_ROOM_ID  = $this->request->getPost('OLD_ROOM_ID');
        $NEW_ROOM_ID  = $this->request->getPost('NEW_ROOM_ID');
        $data = [];

        $reservationExists = $this->checkReservations($RESV_ID, $START, $END);
        if($reservationExists == 0){
            $reservations = $this->getAllReservations($RESV_ID);
            if(!empty($reservations)){
                foreach($reservations as $resv){
                    $ALL_RESV_ARRIVAL_DT[] = strtotime($resv['RESV_ARRIVAL_DT']);
                    $ALL_RESV_DEPARTURE[]  = strtotime($resv['RESV_DEPARTURE']);
                    $RESV_ROOM_ID[]        = $resv['RESV_ROOM_ID'];
                }  
            if($NEW_ROOM_ID != '' || $OLD_ROOM_ID != '')  {
                $NEW_ROOM_TYPE_DATA = $this->getRoomType($NEW_ROOM_ID);
                $OLD_ROOM_TYPE      = $this->getRoomType($OLD_ROOM_ID);
                $NEW_ROOM_TYPE      = $NEW_ROOM_TYPE_DATA['RM_TY_CODE'];
                $NEW_ROOM_TYPE_ID   = $NEW_ROOM_TYPE_DATA['RM_TY_ID'];   
            }

            //// FIRST CASE
           ///// IF THE RESERVATION IS Checked-In THEN WE CAN'T CHANGE THE DATES
            $sql = "SELECT RESV_STATUS, RESV_ARRIVAL_DT, RESV_NIGHT, RESV_DEPARTURE, RESV_ROOM_ID, RESV_ROOM, RESV_RM_TYPE_ID FROM FLXY_RESERVATION WHERE RESV_ID = ".$RESV_ID;       
            $responseCount = $this->DB->query($sql)->getNumRows();
            if($responseCount > 0){
                $row = $this->DB->query($sql)->getRow();
                $START_DATE = explode('T',$START); 
                $END_DATE = explode('T',$END);
                $RESV_ARRIVAL_DT = strtotime(date("Y-m-d", strtotime($row->RESV_ARRIVAL_DT)));
                $RESV_DEPARTURE  = strtotime(date("Y-m-d", strtotime($row->RESV_DEPARTURE)));
                $RESV_STATUS     = $row->RESV_STATUS;
                $RESV_ROOM_ID    = $row->RESV_ROOM_ID;
                $RESV_RM_TYPE_ID = $row->RESV_RM_TYPE_ID;
                $RESV_ROOM       = $row->RESV_ROOM;
                $START           = strtotime($START_DATE[0]);
                $END             = strtotime($END_DATE[0]);

                $START_OVERLAP   = date('Y-m-d',  $START);
                $END_OVERLAP     = date('Y-m-d', $END);

                if($NEW_ROOM_ID == ''){
                    $ROOM_ID = $RESV_ROOM_ID;
                    $ROOM_NO = $RESV_ROOM;
                }            
                else{
                    $ROOM_ID = $NEW_ROOM_ID;
                    $ROOM_NO = $NEW_ROOM;
                }
    
            $overlappedResv = $this->dateExistsOverlap($RESV_ID,$START_OVERLAP,$END_OVERLAP, $ROOM_ID );
            $RoomStatusOOS = $this->roomStatusOOSOverlap($START_OVERLAP, $END_OVERLAP, $ROOM_ID);

                if($RESV_STATUS === "Checked-In" ){
                    if($START != $RESV_ARRIVAL_DT && $END != $RESV_DEPARTURE){
                        $data['status_message'] = "Can't change the dates. Already checked-in";
                        $data['status'] = 1;
                    }
                    else if($overlappedResv > 0){
                        $data['status_message'] = "Can't change the room. Already Booked";
                        $data['status'] = 1;
                    }
                    else if($RoomStatusOOS > 0){
                        $data['status_message'] = "Can't change the dates. Room is Out of Service/Out of Order";
                        $data['status'] = 1;
                    }
                    else{
                        $this->updateRoomReservation($RESV_ID,$ROOM_ID,$ROOM_NO,'','');
                        $data['status_message'] = "Successfully moved to new room";
                        $data['status'] = 0;
                    }
                }
                else if($row->RESV_STATUS === "Due Pre Check-In" || $row->RESV_STATUS === "Pre Checked-In"){
                    if($overlappedResv > 0){
                        $data['status_message'] = "Can't change the room. Already Booked";
                        $data['status'] = 1;
                    }
                    else if($RoomStatusOOS > 0){
                        $data['status_message'] = "Can't change the room. Room is Out of Service/Out of Order";
                        $data['status'] = 1;
                    }
                    else if($OLD_ROOM !='' && ($OLD_ROOM != $NEW_ROOM)){
                        $data['status_message'] = "Do you want to change the room no from ".$OLD_ROOM." to ".$NEW_ROOM." ?";
                        $data['status'] = 2;
                        $data['status_type'] = "room_no_type";
                        $data['OLD_ROOM_TYPE']   = $OLD_ROOM_TYPE;
                        $data['NEW_ROOM_TYPE']   = $NEW_ROOM_TYPE;
                        $data['OLD_RESV_RM_TYPE_ID'] = $RESV_RM_TYPE_ID;
                        $data['NEW_RESV_RM_TYPE_ID'] = $NEW_ROOM_TYPE_ID;
                        
                    }
                    // else if($OLD_ROOM_TYPE != $NEW_ROOM_TYPE){
                    //     $data['status_message'] = "Room type changed to  ".$NEW_ROOM_TYPE.". Do you want to continue?";
                    //     $data['status'] = 2;
                    //     $data['status_type'] = "room_type";
                    // }
                    else{
                        $output = $this->updateRoomReservation($RESV_ID,$ROOM_ID,$ROOM_NO,$START_OVERLAP,$END_OVERLAP);
                        if($output){
                        $data['status_message'] = "Successfully moved to new room: ".$ROOM_NO;
                        $data['status'] = 0;
                        }else{
                            $data['status_message'] = "Error";
                            $data['status'] = 1;
                        }
                    }
                    
                    
                }         
                    
            }
        }
    }
        else{
            $data['status_message'] = "Can't change the room. already booked";
            $data['status'] = 1;
        }

        echo json_encode($data);
    }


public function changeReservationDates(){
    $RESV_ID      = $this->request->getPost('RESV_ID');
    $START        = $this->request->getPost('START');
    $END          = $this->request->getPost('END');
    $START_DATE   = explode('T',$START); 
    $END_DATE     = explode('T',$END);
    $START        = strtotime($START_DATE[0]);
    $END          = strtotime($END_DATE[0]);

    $START_OVERLAP   = date('Y-m-d',  $START );
    $END_OVERLAP     = date('Y-m-d', $END);
        //// FIRST CASE
        ///// IF THE RESERVATION IS Checked-In THEN WE CAN'T CHANGE THE DATES
        $sql = "SELECT RESV_STATUS, RESV_ARRIVAL_DT, RESV_NIGHT, RESV_DEPARTURE, RESV_ROOM_ID, RESV_ROOM  FROM FLXY_RESERVATION WHERE RESV_ID = ".$RESV_ID;       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0){
            $row = $this->DB->query($sql)->getRow();
            $RESV_ARRIVAL_DT = $row->RESV_ARRIVAL_DT;
            $RESV_DEPARTURE  = $row->RESV_DEPARTURE;
            $RESV_STATUS     = $row->RESV_STATUS;
            $RESV_ROOM_ID    = $row->RESV_ROOM_ID;
            $RESV_ROOM       = $row->RESV_ROOM;
            $overlappedDates = $this->dateExistsOverlap($RESV_ID, $START_OVERLAP, $END_OVERLAP, $RESV_ROOM_ID, $RESV_ROOM );
            $RoomStatusOOS = $this->roomStatusOOSOverlap($START_OVERLAP, $END_OVERLAP, $RESV_ROOM_ID);
            if($RESV_STATUS === "Checked-In" ){
                $data['status_message'] = "You can't change the dates. Already checked-in";
                $data['status'] = 1;
            } 
            else if($row->RESV_STATUS === "Due Pre Check-In" || $row->RESV_STATUS === "Pre Checked-In"){
                if($overlappedDates > 0){
                    $data['status_message'] = "Can't change the dates. Already Booked";
                    $data['status'] = 1;
                }
                else if($RoomStatusOOS > 0){
                    $data['status_message'] = "Can't change the dates. Room is Out of Service/Out of Order";
                    $data['status'] = 1;
                }
                else{
                    $output = $this->updateRoomReservation($RESV_ID,$RESV_ROOM_ID,$RESV_ROOM,$START_OVERLAP,$END_OVERLAP);
                    if($output){
                    $data['status_message'] = "Successfully moved to new dates";
                    $data['status'] = 0;
                    }else{
                        $data['status_message'] = "Error";
                        $data['status'] = 1;
                    }
                }
                
                
            }          
                 
        }
        echo json_encode($data);
}


public function checkArrivalExists(){
    $ARRIVAL_DATE  = $this->request->getPost('ARRIVAL'); 
    $ROOM_ID  = $this->request->getPost('ROOM_ID');     
    $cond = "RM_ID = '".$ROOM_ID."'";
    $RM_TYPE_ID =  getValueFromTable('RM_TYPE_REF_ID',$cond,'FLXY_ROOM'); 
    $cond = "RM_TY_ID = '".$RM_TYPE_ID."'";
    $RESV_RM_TYPE =  getValueFromTable('RM_TY_CODE',$cond,'FLXY_ROOM_TYPE'); 

    $data = [];
    $room_assign= '';
    $sql = "SELECT RESV_ID,RESV_DEPARTURE,RESV_ARRIVAL_DT,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, RESV_STATUS, RESV_ADULTS, RESV_CHILDREN,RESV_RM_TYPE_ID  FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID WHERE RESV_ARRIVAL_DT = '".$ARRIVAL_DATE."' AND RESV_ROOM_ID = 0 AND RESV_RM_TYPE_ID = '".$RM_TYPE_ID."' AND (RESV_STATUS LIKE 'Due Pre Check-In' OR RESV_STATUS LIKE 'Pre Check-In')"; 
    $response = $this->DB->query($sql)->getResultArray();     
    $responseCount = $this->DB->query($sql)->getNumRows();
    if($responseCount > 0){
        
        foreach($response as $value){
            $room_assign.= <<<EOD
            <tr class="RoomID_{$value['RESV_ID']}" data-resv_id="{$value['RESV_ID']}" data-roomtype_id="{$value['RESV_RM_TYPE_ID']}" data-arrival_date="{$value['RESV_ARRIVAL_DT']}"> 
                <td>{$RESV_RM_TYPE}</td>               
                <td>{$value['FULLNAME']}</td>
                <td>{$value['RESV_ARRIVAL_DT']}</td>
                <td>{$value['RESV_DEPARTURE']}</td>
                <td>{$value['RESV_STATUS']}</td>
                <td>{$value['RESV_ADULTS']}</td>
                <td>{$value['RESV_CHILDREN']}</td>                
            </tr>
        EOD;
        }

        $data['room_assign'] = $room_assign;
        $data['status'] = 1;
    }               
    echo json_encode($data);
}

public function getAllVacantRooms(){
    $ROOM_TYPE_ID  = $this->request->getPost('ROOM_TYPE_ID');
    $ARRIVAL_DATE  = $this->request->getPost('ARRIVAL_DATE'); 

    $data = [];
    $roomsArray= [];
    $vacant_rooms = '';
    $data = $response = NULL;
        $sql = "SELECT RM_ID, RM_NO, RM_DESC, RM_FEATURE, RF.RM_FL_DESC, RT.RM_TY_CODE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
        FROM FLXY_ROOM 
        LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID

        LEFT JOIN FLXY_ROOM_FLOOR RF ON RF.RM_FL_CODE = FLXY_ROOM.RM_FLOOR_PREFERN

        LEFT JOIN FLXY_ROOM_TYPE RT ON RT.RM_TY_ID = FLXY_ROOM.RM_TYPE_REF_ID
        
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS WHERE RM_TYPE_REF_ID = ".$ROOM_TYPE_ID."

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_DESC,RM_FL_DESC,RM_FEATURE,RM_TY_CODE,RM_STATUS_CODE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC";   
            
        $responseCount = $this->DB->query($sql)->getNumRows();

        if($responseCount > 0){
            $response = $this->DB->query($sql)->getResultArray();
            $sqlRooms = "SELECT RESV_ROOM_ID FROM FLXY_RESERVATION WHERE RESV_ROOM_ID > 0 AND RESV_ARRIVAL_DT = '$ARRIVAL_DATE'";             
            $responseRooms = $this->DB->query($sqlRooms)->getResultArray(); 
            $responseRoomsCount = $this->DB->query($sql)->getNumRows();

            if($responseRoomsCount > 0 ){
                foreach($responseRooms as $value){
                    $roomsArray[] = $value['RESV_ROOM_ID'];
                }
            }

            foreach($response as $value){
                if(!in_array($value['RM_ID'],$roomsArray)){
                    $RM_STATUS_CODE = ($value['RM_STATUS_CODE'] == NULL) ? 'Dirty': $value['RM_STATUS_CODE'];
                    $vacant_rooms.= <<<EOD
                    <tr data-room_id="{$value['RM_ID']}" > 
                        <td><a href="javascript:;" data_roomid="{$value['RM_ID']}"  class"btn btn-primary"><i class="fa-solid fa-list-check"></i> Assign</a></td>
                        <td>{$value['RM_NO']}</td>
                        <td>{$value['RM_TY_CODE']}</td>
                        <td>{$RM_STATUS_CODE}</td>
                        <td>{$value['RM_FL_DESC']}</td>
                        <td>{$value['RM_FEATURE']}</td>                                    
                    </tr>
                    EOD;
                }
            }

            $data['vacant_rooms'] = $vacant_rooms;
            $data['status'] = 1;
        }
                  
    echo json_encode($data);
}

public function updateRoomAssign(){
    $Resv_ID  = $this->request->getPost('Resv_ID');
    $Room_ID  = $this->request->getPost('Room_ID');
    
    if( $Resv_ID != '' && $Room_ID != ''){        
        $cond    = "RM_ID = '".$Room_ID."'";
        $Room_No =  getValueFromTable('RM_NO',$cond,'FLXY_ROOM'); 
        $data    = array('RESV_ROOM' => $Room_No, 'RESV_ROOM_ID' => $Room_ID);
        $response  = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $Resv_ID)->update($data);
        if($response == 1){
            $output['status'] = 1;
            $output['status_message'] = 'Successfully assigned the room';
        }
        else{
            $output['status'] = 0;
            $output['status_message'] = 'Failed to assign room';
        }
        echo json_encode($output);
    }


}



public function checkReservationExists(){
    $RESV_ID  = $this->request->getPost('ID');    
    $data = [];
    $data['status'] = 0;
    $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE RESV_ID = '".$RESV_ID."'";       
    $responseCount = $this->DB->query($sql)->getNumRows();
    if($responseCount > 0)       
        $data['status'] = 1;
                 
    echo json_encode($data);
}


public function checkReservations($RESV_ID, $START, $END){
    $status = 0;
    $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE RESV_ID != '".$RESV_ID."' AND RESV_ARRIVAL_DT = '".$START."' AND RESV_DEPARTURE = '".$END."'";       
    $responseCount = $this->DB->query($sql)->getNumRows();
    if($responseCount > 0)       
        $status = 1;
                 
    return $status; 
}



public function updateRoomReservation($RESV_ID,$NEW_ROOM_ID,$NEW_ROOM,$START_OVERLAP,$END_OVERLAP){
    if( $RESV_ID != '' ){
        
        if($START_OVERLAP != '' && $END_OVERLAP!= '')      
          $data = array('RESV_ROOM' => $NEW_ROOM, 'RESV_ROOM_ID' => $NEW_ROOM_ID, 'RESV_ARRIVAL_DT' => $START_OVERLAP, 'RESV_DEPARTURE' => $END_OVERLAP);
        
        else
          $data = array('RESV_ROOM' => $NEW_ROOM, 'RESV_ROOM_ID' => $NEW_ROOM_ID);

        $output = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $RESV_ID)->update($data);
        return $output;
    }
}

public function dateExistsOverlap($RESV_ID,$START,$END,$NEW_ROOM_ID){
    $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE ((RESV_ARRIVAL_DT BETWEEN '$START' AND '$END') OR (RESV_DEPARTURE BETWEEN '$START' AND '$END')) AND RESV_ROOM_ID = '$NEW_ROOM_ID' AND RESV_STATUS != 'Checked-Out' AND RESV_ID != ".$RESV_ID; 

    //$sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE (((RESV_ARRIVAL_DT BETWEEN '$START' AND '$END') AND (RESV_DEPARTURE BETWEEN '$START' AND '$END')) OR  (('$START' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE) AND ('$END' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE))) AND RESV_ROOM_ID = '$NEW_ROOM_ID' AND RESV_STATUS != 'Checked-Out' AND RESV_ID != ".$RESV_ID; 
   $responseCount = $this->DB->query($sql)->getNumRows();
   return $responseCount;
}

public function roomStatusOOSOverlap($START_OVERLAP, $END_OVERLAP, $ROOM_ID){    
  $sql = "SELECT OOOS_ID FROM FLXY_ROOM_OOOS WHERE (('$START_OVERLAP' BETWEEN STATUS_FROM_DATE AND STATUS_TO_DATE) OR ('$END_OVERLAP' BETWEEN STATUS_FROM_DATE AND STATUS_TO_DATE )) AND ROOMS = '$ROOM_ID'";
   $responseCount = $this->DB->query($sql)->getNumRows();
   return $responseCount;
}


public function getRoomStatistics(){
        $response = NULL;
        $items = array();     
        $START        = $this->request->getPost('start');
        $END          = $this->request->getPost('end');
        $START_DATE   = explode('T',$START); 
        $END_DATE     = explode('T',$END);
        $start        = strtotime($START_DATE[0]);
        $end          = strtotime($END_DATE[0]);

        for($i=1; $i<=5; $i++){            
            $BEGIN_DATE     = $start;
            $END_DATE       = $end; 
            $DATEDIFF       = (int)$end-(int)$start;
            $AVAILABLE_DAYS = round($DATEDIFF / (60 * 60 * 24));
            
            for($j = 1; $j <= ($AVAILABLE_DAYS + 1); $j++ ){
                $values = [];
                $sCurrentDate = gmdate("Y-m-d", strtotime("+$j day", $BEGIN_DATE)); 

                if($i == 1){
                    $TotalRoomsReserved = $this->getTotalRoomsReserved(1, $sCurrentDate);
                } 
                else if($i == 2){
                    $TotalRoomsReserved = $this->getTotalRoomsReserved(2, $sCurrentDate);
                }
                else if($i == 3){
                    $TotalRoomsReserved = $this->getTotalRoomsReserved(3, $sCurrentDate);
                }
                else if($i == 4){
                    $TotalRoomsReserved = $this->getTotalRoomsReserved(4, $sCurrentDate);
                }
                else if($i == 5){
                    $TotalRoomsReserved = $this->getTotalRoomsReserved(5, $sCurrentDate);
                }
                $values['id'] = $j;
                $values['resourceId'] = $i;                
                $values['title'] = $TotalRoomsReserved;
                $values['start'] = $sCurrentDate;
                $values['end'] = $sCurrentDate;
                $items[] = $values;
            }   
        }
       
        echo json_encode($items);
    }

    public function getTotalRoomsReserved($value, $sCurrentDate){
        $responseCount = 0;
        if($value == 1){
            $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE ('$sCurrentDate' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE) AND (RESV_ROOM_ID !='' OR RESV_ROOM_ID != '0')";     
            $responseCount = $this->DB->query($sql)->getNumRows();
            
        }
        else if($value == 2){
            $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE ('$sCurrentDate' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE) AND (RESV_ROOM_ID !='' OR RESV_ROOM_ID != '0')";     
            $reservedRoomsCount = $this->DB->query($sql)->getNumRows();
            if($reservedRoomsCount > 0 ){
                //echo $reservedRoomsCount;
                $sql = "SELECT count(RM_ID) as COUNT FROM FLXY_ROOM";     
                $roomsCount = $this->DB->query($sql)->getRow()->COUNT;
                $OOO_OOS = $this->roomplanResourceStatus(4,5);
                $roomsCount = (int)$roomsCount-(int)$OOO_OOS;
                
                $responseCount = (number_format(@((int)$reservedRoomsCount/(int)$roomsCount)*100,2));
            }
        }

        else if($value == 3){
            $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE RESV_ARRIVAL_DT = '$sCurrentDate' AND (RESV_ROOM_ID !='' OR RESV_ROOM_ID != '0')";     
            $responseCount = $this->DB->query($sql)->getNumRows();

        }
        else if($value == 4){
            $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE RESV_ARRIVAL_DT = '$sCurrentDate' AND RESV_DEPARTURE != '$sCurrentDate'  AND (RESV_ROOM_ID !='' OR RESV_ROOM_ID != '0') ";     
            $responseCount = $this->DB->query($sql)->getNumRows();
        }
        else if($value == 5){
            $sql = "SELECT RESV_ID FROM FLXY_RESERVATION WHERE RESV_DEPARTURE = '$sCurrentDate' AND (RESV_ROOM_ID !='' OR RESV_ROOM_ID != 0)";     
            $responseCount = $this->DB->query($sql)->getNumRows();
        }   
        
        return $responseCount;

    }


    public function roomPlanList(){
        $search = $this->request->getPost("search");       
        $sql = "SELECT RM_ID, RM_NO, RM_DESC FROM FLXY_ROOM WHERE 1 = 1"; 

        if(!empty($search))
            $sql .= " AND RM_DESC like '%$search%'";
      
        $response = $this->DB->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Room</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['RM_ID'].'" data-room-id="'.$row['RM_ID'].'">'.$row['RM_NO'].'</option>';
            }
        }
        else
            $option='<option value="">No Rooms</option>';

        echo $option;
    }

    public function roomsStatusList(){

        $type = $this->request->getPost("type"); 
        
        $option = ''; 
        
        if($type == 1)

        $sql = "SELECT * FROM FLXY_ROOM_STATUS_MASTER WHERE RM_STATUS_ID = '4' OR RM_STATUS_ID = '5'"; 

        if($type == 2)

        $sql = "SELECT * FROM FLXY_ROOM_STATUS_MASTER WHERE RM_STATUS_ID != '4' AND RM_STATUS_ID != '5'"; 

        $response = $this->DB->query($sql)->getResultArray();

        if($response != NULL)
        {           
            foreach($response as $row){
                $option.= '<option value="'.$row['RM_STATUS_ID'].'" data-room-id="'.$row['RM_STATUS_ID'].'">'.$row['RM_STATUS_CODE'].'</option>';
            }
        }
        
        echo $option;
    }

    public function roomsChangeReasonList(){
        
        $search = $this->request->getPost("search");       
        $sql = "SELECT * FROM FLXY_ROOM_STATUS_CHANGE_REASON WHERE 1 = 1"; 

        if(!empty($search))
            $sql .= " AND RM_STATUS_CHANGE_CODE like '%$search%' OR RM_STATUS_CHANGE_DESC like '%$search%'";
      
        $response = $this->DB->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Reason</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['RM_STATUS_CHANGE_ID'].'" data-room-id="'.$row['RM_STATUS_CHANGE_ID'].'">'.$row['RM_STATUS_CHANGE_CODE'].' | '.$row['RM_STATUS_CHANGE_DESC'].'</option>';
            }
        }
        else
            $option='<option value="">No Reasons</option>';

        echo $option;
    }


    

    public function showRoomStatusDetails()
    {
        $roomStatusDetails = $this->getRoomStatusDetails($this->request->getPost('OOOS_ID'));
        echo json_encode($roomStatusDetails);
    }

    

    public function getRoomStatusDetails($OOOS_ID = 0)
    {
        $param = ['SYSID' => $OOOS_ID];
        $sql = "SELECT *           
                FROM dbo.FLXY_ROOM_OOOS
                WHERE OOOS_ID=:SYSID:";       

        $response = $this->DB->query($sql, $param)->getResultArray();
        return $response;
    }

  
    public function roomOOSList()
    {
        
        $mine      = new ServerSideDataTable(); 
        
        $tableName = '( SELECT OOOS_ID,ROOMS,RM_NO,STATUS_FROM_DATE,STATUS_TO_DATE,ROOM_STATUS,ROOM_RETURN_STATUS,ROOM_CHANGE_REASON,RSM.RM_STATUS_CODE AS RSM_RM_STATUS_CODE,SM.RM_STATUS_CODE AS SM_RM_STATUS_CODE,RM_STATUS_CHANGE_DESC,RM_STATUS_CHANGE_CODE,ROOM_REMARKS
        FROM
        FLXY_ROOM_OOOS INNER JOIN FLXY_ROOM_STATUS_CHANGE_REASON ON RM_STATUS_CHANGE_ID = ROOM_CHANGE_REASON INNER JOIN FLXY_ROOM ON RM_ID = ROOMS INNER JOIN FLXY_ROOM_STATUS_MASTER RSM ON RSM.RM_STATUS_ID = ROOM_STATUS LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = ROOM_RETURN_STATUS) STATUS_LIST';
    
        $columns = 'OOOS_ID,ROOMS,RM_NO,STATUS_FROM_DATE,STATUS_TO_DATE,RSM_RM_STATUS_CODE,SM_RM_STATUS_CODE,RM_STATUS_CHANGE_CODE,RM_STATUS_CHANGE_DESC,ROOM_REMARKS';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }


    public function insertRoomOOS()
    {
        try {
            $sysid   = $this->request->getPost('OOOS_ID');

            $validate = $this->validate([
                'ROOMS' => ['label' => 'Room', 'rules' => 'required|is_unique[FLXY_ROOM_OOOS.ROOMS,OOOS_ID,' . $sysid . ']'],
                'STATUS_FROM_DATE' => ['label' => 'From Date', 'rules' => 'required'],               
                'STATUS_TO_DATE' => ['label' => 'To Date', 'rules' => 'required'], 
                'ROOM_CHANGE_REASON' => ['label' => 'Reason ', 'rules' => 'required']                     
                
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
                "ROOMS" => trim($this->request->getPost('ROOMS')),
                "STATUS_FROM_DATE" => trim($this->request->getPost('STATUS_FROM_DATE')),                
                "STATUS_TO_DATE" => trim($this->request->getPost('STATUS_TO_DATE')),
                "ROOM_STATUS" => trim($this->request->getPost('ROOM_STATUS')),
                "ROOM_RETURN_STATUS" => trim($this->request->getPost('ROOM_RETURN_STATUS')),
                "ROOM_CHANGE_REASON" => trim($this->request->getPost('ROOM_CHANGE_REASON')),
                "ROOM_REMARKS" => trim($this->request->getPost('ROOM_REMARKS'))             
            ];   
            
           

           $return = !empty($sysid) ? $this->DB->table('FLXY_ROOM_OOOS')->where('OOOS_ID', $sysid)->update($data) : $this->DB->table('FLXY_ROOM_OOOS')->insert($data);

           $statusData = ['RM_STAT_ROOM_ID' => $this->request->getPost('ROOMS'), 'RM_STAT_ROOM_STATUS'=> trim($this->request->getPost('ROOM_RETURN_STATUS')),'RM_STAT_UPDATED_BY' => session()->get('USR_ID'), 'RM_STAT_UPDATED' => date("Y-m-d H:i:s") ];

           $roomStatus = $this->DB->table('FLXY_ROOM_STATUS_LOG')->insert($statusData);          

           $result = $return ? $this->responseJson("1", "0", $return, !empty($sysid) ? $sysid : $this->DB->insertID()) : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editRoomOOS()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];
        $sql = "SELECT OOOS_ID, ROOMS, STATUS_FROM_DATE, STATUS_TO_DATE,
                ROOM_STATUS,ROOM_RETURN_STATUS,ROOM_CHANGE_REASON, ROOM_REMARKS
                FROM FLXY_ROOM_OOOS
                WHERE OOOS_ID=:SYSID: ";

        $response = $this->DB->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomOOS()
    {
        $sysid = $this->request->getPost('OOOS_ID');
        try {   
                $cond = "OOOS_ID = '".$sysid."'";
                $ROOMS =  getValueFromTable('ROOMS',$cond,'FLXY_ROOM_OOOS');                
                $ROOM_RETURN_STATUS =  getValueFromTable('ROOM_RETURN_STATUS',$cond,'FLXY_ROOM_OOOS');
               
                $statusData = [ 'RM_STAT_ROOM_ID' => $ROOMS, 'RM_STAT_ROOM_STATUS'=> $ROOM_RETURN_STATUS, 
                                'RM_STAT_UPDATED_BY' => session()->get('USR_ID'), 'RM_STAT_UPDATED' => date("Y-m-d H:i:s") ];
                $roomStatus = $this->DB->table('FLXY_ROOM_STATUS_LOG')->insert($statusData);            
                $return = $this->DB->table('FLXY_ROOM_OOOS')->delete(['OOOS_ID' => $sysid]);          
                $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
                echo json_encode($result);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function reservationDepartments()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT DEPT_ID, DEPT_CODE, DEPT_DESC
                FROM FLXY_DEPARTMENT";

        if ($search != '') {
            $sql .= " WHERE DEPT_DESC LIKE '%$search%'
                    ";
        }

        $response = $this->DB->query($sql)->getResultArray();

        $option = '';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['DEPT_ID'] . '">' . $row['DEPT_CODE'] . ' | ' . $row['DEPT_DESC']  . '</option>';
        }

        return $option;
    }

    public function uploadResvAttachments()
    {
        $fileArry = $_FILES['attachmentFile'];
        echo "<pre>"; print_r($fileArry); echo "</pre>"; exit;
    }


    public function getRoomType($NEW_ROOM_ID){
        $param = ['SYSID' => $NEW_ROOM_ID];
        $sql = "SELECT RM_TY_CODE, RM_TY_ID           
                FROM dbo.FLXY_ROOM INNER JOIN FLXY_ROOM_TYPE ON RM_TY_ID = RM_TYPE_REF_ID
                WHERE RM_ID=:SYSID:";       

        $data['RM_TY_CODE'] = $this->DB->query($sql, $param)->getRow()->RM_TY_CODE;
        $data['RM_TY_ID'] = $this->DB->query($sql, $param)->getRow()->RM_TY_ID;
        return $data;
    }

    public function updateRoomPlanDetails(){
        $resv_id         = $this->request->getPost('resv_id');
        $room_id         = $this->request->getPost('room_id');
        $room_no         = $this->request->getPost('room_no');
        $room_type       = $this->request->getPost('room_type');
        $room_type_id    = $this->request->getPost('room_type_id');

              
        $return = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $resv_id)->update(['RESV_RM_TYPE_ID'=>$room_type_id, 'RESV_RM_TYPE' => $room_type,'RESV_ROOM_ID' => $room_id,'RESV_ROOM' => $room_no ]);

        $data['status'] = ($return == 1) ? '1': '2';  
        $data['status_message'] = ($return == 1) ? 'Successfully moved': 'Failed';    
        echo json_encode($data);  
 
    }

    public function updateRoomRTC(){
        $resv_id         = $this->request->getPost('resv_id');
        $room_type       = $this->request->getPost('room_type');
        $room_type_id    = $this->request->getPost('room_type_id');
              
        $return = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $resv_id)->update(['RESV_RTC_ID'=>$room_type_id, 'RESV_RTC' => $room_type ]);

        $data['status'] = ($return == 1) ? '1': '2';  
        $data['status_message'] = ($return == 1) ? 'Successfully moved': 'Failed';    
        echo json_encode($data);  
 
    }


    //////////////// Room Plan Search //////////////


    public function roomStatusList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_STATUS_ID,RM_STATUS_CODE FROM FLXY_ROOM_STATUS_MASTER WHERE RM_STATUS_CODE like '%$search%'";
        $response = $this->DB->query($sql)->getResultArray();
        $option='<option value="">Select Room Status</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_STATUS_ID'].'">'.$row['RM_STATUS_CODE'].'</option>';
        }
        echo $option;  
    }

    public function roomFloorList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_FL_ID,RM_FL_CODE, RM_FL_DESC FROM FLXY_ROOM_FLOOR WHERE RM_FL_CODE like '%$search%'";
        $response = $this->DB->query($sql)->getResultArray();
        $option='<option value="">Select Room Floor</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_FL_ID'].'" data-rm-pref="'.$row['RM_FL_CODE'].'">'.$row['RM_FL_CODE'].' - '.$row['RM_FL_DESC'].'</option>';
        }
        echo $option;  
    }

    

    public function roomClassSearchList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_CL_CODE,RM_CL_DESC FROM FLXY_ROOM_CLASS WHERE RM_CL_DESC like '%$search%'";
        $response = $this->DB->query($sql)->getResultArray();
        $option='<option value=""> Select Room Class</option>';
        foreach($response as $row){
            $option.= '<option value="'.trim($row['RM_CL_CODE']).'">'.$row['RM_CL_CODE'].'</option>';
        }
        echo $option;
    }
  

    public function roomSearchList(){
        $search      = $this->request->getPost("search");
        $room_type   = $this->request->getPost("room_type");
        $room_floor  = $this->request->getPost("room_floor");
        $room_status = $this->request->getPost("room_status");
        $COND = '';

        if(!empty($search))
            $COND .= " AND RM_DESC like '%$search%'";

        if(!empty($room_type))
            $COND .= " AND RM_TYPE_REF_ID = '$room_type'";

        if(!empty($room_floor))
            $COND .= " AND RM_FLOOR_PREFERN = '$room_floor'";
           
        if(!empty($room_status)){
            $COND .= " AND RM_STATUS_ID = '$room_status'";

            $sql = 'SELECT RM_ID, RM_NO      
            FROM FLXY_ROOM 
            LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                        ,RM_STAT_ROOM_ID
                    FROM FLXY_ROOM_STATUS_LOG
                    GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
            
            LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
            
            LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

            WHERE  1 = 1 '.$COND.'

            GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

            ORDER BY RM_ID ASC';
        }
        else{
             $sql = "SELECT RM_ID, RM_NO FROM FLXY_ROOM WHERE 1 = 1 $COND"; 
        }
        
        $response = $this->DB->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Room</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['RM_ID'].'" data-room-id="'.$row['RM_ID'].'">'.$row['RM_NO'].'</option>';
            }
        }
        else
            $option='<option value="">No Rooms</option>';

        echo $option;
    }



    public function roomTypeSearchList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_TY_ID,RM_TY_CODE,RM_TY_DESC,RM_TY_ROOM_CLASS,RM_TY_FEATURE FROM FLXY_ROOM_TYPE WHERE RM_TY_DESC like '%$search%'";
        $response = $this->DB->query($sql)->getResultArray();
        $option='<option value="">Select Room Type</option>';
        foreach($response as $row){
            $option.= '<option data-room-type-id="'.trim($row['RM_TY_ID']).'" data-feture="'.trim($row['RM_TY_FEATURE']).'" data-desc="'.trim($row['RM_TY_DESC']).'" data-rmclass="'.trim($row['RM_TY_ROOM_CLASS']).'" value="'.trim($row['RM_TY_ID']).'"'.set_select('SEARCH_ROOM_TYPE', $row['RM_TY_ID'], False).'>'.$row['RM_TY_DESC'].'</option>';
        }
        echo $option;
    }

    ///////////////////////// dsfsdfsdf/dfgb///////////////////

    public function roomplanResourcesJson()
    {
        $cond = $where = $join = '';
        // $clear = $this->request->getPost('SEARCH_CLEAR');
        // if(isset($clear) && $clear == '0'){
        //     $SEARCH_DATE             =  date("Y-m-d",strtotime($this->request->getPost('SEARCH_DATE')));                    
        //     $SEARCH_DATE_WEEK        = date("Y-m-d",strtotime($SEARCH_DATE."+7 day"));              
        //     $SEARCH_ROOM_TYPE   = $this->request->getPost('SEARCH_ROOM_TYPE');
        //     $SEARCH_ROOM        = $this->request->getPost('SEARCH_ROOM');
        //     $SEARCH_ROOM_STATUS = $this->request->getPost('SEARCH_ROOM_STATUS');
        //     $SEARCH_ROOM_FLOOR  = $this->request->getPost('SEARCH_ROOM_FLOOR');
        //     $SEARCH_ASSIGNED_ROOMS   = $this->request->getPost('SEARCH_ASSIGNED_ROOMS');
        //     $SEARCH_UNASSIGNED_ROOMS = $this->request->getPost('SEARCH_UNASSIGNED_ROOMS');
        //     $cond .= " WHERE 1=1 ";
        //     if($SEARCH_ROOM_TYPE != '' || $SEARCH_ROOM != '' || $SEARCH_ROOM_STATUS != '' || $SEARCH_ROOM_FLOOR != '')
        //     {            
        //         $cond .= ($SEARCH_ROOM_TYPE != '')?" AND RM_TYPE_REF_ID = '".$SEARCH_ROOM_TYPE."'":'';
        //         $cond .= ($SEARCH_ROOM_STATUS != '')?" AND RM_STATUS_ID = '".$SEARCH_ROOM_STATUS."'":'';
        //         $cond .= ($SEARCH_ROOM != '')?" AND RM_ID = '".$SEARCH_ROOM."'":'';
        //         $cond .= ($SEARCH_ROOM_FLOOR != '')?" AND RM.RM_FL_ID = '".$SEARCH_ROOM_FLOOR."'":'';
        //     } 

        //     if($SEARCH_ASSIGNED_ROOMS != '' && $SEARCH_UNASSIGNED_ROOMS != '') {
                
        //     }
        //     else if($SEARCH_ASSIGNED_ROOMS != '' ){
        //             $join = "LEFT JOIN FLXY_RESERVATION ON RESV_ROOM_ID = RM_ID"; 
        //             $where = ($SEARCH_DATE != '')?" AND ('".$SEARCH_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE)":"";
        //     } 
        //     else if($SEARCH_UNASSIGNED_ROOMS != '' ){            
        //         $where .= " AND RM_ID NOT IN (SELECT RM_ID  FROM FLXY_ROOM
        //         INNER JOIN FLXY_RESERVATION ON RESV_ROOM_ID = RM_ID WHERE 1=1  AND ('".$SEARCH_DATE."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE))";
        //     }
        
        // }      

        $data = $response = NULL;

        $sql = "SELECT RM_ID, RM_NO, RM_TYPE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
         FROM FLXY_ROOM 
         LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
        
        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

        LEFT JOIN FLXY_ROOM_FLOOR RM ON RM.RM_FL_CODE = RM_FLOOR_PREFERN 
         ".$join.$cond.$where."

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC";       
        $responseCount = $this->DB->query($sql)->getNumRows();
        if($responseCount > 0)
        $response = $this->DB->query($sql)->getResultArray();
        return $response;
    }

    function itemAvailability(){
        $data['title'] = getMethodName();
        $data['session'] = $this->session;  
        $data['js_to_load'] = array("inventoryFormWizardNumbered.js","RoomPlan/FullCalendar/Core/main.min.js",
        "RoomPlan/FullCalendar/Interaction/main.min.js", "RoomPlan/FullCalendar/Timeline/main.min.js", 
        "RoomPlan/FullCalendar/ResourceCommon/main.min.js","RoomPlan/FullCalendar/ResourceTimeline/main.min.js",
        "resv-attachment-file-upload.js");

        $data['css_to_load'] = array("RoomPlan/FullCalendar/Core/main.min.css", "RoomPlan/FullCalendar/Timeline/main.min.css", 
         "RoomPlan/FullCalendar/ResourceTimeline/main.min.css");

        return view('Reservation/itemAvailability', $data);
    }

}
