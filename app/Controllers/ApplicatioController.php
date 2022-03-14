<?php

namespace App\Controllers;
use  App\Libraries\ServerSideDataTable;
class ApplicatioController extends BaseController
{
    public $Db;
    public $session;
    public function __construct(){
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form']);
    }
    
    // public function index(){
    //     return view('Reservation');
    // }

    public function Reservation(){
        return view('Reservation');
    }

    public function blockList(){
        try{
            $search = $_POST['search'];
            $sql = "SELECT BLK_ID,BLK_NAME,BLK_CODE,BLK_START_DT,BLK_END_DT,BLK_STATUS FROM FLXY_BLOCK WHERE (BLK_NAME LIKE '%$search%' OR BLK_AGENT LIKE '%$search%' OR BLK_GROUP LIKE '%$search%' OR BLK_CODE LIKE '%$search%')";
            $response = $this->Db->query($sql)->getResultArray();
            $option='<option value="">Select Block</option>';
            foreach($response as $row){
                $description = $row['BLK_CODE'].' - '.$row['BLK_NAME'].' - '.$row['BLK_START_DT'].' - '.$row['BLK_END_DT'].' - '.$row['BLK_STATUS'];
                $option.= '<option value="'.$row['BLK_ID'].'">'.$description.'</option>';
            }
            echo $option;
        }catch (Exception $e){
            echo json_encode($e->errors());
        }
    }

    function deleteReservation(){
        $sysid = $_POST['sysid'];
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
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RESERVATION_VIEW';
        $columns = 'RESV_ID,RESV_ARRIVAL_DT,RESV_NIGHT,RESV_DEPARTURE,RESV_NO_F_ROOM,FULLNAME,RESV_FEATURE,RESV_PURPOSE_STAY';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
        // return view('Dashboard');
    }

