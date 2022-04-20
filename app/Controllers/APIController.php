<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use  App\Libraries\EmailLibrary;

class APIController extends ResourceController
{
    use ResponseTrait;
    public $Db;
    public $session;
    public $request;
    public function __construct(){
        $this->Db = \Config\Database::connect();
        $validation =  \Config\Services::validation();
        $this->session = \Config\Services::session();
        helper(['form']);
        helper('responsejson');
        helper('jwt');
        helper('upload');
        $this->request = \Config\Services::request();
    }
    
    // ----------- START API FOR FLEXI GUEST --------------//

    // REGISTRATION API
    public function registerAPI()
        { 
            $rules = [
                "name" => "required",
                "email" => "required|valid_email|is_unique[FLXY_USERS.USR_EMAIL]|min_length[6]|max_length[50]",
                "phone_no" => "required",
                "password" => 'required|min_length[8]|max_length[255]',
                "confirm_password" => 'required|matches[password]',
                
            ];
            $messages = [
                "name" => [
                    "required" => "Name is required"
                ],
                "email" => [
                    "required" => "Email required",
                    "valid_email" => "Email address is not in format",
                    "is_unique"=> "Email already Exist"
                ],
                "phone_no" => [
                    "required" => "Phone Number is required"
                ],
                "password" => [
                    "required" => "password is required"
                ],
                "confirm_password" => [
                    "matches" => "confirm password must be same as password"
                ],
            ];
            if (!$this->validate($rules, $messages)) {
                $result = responseJson(500,true,$this->validator->getErrors(),[]);
                echo json_encode($result);die;
            } else {
                $email = $this->request->getPost("email");
                // check wheather the email is present in customer table
                $isCustomer_data = $this->Db->table('FLXY_CUSTOMER')->where('CUST_EMAIL', $email)->get()->getRowArray();
                if(empty($isCustomer_data)){
                    $result = responseJson(404,false,"Sorry , You are not Reserved any room.",[]);
                    echo json_encode($result);die;
                }
                $data = [
                    "USR_NAME" => $this->request->getVar("name"),
                    "USR_EMAIL" => $email,
                    "USR_PHONE" => $this->request->getVar("phone_no"),
                    "USR_PASSWORD" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                    "USR_ROLE" => "GUEST",
                    "USR_CUST_ID" => $isCustomer_data['CUST_ID'],
                    "USR_CREATED_DT"=>  date("d-M-Y"),
                    "USR_UPDATED_DT"=> date("d-M-Y")
                ];
                if ($this->Db->table('FLXY_USERS')->insert($data) ) {
                    $result = responseJson(200,false,"Successfully, user has been registered",[]);
                    echo json_encode($result);die;
                } else {
                    $result = responseJson(500,true,"Failed to create user",[]);
                    echo json_encode($result);die;
                }
            }
        }
    // login API
    public function loginAPI()
        {
            try {
            $rules = [
                "email" => "required|valid_email|min_length[6]",
                "password" => "required",
            ];
            $messages = [
                "email" => [
                    "required" => "Email required",
                    "valid_email" => "Email address is not in format"
                ],
                "password" => [
                    "required" => "password is required"
                ],
            ];
            if (!$this->validate($rules, $messages)) {
                $result = responseJson(500,true,$this->validator->getErrors(),[]);
                echo json_encode($result);die;
            } else {
                $sql = "SELECT * FROM FLXY_USERS WHERE USR_EMAIL=:email:";
                $param = ['email'=> $this->request->getVar("email")];
                $userdata = $this->Db->query($sql,$param)->getRowArray();
                if (!empty($userdata)) {
                    if (password_verify($this->request->getVar("password"), $userdata['USR_PASSWORD'])) {
                        // Token created  
                        $token =   getSignedJWTForUser($userdata);
                        $result = responseJson(200,false,'User logged In successfully',['token' => $token]);
                        echo json_encode($result);die;
                    } else {
                        $result = responseJson("500",true,"Incorrect details");
                        echo json_encode($result);
                    }
                } else {
                    $result = responseJson("404",false,"User not found");
                    echo json_encode($result);
                }
            }
        } catch (Exception $ex) {
            echo json_encode($ex->errors());
        }
    }

