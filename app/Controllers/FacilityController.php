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
        $tableName = 'FLXY_MAINTENANCE_VIEW';
        $columns = 'MAINT_ID|MAINT_ROOM_NO|TYPE|MAINT_CATEGORY|MAINT_SUBCATEGORY|MAINT_PREFERRED_TIME|MAINT_STATUS';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
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
            
            $attached_path = NULL;
            $validate = $this->validate([
                
                'MAINT_ROOM_NO' => 'required',
                'MAINT_TYPE' => 'required',
                'MAINT_CATEGORY' => 'required',
                'MAINT_PREFERRED_TIME' => 'required',
                'MAINT_PREFERRED_DT' => 'required',
                'MAINT_ATTACHMENT' => [
                    'uploaded[MAINT_ATTACHMENT]',
                    'mime_in[MAINT_ATTACHMENT,image/png, image/jpeg]',
                    'max_size[MAINT_ATTACHMENT,500]',
                ]
                
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
            $sysid = $this->request->getPost("sysid");
            if(empty($sysid)){
                
                // INSERT
                if($doc_file){
                    $doc_name = $doc_file->getName();
                    $folderPath = "assets/Uploads/Maintenance";
                    $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                    if($doc_up['SUCCESS'] == 200){
                        $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    }
                }
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
                    "MAINT_CREATE_UID" =>$this->session->name,
                    "MAINT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE')->insert($data); 
            }else{
               
                
                // UPDATE
                // unlink the old file from the folder and update the column in db
                $doc_data = $this->Db->table('FLXY_MAINTENANCE')->select('MAINT_ATTACHMENT')->where('MAINT_ID', $sysid)->get()->getRowArray();
                $filename = $doc_data['MAINT_ATTACHMENT'];
                if($filename){
                    $filename = explode('/',$filename);
                    $file = end($filename);
                    $folderPath = "assets/Uploads/Maintenance/".$file ;
                    if(file_exists( $folderPath )){              
                        if(unlink($folderPath)){
                            if($doc_file){
                                $doc_name = $doc_file->getName();
                                $folderPath = "assets/Uploads/Maintenance";
                                // 
                                $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                                if($doc_up['SUCCESS'] == 200){
                                    $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                                }
                            }
                        }
                    }    
                
                }
                else{
                    if($doc_file){
                        $doc_name = $doc_file->getName();
                        $folderPath = "assets/Uploads/Maintenance";
                        // 
                        $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                        if($doc_up['SUCCESS'] == 200){
                            $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                        }
                    }
                }
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
                    "MAINT_CREATE_UID" =>$this->session->name,
                    "MAINT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_UPDATE_UID" =>$this->session->name,
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
            $folderPath = "assets/Uploads/Maintenance/". $doc_data['MAINT_ATTACHMENT'] ;
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

    public function maintenanceCategoryList()
    {
        $sql = "SELECT MAINT_CAT_ID,MAINT_CATEGORY FROM FLXY_MAINTENANCE_CATEGORY";
        $response = $this->Db->query($sql)->getResultArray();
        echo json_encode($response);
    }

    public function maintenanceSubCatByCategoryID()
    {
        $param = ['MAINT_CAT_ID'=> $this->request->getPost("category")];
        $sql = "SELECT a.MAINT_CAT_ID,b.MAINT_SUBCATEGORY ,b.MAINT_SUBCAT_ID FROM FLXY_MAINTENANCE_CATEGORY a
        LEFT JOIN FLXY_MAINTENANCE_SUBCATEGORY b ON b.MAINT_CAT_ID = a.MAINT_CAT_ID WHERE a.MAINT_CAT_ID =:MAINT_CAT_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        $option='<option value="">Select SubCategory</option>';
        foreach($response as $row){
            $option.= '<option value="'.$row['MAINT_SUBCAT_ID'].'">'.$row['MAINT_SUBCATEGORY'].'</option>';
        }
        echo $option;
    }
    // Maintenance request - Category
    public function maintenanceRequestCategory(){
        return view('Maintenance/MaintenanceRequestCategoryView');
    }
    public function categorylist()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINTENANCE_CATEGORY';
        $columns = 'MAINT_CAT_ID|MAINT_CATEGORY|MAINT_CAT_CREATE_UID|FORMAT(MAINT_CAT_CREATE_DT,\'dd-MMM-yyyy\')MAINT_CAT_CREATE_DT';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
        exit;
    }
    function editCategory(){
        $param = ['MAINT_CAT_ID'=> $this->request->getPost("sysid")];
        $sql = "SELECT MAINT_CATEGORY FROM FLXY_MAINTENANCE_CATEGORY WHERE MAINT_CAT_ID =:MAINT_CAT_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    public function insertCategory(){
        try{
            $validate = $this->validate([
                'MAINT_CATEGORY' => 'required',
                
             ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("sysid");
            if(empty($sysid)){
                $data = 
                [
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_CAT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_CREATE_UID" =>$this->session->name,
                    "MAINT_CAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->insert($data); 
            }else{
                // UPDATE
                $data = 
                [
                    "MAINT_CATEGORY" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_CAT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_CREATE_UID" =>$this->session->name,
                    "MAINT_CAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_CAT_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->where('MAINT_CAT_ID', $sysid)->update($data); 
            }
            if($ins){
                $result = responseJson(200,true,"Category  Added",[]);
                echo json_encode($result);die;

            }else {
                $result = responseJson(500,true,"Category addition Failed",[]);
                echo json_encode($result);die;
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function deleteCategory()
    {
        $sysid = $this->request->getPost("sysid");
        try{
             
           
            $return = $this->Db->table('FLXY_MAINTENANCE_CATEGORY')->delete(['MAINT_CAT_ID' => $sysid]); 
            
            if($return){
                $result = $this->responseJson(200,false,"Deleted the Category",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson(500,true,"Category not deleted",[]);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function maintenanceRequestSubCategory(){
        return view('Maintenance/MaintenanceRequestSubCategoryView');
    }
    public function subCategoryList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_MAINT_SUBCATEGORY_VIEW';
        $columns = 'MAINT_SUBCAT_ID|MAINT_SUBCATEGORY|MAINT_CATEGORY|CUST_FULLNAME|FORMAT(MAINT_SUBCAT_CREATE_DT,\'dd-MMM-yyyy\')MAINT_SUBCAT_CREATE_DT';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
        exit;
    }
    function editSubCategory(){
        $param = ['MAINT_SUBCAT_ID'=> $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_MAINTENANCE_SUBCATEGORY WHERE MAINT_SUBCAT_ID =:MAINT_SUBCAT_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    public function insertSubCategory(){
        try{
            $validate = $this->validate([
                'MAINT_SUBCATEGORY' => 'required',
                'MAINT_CATEGORY' => 'required',
                
            ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $sysid = $this->request->getPost("sysid");
            if(empty($sysid)){
                $data = 
                [
                    "MAINT_CAT_ID" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUBCATEGORY" => $this->request->getPost("MAINT_SUBCATEGORY"),
                    "MAINT_SUBCAT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_SUBCAT_CREATE_UID" =>$this->session->name,
                    "MAINT_SUBCAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_SUBCAT_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->insert($data); 
            }else{
                $data = 
                [
                    "MAINT_CAT_ID" => $this->request->getPost("MAINT_CATEGORY"),
                    "MAINT_SUBCATEGORY" => $this->request->getPost("MAINT_SUBCATEGORY"),
                    "MAINT_SUBCAT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_SUBCAT_CREATE_UID" =>$this->session->name,
                    "MAINT_SUBCAT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_SUBCAT_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->where('MAINT_SUBCAT_ID', $sysid)->update($data); 
            }
            if($ins){
                $result = responseJson(200,true,"SubCategory Added",[]);
                echo json_encode($result);die;

            }else {
                $result = responseJson(500,true,"SubCategory Creation Failed",[]);
                echo json_encode($result);die;
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function deleteSubCategory()
    {
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_MAINTENANCE_SUBCATEGORY')->delete(['MAINT_SUBCAT_ID' => $sysid]);
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
    // Feedback
    public function feedback()
    {
        return view('Feedback/FeedbackView');
    }

    public function feedbackList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_FEEDBACK_VIEW';
        $columns = 'CUST_FULLNAME|FB_RATINGS|FB_DESCRIPTION|FORMAT(FB_CREATE_DT,\'dd-MMM-yyyy\')FB_CREATE_DT';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
        exit;
    }

    // HANDBOOK
    public function handbook()
    {
        return view('handbookView');
    }    

    public function saveHandbook(){
        try{
            $validate = $this->validate([       
                'HANDBOOK' =>  [
                    'uploaded[HANDBOOK]',
                    'mime_in[HANDBOOK,application/pdf]',
                    // 'max_size[HANDBOOK,500]',
                ], ]);
                if (!$validate) {
                        
                    $validate = $this->validator->getErrors();
                    $result["SUCCESS"] = "-402";
                    $result[]["ERROR"] = $validate;
                    $result = responseJson(402,true,"validation errors. please check required parameters",$validate);
                    echo json_encode($result);
                    exit;
                }
            $HANDBOOK = $this->request->getFile("HANDBOOK");
            // $doc_name = $HANDBOOK->getName();
            $folderPath = "assets/Uploads/handbook/";
            $doc_up = documentUpload($HANDBOOK ,"hotel", "handbook" , $folderPath);
            if($doc_up['SUCCESS'] == 200){
                
                $result = responseJson(200,false,"HandBook Uploaded successfully",[]);
                echo json_encode($result);
            }else{
                $result = responseJson(500,true,"Something went wrong on uploading.please try again",[]);
                echo json_encode($result);
            }
        }catch(Exception $e){
            $result = responseJson(500,true,"Something went wrong on uploading.please try again",$e->errors());
            echo json_encode($result);            
        }
        
    }
    public function checkthehandbook()
    {
        try{
            $folderPath ="assets/Uploads/handbook/hotel-handbook.pdf";
            if(file_exists( $folderPath )){        
                $filesize = filesize($folderPath);
                $result = responseJson(200,false,"files in the directory",$filesize);
                echo json_encode($result); 
            }else{
                $result = responseJson(201,false,"No files in the directory",[]);
                echo json_encode($result); 
            } 
            
        }catch(Exception $e){
            $result = responseJson(500,true,"errors",$e->errors());
            echo json_encode($result);
        }

    }
    // SHUTTLE
    public function shuttle()
    {
        return view('Transfers/shuttleView');
    }
    public function shuttlelist()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SHUTTLE';
        $columns = 'SHUTL_ID|SHUTL_NAME|SHUTL_ROUTE|SHUTL_NEXT|SHUTL_DESCRIPTION|cast(SHUTL_START_AT as time)SHUTL_START_AT|cast(SHUTL_END_AT as time)SHUTL_END_AT';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
        exit;
    }
    function getStages($id = NULL){

        $WHERE ="";
        if($id){
            $WHERE = " where SHUTL_STAGE_ID =".$id;
        }
        $sql = "SELECT * FROM FLXY_SHUTL_STAGES".$WHERE;
        $response = $this->Db->query($sql)->getResultArray();
        return json_encode($response);
    }
    public function insertShuttle()
    {
        try{
            
            $attached_path = NULL;
            $validate = $this->validate([
                
                'SHUTL_NAME' => 'required',
                'SHUTL_FROM' => 'required',
                'SHUTL_TO' => 'required',
                'SHUTL_START_AT' => 'required',
                'SHUTL_END_AT' => 'required',
                'SHUTL_NEXT' => 'required',
                'SHUTL_DESCRIPTION' => 'required',
                'SHUTL_ROUTE_IMG' => [
                    'uploaded[SHUTL_ROUTE_IMG]',
                    'mime_in[SHUTL_ROUTE_IMG,image/png, image/jpeg]',
                    'max_size[SHUTL_ROUTE_IMG,500]',
                ]
                
             ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $doc_file = $this->request->getFile('SHUTL_ROUTE_IMG');
            $sysid = $this->request->getPost("sysid");
            // GET SUTTLE NAME FROM IDS
            $stages_from =json_decode( $this->getStages($this->request->getPost("SHUTL_FROM")));
            $stages_To = json_decode($this->getStages($this->request->getPost("SHUTL_TO")));
            $route = $stages_from[0]->SHUTL_STAGE_NAME ." - ".$stages_To[0]->SHUTL_STAGE_NAME;
            
            if(empty($sysid)){
                
                // INSERT
                if($doc_file){
                    $doc_name = $doc_file->getName();
                    $folderPath = "assets/Uploads/Shuttle";
                    $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                    if($doc_up['SUCCESS'] == 200){
                        $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    }
                }
                $data = 
                [
                    "SHUTL_NAME" => $this->request->getPost("SHUTL_NAME"),
                    "SHUTL_FROM" => $this->request->getPost("SHUTL_FROM"),
                    "SHUTL_TO" => $this->request->getPost("SHUTL_TO"),
                    "SHUTL_START_AT" => $this->request->getPost("SHUTL_START_AT"),
                    "SHUTL_END_AT" => $this->request->getPost("SHUTL_END_AT"),
                    "SHUTL_NEXT" => $this->request->getPost("SHUTL_NEXT"),
                    "SHUTL_ROUTE" => $route,
                    "SHUTL_ROUTE_IMG" => $attached_path ,
                    "SHUTL_DESCRIPTION" => $this->request->getPost("SHUTL_DESCRIPTION"),
                    "SHUTL_CREATE_DT" => date("d-M-Y"),
                    "SHUTL_CREATE_UID" =>$this->session->name,
                    "SHUTL_UPDATE_DT" => date("d-M-Y"),
                    "SHUTL_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_SHUTTLE')->insert($data); 
            }else{
               
                
                // UPDATE
                // unlink the old file from the folder and update the column in db
                $doc_data = $this->Db->table('FLXY_SHUTTLE')->select('SHUTL_ROUTE_IMG')->where('SHUTL_ID', $sysid)->get()->getRowArray();
                $filename = $doc_data['SHUTL_ROUTE_IMG'];
                if($filename){
                    $filename = explode('/',$filename);
                    $file = end($filename);
                    $folderPath = "assets/Uploads/Shuttle/".$file ;
                    if(file_exists( $folderPath )){              
                        if(unlink($folderPath)){
                            if($doc_file){
                                $doc_name = $doc_file->getName();
                                $folderPath = "assets/Uploads/Shuttle";
                                // 
                                $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                                if($doc_up['SUCCESS'] == 200){
                                    $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                                }
                            }
                        }
                    }    
                
                }
                else{
                    if($doc_file){
                        $doc_name = $doc_file->getName();
                        $folderPath = "assets/Uploads/Shuttle";
                        // 
                        $doc_up = documentUpload($doc_file ,$doc_name, $this->session->name, $folderPath);
                        if($doc_up['SUCCESS'] == 200){
                            $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                        }
                    }
                }
                $data = 
                [
                    "SHUTL_NAME" => $this->request->getPost("SHUTL_NAME"),
                    "SHUTL_FROM" => $this->request->getPost("SHUTL_FROM"),
                    "SHUTL_TO" => $this->request->getPost("SHUTL_TO"),
                    "SHUTL_START_AT" => $this->request->getPost("SHUTL_START_AT"),
                    "SHUTL_END_AT" => $this->request->getPost("SHUTL_END_AT"),
                    "SHUTL_NEXT" => $this->request->getPost("SHUTL_NEXT"),
                    "SHUTL_ROUTE" => $route,
                    "SHUTL_ROUTE_IMG" => $attached_path ,
                    "SHUTL_DESCRIPTION" => $this->request->getPost("SHUTL_DESCRIPTION"),
                    "SHUTL_CREATE_DT" => date("d-M-Y"),
                    "SHUTL_CREATE_UID" =>$this->session->name,
                    "SHUTL_UPDATE_DT" => date("d-M-Y"),
                    "SHUTL_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_SHUTTLE')->where('SHUTL_ID', $sysid)->update($data); 
            }
            if($ins){
                $result = responseJson(200,true,"Shuttle request Added",[]);
                echo json_encode($result);die;

            }else {
                $result = responseJson(500,true,"Creation Failed",[]);
                echo json_encode($result);die;
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function deleteShuttle()
    {
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SHUTTLE')->delete(['SHUTL_ID' => $sysid]);
            if($return){
                $result = $this->responseJson(200,false,"Deleted the shuttle",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson(500,true,"shuttle not deleted",[]);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    function editShuttle(){
        $param = ['SHUTL_ID'=> $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_SHUTTLE WHERE SHUTL_ID =:SHUTL_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    public function stages()
    {
        return view('Transfers/stagesView');
    }
    public function insertStages()
    {
        try{
            $validate = $this->validate([
                
                'SHUTL_STAGE_NAME' => 'required',
                
             ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
           
            $sysid = $this->request->getPost("sysid");
           
            if(empty($sysid)){
                
                // INSERT
                
                $data = 
                [
                    "SHUTL_STAGE_NAME" => $this->request->getPost("SHUTL_STAGE_NAME"),
                    "SHUTL_CREATE_DT" => date("d-M-Y"),
                    "SHUTL_CREATE_UID" =>$this->session->name,
                    "SHUTL_UPDATE_DT" => date("d-M-Y"),
                    "SHUTL_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_SHUTL_STAGES')->insert($data); 
            }else{
                $data = 
                [
                    "SHUTL_STAGE_NAME" => $this->request->getPost("SHUTL_STAGE_NAME"),
                    "SHUTL_CREATE_DT" => date("d-M-Y"),
                    "SHUTL_CREATE_UID" =>$this->session->name,
                    "SHUTL_UPDATE_DT" => date("d-M-Y"),
                    "SHUTL_UPDATE_UID" =>$this->session->name,
                ];
                $ins = $this->Db->table('FLXY_SHUTL_STAGES')->where('SHUTL_STAGE_ID', $sysid)->update($data); 
            }
            if($ins){
                $result = responseJson(200,true,"Shuttle request Added",[]);
                echo json_encode($result);die;

            }else {
                $result = responseJson(500,true,"Creation Failed",[]);
                echo json_encode($result);die;
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    public function deleteStages()
    {
        $sysid = $this->request->getPost("sysid");
        try{
            $return = $this->Db->table('FLXY_SHUTL_STAGES')->delete(['SHUTL_STAGE_ID' => $sysid]);
            if($return){
                $result = $this->responseJson(200,false,"Deleted the shuttle Stage",$return);
                echo json_encode($result);
            }else{
                $result = $this->responseJson(500,true,"shuttle Stage not deleted",[]);
                echo json_encode($result);
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }
    function editStages(){
        $param = ['SHUTL_STAGE_ID'=> $this->request->getPost("sysid")];
        $sql = "SELECT * FROM FLXY_SHUTL_STAGES WHERE SHUTL_STAGE_ID =:SHUTL_STAGE_ID:";
        $response = $this->Db->query($sql,$param)->getResultArray();
        echo json_encode($response);
    }
    public function getStagesList()
    {
        $mine = new ServerSideDataTable(); // loads and creates instance
        $tableName = 'FLXY_SHUTL_STAGES';
        // CUST_FULLNAME|
        $columns = 'SHUTL_STAGE_ID|SHUTL_CREATE_UID|SHUTL_STAGE_NAME|FORMAT(SHUTL_CREATE_DT,\'dd-MMM-yyyy\')SHUTL_CREATE_DT';
        $mine->generate_DatatTable($tableName,$columns,[],'|');
        exit;
    }
}