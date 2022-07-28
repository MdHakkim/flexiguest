<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;
use App\Models\ShareReservations;
use App\Libraries\ServerSideDataTable;

class ReservationController extends BaseController
{

    use ResponseTrait;

    private $DB;
    private $Reservation;
    private $ShareReservations;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Reservation = new Reservation();
        $this->ShareReservations = new ShareReservations();
        $this->session = \Config\Services::session();
        
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

        $params = [
            'room_no' => $room_no, 'first_name' => "%$first_name%", 'last_name' => "%$last_name%",
            'reservation_id' => $reservation_id, 'current_reservation_id' => $current_reservation_id
        ];

        $where_condition = "(fr.RESV_ROOM = :room_no: 
                            or fr.RESV_ID = :reservation_id:
                            or fr.RESV_NAME in (select CUST_ID from FLXY_CUSTOMER where CUST_FIRST_NAME like :first_name: or CUST_LAST_NAME like :last_name:))";
        
        if(!empty($reservation_id))
            $where_condition = "fr.RESV_ID = :reservation_id:";

        $reservations = $this->DB->query(
            "select fr.*, fc.CUST_TITLE, fc.CUST_FIRST_NAME, fc.CUST_LAST_NAME from FLXY_RESERVATION fr
                left join FLXY_CUSTOMER fc on fr.RESV_NAME = fc.CUST_ID 
                where fr.RESV_ID != :current_reservation_id:
                    and $where_condition",
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
         $sql = "SELECT RESV_ARRIVAL_DT, RESV_ROOM, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, RESV_NO, RESV_RATE, RESV_RM_TYPE, RESV_NAME, (SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY) RESV_COMPANY_DESC, CUST_FIRST_NAME, CUST_LAST_NAME, CUST_MOBILE, CUST_EMAIL, (SELECT ctname FROM CITY WHERE id=CUST_CITY) CUST_CITY_DESC, (SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC, (SELECT cname FROM COUNTRY WHERE ISO2=CUST_NATIONALITY) CUST_NATIONALITY_DESC, CONCAT(CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3) AS CUST_ADDRESS, CUST_DOB, CUST_DOC_TYPE, CUST_DOC_NUMBER FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON FLXY_RESERVATION.RESV_NAME = FLXY_CUSTOMER.CUST_ID WHERE RESV_ID = ".$_SESSION['RESV_ID'];       
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
                FROM FLXY_RATE_CATEGORY WHERE RT_CL_ID = ".$rate_class_id;           

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

        $sql = "SELECT RT_CD_ID, RT_CD_CODE, RT_CD_DESC
                FROM FLXY_RATE_CODE WHERE RT_CT_ID = ".$rate_category_id;           

        $response = $this->DB->query($sql)->getResultArray();

        $option = '<option value="">Select</option>';
        if(!empty($response)){
            foreach ($response as $row) {
                $option .= '<option value="' . $row['RT_CD_ID'] . '">' . $row['RT_CD_CODE'] . ' | ' . $row['RT_CD_DESC'] . '</option>';
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
                    'RSV_PCKG_END_DATE' => ['label' => 'End Date', 'rules' => 'required|checkPackageEndDate[RSV_PCKG_END_DATE]|packageDateOverlapCheck[RSV_PCKG_END_DATE]', 'errors' => ['checkPackageEndDate' => 'End Date should be between the reservation dates ','packageDateOverlapCheck' => 'End Date of package overlaps with an existing package. Change the date']]                  
                    
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
        $RSV_PCKG_END_DATE1  = strtotime($RSV_PCKG_END_DATE); 
        $datediff = $RSV_PCKG_END_DATE1 - $RSV_PCKG_BEGIN_DATE1;
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
            if( ($Posting_Rhythm == 5 && ($RSV_PCKG_BEGIN_DATE == $ACTUAL_RSV_PCKG_END_DATE)) || $Posting_Rhythm == 1 || $Posting_Rhythm == 2) 
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

                $RSV_PCKG_BEGIN_DATE  = date("Y-m-d", strtotime("+$i day", strtotime($RSV_PCKG_BEGIN_DATE))); 

                if( $Posting_Rhythm == 2)
                break;
                
        }

        echo $output;
    }





}