    public function profileAPI()
    {

        try {
            // get Token
            $token = getJWTFromRequest();  
            // decoded token information and userdata information from the table.
            $decoded =  validateJWTFromRequest($token);
            // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
            if (!empty($decoded)) {
                $result = responseJson(200,false,"User details",$decoded);
                echo json_encode($result);die;
            }
        } catch (Exception $ex) {
            $result = responseJson(401,true,"Access denied",[]);
            echo json_encode($result);die;
        }
    }
    
    // for Logout Just delete the token from session or anystorage
    public function logoutApi()
    {
        session()->destroy();
        return true;
    }

    // -----------------------------------------------------------------------CHECKIN API START -------------------------------------------//
    
    /*  ---------------------------------------------------

    Function : List all reservations with details
    METHOD: GET , 
    INPUT : Header Authorization- Token
    OUTPUT: Reservation details like Reservation_no,checkin _date,checkout_date,apartment_details,apartment no ,status ,name ,
            night, adult,childern count, document uploaded or not
    
     --------------------------------------------------- */ 
    public function listReservationsAPI($resID = null)
    {
        try {
            // get Token
            $token = getJWTFromRequest();  
            // decoded token information and userdata information from the table.
            $decoded =  validateJWTFromRequest($token);
            // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
            if(!empty($decoded)) {
                 $cust_id = $decoded['token_info']->data->USR_CUST_ID;
                if(!empty($cust_id)){
                    if($resID){
                        $param = ['RESV_ID' => $resID];
                        $sql = "SELECT  a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            LEFT JOIN FLXY_DOCUMENTS c ON c.CUST_NAME = a.RESV_NAME WHERE RESV_ID=:RESV_ID:";
                           $data = $this->Db->query($sql,$param)->getRowArray();
                    }else{
                        $param = ['RESV_NAME' => $cust_id];
                        $sql = "SELECT c.DOC_PASS,c.DOC_VACCINE, a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            LEFT JOIN FLXY_DOCUMENTS c ON c.CUST_NAME = a.RESV_NAME WHERE RESV_NAME=:RESV_NAME:";
                            $data = $this->Db->query($sql,$param)->getResultArray();
                    } 
                    if(!empty($data)){
                        
                        $result = responseJson(200,false,"Reservation fetched Successfully",[$data]);
                        echo json_encode($result);die;
                    }else{
                        $result = responseJson(500,true,"No reservation found for this user",[$data]);
                        echo json_encode($result);die;
                    }        
                }else{
                    $result = responseJson(401,true,"No user details Found",[]);
                    echo json_encode($result);die;
                }
            }
        }catch (Exception $ex) {
            echo json_encode($ex->errors());
        }
    }
    /*  FUNCTION : list of accompanying persons.
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */ 
        public function getGuestAccompanyProfiles()
        {
            try{
                // get Token
                $token = getJWTFromRequest();  
                // decoded token information and userdata information from the table.
                $decoded =  validateJWTFromRequest($token);
                // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
                if(!empty($decoded)) {
                    $resID = $decoded['table_info']['RESV_ID'];
                    
                    // an indicator to inform this is accompanying person
                    $sql = "SELECT concat(b.CUST_FIRST_NAME,' ',b.CUST_MIDDLE_NAME,' ',b.CUST_LAST_NAME)NAME,c.DOC_PASS,a.ACCOMP_STATUS FROM FLXY_ACCOMPANY_PROFILE a
                    LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.ACCOMP_CUST_ID
                    LEFT JOIN FLXY_DOCUMENTS c ON c.CUST_NAME = a.ACCOMP_CUST_ID WHERE a.ACCOMP_REF_RESV_ID =:ACCOMP_REF_RESV_ID:";
                    $param = ['ACCOMP_REF_RESV_ID' => $resID ];
                    $data = $this->Db->query($sql,$param)->getResultArray();
                    // print_r($data);
                    // // echo "<pre>";die;
                    // echo $this->Db->getLastQuery()->getQuery();die;
                    if(!empty($data)){
                        $result = responseJson(200,false,"Accompany list for the reservation", [$data]);
                        echo json_encode($result);die; 
                    }
                }else{
                    $result = responseJson(200,false,"No user found", []);
                    echo json_encode($result);die; 
                }
            }catch(Execption $ex){
                echo json_encode($ex->errors());
            }
        }
        /*  FUNCTION : send email to accompany person for uplaod document self 
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */ 
        public function requestSelfUpload()
        {
            try{
                // get Token
                $token = getJWTFromRequest();  
                // decoded token information and userdata information from the table.
                $decoded =  validateJWTFromRequest($token);
                // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
                if(!empty($decoded)) {
                    $resID = $decoded['table_info']['RESV_ID'];
                    $validate = $this->validate([
                        'firstName' => 'required',
                        'lastName' => 'required',
                        'email' => 'required|valid_email',

                    ]);
                    if(!$validate){

                        $validate = $this->validator->getErrors();
                        $result["SUCCESS"] = "-402";
                        $result[]["ERROR"] = $validate;
                        $result = responseJson("-402",$validate);
                        echo json_encode($result);
                        exit;
                    }
                    $firstName = $this->request->getPost("firstName");
                    $lastName = $this->request->getPost("lastName");
                    $email = $this->request->getPost("email");
                    
                    // email sending to the accompany person
                    $param = ['RESV_ID'=> $resID];
                    $sql="SELECT RESV_ID,RESV_NO,RESV_ARRIVAL_DT,RESV_DEPARTURE,RESV_NO_F_ROOM,RESV_FEATURE,CUST_FIRST_NAME,CUST_EMAIL FROM FLXY_RESERVATION,FLXY_CUSTOMER WHERE RESV_ID=:RESV_ID: AND RESV_NAME=CUST_ID";
                    $reservationInfo = $this->Db->query($sql,$param)->getResultArray();
                    $emailCall = new EmailLibrary();
                    $emailResp = $emailCall->requestDocUploadEmail($reservationInfo,$email , $firstName." ".$lastName);
                    if($emailResp){
                        $result = responseJson(200,false,"Email send Successfully", []);
                        echo json_encode($result);die; 
                    }else{
                        $result = responseJson(500,false,"Email sending failed", []);
                        echo json_encode($result);die;
                    }
                }else{
                    $result = responseJson(200,false,"No user found", []);
                    echo json_encode($result);die; 
                }
            }catch(Execption $ex){
                echo json_encode($ex->errors());
            }
        }

