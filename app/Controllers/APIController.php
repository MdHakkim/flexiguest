<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use  App\Libraries\EmailLibrary;

class APIController extends BaseController
{
    use ResponseTrait;

    private $DB;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
    }

    // ----------- START API FOR FLEXI GUEST --------------//

    // REGISTRATION API
    public function registerAPI()
    {
        $rules = [
            "name" => "required",
            "email" => "required|valid_email|is_unique[FLXY_USERS.USR_EMAIL]|min_length[6]|max_length[50]",
            "phone_no" => "required",
            "password" => 'required|min_length[8]|max_length[255]'

        ];
        $messages = [
            "name" => [
                "required" => "Name is required"
            ],
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format",
                "is_unique" => "Email already Exist"
            ],
            "phone_no" => [
                "required" => "Phone Number is required"
            ],
            "password" => [
                "required" => "password is required"
            ],
            
        ];

        if (!$this->validate($rules, $messages)) {
            $result = responseJson(409, true, $this->validator->getErrors());
            return $this->respond($result);
        } else {
            $email = $this->request->getVar("email");

            // check wheather the email is present in customer table
            $isCustomer_data = $this->DB->table('FLXY_CUSTOMER')->where('CUST_EMAIL', $email)->get()->getRowArray();

            if (empty($isCustomer_data)) {
                $result = responseJson(404, false, ["msg"=>"Sorry , You are not Reserved any room."]);
                return $this->respond($result);
            }

            $data = [
                "USR_NAME" => $this->request->getVar("name"),
                "USR_EMAIL" => $email,
                "USR_PHONE" => $this->request->getVar("phone_no"),
                "USR_PASSWORD" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
                "USR_ROLE" => "GUEST",
                "USR_CUST_ID" => $isCustomer_data['CUST_ID'],
                "USR_CREATED_DT" =>  date("d-M-Y"),
                "USR_UPDATED_DT" => date("d-M-Y")
            ];

            if ($this->DB->table('FLXY_USERS')->insert($data))
                $result = responseJson(200, false, ["msg"=>"Successfully, user has been registered"]);
            else
                $result = responseJson(500, true, ["msg"=>"Failed to create user"]);

            return $this->respond($result);
        }
    }

    // login API
    public function loginAPI()
    {

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
            $result = responseJson(403, true, $this->validator->getErrors());
            return $this->respond($result);
        } else {
            $sql = "SELECT * FROM FLXY_USERS WHERE USR_EMAIL=:email:";
            $param = ['email' => $this->request->getVar("email")];
            $userdata = $this->DB->query($sql, $param)->getRowArray();

            if (!empty($userdata)) {
                if (password_verify($this->request->getVar("password"), $userdata['USR_PASSWORD'])) {
                    // Token created  
                    $token =   getSignedJWTForUser($userdata);
                    $result = responseJson(200, false, ["msg"=>'User logged In successfully'], ['token' => $token, 'user' => $userdata]);
                } else{
                    $result = responseJson(500, true, ["msg"=>'Incorrect details']);
                 }
                return $this->respond($result);
            } else {
                $result = responseJson(404, false, ["msg"=>'User not found']);
                return $this->respond($result);
            }
        }
    }

    public function profileAPI()
    {
        $user = $this->request->user;

        $result = responseJson(200, false, ["msg"=>"User details"], $user);
        return $this->respond($result);
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
        $cust_id = $this->request->user['USR_CUST_ID'];

        if ($resID) {
            $param = ['RESV_ID' => $resID];

            $sql = "SELECT  a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            LEFT JOIN FLXY_DOCUMENTS c ON c.DOC_CUST_ID = a.RESV_NAME WHERE RESV_ID=:RESV_ID:";

            $data = $this->DB->query($sql, $param)->getRowArray();
        } else {
            $param = ['RESV_NAME' => $cust_id];
            $sql = "SELECT c.*, a.RESV_ID,a.RESV_NAME,a.RESV_CHILDREN,a.RESV_ADULTS,a.RESV_NIGHT,a.RESV_ARRIVAL_DT,a.RESV_DEPARTURE,a.RESV_STATUS, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,d.RM_NO,d.RM_DESC FROM FLXY_RESERVATION a 
                            LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.RESV_NAME 
                            LEFT JOIN FLXY_ROOM d ON d.RM_NO = a.RESV_ROOM 
                            LEFT JOIN FLXY_DOCUMENTS c ON c.DOC_CUST_ID = a.RESV_NAME WHERE RESV_NAME=:RESV_NAME:";
            $data = $this->DB->query($sql, $param)->getResultArray();
        }

        if (!empty($data))
            $result = responseJson(200, false, ["msg"=>"Reservation fetched Successfully"], $data);
        else
            $result = responseJson(500, true,["msg"=> "No reservation found for this user"], $data);

        return $this->respond($result);
    }

    /*  FUNCTION : To check the Docs like Proof and vaccination is uploaded or not and verified or not
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function checkDocDetails()
    {
        $userID = $this->request->user['USR_CUST_ID'];

        // an indicator to inform Docs are uploaded and verified.
        $sql = "SELECT concat(a.CUST_FIRST_NAME,' ',a.CUST_MIDDLE_NAME,' ',a.CUST_LAST_NAME)NAME,c.*,b.VACCINE_IS_VERIFY,c.DOC_IS_VERIFY FROM FLXY_CUSTOMER a
                    LEFT JOIN FLXY_VACCINE_DETAILS b ON b.CUST_ID = a.CUST_ID 
                    LEFT JOIN FLXY_DOCUMENTS c ON c.DOC_CUST_ID = a.CUST_ID WHERE DOC_FILE_TYPE='PROOF' AND a.CUST_ID = :CUST_ID:";
        $param = ['CUST_ID' => $userID];
        $data = $this->DB->query($sql, $param)->getResultArray();

        if (!empty($data))
            $result = responseJson(200, false, ["msg"=>"Doc Details fetched Successfully"], $data);
        else
            $result = responseJson(200, false, ["msg"=>"Fetching Failed"], $data);

        return $this->respond($result);
    }

    /*  FUNCTION : list of accompanying persons.
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function getGuestAccompanyProfiles()
    {
        $resID = $this->request->user['RESV_ID'];

        // an indicator to inform this is accompanying person
        $sql = "SELECT concat(b.CUST_FIRST_NAME,' ',b.CUST_MIDDLE_NAME,' ',b.CUST_LAST_NAME)NAME,c.*,a.ACCOMP_STATUS FROM FLXY_ACCOMPANY_PROFILE a
                    LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.ACCOMP_CUST_ID
                    LEFT JOIN FLXY_DOCUMENTS c ON c.DOC_CUST_ID = a.ACCOMP_CUST_ID WHERE a.ACCOMP_REF_RESV_ID =:ACCOMP_REF_RESV_ID: AND DOC_FILE_TYPE='PROOF'";
        $param = ['ACCOMP_REF_RESV_ID' => $resID];
        $data = $this->DB->query($sql, $param)->getResultArray();

        if (!empty($data))
            $result = responseJson(200, false, ["msg"=>"Accompany list for the reservation"], $data);
        else
            $result = responseJson(201, false, ["msg"=>"There is no accompany person"], $data);

        return $this->respond($result);
    }

    /*  FUNCTION : send email to accompany person for uplaod document self 
        METHOD : POST , 
        INPUT  : Header Authorization- Token
        OUTPUT : list and details of the accompanying the persons   */
    public function requestSelfUpload()
    {
        $resID = $this->request->user['RESV_ID'];

        $validate = $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|valid_email',
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson("403", true, $validate);

            return $this->respond($result);
        }

        $firstName = $this->request->getVar("firstName");
        $lastName = $this->request->getVar("lastName");
        $email = $this->request->getVar("email");

        // email sending to the accompany person
        $param = ['RESV_ID' => $resID];
        $sql = "SELECT RESV_ID,RESV_NO,RESV_ARRIVAL_DT,RESV_DEPARTURE,RESV_NO_F_ROOM,RESV_FEATURE,CUST_FIRST_NAME,CUST_EMAIL FROM FLXY_RESERVATION,FLXY_CUSTOMER WHERE RESV_ID=:RESV_ID: AND RESV_NAME=CUST_ID";
        $reservationInfo = $this->DB->query($sql, $param)->getResultArray();
        $emailCall = new EmailLibrary();
        $emailResp = $emailCall->requestDocUploadEmail($reservationInfo, $email, $firstName . " " . $lastName);

        if ($emailResp)
            $result = responseJson(200, false, ["msg"=>"Email send Successfully"]);
        else
            $result = responseJson(500, false, ["msg"=>"Email sending failed"]);

        return $this->respond($result);
    }

    /*  Function : TO UPLOAD DOCUMENTS OF GUEST ON LOGIN
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT: PATH OF UPLAODED DOCUMENT */
    public function docUploadAPI()
    {
        $user = $this->request->user;

        $userID = $user['USR_CUST_ID'];
        $resID = $user['RESV_ID'];

        $file = $this->validate([
            'images' => [
                'uploaded[images]',
                'mime_in[images,image/png, image/jpeg]',
                'max_size[images,500]',
            ],
        ]);

        // adding validatoion to the files
        if (!$file) {
            $validate = $this->validator->getErrors();
            $result = responseJson("403", true, $validate);
            return $this->respond($result);
        }

        $fileNames = '';
        $fileArry = $this->request->getFileMultiple('images');
        $desc = $this->request->getVar("desc");
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'assets/Uploads/userDocuments/proof', $newName);
                    $comma = '';
                    if (isset($fileArry[$key + 1])) {
                        $comma = ',';
                    }
                    $fileNames .= $newName . $comma;
                }
            }
        }

        if (!empty($fileNames)) {
            // check wheather there is any entry with this user. 
            $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_CUST_ID,DOC_FILE_PATH,DOC_RESV_ID')->where(['DOC_CUST_ID' => $userID, 'DOC_RESV_ID' => $resID, 'DOC_FILE_TYPE' => 'PROOF'])->get()->getRowArray();
            $data = [
                "DOC_CUST_ID" => $userID,
                "DOC_IS_VERIFY" => 0,
                "DOC_FILE_PATH" => $fileNames,
                "DOC_FILE_TYPE" => 'PROOF',
                "DOC_RESV_ID" => $resID,
                "DOC_FILE_DESC" => $desc,
                "DOC_CREATE_UID" => $userID,
                "DOC_CREATE_DT" => date("d-M-Y"),
                "DOC_UPDATE_UID" => $userID,
                "DOC_UPDATE_DT" => date("d-M-Y")
            ];

            $ins = 0;
            $update_data = 0;

            if (empty($doc_data))
                $ins = $this->DB->table('FLXY_DOCUMENTS')->insert($data);
            else
                $update_data = $this->DB->table('FLXY_DOCUMENTS')->where(['DOC_CUST_ID' => $userID, 'DOC_RESV_ID' => $resID])->update($data);

            if ($ins || $update_data)
                $result = responseJson(200, false, ["msg"=>"File uploaded successfully"], ["path" => $fileNames]);
            else
                $result = responseJson(500, true, ["msg"=>"Failed to upload image"]);

            return $this->respond($result);
        }

        return $this->respond(responseJson(500, true, "something went wrong"));
    }

    /*  FUNCTION : SAVE GUEST DETAILS FROM THE IMAGE UPLOADED.
        METHOD: POST , 
        INPUT : Header Authorization- Token
        OUTPUT : UPDATED STATUS.     */
    public function saveDocDetails()
    {
        $validate = $this->validate([
            'title' => 'required',
            'firstName' => 'required',
            'email' => 'required|valid_email',
            'cor' => 'required',
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

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson("403", true, $validate);
            return $this->respond($result);
        }

        $CUST_ID = $this->request->user['USR_CUST_ID'];
        if ($this->request->getVar("expiryDate") < $this->request->getVar("issueDate") && $this->request->getVar("expiryDate") <  date("d-M-Y")) {
            $validate = "Your Document is expired";
            $result = responseJson("403", true, $validate);
            return $this->respond($result);
        }

        $data = [
            "CUST_FIRST_NAME" => $this->request->getVar("firstName"),
            "CUST_MIDDLE_NAME" => $this->request->getVar("middleName"),
            "CUST_LAST_NAME" => $this->request->getVar("lastName"),
            "CUST_TITLE" => $this->request->getVar("title"),
            "CUST_DOC_TYPE" => $this->request->getVar("docType"),
            "CUST_DOC_NUMBER" => $this->request->getVar("docNumber"),
            "CUST_GENDER" => $this->request->getVar("gender"),
            "CUST_NATIONALITY" => $this->request->getVar("nationality"),
            "CUST_COR" => $this->request->getVar("cor"),
            "CUST_DOB" => date("d-M-Y", strtotime($this->request->getVar("DOB"))),
            "CUST_DOC_EXPIRY" => date("d-M-Y", strtotime($this->request->getVar("expiryDate"))),
            "CUST_DOC_ISSUE" => date("d-M-Y", strtotime($this->request->getVar("issueDate"))),
            "CUST_PHONE" => $this->request->getVar("phn"),
            "CUST_EMAIL" => $this->request->getVar("email"),
            "CUST_ADDRESS_1" => $this->request->getVar("address1"),
            "CUST_ADDRESS_2" => $this->request->getVar("address2"),
            "CUST_CITY" => $this->request->getVar("city"),
            "CUST_UPDATE_UID" => $CUST_ID,
            "CUST_UPDATE_DT" => date("d-M-Y")
        ];

        $update = $this->DB->table('FLXY_CUSTOMER')->where('CUST_ID', $CUST_ID)->update($data);
        if ($update)
            $result = responseJson(200, true, ["msg"=>"updated the guest details"]);
        else
            $result = responseJson(500, true, ["msg"=>"updation Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : FETCH ALL DETAILS OF GUEST
        METHOD: GET 
        INPUT : Header Authorization- Token
        OUTPUT : CUSTOMER_DATA  */
    public function FetchSavedDocDetails()
    {
        $CUST_ID = $this->request->user['USR_CUST_ID'];

        $filePath = base_url('assets/Uploads/userDocuments/proof');

        $param = ['CUST_ID' => $CUST_ID];
        $sql = "SELECT  b.* ,a.DOC_FILE_PATH FROM FLXY_CUSTOMER b 
                    LEFT JOIN FLXY_DOCUMENTS a ON a.DOC_CUST_ID = b.CUST_ID WHERE b.CUST_ID=:CUST_ID: AND a.DOC_FILE_TYPE ='PROOF'";
        $data = $this->DB->query($sql, $param)->getRowArray();

        if (!empty($data)) {
            $data['DOCS'] = NULL;
            if ($data['DOC_FILE_PATH']) {
                $files_array = explode(',', $data['DOC_FILE_PATH']);
                foreach ($files_array as $key => $value) {
                    $files[] = $filePath . '/' . $value;
                }
                $data['DOCS'] = $files;
            }

            $result = responseJson(200, true, ["msg"=>"Fetch the user details"], $data);
        } else {
            $result = responseJson(500, true, ["msg"=> "user details fetching failed"]);
        }

        return $this->respond($result);
    }

    /*  FUNCTION : DELETE THE UPLOADED DOC( proof)
        METHOD: DELETE 
        INPUT : Header Authorization- Token
        OUTPUT : DELETED STATUS.  */
    public function deleteUploadedDOC()
    {
        $user = $this->request->user;
        $resID = $user['RESV_ID'];
        $CUST_ID = $user['USR_CUST_ID'];

        $doctype = $this->request->getVar("doctype"); //  proof
        $filename = $this->request->getVar("filename"); // or path

        // fetch details from db
        $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('*')->where(['DOC_CUST_ID' => $CUST_ID, 'DOC_RESV_ID' => $resID, 'DOC_FILE_TYPE' => 'PROOF'])->get()->getRowArray();
        $filenames = $doc_data['DOC_FILE_PATH'];
        $filename_array = explode(',', $filenames);
        // inarray then delete else msg 
        if ($pos = array_search($filename, $filename_array)) {
            unset($filename_array[$pos]);

            $folderPath = "assets/Uploads/userDocuments/" . $doctype . "/" . $filename;
            if (file_exists($folderPath)) {
                unlink($folderPath);
            }

            $data = [
                "DOC_CUST_ID" => $CUST_ID,
                "DOC_IS_VERIFY" => 0,
                "DOC_FILE_PATH" => implode(',', $filename_array),
                "DOC_FILE_TYPE" => 'PROOF',
                "DOC_RESV_ID" => $resID,
                "DOC_FILE_DESC" => "",
                "DOC_UPDATE_UID" => $CUST_ID,
                "DOC_UPDATE_DT" => date("d-M-Y")
            ];

            $return = $this->DB->table('FLXY_DOCUMENTS')->where(['DOC_CUST_ID' => $CUST_ID, 'DOC_RESV_ID' => $resID, 'DOC_FILE_TYPE' => 'PROOF'])->update($data);

            if ($return)
                $result = responseJson(200, false, ["msg"=>"Documents deleted successfully"], $return);
            else
                $result = responseJson("500", true, ["msg"=>"Record not deleted"]);

            return $this->respond($result);
        }

        return $this->respond(responseJson("500", true, ["msg"=>"Record not deleted"]));
    }

    public function deleteSpecificVaccine()
    {
        $param = ['CUST_ID' => $this->request->getVar("CUST_ID")];
        $sql = "DELETE FROM FLXY_VACCINE_DETAILS WHERE EXISTS
        (SELECT VACCINE_ID FROM FLXY_VACCINE_DETAILS WHERE CUST_ID=:CUST_ID:)";
        $response = $this->DB->query($sql, $param);
        return $response;
    }

    /* FUNCTION : Vaccination Form
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : Update the Vaccination details in table.  */

    public function vaccineForm()
    {
        $CUST_ID = $this->request->user['USR_CUST_ID'];
        $del =  $this->deleteSpecificVaccine();

        $validate = $this->validate([
            'vaccineDetail' => 'required',
            'lastVaccineDate' => 'required',
            'VaccineName' => 'required',
            'cerIssuanceCountry' => 'required',
            'vaccine' => [
                'uploaded[vaccine]',
                'mime_in[vaccine,image/png, image/jpeg]',
                'max_size[vaccine,500]',
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        // Code for file upload [vaccine is uploading to FLXY_VACCINE_DETAILS table]
        $fileNames = '';
        $fileArry = $this->request->getFileMultiple('vaccine');
        $desc = $this->request->getVar("desc");
        if (!empty($fileArry)) {
            foreach ($fileArry as $key => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'assets/Uploads/userDocuments/vaccination', $newName);
                    $comma = '';
                    if (isset($fileArry[$key + 1])) {
                        $comma = ',';
                    }
                    $fileNames .= $newName . $comma;
                }
            }
        }

        $data = [
            "CUST_ID" => $CUST_ID,
            "VACCINED_DETAILS" => $this->request->getVar("vaccineDetail"), // values will be -- vaccinated, medicallyExempt, vaccinationLater 
            "LAST_VACCINE_DT" => $this->request->getVar("lastVaccineDate"),
            "VACCINE_NAME" => $this->request->getVar("VaccineName"),
            "ISSUED_COUNTRY" => $this->request->getVar("cerIssuanceCountry"),
            "VACCINE_IS_VERIFY" => 0,
            "VACC_FILE_PATH" => $fileNames,
            "VACC_CREATE_UID" => $CUST_ID,
            "VACC_CREATE_DT" => date("d-M-Y"),
            "VACC_UPDATE_UID" => $CUST_ID,
            "VACC_UPDATE_DT" => date("d-M-Y")
        ];

        $insert = $this->DB->table('FLXY_VACCINE_DETAILS')->insert($data);

        if ($insert)
            $result = responseJson(200, true, ["msg"=>"Added the guest vaccine details"]);
        else
            $result = responseJson(500, true, ["msg"=>"Insertion  Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : CHECKIN COMPLETE (Accepting terms and uploading the signature)
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : update the signature.
*/
    public function acceptAndSignatureUpload()
    {
        $user = $this->request->user;
        $USR_ID = $user['USR_ID'];
        $resID = $user['RESV_ID'];

        $validate = $this->validate([
            'estimatedTimeOfArrival' => 'required',
            'signature' =>  [
                'uploaded[signature]',
                'mime_in[signature,image/png, image/jpeg]',
                'max_size[signature,500]',
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $dataRes = [
            "RESV_ETA" => $this->request->getVar("estimatedTimeOfArrival"),
            "RESV_UPDATE_UID" => $USR_ID,
            "RESV_UPDATE_DT" => date("d-M-Y")
        ];

        // update the signature in the documents table
        $doc_file = $this->request->getFile('signature');
        $doc_name = $doc_file->getName();
        $folderPath = "assets/Uploads/userDocuments/signature/";
        $cusUserID = $user['USR_CUST_ID'];
        $doc_up = documentUpload($doc_file, $doc_name, $cusUserID, $folderPath);
        if ($doc_up['SUCCESS'] == 200) {
            // check wheather there is any entry with this user. 
            $doc_data = $this->DB->table('FLXY_DOCUMENTS')->select('DOC_ID,DOC_FILE_PATH,DOC_CUST_ID,DOC_FILE_TYPE')->where(['DOC_CUST_ID' => $cusUserID, 'DOC_FILE_TYPE' => 'SIGN', 'DOC_RESV_ID' => $resID])->get()->getRowArray();

            if (!empty($doc_data)) {
                $filepath = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
                $data = [
                    "DOC_CUST_ID" => $cusUserID,
                    "DOC_IS_VERIFY" => 0,
                    "DOC_FILE_PATH" => $filepath,
                    "DOC_FILE_TYPE" => 'SIGN',
                    "DOC_RESV_ID" => $resID,
                    "DOC_FILE_DESC" => "",
                    "DOC_UPDATE_UID" => $USR_ID,
                    "DOC_UPDATE_DT" => date("d-M-Y")
                ];

                $update_data = $this->DB->table('FLXY_DOCUMENTS')->where('DOC_ID', $doc_data['DOC_ID'])->update($data);
                $res_data = $this->DB->table('FLXY_RESERVATION')->where('RESV_ID', $resID)->update($dataRes);
                // echo $this->DB->getLastQuery()->getQuery();
                // die;

                if ($update_data &&  $res_data)
                    $result = responseJson(200, false, ["msg"=>"File uploaded successfully"], ["path" => $filepath]);
                else
                    $result = responseJson(500, true, ["msg"=>"Failed to upload image or updation in reservation"]);

                return $this->respond($result);
            }

            $result = responseJson(404, true, ["msg"=>"User details not found"]);
            return $this->respond($result);
        }

        return $this->respond(responseJson(500, true, ["msg"=>"Something went wrong"]));
    }

    // ----------------------------------------------------------------------- MAINTENANCE REQUEST API -------------------------------------------//

    /*  FUNCTION : CREATE MAINTENANCE REQUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT :STATUS OF CREATION  */
    public function createRequest()
    {
        $user = $this->request->user;
        $appartment = $user['RM_NO'];
        $reservation_no = $user['RESV_NO'];

        $data['reservation_details'] = ['appartment' => $appartment, "reservation_num" => $reservation_no];
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
            ],
        ]);

        if (!$validate) {
            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $CUST_ID = $user['USR_CUST_ID'];
        $doc_file = $this->request->getFile('attachement');
        $doc_name = $doc_file->getName();
        $folderPath = "assets/Uploads/maintenance";
        $doc_up = documentUpload($doc_file, $doc_name, $CUST_ID, $folderPath);

        if ($doc_up['SUCCESS'] == 200) {
            $attached_path = base_url($folderPath . $doc_up['RESPONSE']['OUTPUT']);
            $data = [
                "MAINT_TYPE" => $this->request->getVar("type"),
                "MAINT_CATEGORY" => $this->request->getVar("category"),
                "MAINT_SUB_CATEGORY" => $this->request->getVar("subCategory"),
                "MAINT_DETAILS" => $this->request->getVar("details"),
                "MAINT_PREFERRED_DT" => date("d-M-Y", strtotime($this->request->getVar("preferredDate"))),
                "MAINT_PREFERRED_TIME" => date("d-M-Y H:i:s", strtotime($this->request->getVar("preferredTime"))),
                "MAINT_ATTACHMENT" => $attached_path,
                "MAINT_STATUS" => "New",
                "MAINT_ROOM_NO" => $appartment,
                "MAINT_CREATE_DT" => date("d-M-Y"),
                "MAINT_CREATE_UID" => $CUST_ID,
                "MAINT_UPDATE_DT" => date("d-M-Y"),
                "MAINT_UPDATE_UID" => $CUST_ID
            ];

            $ins = $this->DB->table('FLXY_MAINTENANCE')->insert($data);
            if ($ins)
                $result = responseJson(200, true, ["msg"=>"Maintenance request created"]);
            else
                $result = responseJson(500, true, ["msg"=>"Creation Failed"]);

            return $this->respond($result);
        }

        $result = responseJson(500, true, ["msg"=>"User information not available"]);
        return $this->respond($result);
    }

    /*  FUNCTION : LIST MAINTENANCE REQUEST
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF ALL MAINTENANCE REQUEST ADDED.
*/

    public function listRequests($reqID = null)
    {

        $user = $this->request->user;
        $cust_id = $user['USR_CUST_ID'];

        //  get appartments and resrvation details from the token
        $appartment = $user['RM_NO'];
        $reservation_no = $user['RESV_NO'];

        $data['reservation_details'] = ['appartment' => $appartment, "reservation_num" => $reservation_no];
        if ($reqID) {
            $param = ['MAINT_ID' => $reqID];
            $sql = "SELECT a.MAINT_ID, b.CUST_FIRST_NAME+' '+b.CUST_MIDDLE_NAME+' '+b.CUST_LAST_NAME as NAME ,a.MAINT_SUB_CATEGORY,a.MAINT_DETAILS,a.MAINT_ACKNOWEDGE_TIME,a.MAINT_STATUS , a.MAINT_ROOM_NO FROM FLXY_MAINTENANCE a 
                        LEFT JOIN FLXY_CUSTOMER b ON b.CUST_ID = a.CUST_NAME
                        WHERE MAINT_ID=:MAINT_ID:";
            $data = $this->DB->query($sql, $param)->getRowArray();
        } else {
            $param = ['CUST_NAME' => $cust_id];
            $sql = "SELECT MAINT_ID, MAINT_SUB_CATEGORY,MAINT_DETAILS,MAINT_ACKNOWEDGE_TIME,MAINT_STATUS, MAINT_ROOM_NO FROM
                    FLXY_MAINTENANCE  WHERE MAINT_CREATE_UID=:CUST_NAME:";
            $data = $this->DB->query($sql, $param)->getResultArray();
        }

        if (!empty($data))
            $result = responseJson(200, false, ["msg"=>"Maintenance list fetched Successfully"], [$data]);
        else
            $result = responseJson(200, false, ["msg"=>"No Request List for this user"]);

        return $this->respond($result);
    }

    /*  FUNCTION : FEEDBACK ADDING FROM GUEST
    METHOD: POST 
    INPUT : Header Authorization- Token
    OUTPUT : RESPONSE OD ADDITION   */

    public function addFeedBack()
    {
        $validate = $this->validate([
            'rating' => 'required|min_length[1]|less_than_equal_to[5]|not_in_list[0]',
        ]);

        if (!$validate) {

            $validate = $this->validator->getErrors();
            $result = responseJson(403, true, $validate);

            return $this->respond($result);
        }

        $cust_id = $this->request->user['USR_CUST_ID'];
        $data = [
            "FB_RATINGS" => $this->request->getVar("rating"),
            "FB_CUST_ID"  =>$cust_id,
            "FB_DESCRIPTION" => $this->request->getVar("comments"),
            "FB_CREATE_DT" => date("d-M-Y H:i"),
            "FB_CREATE_UID" => $cust_id,
            "FB_UPDATE_DT" => date("d-M-Y"),
            "FB_UPDATE_UID" => $cust_id
        ];

        $ins = $this->DB->table('FLXY_FEEDBACK')->insert($data);
        if ($ins)
            $result = responseJson(200, false, ["msg"=>"Feedback Added"]);
        else
            $result = responseJson(500, true, ["msg"=>"Feedback addition Failed"]);

        return $this->respond($result);
    }

    /*  FUNCTION : BUS SHUTTLE FETCHING 
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : LIST OF SHUTTLES         */
    public function listShuttles($shutleID = null)
    {
        if ($shutleID) {
            $param = ['SHUTL_ID' => $shutleID];
            $sql = "SELECT * FROM FLXY_SHUTTLE_ROUTE WHERE SHUTL_ID=:SHUTL_ID:";
            $data = $this->DB->query($sql, $param)->getResultArray();
        } else {
            $sql = "SELECT * FROM FLXY_SHUTTLE";
            $data = $this->DB->query($sql)->getResultArray();
        }
	
	if($data){

        	$result = responseJson(200, false, ["msg"=>"Shuttles deatils fetched Successfully"], [$data]);
        	
	}else{
		$result = responseJson(500, true, ["msg"=>"Shuttles deatils fetched Failed"]);
        	
	}
	return $this->respond($result);
    }

    //------------------------------------------------------------------------------------- HANDBOOK ----------------------------------------------------------------------------------------------//
    /*  FUNCTION : TO GET HANDBOOK URL 
    METHOD: GET 
    INPUT : Header Authorization- Token
    OUTPUT : HANDBOOK URL         */
    public function getHandBookURL()
    {
        $path = base_url('assets/Uploads/handbook/hotel-handbook.pdf');

        if (file_exists($path))
            $result = responseJson(200, false, ["msg"=>"Handbook URL fetched"], ['url' => $path]);
        else
            $result = responseJson(500, false, ["msg"=>"No Handbook file uploaded"]);

        return $this->respond($result);
    }

    // ------------------------------------------------------------------------------------ ADMIN APP APIS ---------------------------------------------------------------------------------------//




    // ----------- END API FOR FLEXIGUEST ----------------//

}
