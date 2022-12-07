<?php
namespace App\Controllers;

use App\Controllers\Repositories\ReservationAssetRepository;
use App\Controllers\Repositories\ReservationRepository;
use  App\Libraries\ServerSideDataTable;
use  App\Libraries\EmailLibrary;
use App\Models\Documents;
use App\Models\Reservation;
use App\Models\ReservationRoomAsset;
use App\Models\RoomAsset;
use CodeIgniter\API\ResponseTrait;
use DateTime;
use DateTimeZone;

class ApplicatioController extends BaseController
{
    use ResponseTrait;

    public $Db;
    public $session;
    public $request;
    public $todayDate;
    private $Documents;
    private $Reservation;
    private $RoomAsset;
    private $ReservationRoomAsset;
    private $ReservationRepository;
    private $ReservationAssetRepository;

    public function __construct(){
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form', 'common', 'custom']);
        $this->request = \Config\Services::request();
        $this->todayDate = new DateTime("now", new DateTimeZone('Asia/Dubai'));
        $this->Documents = new Documents();

        $this->Reservation = new Reservation();
        $this->RoomAsset = new RoomAsset();
        $this->ReservationRoomAsset = new ReservationRoomAsset();
        $this->ReservationRepository = new ReservationRepository();
        $this->ReservationAssetRepository = new ReservationAssetRepository();
    }

    public function Reservation(){   
        $data['title'] = null !== $this->request->getGet("SHOW_ARRIVALS") ? 'Arrivals' : (null !== $this->request->getGet("SHOW_IN_HOUSE") ? 'In House Guests' : 'Reservation List');
        $data['session'] = $this->session;
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $itemLists = $this->itemList();    
        $data['itemLists'] = $itemLists;   
        $data['itemResources'] = $this->itemResources();                 
        $data['itemAvail'] = $this->ItemCalendar();
        $data['classList'] = $this->itemInventoryClassList();
        $data['FrequencyList'] = $this->frequencyList();  
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();

        $data['userList'] = geUsersList(); 

        $data['js_to_load'] = array("inventoryFormWizardNumbered.js","RoomPlan/FullCalendar/Core/main.min.js",
                                    "RoomPlan/FullCalendar/Interaction/main.min.js", "RoomPlan/FullCalendar/Timeline/main.min.js", 
                                    "RoomPlan/FullCalendar/ResourceCommon/main.min.js","RoomPlan/FullCalendar/ResourceTimeline/main.min.js",
                                    "resv-attachment-file-upload.js");

        $data['css_to_load'] = array("RoomPlan/FullCalendar/Core/main.min.css", "RoomPlan/FullCalendar/Timeline/main.min.css", 
                                     "RoomPlan/FullCalendar/ResourceTimeline/main.min.css");
        
        $data['RoomReservations'] = $this->getReservations(); 
        $data['RoomResources'] = $this->roomplanResources();

        $data['cancelReasons'] = $this->cancellationReasonList();

        $data['RESV_ID'] = null !== $this->request->getGet("RESV_ID") ? $this->request->getGet("RESV_ID") : null;
        $data['ROOM_ID'] = null !== $this->request->getGet("ROOM_ID") ? $this->request->getGet("ROOM_ID") : null;
        $cond = "RM_ID = '".$this->request->getGet('ROOM_ID')."'";
        $data['ROOM_NO'] = null !== $this->request->getGet("ROOM_ID") ? getValueFromTable('RM_NO',$cond,'FLXY_ROOM') : null;        
        $data['ROOM_TYPE'] = null !== $this->request->getGet("ROOM_ID") ? getValueFromTable('RM_TYPE',$cond,'FLXY_ROOM') : null;
        $data['ARRIVAL_DATE'] = null !== $this->request->getGet("ARRIVAL_DATE") ? date("d-M-Y", strtotime($this->request->getGet("ARRIVAL_DATE"))) : null;
        $data['DEPARTURE_DATE'] = null !== $this->request->getGet("ARRIVAL_DATE") ? date("d-M-Y", strtotime("+1 day", strtotime($this->request->getGet("ARRIVAL_DATE")))): null; 
        $cond = "RESV_ID = '".$this->request->getGet("RESV_ID")."'";
        $data['CUSTOMER_ID'] = null !== $this->request->getGet("RESV_ID") ? getValueFromTable('RESV_NAME',$cond,'FLXY_RESERVATION') : null;
        
        $data['show_arrivals'] = null !== $this->request->getGet("SHOW_ARRIVALS") ? '1' : null;
        $data['show_in_house'] = null !== $this->request->getGet("SHOW_IN_HOUSE") ? '1' : null;
        $data['create_walkin'] = null !== $this->request->getGet("CREATE_WALKIN") ? '1' : null;

        //Check if RESV_ID exists in Reservation table
        if($data['RESV_ID'] && !checkValueinTable('RESV_ID', $data['RESV_ID'], 'FLXY_RESERVATION'))
            return redirect()->to(base_url('reservation')); 

        return view('Reservation/Reservation', $data);
    }

    function getInventoryCalendarData(){
        $data['itemResources'] = $this->itemResources();                 
        $data['itemAvail'] = $this->ItemCalendar();           
        echo json_encode($data);
    }

    function getInventoryAllocatedData(){     
        
        $data  = $this->ItemCalendar();           
        echo json_encode($data);
    }

    public function roomplanResources()
    {
        $data = $response = NULL;
        $sql = "SELECT RM_ID, RM_NO, RM_TYPE, SM.RM_STATUS_CODE, RL.RM_STAT_UPDATED      
        FROM FLXY_ROOM 
        LEFT JOIN (SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID
                      ,RM_STAT_ROOM_ID
                  FROM FLXY_ROOM_STATUS_LOG
                  GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG  ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
        
        INNER JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID
        
        INNER JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS 

        GROUP BY RM_ID,RM_NO,RM_STATUS_CODE,RM_TYPE,RM_STAT_UPDATED 

        ORDER BY RM_ID ASC";       
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0)
        $response = $this->Db->query($sql)->getResultArray();
        return $response;
    }