    /*  Function : TO UPLOAD DOCUMENTS OF GUEST ON LOGIN
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT: PATH OF UPLAODED DOCUMENT */ 

    public function passportUploadAPI() // add validation for pdf,and size.
    {
        helper('upload_helper');
        try{
            // get Token
            $token = getJWTFromRequest();  
            // decoded token information and userdata information from the table.
            $decoded =  validateJWTFromRequest($token);
            // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
            if(!empty($decoded)) {
                // DOC_PASS - passport DOC_VACCINE- vaccination
                if(!empty($this->request->getFile("passport"))){
                    $fileGetName = 'passport';
                    $file = $this->validate([
                        'passport' => [
                            'uploaded[passport]',
                            'mime_in[passport,image/png, image/jpeg]',
                            'max_size[passport,500]',
                        ]
                    ]);
                }else if(!empty($this->request->getFile("vaccination"))){
                    $fileGetName = 'vaccination';
                    $file = $this->validate([
                        
                        'vaccination' => [
                            'uploaded[vaccination]',
                            'mime_in[vaccination,image/png, image/jpeg,application/pdf]',
                            'max_size[vaccination,500]',
                        ],
                    ]);
                }else{
                    $fileGetName = 'DOC3';
                    $file = $this->validate([
                        'DOC3' => [
                            'uploaded[vaccination]',
                            'mime_in[vaccination,image/png, image/jpeg]',
                            'max_size[vaccination,500]',
                        ],
                    ]);
                }
                // adding validatoion to the files
                if (!$file) {
                    
                    $validate = $this->validator->getErrors();
                    $result["SUCCESS"] = "-402";
                    $result[]["ERROR"] = $validate;
                    $result = responseJson("-402",$validate);
                    echo json_encode($result);
                    exit;
                }
                $doc_file = $this->request->getFile($fileGetName);
                $doc_name = $doc_file->getName();
                $folderPath = "assets/userDocuments/".$fileGetName."/";
                $userID = $decoded['token_info']->data->USR_CUST_ID;
                $doc_up = documentUpload($doc_file ,$doc_name, $userID , $folderPath);
                if($doc_up['SUCCESS'] == 200){
                     // check wheather there is any entry with this user. 
                    $doc_data = $this->Db->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_PASS,DOC_VACCINE,DOC3')->where('CUST_NAME',$userID)->get()->getRowArray();
                    $data = [
                    "CUST_NAME" => $userID,
                    "IS_VERIFY" => 0, 
                    "DOC_CREATE_UID" =>$userID,
                    "DOC_CREATE_DT" => date("d-M-Y"), 
                    "DOC_UPDATE_UID" => $userID,
                    "DOC_UPDATE_DT" => date("d-M-Y") ];
                    $filepath = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    if($fileGetName == 'passport'){

                        $data["DOC_PASS"] = $filepath;

                    }else if($fileGetName == 'vaccination'){

                        $data["DOC_VACCINE"] = $filepath;
                    }else {

                        $data["DOC3"] = $filepath;
                    }
                    $ins = 0;$update_data = 0;
                    if(empty($doc_data)  ){
                        $ins = $this->Db->table('FLXY_DOCUMENTS')->insert($data);
                    }else {
                        $update_data = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_ID',$doc_data['DOC_ID'])->update($data);
                        
                    }
                    if ( $ins || $update_data ){
                        $result = responseJson(200,false,"File uploaded successfully", ["path"=> $filepath]);
                        echo json_encode($result);die; 
                    } else {
                        $result = responseJson(500,true,"Failed to upload image",[]);
                        echo json_encode($result);die;
                    }
                } 
            }
        }catch(Execption $ex){

            echo json_encode($ex->errors());
        }
    }