    function editReservation(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RESV_ID,RESV_ARRIVAL_DT,RESV_NIGHT,RESV_ADULTS,RESV_CHILDREN,RESV_DEPARTURE,RESV_NO_F_ROOM,
        RESV_NAME,(CUST_FIRST_NAME+' '+CUST_LAST_NAME)RESV_NAME_DESC,RESV_MEMBER_TY,
        RESV_COMPANY,(SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ID=RESV_COMPANY)RESV_COMPANY_DESC,
        RESV_AGENT,(SELECT AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE AGN_ID=RESV_AGENT)RESV_AGENT_DESC,
        RESV_BLOCK,(SELECT BLK_NAME+' - '+BLK_CODE+' - '+BLK_START_DT+' - '+BLK_END_DT AS BLOCKDESC FROM FLXY_BLOCK WHERE BLK_ID=RESV_BLOCK)RESV_BLOCK_DESC,RESV_MEMBER_NO,RESV_CORP_NO,RESV_IATA_NO,RESV_CLOSED,RESV_DAY_USE,
        RESV_PSEUDO,RESV_RATE_CLASS,RESV_RATE_CATEGORY,RESV_RATE_CODE,RESV_ROOM_CLASS,RESV_FEATURE,RESV_PACKAGES,RESV_PURPOSE_STAY,RESV_STATUS,RESV_RM_TYPE,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RM_TYPE)RESV_RM_TYPE_DESC,
        (SELECT RM_DESC FROM FLXY_ROOM WHERE RM_NO=RESV_ROOM)RESV_ROOM_DESC,(SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RESV_RTC) RESV_RTC_DESC,
        RESV_ROOM,RESV_RATE,RESV_ETA,RESV_CO_TIME,RESV_RTC,RESV_FIXED_RATE,RESV_RESRV_TYPE,RESV_MARKET,RESV_SOURCE,RESV_ORIGIN,RESV_PAYMENT_TYPE,RESV_SPECIALS,RESV_COMMENTS,RESV_ITEM_INVT,RESV_BOKR_LAST,RESV_BOKR_FIRST,RESV_BOKR_EMAIL,RESV_BOKR_PHONE,RESV_CONFIRM_YN,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,
        (SELECT CNAME FROM COUNTRY WHERE ISO2=CUST_COUNTRY)CUST_COUNTRY_DESC,CUST_VIP,CUST_PHONE FROM FLXY_RESERVATION,FLXY_CUSTOMER WHERE RESV_ID=:SYSID: AND CUST_ID=RESV_NAME";
        $response = $this->Db->query($sql,$param)->getResultArray();
        $response = $this->removeNullJson($response);
        echo json_encode($response);
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
                'RESV_ARRIVAL_DT' => 'required',
                'RESV_DEPARTURE' => 'required',
                'RESV_NIGHT' => 'required',
                'RESV_ADULTS' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RESV_ID'];
            if(!empty($sysid)){
            $data = ["RESV_ARRIVAL_DT" => $_POST["RESV_ARRIVAL_DT"],
                "RESV_NIGHT" => $_POST["RESV_NIGHT"],
                "RESV_ADULTS" => $_POST["RESV_ADULTS"],
                "RESV_CHILDREN" => $_POST["RESV_CHILDREN"],
                "RESV_DEPARTURE" => $_POST["RESV_DEPARTURE"],
                "RESV_NO_F_ROOM" => $_POST["RESV_NO_F_ROOM"],
                "RESV_NAME" => $_POST["RESV_NAME"],
                "RESV_MEMBER_TY" => $_POST["RESV_MEMBER_TY"],
                "RESV_COMPANY" => $_POST["RESV_COMPANY"],
                "RESV_AGENT" => $_POST["RESV_AGENT"],
                "RESV_BLOCK" => $_POST["RESV_BLOCK"],
                "RESV_MEMBER_NO" => $_POST["RESV_MEMBER_NO"],
                "RESV_CORP_NO" => $_POST["RESV_CORP_NO"],
                "RESV_IATA_NO" => $_POST["RESV_IATA_NO"],
                // "RESV_CLOSED" => $_POST["RESV_CLOSED"],
                // "RESV_DAY_USE" => $_POST["RESV_DAY_USE"],
                // "RESV_PSEUDO" => $_POST["RESV_PSEUDO"],
                "RESV_RATE_CLASS" => $_POST["RESV_RATE_CLASS"],
                "RESV_RATE_CATEGORY" => $_POST["RESV_RATE_CATEGORY"],
                "RESV_RATE_CODE" => $_POST["RESV_RATE_CODE"],
                "RESV_ROOM_CLASS" => $_POST["RESV_ROOM_CLASS"],
                "RESV_FEATURE" => $_POST["RESV_FEATURE"],
                "RESV_PACKAGES" => $_POST["RESV_PACKAGES"],
                "RESV_PURPOSE_STAY" => $_POST["RESV_PURPOSE_STAY"],
                "RESV_STATUS" => $_POST["RESV_STATUS"],
                "RESV_RM_TYPE" => $_POST["RESV_RM_TYPE"],
                "RESV_ROOM" => $_POST["RESV_ROOM"],
                "RESV_RATE" => $_POST["RESV_RATE"],
                "RESV_ETA" => $_POST["RESV_ETA"],
                "RESV_CO_TIME" => $_POST["RESV_CO_TIME"],
                "RESV_RTC" => $_POST["RESV_RTC"],
                "RESV_FIXED_RATE" => (empty($_POST["RESV_FIXED_RATE"]) ? '' : $_POST["RESV_FIXED_RATE"]),
                "RESV_RESRV_TYPE" => $_POST["RESV_RESRV_TYPE"],
                "RESV_MARKET" => $_POST["RESV_MARKET"],
                "RESV_SOURCE" => $_POST["RESV_SOURCE"],
                "RESV_ORIGIN" => $_POST["RESV_ORIGIN"],
                "RESV_PAYMENT_TYPE" => $_POST["RESV_PAYMENT_TYPE"],
                "RESV_SPECIALS" => $_POST["RESV_SPECIALS"],
                "RESV_COMMENTS" => $_POST["RESV_COMMENTS"],
                "RESV_ITEM_INVT" => $_POST["RESV_ITEM_INVT"],
                "RESV_BOKR_LAST" => $_POST["RESV_BOKR_LAST"],
                "RESV_BOKR_FIRST" => $_POST["RESV_BOKR_FIRST"],
                "RESV_BOKR_EMAIL" => $_POST["RESV_BOKR_EMAIL"],
                "RESV_BOKR_PHONE" => $_POST["RESV_BOKR_PHONE"],
                "RESV_CONFIRM_YN" => $_POST["RESV_CONFIRM_YN"],
                "RESV_UPDATE_UID" => $this->session->name,
                "RESV_UPDATE_DT" => date("d-M-Y")
                ];
                $return = $this->Db->table('FLXY_RESERVATION')->where('RESV_ID', $sysid)->update($data); 
            }else{
                $data = ["RESV_ARRIVAL_DT" => $_POST["RESV_ARRIVAL_DT"],
                    "RESV_NIGHT" => $_POST["RESV_NIGHT"],
                    "RESV_ADULTS" => $_POST["RESV_ADULTS"],
                    "RESV_CHILDREN" => $_POST["RESV_CHILDREN"],
                    "RESV_DEPARTURE" => $_POST["RESV_DEPARTURE"],
                    "RESV_NO_F_ROOM" => $_POST["RESV_NO_F_ROOM"],
                    "RESV_NAME" => $_POST["RESV_NAME"],
                    "RESV_MEMBER_TY" => $_POST["RESV_MEMBER_TY"],
                    "RESV_COMPANY" => $_POST["RESV_COMPANY"],
                    "RESV_AGENT" => $_POST["RESV_AGENT"],
                    "RESV_BLOCK" => $_POST["RESV_BLOCK"],
                    "RESV_MEMBER_NO" => $_POST["RESV_MEMBER_NO"],
                    "RESV_CORP_NO" => $_POST["RESV_CORP_NO"],
                    "RESV_IATA_NO" => $_POST["RESV_IATA_NO"],
                    // "RESV_CLOSED" => $_POST["RESV_CLOSED"],
                    // "RESV_DAY_USE" => $_POST["RESV_DAY_USE"],
                    // "RESV_PSEUDO" => $_POST["RESV_PSEUDO"],
                    "RESV_RATE_CLASS" => $_POST["RESV_RATE_CLASS"],
                    "RESV_RATE_CATEGORY" => $_POST["RESV_RATE_CATEGORY"],
                    "RESV_RATE_CODE" => $_POST["RESV_RATE_CODE"],
                    "RESV_ROOM_CLASS" => $_POST["RESV_ROOM_CLASS"],
                    "RESV_FEATURE" => $_POST["RESV_FEATURE"],
                    "RESV_PACKAGES" => $_POST["RESV_PACKAGES"],
                    "RESV_PURPOSE_STAY" => $_POST["RESV_PURPOSE_STAY"],
                    "RESV_STATUS" => "PRE-CHECKIN",
                    "RESV_RM_TYPE" => $_POST["RESV_RM_TYPE"],
                    "RESV_ROOM" => $_POST["RESV_ROOM"],
                    "RESV_RATE" => $_POST["RESV_RATE"],
                    "RESV_ETA" => $_POST["RESV_ETA"],
                    "RESV_CO_TIME" => $_POST["RESV_CO_TIME"],
                    "RESV_RTC" => $_POST["RESV_RTC"],
                    "RESV_FIXED_RATE" => (empty($_POST["RESV_FIXED_RATE"]) ? '' : $_POST["RESV_FIXED_RATE"]),
                    "RESV_RESRV_TYPE" => $_POST["RESV_RESRV_TYPE"],
                    "RESV_MARKET" => $_POST["RESV_MARKET"],
                    "RESV_SOURCE" => $_POST["RESV_SOURCE"],
                    "RESV_ORIGIN" => $_POST["RESV_ORIGIN"],
                    "RESV_PAYMENT_TYPE" => $_POST["RESV_PAYMENT_TYPE"],
                    "RESV_SPECIALS" => $_POST["RESV_SPECIALS"],
                    "RESV_COMMENTS" => $_POST["RESV_COMMENTS"],
                    "RESV_ITEM_INVT" => $_POST["RESV_ITEM_INVT"],
                    "RESV_BOKR_LAST" => $_POST["RESV_BOKR_LAST"],
                    "RESV_BOKR_FIRST" => $_POST["RESV_BOKR_FIRST"],
                    "RESV_BOKR_EMAIL" => $_POST["RESV_BOKR_EMAIL"],
                    "RESV_BOKR_PHONE" => $_POST["RESV_BOKR_PHONE"],
                    "RESV_CONFIRM_YN" => $_POST["RESV_CONFIRM_YN"],
                    "RESV_CREATE_UID" => $this->session->name,
                    "RESV_CREATE_DT" => date("d-M-Y")
                ];
                $return = $this->Db->table('FLXY_RESERVATION')->insert($data); 
            }
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson("-444","db insert not success");
                echo json_encode($result);
            }
            // $return = $this->Db->query($sql);
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    public function updateCustomerShortData(){
        
    }

    function test(){
        return view('TestFiles');
    }

    function countryList(){
        $response = $this->Db->table('COUNTRY')->select('iso2,cname')->get()->getResultArray();
        $option='<option value="">Select Country</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['iso2'].'">'.$row['cname'].'</option>';
        }
        echo $option;
    }

    function stateList(){
        $ccode = $_POST['ccode'];
        $sql = "SELECT sname,state_code FROM STATE WHERE COUNTRY_CODE='$ccode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select State</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['state_code'].'">'.$row['sname'].'</option>';
        }
        echo $option;
    }

    function cityList(){
        $ccode = $_POST['ccode'];
        $scode = $_POST['scode'];
        $sql = "SELECT ctname,id FROM CITY WHERE COUNTRY_CODE='$ccode' AND STATE_CODE='$scode'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select City</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['id'].'">'.$row['ctname'].'</option>';
        }
        echo $option;
    }

    function insertCustomer(){
        try{
            $validate = $this->validate([
                'CUST_FIRST_NAME' => 'required',
                'CUST_EMAIL' => 'required|valid_email',
                'CUST_MOBILE' => 'required',
                'CUST_ADDRESS_1' => 'required',
                'CUST_COUNTRY' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['CUST_ID'];
            if(!empty($sysid)){
                $data = ["CUST_FIRST_NAME" => $_POST["CUST_FIRST_NAME"],
                    "CUST_MIDDLE_NAME" => $_POST["CUST_MIDDLE_NAME"],
                    "CUST_LAST_NAME" => $_POST["CUST_LAST_NAME"],
                    "CUST_LANG" => $_POST["CUST_LANG"],
                    "CUST_TITLE" => $_POST["CUST_TITLE"],
                    "CUST_DOB" => $_POST["CUST_DOB"],
                    "CUST_PASSPORT" => $_POST["CUST_PASSPORT"],
                    "CUST_ADDRESS_1" => $_POST["CUST_ADDRESS_1"],
                    "CUST_ADDRESS_2" => $_POST["CUST_ADDRESS_2"],
                    "CUST_ADDRESS_3" => $_POST["CUST_ADDRESS_3"],
                    "CUST_COUNTRY" => $_POST["CUST_COUNTRY"],
                    "CUST_STATE" => $_POST["CUST_STATE"],
                    "CUST_CITY" => $_POST["CUST_CITY"],
                    "CUST_EMAIL" => $_POST["CUST_EMAIL"],
                    "CUST_MOBILE" => $_POST["CUST_MOBILE"],
                    "CUST_PHONE" => $_POST["CUST_PHONE"],
                    "CUST_CLIENT_ID" => $_POST["CUST_CLIENT_ID"],
                    "CUST_POSTAL_CODE" => $_POST["CUST_POSTAL_CODE"],
                    "CUST_VIP" => $_POST["CUST_VIP"],
                    "CUST_NATIONALITY" => $_POST["CUST_NATIONALITY"],
                    "CUST_BUS_SEGMENT" => $_POST["CUST_BUS_SEGMENT"],
                    "CUST_COMMUNICATION" => $_POST["CUST_COMMUNICATION"],
                    "CUST_COMMUNICATION_DESC" => $_POST["CUST_COMMUNICATION_DESC"],
                    "CUST_ACTIVE" => $_POST["CUST_ACTIVE"],
                    "CUST_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $sysid)->update($data); 
            }else{
                $data = ["CUST_FIRST_NAME" => $_POST["CUST_FIRST_NAME"],
                    "CUST_MIDDLE_NAME" => $_POST["CUST_MIDDLE_NAME"],
                    "CUST_LAST_NAME" => $_POST["CUST_LAST_NAME"],
                    "CUST_LANG" => $_POST["CUST_LANG"],
                    "CUST_TITLE" => $_POST["CUST_TITLE"],
                    "CUST_DOB" => $_POST["CUST_DOB"],
                    "CUST_PASSPORT" => $_POST["CUST_PASSPORT"],
                    "CUST_ADDRESS_1" => $_POST["CUST_ADDRESS_1"],
                    "CUST_ADDRESS_2" => $_POST["CUST_ADDRESS_2"],
                    "CUST_ADDRESS_3" => $_POST["CUST_ADDRESS_3"],
                    "CUST_COUNTRY" => $_POST["CUST_COUNTRY"],
                    "CUST_STATE" => $_POST["CUST_STATE"],
                    "CUST_CITY" => $_POST["CUST_CITY"],
                    "CUST_EMAIL" => $_POST["CUST_EMAIL"],
                    "CUST_MOBILE" => $_POST["CUST_MOBILE"],
                    "CUST_PHONE" => $_POST["CUST_PHONE"],
                    "CUST_CLIENT_ID" => $_POST["CUST_CLIENT_ID"],
                    "CUST_POSTAL_CODE" => $_POST["CUST_POSTAL_CODE"],
                    "CUST_VIP" => $_POST["CUST_VIP"],
                    "CUST_NATIONALITY" => $_POST["CUST_NATIONALITY"],
                    "CUST_BUS_SEGMENT" => $_POST["CUST_BUS_SEGMENT"],
                    "CUST_COMMUNICATION" => $_POST["CUST_COMMUNICATION"],
                    "CUST_COMMUNICATION_DESC" => $_POST["CUST_COMMUNICATION_DESC"],
                    "CUST_ACTIVE" => $_POST["CUST_ACTIVE"],
                    "CUST_CREATE_DT" => date("d-M-Y")
                ];
                $return = $this->Db->table('FLXY_CUSTOMER')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $_POST["CUST_FIRST_NAME"].' '.$_POST["CUST_LAST_NAME"];
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

    function deleteCustomer(){
        $sysid = $_POST['sysid'];
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

    public function Customer(){
        return view('Customer');
    }

    public function customerView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_CUSTOMER';
        $columns = 'CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_PASSPORT,CUST_COUNTRY,CUST_EMAIL,CUST_MOBILE,CUST_CLIENT_ID';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
        // return view('Dashboard');
    }

    function editCustomer(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT CUST_ID,CUST_FIRST_NAME,CUST_MIDDLE_NAME,CUST_LAST_NAME,CUST_LANG,CUST_TITLE,CUST_DOB,CUST_PASSPORT,CUST_ADDRESS_1,CUST_ADDRESS_2,CUST_ADDRESS_3,
        CUST_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=CUST_COUNTRY) CUST_COUNTRY_DESC
        ,CUST_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=CUST_STATE AND COUNTRY_CODE=CUST_COUNTRY) CUST_STATE_DESC
        ,CUST_CITY,(SELECT ctname FROM CITY WHERE ID=CUST_CITY) CUST_CITY_DESC
        ,CUST_EMAIL,CUST_MOBILE,CUST_PHONE,CUST_CLIENT_ID,CUST_POSTAL_CODE,CUST_VIP,CUST_NATIONALITY,CUST_BUS_SEGMENT,CUST_ACTIVE ,CUST_COMMUNICATION,CUST_COMMUNICATION_DESC FROM FLXY_CUSTOMER WHERE CUST_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
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
        $param = ['SYSID'=> $_POST['custId']];
        $sql = "SELECT CUST_ID,CUST_FIRST_NAME,CUST_TITLE,CUST_COUNTRY,CUST_VIP,CUST_PHONE FROM FLXY_CUSTOMER WHERE CUST_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function customerList(){
        try{
            $search = $_POST['search'];
            $sql = "SELECT CUST_FIRST_NAME+' '+CUST_LAST_NAME AS FULLNAME,CUST_ID FROM FLXY_CUSTOMER WHERE (CUST_FIRST_NAME LIKE '%$search%' OR CUST_MIDDLE_NAME LIKE '%$search%' OR CUST_LAST_NAME LIKE '%$search%')";
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
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5,$respon6,$respon7];
        echo json_encode($data);
    }

    
    function insertCompany(){
        try{
            $validate = $this->validate([
                'COM_ACCOUNT' => 'required|min_length[3]',
                'COM_CONTACT_EMAIL' => 'required|valid_email',
                'COM_COUNTRY' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['COM_ID'];
            if(!empty($sysid)){
                $data = ["COM_ACCOUNT" => $_POST["COM_ACCOUNT"],
                    "COM_ADDRESS1" => $_POST["COM_ADDRESS1"],
                    "COM_ADDRESS2" => $_POST["COM_ADDRESS2"],
                    "COM_ADDRESS3" => $_POST["COM_ADDRESS3"],
                    "COM_COUNTRY" => $_POST["COM_COUNTRY"],
                    "COM_STATE" => $_POST["COM_STATE"],
                    "COM_CITY" => $_POST["COM_CITY"],
                    "COM_POSTAL" => $_POST["COM_POSTAL"],
                    "COM_CONTACT_FR" => $_POST["COM_CONTACT_FR"],
                    "COM_CONTACT_LT" => $_POST["COM_CONTACT_LT"],
                    "COM_CONTACT_NO" => $_POST["COM_CONTACT_NO"],
                    "COM_CONTACT_EMAIL" => $_POST["COM_CONTACT_EMAIL"],
                    "COM_TYPE" => $_POST["COM_TYPE"],
                    "COM_CORP_ID" => $_POST["COM_CORP_ID"],
                    "COM_ACTIVE" => $_POST["COM_ACTIVE"],
                    "COM_COMMUNI_CODE" => $_POST["COM_COMMUNI_CODE"],
                    "COM_COMMUNI_DESC" => $_POST["COM_COMMUNI_DESC"],
                    "COM_UPDATE_UID" => $this->session->name,
                    "COM_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_COMPANY_PROFILE')->where('COM_ID', $sysid)->update($data); 
            }else{
                $data = ["COM_ACCOUNT" => $_POST["COM_ACCOUNT"],
                    "COM_ADDRESS1" => $_POST["COM_ADDRESS1"],
                    "COM_ADDRESS2" => $_POST["COM_ADDRESS2"],
                    "COM_ADDRESS3" => $_POST["COM_ADDRESS3"],
                    "COM_COUNTRY" => $_POST["COM_COUNTRY"],
                    "COM_STATE" => $_POST["COM_STATE"],
                    "COM_CITY" => $_POST["COM_CITY"],
                    "COM_POSTAL" => $_POST["COM_POSTAL"],
                    "COM_CONTACT_FR" => $_POST["COM_CONTACT_FR"],
                    "COM_CONTACT_LT" => $_POST["COM_CONTACT_LT"],
                    "COM_CONTACT_NO" => $_POST["COM_CONTACT_NO"],
                    "COM_CONTACT_EMAIL" => $_POST["COM_CONTACT_EMAIL"],
                    "COM_TYPE" => $_POST["COM_TYPE"],
                    "COM_CORP_ID" => $_POST["COM_CORP_ID"],
                    "COM_ACTIVE" => $_POST["COM_ACTIVE"],
                    "COM_COMMUNI_CODE" => $_POST["COM_COMMUNI_CODE"],
                    "COM_COMMUNI_DESC" => $_POST["COM_COMMUNI_DESC"],
                    "COM_CREATE_UID" => (!empty($_POST["COM_CREATE_UID"]) ? $_POST["COM_CREATE_UID"]:null),
                    "COM_CREATE_DT" => date("d-M-Y"),
                    "COM_UPDATE_UID" => (!empty($_POST["COM_UPDATE_UID"]) ? $_POST["COM_UPDATE_UID"]:null),
                    "COM_UPDATE_DT" => (!empty($_POST["COM_UPDATE_DT"]) ? $_POST["COM_UPDATE_DT"]:null)
                ];
                $return = $this->Db->table('FLXY_COMPANY_PROFILE')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $_POST["COM_ACCOUNT"];
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
                'COM_ACCOUNT' => 'required|min_length[3]',
                'COM_CONTACT_EMAIL' => 'required|valid_email',
                'COM_COUNTRY' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['AGN_ID'];
            if(!empty($sysid)){
                $data = ["AGN_ACCOUNT" => $_POST["COM_ACCOUNT"],
                    "AGN_ADDRESS1" => $_POST["COM_ADDRESS1"],
                    "AGN_ADDRESS2" => $_POST["COM_ADDRESS2"],
                    "AGN_ADDRESS3" => $_POST["COM_ADDRESS3"],
                    "AGN_COUNTRY" => $_POST["COM_COUNTRY"],
                    "AGN_STATE" => $_POST["COM_STATE"],
                    "AGN_CITY" => $_POST["COM_CITY"],
                    "AGN_POSTAL" => $_POST["COM_POSTAL"],
                    "AGN_TERRITORY" => $_POST["COM_TERRITORY"],
                    "AGN_IATA" => $_POST["COM_IATA"],
                    "AGN_CONTACT_EMAIL" => $_POST["COM_CONTACT_EMAIL"],
                    "AGN_TYPE" => $_POST["COM_TYPE"],
                    "AGN_CONTACT_NO" => $_POST["COM_CONTACT_NO"],
                    "AGN_ACTIVE" => $_POST["COM_ACTIVE"],
                    "AGN_COMMUNI_CODE" => $_POST["COM_COMMUNI_CODE"],
                    "AGN_COMMUNI_DESC" => $_POST["COM_COMMUNI_DESC"],
                    "AGN_UPDATE_UID" => $this->session->name,
                    "AGN_UPDATE_DT" => date("d-M-Y")
                 ];
                //  print_r($_POST);exit;
            $return = $this->Db->table('FLXY_AGENT_PROFILE')->where('AGN_ID', $sysid)->update($data); 
            }else{
                $data = ["AGN_ACCOUNT" => $_POST["COM_ACCOUNT"],
                    "AGN_ADDRESS1" => $_POST["COM_ADDRESS1"],
                    "AGN_ADDRESS2" => $_POST["COM_ADDRESS2"],
                    "AGN_ADDRESS3" => $_POST["COM_ADDRESS3"],
                    "AGN_COUNTRY" => $_POST["COM_COUNTRY"],
                    "AGN_STATE" => $_POST["COM_STATE"],
                    "AGN_CITY" => $_POST["COM_CITY"],
                    "AGN_POSTAL" => $_POST["COM_POSTAL"],
                    "AGN_TERRITORY" => $_POST["COM_TERRITORY"],
                    "AGN_IATA" => $_POST["COM_IATA"],
                    "AGN_CONTACT_NO" => $_POST["COM_CONTACT_NO"],
                    "AGN_CONTACT_EMAIL" => $_POST["COM_CONTACT_EMAIL"],
                    "AGN_TYPE" => $_POST["COM_TYPE"],
                    "AGN_ACTIVE" => $_POST["COM_ACTIVE"],
                    "AGN_COMMUNI_CODE" => $_POST["COM_COMMUNI_CODE"],
                    "AGN_COMMUNI_DESC" => $_POST["COM_COMMUNI_DESC"],
                    "AGN_CREATE_UID" => (!empty($_POST["COM_CREATE_UID"]) ? $_POST["COM_CREATE_UID"]:null),
                    "AGN_CREATE_DT" => date("d-M-Y"),
                    "AGN_UPDATE_UID" => (!empty($_POST["COM_UPDATE_UID"]) ? $_POST["COM_UPDATE_UID"]:null),
                    "AGN_UPDATE_DT" => (!empty($_POST["COM_UPDATE_DT"]) ? $_POST["COM_UPDATE_DT"]:null)
                ];
                $return = $this->Db->table('FLXY_AGENT_PROFILE')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $_POST["COM_ACCOUNT"];
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
        return view('Company');
    }

    public function companyView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_COMPANY_PROFILE';
        $columns = 'COM_ID,COM_ACCOUNT,COM_COUNTRY,COM_CONTACT_EMAIL,COM_CORP_ID,COM_CONTACT_NO';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function editCompany(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT COM_ID,COM_ACCOUNT,COM_ADDRESS1,COM_ADDRESS2,COM_ADDRESS3,
        COM_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=COM_COUNTRY) COM_COUNTRY_DESC
        ,COM_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=COM_STATE AND COUNTRY_CODE=COM_COUNTRY)COM_STATE_DESC
        ,COM_CITY,(SELECT ctname FROM CITY WHERE ID=COM_CITY)COM_CITY_DESC
        ,COM_POSTAL,COM_CONTACT_FR,COM_CONTACT_LT,COM_CONTACT_NO,COM_CONTACT_EMAIL,COM_TYPE,COM_CORP_ID,COM_ACTIVE,COM_COMMUNI_CODE,COM_COMMUNI_DESC FROM FLXY_COMPANY_PROFILE WHERE COM_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function editAgent(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT AGN_ID,AGN_ACCOUNT COM_ACCOUNT,AGN_ADDRESS1 COM_ADDRESS1,AGN_ADDRESS2 COM_ADDRESS2,AGN_ADDRESS3 COM_ADDRESS3,AGN_COUNTRY COM_COUNTRY,(SELECT cname FROM COUNTRY WHERE ISO2=AGN_COUNTRY) COM_COUNTRY_DESC
        ,AGN_STATE COM_STATE,(SELECT sname FROM STATE WHERE STATE_CODE=AGN_STATE AND COUNTRY_CODE=AGN_COUNTRY) COM_STATE_DESC,AGN_CITY COM_CITY,(SELECT ctname FROM CITY WHERE ID=AGN_CITY) COM_CITY_DESC
        ,AGN_POSTAL COM_POSTAL,AGN_TERRITORY COM_TERRITORY,AGN_IATA COM_IATA,AGN_CONTACT_NO COM_CONTACT_NO,AGN_CONTACT_EMAIL COM_CONTACT_EMAIL,AGN_TYPE COM_TYPE,AGN_ACTIVE COM_ACTIVE,AGN_COMMUNI_CODE COM_COMMUNI_CODE,AGN_COMMUNI_DESC COM_COMMUNI_DESC FROM FLXY_AGENT_PROFILE WHERE AGN_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function deleteCompany(){
        $sysid = $_POST['sysid'];
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
        return view('Agent');
    }

    public function AgentView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_AGENT_PROFILE';
        $columns = 'AGN_ID,AGN_ACCOUNT,AGN_COUNTRY,AGN_CONTACT_NO,AGN_CONTACT_EMAIL,AGN_TYPE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function deleteAgent(){
        $sysid = $_POST['sysid'];
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
        return view('Group');
    }
    public function GroupView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_GROUP';
        $columns = 'GRP_ID,GRP_NAME,GRP_COUNTRY,GRP_CONTACT_NO,GRP_EMAIL,GRP_ADDRESS1';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }
    
    function editGroup(){
        $param = ['SYSID'=> $_POST['sysid']];
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
                'GRP_NAME' => 'required|min_length[3]',
                'GRP_EMAIL' => 'required|valid_email',
                'GRP_COUNTRY' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['GRP_ID'];
            if(!empty($sysid)){
                $data = ["GRP_NAME" => $_POST["GRP_NAME"],
                    "GRP_LANG" => $_POST["GRP_LANG"],
                    "GRP_ADDRESS1" => $_POST["GRP_ADDRESS1"],
                    "GRP_ADDRESS2" => $_POST["GRP_ADDRESS2"],
                    "GRP_ADDRESS3" => $_POST["GRP_ADDRESS3"],
                    "GRP_COUNTRY" => $_POST["GRP_COUNTRY"],
                    "GRP_STATE" => $_POST["GRP_STATE"],
                    "GRP_CITY" => $_POST["GRP_CITY"],
                    "GRP_POSTAL" => $_POST["GRP_POSTAL"],
                    "GRP_CONTACT_NO" => $_POST["GRP_CONTACT_NO"],
                    "GRP_EMAIL" => $_POST["GRP_EMAIL"],
                    "GRP_VIP" => $_POST["GRP_VIP"],
                    "GRP_CURR" => $_POST["GRP_CURR"],
                    "GRP_COMMUNI_CODE" => $_POST["GRP_COMMUNI_CODE"],
                    "GRP_COMMUNI_DESC" => $_POST["GRP_COMMUNI_DESC"],
                    "GRP_NOTES" => $_POST["GRP_NOTES"],
                    "GRP_ACTIVE" => $_POST["GRP_ACTIVE"],
                    "GRP_UPDATE_UID" => $this->session->name,
                    "GRP_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_GROUP')->where('GRP_ID', $sysid)->update($data); 
            }else{
                $data = ["GRP_NAME" => $_POST["GRP_NAME"],
                    "GRP_LANG" => $_POST["GRP_LANG"],
                    "GRP_ADDRESS1" => $_POST["GRP_ADDRESS1"],
                    "GRP_ADDRESS2" => $_POST["GRP_ADDRESS2"],
                    "GRP_ADDRESS3" => $_POST["GRP_ADDRESS3"],
                    "GRP_COUNTRY" => $_POST["GRP_COUNTRY"],
                    "GRP_STATE" => $_POST["GRP_STATE"],
                    "GRP_CITY" => $_POST["GRP_CITY"],
                    "GRP_POSTAL" => $_POST["GRP_POSTAL"],
                    "GRP_CONTACT_NO" => $_POST["GRP_CONTACT_NO"],
                    "GRP_EMAIL" => $_POST["GRP_EMAIL"],
                    "GRP_VIP" => $_POST["GRP_VIP"],
                    "GRP_CURR" => $_POST["GRP_CURR"],
                    "GRP_COMMUNI_CODE" => $_POST["GRP_COMMUNI_CODE"],
                    "GRP_COMMUNI_DESC" => $_POST["GRP_COMMUNI_DESC"],
                    "GRP_NOTES" => $_POST["GRP_NOTES"],
                    "GRP_ACTIVE" => $_POST["GRP_ACTIVE"],
                    "GRP_CREATE_UID" => $this->session->name,
                    "GRP_CREATE_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_GROUP')->insert($data); 
            }
            if($return){
                if(empty($sysid)){
                    $fullname = $_POST["GRP_NAME"];
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
        $sysid = $_POST['sysid'];
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
        return view('BlockView');
    }
    public function BlockView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_BLOCK';
        $columns = 'BLK_ID,BLK_CODE,BLK_NAME,BLK_START_DT,BLK_END_DT,BLK_STATUS,BLK_RESER_TYPE,BLK_RESER_METHOD';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function companyList(){
        $search = $_POST['search'];
        $sql = "SELECT COM_ID,COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE COM_ACCOUNT like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Company</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['COM_ID'].'">'.$row['COM_ACCOUNT'].'</option>';
        }
        echo $option;
    }

    public function agentList(){
        $search = $_POST['search'];
        $sql = "SELECT AGN_ID,AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE AGN_ACCOUNT like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Agent</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['AGN_ID'].'">'.$row['AGN_ACCOUNT'].'</option>';
        }
        echo $option;
    }

    public function groupList(){
        $search = $_POST['search'];
        $sql = "SELECT GRP_ID,GRP_NAME FROM FLXY_GROUP WHERE GRP_NAME like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Group</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['GRP_ID'].'">'.$row['GRP_NAME'].'</option>';
        }
        echo $option;
    }

    public function getSupportingblkLov(){
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='BLK_MARKET'";
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='COM_SOURCE'";
        $respon2 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2];
        echo json_encode($data);
    }

    function insertBlock(){
        try{
            $validate = $this->validate([
                'BLK_GROUP' => 'required',
                'BLK_NAME' => 'required',
                'BLK_CODE' => 'required',
                'BLK_START_DT' => 'required',
                'BLK_END_DT' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['BLK_ID'];
            if(!empty($sysid)){
                $data = ["BLK_COMP" => $_POST["BLK_COMP"],
                    "BLK_AGENT" => $_POST["BLK_AGENT"],
                    "BLK_GROUP" => $_POST["BLK_GROUP"],
                    "BLK_NAME" => $_POST["BLK_NAME"],
                    "BLK_START_DT" => $_POST["BLK_START_DT"],
                    "BLK_NIGHT" => $_POST["BLK_NIGHT"],
                    "BLK_END_DT" => $_POST["BLK_END_DT"],
                    "BLK_CODE" => $_POST["BLK_CODE"],
                    "BLK_STATUS" => $_POST["BLK_STATUS"],
                    "BLK_RESER_TYPE" => $_POST["BLK_RESER_TYPE"],
                    "BLK_MARKET" => $_POST["BLK_MARKET"],
                    "BLK_SOURCE" => $_POST["BLK_SOURCE"],
                    "BLK_ELASTIC" => $_POST["BLK_ELASTIC"],
                    "BLK_CUTOFF_DAYS" => $_POST["BLK_CUTOFF_DAYS"],
                    "BLK_CUTOFF_DT" => $_POST["BLK_CUTOFF_DT"],
                    "BLK_RESER_METHOD" => $_POST["BLK_RESER_METHOD"],
                    "BLK_RATE_CODE" => $_POST["BLK_RATE_CODE"],
                    "BLK_PACKAGE" => $_POST["BLK_PACKAGE"],
                    "BLK_UPDATE_UID" => $this->session->name,
                    "BLK_UPDATE_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_BLOCK')->where('BLK_ID', $sysid)->update($data); 
            }else{
                $data = ["BLK_COMP" => $_POST["BLK_COMP"],
                    "BLK_AGENT" => $_POST["BLK_AGENT"],
                    "BLK_GROUP" => $_POST["BLK_GROUP"],
                    "BLK_NAME" => $_POST["BLK_NAME"],
                    "BLK_START_DT" => $_POST["BLK_START_DT"],
                    "BLK_NIGHT" => $_POST["BLK_NIGHT"],
                    "BLK_END_DT" => $_POST["BLK_END_DT"],
                    "BLK_CODE" => $_POST["BLK_CODE"],
                    "BLK_STATUS" => $_POST["BLK_STATUS"],
                    "BLK_RESER_TYPE" => $_POST["BLK_RESER_TYPE"],
                    "BLK_MARKET" => $_POST["BLK_MARKET"],
                    "BLK_SOURCE" => $_POST["BLK_SOURCE"],
                    "BLK_ELASTIC" => $_POST["BLK_ELASTIC"],
                    "BLK_CUTOFF_DAYS" => $_POST["BLK_CUTOFF_DAYS"],
                    "BLK_CUTOFF_DT" => $_POST["BLK_CUTOFF_DT"],
                    "BLK_RESER_METHOD" => $_POST["BLK_RESER_METHOD"],
                    "BLK_RATE_CODE" => $_POST["BLK_RATE_CODE"],
                    "BLK_PACKAGE" => $_POST["BLK_PACKAGE"],
                    "BLK_CREATE_UID" => $this->session->name,
                    "BLK_CREATE_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_BLOCK')->insert($data); 
            }
            if($return){
                // if(empty($sysid)){
                //     $fullname = $_POST["GRP_NAME"];
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT BLK_ID,
        BLK_COMP,(SELECT COM_ACCOUNT FROM FLXY_COMPANY_PROFILE WHERE BLK_COMP=COM_ID)BLK_COMP_DESC,
        BLK_AGENT,(SELECT AGN_ACCOUNT FROM FLXY_AGENT_PROFILE WHERE BLK_AGENT=AGN_ID)BLK_AGENT_DESC,
        BLK_GROUP,(SELECT GRP_NAME FROM FLXY_GROUP WHERE BLK_GROUP=GRP_ID)BLK_GROUP_DESC,
        BLK_NAME,BLK_START_DT,BLK_NIGHT,BLK_CODE,BLK_END_DT,BLK_STATUS,BLK_RESER_TYPE,BLK_MARKET,BLK_SOURCE,BLK_ELASTIC,BLK_CUTOFF_DAYS,BLK_CUTOFF_DT,BLK_RESER_METHOD,BLK_RATE_CODE,BLK_PACKAGE FROM FLXY_BLOCK WHERE BLK_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    function deleteBlock(){
        $sysid = $_POST['sysid'];
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

    public function room(){
        return view('RoomView');
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
                'RM_NO' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RM_ID'];
            $RM_FEATURE = $_POST["RM_FEATURE"];
            $RM_FEATURE = implode(",",$RM_FEATURE);
            if(!empty($sysid)){
                $data = ["RM_NO" => $_POST["RM_NO"],
                    "RM_CLASS" => $_POST["RM_CLASS"],
                    "RM_DESC" => $_POST["RM_DESC"],
                    "RM_TYPE" => $_POST["RM_TYPE"],
                    "RM_FEATURE" => $RM_FEATURE,
                    "RM_PUBLIC_RATE_CODE" => $_POST["RM_PUBLIC_RATE_CODE"],
                    "RM_PUBLIC_RATE_AMOUNT" => $_POST["RM_PUBLIC_RATE_AMOUNT"],
                    "RM_MAX_OCCUPANCY" => $_POST["RM_MAX_OCCUPANCY"],
                    "RM_DISP_SEQ" => $_POST["RM_DISP_SEQ"],
                    "RM_FLOOR_PREFERN" => $_POST["RM_FLOOR_PREFERN"],
                    "RM_SMOKING_PREFERN" => $_POST["RM_SMOKING_PREFERN"],
                    "RM_PHONE_NO" => $_POST["RM_PHONE_NO"],
                    "RM_SQUARE_UNITS" => $_POST["RM_SQUARE_UNITS"],
                    "RM_MEASUREMENT" => $_POST["RM_MEASUREMENT"],
                    "RM_HOUSKP_DY_SECTION" => $_POST["RM_HOUSKP_DY_SECTION"],
                    "RM_HOUSKP_EV_SECTION" => $_POST["RM_HOUSKP_EV_SECTION"],
                    "RM_STAYOVER_CR" => $_POST["RM_STAYOVER_CR"],
                    "RM_DEPARTURE_CR" => $_POST["RM_DEPARTURE_CR"],
                    "RM_UPDATED_UID" => $this->session->name,
                    "RM_UPDATED_DT" => date("d-M-Y")
                 ];
            $return = $this->Db->table('FLXY_ROOM')->where('RM_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_NO" => $_POST["RM_NO"],
                    "RM_CLASS" => $_POST["RM_CLASS"],
                    "RM_DESC" => $_POST["RM_DESC"],
                    "RM_TYPE" => $_POST["RM_TYPE"],
                    "RM_FEATURE" => $RM_FEATURE,
                    "RM_PUBLIC_RATE_CODE" => $_POST["RM_PUBLIC_RATE_CODE"],
                    "RM_PUBLIC_RATE_AMOUNT" => $_POST["RM_PUBLIC_RATE_AMOUNT"],
                    "RM_MAX_OCCUPANCY" => $_POST["RM_MAX_OCCUPANCY"],
                    "RM_DISP_SEQ" => $_POST["RM_DISP_SEQ"],
                    "RM_FLOOR_PREFERN" => $_POST["RM_FLOOR_PREFERN"],
                    "RM_SMOKING_PREFERN" => $_POST["RM_SMOKING_PREFERN"],
                    "RM_PHONE_NO" => $_POST["RM_PHONE_NO"],
                    "RM_SQUARE_UNITS" => $_POST["RM_SQUARE_UNITS"],
                    "RM_MEASUREMENT" => $_POST["RM_MEASUREMENT"],
                    "RM_HOUSKP_DY_SECTION" => $_POST["RM_HOUSKP_DY_SECTION"],
                    "RM_HOUSKP_EV_SECTION" => $_POST["RM_HOUSKP_EV_SECTION"],
                    "RM_STAYOVER_CR" => $_POST["RM_STAYOVER_CR"],
                    "RM_DEPARTURE_CR" => $_POST["RM_DEPARTURE_CR"],
                    "RM_CREATED_UID" => $this->session->name,
                    "RM_CREATED_DT" => date("d-M-Y")
                 ];
                $return = $this->Db->table('FLXY_ROOM')->insert($data); 
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RM_ID,RM_NO,RM_DESC,RM_CLASS,RM_TYPE,
        (SELECT RM_TY_DESC FROM FLXY_ROOM_TYPE WHERE RM_TY_CODE=RM_TYPE)RM_TYPE_DESC,
        (SELECT RM_FT_DESC FROM FLXY_ROOM_FEATURE WHERE RM_FT_CODE=RM_FEATURE)RM_FEATURE_DESC,
        RM_FEATURE,RM_PUBLIC_RATE_CODE,RM_PUBLIC_RATE_AMOUNT,RM_MAX_OCCUPANCY,RM_DISP_SEQ,RM_FLOOR_PREFERN,RM_SMOKING_PREFERN,RM_PHONE_NO,RM_SQUARE_UNITS,RM_MEASUREMENT,RM_HOUSKP_DY_SECTION,RM_HOUSKP_EV_SECTION,RM_STAYOVER_CR,RM_DEPARTURE_CR FROM FLXY_ROOM WHERE RM_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function roomList(){
        $search = $_POST['search'];
        $sql = "SELECT RM_NO,RM_DESC FROM FLXY_ROOM WHERE RM_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Room</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_NO'].'">'.$row['RM_NO'].' - '.$row['RM_DESC'].'</option>';
        }
        echo $option;
    }

    public function deleteRoom(){
        $sysid = $_POST['sysid'];
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
        return view('RoomClassView');
    }

    public function RoomClassView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_ROOM_CLASS';
        $columns = 'RM_CL_ID,RM_CL_CODE,RM_CL_DESC,RM_CL_FEATURE';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRoomClass(){
        try{
            $validate = $this->validate([
                'RM_CL_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RM_CL_ID'];
            if(!empty($sysid)){
                $data = ["RM_CL_CODE" => $_POST["RM_CL_CODE"],
                    "RM_CL_DESC" => $_POST["RM_CL_DESC"],
                    "RM_CL_DISPLY_SEQ" => $_POST["RM_CL_DISPLY_SEQ"],
                    "RM_CL_TOTAL_ROOM" => $_POST["RM_CL_TOTAL_ROOM"]
                 ];
            $return = $this->Db->table('FLXY_ROOM_CLASS')->where('RM_CL_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_CL_CODE" => $_POST["RM_CL_CODE"],
                    "RM_CL_DESC" => $_POST["RM_CL_DESC"],
                    "RM_CL_DISPLY_SEQ" => $_POST["RM_CL_DISPLY_SEQ"],
                    "RM_CL_TOTAL_ROOM" => $_POST["RM_CL_TOTAL_ROOM"]
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RM_CL_ID,RM_CL_CODE,RM_CL_DESC,RM_CL_DISPLY_SEQ,RM_CL_TOTAL_ROOM FROM FLXY_ROOM_CLASS WHERE RM_CL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomClass(){
        $sysid = $_POST['sysid'];
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
        return view('RoomTypeView');
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
                'RM_TY_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RM_TY_ID'];
            $RM_TY_FEATURE = $_POST['RM_TY_FEATURE'];
            $RM_TY_FEATURE = implode(",",$RM_TY_FEATURE);
            if(!empty($sysid)){
                $data = ["RM_TY_ROOM_CLASS" => $_POST["RM_TY_ROOM_CLASS"],
                    "RM_TY_CODE" => $_POST["RM_TY_CODE"],
                    "RM_TY_DESC" => $_POST["RM_TY_DESC"],
                    "RM_TY_FEATURE" => $RM_TY_FEATURE,
                    "RM_TY_TOTAL_ROOM" => $_POST["RM_TY_TOTAL_ROOM"],
                    "RM_TY_DISP_SEQ" => $_POST["RM_TY_DISP_SEQ"],
                    "RM_TY_PUBLIC_RATE_CODE" => $_POST["RM_TY_PUBLIC_RATE_CODE"],
                    "RM_TY_DEFUL_OCCUPANCY" => $_POST["RM_TY_DEFUL_OCCUPANCY"],
                    "RM_TY_MAX_OCCUPANCY" => $_POST["RM_TY_MAX_OCCUPANCY"],
                    "RM_TY_MAX_ADULTS" => $_POST["RM_TY_MAX_ADULTS"],
                    "RM_TY_MAX_CHILDREN" => $_POST["RM_TY_MAX_CHILDREN"],
                    "RM_TY_PSEUDO_RM" => $_POST["RM_TY_PSEUDO_RM"],
                    "RM_TY_HOUSEKEEPING" => $_POST["RM_TY_HOUSEKEEPING"],
                    "RM_TY_MIN_OCCUPANCY" => $_POST["RM_TY_MIN_OCCUPANCY"],
                    "RM_TY_SEND_T_INTERF" => $_POST["RM_TY_SEND_T_INTERF"],
                    "RM_TY_PUBLIC_RATE_AMT" => $_POST["RM_TY_PUBLIC_RATE_AMT"],
                    "RM_TY_ACTIVE_DT" => null,
                    "RM_TY_UPDATED_DT" => date("d-M-Y"),
                    "RM_TY_UPDATED_UID" => $this->session->name,
                 ];
            $return = $this->Db->table('FLXY_ROOM_TYPE')->where('RM_TY_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_TY_ROOM_CLASS" => $_POST["RM_TY_ROOM_CLASS"],
                    "RM_TY_CODE" => $_POST["RM_TY_CODE"],
                    "RM_TY_DESC" => $_POST["RM_TY_DESC"],
                    "RM_TY_FEATURE" => $RM_TY_FEATURE,
                    "RM_TY_TOTAL_ROOM" => $_POST["RM_TY_TOTAL_ROOM"],
                    "RM_TY_DISP_SEQ" => $_POST["RM_TY_DISP_SEQ"],
                    "RM_TY_PUBLIC_RATE_CODE" => $_POST["RM_TY_PUBLIC_RATE_CODE"],
                    "RM_TY_DEFUL_OCCUPANCY" => $_POST["RM_TY_DEFUL_OCCUPANCY"],
                    "RM_TY_MAX_OCCUPANCY" => $_POST["RM_TY_MAX_OCCUPANCY"],
                    "RM_TY_MAX_ADULTS" => $_POST["RM_TY_MAX_ADULTS"],
                    "RM_TY_MAX_CHILDREN" => $_POST["RM_TY_MAX_CHILDREN"],
                    "RM_TY_PSEUDO_RM" => $_POST["RM_TY_PSEUDO_RM"],
                    "RM_TY_HOUSEKEEPING" => $_POST["RM_TY_HOUSEKEEPING"],
                    "RM_TY_MIN_OCCUPANCY" => $_POST["RM_TY_MIN_OCCUPANCY"],
                    "RM_TY_SEND_T_INTERF" => $_POST["RM_TY_SEND_T_INTERF"],
                    "RM_TY_PUBLIC_RATE_AMT" => $_POST["RM_TY_PUBLIC_RATE_AMT"],
                    "RM_TY_ACTIVE_DT" => null,
                    "RM_TY_CREATE_DT" => date("d-M-Y"),
                    "RM_TY_CREATE_UID" => $this->session->name,
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
        $search = $_POST['search'];
        $sql = "SELECT RM_CL_CODE,RM_CL_DESC FROM FLXY_ROOM_CLASS WHERE RM_CL_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select RoomClass</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_CL_CODE'].'">'.$row['RM_CL_DESC'].'</option>';
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
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='RESV_TYPE'";
        $respon1 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='BLK_MARKET'";
        $respon2 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='COM_SOURCE'";
        $respon3 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='ORIGIN'";
        $respon4 = $this->Db->query($sql)->getResultArray();
        $sql = "SELECT LOV_SET_CODE CODE,LOV_SET_DESC DESCS FROM FLXY_LOV_SET WHERE LOV_SET_PARAMS='PAYMENT'";
        $respon5 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5];
        echo json_encode($data);
    }


    public function editRoomType(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RM_TY_ID,RM_TY_ROOM_CLASS,(SELECT RM_CL_DESC FROM FLXY_ROOM_CLASS WHERE RM_CL_CODE=RM_TY_ROOM_CLASS)RM_TY_ROOM_CLASS_DESC,RM_TY_CODE,RM_TY_DESC,RM_TY_FEATURE,RM_TY_TOTAL_ROOM,RM_TY_DISP_SEQ,RM_TY_PUBLIC_RATE_CODE,RM_TY_DEFUL_OCCUPANCY,RM_TY_MAX_OCCUPANCY,RM_TY_MAX_ADULTS,RM_TY_MAX_CHILDREN,RM_TY_PSEUDO_RM,RM_TY_HOUSEKEEPING,RM_TY_MIN_OCCUPANCY,RM_TY_SEND_T_INTERF,RM_TY_PUBLIC_RATE_AMT,RM_TY_ACTIVE_DT FROM FLXY_ROOM_TYPE WHERE RM_TY_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function roomTypeList(){
        $search = $_POST['search'];
        $sql = "SELECT RM_TY_ID,RM_TY_CODE,RM_TY_DESC,RM_TY_ROOM_CLASS FROM FLXY_ROOM_TYPE WHERE RM_TY_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Room Type</option>';
        foreach($response as $row){
            $option.= '<option data-desc="'.trim($row['RM_TY_DESC']).'" data-rmclass="'.trim($row['RM_TY_ROOM_CLASS']).'" value="'.$row['RM_TY_CODE'].'">'.$row['RM_TY_DESC'].'</option>';
        }
        echo $option;
    }

    public function featureList(){
        $search = $_POST['search'];
        $sql = "SELECT RM_FT_ID,RM_FT_CODE,RM_FT_DESC FROM FLXY_ROOM_FEATURE WHERE RM_FT_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Feature</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['RM_FT_CODE'].'">'.$row['RM_FT_DESC'].'</option>';
        }
        echo $option;
    }

    public function houseKeepSecionList(){
        $search = $_POST['search'];
        $sql = "SELECT SC_FL_CODE,SC_FL_DESC,SC_FL_ID FROM FLXY_SECTION WHERE SC_FL_DESC like '%$search%'";
        $response = $this->Db->query($sql)->getResultArray();
        $option='<option value="">Select Section</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['SC_FL_CODE'].'">'.$row['SC_FL_DESC'].'</option>';
        }
        echo $option;
    }

    public function deleteRoomType(){
        $sysid = $_POST['sysid'];
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
        return view('RoomFloorView');
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
                'RM_FL_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RM_FL_ID'];
            if(!empty($sysid)){
                $data = ["RM_FL_CODE" => $_POST["RM_FL_CODE"],
                    "RM_FL_DESC" => $_POST["RM_FL_DESC"],
                    "RM_FL_FEATURE" => ""
                 ];
            $return = $this->Db->table('FLXY_ROOM_FLOOR')->where('RM_FL_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_FL_CODE" => $_POST["RM_FL_CODE"],
                    "RM_FL_DESC" => $_POST["RM_FL_DESC"],
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RM_FL_ID,RM_FL_CODE,RM_FL_DESC,RM_FL_FEATURE FROM FLXY_ROOM_FLOOR WHERE RM_FL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomFloor(){
        $sysid = $_POST['sysid'];
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
        return view('RoomFeatureView');
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
                'RM_FT_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RM_FT_ID'];
            if(!empty($sysid)){
                $data = ["RM_FT_CODE" => $_POST["RM_FT_CODE"],
                    "RM_FT_DESC" => $_POST["RM_FT_DESC"],
                    "RM_FT_FEATURE" => $_POST["RM_FT_FEATURE"]
                 ];
            $return = $this->Db->table('FLXY_ROOM_FEATURE')->where('RM_FT_ID', $sysid)->update($data); 
            }else{
                $data = ["RM_FT_CODE" => $_POST["RM_FT_CODE"],
                    "RM_FT_DESC" => $_POST["RM_FT_DESC"],
                    "RM_FT_FEATURE" => $_POST["RM_FT_FEATURE"]
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RM_FT_ID,RM_FT_CODE,RM_FT_DESC,RM_FT_FEATURE FROM FLXY_ROOM_FEATURE WHERE RM_FT_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRoomFeature(){
        $sysid = $_POST['sysid'];
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
        return view('SectionView');
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
                'SC_FL_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['SC_FL_ID'];
            if(!empty($sysid)){
                $data = ["SC_FL_CODE" => $_POST["SC_FL_CODE"],
                    "SC_FL_DESC" => $_POST["SC_FL_DESC"],
                    "SC_FL_TARGET_CREDIT" => $_POST["SC_FL_TARGET_CREDIT"],
                    "SC_FL_DISPLAY_SEQ" => $_POST["SC_FL_DISPLAY_SEQ"],
                    "SC_FL_ACTIVE" => null
                 ];
            $return = $this->Db->table('FLXY_SECTION')->where('SC_FL_ID', $sysid)->update($data); 
            }else{
                $data = ["SC_FL_CODE" => $_POST["SC_FL_CODE"],
                    "SC_FL_DESC" => $_POST["SC_FL_DESC"],
                    "SC_FL_TARGET_CREDIT" => $_POST["SC_FL_TARGET_CREDIT"],
                    "SC_FL_DISPLAY_SEQ" => $_POST["SC_FL_DISPLAY_SEQ"],
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT SC_FL_ID,SC_FL_CODE,SC_FL_DESC,SC_FL_TARGET_CREDIT,SC_FL_DISPLAY_SEQ,SC_FL_ACTIVE FROM FLXY_SECTION WHERE SC_FL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSection(){
        $sysid = $_POST['sysid'];
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

    public function rateClass(){
        return view('RateClassView');
    }

    public function RateClassView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_RATE_CLASS';
        $columns = 'RT_CL_ID,RT_CL_CODE,RT_CL_DESC,RT_CL_DIS_SEQ,RT_CL_BEGIN_DT,RT_CL_END_DT';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    public function insertRateClass(){
        try{
            $validate = $this->validate([
                'RT_CL_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['RT_CL_ID'];
            if(!empty($sysid)){
                $data = ["RT_CL_CODE" => $_POST["RT_CL_CODE"],
                    "RT_CL_DESC" => $_POST["RT_CL_DESC"],
                    "RT_CL_DIS_SEQ" => $_POST["RT_CL_DIS_SEQ"],
                    "RT_CL_BEGIN_DT" => $_POST["RT_CL_BEGIN_DT"],
                    "RT_CL_END_DT" => $_POST["RT_CL_END_DT"]
                 ];
            $return = $this->Db->table('FLXY_RATE_CLASS')->where('RT_CL_ID', $sysid)->update($data); 
            }else{
                $data = ["RT_CL_CODE" => $_POST["RT_CL_CODE"],
                    "RT_CL_DESC" => $_POST["RT_CL_DESC"],
                    "RT_CL_DIS_SEQ" => $_POST["RT_CL_DIS_SEQ"],
                    "RT_CL_BEGIN_DT" => $_POST["RT_CL_BEGIN_DT"],
                    "RT_CL_END_DT" => $_POST["RT_CL_END_DT"]
                 ];
                $return = $this->Db->table('FLXY_RATE_CLASS')->insert($data); 
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

    public function editRateClass(){
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT RT_CL_ID,RT_CL_CODE,RT_CL_DESC,RT_CL_DIS_SEQ,RT_CL_BEGIN_DT,RT_CL_END_DT FROM FLXY_RATE_CLASS WHERE RT_CL_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteRateClass(){
        $sysid = $_POST['sysid'];
        try{
            $return = $this->Db->table('FLXY_RATE_CLASS')->delete(['RT_CL_ID' => $sysid]); 
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
        return view('SourceView');
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
                'SOR_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['SOR_ID'];
            if(!empty($sysid)){
                $data = ["SOR_CODE" => $_POST["SOR_CODE"],
                    "SOR_DESC" => $_POST["SOR_DESC"],
                    "SOR_GROUP" => $_POST["SOR_GROUP"],
                    "SOR_DIS_SEQ" => $_POST["SOR_DIS_SEQ"],
                    "SOR_ACTIVE" => 'Y'
                 ];
            $return = $this->Db->table('FLXY_SOURCE')->where('SOR_ID', $sysid)->update($data); 
            }else{
                $data = ["SOR_CODE" => $_POST["SOR_CODE"],
                    "SOR_DESC" => $_POST["SOR_DESC"],
                    "SOR_GROUP" => $_POST["SOR_GROUP"],
                    "SOR_DIS_SEQ" => $_POST["SOR_DIS_SEQ"],
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT SOR_ID,SOR_CODE,SOR_DESC,SOR_GROUP,SOR_DIS_SEQ,SOR_ACTIVE FROM FLXY_SOURCE WHERE SOR_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSource(){
        $sysid = $_POST['sysid'];
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
        return view('SourceGroupView');
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
                'SOR_GR_CODE' => 'required'
            ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = $this->responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $_POST['SOR_GR_ID'];
            if(!empty($sysid)){
                $data = ["SOR_GR_CODE" => $_POST["SOR_GR_CODE"],
                    "SOR_GR_DESC" => $_POST["SOR_GR_DESC"],
                    "SOR_GR_DIS_SEQ" => $_POST["SOR_GR_DIS_SEQ"],
                    "SOR_GR_ACTIVE" => 'Y'
                 ];
            $return = $this->Db->table('FLXY_SOURCE_GROUP')->where('SOR_GR_ID', $sysid)->update($data); 
            }else{
                $data = ["SOR_GR_CODE" => $_POST["SOR_GR_CODE"],
                    "SOR_GR_DESC" => $_POST["SOR_GR_DESC"],
                    "SOR_GR_DIS_SEQ" => $_POST["SOR_GR_DIS_SEQ"],
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
        $param = ['SYSID'=> $_POST['sysid']];
        $sql = "SELECT SOR_GR_ID,SOR_GR_CODE,SOR_GR_DESC,SOR_GR_DIS_SEQ,SOR_GR_ACTIVE FROM FLXY_SOURCE_GROUP WHERE SOR_GR_ID=:SYSID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }

    public function deleteSourceGroup(){
        $sysid = $_POST['sysid'];
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

}
