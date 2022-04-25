<?php
namespace App\Controllers;
use  App\Libraries\ServerSideDataTable;
use  App\Libraries\EmailLibrary;
use DateTime;
use DateTimeZone;
class FacilityController extends BaseController
{
    public $Db;
    public $session;
    public $request;
    public $todayDate;
    public function __construct(){
        $this->Db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['form','responsejson','upload']);
        $this->request = \Config\Services::request();
        $this->todayDate = new DateTime("now", new DateTimeZone('Asia/Dubai'));
    }
    // CODE BY ALEESHA  - Maintenance Request
    public function maintenanceRequest(){
        return view('Maintenance/MaintenanceRequestView');
    }
    public function getRequestList(){
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINTENANCE';
        $columns = 'MAINT_ID,MAINT_ROOM_NO,MAINT_TYPE,MAINT_CATEGORY,MAINT_SUB_CATEGORY,MAINT_PREFERRED_DT,MAINT_PREFERRED_TIME,MAINT_ATTACHMENT,MAINT_STATUS';
        $mine->generate_DatatTable($tableName,$columns);
        exit;
    }
    function getCustomerFromRoomNo(){

        $room = $this->request->getPost("room");
        $sql = "SELECT concat(b.CUST_FIRST_NAME,' ',b.CUST_MIDDLE_NAME,' ',b.CUST_LAST_NAME)NAME FROM FLXY_RESERVATION a
        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID =a.RESV_NAME WHERE a.RESV_ROOM =:RESV_ROOM: AND a.RESV_STATUS='CHECKIN'";
        $param = ['RESV_ROOM'=>$room];
        $response = $this->Db->query($sql,$param)->getResultArray();
        
        $option='<option value="">Select Customer</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['NAME'].'">'.$row['NAME'].'</option>';
        }
        echo $option;
    }
    public function insertMaintenanceRequest(){
        try{
            
            $validate = $this->validate([
                
                'MAINT_ROOM_NO' => 'required',
                'MAINT_TYPE' => 'required',
                'MAINT_CATEGORY' => 'required',
                
                'MAINT_PREFERRED_TIME' => 'required',
                'MAINT_PREFERRED_DT' => 'required',
                
             ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $doc_file = $this->request->getFile('MAINT_ATTACHMENT');
            if($doc_file){
                $doc_name = $doc_file->getName();
                $folderPath = "assets/maintenance";
                $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                if($doc_up['SUCCESS'] == 200){
                    $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                }
            }else{
                $attached_path = NULL;
            }
            $sysid = $this->request->getPost("sysid");
            if(empty($sysid)){
                $data = 
                [
                    "MAINT_TYPE" => $this->request->getPost("MAINT_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUB_CATEGORY" => $this->request->getPost("MAINT_SUB_CATEGORY"),
                    "MAINT_DETAILS" => $this->request->getPost("MAINT_DETAILS"),
                    "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getPost("MAINT_PREFERRED_DT"))),
                    "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getPost("MAINT_PREFERRED_TIME"))),
                    "MAINT_ATTACHMENT" => $attached_path ,
                    "MAINT_STATUS" => "New" ,
                    "MAINT_ROOM_NO" => $this->request->getPost("MAINT_ROOM_NO"),
                    "MAINT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CREATE_UID" => $this->session->name,
                    "MAINT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_UPDATE_UID" => $this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE')->insert($data); 
            }else{
                $data = 
                [
                    "MAINT_TYPE" => $this->request->getPost("MAINT_TYPE"),
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUB_CATEGORY" => $this->request->getPost("MAINT_SUB_CATEGORY"),
                    "MAINT_DETAILS" => $this->request->getPost("MAINT_DETAILS"),
                    "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getPost("MAINT_PREFERRED_DT"))),
                    "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getPost("MAINT_PREFERRED_TIME"))),
                    "MAINT_ATTACHMENT" => $attached_path ,
                    "MAINT_STATUS" => "New" ,
                    "MAINT_ROOM_NO" => $this->request->getPost("MAINT_ROOM_NO") ,
                    "MAINT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CREATE_UID" => $this->session->name,
                    "MAINT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_UPDATE_UID" => $this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE')->where('MAINT_ID', $sysid)->update($data); 
            }
            if($ins){
                $result = responseJson(200,true,"Maintenance request Added",[]);
                echo json_encode($result);die;

            }else {
                $result = responseJson(500,true,"Creation Failed",[]);
                echo json_encode($result);die;
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function deleteRequest()
    {
        $sysid = $this->request->getPost("sysid");
        try{
            $doc_data = $this->Db->table('FLXY_MAINTENANCE')->select('MAINT_ATTACHMENT')->where(['MAINT_ID' => $sysid])->get()->getRowArray(); 
           
            $return = $this->Db->table('FLXY_MAINTENANCE')->delete(['MAINT_ID' => $sysid]); 
            
            // unlink the document attached
            $folderPath = "assets/userDocuments/Maintenance/". $doc_data['MAINT_ATTACHMENT'] ;
            if(file_exists( $folderPath )){              
                unlink($folderPath);
            }
            if($return){
                $result = $this->responseJson(200,false,"Deleted the request",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson(500,true,"Record not deleted",[]);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    function editMaintenanceRequest(){
        $param = ['MAINT_ID'=> $this->request->getPost("sysid")];
        $sql = "SELECT *,(SELECT RM_DESC FROM FLXY_ROOM WHERE RM_NO = MAINT_ROOM_NO) RM_DESC FROM FLXY_MAINTENANCE WHERE MAINT_ID =:MAINT_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }


}