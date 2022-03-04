<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use  App\Libraries\ServerSideDataTable;
class ApplicatioController extends BaseController
{
    public $Db;
    public function __construct(){
        $this->Db = \Config\Database::connect();
        helper(['form']);
    }
    
    public function index(){
        $response = $this->Db->table('COUNTRY')->select('iso2,cname')->get()->getResultArray();
        return view('Reservation');
    }

    public function Reservation(){
       
        return view('UserProfile');
    }

    public function datatableView(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'test_tb';
        $columns = 'name_e,email,age';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }

    function insertReservation(){
        try{
            $data = ["RESV_ARRIVAL_DT" => $_POST["RESV_ARRIVAL_DT"],
                "RESV_NIGHT" => $_POST["RESV_NIGHT"],
                "RESV_ADULTS" => $_POST["RESV_ADULTS"],
                "RESV_CHILDREN" => $_POST["RESV_CHILDREN"],
                "RESV_DEPARTURE" => $_POST["RESV_DEPARTURE"],
                "RESV_NO_F_ROOM" => $_POST["RESV_NO_F_ROOM"],
                "RESV_NAME" => $_POST["RESV_NAME"],
                "RESV_COMPANY" => $_POST["RESV_COMPANY"],
                "RESV_AGENT" => $_POST["RESV_AGENT"],
                "RESV_BLOCK" => $_POST["RESV_BLOCK"],
                "RESV_MEMBER_NO" => $_POST["RESV_MEMBER_NO"],
                "RESV_CORP_NO" => $_POST["RESV_CORP_NO"],
                "RESV_IATA_NO" => $_POST["RESV_IATA_NO"],
                "RESV_CLOSED" => $_POST["RESV_CLOSED"],
                "RESV_DAY_USE" => $_POST["RESV_DAY_USE"],
                "RESV_PSEUDO" => $_POST["RESV_PSEUDO"],
                "RESV_RATE_CLASS" => $_POST["RESV_RATE_CLASS"],
                "RESV_RATE_CATEGORY" => $_POST["RESV_RATE_CATEGORY"],
                "RESV_RATE_CODE" => $_POST["RESV_RATE_CODE"],
                "RESV_ROOM_CLASS" => $_POST["RESV_ROOM_CLASS"],
                "RESV_FEATURE" => $_POST["RESV_FEATURE"],
                "RESV_PACKAGES" => $_POST["RESV_PACKAGES"],
                "RESV_PURPOSE_STAY" => $_POST["RESV_PURPOSE_STAY"],
                "RESV_CREATE_DT" => date("d-M-Y")
            ];
            $return = $this->Db->table('FLXY_RESERVATION')->insert($data); 
            if($return){
                $result = $this->responseJson("1","0",$return);
                echo json_encode($response);
            }else{
                $result = $this->responseJson("-444","db insert not success");
                echo json_encode($response);
            }
            // $return = $this->Db->query($sql);
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
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
                'CUST_FIRST_NAME' => 'required|min_length[3]',
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
            // if(!empty($sysid)){
            //     $return = $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $sysid)->update($data); 
            // }else{
            //     $return = $this->Db->table('FLXY_CUSTOMER')->insert($data); 
            // }
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
        $sql = 'SELECT RM_CODE CODE,RM_DESC DESCS FROM FLXY_ROOM_CLASS';
        $respon4 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT RM_FT_CODE CODE,RM_FT_DESC DESCS FROM FLXY_ROOM_FEATURE';
        $respon5 = $this->Db->query($sql)->getResultArray();
        $sql = 'SELECT PUR_ST_ID CODE,PUR_ST_DESC DESCS FROM FLXY_PURPOSE_STAY';
        $respon6 = $this->Db->query($sql)->getResultArray();
        $data = [$respon1,$respon2,$respon3,$respon4,$respon5,$respon6];
        echo json_encode($data);
    }
}
