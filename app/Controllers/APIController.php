<?php 

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        $this->request = \Config\Services::request();
    }
    
    
    // ----------- START API FOR FLEXI GUEST --------------//

    // REGISTRATION API
    public function registerAPI()
        {
            $rules = [
                "name" => "required",
                "email" => "required|valid_email|is_unique[users.email]|min_length[6]|max_length[50]",
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

                
                $email = $this->request->getVar("email");

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
                // print_r($data);die;
                
                if ($this->Db->table('FlXY_USERS')->insert($data) ) {
                    
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

                $sql = "SELECT * FROM FlXY_USERS WHERE USR_EMAIL=:email:";
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

    // ----------------------------------------------------------CHECKIN API START -------------------------------------------//
    
    /* 

    Function : List all reservations with details
    METHOD: GET , 
    INPUT : Header Authorization- Token
    OUTPUT : Reservation details like Reservation_no,checkin _date,checkout_date,|apartment_details,apartment no| ,status ,name ,night, adult,childern count, document uploaded or not
    
    */ 
    public function listReservationsAPI()
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

                    $param = ['RESV_NAME' => $cust_id];
                    
                    $sql = "SELECT c.DOC_PASS,c.DOC_VACCINE, a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            LEFT JOIN FLXY_DOCUMENTS c ON c.CUST_NAME = a.RESV_NAME WHERE RESV_NAME=:RESV_NAME:";
                
                    $data = $this->Db->query($sql,$param)->getResultArray();
                    
                    if(!empty($data)){
                        
                        $result = responseJson(200,false,"Reservation fetched Successfully",[$data]);
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

    public function passportUploadAPI()
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
                  
                }else if(!empty($this->request->getFile("vaccination"))){

                    $fileGetName = 'vaccination';
                    
                }else{

                    $fileGetName = 'DOC3';
                    
                }
                

                $doc_file = $this->request->getFile($fileGetName);
                $doc_name = $doc_file->getName();
                
                $folderPath = "assets/userDocuments/".$fileGetName."/";
                $userID = $decoded['token_info']->data->USR_CUST_ID;

                $doc_up = documentUpload($doc_file ,$doc_name, $userID , $folderPath);
                
                if($doc_up['SUCCESS'] == 200){

                     // check wheather there is any entry with this user. 
                    $doc_data = $this->Db->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_PASS,DOC_VACCINE,DOC3')->where('CUST_NAME',$userID)->get()->getRowArray();
                    // echo $this->Db->getLastQuery()->getQuery();die;
                    // print_r($doc_data);die;

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
                    
                        // insert
                        
                        $ins = $this->Db->table('FLXY_DOCUMENTS')->insert($data);

                    }else {
                       
                        // update with id to the next column
                     
                        //  get that row and update with next document.
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

    /* 

    FUNCTION : DELETE THE UPLOADED DOC
    METHOD: DELETE , 
    INPUT : Header Authorization- Token
    OUTPUT : DELETED STATUS.
    
    */ 
    public function deleteUploadedDOC()
    {
        
    }
    
    // ----------- END API FOR FLEXIGUEST ----------------//

}