    /*  FUNCTION : SAVE GUEST DETAILS FROM THE IMAGE UPLOADED.
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT : UPDATED STATUS.     */ 

    public function saveDocDetails()
    {
        try {
            // get Token
            $token = getJWTFromRequest();  
            // decoded token information and userdata information from the table.
            $decoded =  validateJWTFromRequest($token);
            // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
            if(!empty($decoded)) {
                    $validate = $this->validate([
                        'title' => 'required',
                        'firstName' => 'required',
                        'email' => 'required|valid_email',
                        'countryOfResidence' => 'required',
                        'DOB' => 'required',
                        'phn' => 'required',
                        'gender' => 'required',
                        'docType' => 'required',
                        'address1' => 'required',
                        'nationality' => 'required',
                        'docNumber' => 'required',
                        'expiryDate' => 'required',
                        'issueDate' => 'required',
                        'city' => 'required',
                    ]);
                    if(!$validate){

                        $validate = $this->validator->getErrors();
                        $result["SUCCESS"] = "-402";
                        $result[]["ERROR"] = $validate;
                        $result = responseJson("-402",$validate);
                        echo json_encode($result);
                        exit;
                    }
                    $custId = $this->request->getPost("CUST_ID");
                    $CUST_ID = $decoded['token_info']->data->USR_CUST_ID;
                    // checking the id from token and id from post values are same
                    if($custId != $CUST_ID){

                        $validate = "Token details and updating details mismactch";
                        $result["SUCCESS"] = "-400";
                        $result[]["ERROR"] = $validate;
                        $result = responseJson("-400",$validate);
                        echo json_encode($result);
                        exit;
                       
                    }
                    $custId = $CUST_ID;
                    if($this->request->getPost("expiryDate") < $this->request->getPost("issueDate") && $this->request->getPost("expiryDate") <  date("d-M-Y")){
                        $validate = "Your Document is expired";
                        $result["SUCCESS"] = "-402";
                        $result[]["ERROR"] = $validate;
                        $result = responseJson("-402",$validate);
                        echo json_encode($result);
                        exit;
                    }
                    $data = 
                    ["CUST_FIRST_NAME" => $this->request->getPost("firstName"),
                        "CUST_MIDDLE_NAME" => $this->request->getPost("middleName"),
                        "CUST_LAST_NAME" => $this->request->getPost("lastName"),
                        "CUST_TITLE" => $this->request->getPost("title"),
                        "CUST_COUNTRY" => $this->request->getPost("countryOfResidence"),
                        "CUST_DOC_TYPE" => $this->request->getPost("docType"),
                        "CUST_DOC_NUMBER" => $this->request->getPost("docNumber"),
                        "CUST_GENDER" => $this->request->getPost("gender"),
                        "CUST_NATIONALITY" => $this->request->getPost("nationality"),
                        "CUST_DOB" => date("d-M-Y", strtotime($this->request->getPost("DOB"))),
                        "CUST_DOC_EXPIRY" => date("d-M-Y", strtotime($this->request->getPost("expiryDate"))),
                        "CUST_DOC_ISSUE" => date("d-M-Y", strtotime($this->request->getPost("issueDate"))),
                        "CUST_PHONE" => $this->request->getPost("phn"),
                        "CUST_EMAIL" => $this->request->getPost("email"),
                        "CUST_ADDRESS_1" => $this->request->getPost("address1"),
                        "CUST_ADDRESS_2" => $this->request->getPost("address2"),
                        "CUST_CITY" => $this->request->getPost("city"),
                        "CUST_UPDATE_UID" => $custId,
                        "CUST_UPDATE_DT" => date("d-M-Y")
                    ];
                    $update = $this->Db->table('FLXY_CUSTOMER')->where('CUST_ID', $custId)->update($data); 
                    if($update){
                        $result = responseJson(200,true,"updated the guest details",[]);
                        echo json_encode($result);die;
                    }else {
                        $result = responseJson(500,true,"updation Failed",[]);
                        echo json_encode($result);die;
                    }
            }
        } catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

    /*  FUNCTION : DELETE THE UPLOADED DOC
        METHOD: DELETE 
        INPUT : Header Authorization- Token
        OUTPUT : DELETED STATUS.  */ 
    public function deleteUploadedDOC()
    {
        try{
            $doctype = $this->request->getPost("doctype");
            $filename = $this->request->getPost("filename"); // or path
            // get Token
            $token = getJWTFromRequest();  
            // decoded token information and userdata information from the table.
            $decoded =  validateJWTFromRequest($token);
            // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
            if(!empty($decoded)) {
                $CUST_ID = $decoded['token_info']->data->USR_CUST_ID;
                // fetch details from db
                $doc_data = $this->Db->table('FLXY_DOCUMENTS')->select('*')->where(['CUST_NAME' => $CUST_ID])->get()->getRowArray(); 
                if($doctype == 'passport'){
                    $doc_column = 'DOC_PASS';
                    $data['DOC_PASS'] = null;

                }else if($doctype == 'vaccination'){
                    $doc_column = 'DOC_VACCINEPASS';
                    $data['DOC_VACCINE'] = null;    
                }else {         
                    $doc_column = 'DOC3';
                    $data['DOC3'] = null;
                }
                $filename = $doc_data[$doc_column];
                $filename = explode('/',$filename);
                $file = end($filename);
                // Unlink the file from the folder
                $folderPath = "assets/userDocuments/".$doctype."/".$file ;
                if(file_exists( $folderPath )){              
                    if(unlink($folderPath)){
                        $return = $this->Db->table('FLXY_DOCUMENTS')->where(['CUST_NAME' => $CUST_ID])->update($data); 
                        if($return){
                            $data =[ 'VACCINE_DOC_ISNOT'=> 0];
                            // update the vaccine details table with document removed
                            $doc_status_update = $this->Db->table('FLXY_VACCINE_DETAILS')->where(['CUST_ID' => $CUST_ID])->update($data); 
                            if($doc_status_update){

                                $result = responseJson(200,"Documents deleted successfully",$return);
                                echo json_encode($result);  
                            }
                        }else{
                            $result = responseJson("-402","Record not deleted");
                            echo json_encode($result);
                        }
                    }
                }            
            }
        }catch (Exception $e){
            return $this->respond($e->errors());
        }
    }

/* FUNCTION : Vaccination Form
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : Update the Vaccination details in table.  */ 

public function vaccineForm()
{
    try{
        // get Token
        $token = getJWTFromRequest();
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
            $CUST_ID = $decoded['token_info']->data->USR_CUST_ID;
            $validate = $this->validate([

                'vaccineDetail' => 'required',
                'lastVaccineDate' => 'required',
                'VaccineName' => 'required',
                'cerIssuanceCountry' => 'required',
                
            ]);
            if(!$validate){
    
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $vaccineUploadedOrNot = $this->request->getPost("vaccineUploadedOrNot");
            $data = 
            [
                "CUST_ID" => $CUST_ID,
                "VACCINED_DETAILS" => $this->request->getPost("vaccineDetail"), //vaccinated, medicallyExempt, vaccinationLater 
                "LAST_VACCINE_DT" => $this->request->getPost("lastVaccineDate"),
                "VACCINE_NAME" => $this->request->getPost("VaccineName"),
                "ISSUED_COUNTRY" => $this->request->getPost("cerIssuanceCountry"),
                "VACCINE_DOC_ISNOT" => $vaccineUploadedOrNot,
                "VACC_CREATE_UID" =>$CUST_ID,
                "VACC_CREATE_DT" => date("d-M-Y"), 
                "VACC_UPDATE_UID" => $CUST_ID,
                "VACC_UPDATE_DT" => date("d-M-Y")
            ];
            $insert = $this->Db->table('FLXY_VACCINE_DETAILS')->insert($data); 
            if($insert){

                $result = responseJson(200,true,"Added the guest vaccine details",[]);
                echo json_encode($result);die;
            }else {
                $result = responseJson(500,true,"Insertion  Failed",[]);
                echo json_encode($result);die;
            }
        }else{
            $result = responseJson(500," Token is not valid ",[]);
            echo json_encode($result);
        }
    }catch(Exception $e){
        return $this->respond($e->errors());
    }
}
/*  FUNCTION : CHECKIN COMPLETE (Accepting terms and uploading the signature)
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : update the signature.
*/ 
public function acceptAndSignatureUpload()
{
    try{
        // get Token
        $token = getJWTFromRequest();  
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);  
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
            $USR_ID = $decoded['token_info']->data->USR_ID;
            $validate = $this->validate([ 
                'estimatedTimeOfArrival' => 'required', 
                'signature' =>  [
                    'uploaded[signature]',
                    'mime_in[signature,image/png, image/jpeg]',
                    'max_size[signature,500]',
                ], ]);
            if(!$validate){
                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $data = [

                "RESV_ETA" => $this->request->getPost("estimatedTimeOfArrival"), 
                "RESV_UPDATE_UID" => $USR_ID,
                "RESV_UPDATE_DT" => date("d-M-Y")
            ];
            // update the signature in the documents table
            $doc_file = $this->request->getFile('signature');
            $doc_name = $doc_file->getName();
            $folderPath = "assets/userDocuments/signature";
            $cusUserID = $decoded['token_info']->data->USR_CUST_ID;
            $doc_up = documentUpload($doc_file ,$doc_name, $cusUserID , $folderPath);
            if($doc_up['SUCCESS'] == 200){
                // check wheather there is any entry with this user. 
                $doc_data = $this->Db->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_PASS,DOC_VACCINE,DOC3')->where('CUST_NAME',$cusUserID)->get()->getRowArray();
                if(!empty($doc_data)){
                    $filepath = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                    $data = [
                        "SIGNATURE_IMG" => $filepath ,
                        "DOC_UPDATE_UID" => $cusUserID,
                        "DOC_UPDATE_DT" => date("d-M-Y") ];
                    $update_data = $this->Db->table('FLXY_DOCUMENTS')->where('DOC_ID',$doc_data['DOC_ID'])->update($data);
                    if ( $update_data ){
                        $result = responseJson(200,false,"File uploaded successfully", ["path"=> $filepath]);
                        echo json_encode($result);die;
                    } else {
                        $result = responseJson(500,true,"Failed to upload image",[]);
                        echo json_encode($result);die;
                    }
                }else{
                    $result = responseJson(404,true,"User details not found",[]);
                    echo json_encode($result);die;
                }
            }                            
        }         
    }catch(Exception $e){
        return $this->respond($e->errors());
    }    
}

// ----------------------------------------------------------------------- MAINTENANCE REQUEST API -------------------------------------------//

/*  FUNCTION : CREATE MAINTENANCE REQUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT :STATUS OF CREATION  */ 
public function createRequest()
{
    try {
        // get Token
        $token = getJWTFromRequest();
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
            $appartment = $decoded['table_info']['RM_NO'];
            $reservation_no = $decoded['table_info']['RESV_NO'];
            $data['reservation_details'] = ['appartment' => $appartment , "reservation_num" =>$reservation_no ];
            $validate = $this->validate([
                
                'type' => 'required',
                'category' => 'required',
                'subCategory' => 'required',
                'preferredTime' => 'required',
                'preferredDate' => 'required',
                'attachement' =>  [
                    'uploaded[attachement]',
                    'mime_in[attachement,image/png, image/jpeg]',
                    'max_size[attachement,500]',
                ], ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $CUST_ID = $decoded['token_info']->data->USR_CUST_ID;
            $doc_file = $this->request->getFile('attachement');
            $doc_name = $doc_file->getName();
            $folderPath = "assets/maintenance";
            $doc_up = documentUpload($doc_file ,$doc_name, $CUST_ID , $folderPath);
            if($doc_up['SUCCESS'] == 200){
                $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                $data = 
                [
                    "MAINT_TYPE" => $this->request->getPost("type"),
                    "MAINT_CATEGORY" => $this->request->getPost("category"),
                    "MAINT_SUB_CATEGORY" => $this->request->getPost("subCategory"),
                    "MAINT_DETAILS" => $this->request->getPost("details"),
                    "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getPost("preferredDate"))),
                    "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getPost("preferredTime"))),
                    "MAINT_ATTACHMENT" => $attached_path ,
                    "MAINT_STATUS" => "New" ,
                    "MAINT_ROOM_NO" => $appartment ,
                    "MAINT_CREATE_DT" => date("d-M-Y"),
                    "MAINT_CREATE_UID" => $CUST_ID,
                    "MAINT_UPDATE_DT" => date("d-M-Y"),
                    "MAINT_UPDATE_UID" => $CUST_ID
                ];
                $ins = $this->Db->table('FLXY_MAINTENANCE')->insert($data); 
                if($ins){
                    $result = responseJson(200,true,"Maintenance request created",[]);
                    echo json_encode($result);die;

                }else {
                    $result = responseJson(500,true,"Creation Failed",[]);
                    echo json_encode($result);die;
                }
            }else{
                $result = responseJson(500,true,"USER information not available",[]);
                echo json_encode($result);die;
            }
        }
    } catch (Exception $e){
        return $this->respond($e->errors());
    }
}

/*  FUNCTION : LIST MAINTENANCE REQUEST
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF ALL MAINTENANCE REQUEST ADDED.
*/ 

public function listRequests($reqID = null)
{
    try {
        // get Token
        $token = getJWTFromRequest();
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
             $cust_id = $decoded['token_info']->data->USR_CUST_ID;
            //  get appartments and resrvation details from the token
            $appartment = $decoded['table_info']['RM_NO'];
            $reservation_no = $decoded['table_info']['RESV_NO'];
            $data['reservation_details'] = ['appartment' => $appartment , "reservation_num" =>$reservation_no ];
            if(!empty($cust_id)){
                if($reqID){
                    $param = ['MAINT_ID' => $reqID];
                    $sql = "SELECT a.MAINT_ID, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,a.MAINT_SUB_CATEGORY,a.MAINT_DETAILS,a.MAINT_ACKNOWEDGE_TIME,a.MAINT_STATUS , a.MAINT_ROOM_NO FROM FLXY_MAINTENANCE a 
                        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.CUST_NAME
                        WHERE MAINT_ID=:MAINT_ID:";
                    $data = $this->Db->query($sql,$param)->getRowArray();
                }else{
                    $param = ['CUST_NAME' => $cust_id];
                    $sql = "SELECT MAINT_ID, MAINT_SUB_CATEGORY,MAINT_DETAILS,MAINT_ACKNOWEDGE_TIME,MAINT_STATUS, MAINT_ROOM_NO FROM
                    FLXY_MAINTENANCE  WHERE MAINT_CREATE_UID=:CUST_NAME:";
                    $data = $this->Db->query($sql,$param)->getResultArray();
                }
                if(!empty($data)){
                    $result = responseJson(200,false,"Maintenance list fetched Successfully",[$data]);
                    echo json_encode($result);die;
                }else{
                    $result = responseJson(200,false,"No Request List for this user",[$data]);
                    echo json_encode($result);die;
                }          
            } else{
                $result = responseJson(401,true,"No user details Found",[]);
                echo json_encode($result);die;
            }
        }
    }catch (Exception $ex) {
        echo json_encode($ex->errors());
    }
}

/*  FUNCTION : FEEDBACK ADDING FROM GUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : RESPONSE OD ADDITION   */ 

public function addFeedBack()
{
    try {
        // get Token
        $token = getJWTFromRequest(); 
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
            $validate = $this->validate([
                
                'rating' => 'required|min_length[1]|less_than_equal_to[5]|not_in_list[0]',
                
            ]);
            if(!$validate){

                $validate = $this->validator->getErrors();
                $result["SUCCESS"] = "-402";
                $result[]["ERROR"] = $validate;
                $result = responseJson("-402",$validate);
                echo json_encode($result);
                exit;
            }
            $CUST_ID = $decoded['token_info']->data->USR_CUST_ID;
            $data = 
            [
                "FB_RATINGS" => $this->request->getPost("rating"),
                "FB_DESCRIPTION" => $this->request->getPost("comments"),
                "FB_CREATE_DT" => date("d-M-Y"),
                "FB_CREATE_UID" => $CUST_ID,
                "FB_UPDATE_DT" => date("d-M-Y"),
                "FB_UPDATE_UID" => $CUST_ID
            ];
            $ins = $this->Db->table('FLXY_FEEDBACK')->insert($data); 
            if($ins){
                $result = responseJson(200,false,"Feedback Added",[]);
                echo json_encode($result);die;
            }else {
                $result = responseJson(500,true,"Feedback addition Failed",[]);
                echo json_encode($result);die;
            }
        }else{
            $result = responseJson(500,true,"User information not available",[]);
            echo json_encode($result);die;
        }
    } catch (Exception $e){
        return $this->respond($e->errors());
    }
}