    public function getReservations(){
        $response = NULL;
        $sql = "SELECT RESV_ID, RESV_ARRIVAL_DT, RESV_NIGHT, RESV_DEPARTURE, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME, RESV_ROOM, RESV_STATUS, RM_ID FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID INNER JOIN FLXY_ROOM ON RM_ID = RESV_ROOM_ID WHERE RESV_STATUS != 'Checked-Out' ORDER BY RM_ID ASC";       
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->Db->query($sql)->getResultArray();           
        }
      return $response;
    }

    public function blockList(){
        try{
            $search = $this->request->getPost("search");
            $sql = "SELECT BLK_ID,BLK_NAME,BLK_CODE,BLK_START_DT,BLK_END_DT,BLK_STATUS FROM FLXY_BLOCK WHERE (BLK_NAME LIKE '%$search%' OR BLK_AGENT LIKE '%$search%' OR BLK_GROUP LIKE '%$search%' OR BLK_CODE LIKE '%$search%')";
            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Block</option>';
            foreach($response as $row){
                $description = $row['BLK_CODE'].' - '.$row['BLK_NAME'].' - '.$row['BLK_START_DT'].' - '.$row['BLK_END_DT'].' - '.$row['BLK_STATUS'];
                $option.= '<option value="'.$row['BLK_ID'].'">'.$description.'</option>';
            }
            echo $option;
        }catch (\Exception $e){
            echo json_encode($e->getMessage());
        }
    }

    function deleteReservation(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_RESERVATION')->delete(['RESV_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function reservationView(){

        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

        $postValues = $this->request->getPost('columns');

        $search_keys = ['S_GUEST_NAME', 'S_CUST_FIRST_NAME', 'S_GUEST_PHONE', 'S_CUST_EMAIL', 
                        'S_COMPNAME', 'S_AGENTNAME', 'S_RESV_ROOM', 'S_RESV_RM_TYPE', 'S_RESV_NO', 
                        'S_ARRIVAL_FROM', 'S_ARRIVAL_TO', 'S_DEPARTURE_FROM', 'S_DEPARTURE_TO', 'S_ROOM_NO', 
                        'S_SEARCH_TYPE', 'S_RESV_CREATE_DT', 'S_RESV_CREATE_UID'];

        $init_cond = array();

        if(null !== $this->request->getPost('SHOW_IN_HOUSE')){
            $init_cond["'".date('Y-m-d')."' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE"] = "";
            $init_cond["RESV_STATUS IN ('Checked-In','Check-Out-Requested')"] = "";
        }

        if($search_keys != NULL){
            foreach($search_keys as $search_key)
            {
                if(null !== $this->request->getPost($search_key) && !empty(trim($this->request->getPost($search_key))))
                {
                    $value = trim($this->request->getPost($search_key));

                    switch($search_key)
                    {
                        case 'S_GUEST_NAME': $init_cond["CONCAT_WS(' ', CUST_FIRST_NAME, CUST_LAST_NAME) LIKE "] = "'%$value%'"; break;
                        case 'S_CUST_FIRST_NAME': $init_cond["CUST_FIRST_NAME LIKE "] = "'%$value%'"; break;
                        
                        //case 'S_GUEST_FIRST_NAME': $init_cond["SUBSTRING(FULLNAME,1,(CHARINDEX(' ',FULLNAME + ' ')-1)) LIKE "] = "'%$value%'"; break;
                        case 'S_GUEST_PHONE': $init_cond["(CUST_MOBILE LIKE '%$value%' OR CUST_PHONE LIKE '%$value%')"] = ""; break;
                        case 'S_ARRIVAL_FROM': $init_cond["RESV_ARRIVAL_DT >= "] = "'$value'"; break;
                        case 'S_ARRIVAL_TO': $init_cond["RESV_ARRIVAL_DT <= "] = "'$value'"; break;
                        case 'S_DEPARTURE_FROM': $init_cond["RESV_DEPARTURE >= "] = "'$value'"; break;
                        case 'S_DEPARTURE_TO': $init_cond["RESV_DEPARTURE <= "] = "'$value'"; break;
                        case 'S_RESV_CREATE_DT': $init_cond["RESV_CREATE_DT = "] = "'$value'"; break;
                        
                        case 'S_SEARCH_TYPE': { switch($value)
                                                {
                                                    //Due In
                                                    case '1': $init_cond["RESV_ARRIVAL_DT = "] = "'".date('Y-m-d')."'"; 
                                                              $init_cond["RESV_STATUS IN ('Due Pre Check-In','Pre Checked-In')"] = "";
                                                              break;
                                                    
                                                    //Due Out          
                                                    case '2': $init_cond["RESV_DEPARTURE = "]  = "'".date('Y-m-d')."'"; 
                                                              $init_cond["RESV_STATUS IN ('Checked-In','Check-Out-Requested')"] = "";
                                                              break;

                                                    case '3': $init_cond["RESV_ARRIVAL_DT = "] = "RESV_DEPARTURE"; break;
                                                    case '4': $init_cond["RESV_STATUS = "] = "'Checked-In'"; break;
                                                    case '5': $init_cond["RESV_STATUS = "] = "'Checked-Out'"; break;
                                                    case '6': $init_cond["RESV_STATUS = "] = "'No Show'"; break;
                                                    case '7': $init_cond["RESV_STATUS = "] = "'Cancelled'"; break;
                                                    case '8': $init_cond["RESV_STATUS = "] = "'Check-Out-Requested'"; break;
                                                    default: break;
                                                }
                                              } break;

                        default: $init_cond["".ltrim($search_key, "S_")." LIKE "] = "'%$value%'"; break;                        
                    }                    
                }
            }
        }
        
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RESERVATION_VIEW LEFT JOIN FLXY_CUSTOMER C ON C.CUST_ID = FLXY_RESERVATION_VIEW.RESV_NAME';
        $columns = 'RESV_ID|RESV_NO|FORMAT(RESV_ARRIVAL_DT,\'dd-MMM-yyyy\')RESV_ARRIVAL_DT|RESV_STATUS|RESV_NIGHT|FORMAT(RESV_DEPARTURE,\'dd-MMM-yyyy\')RESV_DEPARTURE|RESV_RM_TYPE|RESV_ROOM|(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RM_TY_DESC|RESV_NAME|RESV_NO_F_ROOM|CUST_ID|CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME)CUST_FIRST_NAME|CUST_MIDDLE_NAME|CUST_LAST_NAME|CUST_EMAIL|CUST_MOBILE|CUST_PHONE|RESV_FEATURE|FORMAT(RESV_CREATE_DT,\'dd-MMM-yyyy\')RESV_CREATE_DT|RESV_PURPOSE_STAY';
        $mine->generate_DatatTable($tableName,$columns,$init_cond,'|');
        exit;
        // return view('Dashboard');
    }

    public function getReservationDetails($id = 0)
    {
        $param = ['SYSID' => $id];

        $addColumnCopty='RESV_ID,RESV_PAYMENT_TYPE,RESV_SPECIALS,RESV_COMMENTS,RESV_PACKAGES,RESV_ITEM_INVT,RESV_NAME,(CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME))RESV_NAME_DESC,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,
                        (SELECT CNAME FROM COUNTRY WHERE ISO2=CUST_COUNTRY)CUST_COUNTRY_DESC,CUST_VIP,CUST_PHONE';

        $sql = "SELECT $addColumnCopty,FORMAT(RESV_ARRIVAL_DT,'dd-MMM-yyyy')RESV_ARRIVAL_DT,RESV_NIGHT,RESV_ADULTS,RESV_CHILDREN,FORMAT(RESV_DEPARTURE,'dd-MMM-yyyy')RESV_DEPARTURE,RESV_NO_F_ROOM,RESV_MEMBER_TY,RESV_CUST_MEMBERSHIP,
        RESV_COMPANY,(SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY)RESV_COMPANY_DESC,
        RESV_AGENT,(SELECT AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE AGN_ID=RESV_AGENT)RESV_AGENT_DESC,
        RESV_BLOCK,(SELECT CONCAT_WS(' - ', BLK_NAME, BLK_CODE, BLK_START_DT, BLK_END_DT) AS BLOCKDESC FROM FLXY_BLOCK WHERE BLK_ID=RESV_BLOCK)RESV_BLOCK_DESC,RESV_MEMBER_NO,RESV_CORP_NO,RESV_IATA_NO,RESV_CLOSED,RESV_DAY_USE,
        RESV_PSEUDO,RESV_RATE_CLASS,RESV_RATE_CATEGORY,RESV_RATE_CODE,RESV_ROOM_CLASS,RESV_FEATURE,RESV_PURPOSE_STAY,RESV_STATUS,RESV_RM_TYPE,RESV_C_O_TIME,RESV_TAX_TYPE,RESV_EXEMPT_NO,RESV_PICKUP_YN,RESV_TRANSPORT_TYP,RESV_STATION_CD,RESV_CARRIER_CD,RESV_TRANSPORT_NO,FORMAT(RESV_ARRIVAL_DT_PK,'dd-MMM-yyyy')RESV_ARRIVAL_DT_PK,RESV_PICKUP_TIME,RESV_DROPOFF_YN,RESV_TRANSPORT_TYP_DO,RESV_STATION_CD_DO,RESV_CARRIER_CD_DO,RESV_TRANSPORT_NO_DO,FORMAT(RESV_ARRIVAL_DT_DO,'dd-MMM-yyyy')RESV_ARRIVAL_DT_DO,RESV_DROPOFF_TIME,RESV_GUST_TY,RESV_EXT_PURP_STAY,RESV_ENTRY_PONT,RESV_PROFILE,RESV_NAME_ON_CARD,RESV_EXT_PRINT_RT,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RESV_RM_TYPE_DESC,
        (SELECT RM_DESC FROM FLXY_ROOM WHERE RM_NO=RESV_ROOM)RESV_ROOM_DESC,(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RTC) RESV_RTC_DESC,
        RESV_ROOM,RESV_RATE,RESV_ETA,RESV_CO_TIME,RESV_RTC,RESV_FIXED_RATE,RESV_RESRV_TYPE,RESV_MARKET,RESV_SOURCE,RESV_ORIGIN,RESV_BOKR_LAST,RESV_BOKR_FIRST,RESV_BOKR_EMAIL,RESV_BOKR_PHONE,RESV_CONFIRM_YN,RESV_NO_POST FROM FLXY_RESERVATION,FLXY_CUSTOMER WHERE RESV_ID=:SYSID: AND CUST_ID=RESV_NAME";
       
        $response = $this->Db->query($sql,$param)->getRowArray();
        return $response;
    }

    function editReservation(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $mode = $this->request->getPost("mode");
        if($mode=='CPY'){
            $paramArr = $this->request->getPost("paramArr");
            $addColumnCopty='';
            foreach($paramArr as $inx=>$row){    
                if($row=='PM'){
                    $addColumnCopty.='RESV_PAYMENT_TYPE';
                }else if($row=='SP'){
                    $addColumnCopty.='RESV_SPECIALS';
                }else if($row=='CM'){
                    $addColumnCopty.='RESV_COMMENTS';
                }else if($row=='PK'){
                    $addColumnCopty.='RESV_PACKAGES';
                }else if($row=='IN'){
                    $addColumnCopty.='RESV_ITEM_INVT';
                }else if($row=='GU'){
                    $addColumnCopty.='RESV_NAME,(CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME))RESV_NAME_DESC,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,(SELECT CNAME FROM COUNTRY WHERE ISO2=CUST_COUNTRY)CUST_COUNTRY_DESC,CUST_VIP,CUST_PHONE';
                }
                if($inx !== array_key_last($paramArr) && $row!='CR' && $row!='RU'){
                    $addColumnCopty.=',';  
                }
            }
        }else{
            $addColumnCopty='RESV_ID,RESV_PAYMENT_TYPE,RESV_SPECIALS,RESV_COMMENTS,RESV_PACKAGES,RESV_ITEM_INVT,RESV_NAME,(CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME))RESV_NAME_DESC,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,
            (SELECT CNAME FROM COUNTRY WHERE ISO2=CUST_COUNTRY)CUST_COUNTRY_DESC,CUST_VIP,CUST_PHONE';
        }
        $sql = "SELECT $addColumnCopty,FORMAT(RESV_ARRIVAL_DT,'dd-MMM-yyyy')RESV_ARRIVAL_DT,RESV_NIGHT,RESV_ADULTS,RESV_CHILDREN,FORMAT(RESV_DEPARTURE,'dd-MMM-yyyy')RESV_DEPARTURE,RESV_NO_F_ROOM,RESV_MEMBER_TY,RESV_CUST_MEMBERSHIP,
        RESV_COMPANY,(SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY)RESV_COMPANY_DESC,
        RESV_AGENT,(SELECT AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE AGN_ID=RESV_AGENT)RESV_AGENT_DESC,
        RESV_BLOCK,(SELECT CONCAT_WS(' - ', BLK_NAME, BLK_CODE, BLK_START_DT, BLK_END_DT) AS BLOCKDESC FROM FLXY_BLOCK WHERE BLK_ID=RESV_BLOCK)RESV_BLOCK_DESC,RESV_MEMBER_NO,RESV_CORP_NO,RESV_IATA_NO,RESV_CLOSED,RESV_DAY_USE,
        RESV_PSEUDO,RESV_RATE_CLASS,RESV_RATE_CATEGORY,RESV_RATE_CODE,RESV_ROOM_CLASS,RESV_FEATURE,RESV_PURPOSE_STAY,RESV_NO,RESV_STATUS,RESV_RM_TYPE,RESV_C_O_TIME,RESV_TAX_TYPE,RESV_EXEMPT_NO,RESV_PICKUP_YN,RESV_TRANSPORT_TYP,RESV_STATION_CD,RESV_CARRIER_CD,RESV_TRANSPORT_NO,FORMAT(RESV_ARRIVAL_DT_PK,'dd-MMM-yyyy')RESV_ARRIVAL_DT_PK,RESV_PICKUP_TIME,RESV_DROPOFF_YN,RESV_TRANSPORT_TYP_DO,RESV_STATION_CD_DO,RESV_CARRIER_CD_DO,RESV_TRANSPORT_NO_DO,FORMAT(RESV_ARRIVAL_DT_DO,'dd-MMM-yyyy')RESV_ARRIVAL_DT_DO,RESV_DROPOFF_TIME,RESV_GUST_TY,RESV_EXT_PURP_STAY,RESV_ENTRY_PONT,RESV_PROFILE,RESV_NAME_ON_CARD,RESV_EXT_PRINT_RT,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RESV_RM_TYPE_DESC,
        (SELECT RM_DESC FROM FLXY_ROOM WHERE RM_NO=RESV_ROOM)RESV_ROOM_DESC,(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RTC) RESV_RTC_DESC,
        RESV_ROOM,RESV_RATE,RESV_ETA,RESV_CO_TIME,RESV_RTC,RESV_FIXED_RATE,RESV_RESRV_TYPE,RESV_MARKET,RESV_SOURCE,RESV_ORIGIN,RESV_BOKR_LAST,RESV_BOKR_FIRST,RESV_BOKR_EMAIL,RESV_BOKR_PHONE,RESV_CONFIRM_YN,RESV_NO_POST FROM FLXY_RESERVATION,FLXY_CUSTOMER WHERE RESV_ID=:SYSID: AND CUST_ID=RESV_NAME";
        $response = $this->Db->query($sql,$param)->getResultArray();
        $response = $this->removeNullJson($response);
        echo json_encode($response);
    }

    public function ReservationChangesView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array( "AC_GP_ID = " => "1", 
                            "ELEMENT_ID = " => "'$sysid'"); // Add condition for Reservation Item

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ACTIVITY_LOG_VIEW';
        $columns = 'LOG_ID,USR_NAME,LOG_DATE,LOG_TIME,AC_TY_DESC,LOG_ACTION_DESCRIPTION';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }


    public function removeNullJson($value){
        array_walk_recursive($value, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $value;
    }

    function insertReservation(){
        try{
            $validate = $this->validate([
                'RESV_ARRIVAL_DT' => ['label' => 'Arrival Date', 'rules' => 'required'],
                'RESV_DEPARTURE' => ['label' => 'Departure Date', 'rules' => 'required'],
                'RESV_NIGHT' => ['label' => 'No. of Nights', 'rules' => 'required'],
                'RESV_ADULTS' => ['label' => 'Rate Class Description', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["aSUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RESV_ID");
            $emailProc = $log_action_desc = '';

            if(!empty($sysid)){ // Edit Reservation

            $currentReservation = $this->getReservationDetails($sysid);

            $data = ["RESV_ARRIVAL_DT" => $this->request->getPost("RESV_ARRIVAL_DT"),
                "RESV_NIGHT" => $this->request->getPost("RESV_NIGHT"),
                "RESV_ADULTS" => $this->request->getPost("RESV_ADULTS"),
                "RESV_CHILDREN" => $this->request->getPost("RESV_CHILDREN"),
                "RESV_DEPARTURE" => $this->request->getPost("RESV_DEPARTURE"),
                "RESV_NO_F_ROOM" => $this->request->getPost("RESV_NO_F_ROOM"),
                "RESV_NAME" => $this->request->getPost("RESV_NAME"),
                "RESV_CUST_MEMBERSHIP" => $this->request->getPost("RESV_CUST_MEMBERSHIP"),
                "RESV_MEMBER_TY" => $this->request->getPost("RESV_MEMBER_TY"),
                "RESV_COMPANY" => $this->request->getPost("RESV_COMPANY"),
                "RESV_AGENT" => $this->request->getPost("RESV_AGENT"),
                "RESV_BLOCK" => $this->request->getPost("RESV_BLOCK"),
                "RESV_MEMBER_NO" => $this->request->getPost("RESV_MEMBER_NO"),
                "RESV_CORP_NO" => $this->request->getPost("RESV_CORP_NO"),
                "RESV_IATA_NO" => $this->request->getPost("RESV_IATA_NO"),
                // "RESV_CLOSED" => $this->request->getPost("RESV_CLOSED"),
                "RESV_DAY_USE" => $this->request->getPost("RESV_ARRIVAL_DT") == $this->request->getPost("RESV_DEPARTURE") ? 'Y' : 'N',
                // "RESV_PSEUDO" => $this->request->getPost("RESV_PSEUDO"),
                "RESV_RATE_CLASS" => $this->request->getPost("RESV_RATE_CLASS"),
                "RESV_RATE_CATEGORY" => $this->request->getPost("RESV_RATE_CATEGORY"),
                "RESV_RATE_CODE" => $this->request->getPost("RESV_RATE_CODE"),
                "RESV_ROOM_CLASS" => $this->request->getPost("RESV_ROOM_CLASS"),
                "RESV_FEATURE" => $this->request->getPost("RESV_FEATURE"),
                "RESV_PACKAGES" => $this->request->getPost("RESV_PACKAGES"),
                "RESV_PURPOSE_STAY" => $this->request->getPost("RESV_PURPOSE_STAY"),
                "RESV_STATUS" => $this->request->getPost("RESV_STATUS"),
                "RESV_RM_TYPE" => $this->request->getPost("RESV_RM_TYPE"),
                "RESV_RM_TYPE_ID" => $this->request->getPost("RESV_RM_TYPE_ID"),
                "RESV_ROOM" => $this->request->getPost("RESV_ROOM"),
                "RESV_ROOM_ID" => $this->request->getPost("RESV_ROOM_ID"),
                "RESV_RATE" => $this->request->getPost("RESV_RATE"),
                "RESV_ETA" => $this->request->getPost("RESV_ETA"),
                "RESV_CO_TIME" => $this->request->getPost("RESV_CO_TIME"),
                "RESV_RTC" => !empty($this->request->getPost("RESV_RTC")) ? $this->request->getPost("RESV_RTC") : $this->request->getPost("RESV_RM_TYPE"),
                "RESV_RTC_ID" => $this->request->getPost("RESV_RTC_ID"),
                "RESV_FIXED_RATE" => (empty($this->request->getPost("RESV_FIXED_RATE")) ? '' : $this->request->getPost("RESV_FIXED_RATE")),
                "RESV_RESRV_TYPE" => $this->request->getPost("RESV_RESRV_TYPE"),
                "RESV_MARKET" => $this->request->getPost("RESV_MARKET"),
                "RESV_SOURCE" => $this->request->getPost("RESV_SOURCE"),
                "RESV_ORIGIN" => $this->request->getPost("RESV_ORIGIN"),
                "RESV_PAYMENT_TYPE" => $this->request->getPost("RESV_PAYMENT_TYPE"),
                "RESV_COMMENTS" => $this->request->getPost("RESV_COMMENTS"),
                "RESV_ITEM_INVT" => $this->request->getPost("RESV_ITEM_INVT"),
                "RESV_BOKR_LAST" => $this->request->getPost("RESV_BOKR_LAST"),
                "RESV_BOKR_FIRST" => $this->request->getPost("RESV_BOKR_FIRST"),
                "RESV_BOKR_EMAIL" => $this->request->getPost("RESV_BOKR_EMAIL"),
                "RESV_BOKR_PHONE" => $this->request->getPost("RESV_BOKR_PHONE"),
                "RESV_CONFIRM_YN" => $this->request->getPost("RESV_CONFIRM_YN"),
                "RESV_NO_POST" => $this->request->getPost("RESV_NO_POST"),
                "RESV_C_O_TIME" => $this->request->getPost("RESV_C_O_TIME"),
                "RESV_TAX_TYPE" => $this->request->getPost("RESV_TAX_TYPE"),
                "RESV_EXEMPT_NO" => $this->request->getPost("RESV_EXEMPT_NO"),
                "RESV_PICKUP_YN" => $this->request->getPost("RESV_PICKUP_YN"),
                "RESV_TRANSPORT_TYP" => $this->request->getPost("RESV_TRANSPORT_TYP"),
                "RESV_STATION_CD" => $this->request->getPost("RESV_STATION_CD"),
                "RESV_CARRIER_CD" => $this->request->getPost("RESV_CARRIER_CD"),
                "RESV_TRANSPORT_NO" => $this->request->getPost("RESV_TRANSPORT_NO"),
                "RESV_ARRIVAL_DT_PK" => $this->request->getPost("RESV_ARRIVAL_DT_PK"),
                "RESV_PICKUP_TIME" => $this->request->getPost("RESV_PICKUP_TIME"),
                "RESV_DROPOFF_YN" => $this->request->getPost("RESV_DROPOFF_YN"),
                "RESV_TRANSPORT_TYP_DO" => $this->request->getPost("RESV_TRANSPORT_TYP_DO"),
                "RESV_STATION_CD_DO" => $this->request->getPost("RESV_STATION_CD_DO"),
                "RESV_CARRIER_CD_DO" => $this->request->getPost("RESV_CARRIER_CD_DO"),
                "RESV_TRANSPORT_NO_DO" => $this->request->getPost("RESV_TRANSPORT_NO_DO"),
                "RESV_ARRIVAL_DT_DO" => $this->request->getPost("RESV_ARRIVAL_DT_DO"),
                "RESV_DROPOFF_TIME" => $this->request->getPost("RESV_DROPOFF_TIME"),
                "RESV_GUST_TY" => $this->request->getPost("RESV_GUST_TY"),
                "RESV_EXT_PURP_STAY" => $this->request->getPost("RESV_EXT_PURP_STAY"),
                "RESV_ENTRY_PONT" => $this->request->getPost("RESV_ENTRY_PONT"),
                "RESV_PROFILE" => $this->request->getPost("RESV_PROFILE"),
                "RESV_NAME_ON_CARD" => $this->request->getPost("RESV_NAME_ON_CARD"),
                "RESV_EXT_PRINT_RT" => $this->request->getPost("RESV_EXT_PRINT_RT"),
                "RESV_UPDATE_UID" => session()->get('USR_ID'),
                "RESV_UPDATE_DT" => date("d-M-Y")
                ];

                $RESV_SPECIALS = $this->request->getPost('RESV_SPECIALS[]');
                if($RESV_SPECIALS != NULL)
                    $data['RESV_SPECIALS'] = implode(",", $RESV_SPECIALS);

                $return = $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update($data); 

                foreach($currentReservation as $rkey => $rvalue)
                {
                    // Save changes in log description if data is updated/changed
                    if( array_key_exists($rkey, $data) && 
                        (!empty(trim($data[$rkey])) || !empty(trim($rvalue))) && 
                        trim($data[$rkey]) != trim($rvalue))
                    {
                        $log_action_desc .= "<b>".str_replace('RESV_', '', $rkey) . ": </b> '" . $rvalue . "' -> '". $data[$rkey]."'<br/>";
                    }
                }

                /////// Update Inventory with reservation ID

                $sessionID = session_id(); 
                $sql="UPDATE FLXY_RESERVATION_ITEM SET RSV_ID = ".$sysid." WHERE RSV_ID = 0 AND RSV_SESSION_ID LIKE '%$sessionID%'";
                $resvItemUpdate = $this->Db->query($sql);

                
                 /////// Update Packages with reservation ID

                $sql="UPDATE FLXY_RESERVATION_PACKAGES SET RSV_ID = ".$sysid." WHERE RSV_ID = 0 AND RSV_PCKG_SESSION_ID LIKE '%$sessionID%'";
                $resvItemUpdate = $this->Db->query($sql);

                 

            }else{ // Add New Reservation
                $data = ["RESV_ARRIVAL_DT" => $this->request->getPost("RESV_ARRIVAL_DT"),
                    "RESV_NIGHT" => $this->request->getPost("RESV_NIGHT"),
                    "RESV_ADULTS" => $this->request->getPost("RESV_ADULTS"),
                    "RESV_CHILDREN" => $this->request->getPost("RESV_CHILDREN"),
                    "RESV_DEPARTURE" => $this->request->getPost("RESV_DEPARTURE"),
                    "RESV_NO_F_ROOM" => $this->request->getPost("RESV_NO_F_ROOM"),
                    "RESV_NAME" => $this->request->getPost("RESV_NAME"),
                    "RESV_CUST_MEMBERSHIP" => $this->request->getPost("RESV_CUST_MEMBERSHIP"),
                    "RESV_MEMBER_TY" => $this->request->getPost("RESV_MEMBER_TY"),
                    "RESV_COMPANY" => $this->request->getPost("RESV_COMPANY"),
                    "RESV_AGENT" => $this->request->getPost("RESV_AGENT"),
                    "RESV_BLOCK" => $this->request->getPost("RESV_BLOCK"),
                    "RESV_MEMBER_NO" => $this->request->getPost("RESV_MEMBER_NO"),
                    "RESV_CORP_NO" => $this->request->getPost("RESV_CORP_NO"),
                    "RESV_IATA_NO" => $this->request->getPost("RESV_IATA_NO"),
                    // "RESV_CLOSED" => $this->request->getPost("RESV_CLOSED"),
                    "RESV_DAY_USE" => $this->request->getPost("RESV_ARRIVAL_DT") == $this->request->getPost("RESV_DEPARTURE") ? 'Y' : 'N',
                    // "RESV_PSEUDO" => $this->request->getPost("RESV_PSEUDO"),
                    "RESV_RATE_CLASS" => $this->request->getPost("RESV_RATE_CLASS"),
                    "RESV_RATE_CATEGORY" => $this->request->getPost("RESV_RATE_CATEGORY"),
                    "RESV_RATE_CODE" => $this->request->getPost("RESV_RATE_CODE"),
                    "RESV_ROOM_CLASS" => $this->request->getPost("RESV_ROOM_CLASS"),
                    "RESV_FEATURE" => $this->request->getPost("RESV_FEATURE"),
                    "RESV_PACKAGES" => $this->request->getPost("RESV_PACKAGES"),
                    "RESV_PURPOSE_STAY" => $this->request->getPost("RESV_PURPOSE_STAY"),
                    "RESV_STATUS" => "Due Pre Check-In",
                    "RESV_RM_TYPE" => $this->request->getPost("RESV_RM_TYPE"),
                    "RESV_RM_TYPE_ID" => $this->request->getPost("RESV_RM_TYPE_ID"),
                    "RESV_ROOM" => $this->request->getPost("RESV_ROOM"),
                    "RESV_ROOM_ID" => $this->request->getPost("RESV_ROOM_ID"),
                    "RESV_RATE" => $this->request->getPost("RESV_RATE"),
                    "RESV_ETA" => $this->request->getPost("RESV_ETA"),
                    "RESV_CO_TIME" => $this->request->getPost("RESV_CO_TIME"),
                    "RESV_RTC" => !empty($this->request->getPost("RESV_RTC")) ? $this->request->getPost("RESV_RTC") : $this->request->getPost("RESV_RM_TYPE"),
                    "RESV_RTC_ID" => $this->request->getPost("RESV_RTC_ID"),
                    "RESV_FIXED_RATE" => (empty($this->request->getPost("RESV_FIXED_RATE")) ? '' : $this->request->getPost("RESV_FIXED_RATE")),
                    "RESV_RESRV_TYPE" => $this->request->getPost("RESV_RESRV_TYPE"),
                    "RESV_MARKET" => $this->request->getPost("RESV_MARKET"),
                    "RESV_SOURCE" => $this->request->getPost("RESV_SOURCE"),
                    "RESV_ORIGIN" => $this->request->getPost("RESV_ORIGIN"),
                    "RESV_PAYMENT_TYPE" => $this->request->getPost("RESV_PAYMENT_TYPE"),
                    "RESV_COMMENTS" => $this->request->getPost("RESV_COMMENTS"),
                    "RESV_ITEM_INVT" => $this->request->getPost("RESV_ITEM_INVT"),
                    "RESV_BOKR_LAST" => $this->request->getPost("RESV_BOKR_LAST"),
                    "RESV_BOKR_FIRST" => $this->request->getPost("RESV_BOKR_FIRST"),
                    "RESV_BOKR_EMAIL" => $this->request->getPost("RESV_BOKR_EMAIL"),
                    "RESV_BOKR_PHONE" => $this->request->getPost("RESV_BOKR_PHONE"),
                    "RESV_CONFIRM_YN" => $this->request->getPost("RESV_CONFIRM_YN"),
                    "RESV_NO_POST" => $this->request->getPost("RESV_NO_POST"),
                    "RESV_C_O_TIME" => $this->request->getPost("RESV_C_O_TIME"),
                    "RESV_TAX_TYPE" => $this->request->getPost("RESV_TAX_TYPE"),
                    "RESV_EXEMPT_NO" => $this->request->getPost("RESV_EXEMPT_NO"),
                    "RESV_PICKUP_YN" => $this->request->getPost("RESV_PICKUP_YN"),
                    "RESV_TRANSPORT_TYP" => $this->request->getPost("RESV_TRANSPORT_TYP"),
                    "RESV_STATION_CD" => $this->request->getPost("RESV_STATION_CD"),
                    "RESV_CARRIER_CD" => $this->request->getPost("RESV_CARRIER_CD"),
                    "RESV_TRANSPORT_NO" => $this->request->getPost("RESV_TRANSPORT_NO"),
                    "RESV_ARRIVAL_DT_PK" => $this->request->getPost("RESV_ARRIVAL_DT_PK"),
                    "RESV_PICKUP_TIME" => $this->request->getPost("RESV_PICKUP_TIME"),
                    "RESV_DROPOFF_YN" => $this->request->getPost("RESV_DROPOFF_YN"),
                    "RESV_TRANSPORT_TYP_DO" => $this->request->getPost("RESV_TRANSPORT_TYP_DO"),
                    "RESV_STATION_CD_DO" => $this->request->getPost("RESV_STATION_CD_DO"),
                    "RESV_CARRIER_CD_DO" => $this->request->getPost("RESV_CARRIER_CD_DO"),
                    "RESV_TRANSPORT_NO_DO" => $this->request->getPost("RESV_TRANSPORT_NO_DO"),
                    "RESV_ARRIVAL_DT_DO" => $this->request->getPost("RESV_ARRIVAL_DT_DO"),
                    "RESV_DROPOFF_TIME" => $this->request->getPost("RESV_DROPOFF_TIME"),
                    "RESV_GUST_TY" => $this->request->getPost("RESV_GUST_TY"),
                    "RESV_EXT_PURP_STAY" => $this->request->getPost("RESV_EXT_PURP_STAY"),
                    "RESV_ENTRY_PONT" => $this->request->getPost("RESV_ENTRY_PONT"),
                    "RESV_PROFILE" => $this->request->getPost("RESV_PROFILE"),
                    "RESV_NAME_ON_CARD" => $this->request->getPost("RESV_NAME_ON_CARD"),
                    "RESV_EXT_PRINT_RT" => $this->request->getPost("RESV_EXT_PRINT_RT"),
                    "RESV_CREATE_UID" => session()->get('USR_ID'),
                    "RESV_CREATE_DT" => date("d-M-Y")
                ];

                $RESV_SPECIALS = $this->request->getPost('RESV_SPECIALS[]');
                if($RESV_SPECIALS != NULL)
                    $data['RESV_SPECIALS'] = implode(",", $RESV_SPECIALS);

                $return = $this->Db->table('FLXY_RESERVATION')->insert($data); 
                $sysid = $this->Db->insertID();

                /////// Update Inventory with reservation ID

                $sessionID = session_id(); 
                $sql="UPDATE FLXY_RESERVATION_ITEM SET RSV_ID = ".$sysid." WHERE RSV_ID = 0 AND RSV_SESSION_ID LIKE '%$sessionID%'";
                $resvItemUpdate = $this->Db->query($sql);

                 /////// Update Packages with reservation ID

                $sql="UPDATE FLXY_RESERVATION_PACKAGES SET RSV_ID = ".$sysid." WHERE RSV_ID = 0 AND RSV_PCKG_SESSION_ID LIKE '%$sessionID%'";
                $resvItemUpdate = $this->Db->query($sql);

                

                $emailProc='S';

                foreach($data as $dkey => $dvalue)
                {
                    // Save changes in log description if data is not empty
                    if(!empty(trim($dvalue)))
                    {
                        $log_action_desc .= "<b>".str_replace('RESV_', '', $dkey) . ": </b> '" . $dvalue ."'<br/>";
                    }
                }
            }
            if($return){
                $custId = $this->request->getPost("RESV_NAME");
                $this->updateCustomerData($custId);
                
                if($emailProc=='S'){ // New Reservation
                    

                    $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update(array('RESV_NO' => 'RES'.$sysid));
                    addActivityLog(1, 10, $sysid, $log_action_desc);
                    $this->triggerReservationEmail($sysid,'');
                }
                else    // Update exisitng Reservation
                {
                    addActivityLog(1, 26, $sysid, $log_action_desc);
                }

                $return = $this->Db->table('FLXY_RESERVATION')->select('RESV_NO')->where('RESV_ID',$sysid)->get()->getResultArray();
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function triggerReservationEmail($sysid,$parametr){
        $param = ['SYSID'=> $sysid];
        $sql="SELECT RESV_ID,RESV_NO,FORMAT(RESV_ARRIVAL_DT,'dd-MMM-yyyy')RESV_ARRIVAL_DT,FORMAT(RESV_DEPARTURE,'dd-MMM-yyyy')RESV_DEPARTURE,RESV_RM_TYPE,(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RM_TY_DESC,
        RESV_NO_F_ROOM,RESV_FEATURE,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) FULLNAME,CUST_EMAIL FROM FLXY_RESERVATION,FLXY_CUSTOMER 
        WHERE RESV_ID=:SYSID: AND RESV_NAME=CUST_ID";
        $reservationInfo = $this->Db->query($sql,$param)->getResultArray();
       // print_r($reservationInfo);exit;
        $emailCall = new EmailLibrary();
        $emailResp = $emailCall->preCheckInEmail($reservationInfo,$parametr);
    }

    public function updateCustomerData($custId) {
        $data = [   "CUST_FIRST_NAME" => $this->request->getPost("CUST_FIRST_NAME"),
                    "CUST_TITLE" => $this->request->getPost("CUST_TITLE"),
                    "CUST_COUNTRY" => $this->request->getPost("CUST_COUNTRY"),
                    "CUST_GENDER" => $this->request->getPost("CUST_GENDER"),
                    "CUST_VIP" => $this->request->getPost("CUST_VIP"),
                    "CUST_PHONE" => $this->request->getPost("CUST_PHONE"),
                    "CUST_UPDATE_UID" => session()->get('USR_ID'),
                    "CUST_UPDATE_DT" => date("Y-m-d")
                ];

        $log_action_desc = '';
        $currentCustomer = $this->getProfileDetails($custId);
        foreach($currentCustomer as $ckey => $cvalue)
        {
            // Save changes in log description if data is updated/changed
            if( array_key_exists($ckey, $data) && 
                (!empty(trim($data[$ckey])) || !empty(trim($cvalue))) && 
                trim($data[$ckey]) != trim($cvalue))
            {
                $log_action_desc .= "<b>".str_replace('CUST_', '', $ckey) . ": </b> '" . $cvalue . "' -> '". $data[$ckey]."'<br/>";
            }
        }

        addActivityLog(3, 50, $custId, $log_action_desc); // Update Customer Log entry

        $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $custId)->update($data); 
        return true;
    }

    public function countryList(){
        $response = $this->Db->table('COUNTRY')->select('iso2,cname')->get()->getResultArray();
        $option='<option value="">Select Country</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['iso2'].'">'.$row['cname'].'</option>';
        }
        echo $option;
        die();
    }

    public function stateList(){
        $ccode = $this->request->getPost("ccode");
        $sql = "SELECT sname,state_code FROM STATE WHERE COUNTRY_CODE='$ccode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select State</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['state_code'].'">'.$row['sname'].'</option>';
        }
        echo $option;
    }

    public function cityList(){
        $ccode = $this->request->getPost("ccode");
        $scode = $this->request->getPost("scode");
        $sql = "SELECT ctname,id FROM CITY WHERE COUNTRY_CODE='$ccode' AND STATE_CODE='$scode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select City</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['id'].'">'.$row['ctname'].'</option>';
        }
        echo $option;
    }

    public function insertCustomer(){
        try{
            $validate = $this->validate([
                'CUST_FIRST_NAME' => ['label' => 'First Name', 'rules' => 'required'],
                'CUST_EMAIL' =>[
                    'label' => 'Email Address',
                    'rules'  => "required|valid_email|emailVerification[CUST_EMAIL,CUST_ID]",
                    'errors' => [
                        'required' => 'Email is required and cannot be empty',
                        'emailVerification' => 'Email already exists'
                        ]
                    ],
                'CUST_MOBILE' => ['label' => 'Mobile Number', 'rules' => 'required'],
                'CUST_ADDRESS_1' => ['label' => 'Address', 'rules' => 'required'],
                'CUST_COUNTRY' => ['label' => 'Country', 'rules' => 'required'],
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }

            $CUST_DOC_EXPIRY = $this->request->getVar("CUST_DOC_EXPIRY");
            $CUST_DOC_ISSUE = $this->request->getVar("CUST_DOC_ISSUE");

            if ((!empty($CUST_DOC_EXPIRY) && !empty($CUST_DOC_ISSUE)) && 
                (strtotime($CUST_DOC_EXPIRY) < strtotime($CUST_DOC_ISSUE) || strtotime($CUST_DOC_EXPIRY) <= strtotime(date("Y-m-d")))) {
                return $this->respond(responseJson("-402", ['msg' => "Your Document is expired"]));
            }

            $sysid = $this->request->getPost("CUST_ID");
            $log_action_desc = '';

            if(!empty($sysid)){ // Edit Customer 

                $currentCustomer = $this->getProfileDetails($sysid);

                $data = ["CUST_FIRST_NAME" => $this->request->getPost("CUST_FIRST_NAME"),
                    "CUST_MIDDLE_NAME" => $this->request->getPost("CUST_MIDDLE_NAME"),
                    "CUST_LAST_NAME" => $this->request->getPost("CUST_LAST_NAME"),
                    "CUST_LANG" => $this->request->getPost("CUST_LANG"),
                    "CUST_TITLE" => $this->request->getPost("CUST_TITLE"),
                    "CUST_DOB" => $this->request->getPost("CUST_DOB"),
                    "CUST_DOC_NUMBER" => $this->request->getPost("CUST_DOC_NUMBER"),
                    "CUST_DOC_ISSUE" => $this->request->getPost("CUST_DOC_ISSUE"),
                    "CUST_DOC_EXPIRY" => $this->request->getPost("CUST_DOC_EXPIRY"),
                    "CUST_ADDRESS_1" => $this->request->getPost("CUST_ADDRESS_1"),
                    "CUST_ADDRESS_2" => $this->request->getPost("CUST_ADDRESS_2"),
                    "CUST_ADDRESS_3" => $this->request->getPost("CUST_ADDRESS_3"),
                    "CUST_GENDER" => $this->request->getPost("CUST_GENDER"),
                    "CUST_COUNTRY" => $this->request->getPost("CUST_COUNTRY"),
                    "CUST_STATE" => $this->request->getPost("CUST_STATE"),
                    "CUST_CITY" => $this->request->getPost("CUST_CITY"),
                    "CUST_EMAIL" => $this->request->getPost("CUST_EMAIL"),
                    "CUST_MOBILE" => $this->request->getPost("CUST_MOBILE"),
                    "CUST_PHONE" => $this->request->getPost("CUST_PHONE"),
                    "CUST_CLIENT_ID" => $this->request->getPost("CUST_CLIENT_ID"),
                    "CUST_POSTAL_CODE" => $this->request->getPost("CUST_POSTAL_CODE"),
                    "CUST_VIP" => $this->request->getPost("CUST_VIP"),
                    "CUST_NATIONALITY" => $this->request->getPost("CUST_NATIONALITY"),
                    "CUST_BUS_SEGMENT" => $this->request->getPost("CUST_BUS_SEGMENT"),
                    "CUST_COMMUNICATION" => $this->request->getPost("CUST_COMMUNICATION"),
                    "CUST_COMMUNICATION_DESC" => $this->request->getPost("CUST_COMMUNICATION_DESC"),
                    "CUST_ACTIVE" => null !== $this->request->getPost("CUST_ACTIVE") ? 'Y' : 'N',
                    "CUST_UPDATE_DT" => date("Y-m-d")
                ];
                $return = $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $sysid)->update($data); 

                foreach($currentCustomer as $ckey => $cvalue)
                {
                    // Save changes in log description if data is updated/changed
                    if( array_key_exists($ckey, $data) && 
                        (!empty(trim($data[$ckey])) || !empty(trim($cvalue))) && 
                        trim($data[$ckey]) != trim($cvalue))
                    {
                        $log_action_desc .= "<b>".str_replace('CUST_', '', $ckey) . ": </b> '" . $cvalue . "' -> '". $data[$ckey]."'<br/>";
                    }
                }

            }else{
                $data = ["CUST_FIRST_NAME" => $this->request->getPost("CUST_FIRST_NAME"),
                    "CUST_MIDDLE_NAME" => $this->request->getPost("CUST_MIDDLE_NAME"),
                    "CUST_LAST_NAME" => $this->request->getPost("CUST_LAST_NAME"),
                    "CUST_LANG" => $this->request->getPost("CUST_LANG"),
                    "CUST_TITLE" => $this->request->getPost("CUST_TITLE"),
                    "CUST_DOB" => $this->request->getPost("CUST_DOB"),
                    "CUST_DOC_NUMBER" => $this->request->getPost("CUST_DOC_NUMBER"),
                    "CUST_DOC_ISSUE" => $this->request->getPost("CUST_DOC_ISSUE"),
                    "CUST_DOC_EXPIRY" => $this->request->getPost("CUST_DOC_EXPIRY"),
                    "CUST_ADDRESS_1" => $this->request->getPost("CUST_ADDRESS_1"),
                    "CUST_ADDRESS_2" => $this->request->getPost("CUST_ADDRESS_2"),
                    "CUST_ADDRESS_3" => $this->request->getPost("CUST_ADDRESS_3"),
                    "CUST_GENDER" => $this->request->getPost("CUST_GENDER"),
                    "CUST_COUNTRY" => $this->request->getPost("CUST_COUNTRY"),
                    "CUST_STATE" => $this->request->getPost("CUST_STATE"),
                    "CUST_CITY" => $this->request->getPost("CUST_CITY"),
                    "CUST_EMAIL" => $this->request->getPost("CUST_EMAIL"),
                    "CUST_MOBILE" => $this->request->getPost("CUST_MOBILE"),
                    "CUST_PHONE" => $this->request->getPost("CUST_PHONE"),
                    "CUST_CLIENT_ID" => $this->request->getPost("CUST_CLIENT_ID"),
                    "CUST_POSTAL_CODE" => $this->request->getPost("CUST_POSTAL_CODE"),
                    "CUST_VIP" => $this->request->getPost("CUST_VIP"),
                    "CUST_NATIONALITY" => $this->request->getPost("CUST_NATIONALITY"),
                    "CUST_BUS_SEGMENT" => $this->request->getPost("CUST_BUS_SEGMENT"),
                    "CUST_COMMUNICATION" => $this->request->getPost("CUST_COMMUNICATION"),
                    "CUST_COMMUNICATION_DESC" => $this->request->getPost("CUST_COMMUNICATION_DESC"),
                    "CUST_ACTIVE" => null !== $this->request->getPost("CUST_ACTIVE") ? 'Y' : 'N',
                    "CUST_CREATE_DT" => date("d-M-Y")
                ];
                $return = $this->Db->table('FLXY_CUSTOMER')->insert($data); 

                foreach($data as $dkey => $dvalue)
                {
                    // Save changes in log description if data is not empty
                    if(!empty(trim($dvalue)))
                    {
                        $log_action_desc .= "<b>".str_replace('CUST_', '', $dkey) . ": </b> '" . $dvalue ."'<br/>";
                    }
                }
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $this->request->getPost("CUST_FIRST_NAME").' '.$this->request->getPost("CUST_LAST_NAME");
                    $id = $this->Db->insertID();
                    $CUST_TITLE = $this->request->getPost("CUST_TITLE");
                    $CUST_FIRST_NAME = $this->request->getPost("CUST_FIRST_NAME");
                    $CUST_VIP = $this->request->getPost("CUST_VIP");
                    $CUST_PHONE = $this->request->getPost("CUST_PHONE");
                    $CUST_COUNTRY = $this->request->getPost("CUST_COUNTRY");
                    $response = array("ID"=>$id,"FULLNAME"=>$fullname,"CUST_TITLE"=>$CUST_TITLE,"CUST_FIRST_NAME"=>$CUST_FIRST_NAME,"CUST_VIP"=>$CUST_VIP,"CUST_PHONE"=>$CUST_PHONE,"CUST_COUNTRY"=>$CUST_COUNTRY);

                    addActivityLog(3, 41, $id, $log_action_desc);
                }else{
                    $response ='';
                    addActivityLog(3, 50, $sysid, $log_action_desc);
                }
                $result = $this->responseJson("1","0",$return,$response);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function deleteCustomer(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_CUSTOMER')->delete(['CUST_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function rateCodeList()
    {
        $search = $this->request->getPost('search');

        $sql = "SELECT  RT_CD_ID, RTRIM(LTRIM(REPLACE(REPLACE(REPLACE(RT_CD_CODE, CHAR(9), ' '), CHAR(10), ' '), CHAR(13), ' '))) AS RT_CD_CODE,
                        RT_CD_DESC, RT_CD_BEGIN_SELL_DT, RT_CD_END_SELL_DT

                FROM FLXY_RATE_CODE
                WHERE 1=1";
                
        if (trim($search) != '')
            $sql .= "RT_CD_CODE LIKE '%$search%' OR RT_CD_DESC LIKE '%$search%'";

        $response = $this->Db->query($sql)->getResultArray();

        $options = array();

        foreach ($response as $row) {
            $options[] = array( "id" => $row['RT_CD_ID'], "value" => $row['RT_CD_CODE']. ' | ' .$row['RT_CD_DESC']);
        }

        return $options;
    }

    public function Customer(){

        $data['editId'] = null !== $this->request->getGet("editId") ? $this->request->getGet("editId") : null;
        //Check if edit ID exists in Customer table
        if($data['editId'] && !checkValueinTable('CUST_ID', $data['editId'], 'FLXY_CUSTOMER'))
            return redirect()->to(base_url('customer'));  

        $data['show_options'] = null !== $this->request->getGet("show_options") ? '1' : null;
        $data['add'] = null !== $this->request->getGet("add") ? '1' : null;

        $data['rateCodeOptions'] = $this->rateCodeList();
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();
        
        $data['profileTypeOptions'] = profileTypeList();
        $data['membershipTypes'] = getMembershipTypeList(NULL, 'edit');
        
        $data['prefGroupOptions'] = getPreferenceGroupList();

        $data['title'] = getMethodName();
        return view('Reservation/Customer', $data);
    }

    public function customerView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = ' (SELECT  CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_LAST_NAME,
                        TRIM(CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME)) CUST_FULL_NAME,
                        CUST_PASSPORT,CUST_COUNTRY,CUST_EMAIL,CUST_MOBILE,CUST_CLIENT_ID,CUST_DOC_NUMBER 
                        FROM FLXY_CUSTOMER) CUSTOMER_LIST';
        $columns = 'CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_LAST_NAME,CUST_FULL_NAME,CUST_DOC_NUMBER,CUST_COUNTRY,CUST_EMAIL,CUST_MOBILE,CUST_CLIENT_ID';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
        // return view('Dashboard');
    }

    public function editCustomer(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_LAST_NAME,CUST_LANG,CUST_TITLE,CUST_DOB,CUST_PASSPORT,CUST_DOC_NUMBER,CUST_DOC_ISSUE,CUST_DOC_EXPIRY,CUST_ADDRESS_1,CUST_ADDRESS_2,CUST_ADDRESS_3,
        CUST_GENDER,CUST_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC
        ,CUST_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=CUST_STATE AND COUNTRY_CODE=CUST_COUNTRY) CUST_STATE_DESC
        ,CUST_CITY,(SELECT ctname FROM CITY WHERE ID=CUST_CITY) CUST_CITY_DESC
        ,CUST_EMAIL,CUST_MOBILE,CUST_PHONE,CUST_CLIENT_ID,CUST_POSTAL_CODE,CUST_VIP,CUST_NATIONALITY,CUST_BUS_SEGMENT,CUST_ACTIVE ,CUST_COMMUNICATION,CUST_COMMUNICATION_DESC FROM FLXY_CUSTOMER WHERE CUST_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function CustomerChangesView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array( "AC_GP_ID = " => "3", 
                            "ELEMENT_ID = " => "'$sysid'"); // Add condition for Customer

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ACTIVITY_LOG_VIEW';
        $columns = 'LOG_ID,USR_NAME,LOG_DATE,LOG_TIME,AC_TY_DESC,LOG_ACTION_DESCRIPTION';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function CustomerMembershipsView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array( "CUST_ID = " => "'$sysid'", "MEM_STATUS = " => "1"); // Add condition for Customer

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_CUSTOMER_MEMBERSHIP LEFT JOIN FLXY_MEMBERSHIP FM ON FM.MEM_ID = FLXY_CUSTOMER_MEMBERSHIP.MEM_ID';
        $columns = 'CM_ID,CUST_ID,MEM_CODE,MEM_DESC,CM_DIS_SEQ,CM_CARD_NUMBER,CM_EXPIRY_DATE,CM_STATUS,MEM_STATUS';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function showMembershipTypeList()
    {
        $membershipTypes = getMembershipTypeList(null !== $this->request->getPost('custId') ? $this->request->getPost('custId') : 0, $this->request->getPost('mode'));
        echo json_encode($membershipTypes);
    }

    public function getCustomerMembershipsList()
    {
        $custId = $this->request->getPost('custId');

        $sql = "SELECT CM_ID,CUST_ID,MEM_CODE,MEM_DESC,CM_CARD_NUMBER
                FROM FLXY_CUSTOMER_MEMBERSHIP 
                LEFT JOIN FLXY_MEMBERSHIP FM ON FM.MEM_ID = FLXY_CUSTOMER_MEMBERSHIP.MEM_ID 
                WHERE FLXY_CUSTOMER_MEMBERSHIP.CUST_ID = '".$custId."' 
                AND FLXY_CUSTOMER_MEMBERSHIP.CM_STATUS = 1
                AND FM.MEM_STATUS = 1";

        $response = $this->Db->query($sql)->getResultArray();

        $customerMemberships = array();
        if($response != NULL)
        {
            foreach ($response as $row) {
                $customerMemberships[] = array( "id" => $row['CM_ID'], "text" => $row["MEM_CODE"] . " | " . $row["MEM_DESC"], "card_no" => $row["CM_CARD_NUMBER"]);
            }
        }

        echo json_encode($customerMemberships);
    }

    public function insertCustomerMembership()
    {
        try {
            $sysid = $this->request->getPost('CM_ID');

            $validation_rules = [
                'CM_CUST_ID' => ['label' => 'Customer', 'rules' => 'required'],
                'MEM_ID' => ['label' => 'Membership Type', 'rules' => 'required'],
                'CM_CARD_NUMBER' => ['label' => 'Card Number', 'rules' => 'required|is_unique[FLXY_CUSTOMER_MEMBERSHIP.CM_CARD_NUMBER,CM_ID,' . $sysid . ']'],
                'CM_NAME_CARD' => ['label' => 'Name on Card', 'rules' => 'required'],
                'CM_EXPIRY_DATE' => ['label' => 'Expiry Date', 'rules' => 'compareDate', 'errors' => ['compareDate' => 'The Membership has already expired. Try another date']]               
            ];

            // Check if EXPIRY DATE required for selected Membership Type
            if(null !== $this->request->getPost('MEM_ID'))
            {
                $param = ['SYSID' => $this->request->getPost('MEM_ID')];

                $sql = "SELECT FMT.MEM_EXP_DATE_REQ
                        FROM dbo.FLXY_MEMBERSHIP AS FMT
                        WHERE MEM_ID=:SYSID:";

                $response = $this->Db->query($sql, $param)->getRowArray();

                if($response['MEM_EXP_DATE_REQ'] == '1')
                    $validation_rules['CM_EXPIRY_DATE']['rules'] = 'required|compareDate';
            }

            $validate = $this->validate($validation_rules);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $seqparam = ['SYSID' => $this->request->getPost('CM_CUST_ID')];

            $seqsql = " SELECT ISNULL(MAX(CM_DIS_SEQ),0)+1 AS NEW_DIS_SEQ 
                        FROM FLXY_CUSTOMER_MEMBERSHIP 
                        WHERE CUST_ID=:SYSID:";

            $seqresponse = $this->Db->query($seqsql, $seqparam)->getRowArray();

            $data = [
                "CUST_ID" => trim($this->request->getPost('CM_CUST_ID')),
                "MEM_ID" => trim($this->request->getPost('MEM_ID')),
                "CM_NAME_CARD" => trim($this->request->getPost('CM_NAME_CARD')),
                "CM_CARD_NUMBER" => trim($this->request->getPost('CM_CARD_NUMBER')),
                "CM_EXPIRY_DATE" => trim($this->request->getPost('CM_EXPIRY_DATE')),
                "CM_MEMBER_SINCE" => trim($this->request->getPost('CM_MEMBER_SINCE')),
                "CM_DIS_SEQ" => !empty($this->request->getPost('CM_DIS_SEQ')) ? trim($this->request->getPost('CM_DIS_SEQ')) : $seqresponse['NEW_DIS_SEQ'],
                "CM_COMMENTS" => trim($this->request->getPost('CM_COMMENTS')),
                "CM_STATUS" => null !== $this->request->getPost('CM_STATUS') ? '1' : '0',
            ];

            $return = !empty($sysid) ? $this->Db->table('FLXY_CUSTOMER_MEMBERSHIP')->where('CM_ID', $sysid)->update($data) : $this->Db->table('FLXY_CUSTOMER_MEMBERSHIP')->insert($data);
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function editCustomerMembership()
    {
        $param = ['SYSID' => $this->request->getPost('sysid')];

        $sql = "SELECT FCM.*
                FROM dbo.FLXY_CUSTOMER_MEMBERSHIP AS FCM
                WHERE CM_ID=:SYSID:";

        $response = $this->Db->query($sql, $param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteCustomerMembership()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_CUSTOMER_MEMBERSHIP')->delete(['CM_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    function profiles()
    {
        $profileTypes = profileTypeList();
        $data['profileTypeOptions'] = $profileTypes;

        $data['membershipTypes'] = getMembershipTypeList(NULL, 'edit');
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();

        $data['title'] = 'All Profiles';
        
        return view('Reservation/ProfileView', $data);
    }    

    function getProfileDetails($id = 0)
    {
        $param = ['SYSID'=> $id];
        $sql = "SELECT  CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_LAST_NAME,
                        CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) CUST_FULL_NAME,
                        CUST_LANG,CUST_TITLE,CUST_DOB,CUST_PASSPORT,CUST_ADDRESS_1,CUST_ADDRESS_2,CUST_ADDRESS_3,
                        CUST_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC,
                        CUST_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=CUST_STATE AND COUNTRY_CODE=CUST_COUNTRY) CUST_STATE_DESC,
                        CUST_CITY,(SELECT ctname FROM CITY WHERE ID=CUST_CITY) CUST_CITY_DESC,
                        CUST_EMAIL,CUST_MOBILE,CUST_PHONE,CUST_CLIENT_ID,CUST_POSTAL_CODE,
                        CUST_VIP,(SELECT VIP_DESC FROM FLXY_VIP WHERE VIP_ID=CUST_VIP) CUST_VIP_DESC,
                        CUST_NATIONALITY,CUST_BUS_SEGMENT,CUST_GENDER,CUST_COMMUNICATION,CUST_COMMUNICATION_DESC,CUST_ACTIVE,
                        CUST_CREATE_DT,CUST_UPDATE_DT,RATE_CODES,LAST_STAY,CUST_MEMBERSHIPS,CUST_PREFERENCES 
                        
                FROM FLXY_CUSTOMER
                LEFT JOIN ( SELECT NR.PROFILE_ID AS P_ID, NR.PROFILE_TYPE AS P_TYPE, STRING_AGG(RC.RT_CD_CODE, ', ') WITHIN GROUP (ORDER BY RC.RT_CD_CODE) AS RATE_CODES
                            FROM FLXY_RATE_CODE_NEGOTIATED_RATE NR
                            LEFT JOIN FLXY_RATE_CODE RC ON RC.RT_CD_ID = NR.RT_CD_ID
                            GROUP BY NR.PROFILE_ID,NR.PROFILE_TYPE ) PRC ON (PRC.P_ID = FLXY_CUSTOMER.CUST_ID AND PRC.P_TYPE = 1)
                LEFT JOIN ( SELECT RESV_NAME, MAX(RESV_ARRIVAL_DT) AS LAST_STAY
                            FROM FLXY_RESERVATION
                            GROUP BY RESV_NAME) RESV ON (RESV.RESV_NAME = FLXY_CUSTOMER.CUST_ID)
                LEFT JOIN ( SELECT CM.CUST_ID AS C_ID, STRING_AGG(M.MEM_DESC, '<br/>') WITHIN GROUP (ORDER BY M.MEM_DESC) AS CUST_MEMBERSHIPS
                            FROM FLXY_CUSTOMER_MEMBERSHIP CM
                            LEFT JOIN FLXY_MEMBERSHIP M ON M.MEM_ID = CM.MEM_ID
                            GROUP BY CM.CUST_ID ) CMS ON (CMS.C_ID = FLXY_CUSTOMER.CUST_ID)
                LEFT JOIN ( SELECT CP.CUST_ID AS CP_ID, STRING_AGG(PC.PF_CD_DESC, '<br/>') WITHIN GROUP (ORDER BY PC.PF_CD_DESC) AS CUST_PREFERENCES
                            FROM FLXY_CUSTOMER_PREFERENCE CP
                            LEFT JOIN FLXY_PREFERENCE_CODE PC ON PC.PF_CD_ID = CP.PF_CD_ID
                            GROUP BY CP.CUST_ID ) CPS ON (CPS.CP_ID = FLXY_CUSTOMER.CUST_ID)
                
                WHERE CUST_ID=:SYSID:";

        $data = $this->Db->query($sql,$param)->getRowArray();

        return $data;
    }

    public function showCompareProfiles()
    {
        $profile_to_merge = $this->getProfileDetails(null !== $this->request->getGet('pmCustId') ? $this->request->getGet('pmCustId') : 0);
        $orig_profile     = $this->getProfileDetails(null !== $this->request->getGet('ogCustId') ? $this->request->getGet('ogCustId') : 0);
        
        echo json_encode([$orig_profile,$profile_to_merge]);
    }

    public function mergeProfileTables()
    {
        $pmCustId = null !== $this->request->getPost('pmCustId') ? $this->request->getPost('pmCustId') : 0;
        $ogCustId = null !== $this->request->getPost('ogCustId') ? $this->request->getPost('ogCustId') : 0;

        $allMergeFields = ['CUST_FULL_NAME', 'CUST_TITLE', 'CUST_LANG', 'CUST_ADDRESS_1', 'CUST_ADDRESS_2',
            'CUST_CITY_DESC', 'CUST_STATE_DESC', 'CUST_COUNTRY_DESC', 'CUST_POSTAL_CODE', 'CUST_MOBILE', 'CUST_PHONE',
            'CUST_MEMBERSHIPS', 'CUST_PREFERENCES', 'CUST_VIP_DESC', 'LAST_STAY', 'RATE_CODES'
        ];

        $checkedMergeFields = [];

        foreach($allMergeFields as $mergeField)
        {
            if(null !== $this->request->getPost('chk_'.$mergeField))
            {
                switch($mergeField)
                {
                    case 'CUST_FULL_NAME':  $checkedMergeFields[] = 'CUST_FIRST_NAME';
                                            $checkedMergeFields[] = 'CUST_MIDDLE_NAME';
                                            $checkedMergeFields[] = 'CUST_LAST_NAME';
                                            break;

                    case 'CUST_CITY_DESC':
                    case 'CUST_STATE_DESC':
                    case 'CUST_COUNTRY_DESC': 
                    case 'CUST_VIP_DESC':   $checkedMergeFields[] = str_replace("_DESC", "", $mergeField); break;

                    default: $checkedMergeFields[] = $mergeField; break;
                        
                }
            }
        }

        if(mergeCustomer($pmCustId, $ogCustId, $checkedMergeFields))
            echo '1';
        else
            echo '0';    
    }

    function printProfile($id = 0)
    {
        $data = $this->getProfileDetails($id);
        if(!$data)
            return redirect()->to(base_url('customer'));        
                
        $data['title'] = getMethodName();
        $data['base_url'] = '/FlexiGuest/assets/';
        $data['base_path'] = '/FlexiGuest/assets';

        return view('Reservation/PrintProfile', $data);
    }    
 
    function exportProfile($id = 0){

        $data = $this->getProfileDetails($id);
        if(!$data)
            return redirect()->to(base_url('customer'));
        
            $data['base_url'] = '';
            $data['base_path'] = '';
            
        $dompdf = new \Dompdf\Dompdf(); 
        //$dompdf->getOptions()->setChroot('/FlexiGuest');

        $dompdf->setBasePath('/FlexiGuest/assets');
        $dompdf->loadHtml(view('Reservation/PrintProfile', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Profile_Data_Export_'.$id.'_'.date('Y-m-d-H-i-s').'.pdf');
    }

    public function CustomerPreferencesView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array(); // Add condition for Customer

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = '  (SELECT FCPF.CUSTOMER_ID, FCPF.GROUP_ID, FPG.PF_GR_CODE, FPG.PF_GR_DESC, PF_CODES.PREFS FROM
                            (SELECT CUST_ID AS CUSTOMER_ID, PF_GR_ID AS GROUP_ID, PF_CD_ID AS PREFERENCE_ID FROM FLXY_CUSTOMER_PREFERENCE) FCPF
                            LEFT JOIN FLXY_PREFERENCE_GROUP FPG ON FPG.PF_GR_ID = FCPF.GROUP_ID
                            LEFT JOIN FLXY_PREFERENCE_CODE FPC ON FPC.PF_CD_ID = FCPF.PREFERENCE_ID
                            LEFT JOIN ( SELECT FLXY_CUSTOMER_PREFERENCE.PF_GR_ID, STRING_AGG(CONCAT_WS(\' -> \', FLXY_PREFERENCE_CODE.PF_CD_CODE, FLXY_PREFERENCE_CODE.PF_CD_DESC), \'<br/>\') AS PREFS
                                        FROM FLXY_CUSTOMER_PREFERENCE
                                        LEFT JOIN FLXY_PREFERENCE_GROUP ON FLXY_PREFERENCE_GROUP.PF_GR_ID = FLXY_CUSTOMER_PREFERENCE.PF_GR_ID
                                        LEFT JOIN FLXY_PREFERENCE_CODE ON FLXY_PREFERENCE_CODE.PF_CD_ID = FLXY_CUSTOMER_PREFERENCE.PF_CD_ID
                                        WHERE FLXY_CUSTOMER_PREFERENCE.CUST_ID = '.$sysid.'
                                        GROUP BY FLXY_CUSTOMER_PREFERENCE.PF_GR_ID ) PF_CODES ON PF_CODES.PF_GR_ID = FCPF.GROUP_ID
                            GROUP BY FCPF.CUSTOMER_ID, FCPF.GROUP_ID, FPG.PF_GR_CODE, FPG.PF_GR_DESC, PF_CODES.PREFS HAVING CUSTOMER_ID = '.$sysid.'
                        ) PREF_COMB';

        $columns = 'CUSTOMER_ID,GROUP_ID,PF_GR_CODE,PF_GR_DESC,PREFS';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    } 

    public function showPreferenceCodeList()
    {
        $response = getPreferenceCodeList($this->request->getGet('custId'), $this->request->getGet('pf_group'));
        echo json_encode($response);
    }

    public function insertCustomerPreference()
    {
        $custId = trim($this->request->getPost('pref_CUSTOMER_ID'));
        $pfGrp  = trim($this->request->getPost('pref_PF_GR_ID'));
        
        try {

            $validation_rules = [
                'pref_CUSTOMER_ID' => ['label' => 'Customer', 'rules' => 'required'],
                'pref_PF_GR_ID' => ['label' => 'Preference Group', 'rules' => 'required'],
                'pref_PREFS.*' => ['label' => 'Preferences', 'rules' => 'required', 'errors' => ['required' => 'Please select at least one preference']]
            ];

            // Check if EXPIRY DATE required for selected Membership Type
            if(null !== $this->request->getPost('pref_PF_GR_ID'))
            {
                $param = ['SYSID' => $this->request->getPost('pref_PF_GR_ID')];

                $sql = "SELECT PFG.PF_GR_MAX_QTY
                        FROM dbo.FLXY_PREFERENCE_GROUP AS PFG
                        WHERE PF_GR_ID=:SYSID:";

                $response = $this->Db->query($sql, $param)->getRowArray();

                if(!empty($response['PF_GR_MAX_QTY'])){
                    $validation_rules['pref_PREFS.*']['rules'] = 'required|checkMaxItems['.$response['PF_GR_MAX_QTY'].']';
                    $validation_rules['pref_PREFS.*']['errors']['checkMaxItems'] = 'You can only select '.$response['PF_GR_MAX_QTY'].' preference(s)';
                }
            }
            
            $validate = $this->validate($validation_rules);

            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
            
            //Delete all Customer Preferences
            $this->deleteCustomerPreference($custId, $pfGrp);

            $PREFERENCE_CODE_IDS = $this->request->getPost('pref_PREFS[]');

            $no_of_added = 0;
            if($PREFERENCE_CODE_IDS != NULL)
            {
                foreach($PREFERENCE_CODE_IDS as $PREFERENCE_CODE_ID){
                    $data = [
                        "CUST_ID"  => trim($this->request->getPost('pref_CUSTOMER_ID')),
                        "PF_GR_ID" => trim($this->request->getPost('pref_PF_GR_ID')),
                        "PF_CD_ID" => $PREFERENCE_CODE_ID,
                        "CPF_ADDED_ON" => date('Y-m-d H:i:s')
                    ];

                    if($this->Db->table('FLXY_CUSTOMER_PREFERENCE')->insert($data))
                        $no_of_added++;
                }
            }
            
            $result = $no_of_added > 0 ? $this->responseJson("1", "0", "1", $no_of_added) : $this->responseJson("-444", "db insert not successful", "0");
            echo json_encode($result);
            
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteCustomerPreference($custId, $pfGrp)
    {
        $sql = "SELECT CPF_ID
                FROM FLXY_CUSTOMER_PREFERENCE
                WHERE CUST_ID = $custId";

        if(!empty($pfGrp))
            $sql .= " AND PF_GR_ID = '" . $pfGrp . "'";

        $response = $this->Db->query($sql)->getNumRows();

        if($response < 1) // Check if Preference can be deleted
           return false;
        else
        {
            try {
                $delCond = ['CUST_ID' => $custId];
                if(!empty($pfGrp)) $delCond['PF_GR_ID'] = $pfGrp;
                
                $return = $this->Db->query("DELETE FROM FLXY_CUSTOMER_PREFERENCE WHERE CUST_ID = $custId AND PF_GR_ID = $pfGrp"); 
                return $return;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    public function deletePreference()
    {
        $return = $this->deleteCustomerPreference(trim($this->request->getPost('custId')), trim($this->request->getPost('pf_group')));

        $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
        
        echo json_encode($result);
    }

    function getSupportingLov(){
        $sql = 'SELECT VIP_ID,VIP_DESC FROM FLXY_VIP';
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT BUS_SEG_CODE,BUS_SEG_DESC FROM FLXY_BUSINESS_SEGMENT';
        $respon2 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2];
        echo json_encode($data);
    }

    function getCustomerDetail(){
        $param = ['SYSID'=> $this->request->getPost("custId")];
        $sql = "SELECT CUST_ID,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,CUST_VIP,CUST_PHONE FROM FLXY_CUSTOMER WHERE CUST_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function customerList(){
        try{
            $search = $this->request->getPost("search");
            $sql = "SELECT CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULLNAME,CUST_ID 
                    FROM FLXY_CUSTOMER 
                    WHERE ( CUST_FIRST_NAME LIKE '%$search%' 
                            OR CUST_MIDDLE_NAME LIKE '%$search%' 
                            OR CUST_LAST_NAME LIKE '%$search%')
                    ORDER BY CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) ASC";
            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Guest</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['CUST_ID'].'">'.$row['FULLNAME'].'</option>';
            }
            echo $option;
        }catch (Exception $e){
            echo json_encode($e->errors());
        }
    }

    public function CustomerNegotiatedRateView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array("PROFILE_ID = " => "'$sysid'", "PROFILE_TYPE = " => "1", "RT_CD_CODE IS NOT" => "NULL"); // Add condition for main Rate Code

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CODE_NEGOTIATED_RATE_VIEW';
        $columns = 'NG_RT_ID,RT_CD_ID,RT_CD_CODE,PROFILE_ID,PROFILE_TYPE,NG_RT_START_DT,NG_RT_END_DT,NG_RT_DIS_SEQ';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }

    public function insertCustomerNegotiatedRate()
    {
        try {
            $sysid = $this->request->getPost('NG_RT_ID');

            $validate = $this->validate([
                'neg_RT_CD_ID' => ['label' => 'Rate Code', 'rules' => 'required'],
                'neg_PROFILE_ID' => ['label' => 'Profiles', 'rules' => 'required', 'errors' => ['required' => 'No Profiles have been selected. Please try again.']],
                'NG_RT_DIS_SEQ' => ['label' => 'Display Sequence', 'rules' => 'permit_empty|greater_than_equal_to[0]'],
                'NG_RT_START_DT' => ['label' => 'Start Date', 'rules' => 'required|dateOverlapCheckNR[NG_RT_START_DT,'.$this->request->getPost('neg_PROFILE_ID').']', 'errors' => ['dateOverlapCheckNR' => 'The Start Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
                'NG_RT_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate|dateOverlapCheckNR[NG_RT_END_DT,'.$this->request->getPost('neg_PROFILE_ID').']', 'errors' => ['compareDate' => 'The End Date should be after Start Date', 'dateOverlapCheckNR' => 'The End Date overlaps with an existing Negotiated Rate. Change the date or selected user(s)']],
            ]);
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $no_of_added = 0;
            $profile_data = explode('_', trim($this->request->getPost('neg_PROFILE_ID')));
            $data = [
                "RT_CD_ID" => trim($this->request->getPost('neg_RT_CD_ID')),
                "PROFILE_ID" => $profile_data[3],
                "PROFILE_TYPE" => 1,
                "NG_RT_DIS_SEQ" => trim($this->request->getPost('NG_RT_DIS_SEQ')) != '' ? trim($this->request->getPost('NG_RT_DIS_SEQ')) : '',
                "NG_RT_START_DT" => date('Y-m-d', strtotime(trim($this->request->getPost('NG_RT_START_DT')))),
                "NG_RT_END_DT" => trim($this->request->getPost('NG_RT_END_DT')) != '' ? date('Y-m-d', strtotime(trim($this->request->getPost('NG_RT_END_DT')))) : '2030-12-31',
            ];

            //$return = $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->insert($data);
            $return = !empty($sysid) ? $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->where('NG_RT_ID', $sysid)->update($data) : $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->insert($data);

            $no_of_added = $this->Db->affectedRows(); 
            
            $result = $no_of_added > 0 ? $this->responseJson("1", "0", $return, $no_of_added) : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
            
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function deleteCustomerNegotiatedRate()
    {
        $sysid = $this->request->getPost('sysid');

        try {
            $return = $this->Db->table('FLXY_RATE_CODE_NEGOTIATED_RATE')->delete(['NG_RT_ID' => $sysid]);
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    function getSupportingReservationLov(){

        $sql = 'SELECT MEM_CODE CODE,MEM_DESC DESCS FROM FLXY_MEMBERSHIP';
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RT_CL_CODE CODE,RT_CL_DESC DESCS FROM FLXY_RATE_CLASS';
        $respon2 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RT_CD_CODE CODE,RT_CD_DESC DESCS FROM FLXY_RATE_CODE';
        $respon3 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RM_CL_CODE CODE,RM_CL_DESC DESCS FROM FLXY_ROOM_CLASS';
        $respon4 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RM_FT_CODE CODE,RM_FT_DESC DESCS FROM FLXY_ROOM_FEATURE';
        $respon5 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT PUR_ST_ID CODE,PUR_ST_DESC DESCS FROM FLXY_PURPOSE_STAY';
        $respon6 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT VIP_ID CODE,VIP_DESC DESCS FROM FLXY_VIP';
        $respon7 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS=\'TRNSP_TYPE\'';
        $respon8 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS=\'GUST_TYPE\'';
        $respon9 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS=\'ENRY_PNT\'';
        $respon10 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS=\'RESV_PROF\'';
        $respon11 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5,$respon6,$respon7,$respon8,$respon9,$respon10,$respon11];
        echo json_encode($data);
    }

    
    function insertCompany(){
        try{
            $validate = $this->validate([
                'COM_ACCOUNT' => ['label' => 'Account', 'rules' => 'required|min_length[3]'],
                'COM_CONTACT_EMAIL' => ['label' => 'Contact Email', 'rules' => 'required|valid_email'],
                'COM_COUNTRY' => ['label' => 'Country', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("COM_ID");
            if(!empty($sysid)){
                $data = ["COM_ACCOUNT" => $this->request->getPost("COM_ACCOUNT"),
                    "COM_ADDRESS1" => $this->request->getPost("COM_ADDRESS1"),
                    "COM_ADDRESS2" => $this->request->getPost("COM_ADDRESS2"),
                    "COM_ADDRESS3" => $this->request->getPost("COM_ADDRESS3"),
                    "COM_COUNTRY" => $this->request->getPost("COM_COUNTRY"),
                    "COM_STATE" => $this->request->getPost("COM_STATE"),
                    "COM_CITY" => $this->request->getPost("COM_CITY"),
                    "COM_POSTAL" => $this->request->getPost("COM_POSTAL"),
                    "COM_CONTACT_FR" => $this->request->getPost("COM_CONTACT_FR"),
                    "COM_CONTACT_LT" => $this->request->getPost("COM_CONTACT_LT"),
                    "COM_CONTACT_NO" => $this->request->getPost("COM_CONTACT_NO"),
                    "COM_CONTACT_EMAIL" => $this->request->getPost("COM_CONTACT_EMAIL"),
                    "COM_TYPE" => $this->request->getPost("COM_TYPE"),
                    "COM_CORP_ID" => $this->request->getPost("COM_CORP_ID"),
                    "COM_ACTIVE" => $this->request->getPost("COM_ACTIVE"),
                    "COM_COMMUNI_CODE" => $this->request->getPost("COM_COMMUNI_CODE"),
                    "COM_COMMUNI_DESC" => $this->request->getPost("COM_COMMUNI_DESC"),
                    "COM_UPDATE_UID" => session()->get('USR_ID'),
                    "COM_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_COMPANY_PROFILE')->where('COM_ID', $sysid)->update($data); 
            }else{
                $data = ["COM_ACCOUNT" => $this->request->getPost("COM_ACCOUNT"),
                    "COM_ADDRESS1" => $this->request->getPost("COM_ADDRESS1"),
                    "COM_ADDRESS2" => $this->request->getPost("COM_ADDRESS2"),
                    "COM_ADDRESS3" => $this->request->getPost("COM_ADDRESS3"),
                    "COM_COUNTRY" => $this->request->getPost("COM_COUNTRY"),
                    "COM_STATE" => $this->request->getPost("COM_STATE"),
                    "COM_CITY" => $this->request->getPost("COM_CITY"),
                    "COM_POSTAL" => $this->request->getPost("COM_POSTAL"),
                    "COM_CONTACT_FR" => $this->request->getPost("COM_CONTACT_FR"),
                    "COM_CONTACT_LT" => $this->request->getPost("COM_CONTACT_LT"),
                    "COM_CONTACT_NO" => $this->request->getPost("COM_CONTACT_NO"),
                    "COM_CONTACT_EMAIL" => $this->request->getPost("COM_CONTACT_EMAIL"),
                    "COM_TYPE" => $this->request->getPost("COM_TYPE"),
                    "COM_CORP_ID" => $this->request->getPost("COM_CORP_ID"),
                    "COM_ACTIVE" => $this->request->getPost("COM_ACTIVE"),
                    "COM_COMMUNI_CODE" => $this->request->getPost("COM_COMMUNI_CODE"),
                    "COM_COMMUNI_DESC" => $this->request->getPost("COM_COMMUNI_DESC"),
                    "COM_CREATE_UID" => (!empty($this->request->getPost("COM_CREATE_UID")) ? $this->request->getPost("COM_CREATE_UID"):null),
                    "COM_CREATE_DT" => date("d-M-Y"),
                    "COM_UPDATE_UID" => (!empty($this->request->getPost("COM_UPDATE_UID")) ? $this->request->getPost("COM_UPDATE_UID"):null),
                    "COM_UPDATE_DT" => (!empty($this->request->getPost("COM_UPDATE_DT")) ? $this->request->getPost("COM_UPDATE_DT"):null)
                ];
                $return = $this->Db->table('FLXY_COMPANY_PROFILE')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $this->request->getPost("COM_ACCOUNT");
                    $id = $this->Db->insertID();
                    $response = array("ID"=>$id,"FULLNAME"=>$fullname);
                }else{
                    $response ='';
                }
                $result = $this->responseJson("1","0",$return,$response);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function insertAgent(){
        try{
            $validate = $this->validate([
                'COM_ACCOUNT' => ['label' => 'Account', 'rules' => 'required|min_length[3]'],
                'COM_CONTACT_EMAIL' => ['label' => 'Contact Email', 'rules' => 'required|valid_email'],
                'COM_COUNTRY' => ['label' => 'Country', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("AGN_ID");
            if(!empty($sysid)){
                $data = ["AGN_ACCOUNT" => $this->request->getPost("COM_ACCOUNT"),
                    "AGN_ADDRESS1" => $this->request->getPost("COM_ADDRESS1"),
                    "AGN_ADDRESS2" => $this->request->getPost("COM_ADDRESS2"),
                    "AGN_ADDRESS3" => $this->request->getPost("COM_ADDRESS3"),
                    "AGN_COUNTRY" => $this->request->getPost("COM_COUNTRY"),
                    "AGN_STATE" => $this->request->getPost("COM_STATE"),
                    "AGN_CITY" => $this->request->getPost("COM_CITY"),
                    "AGN_POSTAL" => $this->request->getPost("COM_POSTAL"),
                    "AGN_TERRITORY" => $this->request->getPost("COM_TERRITORY"),
                    "AGN_IATA" => $this->request->getPost("COM_IATA"),
                    "AGN_CONTACT_EMAIL" => $this->request->getPost("COM_CONTACT_EMAIL"),
                    "AGN_TYPE" => $this->request->getPost("COM_TYPE"),
                    "AGN_CONTACT_NO" => $this->request->getPost("COM_CONTACT_NO"),
                    "AGN_ACTIVE" => $this->request->getPost("COM_ACTIVE"),
                    "AGN_COMMUNI_CODE" => $this->request->getPost("COM_COMMUNI_CODE"),
                    "AGN_COMMUNI_DESC" => $this->request->getPost("COM_COMMUNI_DESC"),
                    "AGN_UPDATE_UID" => session()->get('USR_ID'),
                    "AGN_UPDATE_DT" => date("d-M-Y")
                 ];
                //  print_r($_POST);exit;
            $return = $this->Db->table('FLXY_AGENT_PROFILE')->where('AGN_ID', $sysid)->update($data); 
            }else{
                $data = ["AGN_ACCOUNT" => $this->request->getPost("COM_ACCOUNT"),
                    "AGN_ADDRESS1" => $this->request->getPost("COM_ADDRESS1"),
                    "AGN_ADDRESS2" => $this->request->getPost("COM_ADDRESS2"),
                    "AGN_ADDRESS3" => $this->request->getPost("COM_ADDRESS3"),
                    "AGN_COUNTRY" => $this->request->getPost("COM_COUNTRY"),
                    "AGN_STATE" => $this->request->getPost("COM_STATE"),
                    "AGN_CITY" => $this->request->getPost("COM_CITY"),
                    "AGN_POSTAL" => $this->request->getPost("COM_POSTAL"),
                    "AGN_TERRITORY" => $this->request->getPost("COM_TERRITORY"),
                    "AGN_IATA" => $this->request->getPost("COM_IATA"),
                    "AGN_CONTACT_NO" => $this->request->getPost("COM_CONTACT_NO"),
                    "AGN_CONTACT_EMAIL" => $this->request->getPost("COM_CONTACT_EMAIL"),
                    "AGN_TYPE" => $this->request->getPost("COM_TYPE"),
                    "AGN_ACTIVE" => $this->request->getPost("COM_ACTIVE"),
                    "AGN_COMMUNI_CODE" => $this->request->getPost("COM_COMMUNI_CODE"),
                    "AGN_COMMUNI_DESC" => $this->request->getPost("COM_COMMUNI_DESC"),
                    "AGN_CREATE_UID" => (!empty($this->request->getPost("COM_CREATE_UID")) ? $this->request->getPost("COM_CREATE_UID"):null),
                    "AGN_CREATE_DT" => date("d-M-Y"),
                    "AGN_UPDATE_UID" => (!empty($this->request->getPost("COM_UPDATE_UID")) ? $this->request->getPost("COM_UPDATE_UID"):null),
                    "AGN_UPDATE_DT" => (!empty($this->request->getPost("COM_UPDATE_DT")) ? $this->request->getPost("COM_UPDATE_DT"):null)
                ];
                $return = $this->Db->table('FLXY_AGENT_PROFILE')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $this->request->getPost("COM_ACCOUNT");
                    $id = $this->Db->insertID();
                    $response = array("ID"=>$id,"FULLNAME"=>$fullname);
                }else{
                    $response ='';
                }
                $result = $this->responseJson("1","0",$return,$response);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    // company modal 
    public function company(){

        $data['editId'] = null !== $this->request->getGet("editId") ? $this->request->getGet("editId") : null;
        //Check if edit ID exists in Company table
        if($data['editId'] && !checkValueinTable('COM_ID', $data['editId'], 'FLXY_COMPANY_PROFILE'))
            return redirect()->to(base_url('company'));  

        $data['add'] = null !== $this->request->getGet("add") ? '1' : null;

        $data['title'] = getMethodName();
        return view('Reservation/Company', $data);
    }

    public function companyView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_COMPANY_PROFILE';
        $columns = 'COM_ID,COM_ACCOUNT,COM_COUNTRY,COM_CONTACT_EMAIL,COM_CORP_ID,COM_CONTACT_NO';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function editCompany(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT COM_ID,COM_ACCOUNT,COM_ADDRESS1,COM_ADDRESS2,COM_ADDRESS3,
        COM_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=COM_COUNTRY) COM_COUNTRY_DESC
        ,COM_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=COM_STATE AND COUNTRY_CODE=COM_COUNTRY)COM_STATE_DESC
        ,COM_CITY,(SELECT ctname FROM CITY WHERE ID=COM_CITY)COM_CITY_DESC
        ,COM_POSTAL,COM_CONTACT_FR,COM_CONTACT_LT,COM_CONTACT_NO,COM_CONTACT_EMAIL,COM_TYPE,COM_CORP_ID,COM_ACTIVE,COM_COMMUNI_CODE,COM_COMMUNI_DESC FROM FLXY_COMPANY_PROFILE WHERE COM_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function editAgent(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT AGN_ID,AGN_ACCOUNT COM_ACCOUNT,AGN_ADDRESS1 COM_ADDRESS1,AGN_ADDRESS2 COM_ADDRESS2,AGN_ADDRESS3 COM_ADDRESS3,AGN_COUNTRY COM_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=AGN_COUNTRY) COM_COUNTRY_DESC
        ,AGN_STATE COM_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=AGN_STATE AND COUNTRY_CODE=AGN_COUNTRY) COM_STATE_DESC,AGN_CITY COM_CITY,(SELECT ctname FROM CITY WHERE ID=AGN_CITY) COM_CITY_DESC
        ,AGN_POSTAL COM_POSTAL,AGN_TERRITORY COM_TERRITORY,AGN_IATA COM_IATA,AGN_CONTACT_NO COM_CONTACT_NO,AGN_CONTACT_EMAIL COM_CONTACT_EMAIL,AGN_TYPE COM_TYPE,AGN_ACTIVE COM_ACTIVE,AGN_COMMUNI_CODE COM_COMMUNI_CODE,AGN_COMMUNI_DESC COM_COMMUNI_DESC FROM FLXY_AGENT_PROFILE WHERE AGN_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function deleteCompany(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_COMPANY_PROFILE')->delete(['COM_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    // company modal 
    // agent modal

    public function agent(){
        $data['editId'] = null !== $this->request->getGet("editId") ? $this->request->getGet("editId") : null;
        //Check if edit ID exists in Agent table
        if($data['editId'] && !checkValueinTable('AGN_ID', $data['editId'], 'FLXY_AGENT_PROFILE'))
            return redirect()->to(base_url('agent'));  

        $data['add'] = null !== $this->request->getGet("add") ? '1' : null;

        $data['title'] = getMethodName();
        return view('Reservation/Agent', $data);
    }

    public function AgentView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_AGENT_PROFILE';
        $columns = 'AGN_ID,AGN_ACCOUNT,AGN_COUNTRY,AGN_CONTACT_NO,AGN_CONTACT_EMAIL,AGN_TYPE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function deleteAgent(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_AGENT_PROFILE')->delete(['AGN_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    // agent modal

    // Group modal
    public function group(){
        $data['editId'] = null !== $this->request->getGet("editId") ? $this->request->getGet("editId") : null;
        //Check if edit ID exists in Group table
        if($data['editId'] && !checkValueinTable('GRP_ID', $data['editId'], 'FLXY_GROUP'))
            return redirect()->to(base_url('group'));

        $data['add'] = null !== $this->request->getGet("add") ? '1' : null;    

        $data['title'] = getMethodName();
        return view('Reservation/Group', $data);
    }

    public function GroupView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_GROUP';
        $columns = 'GRP_ID,GRP_NAME,GRP_COUNTRY,GRP_CONTACT_NO,GRP_EMAIL,GRP_ADDRESS1';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }
    
    function editGroup(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT GRP_ID,GRP_NAME,GRP_LANG,GRP_ADDRESS1,GRP_ADDRESS2,GRP_ADDRESS3,
        GRP_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=GRP_COUNTRY) GRP_COUNTRY_DESC
        ,GRP_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=GRP_STATE AND COUNTRY_CODE=GRP_COUNTRY)GRP_STATE_DESC
        ,GRP_CITY,(SELECT ctname FROM CITY WHERE ID=GRP_CITY)GRP_CITY_DESC
        ,GRP_POSTAL,GRP_CONTACT_NO,GRP_EMAIL,GRP_VIP,GRP_CURR,GRP_COMMUNI_CODE,GRP_COMMUNI_DESC,GRP_NOTES,GRP_ACTIVE FROM FLXY_GROUP WHERE GRP_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    
    function insertGroup(){
        try{
            $validate = $this->validate([
                'GRP_NAME' => ['label' => 'Account', 'rules' => 'required|min_length[3]'],
                'GRP_EMAIL' => ['label' => 'Contact Email', 'rules' => 'required|valid_email'],
                'GRP_COUNTRY' => ['label' => 'Country', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("GRP_ID");
            if(!empty($sysid)){
                $data = ["GRP_NAME" => $this->request->getPost("GRP_NAME"),
                    "GRP_LANG" => $this->request->getPost("GRP_LANG"),
                    "GRP_ADDRESS1" => $this->request->getPost("GRP_ADDRESS1"),
                    "GRP_ADDRESS2" => $this->request->getPost("GRP_ADDRESS2"),
                    "GRP_ADDRESS3" => $this->request->getPost("GRP_ADDRESS3"),
                    "GRP_COUNTRY" => $this->request->getPost("GRP_COUNTRY"),
                    "GRP_STATE" => $this->request->getPost("GRP_STATE"),
                    "GRP_CITY" => $this->request->getPost("GRP_CITY"),
                    "GRP_POSTAL" => $this->request->getPost("GRP_POSTAL"),
                    "GRP_CONTACT_NO" => $this->request->getPost("GRP_CONTACT_NO"),
                    "GRP_EMAIL" => $this->request->getPost("GRP_EMAIL"),
                    "GRP_VIP" => $this->request->getPost("GRP_VIP"),
                    "GRP_CURR" => $this->request->getPost("GRP_CURR"),
                    "GRP_COMMUNI_CODE" => $this->request->getPost("GRP_COMMUNI_CODE"),
                    "GRP_COMMUNI_DESC" => $this->request->getPost("GRP_COMMUNI_DESC"),
                    "GRP_NOTES" => $this->request->getPost("GRP_NOTES"),
                    "GRP_ACTIVE" => $this->request->getPost("GRP_ACTIVE"),
                    "GRP_UPDATE_UID" => session()->get('USR_ID'),
                    "GRP_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_GROUP')->where('GRP_ID', $sysid)->update($data); 
            }else{
                $data = ["GRP_NAME" => $this->request->getPost("GRP_NAME"),
                    "GRP_LANG" => $this->request->getPost("GRP_LANG"),
                    "GRP_ADDRESS1" => $this->request->getPost("GRP_ADDRESS1"),
                    "GRP_ADDRESS2" => $this->request->getPost("GRP_ADDRESS2"),
                    "GRP_ADDRESS3" => $this->request->getPost("GRP_ADDRESS3"),
                    "GRP_COUNTRY" => $this->request->getPost("GRP_COUNTRY"),
                    "GRP_STATE" => $this->request->getPost("GRP_STATE"),
                    "GRP_CITY" => $this->request->getPost("GRP_CITY"),
                    "GRP_POSTAL" => $this->request->getPost("GRP_POSTAL"),
                    "GRP_CONTACT_NO" => $this->request->getPost("GRP_CONTACT_NO"),
                    "GRP_EMAIL" => $this->request->getPost("GRP_EMAIL"),
                    "GRP_VIP" => $this->request->getPost("GRP_VIP"),
                    "GRP_CURR" => $this->request->getPost("GRP_CURR"),
                    "GRP_COMMUNI_CODE" => $this->request->getPost("GRP_COMMUNI_CODE"),
                    "GRP_COMMUNI_DESC" => $this->request->getPost("GRP_COMMUNI_DESC"),
                    "GRP_NOTES" => $this->request->getPost("GRP_NOTES"),
                    "GRP_ACTIVE" => $this->request->getPost("GRP_ACTIVE"),
                    "GRP_CREATE_UID" => session()->get('USR_ID'),
                    "GRP_CREATE_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_GROUP')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $this->request->getPost("GRP_NAME");
                    $id = $this->Db->insertID();
                    $response = array("ID"=>$id,"FULLNAME"=>$fullname);
                }else{
                    $response ='';
                }
                $result = $this->responseJson("1","0",$return,$response);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    
    function getSupportingVipLov(){
        $sql = 'SELECT VIP_ID,VIP_DESC FROM FLXY_VIP';
        $respon = $this->Db->query($sql)->getResultArray();
        echo json_encode($respon);
    }

    function deleteGroup(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_GROUP')->delete(['GRP_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    // Group modal
    
    // Block modal
    public function block(){

        $data['userList'] = geUsersList(); 
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['title'] = getMethodName();
        return view('Reservation/BlockView', $data);
    }

    public function BlockView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_BLOCK 
                      LEFT JOIN FLXY_BLOCK_STATUS_CODE ON BLK_STS_CD_ID = BLK_STATUS
                      LEFT JOIN FLXY_RESERVATION_METHOD ON RM_ID = BLK_RESER_METHOD';
        $columns = 'BLK_ID,BLK_CODE,BLK_NAME,BLK_START_DT,BLK_END_DT,BLK_STATUS,BLK_STS_CD_DESC,BLK_RESER_TYPE,RM_DESC,BLK_RESER_METHOD';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function BlockReservationView(){

        $_POST = filter_var($_POST, \FILTER_CALLBACK, ['options' => 'trim']);

        $postValues = $this->request->getPost('columns');
        $blkId = null !== $this->request->getPost('RESV_BLOCK') && !empty(trim($this->request->getPost('RESV_BLOCK'))) ? 
                 $this->request->getPost('RESV_BLOCK') : '0';

        $search_keys = ['S_GUEST_NAME', 'S_CUST_FIRST_NAME', 'S_GUEST_PHONE', 'S_CUST_EMAIL', 
                        'S_COMPNAME', 'S_AGENTNAME', 'S_RESV_ROOM', 'S_RESV_RM_TYPE', 'S_RESV_NO', 
                        'S_ARRIVAL_FROM', 'S_ARRIVAL_TO', 'S_DEPARTURE_FROM', 'S_DEPARTURE_TO', 'S_ROOM_NO', 
                        'S_SEARCH_TYPE', 'S_RESV_CREATE_DT', 'S_RESV_CREATE_UID'];

        $init_cond = array("RESV_BLOCK = " => $blkId);

        if($search_keys != NULL){
            foreach($search_keys as $search_key)
            {
                if(null !== $this->request->getPost($search_key) && !empty(trim($this->request->getPost($search_key))))
                {
                    $value = trim($this->request->getPost($search_key));

                    switch($search_key)
                    {
                        case 'S_GUEST_NAME': $init_cond["CONCAT_WS(' ', CUST_FIRST_NAME, CUST_LAST_NAME) LIKE "] = "'%$value%'"; break;
                        //case 'S_GUEST_FIRST_NAME': $init_cond["SUBSTRING(FULLNAME,1,(CHARINDEX(' ',FULLNAME + ' ')-1)) LIKE "] = "'%$value%'"; break;
                        case 'S_GUEST_PHONE': $init_cond["(CUST_MOBILE LIKE '%$value%' OR CUST_PHONE LIKE '%$value%')"] = ""; break;
                        case 'S_ARRIVAL_FROM': $init_cond["RESV_ARRIVAL_DT >= "] = "'$value'"; break;
                        case 'S_ARRIVAL_TO': $init_cond["RESV_ARRIVAL_DT <= "] = "'$value'"; break;
                        case 'S_DEPARTURE_FROM': $init_cond["RESV_DEPARTURE >= "] = "'$value'"; break;
                        case 'S_DEPARTURE_TO': $init_cond["RESV_DEPARTURE <= "] = "'$value'"; break;
                        case 'S_RESV_CREATE_DT': $init_cond["RESV_CREATE_DT = "] = "'$value'"; break;
                        
                        case 'S_SEARCH_TYPE': { switch($value)
                                                {
                                                    case '1': $init_cond["RESV_ARRIVAL_DT = "] = "'".date('Y-m-d')."'"; break;
                                                    case '2': $init_cond["RESV_DEPARTURE = "]  = "'".date('Y-m-d')."'"; break;
                                                    case '3': $init_cond["RESV_ARRIVAL_DT = "] = "RESV_DEPARTURE"; break;
                                                    case '4': $init_cond["RESV_STATUS = "] = "'Checked-In'"; break;
                                                    case '5': $init_cond["RESV_STATUS = "] = "'Checked-Out'"; break;
                                                    case '7': $init_cond["RESV_STATUS = "] = "'Cancelled'"; break;
                                                    default: break;
                                                }
                                              } break;

                        default: $init_cond["".ltrim($search_key, "S_")." LIKE "] = "'%$value%'"; break;
                        
                    }
                    
                }
            }
        }
        
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RESERVATION_VIEW LEFT JOIN FLXY_CUSTOMER C ON C.CUST_ID = FLXY_RESERVATION_VIEW.RESV_NAME';
        $columns = 'RESV_ID|RESV_NO|RESV_BLOCK|FORMAT(RESV_ARRIVAL_DT,\'dd-MMM-yyyy\')RESV_ARRIVAL_DT|RESV_STATUS|RESV_NIGHT|FORMAT(RESV_DEPARTURE,\'dd-MMM-yyyy\')RESV_DEPARTURE|RESV_RM_TYPE|RESV_ROOM|(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RM_TY_DESC|RESV_NO_F_ROOM|CUST_ID|CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_LAST_NAME)CUST_FIRST_NAME|CUST_MIDDLE_NAME|CUST_LAST_NAME|CUST_EMAIL|CUST_MOBILE|CUST_PHONE|RESV_FEATURE|FORMAT(RESV_CREATE_DT,\'dd-MMM-yyyy\')RESV_CREATE_DT|RESV_PURPOSE_STAY';
        $mine->generate_DatatTable($tableName,$columns,$init_cond,'|');
        exit;
        // return view('Dashboard');
    }

    public function companyList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT COM_ID,COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ACCOUNT like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Company</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['COM_ID'].'">'.$row['COM_ACCOUNT'].'</option>';
        }
        echo $option;
    }

    public function agentList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT AGN_ID,AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE AGN_ACCOUNT like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Agent</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['AGN_ID'].'">'.$row['AGN_ACCOUNT'].'</option>';
        }
        echo $option;
    }

    public function groupList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT GRP_ID,GRP_NAME FROM FLXY_GROUP WHERE GRP_NAME like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Group</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['GRP_ID'].'">'.$row['GRP_NAME'].'</option>';
        }
        echo $option;
    }

    public function numRooms(){
        $rmtype = $this->request->getPost("rmtype");
        $start_date = $this->request->getPost("arr_date");
        $end_date = $this->request->getPost("dep_date");

        $free_rooms = $this->getFreeRooms($start_date, $end_date, $rmtype);

        echo isset($free_rooms[$rmtype]) ? $free_rooms[$rmtype] : 0;
    }
    
    public function getFreeRooms($date1, $date2 = '', $rmtype_id = '')
    {
        $sql = "SELECT RM_TYPE_REF_ID, RM_TYPE, (COUNT(*)-COUNT(RESV_ROOM)) AS NUM_ROOMS      
                FROM FLXY_ROOM 
                LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                            FROM FLXY_ROOM_STATUS_LOG
                            GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
                
                LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
                LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS

                LEFT JOIN (SELECT RESV_ROOM_ID AS RESV_ROOM     
                            FROM FLXY_RESERVATION
                            WHERE RESV_ROOM_ID > 0";
        if($date2 != '')
            $sql .= "       AND ('$date1' <= RESV_DEPARTURE AND '$date2' >= RESV_ARRIVAL_DT)";
        else        
            $sql .= "       AND '$date1' BETWEEN RESV_ARRIVAL_DT AND RESV_DEPARTURE";

        $sql .= "           AND RESV_STATUS NOT IN ('Checked-Out','Cancelled')
                            GROUP BY RESV_ROOM_ID) RESV_ROOMS ON RESV_ROOMS.RESV_ROOM = RM_ID

                WHERE RM_STATUS_ID IS NULL OR RM_STATUS_ID NOT IN (4,5)";
        
        if($rmtype_id != '')
        $sql .= "AND RM_TYPE_REF_ID = '".$rmtype_id."'";
        
        $sql .= "GROUP BY RM_TYPE_REF_ID, RM_TYPE
                ORDER BY RM_TYPE ASC";

        $response = $this->Db->query($sql)->getResultArray();
        $roomtype_rooms = [];

        foreach ($response as $roomtype_room) {
            $roomtype_rooms[$roomtype_room['RM_TYPE_REF_ID']] = $roomtype_room['NUM_ROOMS'];
        }

        return $roomtype_rooms;
    }

    public function showBlockRoomPool()
    {
        $block_id = $this->request->getPost("blkId");
        
        $block_sql = "SELECT BLK_ID,BLK_NAME,BLK_START_DT,BLK_END_DT FROM FLXY_BLOCK WHERE BLK_ID=:SYSID:";                                      
        $block_data = empty($block_id) ? [] : $this->Db->query($block_sql,['SYSID'=> $block_id])->getRowArray();

        $rmtype_sql = "SELECT RM_TY_ID,RM_TY_CODE FROM FLXY_ROOM_TYPE ORDER BY RM_TY_CODE ASC";
        $rmtype_data = $this->Db->query($rmtype_sql)->getResultArray();

        $room_pool_html = '<table id="room_pool" class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>';

        foreach ($rmtype_data as $rmtype_row) {
            $room_pool_html .= '<th>' . $rmtype_row['RM_TY_CODE'] . '</th>';
        }
        
        $room_pool_html .= '    </tr>
                            </thead>';

        $start_date = empty($block_id) ? date('Y-m-d') : $block_data['BLK_START_DT'];
        $end_date   = empty($block_id) ? date('Y-m-d', strtotime("+7 day")) : $block_data['BLK_END_DT'];

        $BEGIN_DATE_SEC     = strtotime($start_date);
        $END_DATE_SEC       = strtotime($end_date);
        $DATEDIFF       = (int)$END_DATE_SEC-(int)$BEGIN_DATE_SEC;
        $AVAILABLE_DAYS = round($DATEDIFF / (60 * 60 * 24));

        for($j = 1; $j <= ($AVAILABLE_DAYS + 1); $j++ ){

            $room_pool_html .= ' <tr>';

            $sCurrentDate = gmdate("Y-m-d", strtotime("+$j day", $BEGIN_DATE_SEC));
            $room_pool_html .= '<td>' . date('d/m/y D', strtotime($sCurrentDate)) . '</td>';

            $rmtype_rooms = $this->getFreeRooms($sCurrentDate);

            foreach ($rmtype_data as $rmtype_row) {
                $numRooms = !array_key_exists($rmtype_row['RM_TY_ID'], $rmtype_rooms) ? 0 : $rmtype_rooms[$rmtype_row['RM_TY_ID']];
                $room_pool_html .= '<td>' . $numRooms . '</td>';
            }

            $room_pool_html .= ' </tr>';
        }

        $room_pool_html .= '</table>';
        
        echo $room_pool_html;        
    }

    public function getRateCodesByRoomType(){
        $rmtype = $this->request->getPost("rmtype");
        $arr_date = date('Y-m-d', strtotime(trim($this->request->getPost('arr_date'))));
        $dep_date = date('Y-m-d', strtotime(trim($this->request->getPost('dep_date'))));
        
        $sql = "SELECT  RC.RT_CD_ID, RC.RT_CD_CODE, RC.RT_CD_DESC, 
                        CAST(RCD.RT_CD_DT_1_ADULT AS DECIMAL(10, 2)) AS ACTUAL_ADULT_PRICE 
                FROM FLXY_RATE_CODE_DETAIL RCD
                LEFT JOIN FLXY_RATE_CODE RC ON RC.RT_CD_ID = RCD.RT_CD_ID
                WHERE CONCAT(',', RCD.RT_CD_DT_ROOM_TYPES, ',') LIKE '%,".$rmtype.",%' 
                AND ( '".$arr_date."' BETWEEN RT_CD_START_DT AND RT_CD_END_DT 
                      AND '".$dep_date."' BETWEEN RT_CD_START_DT AND RT_CD_END_DT)";
        
        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose a Rate Code</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['RT_CD_CODE'] . '" data-rtcode-price="'.$row['ACTUAL_ADULT_PRICE'].'">' . $row['RT_CD_CODE'] . ' | ' . $row['RT_CD_DESC'] . '</option>';
        }

        echo $option;        
    }

    public function getSupportingblkLov(){
        $sql = "SELECT MK_CD_ID CODE,MK_CD_DESC DESCS FROM FLXY_MARKET_CODE";
        $respon1 = $this->Db->query($sql)->getResultArray();
        
        $sql = "SELECT SOR_ID CODE,SOR_DESC DESCS FROM FLXY_SOURCE";
        $respon2 = $this->Db->query($sql)->getResultArray();
        
        $sql = "SELECT RM_ID CODE,RM_DESC DESCS FROM FLXY_RESERVATION_METHOD";
        $respon3 = $this->Db->query($sql)->getResultArray();
        
        $sql = "SELECT BLK_STS_CD_ID CODE,BLK_STS_CD_DESC DESCS, BLK_STS_CD_RETURN_INVENTORY RET_INV 
                FROM FLXY_BLOCK_STATUS_CODE WHERE BLK_STS_CD_STARTING_STATUS = 1";
        $respon4 = $this->Db->query($sql)->getResultArray();
        
        $sql = "SELECT BLK_STS_CD_ID CODE,BLK_STS_CD_DESC DESCS, BLK_STS_CD_RETURN_INVENTORY RET_INV 
                FROM FLXY_BLOCK_STATUS_CODE";
        $respon5 = $this->Db->query($sql)->getResultArray();

        $sql = "SELECT RESV_TY_ID CODE,RESV_TY_DESC DESCS FROM FLXY_RESERVATION_TYPE";
        $respon6 = $this->Db->query($sql)->getResultArray();
        
                
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5,$respon6];
        echo json_encode($data);
    }

    function insertBlock(){
        try{
            $sysid = $this->request->getPost("BLK_ID");

            $validate = $this->validate([
                'BLK_NAME' => ['label' => 'Block Name', 'rules' => 'required'],
                'BLK_CODE' => ['label' => 'Block Code', 'rules' => 'required|is_unique[FLXY_BLOCK.BLK_CODE,BLK_ID,' . $sysid . ']'],
                'BLK_START_DT' => ['label' => 'Start Date', 'rules' => 'required'],
                'BLK_END_DT' => ['label' => 'End Date', 'rules' => 'required|compareDate', 'errors' => ['compareDate' => 'The End Date should be after Begin Date']],
                'BLK_STATUS' => ['label' => 'Block Status', 'rules' => 'required'],
                'BLK_RESER_TYPE' => ['label' => 'Reservation Type', 'rules' => 'required'],
                'BLK_MARKET' => ['label' => 'Market', 'rules' => 'required'],
                'BLK_SOURCE' => ['label' => 'Source', 'rules' => 'required'],
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            if(!empty($sysid)){
                $data = ["BLK_COMP" => $this->request->getPost("BLK_COMP"),
                    "BLK_AGENT" => $this->request->getPost("BLK_AGENT"),
                    "BLK_GROUP" => $this->request->getPost("BLK_GROUP"),
                    "BLK_NAME" => $this->request->getPost("BLK_NAME"),
                    "BLK_START_DT" => $this->request->getPost("BLK_START_DT"),
                    "BLK_NIGHT" => $this->request->getPost("BLK_NIGHT"),
                    "BLK_END_DT" => $this->request->getPost("BLK_END_DT"),
                    "BLK_CODE" => $this->request->getPost("BLK_CODE"),
                    "BLK_STATUS" => $this->request->getPost("BLK_STATUS"),
                    "BLK_RESER_TYPE" => $this->request->getPost("BLK_RESER_TYPE"),
                    "BLK_MARKET" => $this->request->getPost("BLK_MARKET"),
                    "BLK_SOURCE" => $this->request->getPost("BLK_SOURCE"),
                    "BLK_ELASTIC" => $this->request->getPost("BLK_ELASTIC"),
                    "BLK_CUTOFF_DAYS" => $this->request->getPost("BLK_CUTOFF_DAYS"),
                    "BLK_CUTOFF_DT" => $this->request->getPost("BLK_CUTOFF_DT"),
                    "BLK_RESER_METHOD" => $this->request->getPost("BLK_RESER_METHOD"),
                    "BLK_UPDATE_UID" => session()->get('USR_ID'),
                    "BLK_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_BLOCK')->where('BLK_ID', $sysid)->update($data); 
            }else{
                $data = ["BLK_COMP" => $this->request->getPost("BLK_COMP"),
                    "BLK_AGENT" => $this->request->getPost("BLK_AGENT"),
                    "BLK_GROUP" => $this->request->getPost("BLK_GROUP"),
                    "BLK_NAME" => $this->request->getPost("BLK_NAME"),
                    "BLK_START_DT" => $this->request->getPost("BLK_START_DT"),
                    "BLK_NIGHT" => $this->request->getPost("BLK_NIGHT"),
                    "BLK_END_DT" => $this->request->getPost("BLK_END_DT"),
                    "BLK_CODE" => $this->request->getPost("BLK_CODE"),
                    "BLK_STATUS" => $this->request->getPost("BLK_STATUS"),
                    "BLK_RESER_TYPE" => $this->request->getPost("BLK_RESER_TYPE"),
                    "BLK_MARKET" => $this->request->getPost("BLK_MARKET"),
                    "BLK_SOURCE" => $this->request->getPost("BLK_SOURCE"),
                    "BLK_ELASTIC" => $this->request->getPost("BLK_ELASTIC"),
                    "BLK_CUTOFF_DAYS" => $this->request->getPost("BLK_CUTOFF_DAYS"),
                    "BLK_CUTOFF_DT" => $this->request->getPost("BLK_CUTOFF_DT"),
                    "BLK_RESER_METHOD" => $this->request->getPost("BLK_RESER_METHOD"),
                    "BLK_CREATE_UID" => session()->get('USR_ID'),
                    "BLK_CREATE_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_BLOCK')->insert($data); 
            }
            if($return){
                // if(empty($sysid)){
                //     $fullname = $this->request->getPost("GRP_NAME");
                //     $id = $this->Db->insertID();
                //     $response = array("ID"=>$id,"FULLNAME"=>$fullname);
                // }else{
                //     $response ='';
                // }
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function editBlock(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT BLK_ID,
                BLK_COMP,(SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE BLK_COMP=COM_ID)BLK_COMP_DESC,
                BLK_AGENT,(SELECT AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE BLK_AGENT=AGN_ID)BLK_AGENT_DESC,
                BLK_GROUP,(SELECT GRP_NAME FROM FLXY_GROUP WHERE BLK_GROUP=GRP_ID)BLK_GROUP_DESC,
                BLK_NAME,BLK_START_DT,BLK_NIGHT,BLK_CODE,BLK_END_DT,BLK_STATUS,BLK_RESER_TYPE,BLK_MARKET,BLK_SOURCE,
                BLK_ELASTIC,BLK_CUTOFF_DAYS,BLK_CUTOFF_DT,BLK_RESER_METHOD 
                FROM FLXY_BLOCK WHERE BLK_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function deleteBlock(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_BLOCK')->delete(['BLK_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function insertBlockReservation()
    {
        $addResvFields = $this->request->getPost('ADD_RESV');
        //echo "<pre>"; print_r($this->request->getPost()); echo "</pre>"; exit;

        $validate = $this->validate([
            'ADD_RESV.*.RESV_NAME' => ['label' => 'Guest Name', 'rules' => 'required'],
            'ADD_RESV.*.RESV_ARRIVAL_DT' => ['label' => 'Arrival Date', 'rules' => 'required|valid_date[j-M-Y]', 'errors' => ['required' => 'Please enter a valid Arrival Date']],
            'ADD_RESV.*.RESV_DEPARTURE' => ['label' => 'Departure Date', 'rules' => 'required|valid_date[j-M-Y]', 'errors' => ['required' => 'Please enter a valid Departure Date']],
            'ADD_RESV.*.RESV_RM_TYPE' => ['label' => 'Room Type', 'rules' => 'required'],
            'ADD_RESV.*.RESV_NO_F_ROOM' => ['label' => 'No of Rooms', 'rules' => 'required|is_natural_no_zero', 'errors' => ['required' => 'Please enter the number of rooms']],
            'ADD_RESV.*.RESV_RATE_CODE' => ['label' => 'Rate Code', 'rules' => 'required', 'errors' => ['required' => 'Please select a Rate Code or try another room type']],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result["SUCCESS"] = "-402";
            $result[]["ERROR"] = $validate;
            $result = $this->responseJson("-402", $validate);
            echo json_encode($result);
            exit;
        }

        $tot_new_resv = 0;
        $all_new_resv = [];

        foreach($addResvFields as $addResvField)
        {
            //echo "<pre>"; print_r($addResvField); echo "</pre>";   
            $no_of_resv = $addResvField['RESV_NO_F_ROOM'] > $addResvField['MAX_NO_F_ROOM'] ? $addResvField['MAX_NO_F_ROOM'] : $addResvField['RESV_NO_F_ROOM'];

            // Split no of rooms into separate reservations
            while($no_of_resv > 0)
            {
                $data = [   "RESV_ARRIVAL_DT" => $addResvField["RESV_ARRIVAL_DT"],
                            "RESV_DEPARTURE" => $addResvField["RESV_DEPARTURE"],
                            "RESV_NIGHT" => abs(round(  (strtotime($addResvField["RESV_DEPARTURE"]) - 
                                                         strtotime($addResvField["RESV_ARRIVAL_DT"])) / (24 * 60 * 60))),
                            "RESV_ADULTS" => "1",
                            "RESV_CHILDREN" => "0",
                            "RESV_NO_F_ROOM" => "1",
                            "RESV_NAME" => $addResvField["RESV_NAME"],
                            "RESV_COMPANY" => $this->request->getPost('main_BLK_COMPANY'),
                            "RESV_AGENT" => $this->request->getPost('main_BLK_AGENT'),
                            "RESV_BLOCK" => $this->request->getPost('main_BLK_ID'),
                            "RESV_DAY_USE" => $addResvField["RESV_ARRIVAL_DT"] == $addResvField["RESV_DEPARTURE"] ? 'Y' : 'N',
                            "RESV_RATE_CODE" => $addResvField["RESV_RATE_CODE"],
                            "RESV_ROOM_CLASS" => $addResvField["RESV_ROOM_CLASS"],
                            "RESV_FEATURE" => $addResvField["RESV_FEATURE"],
                            "RESV_STATUS" => "Due Pre Check-In",
                            "RESV_RM_TYPE" => $addResvField["RESV_RM_TYPE"],
                            "RESV_RM_TYPE_ID" => $addResvField["RESV_RM_TYPE_ID"],
                            "RESV_RATE" => $addResvField["RESV_RATE"],
                            "RESV_MARKET" => $this->request->getPost('main_BLK_MARKET'),
                            "RESV_SOURCE" => $this->request->getPost('main_BLK_SOURCE'),
                            "RESV_NO_POST" => "N",
                            "RESV_CREATE_UID" => session()->get('USR_ID'),
                            "RESV_CREATE_DT" => date("d-M-Y")
                        ];

                $all_new_resv[] = $data;

                $return = $this->Db->table('FLXY_RESERVATION')->insert($data); 
                $sysid = $this->Db->insertID();
                
                $log_action_desc = "";
                foreach($data as $dkey => $dvalue)
                {
                    // Save changes in log description if data is not empty
                    if(!empty(trim($dvalue)))
                    {
                        $log_action_desc .= "<b>".str_replace('RESV_', '', $dkey) . ": </b> '" . $dvalue ."'<br/>";
                    }
                }

                $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update(array('RESV_NO' => 'RES'.$sysid));
                addActivityLog(1, 10, $sysid, $log_action_desc);
                //$this->triggerReservationEmail($sysid,'');

                $no_of_resv--; 
                $tot_new_resv++;
            }
        }

        $result = $this->responseJson("1", "0", $all_new_resv, $tot_new_resv);
        echo json_encode($result);
    }

    public function room(){
        $data['title'] = getMethodName();
        return view('Reservation/RoomView', $data);
    }

    public function RoomView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM';
        $columns = 'RM_ID,RM_NO,RM_DESC,RM_TYPE,RM_FEATURE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function insertRoom(){
        try{
            $validate = $this->validate([
                'RM_NO' => ['label' => 'Room Number', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RM_ID");
            $RM_FEATURE = $this->request->getPost("RM_FEATURE");
            $RM_FEATURE = implode(",",$RM_FEATURE);
            if(!empty($sysid)){
                $data = ["RM_NO" => $this->request->getPost("RM_NO"),
                    "RM_CLASS" => $this->request->getPost("RM_CLASS"),
                    "RM_DESC" => $this->request->getPost("RM_DESC"),
                    "RM_TYPE" => $this->request->getPost("RM_TYPE"),
                    "RM_TYPE_REF_ID" => $this->request->getPost("RM_TYPE_REF_ID"),
                    "RM_FEATURE" => $RM_FEATURE,
                    "RM_PUBLIC_RATE_CODE" => $this->request->getPost("RM_PUBLIC_RATE_CODE"),
                    "RM_PUBLIC_RATE_AMOUNT" => $this->request->getPost("RM_PUBLIC_RATE_AMOUNT"),
                    "RM_MAX_OCCUPANCY" => $this->request->getPost("RM_MAX_OCCUPANCY"),
                    "RM_DISP_SEQ" => $this->request->getPost("RM_DISP_SEQ"),
                    "RM_FLOOR_PREFERN" => $this->request->getPost("RM_FLOOR_PREFERN"),
                    "RM_SMOKING_PREFERN" => $this->request->getPost("RM_SMOKING_PREFERN"),
                    "RM_PHONE_NO" => $this->request->getPost("RM_PHONE_NO"),
                    "RM_SQUARE_UNITS" => $this->request->getPost("RM_SQUARE_UNITS"),
                    "RM_MEASUREMENT" => $this->request->getPost("RM_MEASUREMENT"),
                    "RM_HOUSKP_DY_SECTION" => $this->request->getPost("RM_HOUSKP_DY_SECTION"),
                    "RM_HOUSKP_EV_SECTION" => $this->request->getPost("RM_HOUSKP_EV_SECTION"),
                    "RM_STAYOVER_CR" => $this->request->getPost("RM_STAYOVER_CR"),
                    "RM_DEPARTURE_CR" => $this->request->getPost("RM_DEPARTURE_CR"),
                    "RM_UPDATED_UID" => session()->get('USR_ID'),
                    "RM_UPDATED_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_ROOM')->where('RM_ID', $sysid)->update($data); 

           
            }else{
                $data = ["RM_NO" => $this->request->getPost("RM_NO"),
                    "RM_CLASS" => $this->request->getPost("RM_CLASS"),
                    "RM_DESC" => $this->request->getPost("RM_DESC"),
                    "RM_TYPE" => $this->request->getPost("RM_TYPE"),
                    "RM_TYPE_REF_ID" => $this->request->getPost("RM_TYPE_REF_ID"),
                    "RM_FEATURE" => $RM_FEATURE,
                    "RM_PUBLIC_RATE_CODE" => $this->request->getPost("RM_PUBLIC_RATE_CODE"),
                    "RM_PUBLIC_RATE_AMOUNT" => $this->request->getPost("RM_PUBLIC_RATE_AMOUNT"),
                    "RM_MAX_OCCUPANCY" => $this->request->getPost("RM_MAX_OCCUPANCY"),
                    "RM_DISP_SEQ" => $this->request->getPost("RM_DISP_SEQ"),
                    "RM_FLOOR_PREFERN" => $this->request->getPost("RM_FLOOR_PREFERN"),
                    "RM_SMOKING_PREFERN" => $this->request->getPost("RM_SMOKING_PREFERN"),
                    "RM_PHONE_NO" => $this->request->getPost("RM_PHONE_NO"),
                    "RM_SQUARE_UNITS" => $this->request->getPost("RM_SQUARE_UNITS"),
                    "RM_MEASUREMENT" => $this->request->getPost("RM_MEASUREMENT"),
                    "RM_HOUSKP_DY_SECTION" => $this->request->getPost("RM_HOUSKP_DY_SECTION"),
                    "RM_HOUSKP_EV_SECTION" => $this->request->getPost("RM_HOUSKP_EV_SECTION"),
                    "RM_STAYOVER_CR" => $this->request->getPost("RM_STAYOVER_CR"),
                    "RM_DEPARTURE_CR" => $this->request->getPost("RM_DEPARTURE_CR"),
                    "RM_CREATED_UID" => session()->get('USR_ID'),
                    "RM_CREATED_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_ROOM')->insert($data); 
                /////////// Insert Room Log ///////////
                $RMID = $this->Db->insertID();
                $logdata = ["RM_STAT_ROOM_ID" =>  $RMID ,"RM_STAT_ROOM_STATUS"=>"2", "RM_STAT_UPDATED"=> date("Y-m-d H:i:s" )];
                $this->Db->table('FLXY_ROOM_STATUS_LOG')->insert($logdata);

                

            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function editRoom(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RM_ID,RM_NO,RM_DESC,RM_CLASS,RM_TYPE,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RM_TYPE)RM_TYPE_DESC,
        (SELECT RM_FT_DESC FROM FLXY_ROOM_FEATURE WHERE RM_FT_CODE=RM_FEATURE)RM_FEATURE_DESC,
        RM_FEATURE,RM_PUBLIC_RATE_CODE,RM_PUBLIC_RATE_AMOUNT,RM_MAX_OCCUPANCY,RM_DISP_SEQ,RM_FLOOR_PREFERN,RM_SMOKING_PREFERN,RM_PHONE_NO,RM_SQUARE_UNITS,RM_MEASUREMENT,RM_HOUSKP_DY_SECTION,RM_HOUSKP_EV_SECTION,RM_STAYOVER_CR,RM_DEPARTURE_CR FROM FLXY_ROOM WHERE RM_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function roomList(){
        $search = $this->request->getPost("search");
        $room_type = $this->request->getPost("room_type");
        $room_ids = $this->request->getPost("room_ids");

        $sql = "SELECT RM_ID, RM_NO, RM_DESC FROM FLXY_ROOM WHERE 1 = 1"; 
        
        if(!empty($search))
            $sql .= " AND RM_NO like '%$search%'";

        if(!empty($room_type))
            $sql .= " AND RM_TYPE_REF_ID IN (".$room_type.")";

        if(!empty($room_ids))
            $sql .= " AND RM_ID IN (".$room_ids.")";

        $response = $this->Db->query($sql)->getResultArray();

        if($response != NULL)
        {
            $option='<option value="">Select Room</option>';
            foreach($response as $row){
                $option.= '<option value="'.$row['RM_NO'].'" data-room-id="'.$row['RM_ID'].'">'.$row['RM_NO'].'</option>';
            }
        }
        else
            $option='<option value="">No Rooms</option>';

        echo $option;
    }

    public function deleteRoom(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_ROOM')->delete(['RM_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function roomClass(){
        $data['title'] = getMethodName();
        return view('Reservation/RoomClassView', $data);
    }

    public function RoomClassView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_CLASS';
        $columns = 'RM_CL_ID,RM_CL_CODE,RM_CL_DESC,RM_CL_TOTAL_ROOM';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRoomClass(){
        try{
            // $data = $this->request->getRawInput();
            echo $test = $this->request->getPost("RM_CL_CODE");
            $validate = $this->validate([
                'RM_CL_CODE' => ['label' => 'Room Class Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RM_CL_ID");
            if(!empty($sysid)){
                $data = ["RM_CL_CODE" => $this->request->getPost("RM_CL_CODE"),
                    "RM_CL_DESC" => $this->request->getPost("RM_CL_DESC"),
                    "RM_CL_DISPLY_SEQ" => $this->request->getPost("RM_CL_DISPLY_SEQ"),
                    "RM_CL_TOTAL_ROOM" => $this->request->getPost("RM_CL_TOTAL_ROOM")
                 ];
            $return = $this->Db->table('FLXY_ROOM_CLASS')->where('RM_CL_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_CL_CODE" => $this->request->getPost("RM_CL_CODE"),
                    "RM_CL_DESC" => $this->request->getPost("RM_CL_DESC"),
                    "RM_CL_DISPLY_SEQ" => $this->request->getPost("RM_CL_DISPLY_SEQ"),
                    "RM_CL_TOTAL_ROOM" => $this->request->getPost("RM_CL_TOTAL_ROOM")
                 ];
                $return = $this->Db->table('FLXY_ROOM_CLASS')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editRoomClass(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RM_CL_ID,RM_CL_CODE,RM_CL_DESC,RM_CL_DISPLY_SEQ,RM_CL_TOTAL_ROOM FROM FLXY_ROOM_CLASS WHERE RM_CL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomClass(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_ROOM_CLASS')->delete(['RM_CL_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function roomType(){
        $data['title'] = getMethodName();
        return view('Reservation/RoomTypeView', $data);
    }

    public function RoomTypeView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_TYPE';
        $columns = 'RM_TY_ID,RM_TY_CODE,RM_TY_DESC,RM_TY_FEATURE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRoomType(){
        try{
            $validate = $this->validate([
                'RM_TY_CODE' => ['label' => 'Room Type', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RM_TY_ID");
            $RM_TY_FEATURE = $this->request->getPost("RM_TY_FEATURE");
            $RM_TY_FEATURE = implode(",",$RM_TY_FEATURE);
            if(!empty($sysid)){
                $data = ["RM_TY_ROOM_CLASS" => $this->request->getPost("RM_TY_ROOM_CLASS"),
                    "RM_CL_ID" => $this->request->getPost("RM_CL_ID"),
                    "RM_TY_CODE" => $this->request->getPost("RM_TY_CODE"),
                    "RM_TY_DESC" => $this->request->getPost("RM_TY_DESC"),
                    "RM_TY_FEATURE" => $RM_TY_FEATURE,
                    "RM_TY_TOTAL_ROOM" => $this->request->getPost("RM_TY_TOTAL_ROOM"),
                    "RM_TY_DISP_SEQ" => $this->request->getPost("RM_TY_DISP_SEQ"),
                    "RM_TY_PUBLIC_RATE_CODE" => $this->request->getPost("RM_TY_PUBLIC_RATE_CODE"),
                    "RM_TY_DEFUL_OCCUPANCY" => $this->request->getPost("RM_TY_DEFUL_OCCUPANCY"),
                    "RM_TY_MAX_OCCUPANCY" => $this->request->getPost("RM_TY_MAX_OCCUPANCY"),
                    "RM_TY_MAX_ADULTS" => $this->request->getPost("RM_TY_MAX_ADULTS"),
                    "RM_TY_MAX_CHILDREN" => $this->request->getPost("RM_TY_MAX_CHILDREN"),
                    "RM_TY_PSEUDO_RM" => $this->request->getPost("RM_TY_PSEUDO_RM"),
                    "RM_TY_HOUSEKEEPING" => $this->request->getPost("RM_TY_HOUSEKEEPING"),
                    "RM_TY_MIN_OCCUPANCY" => $this->request->getPost("RM_TY_MIN_OCCUPANCY"),
                    "RM_TY_SEND_T_INTERF" => $this->request->getPost("RM_TY_SEND_T_INTERF"),
                    "RM_TY_PUBLIC_RATE_AMT" => $this->request->getPost("RM_TY_PUBLIC_RATE_AMT"),
                    "RM_TY_ACTIVE_DT" => null,
                    "RM_TY_UPDATED_DT" => date("d-M-Y"),
                    "RM_TY_UPDATED_UID" => session()->get('USR_ID'),
                 ];
            $return = $this->Db->table('FLXY_ROOM_TYPE')->where('RM_TY_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_TY_ROOM_CLASS" => $this->request->getPost("RM_TY_ROOM_CLASS"),
                    "RM_CL_ID" => $this->request->getPost("RM_CL_ID"),
                    "RM_TY_CODE" => $this->request->getPost("RM_TY_CODE"),
                    "RM_TY_DESC" => $this->request->getPost("RM_TY_DESC"),
                    "RM_TY_FEATURE" => $RM_TY_FEATURE,
                    "RM_TY_TOTAL_ROOM" => $this->request->getPost("RM_TY_TOTAL_ROOM"),
                    "RM_TY_DISP_SEQ" => $this->request->getPost("RM_TY_DISP_SEQ"),
                    "RM_TY_PUBLIC_RATE_CODE" => $this->request->getPost("RM_TY_PUBLIC_RATE_CODE"),
                    "RM_TY_DEFUL_OCCUPANCY" => $this->request->getPost("RM_TY_DEFUL_OCCUPANCY"),
                    "RM_TY_MAX_OCCUPANCY" => $this->request->getPost("RM_TY_MAX_OCCUPANCY"),
                    "RM_TY_MAX_ADULTS" => $this->request->getPost("RM_TY_MAX_ADULTS"),
                    "RM_TY_MAX_CHILDREN" => $this->request->getPost("RM_TY_MAX_CHILDREN"),
                    "RM_TY_PSEUDO_RM" => $this->request->getPost("RM_TY_PSEUDO_RM"),
                    "RM_TY_HOUSEKEEPING" => $this->request->getPost("RM_TY_HOUSEKEEPING"),
                    "RM_TY_MIN_OCCUPANCY" => $this->request->getPost("RM_TY_MIN_OCCUPANCY"),
                    "RM_TY_SEND_T_INTERF" => $this->request->getPost("RM_TY_SEND_T_INTERF"),
                    "RM_TY_PUBLIC_RATE_AMT" => $this->request->getPost("RM_TY_PUBLIC_RATE_AMT"),
                    "RM_TY_ACTIVE_DT" => null,
                    "RM_TY_CREATE_DT" => date("d-M-Y"),
                    "RM_TY_CREATE_UID" => session()->get('USR_ID'),
                 ];
                $return = $this->Db->table('FLXY_ROOM_TYPE')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function roomClassList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_CL_CODE,RM_CL_DESC,RM_CL_ID FROM FLXY_ROOM_CLASS WHERE RM_CL_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select RoomClass</option>';
        foreach($response as $row){
            $option.= '<option data-room-class-id="'.trim($row['RM_CL_ID']).'" value="'.trim($row['RM_CL_CODE']).'">'.$row['RM_CL_DESC'].'</option>';
        }
        echo $option;
    }

    function getSupportingRoomClassLov(){
        $sql = 'SELECT RT_CD_CODE CODE,RT_CD_DESC DESCS FROM FLXY_RATE_CODE';
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RM_FT_CODE CODE,RM_FT_DESC DESCS FROM FLXY_ROOM_FEATURE';
        $respon2 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2];
        echo json_encode($data);
    }

    function getSupportingRoomLov(){
        $sql = 'SELECT RT_CD_CODE CODE,RT_CD_DESC DESCS FROM FLXY_RATE_CODE';
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RM_FL_CODE CODE,RM_FL_DESC DESCS FROM FLXY_ROOM_FLOOR';
        $respon2 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='SMK_PREF'";
        $respon3 = $this->Db->query($sql)->getResultArray();
        $sql="SELECT RM_FT_CODE CODE,RM_FT_DESC DESCS FROM FLXY_ROOM_FEATURE";
        $respon4 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2,$respon3,$respon4];
        echo json_encode($data);
    }

    function getInitializeListReserv(){
        $sql = "SELECT RESV_TY_ID CODE,RESV_TY_DESC DESCS FROM FLXY_RESERVATION_TYPE";
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT MK_CD_CODE CODE,MK_CD_DESC DESCS FROM FLXY_MARKET_CODE";
        $respon2 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT SOR_CODE CODE,SOR_DESC DESCS FROM FLXY_SOURCE";
        $respon3 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='ORIGIN'";
        $respon4 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT PYM_CODE CODE,PYM_DESC DESCS FROM FLXY_PAYMENT";
        $respon5 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5];
        echo json_encode($data);
    }

    function initalConfigLovSource(){
        $sql = "SELECT SOR_GR_CODE CODE,SOR_GR_DESC DESCS FROM FLXY_SOURCE_GROUP";
        $respon1 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1];
        echo json_encode($data);
    }


    public function editRoomType(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RM_TY_ID,RM_TY_ROOM_CLASS,(SELECT RM_CL_DESC FROM FLXY_ROOM_CLASS WHERE RM_CL_CODE=RM_TY_ROOM_CLASS)RM_TY_ROOM_CLASS_DESC,RM_TY_CODE,RM_CL_ID,RM_TY_DESC,RM_TY_FEATURE,RM_TY_TOTAL_ROOM,RM_TY_DISP_SEQ,RM_TY_PUBLIC_RATE_CODE,RM_TY_DEFUL_OCCUPANCY,RM_TY_MAX_OCCUPANCY,RM_TY_MAX_ADULTS,RM_TY_MAX_CHILDREN,RM_TY_PSEUDO_RM,RM_TY_HOUSEKEEPING,RM_TY_MIN_OCCUPANCY,RM_TY_SEND_T_INTERF,RM_TY_PUBLIC_RATE_AMT,RM_TY_ACTIVE_DT FROM FLXY_ROOM_TYPE WHERE RM_TY_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function roomTypeList(){
        $search = $this->request->getPost("search");
        $room_type = $this->request->getPost("room_type");
        $room_class_id = $this->request->getPost("room_class_id");

        $sql = "SELECT RM_TY_ID,RM_TY_CODE,RM_TY_DESC,RM_TY_ROOM_CLASS,RM_TY_FEATURE 
                FROM FLXY_ROOM_TYPE WHERE 1 = 1";
        
        if(!empty($search))
            $sql .= " AND RM_TY_DESC like '%$search%'";

        if(!empty($room_class_id))
            $sql .= " AND RM_CL_ID IN (".$room_class_id.")";

        $response = $this->Db->query($sql)->getResultArray();
        $option='';
        foreach($response as $row){
            $option.= '<option data-room-type-id="'.trim($row['RM_TY_ID']).'" data-feture="'.trim($row['RM_TY_FEATURE']).'" data-desc="'.trim($row['RM_TY_DESC']).'" data-rmclass="'.trim($row['RM_TY_ROOM_CLASS']).'" value="'.$row['RM_TY_CODE'].'"'.set_select('SEARCH_ROOM_TYPE', $row['RM_TY_ID'], False).'>'.$row['RM_TY_DESC'].'</option>';
        }
        echo $option;
    }

    public function featureList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT RM_FT_ID,RM_FT_CODE,RM_FT_DESC FROM FLXY_ROOM_FEATURE WHERE 1 = 1"; 
        
        if(!empty($search))
            $sql .= " AND RM_FT_DESC like '%$search%'";

        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Feature</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_FT_CODE'].'">'.$row['RM_FT_DESC'].'</option>';
        }
        echo $option;
    }

    public function houseKeepSecionList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT SC_FL_CODE,SC_FL_DESC,SC_FL_ID FROM FLXY_SECTION WHERE SC_FL_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Section</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['SC_FL_CODE'].'">'.$row['SC_FL_DESC'].'</option>';
        }
        echo $option;
    }

    public function deleteRoomType(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_ROOM_TYPE')->delete(['RM_TY_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function roomFloor(){
        $data['title'] = getMethodName();
        return view('Reservation/RoomFloorView', $data);
    }

    public function RoomFloorView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_FLOOR';
        $columns = 'RM_FL_ID,RM_FL_CODE,RM_FL_DESC,RM_FL_FEATURE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRoomFloor(){
        try{
            $validate = $this->validate([
                'RM_FL_CODE' => ['label' => 'Room Floor', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RM_FL_ID");
            if(!empty($sysid)){
                $data = ["RM_FL_CODE" => $this->request->getPost("RM_FL_CODE"),
                    "RM_FL_DESC" => $this->request->getPost("RM_FL_DESC"),
                    "RM_FL_FEATURE" => ""
                 ];
            $return = $this->Db->table('FLXY_ROOM_FLOOR')->where('RM_FL_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_FL_CODE" => $this->request->getPost("RM_FL_CODE"),
                    "RM_FL_DESC" => $this->request->getPost("RM_FL_DESC"),
                    "RM_FL_FEATURE" => ""
                 ];
                $return = $this->Db->table('FLXY_ROOM_FLOOR')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editRoomFloor(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RM_FL_ID,RM_FL_CODE,RM_FL_DESC,RM_FL_FEATURE FROM FLXY_ROOM_FLOOR WHERE RM_FL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomFloor(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_ROOM_FLOOR')->delete(['RM_FL_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function roomFeature(){
        $data['title'] = getMethodName();
        return view('Reservation/RoomFeatureView', $data);
    }

    public function RoomFeatureView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_FEATURE';
        $columns = 'RM_FT_ID,RM_FT_CODE,RM_FT_DESC,RM_FT_FEATURE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRoomFeature(){
        try{
            $validate = $this->validate([
                'RM_FT_CODE' => ['label' => 'Room Feature', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RM_FT_ID");
            if(!empty($sysid)){
                $data = ["RM_FT_CODE" => $this->request->getPost("RM_FT_CODE"),
                    "RM_FT_DESC" => $this->request->getPost("RM_FT_DESC"),
                    "RM_FT_FEATURE" => $this->request->getPost("RM_FT_FEATURE")
                 ];
            $return = $this->Db->table('FLXY_ROOM_FEATURE')->where('RM_FT_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_FT_CODE" => $this->request->getPost("RM_FT_CODE"),
                    "RM_FT_DESC" => $this->request->getPost("RM_FT_DESC"),
                    "RM_FT_FEATURE" => $this->request->getPost("RM_FT_FEATURE")
                 ];
                $return = $this->Db->table('FLXY_ROOM_FEATURE')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editRoomFeature(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RM_FT_ID,RM_FT_CODE,RM_FT_DESC,RM_FT_FEATURE FROM FLXY_ROOM_FEATURE WHERE RM_FT_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomFeature(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_ROOM_FEATURE')->delete(['RM_FT_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function section(){
        $data['title'] = getMethodName();
        return view('Reservation/SectionView', $data);
    }

    public function SectionView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SECTION';
        $columns = 'SC_FL_ID,SC_FL_CODE,SC_FL_DESC,SC_FL_TARGET_CREDIT,SC_FL_DISPLAY_SEQ,SC_FL_ACTIVE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertSection(){
        try{
            $validate = $this->validate([
                'SC_FL_CODE' => ['label' => 'Section Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("SC_FL_ID");
            if(!empty($sysid)){
                $data = ["SC_FL_CODE" => $this->request->getPost("SC_FL_CODE"),
                    "SC_FL_DESC" => $this->request->getPost("SC_FL_DESC"),
                    "SC_FL_TARGET_CREDIT" => $this->request->getPost("SC_FL_TARGET_CREDIT"),
                    "SC_FL_DISPLAY_SEQ" => $this->request->getPost("SC_FL_DISPLAY_SEQ"),
                    "SC_FL_ACTIVE" => null
                 ];
            $return = $this->Db->table('FLXY_SECTION')->where('SC_FL_ID', $sysid)->update($data); 
            }else{
                $data = ["SC_FL_CODE" => $this->request->getPost("SC_FL_CODE"),
                    "SC_FL_DESC" => $this->request->getPost("SC_FL_DESC"),
                    "SC_FL_TARGET_CREDIT" => $this->request->getPost("SC_FL_TARGET_CREDIT"),
                    "SC_FL_DISPLAY_SEQ" => $this->request->getPost("SC_FL_DISPLAY_SEQ"),
                    "SC_FL_ACTIVE" => null
                 ];
                $return = $this->Db->table('FLXY_SECTION')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editSection(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT SC_FL_ID,SC_FL_CODE,SC_FL_DESC,SC_FL_TARGET_CREDIT,SC_FL_DISPLAY_SEQ,SC_FL_ACTIVE FROM FLXY_SECTION WHERE SC_FL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSection(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SECTION')->delete(['SC_FL_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    
    public function source(){
        $data['title'] = getMethodName();
        return view('Reservation/SourceView', $data);
    }

    public function SourceView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SOURCE';
        $columns = 'SOR_ID,SOR_CODE,SOR_DESC,SOR_GROUP,SOR_DIS_SEQ,SOR_ACTIVE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertSource(){
        try{
            $validate = $this->validate([
                'SOR_CODE' => ['label' => 'Source Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("SOR_ID");
            if(!empty($sysid)){
                $data = ["SOR_CODE" => $this->request->getPost("SOR_CODE"),
                    "SOR_DESC" => $this->request->getPost("SOR_DESC"),
                    "SOR_GROUP" => $this->request->getPost("SOR_GROUP"),
                    "SOR_DIS_SEQ" => $this->request->getPost("SOR_DIS_SEQ"),
                    "SOR_ACTIVE" => 'Y'
                 ];
            $return = $this->Db->table('FLXY_SOURCE')->where('SOR_ID', $sysid)->update($data); 
            }else{
                $data = ["SOR_CODE" => $this->request->getPost("SOR_CODE"),
                    "SOR_DESC" => $this->request->getPost("SOR_DESC"),
                    "SOR_GROUP" => $this->request->getPost("SOR_GROUP"),
                    "SOR_DIS_SEQ" => $this->request->getPost("SOR_DIS_SEQ"),
                    "SOR_ACTIVE" => 'Y'
                 ];
                $return = $this->Db->table('FLXY_SOURCE')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editSource(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT SOR_ID,SOR_CODE,SOR_DESC,SOR_GROUP,SOR_DIS_SEQ,SOR_ACTIVE FROM FLXY_SOURCE WHERE SOR_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSource(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SOURCE')->delete(['SOR_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function sourceGroup(){
        $data['title'] = getMethodName();
        return view('Reservation/SourceGroupView', $data);
    }

    public function SourceGroupView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SOURCE_GROUP';
        $columns = 'SOR_GR_ID,SOR_GR_CODE,SOR_GR_DESC,SOR_GR_DIS_SEQ,SOR_GR_ACTIVE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertSourceGroup(){
        try{
            $validate = $this->validate([
                'SOR_GR_CODE' => ['label' => 'Source Group Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("SOR_GR_ID");
            if(!empty($sysid)){
                $data = ["SOR_GR_CODE" => $this->request->getPost("SOR_GR_CODE"),
                    "SOR_GR_DESC" => $this->request->getPost("SOR_GR_DESC"),
                    "SOR_GR_DIS_SEQ" => $this->request->getPost("SOR_GR_DIS_SEQ"),
                    "SOR_GR_ACTIVE" => 'Y'
                 ];
            $return = $this->Db->table('FLXY_SOURCE_GROUP')->where('SOR_GR_ID', $sysid)->update($data); 
            }else{
                $data = ["SOR_GR_CODE" => $this->request->getPost("SOR_GR_CODE"),
                    "SOR_GR_DESC" => $this->request->getPost("SOR_GR_DESC"),
                    "SOR_GR_DIS_SEQ" => $this->request->getPost("SOR_GR_DIS_SEQ"),
                    "SOR_GR_ACTIVE" => 'Y'
                 ];
                $return = $this->Db->table('FLXY_SOURCE_GROUP')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editSourceGroup(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT SOR_GR_ID,SOR_GR_CODE,SOR_GR_DESC,SOR_GR_DIS_SEQ,SOR_GR_ACTIVE FROM FLXY_SOURCE_GROUP WHERE SOR_GR_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSourceGroup(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SOURCE_GROUP')->delete(['SOR_GR_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function special(){
        $data['title'] = getMethodName();
        return view('Reservation/SpecialView', $data);
    }

    public function SpecialView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SPECIAL_CODE';
        $columns = 'SPC_ID,SPC_CODE,SPC_DESC,SPC_SEQ';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertSpecial(){
        try{
            $validate = $this->validate([
                'SPC_CODE' => ['label' => 'Special Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("SPC_ID");
            if(!empty($sysid)){
                $data = ["SPC_CODE" => $this->request->getPost("SPC_CODE"),
                    "SPC_DESC" => $this->request->getPost("SPC_DESC"),
                    "SPC_SEQ" => $this->request->getPost("SPC_SEQ")
                 ];
            $return = $this->Db->table('FLXY_SPECIAL_CODE')->where('SPC_ID', $sysid)->update($data); 
            }else{
                $data = ["SPC_CODE" => $this->request->getPost("SPC_CODE"),
                    "SPC_DESC" => $this->request->getPost("SPC_DESC"),
                    "SPC_SEQ" => $this->request->getPost("SPC_SEQ")
                 ];
                $return = $this->Db->table('FLXY_SPECIAL_CODE')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function specialsList(){
        $search = $this->request->getPost("search");
        $sql = "SELECT * FROM FLXY_SPECIAL_CODE ORDER BY SPC_SEQ ASC";
        $response = $this->Db->query($sql)->getResultArray();
        $option='';
        foreach($response as $row){
            $option.= '<option data-special-id="'.trim($row['SPC_ID']).'" value="'.$row['SPC_CODE'].'">'.$row['SPC_DESC'].'</option>';
        }
        echo $option;
    }

    public function editSpecial(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT SPC_ID,SPC_CODE,SPC_DESC,SPC_SEQ FROM FLXY_SPECIAL_CODE WHERE SPC_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSpecial(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SPECIAL_CODE')->delete(['SPC_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function reservationType(){
        $data['title'] = getMethodName();
        return view('Reservation/ReservationType', $data);
    }

    public function ReservationTypeView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RESERVATION_TYPE';
        $columns = 'RESV_TY_ID,RESV_TY_DESC,RESV_TY_SEQ,RESV_TY_CODE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertReservationType(){
        try{
            $validate = $this->validate([
                'RESV_TY_CODE' => ['label' => 'Reservation Type', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("RESV_TY_ID");
            if(!empty($sysid)){
                $data = ["RESV_TY_CODE" => $this->request->getPost("RESV_TY_CODE"),
                    "RESV_TY_DESC" => $this->request->getPost("RESV_TY_DESC"),
                    "RESV_TY_SEQ" => $this->request->getPost("RESV_TY_SEQ")
                ];
            $return = $this->Db->table('FLXY_RESERVATION_TYPE')->where('RESV_TY_ID', $sysid)->update($data); 
            }else{
                $data = ["RESV_TY_CODE" => $this->request->getPost("RESV_TY_CODE"),
                    "RESV_TY_DESC" => $this->request->getPost("RESV_TY_DESC"),
                    "RESV_TY_SEQ" => $this->request->getPost("RESV_TY_SEQ")
                ];
                $return = $this->Db->table('FLXY_RESERVATION_TYPE')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editReservationType(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT RESV_TY_ID,RESV_TY_DESC,RESV_TY_SEQ,RESV_TY_CODE FROM FLXY_RESERVATION_TYPE WHERE RESV_TY_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteReservationType(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_RESERVATION_TYPE')->delete(['RESV_TY_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function purposeStay(){
        $data['title'] = getMethodName();
        return view('Reservation/PurposeStay', $data);
    }

    public function PurposeStayView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PURPOSE_STAY';
        $columns = 'PUR_ST_ID,PUR_ST_CODE,PUR_ST_DESC,PUR_ST_SEQ';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertPurposeStay(){
        try{
            $validate = $this->validate([
                'PUR_ST_CODE' => ['label' => 'Purpose Stay Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("PUR_ST_ID");
            if(!empty($sysid)){
                $data = ["PUR_ST_CODE" => $this->request->getPost("PUR_ST_CODE"),
                    "PUR_ST_DESC" => $this->request->getPost("PUR_ST_DESC"),
                    "PUR_ST_SEQ" => $this->request->getPost("PUR_ST_SEQ")
                ];
            $return = $this->Db->table('FLXY_PURPOSE_STAY')->where('PUR_ST_ID', $sysid)->update($data); 
            }else{
                $data = ["PUR_ST_CODE" => $this->request->getPost("PUR_ST_CODE"),
                    "PUR_ST_DESC" => $this->request->getPost("PUR_ST_DESC"),
                    "PUR_ST_SEQ" => $this->request->getPost("PUR_ST_SEQ")
                ];
                $return = $this->Db->table('FLXY_PURPOSE_STAY')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editPurposeStay(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT PUR_ST_ID,PUR_ST_CODE,PUR_ST_DESC,PUR_ST_SEQ FROM FLXY_PURPOSE_STAY WHERE PUR_ST_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deletePurposeStay(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_PURPOSE_STAY')->delete(['PUR_ST_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function payment(){
        $data['title'] = getMethodName();
        return view('Reservation/PaymentView', $data);
    }

    public function PaymentView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_PAYMENT';
        $columns = 'PYM_ID,PYM_CODE,PYM_DESC,PYM_TXN_CODE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertPayment(){
        try{
            $validate = $this->validate([
                'PYM_CODE' => ['label' => 'Payment Code', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("PYM_ID");
            if(!empty($sysid)){
                $data = ["PYM_CODE" => $this->request->getPost("PYM_CODE"),
                    "PYM_DESC" => $this->request->getPost("PYM_DESC"),
                    "PYM_TXN_CODE" => $this->request->getPost("PYM_TXN_CODE")
                ];
            $return = $this->Db->table('FLXY_PAYMENT')->where('PYM_ID', $sysid)->update($data); 
            }else{
                $data = ["PYM_CODE" => $this->request->getPost("PYM_CODE"),
                    "PYM_DESC" => $this->request->getPost("PYM_DESC"),
                    "PYM_TXN_CODE" => $this->request->getPost("PYM_TXN_CODE")
                ];
                $return = $this->Db->table('FLXY_PAYMENT')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editPayment(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT PYM_ID,PYM_CODE,PYM_DESC,PYM_TXN_CODE FROM FLXY_PAYMENT WHERE PYM_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deletePayment(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_PAYMENT')->delete(['PYM_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function getRateQueryData(){
        try{
            $RESV_MODE=$this->request->getPost('mode');
            $RESV_ARRIVAL_DT=$this->request->getPost('RESV_ARRIVAL_DT');
            $RESV_ARRIVAL_DT = strtotime($RESV_ARRIVAL_DT);
            $RESV_ARRIVAL_DT = date('Y-m-d', $RESV_ARRIVAL_DT);
            $RESV_DEPARTURE=$this->request->getPost('RESV_DEPARTURE');
            $RESV_DEPARTURE = strtotime($RESV_DEPARTURE);
            $RESV_DEPARTURE = date('Y-m-d', $RESV_DEPARTURE);
            $TODAYDATE = date('Y-m-d');
            $RESV_NIGHT = $this->request->getPost('RESV_NIGHT');
            $RESV_ADULTS = $this->request->getPost('RESV_ADULTS');
            $RESV_CHILDREN = $this->request->getPost('RESV_CHILDREN');


            $RESV_ROOM_CLASS = $this->request->getPost('RESV_ROOM_CLASS');
            $room_class_filter = !empty($RESV_ROOM_CLASS) ? " AND RM_TY_ROOM_CLASS = '".$RESV_ROOM_CLASS."'" : "";

            $RESV_FEATURE = $this->request->getPost('RESV_FEATURE');
            $features_filter = $RESV_FEATURE != NULL ? 
                               " AND CONCAT(',', RM_TY_FEATURE, ',') LIKE '%,".str_replace(",", ",%' OR CONCAT(',', RM_TY_FEATURE, ',') LIKE '%,", implode(",", $RESV_FEATURE)).",%'" : "";

            $RESV_RATE_CLASS = $this->request->getPost('RESV_RATE_CLASS');
            $RESV_RATE_CATEGORY = $this->request->getPost('RESV_RATE_CATEGORY');
            $RESV_RATE_CODE = $this->request->getPost('RESV_RATE_CODE');
            
            $rate_code_filter = (!empty($RESV_RATE_CODE) ? " AND RT_CD_ID = '".$RESV_RATE_CODE."'" : 
                                (!empty($RESV_RATE_CATEGORY) ? " AND RT_CT_ID = '".$RESV_RATE_CATEGORY."'" : 
                                (!empty($RESV_RATE_CLASS) ? " AND RT_CT_ID IN ( SELECT RT_CT_ID 
                                                                                FROM FLXY_RATE_CATEGORY 
                                                                                WHERE RT_CL_ID = '".$RESV_RATE_CLASS."')" : "")));

            $RESV_RATE_CODE_ROOM_TYPES = $this->request->getPost('RESV_RATE_CODE_ROOM_TYPES');
            $rcode_roomtype_filter = (!empty($RESV_RATE_CODE_ROOM_TYPES)) ? 
                                        " AND RM_TY_CODE IN ('".str_replace(",", "','",$RESV_RATE_CODE_ROOM_TYPES)."') " : "";

            $RESV_ROOM_TYPE = $this->request->getPost('resv_room_type');
            $RESV_RATE = $this->request->getPost('resv_rate');
            
            $RATE_CLOSED  = $this->request->getPost('closed');
            $RATE_DAY_USE = $this->request->getPost('day_use');

            $ROOM_PLAN_ROOM_TYPE =  $this->request->getPost('ROOM_PLAN_ROOM_TYPE') ?? 0;

            $param = [  'ARRIVAL_DT'=> $RESV_ARRIVAL_DT,
                        'DEPARTURE_DT'=> $RESV_DEPARTURE,
                        'RESV_ADULTS'=> $this->request->getPost("RESV_ADULTS"),
                        'RESV_CHILDREN'=> $this->request->getPost("RESV_CHILDREN"),
                        'TODAYDATE'=> $TODAYDATE
                     ];

            $sql = "SELECT RM_TY_CODE, RM_TY_DESC, ISNULL(NUM_ROOMS, 0) AS RM_TY_TOTAL_ROOM, ISNULL((NUM_ROOMS+TOTAL_OVER_BOOKING), 0) AS TOTAL_OVER_BOOKING, RM_TY_FEATURE 
                    FROM 
			            (   SELECT  RM_TY_CODE, RM_TY_ID, (RM_TY_TOTAL_ROOM) RM_TY_TOTAL_ROOM, RM_TY_DESC, RM_TY_ROOM_CLASS,
			                        (RM_TY_TOTAL_ROOM)TOTAL_OVER_BOOKING, RM_TY_FEATURE,NUM_ROOMS,
			                        ROW_NUMBER() OVER (PARTITION BY RM_TY_CODE ORDER BY RM_TY_CODE) AS ROW_NUMBER
			                FROM    FLXY_ROOM_TYPE ROOMTYTB
                			LEFT JOIN FLXY_OVERBOOKING OVERBTB  ON RM_TY_CODE = OB_RM_TYPE 
			                                                    AND ( :ARRIVAL_DT: BETWEEN OB_FROM_DT AND OB_UPTO_DT 
                                                                      OR :DEPARTURE_DT: BETWEEN OB_FROM_DT AND OB_UPTO_DT )
                            
                            LEFT JOIN ( SELECT RM_TYPE_REF_ID, RM_TYPE, (COUNT(*)-COUNT(RESV_ROOM)) AS NUM_ROOMS
                                        FROM FLXY_ROOM 
                                        LEFT JOIN ( SELECT MAX(RM_STAT_LOG_ID) AS RM_MAX_LOG_ID, RM_STAT_ROOM_ID
                                                    FROM FLXY_ROOM_STATUS_LOG
                                                    GROUP BY RM_STAT_ROOM_ID) RM_STAT_LOG ON RM_ID = RM_STAT_LOG.RM_STAT_ROOM_ID 
                                        
                                        LEFT JOIN FLXY_ROOM_STATUS_LOG RL ON RL.RM_STAT_LOG_ID = RM_STAT_LOG.RM_MAX_LOG_ID                
                                        LEFT JOIN FLXY_ROOM_STATUS_MASTER SM ON SM.RM_STATUS_ID = RL.RM_STAT_ROOM_STATUS

                                        LEFT JOIN (SELECT RESV_ROOM_ID AS RESV_ROOM     
                                                    FROM FLXY_RESERVATION
                                                    WHERE RESV_ROOM_ID > 0
                                                    AND (:ARRIVAL_DT: <= RESV_DEPARTURE AND :DEPARTURE_DT: >= RESV_ARRIVAL_DT)
                                                    AND RESV_STATUS NOT IN ('Checked-Out','Cancelled')
                                                    GROUP BY RESV_ROOM_ID) RESV_ROOMS ON RESV_ROOMS.RESV_ROOM = RM_ID
                                        
                                        WHERE RM_STATUS_ID IS NULL OR RM_STATUS_ID NOT IN (4,5)
                                        GROUP BY RM_TYPE_REF_ID, RM_TYPE
                                        ) RMTYPE_ROOMS ON RMTYPE_ROOMS.RM_TYPE_REF_ID = ROOMTYTB.RM_TY_ID                                
                        ) MAIN_DATA 
                    WHERE ROW_NUMBER = 1 
                    $room_class_filter
                    $rcode_roomtype_filter
                    $features_filter
                    ORDER BY RM_TY_CODE ASC";
            
            $response = $this->Db->query($sql,$param)->getResultArray();
            // print_r($response);exit;
            if($RESV_MODE=='AVG'){
                $operation = ' CAST(AVG(ACTUAL_ADULT_PRICE)AS DECIMAL(10, 2))ACTUAL_ADULT_PRICE,COUNT(value) TOTAL';
                $groupby=' GROUP BY value,RT_CD_ID,RT_DESCRIPTION,RT_INFO ORDER BY RT_CD_ID,ROOM_TYPE ASC';
            }elseif($RESV_MODE=='TOT'){
                $operation = ' CAST(SUM(ACTUAL_ADULT_PRICE)*'.$RESV_NIGHT.'AS DECIMAL(10, 2))ACTUAL_ADULT_PRICE,COUNT(value) TOTAL';
                $groupby=' GROUP BY value,RT_CD_ID,RT_DESCRIPTION,RT_INFO ORDER BY RT_CD_ID,ROOM_TYPE ASC';
            }

            $closed  = $RATE_CLOSED == 1 ? "" : "AND ( :ARRIVAL_DT: BETWEEN RT_CD_START_DT AND RT_CD_END_DT 
                                                       AND :DEPARTURE_DT: BETWEEN RT_CD_START_DT AND RT_CD_END_DT
                                                     )";
            $day_use = $RATE_DAY_USE == 1 ? "" : "AND RT_CD_DAY_USE != 1";

            $sqlRate = "SELECT RT_DESCRIPTION, RT_INFO, RT_CD_ID, value as ROOM_TYPE, $operation
                        FROM (SELECT 
                                (SELECT RT_CD_CODE FROM FLXY_RATE_CODE WHERE RATEQUERY.RT_CD_ID=RT_CD_ID)RT_DESCRIPTION, 
                                (SELECT RT_CD_DESC FROM FLXY_RATE_CODE WHERE RATEQUERY.RT_CD_ID=RT_CD_ID)RT_INFO, RATEQUERY.*,
                                CASE 
                                    WHEN 1 = '$RESV_ADULTS' THEN CAST(RT_CD_DT_1_ADULT AS DECIMAL(10, 2))
                                    WHEN 2 = '$RESV_ADULTS' THEN CAST(ISNULL(RT_CD_DT_2_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2))
                                    WHEN 3 = '$RESV_ADULTS' THEN CAST(ISNULL(RT_CD_DT_3_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2))
                                    WHEN 4 = '$RESV_ADULTS' THEN CAST(ISNULL(RT_CD_DT_4_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2))
                                    WHEN 5 = '$RESV_ADULTS' THEN CAST(ISNULL(RT_CD_DT_5_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2))
                                ELSE (CAST(ISNULL(RT_CD_DT_5_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2))+CAST(ISNULL(RT_CD_DT_EXTRA_ADULT,RT_CD_DT_1_ADULT) AS DECIMAL(10, 2)))
                                END AS ACTUAL_ADULT_PRICE
                              FROM (
                                        SELECT * FROM FLXY_RATE_CODE_DETAIL RT_DETAIL 
                                        WHERE EXISTS(   SELECT RT_CD_ID FROM FLXY_RATE_CODE 
                                                        WHERE :TODAYDATE: BETWEEN RT_CD_BEGIN_SELL_DT AND RT_CD_END_SELL_DT
                                                        AND RT_CD_ID = RT_DETAIL.RT_CD_ID 
                                                        AND RT_CD_NEGOTIATED != 1 
                                                        $day_use
                                                        $rate_code_filter
                                                    )
                                   ) RATEQUERY 
                              WHERE 1 = 1     
                              $closed
                            ) TEMP_QUERY CROSS APPLY STRING_SPLIT(RT_CD_DT_ROOM_TYPES,',')
                        $groupby";

            $rateresult = $this->Db->query($sqlRate,$param)->getResultArray();  
            $roomType='';
            $physicalinv='';
            $feature='';
            $roomtypeStore=[];
            // print_r($rateresult);
            // exit;
            foreach($response as $row){
                $roomtypeStore[]=$row['RM_TY_CODE'];
                $roomType.='<td style="width:120px;" data-roomtype-info="'.$row['RM_TY_DESC'].'">'.$row['RM_TY_CODE'].'</td>';
                $physicalinv.='<td style="width:100px;word-wrap: break-word;">'.$row['RM_TY_TOTAL_ROOM'].'</td>';
                $feature.='<td style="width:100px;word-wrap: break-word;">'.$row['TOTAL_OVER_BOOKING'].'</td>';
            }
            $trRow = '<tr><td style="width:210px;">Room Type</td>'.$roomType.'</tr>';
            $trRow .= '<tr><td style="width:210px;">Physical Inventory</td>'.$physicalinv.'</tr>';
            $trRow .= '<tr><td style="width:210px;">Include Overbooking</td>'.$feature.'</tr>';
            // print_r($rateresult);exit;
            $roomTypeArr=[];
            $RT_CD_REF_ID='';
            $ROOM_TYPE='';
            $EXISTKEY='';
            $INNERLOOP=-1;
            foreach($rateresult as $key=>$data){
                if($RT_CD_REF_ID!=$data['RT_CD_ID']){
                    $EXISTKEY=$key;
                    $INNERLOOP++;
                    $FieldName=trim($data['RT_DESCRIPTION']);
                }
                if($RESV_MODE=='AVG' || $RESV_MODE=='TOT'){
                    foreach($roomtypeStore as $keys=>$room){ 
                        if (strpos($data['ROOM_TYPE'], $room) !== false) { 
                            $roomTypeArr[$FieldName][$keys]=$data;
                        }
                    }
                    $RT_CD_REF_ID=$data['RT_CD_ID'];
                }else{ 
                    $RT_CD_REF_ID=trim($data['RT_CD_ID']);
                    if($RT_CD_REF_ID==trim($data['RT_CD_ID']) && $ROOM_TYPE!=trim($data['ROOM_TYPE'])){
                        foreach($roomtypeStore as $keys=>$room){ 
                            if (strpos($data['ROOM_TYPE'], $room) !== false) { 
                                $roomTypeArr[$FieldName][$keys]=$data;
                            }
                        }
                    }else{
                        continue;
                    }
                    $ROOM_TYPE=trim($data['ROOM_TYPE']);
                }
            }
            $totalrmType = count($roomtypeStore);
             //print_r($roomTypeArr);
             $j = 0;
            foreach($roomTypeArr as $key=>$data){ 
                $trRow .='<tr class="ratePrice">';
                $trRow .='<td><input type="hidden" id="RT_DESCRIPTION" value="'.trim($key).'">'.$key.'</td>';
                
                for($i=0;$i<$totalrmType;$i++){
                    if(!empty($data[$i])){                   
                        if($ROOM_PLAN_ROOM_TYPE == $data[$i]['ROOM_TYPE'] && $j == 0){
                            $active = 'active';
                            $j++;
                        }                        
                        else
                        $active = ($data[$i]['ACTUAL_ADULT_PRICE'] == $RESV_RATE && $data[$i]['ROOM_TYPE'] == $RESV_ROOM_TYPE) ? 'active' : '';
                        $trRow .='<td class="clickPrice '.$active.'" data-rate-info="'.$data[$i]['RT_INFO'].'">'
                        .'<input type="hidden" id="ACTUAL_ADULT_PRICE" value="'.number_format($data[$i]['ACTUAL_ADULT_PRICE'], 2).'">'
                        .'<input type="hidden" id="ROOMTYPE" value="'.$data[$i]['ROOM_TYPE'].'">'.number_format($data[$i]['ACTUAL_ADULT_PRICE'], 2).'</td>';
                    }else{
                        $trRow .='<td class=""></td>';
                    }
                }
                $trRow .='</tr>';
            }
            $tables[]=$trRow;
            echo json_encode($tables);
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function overBooking(){
        $data['title'] = getMethodName();
        return view('Reservation/OverBookingView', $data);
    }

    public function OverBookingView(){
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_OVERBOOKING';
        $columns = 'OB_ID,OB_FROM_DT,OB_UPTO_DT,OB_RM_CLASS,OB_RM_TYPE,OB_OVER_BK_COUNT,OB_FORMULA';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function getSupportingOverbookingLov(){
        $sql = "SELECT RM_CL_CODE,RM_CL_DESC FROM FLXY_ROOM_CLASS";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value=""></option>';
        foreach($response as $row){
            $option.= '<option value="'.trim($row['RM_CL_CODE']).'">'.$row['RM_CL_DESC'].'</option>';
        }
        echo $option;
    }

    function getRoomType(){
        $param = ['ROOMCLASS'=> $this->request->getPost("rmclass")];
        $sql = "SELECT RM_TY_ID,RM_TY_CODE,RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_ROOM_CLASS=:ROOMCLASS:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        $option='<option value=""></option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_TY_CODE'].'" data-room-type-id="'.$row['RM_TY_ID'].'">'.$row['RM_TY_DESC'].'</option>';
        }
        echo $option;
    }

    public function insertOverBooking(){
        try{
            $validate = $this->validate([
                'OB_FROM_DT' => ['label' => 'From Date', 'rules' => 'required']
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("OB_ID");
            $days = $this->request->getPost("OB_DAYS");
            $dayes = implode(" ",$days);
            if(!empty($sysid)){
                $data = ["OB_FROM_DT" => $this->request->getPost("OB_FROM_DT"),
                    "OB_UPTO_DT" => $this->request->getPost("OB_UPTO_DT"),
                    "OB_RM_CLASS" => $this->request->getPost("OB_RM_CLASS"),
                    "OB_RM_TYPE" => $this->request->getPost("OB_RM_TYPE"),
                    "OB_RM_TYPE_ID" => $this->request->getPost("OB_RM_TYPE_ID"),
                    "OB_OVER_BK_COUNT" => $this->request->getPost("OB_OVER_BK_COUNT"),
                    "OB_DAYS" => $dayes
                ];
                $return = $this->Db->table('FLXY_OVERBOOKING')->where('OB_ID', $sysid)->update($data); 
            }else{
                $data = ["OB_FROM_DT" => $this->request->getPost("OB_FROM_DT"),
                    "OB_UPTO_DT" => $this->request->getPost("OB_UPTO_DT"),
                    "OB_RM_CLASS" => $this->request->getPost("OB_RM_CLASS"),
                    "OB_RM_TYPE" => $this->request->getPost("OB_RM_TYPE"),
                    "OB_RM_TYPE_ID" => $this->request->getPost("OB_RM_TYPE_ID"),
                    "OB_OVER_BK_COUNT" => $this->request->getPost("OB_OVER_BK_COUNT"),
                    "OB_DAYS" => $dayes
                ];
                $return = $this->Db->table('FLXY_OVERBOOKING')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success",$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function editOverBooking(){
        $param = ['SYSID'=> $this->request->getPost("sysid")];
        $sql = "SELECT OB_ID,FORMAT(OB_FROM_DT,'dd-MMM-yyyy') OB_FROM_DT,FORMAT(OB_UPTO_DT,'dd-MMM-yyyy')OB_UPTO_DT,OB_RM_CLASS,OB_RM_TYPE,OB_RM_TYPE_ID,OB_DAYS,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=OB_RM_TYPE)OB_RM_TYPE_DESC,
        OB_OVER_BK_COUNT,OB_FORMULA FROM FLXY_OVERBOOKING WHERE OB_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteOverBooking(){
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_OVERBOOKING')->delete(['OB_ID' => $sysid]); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-402","Record not deleted");
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function getRoomTypeDetails(){
        $param = ['ROOMTYPE'=> $this->request->getPost("rmtype")];
        $sql = "SELECT RM_TY_CODE,RM_TY_DESC,RM_TY_FEATURE,RM_TY_ROOM_CLASS FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=:ROOMTYPE:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function searchProfile(){
        $CUST_ID = $this->request->getPost("CUST_ID") ? $this->request->getPost("CUST_ID") : 0;
        $reservation_customer_id = $this->request->getPost("reservation_customer_id") ? $this->request->getPost("reservation_customer_id") : 0;

        $RESV_ID = $this->request->getPost("RESV_ID") ? $this->request->getPost("RESV_ID") : 0;
        $get_accomp = $this->request->getPost("get_accomp") ? $this->request->getPost("get_accomp") : 0;
        $get_not_accomp = $this->request->getPost("get_not_accomp") ? $this->request->getPost("get_not_accomp") : 0;
        

        $sql = "SELECT  CUST_ID,CUST_FIRST_NAME,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) NAMES,CUST_LAST_NAME,
                        FORMAT(CUST_DOB,'dd-MMM-yyyy')CUST_DOB,CUST_PASSPORT,CUST_NATIONALITY,CUST_VIP,CUST_ADDRESS_1,CUST_CITY,
                        CUST_EMAIL,CUST_MOBILE 
                FROM FLXY_CUSTOMER WHERE 1 = 1 ";

        $search_keys = ['CUST_LAST_NAME', 'CUST_FIRST_NAME', 'CUST_CITY', 'CUST_EMAIL', 
                        'CUST_CLIENT_ID', 'CUST_MOBILE', 'CUST_COMMUNICATION_DESC', 'CUST_PASSPORT'];

        $form_search = 0;
        $form_conditions = '';

        if($search_keys != NULL)
        {
            foreach($search_keys as $search_key)
            {
                if(null !== $this->request->getPost($search_key) && !empty(trim($this->request->getPost($search_key))))
                {
                    $form_search = 1;
                    $value = trim($this->request->getPost($search_key));
                    $form_conditions .= ($search_key == 'CUST_LAST_NAME' ? 'CONCAT_WS(\' \', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME)' : $search_key) ." LIKE '%$value%' OR";
                }
            }
            $form_conditions = rtrim($form_conditions, ' OR');
        }

        if($form_search == 1)        
            $sql .= " AND ( $form_conditions )";
        
        if(!empty($CUST_ID))
            $sql .= " AND CUST_ID IN ($CUST_ID)";

        else if(!empty($RESV_ID) && !empty($get_accomp)) // Get Accompany Guests
            $sql .= " AND CUST_ID IN (SELECT ACCOMP_CUST_ID FROM FLXY_ACCOMPANY_PROFILE WHERE ACCOMP_REF_RESV_ID = $RESV_ID)";
        
        else if(!empty($RESV_ID) && !empty($get_not_accomp)) // Get all except Accompany Guests
            $sql .= " AND CUST_ID NOT IN (SELECT ACCOMP_CUST_ID FROM FLXY_ACCOMPANY_PROFILE WHERE ACCOMP_REF_RESV_ID = $RESV_ID)";
        
        if(!empty($reservation_customer_id))
            $sql .= " AND CUST_ID != $reservation_customer_id";

        $response = $this->Db->query($sql)->getResultArray();
        $table='';
        if(empty($response)){
            if(!empty($RESV_ID) && !empty($get_accomp))
                $table.='<tr><td class="text-left" colspan="12" style="padding-left: 20% !important;">No Accompanying Guests</td></tr>';
            else
                $table.='<tr><td class="text-left" colspan="12" style="padding-left: 20% !important;">No Record Found</td></tr>';
        }
        $modeWindow = $this->request->getPost("windowmode");
        $className = $modeWindow == 'C' ? 'getExistCust' : 'activeRow';
        foreach($response as $key=>$row){
            $table.='<tr class="'.$className.'" data_sysid="'.$row['CUST_ID'].'">'
            .'<td class="editcustomer" data_sysid="'.$row['CUST_ID'].'"><i class="fa-solid fa-user-pen"></i></td>'
            .'<td>'
            .($key+1).'</td>'
            .'<td class="select">'.$row['CUST_FIRST_NAME'].'</td>'
            .'<td class="select">'.$row['CUST_LAST_NAME'].'</td>'
            .'<td class="select">'.$row['CUST_DOB'].'</td>'
            .'<td class="select">'.$row['CUST_PASSPORT'].'</td>'
            .'<td class="select">'.$row['CUST_ADDRESS_1'].'</td>'
            .'<td class="select">'.$row['CUST_CITY'].'</td>'
            .'<td class="select">'.$row['CUST_EMAIL'].'</td>'
            .'<td class="select">'.$row['CUST_MOBILE'].'</td>'
            .'<td class="select">'.$row['CUST_NATIONALITY'].'</td>'
            .'<td class="select">'.$row['CUST_VIP'].'</td>'
            .'</tr>';
        }
        $return['table']=$table;
        echo json_encode($return);
    }

    function getExistingAppcompany(){
        $param = ['CUSTID'=> $this->request->getPost("custId"),'ACCOMP_REF_RESV_ID'=> $this->request->getPost("ressysId")];
        
        $sql = "SELECT '0' ACCOPM_ID,CUST_ID,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) NAMES,
                CUST_DOB,CUST_CITY 
                FROM FLXY_CUSTOMER 
                WHERE CUST_ID=:CUSTID:
                UNION 
                SELECT ACCOPM_ID,ACCOMP_CUST_ID CUST_ID,CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) NAMES,
                CUST_DOB,CUST_CITY 
                FROM FLXY_ACCOMPANY_PROFILE,FLXY_CUSTOMER 
                WHERE ACCOMP_REF_RESV_ID=:ACCOMP_REF_RESV_ID: AND CUST_ID=ACCOMP_CUST_ID";

        $response = $this->Db->query($sql,$param)->getResultArray();
        $table='';
        if(empty($response)){
            $table.='<tr><td class="text-left" colspan="12" style="padding-left: 20% !important;">No Record Found</td></tr>';
        }
        foreach($response as $key=>$row){
            $table.='<tr class="activeDetach" data_sysid="'.$row['ACCOPM_ID'].'">'
            .'<td>'.$row['NAMES'].'</td>'
            .'<td>'.$row['CUST_CITY'].'</td>'
            .'<td>'.$row['CUST_DOB'].'</td>'
            .'</tr>';
        }
        $return['table']=$table;
        echo json_encode($return);
    }

    public function rateQueryDetailOption(){
        $fromdate = $this->request->getPost("fromdate");
        $uptodate = $this->request->getPost("uptodate");
        $roomType = $this->request->getPost("roomType");
        $fromdate = date("Y-m-d", strtotime($fromdate));
        $uptodate = date("Y-m-d", strtotime($uptodate));
        $result = $this->dateLoopBasedOnCustomze($fromdate, $uptodate);
        $param = ['OB_RM_TYPE'=> $this->request->getPost("roomType"),'OB_FROM_DT'=>$fromdate,'OB_UPTO_DT'=>$uptodate];
        $sql="SELECT * FROM FLXY_OVERBOOKING WHERE OB_RM_TYPE=:OB_RM_TYPE: AND (:OB_FROM_DT: BETWEEN OB_FROM_DT AND OB_UPTO_DT 
        AND :OB_UPTO_DT: BETWEEN OB_FROM_DT AND OB_UPTO_DT)";
        $response = $this->Db->query($sql,$param)->getResultArray();
        $table='';
        if(!empty($roomType)){
            foreach($result as $date){
                $OB_RM_TYPE='';
                $OB_OVER_BK_COUNT='';
                foreach($response as $row){
                    if($date>=$row['OB_FROM_DT'] && $date<=$row['OB_UPTO_DT']){
                        $OB_RM_TYPE=$row['OB_RM_TYPE'];
                        $OB_OVER_BK_COUNT=$row['OB_OVER_BK_COUNT'];
                        break;
                    }
                }
                $table.='<tr>'
                .'<td>'.$date.'</td>'
                .'<td>'.$OB_RM_TYPE.'</td>'
                .'<td>'.$OB_OVER_BK_COUNT.'</td>'
                .'</tr>';
            }
        }else{
            $table.='<tr><td class="text-center" colspan="3">No Record Found</td></tr>';
        }
        $return['table']=$table;
        echo json_encode($return);
    }

    function dateLoopBasedOnCustomze( $fromdate, $uptodate, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime($fromdate);
        $last = strtotime($uptodate);
        while($current <= $last) {
            $dates[] = date($format,$current);
            $current = strtotime($step,$current);
        }
        return $dates;
    }

    public function getExistCustomer(){
        $param = ['CUSTID'=> $this->request->getPost("custId")];
        $sql="SELECT CUST_ID, CUST_FIRST_NAME, CUST_LAST_NAME, CUST_TITLE, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) NAMES FROM FLXY_CUSTOMER WHERE CUST_ID=:CUSTID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    
    public function appcompanyProfileSetup(){
        try{
            $dateTime = $this->todayDate->format('Y-m-d H:i:s');
            $mode = $this->request->getPost("mode");

            $ACCOMP_CUST_ID = $this->request->getPost("ACCOMP_CUST_ID");
            $ACCOMP_REF_RESV_ID = $this->request->getPost('ACCOMP_REF_RESV_ID');
            
            if($mode=='A'){ // Add Accompanying Guest
                
                $data = ["ACCOMP_CUST_ID" => $ACCOMP_CUST_ID,
                         "ACCOMP_REF_RESV_ID" => $ACCOMP_REF_RESV_ID,
                         "ACCOMP_CREATE_DT" => $dateTime,
                         "ACCOMP_CREATE_UID" => session()->get('USR_ID'),
                        ];
                $return = $this->Db->table('FLXY_ACCOMPANY_PROFILE')->insert($data); 
                $message='Record not inserted !';

            }else{
                //$return = $this->Db->table('FLXY_ACCOMPANY_PROFILE')->delete(['ACCOPM_ID' => $ACCOPM_ID]);  
                $return = $this->Db->query("DELETE FROM FLXY_ACCOMPANY_PROFILE WHERE ACCOMP_CUST_ID = ".$ACCOMP_CUST_ID." 
                                                                                 AND ACCOMP_REF_RESV_ID = ".$ACCOMP_REF_RESV_ID.""); 
                $message = 'Record not deleted !';
            }

            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444",$message,$return);
                echo json_encode($result);
            }
        }catch (\Exception $e){
            return $this->respond($e->getMessage());
        }
    }
    public function webLineReservation($resvid){
        // try{
            $data['title'] = 'Reservation Detail';

            $param = ['RESV_ID'=> $resvid];
            $sql = "SELECT RESV_ID, RESV_NO, FORMAT(RESV_ARRIVAL_DT,'dd-MMM-yyyy') RESV_ARRIVAL_DT, 
                        RESV_NIGHT, RESV_ADULTS, RESV_CHILDREN, FORMAT(RESV_DEPARTURE,'dd-MMM-yyyy') RESV_DEPARTURE,
                        CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) FULLNAME,
                        RESV_ROOM, RESV_NO_F_ROOM, RESV_NAME, RESV_RM_TYPE, RESV_STATUS, CUST_FIRST_NAME, CUST_ID, CUST_LAST_NAME, CUST_TITLE, 
                        FORMAT(CUST_DOB,'dd-MMM-yyyy') CUST_DOB, CUST_PASSPORT, CUST_ADDRESS_1, CUST_ADDRESS_2, CUST_ADDRESS_3, 
                        CUST_COUNTRY, CUST_STATE, CUST_CITY, 
                        CUST_EMAIL, CUST_MOBILE, CUST_PHONE, CUST_POSTAL_CODE, CUST_NATIONALITY, CUST_DOC_TYPE, 
                        CUST_GENDER, CUST_DOC_EXPIRY, CUST_DOC_NUMBER, FORMAT(CUST_DOC_ISSUE,'dd-MMM-yyyy') CUST_DOC_ISSUE,
                        RESV_ACCP_TRM_CONDI, RESV_SINGATURE_URL, RESV_ETA,
                        RM_TY_DESC,
                        SNAME as CUST_STATE_DESC,
                        CTNAME as CUST_CITY_DESC,
                        fd.DOC_FILE_PATH
                        FROM FLXY_RESERVATION
                        left join FLXY_CUSTOMER on FLXY_RESERVATION.RESV_NAME = FLXY_CUSTOMER.CUST_ID
                        left join FLXY_ROOM_TYPE on FLXY_RESERVATION.RESV_RM_TYPE = FLXY_ROOM_TYPE.RM_TY_CODE
                        left join STATE on STATE.STATE_CODE = FLXY_CUSTOMER.CUST_STATE
                        left join CITY on CITY.ID = FLXY_CUSTOMER.CUST_CITY
                        left join FLXY_DOCUMENTS as fd on RESV_ID = fd.DOC_RESV_ID and fd.DOC_FILE_TYPE = 'SIGN'
                        WHERE RESV_ID = :RESV_ID:";

            $response = $this->Db->query($sql,$param)->getResultArray();

            if(empty($response)) {
                echo "This link is not valid!";
                die();
            }

            $response = $response[0];
            
            $sql = "select ap.*,
                fc.*,
                concat(fc.CUST_FIRST_NAME, ' ', fc.CUST_MIDDLE_NAME, ' ', fc.CUST_LAST_NAME) as FULLNAME,
                FORMAT(fc.CUST_DOB,'dd-MMM-yyyy') CUST_DOB,
                FORMAT(fc.CUST_DOC_ISSUE,'dd-MMM-yyyy') CUST_DOC_ISSUE,
                SNAME as CUST_STATE_DESC,
                CTNAME as CUST_CITY_DESC
                from FLXY_ACCOMPANY_PROFILE as ap 
                left join FLXY_CUSTOMER as fc on ap.ACCOMP_CUST_ID = fc.CUST_ID
                left join STATE on STATE.id = fc.CUST_STATE_ID
                left join CITY on CITY.id = fc.CUST_CITY
                where ap.ACCOMP_REF_RESV_ID = :RESV_ID:";
            
            $response['ACCOMPANY_PROFILES'] = $this->Db->query($sql, $param)->getResultArray();

            // print_r($response['ACCOMPANY_PROFILES']);
            // die();
            
            $data['data'] = $response;
            $data['condition'] = '';
            $data['session'] = $this->session;
            $data['doc_types'] = $this->Db->query("select DT_ID as id, DT_NAME as label from FLXY_DOC_TYPES")->getResultArray();
            $data['vacc_types'] = $this->Db->query("select VT_ID as id, VT_NAME as label from FLXY_VACCINE_TYPES")->getResultArray();

            return view('WebCheckin/CheckInReservation', $data);
        // }catch (\Exception $e){
            // return $this->respond($e->getMessage());
        // }
    }

    function imageUpload(){
        try{
            $avatar = $this->request->getFile('file');
            $avatar->move(ROOTPATH . 'assets/Uploads/UserDocuments/proof/');
            echo $avatar->getClientName();
            exit;
        }catch (\Exception $e){
            return $this->respond($e->getMessage());
        }
    }

    function croppingImage(){
        try{
            $dateTime = $this->todayDate->format('Y-m-d H:i:s');
            $mode = $this->request->getPost("mode");
            $image = $this->request->getPost("img");
            $imageName = basename($image);
            $extn=pathinfo($image, PATHINFO_EXTENSION);
            $newFile = time().'.'.$extn;
            if($mode=='C'){
                $img_r = imagecreatefromjpeg($image);
                $dst_r = ImageCreateTrueColor( $_POST['w'], $_POST['h'] );
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'], $_POST['w'],$_POST['h']);
                // header('Content-type: image/jpeg');
                imagejpeg($dst_r,'assets/Uploads/UserDocuments/proof/'.$newFile);
            }else{
                $sourcePath = dirname(__DIR__,2). '/assets/Uploads/UserDocuments/proof/'.$imageName;
                $newPath = dirname(__DIR__,2). '/assets/Uploads/UserDocuments/proof/'.$newFile;
                rename($sourcePath,$newPath);
            }
            $file_unlink = dirname(__DIR__,2).'/assets/Uploads/UserDocuments/proof/'.$imageName;
            if(file_exists($file_unlink)){
                unlink($file_unlink);
            }
            $data = ["DOC_CUST_ID" => $this->request->getPost("DOC_CUST_ID"),
                "DOC_RESV_ID" => $this->request->getPost("DOC_RESV_ID"),
                "DOC_FILE_PATH" => $newFile,
                "DOC_FILE_TYPE" => $this->request->getPost("DOC_FILE_TYPE"),
                "DOC_FILE_DESC" => $this->request->getPost("DOC_FILE_DESC"),
                "DOC_IS_VERIFY" => $this->request->getPost("DOC_IS_VERIFY") ?? 0,
                "DOC_CREATE_UID" => $this->session->USR_ID,
                "DOC_CREATE_DT" => $dateTime
            ];

            $uploaded_documents = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_CUST_ID', $this->request->getPost("DOC_CUST_ID"))
                ->where('DOC_RESV_ID', $this->request->getPost("DOC_RESV_ID"))
                ->where('DOC_FILE_TYPE', 'PROOF')
                ->get()->getResultArray();
            
            if(count($uploaded_documents)){
                $return = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_CUST_ID', $this->request->getPost("DOC_CUST_ID"))
                                    ->where('DOC_RESV_ID', $this->request->getPost("DOC_RESV_ID"))
                                    ->where('DOC_FILE_TYPE', 'PROOF')
                                    ->update(['DOC_FILE_PATH' => $uploaded_documents[0]['DOC_FILE_PATH'] . ',' . $newFile]);
            }
            else{
                $return = $this->Db->table('FLXY_DOCUMENTS')->insert($data);
            }

            if($return){
                $array = array('DOC_CUST_ID' => $this->request->getPost("DOC_CUST_ID"), 'DOC_RESV_ID' => $this->request->getPost("DOC_RESV_ID"), 'DOC_FILE_TYPE' => 'PROOF');
                $listImage = $this->Db->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_FILE_PATH,DOC_FILE_TYPE')->where($array)->orderBy('DOC_ID', 'DESC')->get()->getResultArray();
                // $imagePath = array('IMAGEPATH'=>$newFile);
                // $outPut = array_merge($imagePath,$listImage);
                $outPut = $listImage[0];
                $result = $this->responseJson("1","0",$return,$outPut);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444", $message, $return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function getActiveUploadImages(){
        $array = array('DOC_CUST_ID' => $this->request->getPost("DOC_CUST_ID"), 'DOC_RESV_ID' => $this->request->getPost("DOC_RESV_ID"));
        $listImage = $this->Db->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_FILE_PATH,DOC_FILE_TYPE')->where($array)->get()->getResultArray();
        echo json_encode($listImage);
    }

    public function deleteUploadImages(){
        try{
            $return = false;

            $sysid = $this->request->getPost("sysid");
            $file_name = $this->request->getPost("file_name");

            $uploaded_documents = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_ID', $sysid)->get()->getResultArray(); 
            if(count($uploaded_documents)){
                $uploaded_documents = $uploaded_documents[0];
                $uploaded_documents_arr = explode(",", $uploaded_documents['DOC_FILE_PATH']);
                
                if(count($uploaded_documents_arr) == 1){
                    $return = $this->Db->table('FLXY_DOCUMENTS')->delete(['DOC_ID' => $sysid]); 
                }

                $return = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_ID', $sysid)
                                    ->update(['DOC_FILE_PATH' => str_replace(",$file_name", '', $uploaded_documents['DOC_FILE_PATH'])]); 
            }

            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444", 'No documents', $return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function updateCustomerDetail(){
        try{
            $resvid = $this->request->getPost("DOC_RESV_ID");
            $custId = $this->request->getPost("DOC_CUST_ID");

            $validate = $this->validate([
                'CUST_TITLE' => ['label' => 'title', 'rules' => 'required'],
                'CUST_FIRST_NAME' => ['label' => 'first name', 'rules' => 'required'],
                'CUST_LAST_NAME' => ['label' => 'last name', 'rules' => 'required'],
                'CUST_GENDER' => ['label' => 'gender', 'rules' => 'required'],
                'CUST_NATIONALITY' => ['label' => 'nationality', 'rules' => 'required'],
                'CUST_DOB' => ['label' => 'date of birth', 'rules' => 'required'],
                'CUST_COUNTRY' => ['label' => 'country', 'rules' => 'required'],
                'CUST_DOC_TYPE' => ['label' => 'document type', 'rules' => 'required'],
                'CUST_DOC_NUMBER' => ['label' => 'document number', 'rules' => 'required'],
                'CUST_DOC_ISSUE' => ['label' => 'issue date', 'rules' => 'required'],
                'CUST_PHONE' => ['label' => 'phone', 'rules' => 'required'],
                'CUST_EMAIL' => ['label' => 'email', 'rules' => 'required'],
                'CUST_ADDRESS_1' => ['label' => 'address line 1', 'rules' => 'required'],
                'CUST_STATE' => ['label' => 'state', 'rules' => 'required'],
                'CUST_CITY' => ['label' => 'city', 'rules' => 'required'],                
            ]);

            if(!$validate){
                $validate = $this->validator->getErrors();

                $result = $this->responseJson(403, $validate);
                echo json_encode($result);
                exit;
            }

            $data = ["CUST_TITLE" => $this->request->getPost("CUST_TITLE"),
                "CUST_FIRST_NAME" => $this->request->getPost("CUST_FIRST_NAME"),
                "CUST_LAST_NAME" => $this->request->getPost("CUST_LAST_NAME"),
                "CUST_GENDER" => $this->request->getPost("CUST_GENDER"),
                "CUST_NATIONALITY" => $this->request->getPost("CUST_NATIONALITY"),
                "CUST_DOB" => $this->request->getPost("CUST_DOB"),
                "CUST_COUNTRY" => $this->request->getPost("CUST_COUNTRY"),
                "CUST_DOC_TYPE" => $this->request->getPost("CUST_DOC_TYPE"),
                "CUST_DOC_NUMBER" => $this->request->getPost("CUST_DOC_NUMBER"),
                "CUST_DOC_ISSUE" => $this->request->getPost("CUST_DOC_ISSUE"),
                "CUST_PHONE" => $this->request->getPost("CUST_PHONE"),
                "CUST_EMAIL" => $this->request->getPost("CUST_EMAIL"),
                "CUST_ADDRESS_1" => $this->request->getPost("CUST_ADDRESS_1"),
                "CUST_ADDRESS_2" => $this->request->getPost("CUST_ADDRESS_2"),
                "CUST_STATE" => $this->request->getPost("CUST_STATE"),
                "CUST_CITY" => $this->request->getPost("CUST_CITY"),
                "CUST_UPDATE_UID" => session()->get('USR_ID'),
                "CUST_UPDATE_DT" => date("Y-m-d")
            ];
            $return = $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $custId)->update($data); 

            if($return){
                $response = $this->checkStatusUploadFiles('RTN',$custId,$resvid);
                $result = $this->responseJson("1","0",$return,$response);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444", 'unable to update customer data.', $return);
                echo json_encode($result);
            }
        }catch (\Exception $e){
            return $this->respond($e->getMessage());
        }
    }

    public function checkStatusUploadFiles($condi=null,$custId=null,$resvid=null){
        if($condi){
            $param = ['CUST_ID'=> $custId,'RESV_ID'=> $resvid];
        }else{
            $param = ['CUST_ID'=> $this->request->getPost("custid"),'RESV_ID'=> $this->request->getPost("resrid")];
        }

        $sql = "select ACCOMP_CUST_ID from FLXY_ACCOMPANY_PROFILE where ACCOMP_REF_RESV_ID = :RESV_ID:";
        $accompany_profiles = $this->Db->query($sql, $param)->getResultArray();  
        
        $cust_ids[] = $param['CUST_ID'];
        foreach($accompany_profiles as $accompany_profile){
            $cust_ids[] = $accompany_profile['ACCOMP_CUST_ID'];
        }

        $resv_id = $param['RESV_ID'];
        $response = [];

        foreach($cust_ids as $cust_id) {
            $data = [
                'CUST_ID' => $cust_id,
                'RESV_ID' => $param['RESV_ID'],
                'DOC_IS_VERIFY' => null,
                'VACC_IS_VERIFY' => null
            ];

            $sql = "select DOC_IS_VERIFY from FLXY_DOCUMENTS where DOC_CUST_ID = :CUST_ID: AND DOC_RESV_ID = :RESV_ID: AND DOC_FILE_TYPE = 'PROOF'";
            $res = $this->Db->query($sql, ['CUST_ID' => $cust_id, 'RESV_ID' => $resv_id])->getResultArray();
            if(count($res))
                $data['DOC_IS_VERIFY'] = $res[0]['DOC_IS_VERIFY'];

            $sql = "select VACC_IS_VERIFY from FLXY_VACCINE_DETAILS where VACC_CUST_ID = :CUST_ID: AND VACC_RESV_ID = :RESV_ID:";
            $res = $this->Db->query($sql, ['CUST_ID' => $cust_id, 'RESV_ID' => $resv_id])->getResultArray();
            if(count($res))
                $data['VACC_IS_VERIFY'] = $res[0]['VACC_IS_VERIFY'];

            $response[] = $data;
        }
         
        if($condi){
            return $response;
        }
        echo json_encode($response);
    }

    public function updateVaccineReport(){
        try{
            $rules = [
                'VACC_DETL' => ['label' => 'Vaccine Detail', 'rules' => 'required'],
                'VACC_LAST_DT' => ['label' => 'Vaccine Last Date', 'rules' => 'required'],
                'VACC_TYPE' => ['label' => 'Vaccine Name', 'rules' => 'required'],
                'VACC_ISSUED_COUNTRY' => ['label' => 'Vaccine issue country', 'rules' => 'required']
            ];

            if(empty($this->request->getPost("VACC_DOC_SAVED"))){
                $rules['files'] = [
                    'label' => 'vaccine certificate', 
                    'rules' => 'uploaded[files]', 'mime_in[files,image/png,image/jpg,image/jpeg,application/pdf]', 'max_size[files,50000]'
                ];
            }

            $validate = $this->validate($rules);


            if(!$validate){
                $validate = $this->validator->getErrors();

                $result = $this->responseJson(403, $validate);
                echo json_encode($result);
                exit;
            }

            $this->deleteSpecificVaccine();
            $fileNames='';
            $fileNm='';
            if(!empty($_FILES['files']['name'][0]) || !empty($this->request->getPost("VACC_DOC_SAVED"))){
                if(!empty($_FILES['files']['name'][0])){
                    $fileArry=$this->request->getFileMultiple('files');
                    foreach($fileArry as $key=>$file){ 
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'assets/Uploads/UserDocuments/vaccination',$newName);
                        $comma='';
                        if (isset($fileArry[$key+1])) {
                            $comma=',';
                        }
                        $fileNames.=$newName.$comma;
                    }
                        $savePath = $this->request->getPost("VACC_DOC_SAVED");
                        $comma = $savePath!='' ?  ',' : '';
                        $fileNames = $fileNames.$comma.$savePath;
                }else{
                    $fileNames = $this->request->getPost("VACC_DOC_SAVED");
                }
                $fileNamesArr = explode(",",$fileNames);
                $deleteImage = $this->request->getPost("DELETEIMAGE");
                $imageDele = explode(",",$deleteImage);
                // print_r($fileNamesArr);
                // print_r($imageDele);
                // exit;
                $fileArr = array_diff($fileNamesArr, $imageDele);
                $fileNm = implode(",",$fileArr);
            }
           
            $VACC_ID = $this->request->getPost("VACC_ID");
            $data = ["VACC_CUST_ID" => $this->request->getPost("VACC_CUST_ID"),
                "VACC_RESV_ID" => $this->request->getPost("VACC_RESV_ID"),
                "VACC_DETAILS" => $this->request->getPost("VACC_DETAILS"),
                "VACC_LAST_DT" => $this->request->getPost("VACC_LAST_DT"),
                "VACC_TYPE" => $this->request->getPost("VACC_TYPE"),
                "VACC_NAME" => $this->request->getPost("VACC_NAME"),
                "VACC_ISSUED_COUNTRY" => $this->request->getPost("VACC_ISSUED_COUNTRY"),
                // "VACC_IS_VERIFY" => $this->request->getPost("VACC_IS_VERIFY"),
                "VACC_IS_VERIFY" => 0,
                "VACC_FILE_PATH" => $fileNm,
                "VACC_CREATE_UID" => session()->get('USR_ID'),
                "VACC_CREATE_DT" => date("d-M-Y")
            ];
            $return = $this->Db->table('FLXY_VACCINE_DETAILS')->insert($data); 
            // $return = $this->Db->table('FLXY_VACCINE_DETAILS')->where('CUST_ID', $custId)->update($data); 
            if($return){
                $result = $this->responseJson("1", "success", $return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444", 'unable to insert/update', $return);
                echo json_encode($result);
            }
        }catch (\Exception $e){
            return $this->respond($e->getMessage());
        }
    }

    public function deleteSpecificVaccine(){
        $param = ['VACC_CUST_ID'=> $this->request->getPost("VACC_CUST_ID"),'VACC_RESV_ID'=> $this->request->getPost("VACC_RESV_ID")];

        if(null !== $this->request->getPost("VACC_CUST_ID") && null !== $this->request->getPost("VACC_RESV_ID"))
        {
            $sql="DELETE FROM FLXY_VACCINE_DETAILS WHERE VACC_CUST_ID=:VACC_CUST_ID: AND VACC_RESV_ID=:VACC_RESV_ID:";
            $response = $this->Db->query($sql,$param); 
        }
    }

    public function getVaccinUploadImages(){
        $param = ['VACC_CUST_ID'=> $this->request->getPost("VACC_CUST_ID"),'VACC_RESV_ID'=> $this->request->getPost("VACC_RESV_ID")];
        $sql = "SELECT * FROM FLXY_VACCINE_DETAILS WHERE VACC_CUST_ID = :VACC_CUST_ID: AND VACC_RESV_ID = :VACC_RESV_ID:";
        $output['vacc_details'] = $this->Db->query($sql,$param)->getResultArray();

        $sql = "SELECT CUST_ID, concat(CUST_FIRST_NAME, ' ', CUST_MIDDLE_NAME, ' ', CUST_LAST_NAME) as FULLNAME FROM FLXY_CUSTOMER where CUST_ID = :VACC_CUST_ID:";
        $output['customer_details'] = $this->Db->query($sql, $param)->getResultArray();

        echo json_encode($output);
    }

    function updateSignatureReserv(){
        $sysid = $this->request->getPost("DOC_RESV_ID");
        $customer_id = $this->request->getPost("customer_id");

        try{
            $signature = $this->request->getPost("signature");
            $modesignature = $this->request->getPost("modesignature");
            if($modesignature==1){
                $extension = explode('/', mime_content_type($signature))[1];
                $parts        = explode(";base64,", $signature);
                $imageparts   = explode("image/", $parts[0]);
                $imagetype    = $imageparts[1];
                $fileName = time();
                $signature=base64_decode($parts[1]);
                $fileNameExt = $fileName.'.'.$extension;
                file_put_contents('assets/Uploads/UserDocuments/signature/'.$fileNameExt,$signature);
            }else{
                $fileNameExt = basename($signature);   
            }
            $data = ["RESV_ETA" => $this->request->getPost("RESV_ETA"),
                "RESV_ACCP_TRM_CONDI" => $this->request->getPost("RESV_ACCP_TRM_CONDI"),
                // "RESV_SINGATURE_URL" => $fileNameExt,
                "RESV_UPDATE_UID" => session()->get('USR_ID'),
                "RESV_UPDATE_DT" => date("d-M-Y")
            ];
            $return = $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update($data);

            $document = $this->Documents->where('DOC_RESV_ID', $sysid)->where('DOC_FILE_TYPE', 'SIGN')->first();            
            if(!empty($document)) {
                $document['DOC_FILE_PATH'] = $fileNameExt;
                $document['DOC_UPDATE_UID'] = session()->get('USR_ID');
                $document['DOC_UPDATE_DT'] = date('Y-m-d');

                $this->Documents->save($document);
            }else{
                $data = [
                    'DOC_CUST_ID' => $customer_id,
                    'DOC_FILE_PATH' => $fileNameExt,
                    'DOC_FILE_TYPE' => 'SIGN',
                    'DOC_IS_VERIFY' => 0,
                    'DOC_CREATE_UID' => session()->get('USR_ID'),
                    'DOC_UPDATE_UID' => session()->get('USR_ID'),
                    'DOC_CREATE_DT' => date('Y-m-d'),
                    'DOC_UPDATE_DT' => date('Y-m-d'),
                    'DOC_RESV_ID' => $sysid,
                ];
                
                $this->Documents->insert($data);
            }


            if($return){
                $result = $this->responseJson("1","0",$return,$response='');
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444",$message,$return);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    function confirmPrecheckinStatus(){
        $sysid = $this->request->getPost("DOC_RESV_ID");

        $reservation_status = 'Pre Checked-In';
        if(isset($this->session->USR_ROLE_ID) && $this->session->USR_ROLE_ID == '1')
            $reservation_status = 'Checked-In';

        $data = [
            "RESV_STATUS" => $reservation_status,
            "RESV_UPDATE_UID" => session()->get('USR_ID'),
            "RESV_UPDATE_DT" => date("d-M-Y")
        ];
        $this->triggerReservationEmail($sysid,'QR');
        $return = $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update($data);
        $result = $this->responseJson("1","0",$return,$response='');

        $this->ReservationAssetRepository->attachAssetList(session('USR_ID'), $sysid);

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
                        WHERE RESV_ID = $sysid";
        $data['response'] = $this->Db->query($sql)->getResultArray();
        $data['branding_logo'] = brandingLogo();

        $file_name = "assets/reservation-registration-cards/RES{$sysid}-RC.pdf";
        $view = "Reservation/RegisterCard";
        generateInvoice($file_name, $view, $data);

        echo json_encode($result);
    }

    function reservationCheckin(){
        $data['data']=array('RESV_ID'=>'','CUST_ID'=>'','CUST_COUNTRY'=>'','CUST_STATE'=>'','RESV_ACCP_TRM_CONDI'=>'','RESV_ACCP_TRM_CONDI'=>'', 'CUST_NATIONALITY' => '', 'RESV_STATUS' => '');
        $data['condition']='SUCC';
        $data['session'] = $this->session;

        return view('WebCheckin/CheckInReservation',$data);
    }

    public function itemList()
        {
           
            $item_id = $this->request->getPost("item_id");
            
            if( $item_id > 0 )
                $sql = "SELECT * FROM FLXY_ITEM WHERE ITM_ID='$item_id' ";
            else 
                $sql = "SELECT * FROM FLXY_ITEM";          

            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Item </option>';
            $selected = "";
            foreach($response as $row){
                if($row['ITM_ID'] == $item_id){
                    $selected = "selected";
                }

                $option.= '<option value="'.$row['ITM_ID'].'"'.$selected.'>'.$row['ITM_CODE'].' | '.$row['ITM_NAME'].'</option>';
            }
            return $option;
        }

    public function updateInventory($reservationID)
        {          
            try {     
                if($reservationID != ''){
                    $where = array(
                        'RSV_ID'  => '',
                        'RSV_SESSION_ID' => session_id(),
                    );
                    $return = $this->Db->table('FLXY_RESERVATION_ITEM')->where("RSV_SESSION_ID",session_id())->update(array('RSV_ID'=>$reservationID));
                } 

                
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        public function itemInventoryClassList()
        {       
            $response = NULL;     
            $sql = "SELECT IT_CL_ID,IT_CL_CODE,IT_CL_DESC FROM FLXY_ITEM_CLASS";                 
            $responseCount = $this->Db->query($sql)->getNumRows();
            if($responseCount > 0) 
            $response = $this->Db->query($sql)->getResultArray(); 
            return $response;
        }


    public function ItemResources(){
        $response = NULL;
        $sql = "SELECT ITM_ID as id, ITM_NAME as item         
                FROM FLXY_ITEM WHERE ITM_STATUS = '1' ORDER BY ITM_ID ASC";       
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0)
        $response = $this->Db->query($sql)->getResultArray();

        return $response;
    }

    public function ItemCalendar(){
        $response = NULL;
        $items = array();
        
        $sql = "SELECT ITM_ID, ITM_QTY_IN_STOCK         
                FROM FLXY_ITEM ORDER BY ITM_ID ASC";       
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0){
            $response = $this->Db->query($sql)->getResultArray();
           
            $START        = $this->request->getPost('start');
            $END          = $this->request->getPost('end');
            $START_DATE   = explode('T',$START); 
            $END_DATE     = explode('T',$END);
            $start        = strtotime($START_DATE[0]);
            $end          = strtotime($END_DATE[0]);
            foreach($response as $row){
              
                $ITM_BEGIN_DATE    = $start;
                $ITM_END_DATE      = $end; 
                $DATEDIFF = (int)$end-(int)$start;
                $AVAILABLE_DAYS = round($DATEDIFF / (60 * 60 * 24));
                $ITM_DLY_QTY = 0;
                $ITEM_RESERVED = 0;
                for($i = 1; $i <= ($AVAILABLE_DAYS+1); $i++ ){
                    $values = [];
                    $sCurrentDate = gmdate("Y-m-d", strtotime("+$i day", $ITM_BEGIN_DATE)); 
                    $CurrentDate = strtotime($sCurrentDate); 
                    $values['id'] = $row['ITM_ID'];
                    $values['resourceId'] = $row['ITM_ID'];
                    $ITEM_RESERVED = $this->checkItemReserved($row['ITM_ID'],$sCurrentDate );                    
                    $ITM_REMAINING_STOCK = $row['ITM_QTY_IN_STOCK'] - $ITEM_RESERVED;
                    $ITM_QTY_IN_STOCK    = $row['ITM_QTY_IN_STOCK'];
                    $values['title'] = $ITM_REMAINING_STOCK.' | '.$ITM_QTY_IN_STOCK;
                    $values['start'] = $sCurrentDate;
                    $values['end'] = $sCurrentDate;
                    $items[] = $values;
                }   
            }
        }
      return $items;
    }

    public function checkItemDailyInventory($item_id, $sCurrentDate){
        $response = NULL;
        $ITM_DLY_QTY = 0;
        $sql = "SELECT ITM_DLY_QTY FROM FLXY_DAILY_INVENTORY WHERE ITM_ID = '$item_id' AND '$sCurrentDate' BETWEEN ITM_DLY_BEGIN_DATE AND ITM_DLY_END_DATE";                 
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0) {
            $response = $this->Db->query($sql)->getResultArray(); 
            foreach($response as $resp){
                $ITM_DLY_QTY += $resp['ITM_DLY_QTY'];
            }
        }
        return $ITM_DLY_QTY;       

    }

    public function checkItemReserved($item_id, $sCurrentDate){
        $response = NULL;
        $total_qty = 0;
        $sql = "SELECT RSV_ITM_QTY FROM FLXY_RESERVATION_ITEM WHERE RSV_ITM_ID = '$item_id' AND '$sCurrentDate' BETWEEN RSV_ITM_BEGIN_DATE AND RSV_ITM_END_DATE";                 
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0) {
            $response = $this->Db->query($sql)->getResultArray(); 
            foreach($response as $resp){
                $total_qty += $resp['RSV_ITM_QTY'];
            }
        }
        return $total_qty;
        

    }


    public function showInventoryItems()
    { 
        $mine = new ServerSideDataTable(); // loads and creates instance

        //Reservation ID 
        $RESV_ID = $this->request->getPost('RESV_ID');
        // $RESV_ID = '3070';
        $session_id = session_id();

        if($RESV_ID > 0)
        $init_cond = array("RSV_ID = " => $RESV_ID); 
        else
        $init_cond = array("RSV_SESSION_ID LIKE " => "'".$session_id."'", "RSV_ID = " => 0); 
        
        $tableName = 'FLXY_RESERVATION_ITEM INNER JOIN FLXY_ITEM ON FLXY_RESERVATION_ITEM.RSV_ITM_ID = FLXY_ITEM.ITM_ID';
        $columns = 'RSV_PRI_ID,ITM_CODE,ITM_NAME,RSV_ITM_ID,RSV_ITM_CL_ID,RSV_ITM_BEGIN_DATE,RSV_ITM_END_DATE,RSV_ITM_QTY';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);        
        
        exit;
    }

    public function verifyDocuments() {
        $customer_id = $this->request->getVar('customer_id');
        $reservation_id = $this->request->getVar('reservation_id');

        $result = $this->ReservationRepository->verifyDocuments($customer_id, $reservation_id);
        return $this->respond($result);
    }
    
    public function transactionList()
    {       
        $response = NULL; 
        $option = '';
        $sql = "SELECT TR_CD_ID,TR_CD_CODE,TR_CD_DESC FROM FLXY_TRANSACTION_CODE WHERE TR_CD_STATUS = '1'";                 
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0) 
        $response = $this->Db->query($sql)->getResultArray(); 
           
        $selected = "";
                        
        $option='<option value="">Select Transaction Code</option>';
            
        foreach($response as $row){            
            $option.= '<option value="'.$row['TR_CD_ID'].'"'.$selected.'>'.$row['TR_CD_CODE'].' | '.$row['TR_CD_DESC'].'</option>';
        }
        return $option;
        
    }

    public function frequencyList()
    {       
        $response = NULL;     
        $sql = "SELECT ID,FREQ_NAME FROM FLXY_FREQUENCY";                 
        $responseCount = $this->Db->query($sql)->getNumRows();
        if($responseCount > 0) 
        $response = $this->Db->query($sql)->getResultArray(); 
        return $response;
    }


    public function showFixedCharge()
    { 
        $mine = new ServerSideDataTable(); // loads and creates instance
        //Reservation ID 
        $RESV_ID = $this->request->getPost('FIXD_CHRG_RESV_ID');
        $session_id = session_id();
        $init_cond = '';

        if($RESV_ID > 0)
        $init_cond = array("FIXD_CHRG_RESV_ID = " => $RESV_ID, "TR_CD_STATUS = " => 1);   
        
        $tableName = 'FLXY_FIXED_CHARGES INNER JOIN FLXY_TRANSACTION_CODE ON FLXY_FIXED_CHARGES.FIXD_CHRG_TRNCODE = FLXY_TRANSACTION_CODE.TR_CD_ID INNER JOIN FLXY_FREQUENCY ON FLXY_FIXED_CHARGES.FIXD_CHRG_FREQUENCY = FLXY_FREQUENCY.ID';
        $columns = 'FIXD_CHRG_ID,TR_CD_ID,TR_CD_CODE,TR_CD_DESC,FIXD_CHRG_QTY,FIXD_CHRG_AMT,FIXD_CHRG_FREQUENCY,FIXD_CHRG_BEGIN_DATE,FIXD_CHRG_END_DATE,FREQ_NAME';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);  
        
        exit;
    }

    public function showFixedChargeDetails()
    {
        $fixedChargeDetails = $this->getFixedChargeDetails($this->request->getPost('FIXD_CHRG_ID'));
        echo json_encode($fixedChargeDetails);
    }

    public function getFixedChargeDetails($FIXD_CHRG_ID = 0)
    {
        $param = ['SYSID' => $FIXD_CHRG_ID];
        $sql = "SELECT *           
                FROM dbo.FLXY_FIXED_CHARGES
                WHERE FIXD_CHRG_ID=:SYSID:";       

        $response = $this->Db->query($sql, $param)->getResultArray();
        return $response;
    }

    public function updateFixedCharges()
    {
        try {
            $FIXD_CHRG_WEEKLY       = '';
            $FIXD_CHRG_MONTHLY      = '';
            $FIXD_CHRG_QUARTERLY    = '';
            $FIXD_CHRG_YEARLY       = '';
            $FIXD_CHRG_ID           =  $this->request->getPost('FIXD_CHRG_ID');
            $FIXD_CHRG_RESV_ID      =  $this->request->getPost('FIXD_CHRG_RESV_ID');
            $FIXD_CHRG_TRNCODE      =  $this->request->getPost('FIXD_CHRG_TRNCODE');
            $FIXD_CHRG_AMT          =  $this->request->getPost('FIXD_CHRG_AMT');
            $FIXD_CHRG_QTY          =  $this->request->getPost('FIXD_CHRG_QTY');
            $FIXD_CHRG_FREQUENCY    =  $this->request->getPost('FIXD_CHRG_FREQUENCY'); 
            $FIXD_CHRG_BEGIN_DATE   =  $this->request->getPost('FIXD_CHRG_BEGIN_DATE');
            $FIXD_CHRG_END_DATE     =  $this->request->getPost('FIXD_CHRG_END_DATE');

            $FIXD_CHRG_BEGIN_DATE   =  ($FIXD_CHRG_BEGIN_DATE !='') ? date('Y-m-d',(strtotime($FIXD_CHRG_BEGIN_DATE))):'';
            if($FIXD_CHRG_FREQUENCY > 1)
                $FIXD_CHRG_END_DATE     =  ($FIXD_CHRG_END_DATE !='') ? date('Y-m-d',(strtotime($FIXD_CHRG_END_DATE))):'';
            else
                $FIXD_CHRG_END_DATE     =  date('Y-m-d',(strtotime($FIXD_CHRG_BEGIN_DATE)));
                
            $FIXD_DEPARTURE_UP      =  date('Y-m-d',(strtotime($this->request->getPost('FIXD_DEPARTURE_UP')))); 

            if($FIXD_CHRG_FREQUENCY == 3){
                $FIXD_CHRG_WEEKLY = $this->request->getPost('FIXD_CHRG_WEEKLY');

            } else if($FIXD_CHRG_FREQUENCY == 4){
                $FIXD_CHRG_MONTHLY = $this->request->getPost('FIXD_CHRG_MONTHLY');
            
            } else if($FIXD_CHRG_FREQUENCY == 5){
                $FIXD_CHRG_QUARTERLY = $this->request->getPost('FIXD_CHRG_QUARTERLY');
            } 
            else if($FIXD_CHRG_FREQUENCY == 6){
                $FIXD_CHRG_YEARLY = date('Y-m-d',(strtotime($this->request->getPost('FIXD_CHRG_YEARLY')))); 
            }            

            $data = [
                "FIXD_CHRG_RESV_ID"       => $FIXD_CHRG_RESV_ID,
                "FIXD_CHRG_TRNCODE"       => $FIXD_CHRG_TRNCODE,
                "FIXD_CHRG_QTY"           => $FIXD_CHRG_QTY,
                "FIXD_CHRG_AMT"           => $FIXD_CHRG_AMT,
                "FIXD_CHRG_FREQUENCY"     => $FIXD_CHRG_FREQUENCY,
                "FIXD_CHRG_BEGIN_DATE"    => $FIXD_CHRG_BEGIN_DATE,
                "FIXD_CHRG_END_DATE"      => $FIXD_CHRG_END_DATE,
                "FIXD_CHRG_WEEKLY"        => $FIXD_CHRG_WEEKLY,
                "FIXD_CHRG_MONTHLY"       => $FIXD_CHRG_MONTHLY,
                "FIXD_CHRG_QUARTERLY"     => $FIXD_CHRG_QUARTERLY,
                "FIXD_CHRG_YEARLY"        => $FIXD_CHRG_YEARLY                
                ];

            $rules = [  'FIXD_CHRG_TRNCODE' => ['label' => 'Transaction code', 'rules' => 'required'],
                        'FIXD_CHRG_FREQUENCY' => ['label' => 'Freequency', 'rules' => 'required'],
                        'FIXD_CHRG_BEGIN_DATE' => ['label' => 'Start Date', 'rules' => 'required|checkReservationStartDate[FIXD_CHRG_BEGIN_DATE]', 'errors' => ['checkReservationStartDate' => 'Start date cannot be greater than the Departure date']],                       
                        'FIXD_CHRG_END_DATE' => ['label' => 'End Date', 'rules' => 'compareFixedChargeDate[FIXD_CHRG_BEGIN_DATE]|checkReservationEndDate[FIXD_CHRG_END_DATE]', 'errors' => ['checkReservationEndDate' => 'End date cannot be greater than the Departure date','compareFixedChargeDate' => 'The End Date should be after Begin Date']],
                        'FIXD_CHRG_AMT' => ['label' => 'Amount', 'rules' => 'required'],
                        'FIXD_CHRG_QTY' => ['label' => 'Quantity', 'rules' => 'required'],
                        'FIXD_CHRG_YEARLY' => ['label' => 'Fixed Charge Yearly', 'rules' => 'checkReservationYearlyDate[FIXD_CHRG_YEARLY]', 'errors' => ['checkReservationYearlyDate' => 'Yearly Date cannot be greater than the Departure date']]
                        
                     ];

            // if($FIXD_CHRG_FREQUENCY == 6){
            //     $rules = ['FIXD_CHRG_YEARLY' => ['label' => 'Fixed Charge Yearly', 'rules' => 'checkReservationYearlyDate', 'errors' => ['checkReservationYearlyDate' => 'Date cannot be greater than the Departure date']]];
            // }

                   

            $validate = $this->validate($rules);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }
           
            $return = !empty($FIXD_CHRG_ID) ? $this->Db->table('FLXY_FIXED_CHARGES')->where('FIXD_CHRG_ID', $FIXD_CHRG_ID)->update($data) : $this->Db->table('FLXY_FIXED_CHARGES')->insert($data);

            $result = $return ? $this->responseJson("1", "0", $return, !empty($FIXD_CHRG_ID) ? $FIXD_CHRG_ID : $this->Db->insertID()) : $this->responseJson("-444", "db insert not successful", $return);


            if(!$return)
                $this->session->setFlashdata('error', 'There has been an error. Please try again.');
            else
            {
                if(empty($FIXD_CHRG_ID))
                    //$this->session->setFlashdata('success', 'The Package Code has been updated.');
                    //else
                    $this->session->setFlashdata('success', 'The new Item  has been added.');
            }
            echo json_encode($result);

        } catch (\Exception $e) {
            return $this->respond($e->getMessage());
        }
    }


    public function deleteFixedcharge()
    {
        $FIXD_CHRG_ID = $this->request->getPost('FIXD_CHRG_ID');

        try {
            $return = $this->Db->table('FLXY_FIXED_CHARGES')->delete(['FIXD_CHRG_ID' => $FIXD_CHRG_ID]); 
            $result = $return ? $this->responseJson("1", "0", $return) : $this->responseJson("-402", "Record not deleted");
            echo json_encode($result);
        } catch (\Exception $e) {
            return $this->respond($e->getMessage());
        }
        
    }

    public function getReservDetails()
    {
        $reservID = $this->request->getPost('reservID');
        try{           
            $response = NULL; 
            $result = NULL;   
            $sql = "SELECT RESV_ARRIVAL_DT,RESV_NIGHT,RESV_DEPARTURE, CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME) AS FULL_NAME, RESV_NO, RESV_STATUS  FROM FLXY_RESERVATION INNER JOIN FLXY_CUSTOMER ON RESV_NAME = CUST_ID WHERE RESV_ID = '".$reservID."'";                 
            $responseCount = $this->Db->query($sql)->getNumRows();
            if($responseCount > 0) {
                $response = $this->Db->query($sql)->getResultArray(); 
                foreach($response as $row){            
                    $result = ['RESV_ARRIVAL_DT' => $row['RESV_ARRIVAL_DT'],'RESV_NIGHT' => $row['RESV_NIGHT'],'RESV_DEPARTURE' => $row['RESV_DEPARTURE'],'FULL_NAME' => $row['FULL_NAME'], 'RESV_NO' => $row['RESV_NO'], 'RESV_STATUS' => $row['RESV_STATUS'] ];
                }
            }
          
            echo json_encode($result);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }    

    public function cancellationReasonList()
    {
        $search = null !== $this->request->getPost('search') && $this->request->getPost('search') != '' ? $this->request->getPost('search') : '';

        $sql = "SELECT CN_RS_ID, CN_RS_CODE, CN_RS_DESC
                FROM FLXY_CANCELLATION_REASONS WHERE CN_RS_STATUS = 1 ";

        if ($search != '') {
            $sql .= " AND CN_RS_CODE LIKE '%$search%'
                      OR CN_RS_DESC LIKE '%$search%'";
        }

        $response = $this->Db->query($sql)->getResultArray();

        $option = '<option value="">Choose an Option</option>';
        foreach ($response as $row) {
            $option .= '<option value="' . $row['CN_RS_ID'] . '">' . $row['CN_RS_CODE'] . ' | ' . $row['CN_RS_DESC'] . '</option>';
        }

        return $option;
    }

    public function insertResvCancelHistory()
    {
        try {
            $validate = $this->validate([
                'CN_RS_ID' => ['label' => 'Cancellation Reason', 'rules' => 'required'],
                'RESV_ID' => ['label' => 'Reservation ID', 'rules' => 'required'],                
            ]);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $current_date = date('Y-m-d H:i:s');
            $resv_id = trim($this->request->getPost('RESV_ID'));
            $cust_id = trim($this->request->getPost('CUST_ID'));
            //echo json_encode(print_r($_POST)); exit;

            $resv_sql = "SELECT RESV_STATUS FROM FLXY_RESERVATION WHERE RESV_ID=:SYSID:";                                      
            $resv_data = $this->Db->query($resv_sql,['SYSID'=> $resv_id])->getRowArray();

            $data = [
                "CN_RS_ID" => trim($this->request->getPost('CN_RS_ID')),
                "RESV_ID" => $resv_id,
                "RESV_STATUS" => $resv_data['RESV_STATUS'],
                "USR_ID" => session()->get('USR_ID'),
                "HIST_ACTION_DESCRIPTION" => trim($this->request->getPost('HIST_ACTION_DESCRIPTION')),
                "HIST_DATETIME" => $current_date,
            ];

            $return = $this->Db->table('FLXY_RESERVATION_CANCELLATION_HISTORY')->insert($data);

            if($return){
                $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $resv_id)
                                                    ->update(["RESV_STATUS" => "Cancelled",
                                                              "RESV_UPDATE_UID" => session()->get('USR_ID'),
                                                              "RESV_UPDATE_DT" => $current_date]); 

                $cust_sql = "SELECT TRIM(CONCAT_WS(' ', CUST_FIRST_NAME, CUST_MIDDLE_NAME, CUST_LAST_NAME)) CUST_FULL_NAME
                             FROM FLXY_CUSTOMER
                             WHERE CUST_ID=:SYSID:";
                                      
                $cust_data = $this->Db->query($cust_sql,['SYSID'=> $cust_id])->getRowArray();

                $log_action_desc = "CANCELLED Reservation of ".$cust_data['CUST_FULL_NAME']."
                                    <br/>Reason: ".trim($this->request->getPost('HIST_REASON'))."
                                    <br/>Date: ".date('d-M-Y g:i a');                                              
                addActivityLog(1, 1, $resv_id, $log_action_desc);
            }
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function reinstateReservation()
    {
        try {
            $validate = $this->validate([
                'RESV_ID' => ['label' => 'Reservation ID', 'rules' => 'required']                
            ]);
            
            if (!$validate) {
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402", $validate);
                echo json_encode($result);
                exit;
            }

            $current_date = date('Y-m-d H:i:s');
            $resv_id = trim($this->request->getPost('RESV_ID'));

            //Get last status prior to cancellation
            $resv_sql = "SELECT TOP (1) RESV_STATUS FROM FLXY_RESERVATION_CANCELLATION_HISTORY 
                         WHERE RESV_ID=:SYSID:";                                      
            $resv_data = $this->Db->query($resv_sql,['SYSID'=> $resv_id])->getRowArray();

            if($resv_data){
                $return = $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $resv_id)
                                                              ->update(["RESV_STATUS" => $resv_data['RESV_STATUS'],
                                                              "RESV_UPDATE_UID" => session()->get('USR_ID'),
                                                              "RESV_UPDATE_DT" => $current_date]); 

                $log_action_desc = "REINSTATED Reservation ".$resv_id." to status '".$resv_data['RESV_STATUS']."'
                                    <br/>Date: ".date('d-M-Y g:i a');                                              
                addActivityLog(1, 22, $resv_id, $log_action_desc);
            }
            
            $result = $return ? $this->responseJson("1", "0", $return, $response = '') : $this->responseJson("-444", "db insert not successful", $return);
            echo json_encode($result);
        } catch (Exception $e) {
            return $this->respond($e->errors());
        }
    }

    public function ResvCancelHistoryView()
    {
        $sysid = $this->request->getPost('sysid');

        $init_cond = array( "RESV_ID = " => "'$sysid'"); // Add condition for Reservation

        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = '( SELECT  HIST_ID, RESV_ID, TRIM(CONCAT_WS(\' \', USR_FIRST_NAME, USR_LAST_NAME)) USR_FULL_NAME,
                        CN_RS_DESC, CN_RS_CODE, HIST_ACTION_DESCRIPTION, FORMAT(HIST_DATETIME,\'dd-MMM-yyyy hh:mm:ss tt\')HIST_DATETIME 
                        FROM FLXY_RESERVATION_CANCELLATION_HISTORY
                        LEFT JOIN FlXY_USERS ON FlXY_USERS.USR_ID = FLXY_RESERVATION_CANCELLATION_HISTORY.USR_ID
                        LEFT JOIN FLXY_CANCELLATION_REASONS ON FLXY_CANCELLATION_REASONS.CN_RS_ID = FLXY_RESERVATION_CANCELLATION_HISTORY.CN_RS_ID
                        ) RESV_CANCEL_HISTORY';
        $columns = 'CN_RS_CODE,HIST_ACTION_DESCRIPTION,USR_FULL_NAME,CN_RS_DESC,RESV_ID,HIST_DATETIME';
        $mine->generate_DatatTable($tableName, $columns, $init_cond);
        exit;
    }


    public function RoomStatistics(){   
        $data['title'] = getMethodName();
        $data['session'] = $this->session;
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $itemLists = $this->itemList();    
        $data['itemLists'] = $itemLists;   
        $data['itemResources'] = $this->itemResources();                 
        $data['itemAvail'] = $this->ItemCalendar();
        $data['classList'] = $this->itemInventoryClassList();
        $data['FrequencyList'] = $this->frequencyList();  
        $data['toggleButton_javascript'] = toggleButton_javascript();
        $data['clearFormFields_javascript'] = clearFormFields_javascript();
        $data['blockLoader_javascript'] = blockLoader_javascript();

        $data['userList'] = geUsersList(); 

        $data['js_to_load'] = array("inventoryFormWizardNumbered.js","RoomPlan/FullCalendar/Core/main.min.js","RoomPlan/FullCalendar/Interaction/main.min.js", "RoomPlan/FullCalendar/Timeline/main.min.js", "RoomPlan/FullCalendar/ResourceCommon/main.min.js","RoomPlan/FullCalendar/ResourceTimeline/main.min.js");
        $data['css_to_load'] = array("RoomPlan/FullCalendar/Core/main.min.css", "RoomPlan/FullCalendar/Timeline/main.min.css", "RoomPlan/FullCalendar/ResourceTimeline/main.min.css");
        
        $data['RoomReservations'] = $this->getReservations(); 
        $data['RoomResources'] = $this->roomplanResources();

        $data['cancelReasons'] = $this->cancellationReasonList();

        $data['RESV_ID'] = null !== $this->request->getGet("RESV_ID") ? $this->request->getGet("RESV_ID") : null;
        $data['CUSTOMER_ID'] = null !== $this->request->getGet("RESV_ID") ? getValueFromTable('RESV_NAME',$this->request->getGet("RESV_ID"),'FLXY_RESERVATION') : null;


        //Check if RESV_ID exists in Customer table
        if($data['RESV_ID'] && !checkValueinTable('RESV_ID', $data['RESV_ID'], 'FLXY_RESERVATION'))
            return redirect()->to(base_url('reservation')); 

        return view('Reservation/RoomPlanTest', $data);
    }   
}