/*  FUNCTION : BUS SHUTTLE FETCHING 
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF SHUTTLES         */ 
public function listShuttles($shutleID = null)
{
    try {
        // get Token
        $token = getJWTFromRequest();  
        // decoded token information and userdata information from the table.
        $decoded =  validateJWTFromRequest($token);
        // ["token_info"=> $decodedToken,"table_info"=> $userdata]; output from decoded.
        if(!empty($decoded)) {
                if($shutleID){
                    $param = ['SHUTL_ID' => $shutleID];
                    $sql = "SELECT * FROM FLXY_SHUTTLE_ROUTE WHERE SHUTL_ID=:SHUTL_ID:";
                    $data = $this->Db->query($sql,$param)->getResultArray();
                }else{
                    $sql = "SELECT * FROM FLXY_SHUTTLE";
                    $data = $this->Db->query($sql)->getResultArray();
                }  
                if(!empty($data)){
                    $result = responseJson(200,false,"Shuttles deatils fetched Successfully",[$data]);
                    echo json_encode($result);die;
                }
            }
        }catch (Exception $ex) {
            echo json_encode($ex->errors());
        }
}


// ------------------------------------------------------------------------------------ ADMIN APP APIS ---------------------------------------------------------------------------------------//




    // ----------- END API FOR FLEXIGUEST ----------------//

}